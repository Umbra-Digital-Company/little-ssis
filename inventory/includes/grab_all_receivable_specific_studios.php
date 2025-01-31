<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrReceivableItems = array();

if ( isset($_SESSION) ) {
	$aimQueryID = $_SESSION['store_code'];
} else {
	$aimQueryID = "";
}

$query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 13 HOUR),
				i.reference_number,
				i.delivery_unique,
				i.stock_from,
				CASE
                    WHEN i.stock_from = 'warehouse' THEN 'warehouse'
					WHEN i.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN i.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sl.store_name_proper != '' THEN LOWER(sl.store_name_proper)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				i.admin_id,
				i.admin_name,
				i.sender,
				i.sender_name,
				et.first_name,
				et.last_name,
				i.product_code,
				TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
				REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
				i.count,
				i.type,
				i.runner_count,
				isig.signature,
				isig.admin_signature,
				i.store_id,
				i.status,
				i.remarks,
				i.item_remark,
				i.reason,
				i.transaction_reason
			FROM 
				inventory_studios i
					LEFT JOIN store_codes_studios sl
						ON sl.store_code = i.stock_from
					LEFT JOIN emp_table et
						ON et.emp_id = i.sender
					LEFT JOIN poll_51_studios p51
						ON p51.product_code = i.product_code
					LEFT JOIN inventory_signature_studios isig
						ON isig.delivery_id = i.reference_number
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from

			WHERE ";
				if ($aimQueryID=='overseer') {
					$query .= "i.type = 'pullout' ";
				} else {
					$query .= "i.store_id = '".$aimQueryID."' ";
				}
								
			$query .= " 
					AND i.status <> 'received'
					AND i.reference_number = '".$_GET['id']."'
				ORDER BY
					i.date_created DESC";

$grabParams = array(
	'date_created',
	'reference_number',	
	'delivery_unique',
	'stock_from_id',
	'stock_from_branch',
	'admin_id',
	'admin_name',
	'sender_id',
	'sender_name',
	'sender_first_name',
	'sender_last_name',
	'product_code',
	'product_style',
	'product_color',
	'count',
	'type',
	'runner_count',
	'signature',
	'admin_signature',
	'stock_to_branch',
	'status',
	'remarks',
	'item_remarks',
	'reason',
	'transaction_reason'
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11,
	 $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParams); $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrReceivableItems[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);	

};

?>