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
$sender_emp_id = $_POST['emp_id'];
$sender_emp_name = $_POST['emp_name'];
$sender_branch_id = $_POST['stock_from_id'];
$recipient_store_id = $_POST['recipient_branch'];

// get the type of process ( stock_transfer or inter_branch )
$process_type = "stock_transfer";

// Generate delivery ID
$generate_id = '0123456789';
$deliveryUnique = "";

for ($i=0; $i < 6; $i++) { 

	$deliveryUnique .= $generate_id[rand(0, (strlen($generate_id)-1))];

};

$deliveryID = date('YmdHis').$deliveryUnique;

// Set frames arrays
$arrFramesSorted = array();
$arrFrames 		 = $_POST['frame_code'];
$arrFramesCount  = $_POST['frame_count'];

// Sort through frames and frame counts
for ($i=0; $i < sizeOf($arrFrames); $i++) { 

	// Insert frame code
	$arrFramesSorted[$i]['product_code'] = $arrFrames[$i];

	// Insert frame count
	$arrFramesSorted[$i]['count'] = $arrFramesCount[$i];
	
};

// Grab signature
$sender_signature = $_POST['signature'];
$status = 'for approval';

$remarks = $_POST['sender_remarks'];

//////////////////////////////////////////////////////////////////////////////////// INSERT INTO DATABASE

// Loop through amount of frames
for ($i=0; $i < sizeOf($arrFramesSorted); $i++) { 

	// Set current data
	$curProductCode = $arrFramesSorted[$i]['product_code'];
	$curCount 		= $arrFramesSorted[$i]['count'];
	$curDeliveryID  = $deliveryID.($i+1);

	// Check to see if blank
	if($curProductCode != '') {

		$query = 	"INSERT INTO 
							inventory (
								reference_number,
								delivery_unique,
								store_id,
								product_code,
								count,
								status,
								status_date,
								stock_from,
								admin_id,
								admin_name,
								type,
								remarks,
								requested
							)
						VALUES(
							'".mysqli_real_escape_string($conn,$deliveryID)."',	
							'".mysqli_real_escape_string($conn,$curDeliveryID)."',	
							'".mysqli_real_escape_string($conn,$recipient_store_id)."',	
							'".mysqli_real_escape_string($conn,$curProductCode)."',	
							'".mysqli_real_escape_string($conn,$curCount)."',	
							'".mysqli_real_escape_string($conn,$status)."',	
							NOW(),	
							'".mysqli_real_escape_string($conn,$sender_branch_id)."',
							'".mysqli_real_escape_string($conn,$sender_emp_id)."',
							'".mysqli_real_escape_string($conn,$sender_emp_name)."',
							'".$process_type."',
							'".mysqli_real_escape_string($conn,$remarks)."',
							'y'
						)";

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
	
};

//////////////////////////////////////////////////////////////////////////////////// INSERT SIGNATURE

$query = 	"INSERT INTO 
				inventory_signature(
					delivery_id,
					admin_signature
				)
			VALUES(
				'".mysqli_real_escape_string($conn,$deliveryID)."',	
				'".mysqli_real_escape_string($conn,$sender_signature)."'
			)
			ON DUPLICATE KEY UPDATE
				delivery_id = VALUES(delivery_id),
				admin_signature = VALUES(signature);";

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

echo "<script> window.location='/inventory/admin/history/'; </script>";

?>
