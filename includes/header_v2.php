<?php 
require $sDocRoot."/process/get_user_access.php";

if(isset($_SESSION['user_login']['username'])) {

	$query = 	'SELECT
					locked,
					online
				FROM
					users
				WHERE
					username = "'.$_SESSION['user_login']['username'].'"';

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 	$query)) {

		// mysqli_stmt_bind_param($stmt, 's', $locked);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);
        mysqli_stmt_fetch($stmt);
		mysqli_stmt_store_result($stmt);

		$lock_check = $result1;
		$online = $result2;

		mysqli_stmt_close($stmt);

	}

if($online=='0') {

	header('location: /process/logout.php');
	exit;

};

};

setUserAccess($_SESSION['user_login']['username'],$conn);


// Dashbarod
if($filter_page!='error-page'){
	checkPermission($group_name,$filter_page);
}

// session_start();
// $_SESSION['current_page'] = $page;
function checkPermission($group_name,$page_title){

	if(session_id() == ''){
	    //session has not started
	    session_start();
	}

	//https://www.sunniessystems.com/inventory/store/inventory-lookup/ conflict

	$access = (isset($_SESSION["user_access"]["{$group_name}"]["{$page_title}"])) ? $_SESSION["user_access"]["{$group_name}"]["{$page_title}"] : 0 ;
	
	if($page_title=="inventory_lookup_store"){

		if($page_title=="inventory_lookup_store"){

			$access_store = ( isset($_SESSION["user_access"]["main_menu"]["inventory_lookup_store"]) ) ? $_SESSION["user_access"]["main_menu"]["inventory_lookup_store"] : 0 ;
			$access_admin = ( isset($_SESSION["user_access"]["main_menu"]["inventory_lookup_admin"]) ) ? $_SESSION["user_access"]["main_menu"]["inventory_lookup_admin"] : 0 ;
			$access_virtual_store = ( isset($_SESSION["user_access"]["virtual_store"]["inventory_lookup"]) ) ? $_SESSION["user_access"]["virtual_store"]["inventory_lookup"] : 0 ;
			$access_aim = ( isset($_SESSION["user_access"]["aim"]["inventory_lookup"]) ) ? $_SESSION["user_access"]["aim"]["inventory_lookup"] : 0 ;

			if($access_store==1||$access_admin==1||$access_virtual_store==1||$access_aim==1){
				$access = 1;
			}else{
				$access = 0;
			}
		
		}

	}//END:: IF
	if($access!=1){
		// echo "You don't have access to this page please contact system administrator"; 
		// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];	
		header("Location: https://sunniessystems.com/error/?page=".$page_title);
		exit();
	}
}
//////////////////////////////////////////////////////////////////////////////////// CSS FILES

$header_css = 	'
	<link rel="stylesheet" href="/css/bootstrap.min.v2.css">
	<link rel="stylesheet" href="/css/perfect-scrollbar.css">
	<link rel="stylesheet" href="/css/select2.min.css">
	<link rel="stylesheet" href="/css/version_2/style_20190617_a.css">
	<link rel="stylesheet" href="/css/material-design-iconic-font.min.css">';

//////////////////////////////////////////////////////////////////////////////////// Favicon

// $favicon = '<link rel="shortcut icon" type="image/png" href="/favicon.ico" />';

$favicon = 	'
	<link rel="apple-touch-icon" sizes="57x57" href="/images/favicon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/images/favicon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/images/favicon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/images/favicon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/images/favicon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/images/favicon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/images/favicon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/images/favicon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/images/favicon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/images/favicon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon/favicon-16x16.png">
	<link rel="manifest" href="/images/favicon/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/images/favicon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<meta name="robots" content="noindex">';