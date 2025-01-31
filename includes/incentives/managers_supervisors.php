<?php
	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			}
					
			$specStore .= "o.origin_branch = '".$arrFilterStores[$i]."'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					if( os.status = 'return' AND os.payment='y',
		                (select payment_date from orders_specs 
		                    where 
		                    status != 'cancelled'
		                    and old_po_number = os.po_number
		                    LIMIT 1
		                ),''
	                )as order_checker,
					os.payment_date,
					sl.store_name,
					sl.store_id,
					if(os.old_po_number !='', os.old_po_number,'N/A') as old_po,
					os.status,
					os.po_number,
					REPLACE(p.total, '-','')
					FROM orders o
					LEFT JOIN orders_specs os ON o.order_id = os.order_id
					LEFT JOIN stores_locations sl ON o.origin_branch = sl.store_id
					LEFT JOIN payments p ON os.po_number = p.po_number
					WHERE
						".$qDate."
						".$specStore."
						".$removeStoreIDs."
						AND os.payment = 'y'
						AND os.dispatch_type!='packaging'
						AND os.po_number!=''
						AND os.orders_specs_id!=''
					ORDER BY os.payment_date ASC
						;";

	$grabParams = array(
		'checker_date',
		'payment_date',
		'store_name',
		'store_id',
		'old_po',
		'status',
		'po_number',
		'total'
	);

	$stmt = mysqli_stmt_init($conn);
	$arrOldPoNumber = [];
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			//to check exist po number with return status has new reorder data upon filter
			if($tempArray['old_po'] != 'N/A'){
				$arrOldPoNumber[] = $tempArray['old_po'];
			}

			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	$arrDataManagersIncentives = [];
	foreach ($arrIncentives as $rowline => $line) {
		$negative = '';
		if($line['status'] == 'return'){
			$monthyearCheckerPaymentDate = date("Y-m", strtotime($line['checker_date']));
			$monthyearPaymentDate = date("Y-m", strtotime($line['payment_date']));
			if(!strtotime($line['checker_date'])){
				$line['total'] = 0;
			}elseif($monthyearCheckerPaymentDate == $monthyearPaymentDate && in_array($line['po_number'], $arrOldPoNumber)){
					$line['total'] = 0;
			}elseif($monthyearCheckerPaymentDate != $monthyearPaymentDate && in_array($line['po_number'], $arrOldPoNumber)){
				$negative = '-';
			}
		}
		include 'get_percentage.php';
		$arrManagersData = $arrStoresManagers[$line['store_id']];
		$arrManagersSupervisor = [];
		//get managers supervisor data
		foreach ($arrManagersData as $key => $value) {
			if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$arrManagersSupervisor[] = ['position' => 'sr_area_manager', 'emp_id' => $value['sr_area_manager'], 'name_desig' => $value['sram_name_designation']];
				$arrManagersSupervisor[] = ['position' => 'area_manager', 'emp_id' => $value['area_manager'], 'name_desig' => $value['am_name_designation']];
				$arrManagersSupervisor[] = ['position' => 'area_supervisor', 'emp_id' => $value['area_supervisor'], 'name_desig' => $value['asup_name_designation']];
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$arrManagersSupervisor[] = ['position' => 'sr_area_manager', 'emp_id' => $value['sr_area_manager'], 'name_desig' => $value['sram_name_designation']];
				$arrManagersSupervisor[] = ['position' => 'area_manager', 'emp_id' => $value['area_manager'], 'name_desig' => $value['am_name_designation']];
				$arrManagersSupervisor[] = ['position' => 'area_supervisor', 'emp_id' => $value['area_supervisor'], 'name_desig' => $value['asup_name_designation']];
				break;

			}
		}
		//to check if Eyewear Area Manager AND AREA Manager per store is not N/A
		$areaManagerCheck = true;
		$areaSupervisorCheck = true;
		foreach ($arrManagersSupervisor as $value) {
			if($value['position'] == 'area_manager'){
				if($value['emp_id'] == 'N/A'){
					$areaManagerCheck = false;
				}
			}elseif($value['position'] == 'area_supervisor'){
				if($value['emp_id'] == 'N/A'){
					$areaSupervisorCheck = false;
				}
			}
		}

		$line['sramBT'] = 0;
		$line['amBT'] = 0;
		$line['supareaBT'] = 0;

		$line['sramAT'] = 0;
		$line['amAT'] = 0;
		$line['supareaAT'] = 0;

		//compute data from ger_percentage.php
		if($valueThreshold != 'n'){
			if($sramDataBelow > 0){
				$multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];
				$line['sramBT'] = $negative.($multiplierValueBelow * $sramDataBelow);
				$line['amBT'] = ($areaManagerCheck) ? $negative.($multiplierValueBelow * $amDataBelow) : 0;
				$line['supareaBT'] = ($areaSupervisorCheck) ? $negative.($multiplierValueBelow * $supareaDataBelow) : 0;
			}
			if($sramDataAbove > 0 && $line['excess'] >= 0){
				$line['sramAT'] = $negative.($line['excess'] * $sramDataAbove);
				$line['amAT'] = ($areaManagerCheck) ? $negative.($line['excess'] * $amDataAbove) : 0;
				$line['supareaAT'] = ($areaSupervisorCheck) ? $negative.($line['excess'] * $supareaDataAbove) : 0;
			}
		}

		// print_r($line);

		foreach ($arrManagersSupervisor as $key => $value) {
			$bonus_1 = 0;
			$bonus_2 = 0;
			if($value['position'] == 'sr_area_manager'){
				$bonus_1 = $line['sramBT'];
				$bonus_2 = $line['sramAT'];
			}
			elseif($value['position'] == 'area_manager'){
				$bonus_1 = $line['amBT'];
				$bonus_2 = $line['amAT'];
			}
			elseif($value['position'] == 'area_supervisor'){
				$bonus_1 = $line['supareaBT'];
				$bonus_2 = $line['supareaAT'];
			}
			$total_bonus = ($bonus_1 + $bonus_2);


			//update the total bonus of managers supervisor
			if(!array_key_exists($value['emp_id'], $arrDataManagersIncentives)){
				$arrDataManagersIncentives[$value['emp_id']] = ['name' => $value['name_desig'], 'bonus_1' => $bonus_1, 'bonus_2' => $bonus_2, 'total_bonus' => $total_bonus];
			}else{
				$arrDataManagersIncentives[$value['emp_id']]['bonus_1'] += $bonus_1;
				$arrDataManagersIncentives[$value['emp_id']]['bonus_2'] += $bonus_2;
				$arrDataManagersIncentives[$value['emp_id']]['total_bonus'] += $total_bonus;
			}
		}
		// if($rowline == 1){
		// 	print_r($arrDataManagersIncentives);
		// 	exit;
		// }
	}
?>