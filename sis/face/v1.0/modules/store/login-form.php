<form method="post" id="login_account" autocomplete="off" class="mt-0">

	<p class="font-bold text-uppercase text-primary"><?= $arrTranslate['Personal Details'] ?></p>

	<div class="d-flex form-row form-group justify-content-center mt-3">
		<div class="form-group col-md-2 col-sm-2" id="div_area_codes" style='padding-left: 0px; padding-right: 0px; margin: 0px; display: none'>
			<select class="text-left select mh-40 form-control" name="country_codes_login" id="country_codes_login" style="margin: 0px; padding-top:0px; padding-bottom:0px; border-bottom-right-radius: 0px; border-top-right-radius: 0px;">
				<option></option>
			</select>
			<label class="placeholder" for="country_codes_login">Area Code</label>
		</div>
		<div class="form-group col-md-12 col-sm-12" id="div_mobile_number" >
			<input type="text" name="username" class="form-control text-lowercase" id="username" required="required"  autocomplete="nope" />
			<label class="placeholder" for="username" style="margin-left:15px;"><?= $arrTranslate['Email or Mobile Number'] ?></label>
			<span class="mobile-format" id="mobile-format_login" style="display: none;"></span>
		</div>
	</div>

	<p class="font-bold text-uppercase text-primary mt-3"><?= $arrTranslate['Birthdate'] ?></p>

	<div class="d-flex form-row form-group justify-content-center mt-3">
		<div class="col-4 form-group">
			<select name="month" id="birthMonth" class="form-control">
				<option value="">MONTH</option>
				<?php
					$arrMonth = ['Jan' => 'January','Feb' => 'February','Mar' => 'March', 'Apr' => 'April', 'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August', 'Sep'=> 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'];

					foreach($arrMonth as $key => $value){
				?>
				<option value="<?= $key ?>"><?= $value ?></option>
					<?php } ?>
			</select>
		</div>
		<div class="col-4 form-group">
			<select name="day" id="birthDay" class="form-control ">
				<option value="">DAY</option>
				<?php
					for($i = 1; $i <=31; $i++){
						$i = ( $i < 10) ? '0'.$i : $i;
				?>
					<option value="<?= $i ?>"><?= $i?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-4 form-group">
			<select name="year" id="birthYear" class="form-control ">
				<option value="">YEAR</option>
				<?php
					$year = date('Y');
					for($i = $year; $i >=1920; $i--){
				?>
					<option value="<?= $i ?>"><?= $i?></option>
				<?php } ?>
			</select>
		</div>
	</div>
	
	<div class="text-center mt-5">
		<div class="form-row">
			<div class="col-md-12 form-group">
				<input type="button" name="btnsubmit" id="btnLoginSubmit" value="Continue" class="btn btn-primary" />
			</div>
		</div>
	</div>
	<div class="text-center mt-3">
		<p class="text-danger" id="msg_login"></p>
	</div>
</form>

<script>
	$(document).ready(function(){
		$('#btnLoginSubmit').click(function(e){
			form = $('#login_account').serialize();
			$.post("/sis/studios/func/store/store-sign-login.php", form, function (d) {
				$('#msg_login').html("");

				if (d == 'success') {
					location.reload();
				}else{
					$("#msg_login").html(d);
				}
			});
		});
	});
</script>