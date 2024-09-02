<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";

// Redirect users to their pages
if($_SESSION['user_login']['userlvl'] == '3') {

	// store account
	header('location: /inventory/store/stock-movement/');
	exit;

} elseif ($_SESSION['user_login']['userlvl'] == '3' && $_SESSION['user_login']['position'] == 'laboratory') {

	// laboratory account
	header('location: /inventory/lab/stock-movement/');
	exit;

} elseif ($_SESSION['store_code'] == 'warehouse' || $_SESSION['store_code'] == 'warehouse_damage' || $_SESSION['store_code'] == 'warehouse_qa') {

	// warehouse account
	header('location: /inventory/warehouse/stock-movement/');
	exit;
	
} elseif ($_SESSION['store_code'] == 'overseer') {

	// admin account
	header('location: /inventory/admin/history/');
	exit;

} elseif ($_SESSION['store_code'] == 'audit') {
	
	// audit account
	header('location: /inventory/audit/');
	exit;
	
} elseif ($_SESSION['store_code'] == 'runner') {
	
	// runner account
	header('location: /inventory/runner/');
	exit;
	
} elseif ($_SESSION['user_login']['userlvl'] == '1') {
	
	// ssis admin account
	header('location: /dashboard/');
	exit;
	
} else {

	header('location: /');
	exit;

};

?>