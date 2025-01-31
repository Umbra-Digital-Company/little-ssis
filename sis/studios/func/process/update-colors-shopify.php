<?php   

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

$arrInit    = file_get_contents('color-swatches.json');
$data_array = json_decode($arrInit, TRUE);
$data_array = $data_array["blocks"];
$arrColors  = array_values($data_array);

// echo '<pre>';
// print_r($arrColors);
// echo '</pre>';
exit;

// Cycle through the colors
for ($i=0; $i < sizeOf($arrColors); $i++) { 

	// Set current data
	$arrCurData   = $arrColors[$i];
	$arrCurTitles = explode(',', $arrCurData['settings']['title']);
	$arrCurHex 	  = $arrCurData['settings']['color_1'];
	$arrCurImg 	  = (isset($arrCurData['settings']['image'])) ? $arrCurData['settings']['image'] : "";

	// Set empty array
	$arrCurColors = array();

	for ($a=0; $a < sizeOf($arrCurTitles); $a++) { 

		$arrCurColors[$a]['title'] = $arrCurTitles[$a];
		$arrCurColors[$a]['hex']   = $arrCurHex;
		$arrCurColors[$a]['image'] = $arrCurImg;
		
	}

	// Cycle through current colors
	for ($a=0; $a < sizeOf($arrCurColors); $a++) { 
	
		$query  =   "INSERT INTO 
						poll_51_shopify_colors (
							name,
							hex_code,
							image_url
						) 
		            VALUES (
		            	'".mysqli_real_escape_string($conn, $arrCurColors[$a]["title"])."',
		            	'".mysqli_real_escape_string($conn, $arrCurColors[$a]["hex"])."',
		            	'".mysqli_real_escape_string($conn, $arrCurColors[$a]["image"])."'
		        	)
		        	ON DUPLICATE KEY UPDATE
		        		name = VALUES(name),
						hex_code = VALUES(hex_code),
						image_url = VALUES(image_url)";

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);		

		}
		else {

			echo mysqli_error($conn);
			exit;
			
		};

	};
	
};

?>