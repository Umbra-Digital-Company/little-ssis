<meta charset="UTF-8">

<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set POST DATA
$lab_id = $_POST['lab_id_locked'];
$arrStoreIDs = $_POST['storeIDs'];

// Cycle through store IDs array and update database

	// Set array
// 	$arrLabs = array();

// 	$query = "SELECT store_id FROM stores_locations_test WHERE lab_id = '".$lab_id."' ";

// 	$grabParams = array(
// 		"store_id"
// 	);
	
// 	$stmt = mysqli_stmt_init($conn);
// 	if (mysqli_stmt_prepare($stmt, $query)) {

// 		mysqli_stmt_execute($stmt);
// 		mysqli_stmt_bind_result($stmt, $result1);

// 		while (mysqli_stmt_fetch($stmt)) {

// 			$tempArray = array();

// 			for ($i=0; $i < sizeOf($grabParams); $i++) { 

// 				$tempArray = ${'result' . ($i+1)};

// 			};
			
// 			$arrLabs[] = $tempArray;
// 		};

// 		mysqli_stmt_close($stmt);    

// 	}
// 	else {

// 		showMe(mysqli_error($conn));

// 	};
// echo '<pre>';
// print_r($arrLabs);
// print_r($arrStoreIDs);
// $res = arr_diff($arrLabs, $arrStoreIDs);
// echo '<pre>';
// exit;

for ($i=0; $i < sizeOf($arrStoreIDs); $i++) { 

	// Set current ID
	$curStoreID = $arrStoreIDs[$i];

	$query = 	'UPDATE
					stores_locations
				SET
					lab_id = "'.mysqli_real_escape_string($conn, $lab_id).'"
				WHERE
					store_id = "'.$curStoreID.'"';	

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);		
	    mysqli_stmt_close($stmt);		

	};

};

echo '<script> window.location="/store-locations"; </script>';

?>
