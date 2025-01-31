<?php

$arrItems = array();

// WHERE
// (p51.product_code LIKE 'SS%' OR p51.product_code LIKE 'M%')
// 	AND p51.product_code <> 'M100'
// 	AND p51.product_code NOT LIKE 'MGC%'

$query =    "SELECT 
				TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
				REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
				p51.product_code,
				IF(
					p51.product_code LIKE 'SS%',
					1,
					2
				) as 'ordering_id'
			FROM 
				poll_51_new p51
					LEFT JOIN inventory_delivery id
						ON id.product_code = p51.product_code
					LEFT JOIN inventory_pullout ip
						ON ip.product_code = p51.product_code
						WHERE
		p51.product_code NOT LIKE 'L0%'

			GROUP BY 
				p51.product_code
			ORDER BY 
				ordering_id ASC,
				item_name ASC";

$grabParams = array(
	'product_style',
	'product_color',
	'product_code',
	'ordering_id'
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParams); $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrItems[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);

};

?>