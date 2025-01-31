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
	$arrDataStoresDateChecked = [];
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

				$arrManagersSupervisor[] = ['position' => 'area_doctor', 'emp_id' => $value['area_doctor'], 'name_desig' => $value['adoc_name_designation']];
				$arrManagersSupervisor[] = ['position' => 'corporate_doctor', 'emp_id' => $value['corporate_doctor'], 'name_desig' => $value['cordoc_name_designation']];
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$arrManagersSupervisor[] = ['position' => 'area_doctor', 'emp_id' => $value['area_doctor'], 'name_desig' => $value['adoc_name_designation']];
				$arrManagersSupervisor[] = ['position' => 'corporate_doctor', 'emp_id' => $value['corporate_doctor'], 'name_desig' => $value['cordoc_name_designation']];
				break;

			}
		}
		// echo 'test'.$valueThreshold. ' - '.$line['total']; exit;

		$line['adocBT'] = 0;
		$line['cordocBT'] = 0;

		$line['adocAT'] = 0;
		$line['cordocAT'] = 0;

		$areaDoctorCheck = true;
		foreach ($arrManagersSupervisor as $value) {
			if($value['position'] == 'area_doctor'){
				if($value['emp_id'] == 'N/A'){
					$areaDoctorCheck = false;
				}
			}
		}

		//compute data from ger_percentage.php
		if($valueThreshold != 'n'){
			if($adocDataBelow > 0){
				$multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];
				$line['adocBT'] = ($areaDoctorCheck) ? $negative.($multiplierValueBelow * $adocDataBelow) : 0;
				$line['cordocBT'] = $negative.($multiplierValueBelow * $cordocDataBelow);
			}
			if($adocDataAbove > 0 && $line['excess'] >= 0){
				$line['adocAT'] = ($areaDoctorCheck) ? $negative.($line['excess'] * $adocDataAbove) : 0;
				$line['cordocAT'] = $negative.($line['excess'] * $cordocDataAbove);
			}
		}

		// print_r($line);


		foreach ($arrManagersSupervisor as $key => $value) {
			$bonus_1 = 0;
			$bonus_2 = 0;
			if($value['position'] == 'area_doctor'){
				$bonus_1 = $line['adocBT'];
				$bonus_2 = $line['adocAT'];
			}
			elseif($value['position'] == 'corporate_doctor'){
				$bonus_1 = $line['cordocBT'];
				$bonus_2 = $line['cordocAT'];
			}
			$total_bonus = $bonus_1 + $bonus_2;


			$payment_date = date('Y-m-d', strtotime($line['payment_date']));
			if(!isset($arrDataStoresDateChecked[$value['emp_id']])){
				$arrDataStoresDateChecked[$value['emp_id']] = [];
			}
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