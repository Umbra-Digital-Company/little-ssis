
<?php
	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	include($sDocRoot."/v2.0/sis/studios/func/functions.php");
?>

<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />

	<title>Sunnies Studios Integrated System</title>

	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/bootstrap.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/jquery-ui.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/select2.min.css" />
	<link rel="stylesheet" type="text/css" href="<?= get_url('css') ?>/style.css?v=<?= date('YmdHis') ?>" />
	<script type="text/javascript" src="<?= get_url('js') ?>/jquery.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/jquery-ui.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/tether.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/select2.min.js"></script>
	<script type="text/javascript" src="<?= get_url('js') ?>/bootstrap.min.js"></script>
</head>

<body>

	<div class="d-flex justify-content-center">
		
		<div class="card">
			<div class="card-header">Order Confirmation</div>
		  <div class="card-body">
		  	<p>ORDER ID : <?= $_GET['order_id'] ?></p><br>
		  	<div class="d-flex justify-content-between">
			  	<a href="/dispatch" style="color: #fff; margin-right: 30px;"><button type="button" class="btn btn-primary">Go to dispatch</button></a>
			  	<a href="/v2.0/sis/studios/vn/?page=store-home" style="color: #fff"><button type="button" class="btn btn-primary">Go to Home Page</button></a>
			  </div>
		  </div>
		</div>
	</div>

</body>
</html>