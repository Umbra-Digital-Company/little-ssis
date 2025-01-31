<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB FRAMES

// Set array
$arrFrames = array();

$query = 	"SELECT 
				TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
				REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
				p51.product_code,
				p51.collection,
				p51.general_color,
				p51.material
			FROM 
				poll_51_new p51
			WHERE 
				p51.item_code!='LENS001'
					AND p51.product_code NOT LIKE 'HS0%'
					AND p51.product_code NOT LIKE 'CP%'
					AND price!='0'
					AND product_code LIKE '%SS1%'  
			ORDER BY 
				item_name ASC";

$grabParams = array(
	"product_style",
    "product_color",
    "product_code",
    "collection",
    "general_color",
    "material"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParams); $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrFrames[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    

}
else {

	showMe(mysqli_error($conn));

};

?>