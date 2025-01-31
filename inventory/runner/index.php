<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";

// // Set access for Admin account
if($_SESSION['user_login']['userlvl'] == '11') {

	header('location: /inventory/runner/orders/');
	exit;

} else {

	header('location: /');
	exit;

}



?>