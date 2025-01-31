<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB POST DATA

// Set data
$recipient_emp_id 	= $_POST['emp_id'];
$recipient_store_id = $_POST['recipient_branch'];
$referenceNumber 	= $_POST['reference_number'];

// Set frames arrays
$arrFramesSorted = array();
$arrFrames 		 = $_POST['frame_code'];
$arrDeliveredCount  = $_POST['delivered_count'];
$arrReceivedCount = $_POST['received_count'];
$arrDeliveryIDs	 = $_POST['delivery_id'];

// Sort through frames and frame counts
for ($i=0; $i < sizeOf($arrFrames); $i++) { 

	// Insert frame code
	$arrFramesSorted[$i]['product_code'] = $arrFrames[$i];

	// Insert delivered count
	$arrFramesSorted[$i]['delivered_count'] = preg_replace('/\s+/', '',$arrDeliveredCount[$i]);

	// Insert received count
	$arrFramesSorted[$i]['received_count'] = preg_replace('/\s+/', '',$arrReceivedCount[$i]);

	// Insert delivery id
	$arrFramesSorted[$i]['delivery_id'] = $arrDeliveryIDs[$i];

	if($arrFramesSorted[$i]['received_count']!=$arrFramesSorted[$i]['delivered_count']){
		$arrFramesSorted[$i]['variance']='y';
	}else{
		$arrFramesSorted[$i]['variance']='n';
	}
	
	
};

// Grab signature
$recipient_signature = $_POST['signature'];
$status = 'received';

//////////////////////////////////////////////////////////////////////////////////// INSERT INTO DATABASE

// Loop through amount of frames
for ($i=0; $i < sizeOf($arrFramesSorted); $i++) { 

	// Set current data
	$curProductCode = $arrFramesSorted[$i]['product_code'];
	$curCount 		= $arrFramesSorted[$i]['received_count'];
	$curDeliveryID  = $arrFramesSorted[$i]['delivery_id'];

	$query = 	"UPDATE
					inventory_face
				SET
					actual_count = '".mysqli_real_escape_string($conn,$curCount)."',
					status = '".mysqli_real_escape_string($conn,$status)."',
					status_date = NOW(),
					receiver = '".mysqli_real_escape_string($conn,$recipient_emp_id)."',
					variance ='".$arrFramesSorted[$i]['variance']."',
					store_receive_date=now()
				WHERE
					delivery_unique = '".mysqli_real_escape_string($conn,$curDeliveryID)."';";

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

//////////////////////////////////////////////////////////////////////////////////// INSERT SIGNATURE

$query = 	"UPDATE
				inventory_signature_face
			SET
				store_signature = '".mysqli_real_escape_string($conn,$recipient_signature)."'
			WHERE
				delivery_id = '".mysqli_real_escape_string($conn,$referenceNumber)."';";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}
else {

	echo mysqli_error($conn);
	exit;

};

//////////////////////////////////////////////////////////////////////////////////// SEND BACK

echo "<script> window.location='/face/inventory/warehouse/face-history/'; </script>";

?>
