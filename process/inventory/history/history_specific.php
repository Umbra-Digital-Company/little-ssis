<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES
function itemSpecific($arrReceivable){
	global $conn;
	$arrReceivableSpecific = [0=>['TYPE', 'FROM' , 'TO', 'TOTAL SENT', 'TOTAL RECEIVED', 'STATUS', 'REFERENCE NUMBER', 'DATA SENT', 'ITEM NAME','PRODUCT CODE' , 'TRANSFERRED COUNT', 'PICKUP COUNT', 'RECEIVED COUNT','REMARKS', 'REASON REMARKS']];
	if ( isset($_SESSION) ) {
		$aimQueryID = $_SESSION['store_code'];
	} else {
		$aimQueryID = "";
	}

	for($b = 0; $b < count($arrReceivable); $b++){
		$query ="SELECT 
					i.product_code,
					TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
					REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
					i.count,
					i.runner_count,
					i.actual_count,
					i.remarks,
					i.transaction_reason
				FROM 
					inventory i
						LEFT JOIN poll_51 p51
							ON p51.product_code = i.product_code
				WHERE
					i.reference_number = '".$arrReceivable[$b]['reference_number']."'
				ORDER BY
					i.date_created DESC,
					i.delivery_unique ASC";

		$grabParams = array(
			'product_code',
			'product_style',
			'product_color',
			'count',
			'runner_count',
			'actual_count',
			'remarks',
			'transaction_reason'
			
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

				$arrData = [];
				$arrData['type'] = $arrReceivable[$b]['type'];
				$arrData['from'] =  $arrReceivable[$b]['from'];
				$arrData['to'] =  $arrReceivable[$b]['to'];
				$arrData['total_sent'] = $arrReceivable[$b]['total_sent'];
				$arrData['total_received'] = $arrReceivable[$b]['total_received'];
				$arrData['status'] = $arrReceivable[$b]['status'];
				$arrData['reference_number'] = '="'.$arrReceivable[$b]['reference_number'].'"';
				$arrData['data_sent'] = $arrReceivable[$b]['data_sent'];
				$arrData['item_name'] = $tempArray['product_style'].' '.$tempArray['product_color'];
				$arrData['product_code'] = $tempArray['product_code'];
				$arrData['transferred_count'] = $tempArray['count'];
				$arrData['pickup_count'] = $tempArray['runner_count'];
				$arrData['received_count'] = $tempArray['actual_count'];
				$arrData['remarks'] = $tempArray['remarks'];
				$arrData['transaction_reason'] = $tempArray['transaction_reason'];
				$arrReceivableSpecific[] = $arrData;

			};

			mysqli_stmt_close($stmt);    
									
		}
		else {

			echo mysqli_error($conn);	

		};
	}
return $arrReceivableSpecific;
	//print_r($arrReceivableSpecific); exit;
}
?>