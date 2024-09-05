<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
require $sDocRoot."/includes/connect.php";
include($sDocRoot."/sis/studios/func/functions.php");
$arrTranslate = grabLanguageTags();

?>



<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content cancel" style="padding: 20px;">
			<div class="modal-body">
				<div class="text-center" style="margin-bottom: 20px;">
					<span class="font-bold text-center" style="font-size: 18px;">Are you sure you want to leave?</span>
				</div>
				<div class="text-center">
					<?php if(isset($_SESSION['language_setting']) && $_SESSION['language_setting'] == 'vn') {?>
					<p style="font-size: 18px;"><?= $arrTranslate['You are about to cancel this order. If you\'d wish to proceed please type "SSIS" in the input below'] ?>.</p>
					<?php }else{ ?>
						<p>You are about to cancel this order. If you'd wish to proceed please type "SSIS" in the input below.</p>
					<?php } ?>
				</div>
				<div class="form-group mt-3">
					<input type="password" id="password_confirmation" class="form-control" required>
					<label for="password_confirmation" class="placeholder">Type SSIS to confirm</label>
				</div>
				<div class="text-center mt-4">
					<button class="btn btn-discontinue" id="yes_button">Yes, discontinue my order</button>
					<button class="btn btn-not-cancel mt-2" id="no_button">No, I want to keep my order</button>
					<p class="mt-4 text-danger hide wrong-password">Invalid password</p>
				</div>
			</div>
		</div>
	</div>

	<style>

#yes_button {
  display: flex !important;
  align-items: center !important; /* Center text vertically */
  justify-content: center !important; /* Center text horizontally */
}
.cancel {
  background-color: #fefefe;
  margin: 10% auto; /* Center the modal */
  border-radius: 16px;
  padding: 15px 15px 15px 15px !important;
  border: none;
  width: 80%; /* Adjust width as needed */
  max-width: 80% !important; /* Set a maximum width */
}

	.ssis-overlay {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
	z-index: 1040; /* Make sure it's above other content but below modals */
	}
</style>