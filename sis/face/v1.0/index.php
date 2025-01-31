<?php

////////////////////////////////////////////////////////////////

session_save_path($_SERVER["DOCUMENT_ROOT"] . "/cgi-bin/tmp");
session_start();
date_default_timezone_set('Asia/Manila');

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot . "/includes/connect.php";
include($sDocRoot . "/sis/face/func/functions.php");

////////////////////////////////////////////////////////////////

$page = '';
if (isset($_GET['page'])) {
	$page = $_GET['page'];
}

if (!isset($_SESSION)) {
	session_start();
}

////////////////////////////////////////////////////////////////

$arrTranslate = grabLanguageTags();

////////////////////////////////////////////////////////////////

?>
<!doctype html>
<html>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title>SIS for FACE</title>

	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/slick.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/style.css?v=<?= date('YmdHis') ?>" />

	<?php if (isset($_SESSION['userlvl']) && $_SESSION['userlvl'] == '1') { ?>
		<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/doctor.css?v=<?= date('YmdHis') ?>" />
	<?php } ?>

	<script type="text/javascript" src="<?= get_url('js') ?>/jquery.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/tether.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/select2.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/signature.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/slick.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/set_location.js?v=<?php echo date('YmdHis'); ?>"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/ssis_functions.js?v=<?php echo date('YmdHis'); ?>"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/main.js?v=<?php echo date('YmdHis'); ?>"></script>

	<style>
		.lang-opt {
			position: absolute;
			right: 20px;
			padding: 5px 0;
			margin-top: 15px;
			background: #f7f7f7;
			text-align: right;
			-webkit-border-radius: 8px;
			border-radius: 8px;
			display: none;
		}

		.lang-opt a {
			display: block;
			padding: 5px 10px;
		}

		.language {
			cursor: pointer;
		}
	</style>

</head>
<script>
	// document.addEventListener('contextmenu', (e) => e.preventDefault());

	// function ctrlShiftKey(e, keyCode) {
	//   return e.ctrlKey && e.shiftKey && e.keyCode === keyCode.charCodeAt(0);
	// }

	// document.onkeydown = (e) => {
	//   // Disable F12, Ctrl + Shift + I, Ctrl + Shift + J, Ctrl + U
	//   if (
	//     event.keyCode === 123 ||
	//     ctrlShiftKey(e, 'I') ||
	//     ctrlShiftKey(e, 'J') ||
	//     ctrlShiftKey(e, 'C') ||
	//     (e.ctrlKey && e.keyCode === 'U'.charCodeAt(0))
	//   )
	//     return false;
	// };
</script>

<body class="page-<?= $page ?> <?= (isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES') ? 'customer-page' : '' ?>">

	<?php if (!isset($_SESSION['login']) == 'YES') : ?>

		<?php include("modules/login.php"); ?>

	<?php else : ?>

		<div class="container">
			<?php include("modules/main.php"); ?>

		</div>

	<?php endif; ?>

	<div class="d-none" id="session">
		<pre>
			<?= print_r($_SESSION) ?>
		</pre>
		<script>
			// console.log($('#session pre').html());
		</script>
	</div>

</body>

</html>