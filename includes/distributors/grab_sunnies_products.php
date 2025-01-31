<?php

$arrItems = array();

$query =    "SELECT 
				p51.item_name,
				p51.product_code,
				IF(
					p51.product_code LIKE '6%',
					1,
					2
				) as 'ordering_id',
				dpi.stock
			FROM 
				poll_51_studios p51
					LEFT JOIN distributors_products_inventory dpi
						ON dpi.product_code = p51.product_code
			ORDER BY 
				ordering_id ASC,
				item_name ASC";

$grabParams = array(
	'product_name',
	'product_code',
	'ordering_id',
	'product_inventory'
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