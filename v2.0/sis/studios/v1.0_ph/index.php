<?php 
session_save_path("/var/www/html/cgi-bin/tmp");
session_start();

date_default_timezone_set('Asia/Manila');

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";

include($sDocRoot."/v2.0/sis/studios/func/functions.php");

$page='';
if (isset($_GET['page'])){
	$page = $_GET['page'];
}

if ( !isset($_SESSION) ) {
	session_start();
}

// $_SESSION["store_code"]	= "990";
// $_SESSION["lab_code_pos"] = "1";


$labarray=array(
	'109' => '1',
	'103' =>'2',
	'108' =>'2',
	'116' => '2',
	'101' => '1',
	'124' => '1',
	'110' => '2',
	'107' => '4',
	'121' => '4',
	'127' => '4',
	'129' => '4',
	'106' => '2',
	'104' => '3',
	'117' => '3',
	'120' => '3',
	'130' => '3',
	'115' => '8',
	'118' => '9',
	'113' => '7',
	'128' => '7',
	'119' => '6',
	'111' => '6',
	'125' => '2',
	'126' => '2',
	'114' => '2',
	'123' => '10',
	'102' => '1',
	'112' => '1',
	'105' => '2'
);

$arrTranslate = grabLanguageTags();
?>
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
	.language{
		cursor: pointer;
	}
</style>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title>Sunnies Specs Integrated System</title>

	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/style.css?v=<?= date('YmdHis') ?>" />
	<?php if ( isset($_SESSION['userlvl']) && $_SESSION['userlvl'] == '1' ) { ?>
		<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/doctor.css?v=<?= date('YmdHis') ?>" />
	<?php } ?>

	<script type="text/javascript" src="<?= get_url('js') ?>/jquery.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/tether.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/select2.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/signature.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/set_location.js?v=<?php echo date('YmdHis'); ?>"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/ssis_functions.js?v=<?php echo date('YmdHis'); ?>"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/main.js?v=<?php echo date('YmdHis'); ?>"></script>
</head>

<body class="page-<?= $page ?> <?= ( isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES' ) ? 'customer-page' : '' ?>">

	<?php if ( !isset($_SESSION['login']) == 'YES' ) : ?>

		<?php include("modules/login.php"); ?>
	
	<?php else : ?>

		<div class="container">

			<?php include("modules/main.php");?>
				
		</div>

	<?php endif; ?>

	<div class="d-none" id="session">
		<pre>
			<?= print_r($_SESSION) ?>
		</pre>
		<script>
			console.log($('#session pre').html());
		</script>
	</div>

</body>
</html>