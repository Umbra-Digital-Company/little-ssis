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
				TRIM(LEFT(LOWER(p51.item_name) , LOCATE(' ', LOWER(p51.item_name)) - 1)) AS 'product_style',
				REPLACE(LOWER(p51.item_name),  TRIM(LEFT(LOWER(p51.item_name) , LOCATE(' ', LOWER(p51.item_name)) - 1)), '') AS 'product_color',
				i.count,
				i.input_count,
				i.actual_count_id,
				i.date_count,
				i.date_start,
				i.date_end,
				CASE
                    WHEN i.store_audited = 'warehouse' THEN 'warehouse'
					WHEN sl.store_name_proper != '' THEN LOWER(sl.store_name_proper)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_audited_filter,
				i.auditor,
				LOWER(u.first_name),
				LOWER(u.last_name),
				i.running
			FROM 
				inventory_actual_count_studios i
					LEFT JOIN store_codes_studios sl
						ON sl.store_code = i.store_audited
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.store_audited
					LEFT JOIN poll_51_studios p51
						ON p51.product_code = i.product_code
					LEFT JOIN users u
						ON u.id = i.auditor
			WHERE
				i.date_end = '".$_GET['date']."' AND i.store_audited = '".$_GET['id']."'
			ORDER BY				
				i.date_created DESC";

$grabParams = array(
	'date_created',
	'date_updated',
	'product_code',
	'product_name',
	'product_color',
	'count',
	'input_count',
	'actual_count_id',
	'date_count',
	'date_start',
	'date_end',
	'store_audited',
	'auditor_id',
	'auditor_firstname',
	'auditor_lastname',
	'running'
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9,
	 $result10, $result11, $result12, $result13, $result14, $result15, $result16);

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