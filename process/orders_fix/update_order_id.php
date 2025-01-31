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

$store_id = mysqli_real_escape_string($conn, $_POST['store_id']);
$origin_branch = mysqli_real_escape_string($conn, $_POST['origin_branch']);

$arrSet = [];
($store_id != '') ? $arrSet[] = 'store_id = "'.$store_id.'"' : '';
($origin_branch != '') ? $arrSet[] = 'origin_branch = "'.$origin_branch.'"' : '';

$query = 	'UPDATE
				orders
			SET '.implode(',', $arrSet).'
			WHERE
				order_id = "'.mysqli_real_escape_string($conn, $_POST['order_id']).'"';

//echo $query;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

echo 'Successfully updated.';

?>
