<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";

// // Set access for Admin and Store account
if($_SESSION['user_login']['userlvl'] == '8') {

	if($_SESSION['user_login']['store_code']=='warehouse'){
		header('location: /inventory/warehouse/request/');
	}elseif($_SESSION['user_login']['store_code']=='warehouse_qa' || $_SESSION['user_login']['store_code']=='warehouse_damage'){
		header('location: /inventory/warehouse/stock-movement/');
	}else{
		header('location: /');
	}

} else {

	header('location: /');
	exit;

}

?>