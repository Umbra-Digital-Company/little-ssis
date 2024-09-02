<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrReceivable = array();

if ( isset($_SESSION) ) {
	$aimQueryID = $_SESSION['store_code'];
} else {
	$aimQueryID = "";
}

$query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 13 HOUR),
				DATE_ADD(i.date_updated, INTERVAL 13 HOUR),
				i.reference_number,
				i.delivery_unique,
				IF(
					i.store_id = '".$aimQueryID."',
					'in',
					'out'
				) AS 'direction',
				i.type,
				i.reason,
				i.remarks,
				i.item_remark,
				i.product_code,
				TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
				REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
				i.count,
				i.runner_count,
				i.actual_count,
				i.status,
				i.store_id,
				CASE
                    WHEN i.store_id = 'warehouse' THEN 'warehouse'
					WHEN i.store_id = 'hq' THEN 'Sunnies HQ'
					WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN i.store_id = 'warehouse_qa' THEN 'warehouse QA'
					WHEN i.store_id = 'manufacturer' THEN 'manufacturer'
					WHEN sls.store_name_proper != '' THEN LOWER(sls.store_name_proper)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE i.store_id
				END AS store_name_to,
				i.stock_from,
				CASE
                   	WHEN i.stock_from = 'warehouse' THEN 'warehouse'
					WHEN i.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN i.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN i.stock_from = 'warehouse_qa' THEN 'warehouse QA'
					WHEN i.stock_from = 'manufacturer' THEN 'manufacturer'
					WHEN sl.store_name_proper != '' THEN LOWER(sl.store_name_proper)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				i.admin_id,
				i.admin_name,
				i.sender,
				i.sender_name,
				ae.first_name,
				ae.last_name,
				isig.signature,
				isig.admin_signature,
				i.receiver,
				isig.store_signature,
				aer.first_name,
				aer.last_name,
				i.transaction_reason,
				i.requestor
			FROM 
				inventory_studios i
					LEFT JOIN inventory_signature_studios isig
						ON isig.delivery_id = i.reference_number
					LEFT JOIN store_codes_studios sl
						ON sl.store_code = i.stock_from
                    LEFT JOIN store_codes_studios sls
						ON sls.store_code = i.store_id
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
					LEFT JOIN admin_employee ae
						ON ae.emp_no = i.sender
					LEFT JOIN admin_employee aer
						ON aer.emp_no = i.receiver
					LEFT JOIN poll_51_studios p51
						ON p51.product_code = i.product_code
			WHERE
				i.reference_number = '".$_GET['ref_num']."'
			ORDER BY
				i.date_created DESC,
				i.delivery_unique ASC";

$grabParams = array(
	'date_created',
	'date_updated',
	'reference_number',
	'delivery_unique',
	'direction',
	'type',
	'reason',
	'remarks',
	'item_remarks',
	'product_code',
	'product_style',
	'product_color',
	'count',
	'runner_count',
	'actual_count',
	'status',
	'stock_to_id',
	'stock_to_name',
	'stock_from_id',
	'stock_from_branch',
	'admin_id',
	'admin_name',
	'sender_id',
	'sender_name',
	'sender_first_name',
	'sender_last_name',
	'signature',
	'admin_signature',
	'receiver_id',
	'receiver_signature',
	'receiver_firstname',
	'receiver_lastname',
	'transaction_reason',
	'i_requestor'
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33, $result34);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParams); $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrReceivable[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);	

};

?>