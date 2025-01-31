<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Required includes
include("./modules/includes/location_setup.php");
include("./modules/includes/all_countries.php");
include("./modules/includes/grab_specific_province.php");
include("./modules/includes/grab_area_codes.php");
include("./modules/includes/grab_vietnam_city.php");

if ( isset($_SESSION['customer_id']) ) {
	include("./modules/includes/grab_customer_hdf.php");
}
/*
 * Set session to detect that you are in the CUSTOMER PAGE
 * this hides all the menu for assistant
 *
 */
// if ( !isset($_SESSION['customer_page']) ) {
// 	$_SESSION['customer_page'] = 'YES';
// 	echo "<script>window.location.reload(true)</script>";
// }

$generate_pass = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$password = "";
$password2 = "";

for ($i=0; $i < 8; $i++) {
	$password .= $generate_pass[rand(0, (strlen($generate_pass)-1))];
	$password2 .=$password;
}

function get_customer_data_2($data) {
	global $arrCustomer;
	if ( isset($_SESSION['customer_id']) ) {
		if ( $arrCustomer[0][$data] != ''  ) {
			$value = $arrCustomer[0][$data];
		} else {
			$value = '';
		}
	} elseif ( isset($_SESSION['temp_data']) ) {
		$value = $_SESSION[$data];
	} else {
		$value = '';
	}

	return $value;
}

// function get_customer_hdf($data) {
// 	global $arrCustomerHdf;
// 	if ( isset($_SESSION['customer_id']) ) {
// 		if ( $arrCustomerHdf[0][$data] != 'n' && $arrCustomerHdf[0][$data] != ''  ) {
// 			$value = 'checked';
// 		} else {
// 			$value = '';
// 		}
// 	} else {
// 		$value = '';
// 	}

// 	return $value;
// }

// function get_customer_hdf_N($data) {
// 	global $arrCustomerHdf;
// 	if ( isset($_SESSION['customer_id']) ) {
// 		if ( $arrCustomerHdf[0][$data] == 'n') {
// 			$value = 'checked';
// 		} else {
// 			$value = '';
// 		}
// 	} else {
// 		$value = '';
// 	}

// 	return $value;
// }

if ( !isset($_SESSION['customer_id']) ) {
	unset($_SESSION['step_3']);
	unset($_SESSION['pickup']);
	unset($_SESSION['prescription']);
	unset($_SESSION['order_no']);
	unset($_SESSION['order_confirmation']);
}


 //showArray($_SESSION);
?>

	<div class="account-content mt-4" id="use-content">
		<!-- <p class="font-bold text-uppercase text-primary">log in account</p> -->
		<form method="post" id="use_account" name="use_account" autocomplete="off" class="mt-0">

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
						<input type="button" name="btnsubmit" id="btnsubmit" value="Continue" class="btn btn-primary" />
					</div>
				</div>
			</div>
			<div class="text-center mt-3">
				<p class="text-danger" id="msg"></p>
			</div>
		</form>
		<script type="text/javascript">
			$('#username').on('keydown', function () {
				if ($.isNumeric($(this).val())) {
					if($('#country_codes_login').val() == ''){
						$('#country_codes_login').val('63');
						$('#country_codes_login').select2().trigger('change');
					}
					$('#div_area_codes').css('display', '');
					//$("#div_mobile_number").attr('class', 'form-group col-md-11 col-sm-9');
					$("#username").css({"border-bottom-left-radius": "0px", "border-top-left-radius": "0px", "border-left-style": "none"});
					paddingUsername($('#country_codes_login'));
				} else {
					$(this).next('label').text('Email Address');
					//$("#div_mobile_number").attr('class', 'form-group col-md-12 col-sm-9');
					$('#div_area_codes').css('display', 'none');
					$('#mobile-format_login').css('display','none');
					$("#username").attr('style', false);
				}
			});
			$('#country_codes_login').change(function(){
				paddingUsername(this);
			});
			function paddingUsername($this){
				let code = '';
				code = $($this).val();
				if(code != ''){
					$('#mobile-format_login').text('+'+code);
					let area_code = code.length;
					let userNumber = $('#username').val().substr(0, area_code);
					if($($this).val() == userNumber)
					$('#username').val($('#username').val().substring(area_code));
					if(code !=''){
						$('#mobile-format_login').text('+'+code);
						$('#username').css('padding-left','50px');
						(code.length ==3) ? $('#username').css('padding-left','60px') : '';
						(code.length ==4) ? $('#username').css('padding-left','65px') : '';
						$('#mobile-format_login').css('display','');
					}else{
						$('#username').css('padding-left','');
					}
				}
			}
		</script>
		<!-- <form name="guest_account" id="guest_account" action="modules/process/store-signup-register-guest.php" method="POST" autocomplete="off">
			<input type="hidden" name="specs_branch" value="<?= 'This Store Name' ?>">
			<input type="hidden" name="joining_date" value="<?= date('Y/m/d'); ?>">
			<div class="text-center">
				<input type="submit" name="btnsubmitguest" id="btnsubmitguest" value="Checkout as Guest" class="btn btn-link text-primary" style="text-decoration: underline !important;" />
			</div>
		</form> -->
	</div>
	<style>
		input[type=date]:required:invalid::-webkit-datetime-edit {
		    color: transparent;
		}
		input[type=date]:focus::-webkit-datetime-edit {
		    color: black !important;
		}
	</style>
	<div class="account-content active mt-4 mb-4" id="create-content">
		<form name="update_guest_account" id="update_guest_account" method="POST" autocomplete="off">
			<input type="hidden" value="v1.0" name="path_loc">
			<input type="hidden" value="<?php echo $password; ?>" name="password2">
			<input type="hidden" value="<?php echo $password; ?>" name="confirmPassword2">
			<input type="hidden" name="specs_branch" value="<?= 'This Store Name' ?>">
			<input type="hidden" name="joining_date" value="<?= date('Y/m/d'); ?>">

			<p class="font-bold text-uppercase text-primary">Profile Details</p>
			<div class="form-row no-gutters mt-3">
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="lname" class="form-control" id="lname" required>
						<label class="placeholder" for="lname"><?= $arrTranslate['Last Name'] ?></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="fname" class="form-control" id="fname" required>
						<label class="placeholder" for="fname"><?= $arrTranslate['First Name'] ?></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="date" name="bdate" class="form-control" id="bdate" required>
						<label class="placeholder" for="bdate"><?= $arrTranslate['Birth Day'] ?></label>
					</div>
				</div>
				<style>
					[type="checkbox"]:not(:checked) + label, [type="checkbox"]:checked + label {
				        position: relative;
				        padding-left: 1em;
				        cursor: pointer;
				    }
				    .radio-active label, .radio .checked, .checkbox .checked, .checkbox-active label {
				        font-weight: 600;
				    }
				    .radio label, .checkbox label {
				        min-height: 20px;
				        padding-left: 5px;
				        margin-bottom: 0;
				        font-weight: normal;
				        cursor: pointer;
				    }
				</style>
				<div class="col-12 col-md-6">
					<div class="form-group gender">
							<input class="form-control" value="-" style="display: none;">
							<div id='age_range' class="form-control d-flex justify-content-between">
								<?php
									$arrGender = ['male'=>'Man', 'female'=>'Woman', 'Non-binary'=> 'Non-binary','N/A'=> 'Prefer not to say'];
									foreach ($arrGender as $key => $value) {
								?>
										<div class="d-flex align-items-center radio">
                                            <input type="radio" name="gender" id="<?=$key ?>" class="sr-only checkbox"  value="<?=$key ?>" required >
                                            <label for="<?=$key ?>" class="custom_checkbox"></label>
                                            <label for="<?=$key ?>"><?=$value ?></label>
                                        </div>
								<?php } ?>
							</div>
						<label class="placeholder" for="age_range">I Identify As?</label>
					</div>
				</div>
				<input type="hidden" name="country" value="philippines">
				<div class="col-6" <?= ($_SESSION['store_code'] == 142 || $_SESSION['store_code'] == 150) ? 'style="display:none;"' : '' ?> >
					<div class="form-group">
						<select class="text-left s-a mh-40 select form-control" name="province" id="province" required>

							<?php

							if ( $specProvince != "" ) {
								echo '<option value="n" disabled selected></option>';
								echo '<option value="'.$specProvince.'">'.ucwords(str_replace("-", " ", $specProvince)).'</option>';
							}

							for ( $i=0; $i < sizeOf($arrCC); $i++) : ?>

								<option
									value="<?= $arrCC[$i]["province"] ?>"
									<?php
									if ( isset($_SESSION['customer_id']) && $arrCustomer[0]['province'] == $arrCC[$i]['province'] ) {
										echo 'selected="selected"';
									}
									?>
								>
									<?= ucwords(str_replace("-", " ", $arrCC[$i]["province"])) ?>
								</option>

							<?php endfor ?>

						</select>
						<label class="placeholder" for="province">Province</label>
					</div>
				</div>
				<?php if ( isset($_SESSION['customer_id']) ) : ?>

					<script> // LOAD CITY AND BARANGAY AUTOMATICALLY
						var current_city = '<?= $arrCustomer[0]['city'] ?>';

						setTimeout(function() {
							$('#city').val(current_city.replace(/\s/g, '-')).change();

							<?php if ( $arrCustomer[0]['barangay'] != '' ) : ?>

								var current_barangay = '<?= $arrCustomer[0]['barangay'] ?>';

								setTimeout(function() {
									$('#barangay').val(current_barangay.replace(/\s/g, '-')).change();
								},1200);

							<?php endif ?>
						},800);
					</script>

				<?php endif ?>
				<div class="col-6">
					<div class="form-group">
						<div class="p-c-sect">
							<select class="text-left mh-40 select form-control" name="city" id="city" required>
								<option value="n" selected disabled></option>
							</select>
							<label class="placeholder" for="city"><?= $arrTranslate['City'] ?></label>
						</div>
					</div>
				</div>
			</div>
			<input type="hidden" name="barangay" value="N/A">
			<p class="font-bold text-uppercase text-primary mt-2"><?= $arrTranslate['Contact Details'] ?></p>
			<div class="form-row no-gutters mt-3">
				<div class="col-12">
					<div class="form-group">
						<?php if ( isset($_SESSION['customer_id']) ) : ?>
							<input type="hidden" name="email_confirmation" >
						<?php endif ?>
						<input type="email" name="email" class="form-control" id="s_email" required>
						<label class="placeholder" for="s_email"><?= $arrTranslate['Email Address'] ?></label>
						<div class="emailAvail"></div>
					</div>
				</div>
				<div class="col-md-12 col-sm-12 d-flex align-items-center">
					<div class="form-group col-md-2 col-sm-3" style='padding-left: 0px; padding-right: 0px;'>
						<select class="text-left select mh-40 form-control" name="country_codes" id="country_codes" style="margin: 0px; padding-top:0px; padding-bottom:0px; border-bottom-right-radius: 0px; border-top-right-radius: 0px;" required>

							<?php

								usort($countryArray, function($a, $b) {
									return $a['code'] - $b['code'];
								});
								$countryArray= array_reverse($countryArray);
								$get_num = get_customer_data_2('phone_number');
								echo '<option></option>';
								$phone_number = '';
								foreach($countryArray as $key => $value){
									$ac_length = strlen($value['code']);
									if($get_num == ''){
										if($_SESSION['store_code'] == 142 || $_SESSION['store_code'] == 150){
											if($value['code'] == '84'){
												echo '<option  value="'.$value['code'].'" selected>'.$value['key'].' +'. $value['code'].'</option>';
												break;
											}
										}
										else{
											if($value['code'] == '63'){
												echo '<option  value="'.$value['code'].'" selected>'.$value['key'].' +'. $value['code'].'</option>';
												break;
											}
										}
									}
									elseif($value['code'] == substr($get_num, 0, $ac_length)){
										echo '<option  value="'.$value['code'].'" selected>'.$value['key'].' +'. $value['code'].'</option>';
										$phone_number = substr($get_num,$ac_length);
										break;
									}
								};
							?>

						</select>
						<label class="placeholder" for="country_codes">Area Code</label>
					</div>
					<div class="form-group col-md-10 col-sm-9 country_num" style='padding-left: 0px; padding-right: 0px;'>
						<input type="text" style="border-bottom-left-radius: 0px; border-top-left-radius: 0px; border-left-style: none;" name="mnum" class="form-control mobile-number" id="mnum" class="mnum" required>
						<label class="placeholder" for="mnum"><?= $arrTranslate['Mobile Number'] ?></label>
						<span class="mobile-format"></span>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				let code = '';
				code = $('#country_codes').val();
				$('.mobile-format').text('+'+code);
				(code.length ==3) ? $('.mobile-number').css('padding-left','60px') : '';
				(code.length ==4) ? $('.mobile-number').css('padding-left','65px') : '';
				$('#country_codes').change(function(){
					code = $(this).val();
					$('.mobile-number').css('padding-left','50px');
					(code.length ==3) ? $('.mobile-number').css('padding-left','60px') : '';
					(code.length ==4) ? $('.mobile-number').css('padding-left','65px') : '';
					$('.mobile-format').text('+'+code);
				});
			</script>
			<div class="text-center mt-4">

				<?php if ( !isset($_SESSION['customer_id']) ) : ?>

					<p class="text-secondary mb-3">By clicking Submit, you have read and agreed<br/>to our <span class="font-bold text-primary btn-terms">Terms and Conditions</span>.</p>

					<?php include("terms-and-condition.php") ?>

					<script type="text/javascript">

						$('.btn-terms').click(function() {

							if($('.terms-well').hasClass('open')) {

								$('.terms-well').removeClass('open').addClass('closed');

							}
							else {

								$('.terms-well').removeClass('closed').addClass('open');

							};

						});

					</script>

				<?php endif ?>
				
				<button type="submit" class="btn btn-black" id="submitCustomer">Submit</button>
				<p class="hide text-danger mt-4 required-warning">Please make sure all required fields are filled</p>

				<?php if ( isset($_SESSION['email_taken']) ) : ?>

					<p class="mt-4 text-danger"><?= $_SESSION['email_taken'] ?></p>

				<?php endif ?>

			</div>
		</form>
	</div>
<script>
	let arrVietnam = <?= json_encode($arrVietnam); ?>;
	let user_type = <?= $_SESSION['store_code']; ?>;
$(document).ready(function(){
	$("input[type=radio]").click(function(){
		if($(this).attr('value') == 'yes'){
			$(this).parent().parent().parent().next().removeClass('col-lg-10 col-md-10 col-xs-12 d-none').addClass('col-lg-11 col-md-11 col-xs-12')
		}else{
			$(this).parent().parent().parent().next().removeClass('col-lg-11 col-md-11 col-xs-12').addClass('col-lg-11 col-md-11 col-xs-12  d-none');
		}
		if($(this).attr('value') == 'yes'){
			$(this).parent().parent().parent().next().find('input').focus();
			$(this).parent().parent().parent().next().find('input').attr('required', true);
		}else{
			$(this).parent().parent().parent().next().find('input').removeAttr('required');
		}
	});
	if(user_type == 142 || user_type == 150){
		//$("#city").removeAttr('disabled');
		let city = '';
		for(let i =0; i < arrVietnam.length; i++){
			let sel = (typeof current_city != "undefined" && current_city == arrVietnam[i].city.replace(" ", "-") ) ? 'selected' : '';
			city += '<option value="'+arrVietnam[i].city.replace(" ", "-")+'" '+sel+'>'+arrVietnam[i].city+'</option>';
		}
		setTimeout(function() {
			$('#city').html(city);
		},850);
	}else{
		$("#barangay").parent().parent().parent().show();
	}
	$('#country').change(function(){
		if($(this).val() == 'viet-nam'){
			$(this).parent().parent().attr('class', 'col-6');
			$("#city").removeAttr('disabled');
			let city = '';
			for(let i =0; i < arrVietnam.length; i++){
				city += '<option value="'+arrVietnam[i].city.replace(" ", "-")+'">'+arrVietnam[i].city+'</option>';
			}
			$('#city').html(city);
			$('#city').parent().parent().show();
		}else{
			$('#province').change();
			$("#barangay").html('<option value="n" selected></option>');
			$("#barangay").parent().parent().parent().show();

		}
	});

});
</script>
