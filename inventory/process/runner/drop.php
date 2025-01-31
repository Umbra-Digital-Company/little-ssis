<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

if ( isset($_GET['ref_num']) && $_GET['ref_num'] != '' ) {

	//////////////////////////////////////////////////////////////////////////////////// GRAB POST DATA

	// Set data
	$referenceNumber = $_GET['ref_num'];
	$runnerID = $_SESSION['user_login']['id'];

	//////////////////////////////////////////////////////////////////////////////////// UPDATE DATABASE

	$query = 	"UPDATE
				inventory
			SET
				`status` = 'waiting for pickup',
				runner_count = NULL,
				runner_id = NULL,
				runner_name = NULL,
				pick_up_date= NULL
			WHERE
				reference_number = '".mysqli_real_escape_string($conn,$referenceNumber)."'";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);		
		mysqli_stmt_close($stmt);		

	}
	else {

		echo mysqli_error($conn);
		exit;

	};

	//////////////////////////////////////////////////////////////////////////////////// UPDATE SIGNATURE

	$querySign = 	"UPDATE
					inventory_signature
				SET
					runner_signature = NULL
				WHERE
					delivery_id = '".mysqli_real_escape_string($conn,$referenceNumber)."'";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $querySign)) {

		mysqli_stmt_execute($stmt);		
		mysqli_stmt_close($stmt);		

	}
	else {

		echo mysqli_error($conn);
		exit;

	};

} else {

	echo "<script> window.location='/inventory/runner/on-hand/'; </script>";

}

//////////////////////////////////////////////////////////////////////////////////// SEND BACK

echo "<script> window.location='/inventory/runner/on-hand/'; </script>";

?>
