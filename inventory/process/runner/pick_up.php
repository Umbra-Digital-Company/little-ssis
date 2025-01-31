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
$referenceNumber = $_POST['ref_num'];
$runnerID = $_SESSION['user_login']['id'];

// Set runner count arrays
$arrCountSorted = array();
$arrPickupCount = $_POST['pickup_count'];
$arrOrderID 	= $_POST['pickup_order_id'];

// Sort through frames and frame counts
for ($i=0; $i < sizeOf($arrPickupCount); $i++) { 

	// Insert runner count
	$arrCountSorted[$i]['count'] = $arrPickupCount[$i];

	// Insert order id
	$arrCountSorted[$i]['id'] = $arrOrderID[$i];
	
};

//////////////////////////////////////////////////////////////////////////////////// UPDATE DATABASE

for ($i=0; $i < sizeOf($arrCountSorted); $i++) { 

	// Set current data
	$curOrderID = $arrCountSorted[$i]['id'];
	$curCount 		= preg_replace('/\s+/', '',$arrCountSorted[$i]['count']);

	$query = 	"UPDATE
				inventory
			SET
				`status` = 'in transit',
				runner_count = '".mysqli_real_escape_string($conn,$curCount)."',
				runner_id = '".mysqli_real_escape_string($conn,$runnerID)."',
				runner_name = '".mysqli_real_escape_string($conn,$_POST['runner_name'])."',
				pick_up_date= now()
			WHERE
				delivery_unique = '".mysqli_real_escape_string($conn,$curOrderID)."'";

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

//////////////////////////////////////////////////////////////////////////////////// UPDATE SIGNATURE

$querySign = 	"UPDATE
				inventory_signature
			SET
				runner_signature = '".mysqli_real_escape_string($conn,$_POST['runner_signature'])."'
			WHERE
				delivery_id = '".mysqli_real_escape_string($conn,$referenceNumber)."';";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querySign)) {

	mysqli_stmt_execute($stmt);		
	mysqli_stmt_close($stmt);		

}
else {

	echo mysqli_error($conn);
	exit;

};

//////////////////////////////////////////////////////////////////////////////////// SEND BACK

echo "<script> window.location='/inventory/runner/orders/'; </script>";

?>
