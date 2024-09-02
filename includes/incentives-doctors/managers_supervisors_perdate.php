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
					os.payment_date,
					sl.store_name,
					sl.store_id,
					os.po_number,
					p.total
					FROM orders o
					LEFT JOIN orders_specs os ON o.order_id = os.order_id
					LEFT JOIN stores_locations sl ON o.origin_branch = sl.store_id
					LEFT JOIN payments p ON os.po_number = p.po_number
					WHERE
						".$qDate."
						".$specStore."
						".$removeStoreIDs."
						AND os.payment = 'y'
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.dispatch_type!='packaging'
						AND os.po_number!=''
						AND os.orders_specs_id!=''
					ORDER BY os.payment_date ASC
						;";

	$grabParams = array(
		'payment_date',
		'store_name',
		'store_id', 
		'po_number',
		'total'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	$arrDataManagersIncentives = [];
	$arrDataStoresDateChecked = [];
	foreach ($arrIncentives as $rowline => $line) {

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
		// echo 'test'.$valueThreshold. ' - '.$line['total']; exit;

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
				$line['sramBT'] = $multiplierValueBelow * $sramDataBelow;
				$line['amBT'] = $multiplierValueBelow * $amDataBelow;
				$line['supareaBT'] = $multiplierValueBelow * $supareaDataBelow;
			}
			if($sramDataAbove > 0 && $line['excess'] >= 0){
				$line['sramAT'] = $line['excess'] * $sramDataAbove;
				$line['amAT'] = $line['excess'] * $amDataAbove;
				$line['supareaAT'] = $line['excess'] * $supareaDataAbove;
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
			$total_bonus = $bonus_1 + $bonus_2;


			$payment_date = date('Y-m-d', strtotime($line['payment_date']));

			if(!in_array($payment_date, $arrDataStoresDateChecked[$value['emp_id']])){

				$arrDataStoresDateChecked[$value['emp_id']][] = $payment_date;
				$arrDataManagersIncentives[$value['emp_id']][$payment_date] = 
					[
						'name' => $value['name_desig'],
						'bonus_1' => $bonus_1,
						'bonus_2' => $bonus_2,
						'total_bonus' => $total_bonus,
						'date' => $payment_date
					];
			}else{
				$arrDataManagersIncentives[$value['emp_id']][$payment_date]['bonus_1'] += $bonus_1;
				$arrDataManagersIncentives[$value['emp_id']][$payment_date]['bonus_2'] += $bonus_2;
				$arrDataManagersIncentives[$value['emp_id']][$payment_date]['total_bonus'] += $total_bonus;
			}
		}
		// if($rowline == 1){
		// 	print_r($arrDataManagersIncentives);
		// 	exit;
		// }
	}
?>