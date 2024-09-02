<?php 

//////////////////////////////////////////////////////////////////////////////////// CSS FILES
if(isset($_SESSION['id'])) {

	$query = 	'SELECT
					locked,
					online
				FROM
					users
				WHERE
					id = "'.$_SESSION['id'].'"';

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