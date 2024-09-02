<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set frames arrays
$arrFramesSorted = array();
$arrFrames 		 = $_POST['frame_code'];
$arrFramesCount  = $_POST['frame_count'];
$arrFramesID 	 = $_POST['delivery_unique'];

// Sort through frames and frame counts
for ($i=0; $i < sizeOf($arrFrames); $i++) { 

	// Insert frame code
	$arrFramesSorted[$i]['product_code'] = $arrFrames[$i];

	// Insert frame count
	$arrFramesSorted[$i]['count'] = $arrFramesCount[$i];

	// Insert delivery unique
	$arrFramesSorted[$i]['delivery_unique'] = $arrFramesID[$i];
	
};

// Loop through amount of frames
for ($i=0; $i < sizeOf($arrFramesSorted); $i++) { 

	// Set current data
	$curProductCode = $arrFramesSorted[$i]['product_code'];
	$curCount 		= $arrFramesSorted[$i]['count'];
	$curDeliveryID  = $arrFramesSorted[$i]['delivery_unique'];

	// Check to see if blank
	if($curProductCode != '') {

		$query = 	"UPDATE
			inventory
		SET
			`status` = 'waiting for pickup',
			count = '".mysqli_real_escape_string($conn,$curCount)."'
		WHERE
			delivery_unique = '".$curDeliveryID."'";

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_execute($stmt);		
			mysqli_stmt_close($stmt);		

		}
		else {

			echo mysqli_error($conn);
			exit;

		};

	}

}





echo "<script> window.location='/inventory/warehouse/history/'; </script>";

?>