<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

if ( isset($_GET['ref']) && $_GET['ref']!='' ) {

	$ref = $_GET['ref'];

	$query = 	"UPDATE
				inventory
			SET
				`status` = 'requested',
				approved_date=now()
			WHERE
				reference_number = '".$ref."'";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);		
	    mysqli_stmt_close($stmt);		

	}
	else {

		echo mysqli_error($conn);
		exit;

	};

	if ( $_SESSION['store_code']=='overseer' ) {

		echo "<script> window.location='/inventory/admin/request-approval/'; </script>";

	} else {

		echo "<script> window.location='/inventory/dashboard/request/'; </script>";

	}

} else {

	if ( $_SESSION['store_code']=='overseer' ) {

		header('Location: /inventory/admin/request-approval/');
		exit;

	} else {

		header('Location: /inventory/dashboard/request/');
		exit;

	}

}

?>