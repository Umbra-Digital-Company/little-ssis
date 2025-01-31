<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrVariance = array();

if ( isset($_SESSION) ) {
	$aimQueryID = $_SESSION['store_code'];
} else {
	$aimQueryID = "";
}

 $query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 12 HOUR),
				i.reference_number,
				SUM(i.count) as 'total_items',
				i.stock_from,
                CASE
				WHEN stock_from = 'warehouse' THEN 'warehouse'
					WHEN stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sl.store_name_proper != '' THEN LOWER(sl.store_name_proper)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				i.sender,
				et.first_name,
				et.last_name,
				i.type,
                i.variance,
				IF(
					i.store_id = '".$aimQueryID."',
					'in',
					'out'
				) AS 'direction',
                CASE
					WHEN i.store_id = 'warehouse' THEN 'warehouse'
					WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sls.store_name_proper != '' THEN LOWER(sls.store_name_proper)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE ''
				END AS store_name_to
			FROM 
				inventory_face i
					LEFT JOIN store_codes_face sl
						ON sl.store_code = i.stock_from
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
					LEFT JOIN emp_table et
						ON et.emp_id = i.sender
					LEFT JOIN store_codes_face sls
						ON sls.store_code = i.store_id
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
			WHERE
			(i.store_id = '".$aimQueryID."' 
                OR
                i.stock_from = '".$aimQueryID."' )
					AND i.status = 'received'
                    and variance='y'	
									
			GROUP BY
				i.reference_number
			ORDER BY
				i.date_created ASC";

$grabParams = array(
	'date_created',
	'reference_number',
	'total_items',
	'stock_from_id',
	'stock_from_branch',
	'sender_id',
	'sender_first_name',
	'sender_last_name',
    'type',
    'variance',
    'direction',
    'store_to_name'
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParams); $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrVariance[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);	

};

?>