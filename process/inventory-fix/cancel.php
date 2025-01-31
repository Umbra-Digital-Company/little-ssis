<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();
// Required includes
require $sDocRoot."/includes/connect.php";

if(!isset($_SESSION['dashboard_login'])){
	echo 'Invalid session';
	exit;
}
// Set POST DATA

$ref_num = mysqli_real_escape_string($conn, $_POST['ref_num']);

$query = 	'UPDATE
				inventory
				SET status = "cancelled", status_date = now()
			WHERE
				reference_number = "'.$ref_num.'";';

// echo $query;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

echo 'Successfully cancelled.';

?>
