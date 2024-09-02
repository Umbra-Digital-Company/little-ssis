<div id="admin">
	<form method="post" id="login_form" name="login_form" autocomplete="off">

		<img src="<?= get_url('images') ?>/logo/logo-full-dark.png" alt="SSIS" class="img-fluid d-block m-auto">

		<fieldset class="mt-5">
			<div class="form-group">
				<input type="text"  class="form-control" id="UserName" name="user" required />
				<label for="UserName" class="placeholder m-0"><?= $arrTranslate['Username'] ?></label>
			</div>

			<div class="form-group">
				<input type="password" class="form-control toggle-password" id="Password" name="pass" required />
				<label for="Password" class="placeholder m-0"><?= $arrTranslate['Password'] ?></label>
				<input type="checkbox" class="sr-only" id="toggle_password">
				<label for="toggle_password"></label>
			</div>
		</fieldset>

		<input type="button" name="btnsubmit" id="btnsubmit" value="<?= $arrTranslate['Log In'] ?>" class="log-btn btn btn-primary" />

		<div id="msg"></div>

	</form>
</div>