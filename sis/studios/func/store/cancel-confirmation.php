<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
require $sDocRoot."/includes/connect.php";
include($sDocRoot."/sis/studios/func/functions.php");
$arrTranslate = grabLanguageTags();

?>

<div class="overlay-title">
	<div class="d-flex align-items-center">
		<span class="close-overlay" data-reload="no"><img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="close" class="img-fluid"></span>
		<p class="h2"><?= $arrTranslate['Cancel Order'] ?></p>
	</div>
</div>
<div class="overlay-body mt-3">
	<!-- <p>Hi! if you wish to discontinue your order, please look for <?= ucwords( $_SESSION['name'] ) ?> or anyone from our sales representive for assistance. Thank you and have a nice day!</p> -->

	<?php if(isset($_SESSION['language_setting']) && $_SESSION['language_setting'] == 'vn') {?>
		<p><?= $arrTranslate['You are about to cancel this order. If you\'d wish to proceed please type "SSIS" in the input below'] ?>.</p>
	<?php }else{ ?>
		<p>You are about to <b><u>cancel</u></b> this order. If you'd wish to proceed please type "SSIS" in the input below.</p>
	<?php } ?>
	
	<div class="form-group mt-3">
		<input type="text" id="password_confirmation" class="form-control" autocomplete="off"  required>
		<label for="password_confirmation" class="placeholder"><?= $arrTranslate['Please type SSIS to confirm'] ?></label>
	</div>
	<div class="text-center">
		<button class="btn btn-primary" id="check_password"><?= $arrTranslate['Confirm'] ?></button>
		<p class="mt-3 text-danger hide wrong-password"><?= $arrTranslate['Invalid Password'] ?></p>
	</div>
</div>