<?php

if ( !isset($_SESSION) ) { 
	session_start();
}

include("../../modules/functions.php");

?>

<div class="overlay-title">
	<div class="d-flex align-items-center">
		<span class="close-overlay" data-reload="no"><img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="close" class="img-fluid"></span>
		<p class="h2">Cancel Order</p>
	</div>
</div>
<div class="overlay-body">
	<p>Hi! if you wish to discontinue your order, please look for <?= ucwords( $_SESSION['name'] ) ?> or anyone from our sales representive for assistance. Thank you and have a nice day!</p>
	<div class="form-group mt-3">
		<input type="password" id="password_confirmation" class="form-control" required>
		<label for="password_confirmation" class="placeholder">Password</label>
	</div>
	<div class="text-center">
		<button class="btn btn-primary" id="check_password">confirm</button>
		<p class="mt-3 text-danger hide wrong-password">Invalid password</p>
	</div>
</div>