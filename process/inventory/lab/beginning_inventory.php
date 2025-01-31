<meta charset="UTF-8">
<pre>
<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};


$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB POST DATA
// print_r($_POST);
// Set data
$sender_emp_id = $_POST['emp_id'];
$sender_branch_id = "beginning_stock";
$recipient_store_id = $_POST['recipient_branch'];

// get the type of process ( stock_transfer or inter_branch )
$process_type = "";
if ( $recipient_store_id == 'warehouse' ) {
	$process_type = 'stock_transfer';
} else {
	$process_type = 'interbranch';
}

// Generate delivery ID
$generate_id = '0123456789';
$deliveryUnique = "";

for ($i=0; $i < 6; $i++) { 

	$deliveryUnique .= $generate_id[rand(0, (strlen($generate_id)-1))];

};

$deliveryID =date('YmdHis').$deliveryUnique;

// Set frames arrays
$arrFramesSorted = array();
$arrFrames 		 = $_POST['product_code'];
$arrFramesCount  = $_POST['product_stock'];

// Sort through frames and frame counts
for ($i=0; $i < sizeOf($arrFrames); $i++) { 

	// Insert frame code
	$arrFramesSorted[$i]['product_code'] = $arrFrames[$i];

	// Insert frame count
	$arrFramesSorted[$i]['count'] = $arrFramesCount[$i];
	
};

// Grab signature
$sender_signature = $_POST['signature'];
$status = 'received';

//////////////////////////////////////////////////////////////////////////////////// INSERT INTO DATABASE




$querStoreLogged="INSERT INTO inventory_store_setup

								(store_code,`action`,`user_id`) 
								VALUES(
										'".$_SESSION['user_login']['store_code']."',
										'y',
										'".$_SESSION['user_login']['id']."'
									) ";
	$stmt2 = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt2, $querStoreLogged)) {

		mysqli_stmt_execute($stmt2);		
		mysqli_stmt_close($stmt2);		

	}
	else {

		echo mysqli_error($conn);
		exit;

	};

// Loop through amount of frames
for ($i=0; $i < sizeOf($arrFramesSorted); $i++) { 

	// Set current data
	$curProductCode = $arrFramesSorted[$i]['product_code'];
	$curCount 		= $arrFramesSorted[$i]['count'];
	$curDeliveryID  = $deliveryID.($i+1);

	// Check to see if blank
	if($curCount != '0') {

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
								sender,
								type
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
							'".$process_type."'
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
					signature
				)
			VALUES(
				'".mysqli_real_escape_string($conn,$deliveryID)."',	
				'".mysqli_real_escape_string($conn,$sender_signature)."'
			)
			ON DUPLICATE KEY UPDATE
				delivery_id = VALUES(delivery_id),
				signature = VALUES(signature);";

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

echo "<script> window.location='/inventory/lab/history/'; </script>";

?></pre>
