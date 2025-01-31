<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrReceivable = array();

if ( isset($_SESSION) ) {
	$aimQueryID = $_SESSION['store_code'];
} else {
	$aimQueryID = "";
}

 $query =    "SELECT 
				count( DISTINCT reference_number) as total,
              CASE
            WHEN i.store_id = 'warehouse' THEN 'warehouse'
            WHEN i.store_id = 'hq' THEN 'Sunnies HQ'
            WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
            WHEN sls.store_name_proper != '' THEN LOWER(sls.store_name_proper)
            WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
            ELSE ''
            END AS store_name_to
			FROM 
				inventory_studios i
					LEFT JOIN store_codes_studios sl
						ON sl.store_code = i.stock_from
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
                    LEFT JOIN store_codes_studios sls
						ON sls.store_code = i.store_id
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
					LEFT JOIN emp_table et
						ON et.emp_id = i.sender
			WHERE i.store_id = '".$aimQueryID."'
					AND i.status ='in transit'
					AND i.status !='cancelled'
					group by i.reference_number
                    ORDER BY
				i.date_created DESC";

$grabParams = array(
	"count",
    "name"
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2);

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