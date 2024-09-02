<?php
$arrPoll51_items= array();

$query="SELECT 
			TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
			REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
			p51.product_code
			FROM 
				poll_51_new p51
				where p51.item_code!='LENS001'

				and p51.product_code NOT LIKE 'HS0%'
			and p51.product_code NOT LIKE 'CP%'
			and price!='0'
			AND product_code LIKE '%SS1%'  
				ORDER BY 

			item_name ASC

		
		
		";




$grabParams = array(
    'product_style',
    'product_color',
    'product_code');

    $stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrPoll51_items[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

	};

                        ?>