<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";

if(!isset($_SESSION)){
	session_start();
}

if(isset($_POST['feedback']) && $_POST['feedback']!=''){

	$queryFeedback = "UPDATE orders SET feedback='" . mysqli_real_escape_string($conn, $_POST['feedback']) . "' WHERE order_id='" . mysqli_real_escape_string($conn, $_SESSION['order_no']) . "'";
	
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $queryFeedback)) {	

		mysqli_stmt_execute($stmt);		

	};
}

?>