<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrVarianceReport = array();

$aimQueryID = "";
if ( isset($_GET['date']) ) {
	$aimQueryID = $_GET['filterStores'];
} else {
	$aimQueryID = 'warehouse';
}

$query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 13 HOUR),
				DATE_ADD(i.date_updated, INTERVAL 13 HOUR),
				i.product_code,
				COUNT(i.id) AS 'total_items',
				SUM(i.count) AS 'total_variance',
				i.actual_count_id,
				i.date_count,
				i.date_start,
				i.date_end,
				i.store_audited,
				CASE
                    WHEN i.store_audited = 'warehouse' THEN 'warehouse'
					WHEN sl.store_name != '' THEN LOWER(sl.store_name)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_audited_filter,
				i.auditor,
				LOWER(u.first_name),
				LOWER(u.last_name)
			FROM 
				inventory_actual_count i
					LEFT JOIN stores_locations sl
						ON sl.store_id = i.store_audited
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.store_audited
					LEFT JOIN users u
						ON u.id = i.auditor
			GROUP BY
				i.store_audited, i.date_end, i.date_start
			ORDER BY				
				i.date_created DESC";

$grabParams = array(
	'date_created',
	'date_updated',
	'product_code',
	'total_items',
	'total_variance',
	'actual_count_id',
	'date_count',
	'date_start',
	'date_end',
	'branch_id',
	'store_audited',
	'auditor_id',
	'auditor_firstname',
	'auditor_lastname'
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

		$arrVarianceReport[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);	

};

?>