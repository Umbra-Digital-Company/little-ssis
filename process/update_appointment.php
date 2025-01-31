<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";


if ( isset($_GET['status']) && isset($_GET['id']) ) {

	$status = $_GET['status'];
	$id = $_GET['id'];

	$query = "UPDATE so_appointment SET status = '".$status."' WHERE appointment_id = '".$id."'";
	$stmt = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt, $query)) {
		mysqli_stmt_execute($stmt);		
		mysqli_stmt_close($stmt);		
	} else {
		mysql_error($conn);
	};
	
}

header('Location: /appointment/');
exit;

?>