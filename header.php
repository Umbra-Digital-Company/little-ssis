<?php

function get_header( $page = "", $page_spec = NULL ) {

	$version 	= date('YmdHis');
	$body_class = ( $page != "" ) ? "ssis-".strtolower(str_replace(' ', '-', $page)) : "";
	$title 		= ( $page != "" ) ? ucwords(str_replace('-',' ',$page)) . " - Sunnies Specs Integrated System" : "Sunnies Specs Integrated System";

	if($page_spec == 'login') {

		$title = 'System';

	};

	?> 
	
	<!DOCTYPE html>
	<html>
		<head>
			<meta charset="utf-8">
			<meta http-equiv="X-UA-Compatible" content="IE=edge">
			<meta name="viewport" content="width=device-width, minimal-ui, initial-scale=1, user-scalable=1.0, minimum-scale=1.0, maximum-scale=1.0">
			<meta name="apple-mobile-web-app-capable" content="yes" />
			<meta name="robots" content="noindex">

			<title><?= $title ?></title>

			<link rel="apple-touch-icon" sizes="57x57" href="/images/favicon-v2/apple-icon-57x57.png">
			<link rel="apple-touch-icon" sizes="60x60" href="/images/favicon-v2/apple-icon-60x60.png">
			<link rel="apple-touch-icon" sizes="72x72" href="/images/favicon-v2/apple-icon-72x72.png">
			<link rel="apple-touch-icon" sizes="76x76" href="/images/favicon-v2/apple-icon-76x76.png">
			<link rel="apple-touch-icon" sizes="114x114" href="/images/favicon-v2/apple-icon-114x114.png">
			<link rel="apple-touch-icon" sizes="120x120" href="/images/favicon-v2/apple-icon-120x120.png">
			<link rel="apple-touch-icon" sizes="144x144" href="/images/favicon-v2/apple-icon-144x144.png">
			<link rel="apple-touch-icon" sizes="152x152" href="/images/favicon-v2/apple-icon-152x152.png">
			<link rel="apple-touch-icon" sizes="180x180" href="/images/favicon-v2/apple-icon-180x180.png">
			<link rel="icon" type="image/png" sizes="192x192"  href="/images/favicon-v2/android-icon-192x192.png">
			<link rel="icon" type="image/png" sizes="32x32" href="/images/favicon-v2/favicon-32x32.png">
			<link rel="icon" type="image/png" sizes="96x96" href="/images/favicon-v2/favicon-96x96.png">
			<link rel="icon" type="image/png" sizes="16x16" href="/images/favicon-v2/favicon-16x16.png">
			<link rel="manifest" href="/images/favicon-v2/manifest.json">
			<link rel="shortcut icon" href="/images/favicon-v2/favicon.ico" type="image/x-icon">			
			
			<link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
			<link rel="stylesheet" type="text/css" href="/assets/css/jquery-ui.min.css" />
			<link rel="stylesheet" type="text/css" href="/css/perfect-scrollbar.css" />
			<link rel="stylesheet" type="text/css" href="/css/select2.min.css" />
			<?php if ( $page=='ssis' ) { ?>
				<link rel="stylesheet" type="text/css" href="/ssis/assets/css/style.css?v=<?= $version ?>" />
			<?php } else { ?>
				<link rel="stylesheet" type="text/css" href="/assets/css/main.css?v=<?= $version ?>" />
			<?php } ?>

			<script type="text/javascript" src="/assets/js/jquery.min.js"></script>
			<script type="text/javascript" src="/assets/js/jquery-ui.min.js"></script>

			<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
			<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

			<!--[if lt IE 9]>
				<script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script>
				<script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
			<![endif]-->
		</head>
		<body class="body-class <?= $body_class ?>">

<?php } ?>