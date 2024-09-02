<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";

// Set access for Laboratory account
if($_SESSION['user_login']['userlvl'] == '3' && $_SESSION['user_login']['position'] == 'laboratory') {

	header('location: /inventory/lab/receive/');
	exit;

} else {

	header('location: /');
	exit;

}



?>