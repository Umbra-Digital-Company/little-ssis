<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrReceivable = array();

if ( isset($_SESSION) ) {
	$aimQueryID = $_SESSION['store_code'];
} else {
	$aimQueryID = "";
}

$query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 12 HOUR),
				i.reference_number,
				IF(
					i.store_id = '".$aimQueryID."',
					'in',
					'out'
				) AS 'direction',
				i.type,
				SUM(i.count) AS 'total_items',
				SUM(i.actual_count) AS 'total_items_received',
				i.status,
				i.store_id,
				CASE
                    WHEN i.store_id = 'warehouse' THEN 'warehouse'
					WHEN i.store_id = 'hq' THEN 'Sunnies HQ'
					WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sls.store_name != '' THEN LOWER(sls.store_name)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE ''
				END AS store_name_to,
				i.stock_from,
				CASE
					WHEN i.stock_from = 'warehouse' THEN 'warehouse'
					WHEN i.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN i.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sl.store_name != '' THEN LOWER(sl.store_name)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				i.sender,
				i.sender_name,
				i.admin_id,
				i.admin_name,
				ae.first_name,
				ae.last_name
			FROM 
				inventory i
					LEFT JOIN stores_locations sl
						ON sl.store_id = i.stock_from
                    LEFT JOIN stores_locations sls
						ON sls.store_id = i.store_id
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
					LEFT JOIN admin_employee ae
						ON ae.emp_no = i.sender
			 ";
				if ($aimQueryID=='overseer') {
					$query .= "WHERE  i.requested='y' AND i.status='for approval' ";
				} elseif ($aimQueryID=='warehouse') {
					$query .= "WHERE  i.admin_id = 'overseer' AND i.status = 'requested' ";
				} else {
					$query .= "WHERE i.admin_id='overseer' AND i.requested='y' AND i.status='for approval' ";
				}
			$query .= "GROUP BY
				i.reference_number
			ORDER BY				
				i.date_created DESC,
				i.delivery_unique ASC";

$grabParams = array(
	'date_created',
	'reference_number',
	'direction',
	'type',
	'total_items',
	'total_items_received',
	'status',
	'stock_to_id',
	'stock_to_name',
	'stock_from_id',
	'stock_from_branch',
	'sender_id',
	'sender_name',
	'admin_id',
	'admin_name',
	'sender_first_name',
	'sender_last_name',
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17);

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