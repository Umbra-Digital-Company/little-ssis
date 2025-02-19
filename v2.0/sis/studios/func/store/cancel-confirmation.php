<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
require $sDocRoot."/includes/connect.php";
include($sDocRoot."/v2.0/sis/studios/func/functions.php");
$arrTranslate = grabLanguageTags();

?>



<div class="modal-dialog modal-dialog-centered">
		<div class="modal-content  cancel modal-cancel" style=" width: 400px !important; height: 335px !important; ">
			<div class="modal-body">
				<div class="text-center" style="margin-bottom: 30px;">
					<span class="font-bold text-center" style="font-size: 18px; font-weight: 700;">Are you sure you want to leave?</span>
				</div>
				<div class="text-center">
					<?php if(isset($_SESSION['language_setting']) && $_SESSION['language_setting'] == 'vn') {?>
					<p style="font-size: 18px;"><?= $arrTranslate['You are about to cancel this order. If you\'d wish to proceed please type "SSIS" in the input below'] ?>.</p>
					<?php }else{ ?>
						<p style="font-size: 18px; margin-bottom: 30px;  font-weight: 400;">You are about to cancel this order.</p>
						<!--  If you'd wish to proceed please type "SSIS" in the input below -->
					<?php } ?>
				</div>
				<div class="form-group mt-5" >
					<input type="hidden" id="password_confirmation" class="form-control" value="SSIS">
					<!-- <label for="password_confirmation" class="placeholder">Type SSIS to confirm</label> -->
				</div>
				<div class="text-center mt-4" style="margin-bottom: 20px !important;" >
					<button class="btn btn-discontinue mb-3" id="yes_button" >Yes, discontinue my order</button>
			
					<button class="btn btn-not-cancel mt-2" id="no_button">No, I want to keep my order</button>
					<p class="mt-2 text-danger hide wrong-password" style="font-size: 12px;">Invalid password</p>
				</div>
			</div>
		</div>
	</div>

	<style>



#yes_button {
  display: flex !important;
  align-items: center !important; /* Center text vertically */
  justify-content: center !important; /* Center text horizontally */
  height: 56px;
}

#no_button {
  font-size: 18px ;
  font-weight: 400;
  font-family: "Surt-Regular";
  display: flex !important;
  align-items: center !important; /* Center text vertically */
  justify-content: center !important; /* Center text horizontally */
  height: 56px;
}

.placeholder {
  font-weight: 400; /* Regular font weight */
}
.modal-cancel {
	padding: 0px !important;
	margin: 0 auto;
}

.modal-body {
	padding: 20px 12px 12px 12px !important;

  }
.cancel {
  background-color: #fefefe;
  border-radius: 16px;
  padding: 20px 24px 24px 24px !important;
  border: none;
  height: 446px !important; /* Adjust width as needed */
  width: 400px !important;
  max-width: 450px !important; /* Set a maximum width */
}


@media (max-width: 768px) {
  .cancel {
    height: 446px !important; /* Adjust width as needed */
	max-width: 400px !important; /* Set a maximum width */
  }

  .modal-body {
	
	padding: 12px 0px 15px 0px !important;
  }
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