<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";
if(!isset($_SESSION['user_login']['username'])) {
    header("Location: /");
    exit;
}
//include("../includes/grab_cart.php");
$arrOrderSpecsId = explode(",", mysqli_real_escape_string($conn, $_POST['orders_specs_id']));
$query = 'UPDATE orders_face_details SET status="cancelled", status_date = ADDTIME(now(), "12:00:00") WHERE orders_specs_id IN("'.implode('","', $arrOrderSpecsId).'")';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);		
};
?>