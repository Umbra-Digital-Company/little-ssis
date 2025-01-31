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
				i.reference_number,
				SUM(i.count) as 'total_items',
				SUM(i.runner_count) as 'total_items_pickup',
				i.stock_from,
				CASE
                    WHEN i.stock_from = 'warehouse' THEN 'warehouse'
					WHEN i.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN i.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sl.store_name != '' THEN LOWER(sl.store_name)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				CASE
                    WHEN i.store_id = 'warehouse' THEN 'warehouse'
					WHEN i.store_id = 'hq' THEN 'Sunnies HQ'
					WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sls.store_name != '' THEN LOWER(sls.store_name)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE ''
				END AS store_name_to,
				i.admin_id,
				i.admin_name,
				i.sender,
				et.first_name,
				et.last_name,
				i.type,
				i.status
			FROM 
				inventory i
					LEFT JOIN stores_locations sl
						ON sl.store_id = i.stock_from
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
                    LEFT JOIN stores_locations sls
						ON sls.store_id = i.store_id
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
					LEFT JOIN emp_table et
						ON et.emp_id = i.sender
			WHERE ";
				if ($aimQueryID=='overseer') {
					$query .= "i.type = 'pullout'
					AND i.status <> 'received' ";
				} else {
					$query .= "i.store_id = '".$aimQueryID."'
					AND i.status <> 'received' ";
				}
								
			$query .= "GROUP BY
				i.reference_number
			ORDER BY
				i.date_created DESC";

$grabParams = array(
	'date_created',
	'reference_number',
	'total_items',
	'total_items_pickup',
	'stock_from_id',
	'stock_from_branch',
	'stock_to_name',
	'admin_id',
	'admin_name',
	'sender_id',
	'sender_first_name',
	'sender_last_name',
	'type',
	'status'
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14);

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