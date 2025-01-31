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

if($_POST['stock_to_id']=='warehouse'){
	$recipient_store_id="warehouse";
}elseif($_POST['stock_to_id']=='warehouse_qa'){
	$recipient_store_id="warehouse_qa";
}
else{
$recipient_store_id = $_POST['recipient_branch'];
}
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
$arrFrameRemark = $_POST['item_remark'];
$transaction_reason =  mysqli_real_escape_string($conn,$_POST['transaction_reason']);
$transaction_reason = ($transaction_reason == 'Others') ? mysqli_real_escape_string($conn,$_POST['others_reason']) : $transaction_reason;

// Sort through frames and frame counts
for ($i=0; $i < sizeOf($arrFrames); $i++) { 

	// Insert frame code
	$arrFramesSorted[$i]['product_code'] = $arrFrames[$i];

	// Insert frame count
	$arrFramesSorted[$i]['count'] = $arrFramesCount[$i];

	// Insert frame remark
	$arrFramesSorted[$i]['item_remark'] = $arrFrameRemark[$i];
	
};

// Grab signature
$sender_signature = $_POST['signature'];

if($_POST['stock_from_id']=='manufacturer'){

	$status='received';
}
elseif($_POST['stock_from_id']=='warehouse_qa'){

	$status='in transit';
}
else{


	$status = 'in transit';

}



//////////////////////////////////////////////////////////////////////////////////// INSERT INTO DATABASE

// Loop through amount of frames
for ($i=0; $i < sizeOf($arrFramesSorted); $i++) { 

	// Set current data
	$curProductCode = $arrFramesSorted[$i]['product_code'];
	$curCount 		= preg_replace('/\s+/', '',$arrFramesSorted[$i]['count']);
	$curDeliveryID  = $deliveryID.($i+1);
	$damage_reason = $_POST['sender_remarks'];

	// Check to see if blank
	if($curProductCode != '') {

		$query = 	"INSERT INTO 
						inventory_studios (
							reference_number,
							delivery_unique,
							store_id,
							product_code,
							count,
							status,
							status_date,
							stock_from,
							sender,
							sender_name,
							type,
							item_remark,
							remarks,
							runner_count,
							transaction_reason,
							transit_date
						)
					VALUES(
						'".mysqli_real_escape_string($conn,$deliveryID)."',	
						'".mysqli_real_escape_string($conn,$curDeliveryID)."',	
						'".mysqli_real_escape_string($conn,$recipient_store_id)."',	
						'".mysqli_real_escape_string($conn,$curProductCode)."',	
						'".mysqli_real_escape_string($conn,$curCount)."',	
						'".mysqli_real_escape_string($conn,$status)."',	
						NOW(),	
						'".$_POST['stock_from_id']."',	
						'".mysqli_real_escape_string($conn,$sender_emp_id)."',
						'".mysqli_real_escape_string($conn,$sender_emp_name)."',
						'stock_transfer',
						'".mysqli_real_escape_string($conn,$curRemark)."',	
						'".mysqli_real_escape_string($conn,$_POST['remarks'])."',	
						'".mysqli_real_escape_string($conn,$curCount)."',
						'".$transaction_reason."',
						now()
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
				inventory_signature_studios(
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

echo "<script> window.location='/studios/inventory/warehouse/studios-history/'; </script>";

?>
