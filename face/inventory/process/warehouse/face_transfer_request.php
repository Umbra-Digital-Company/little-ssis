<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

$empID = $_POST['emp_id'];
$empName = $_POST['emp_name'];
$refNum = $_POST['reference_number'];

// Set frames arrays
$arrFramesSorted = array();
$arrFrames 		 = $_POST['frame_code'];
$arrFramesCount  = $_POST['frame_count'];
$arrFramesID 	 = $_POST['delivery_unique'];
$arrFramesRemark = $_POST['item_remark'];

// Sort through frames and frame counts
for ($i=0; $i < sizeOf($arrFrames); $i++) { 

	// Insert frame code
	$arrFramesSorted[$i]['product_code'] = $arrFrames[$i];

	// Insert frame count
	$arrFramesSorted[$i]['count'] = $arrFramesCount[$i];

	// Insert delivery unique
	$arrFramesSorted[$i]['delivery_unique'] = $arrFramesID[$i];

	// Insert delivery unique
	$arrFramesSorted[$i]['item_remark'] = $arrFramesRemark[$i];
	
};

// Loop through amount of frames
for ($i=0; $i < sizeOf($arrFramesSorted); $i++) { 

	// Set current data
	$curProductCode = $arrFramesSorted[$i]['product_code'];
	$curCount 		= preg_replace('/\s+/', '',$arrFramesSorted[$i]['count']);
	$curDeliveryID  = $arrFramesSorted[$i]['delivery_unique'];
	$curRemark 		= $arrFramesSorted[$i]['item_remark'];

	// Check to see if blank
	if($curProductCode != '') {

		$query = 	"UPDATE
			inventory_face
		SET
			`status` = 'in transit',
			count = '".mysqli_real_escape_string($conn,$curCount)."',
			sender = '".mysqli_real_escape_string($conn,$empID)."',
			sender_name = '".mysqli_real_escape_string($conn,$empName)."',
			runner_count = '".mysqli_real_escape_string($conn,$curCount)."',
			item_remark = '".mysqli_real_escape_string($conn,$curRemark)."',
			transit_date=now()
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


////////////////////////////////////////////////////////////////// UPDATE SIGNATURE

$querySign = "UPDATE
	inventory_signature_face
SET
	signature = '".mysqli_real_escape_string($conn,$senderSignature)."'
WHERE
	delivery_id = '".$refNum."'";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querySign)) {

	mysqli_stmt_execute($stmt);		
	mysqli_stmt_close($stmt);		

}
else {

	echo mysqli_error($conn);
	exit;

};





echo "<script> window.location='/face/inventory/warehouse/face-history/'; </script>";

?>