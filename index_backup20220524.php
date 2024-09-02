<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'admin';

////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

?>


<?= get_header($page, 'login') ?>

<div class="container">	
	
	<div class="admin-form" id="admin">
		<form method="post" id="login_form" name="login_form">

			<img src="./assets/images/logo/logo-full-dark.png" alt="SSIS" class="img-fluid d-block m-auto">

			<fieldset class="mt-5">
				<div class="form-group">
					<input type="text"  class="form-control" id="UserName" name="user" required />
					<label for="UserName" class="placeholder m-0">Username</label>
				</div>

				<div class="form-group">
					<input type="password" class="form-control toggle-password" id="Password" name="pass" required />
					<label for="Password" class="placeholder m-0">Password</label>
					<input type="checkbox" class="sr-only" id="toggle_password">
					<label for="toggle_password"></label>
				</div>
			</fieldset>

			<input type="button" name="btnsubmit" id="btnsubmit" value="Log in" class="log-btn btn btn-primary" />
			
			<div id="msg"></div>

		</form>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){

		function showPassword() {
			var x = $('.toggle-password');
			(x.prop('type') === "password") ? x.attr('type', 'text') : x.attr('type', 'password');
		}

		$('#toggle_password').on('click', showPassword);

		function adminLogin() {
			$.post("/process/loginConfirm.php",$("form#login_form").serialize(),function(d){
				$('#msg').html("");
				if (d.substring(0,7)=='success') {
					location.reload();
				} else {
					$("#msg").html(d);
				}
			});
		}

		$(".form-control").on('keyup', function (e) {
			if (e.keyCode == 13) {
				adminLogin();
			}
		});

		$("#btnsubmit").click(function(){
			adminLogin();
		});

	});
</script>

<?= get_footer() ?>