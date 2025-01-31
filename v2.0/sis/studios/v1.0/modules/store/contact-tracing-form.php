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
if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '') {
	echo '<script>	window.location.href="/"; </script>';
}
// if(isset($_SESSION['login_set'])){
//     echo '<script>window.location="./?page=select-store"</script>';
// }
if (isset($_SESSION['customer_id'])) {
	include("./modules/includes/grab_customer_hdf.php");
}
/*
 * Set session to detect that you are in the CUSTOMER PAGE
 * this hides all the menu for assistant
 *
 */



if (!isset($_SESSION['customer_page'])) {
	$_SESSION['customer_page'] = 'YES';

	echo "<script>window.location.reload(true)</script>";
}

$generate_pass = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$password = "";
$password2 = "";

for ($i = 0; $i < 8; $i++) {
	$password .= $generate_pass[rand(0, (strlen($generate_pass) - 1))];
	$password2 .= $password;
}

function get_customer_data($data)
{
	global $arrCustomer;
	if (isset($_SESSION['customer_id'])) {
		if ($arrCustomer[0][$data] != '') {
			$value = $arrCustomer[0][$data];
		} else {
			$value = '';
		}
	} elseif (isset($_SESSION['temp_data'])) {
		$value = $_SESSION[$data];
	} else {
		$value = '';
	}

	return $value;
}

function get_customer_hdf($data)
{
	global $arrCustomerHdf;
	if (isset($_SESSION['customer_id'])) {
		if ($arrCustomerHdf[0][$data] != 'n' && $arrCustomerHdf[0][$data] != '') {
			$value = 'checked';
		} else {
			$value = '';
		}
	} else {
		$value = '';
	}

	return $value;
}

function get_customer_hdf_N($data)
{
	global $arrCustomerHdf;
	if (isset($_SESSION['customer_id'])) {
		if ($arrCustomerHdf[0][$data] == 'n') {
			$value = 'checked';
		} else {
			$value = '';
		}
	} else {
		$value = '';
	}

	return $value;
}

if (!isset($_SESSION['customer_id'])) {
	unset($_SESSION['step_3']);
	unset($_SESSION['pickup']);
	unset($_SESSION['prescription']);
	unset($_SESSION['order_no']);
	unset($_SESSION['order_confirmation']);
}


//showArray($_SESSION);
?>


<?php if (isset($_GET['guest'])) { ?>



	<div class="wrapper-guest customer-guest mt-3">
		<h1 class="text-start mb-4 font-weight-bold text-uppercase" style="font-size: 18px;">GUEST ACCOUNT</h1>
		<form action="/v2.0/sis/studios/func/process/guest_register.php?path_loc=v1.0" method="post" class="form-guest"
			id="guestForm" id="guestForm>
				<input type=" hidden" name="specs_branch" value="<?= 'This Store Name' ?>">
			<input type="hidden" name="joining_date" value="<?= date('Y/m/d'); ?>">


			<div class="form-group mt-4">
				<input type="text" name="lastname" class="form-control" id="lastname" autocomplete="off" autofocus
					required />
				<label class="placeholder" for="lastname">Last Name</label>
			</div>
			<div class="form-group mt-4">
				<input type="text" name="firstname" class="form-control" id="firstname" autocomplete="off" required />
				<label class="placeholder" for="firstname">First Name</label>
			</div>



			<div class="form-group mt-4">
				<select class="text-left s-a mh-40 select form-control" name="gender" id="gender" required>
					<option value="" disabled selected></option>
					<option value="male">Male</option>
					<option value="female">Female</option>

				</select>
				<label class="placeholder" for="gender">I identify as</label>
			</div>

			<div class="form-group mt-4">
				<select class="text-left s-a mh-40 select form-control" name="age_range" id="age_range" required>
					<option value="" disabled selected></option>
					<option value="1">0-12</option>
					<option value="13">13-17</option>
					<option value="18">18-24</option>
					<option value="25">25-34</option>
					<option value="35">35-44</option>
					<option value="45">45-54</option>
					<option value="55">55-64</option>
					<option value="65">65+</option>
				</select>
				<label class="placeholder" for="age_range">Age Group</label>
			</div>


			<div class="text-center mt-4">
				<input type="submit" id="guest-submit" class="btn btn-primary" value="<?= $arrTranslate['Proceed'] ?>"
					disabled>
			</div>
			<div class="text-center mt-4">
				<a href="/v2.0/sis/studios/v1.0/?page=contact-tracing-form&type=log-in">
					<div style="color: #919191; font-size: 18px; font-weight: 400; ">Log in with your Sunnies Club account to
						earn points</div>
				</a>
			</div>
		</form>
	</div>
<?php } else { ?>
	<?php if (!isset($_SESSION['customer_id'])): ?>
		<div class="switch-layout mt-2	">
			<span
				class="switch-animation <?= (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) ? 'slide' : '' ?>"></span>
			<div class="account-navigation d-flex no-gutters">
				<a href="#use-content"
					class="col-6 text-center account-option <?= (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) ? '' : 'active' ?>">Log
					in</a>
				<a href="#create-content"
					class="col-6 text-center account-option <?= (!isset($_SESSION['customer_id']) && !isset($_SESSION['temp_data'])) ? '' : '' ?>">Sign
					up</a>
			</div>
		</div>
	<?php endif ?>

	<div class="account-content active mt-4" id="use-content">
		<!-- <p class="font-bold text-uppercase text-primary">log in account</p> -->
		<div class="account-login">
			<form method="post" id="use_account" name="use_account" autocomplete="off" class="form-login" id="loginForm">

				<!-- <p class="font-bold text-uppercase text-primary"><?= $arrTranslate['Personal Details'] ?> </p> -->

				<div class="d-flex form-row form-group justify-content-center mt-3">
					<div class="form-group col-md-12 col-sm-12 d-flex" id="div_mobile_number">
						<div class="form-group col-md-3 col-sm-3" id="div_area_codes"
							style='padding-left: 0px; padding-right: 0px; margin: 0px; display: none'>
							<select class="text-left select form-control" name="country_codes_login"
								id="country_codes_login">
								<option></option>
							</select>
							<!-- <label class="placeholder" for="country_codes_login">Area Code</label> -->
						</div>
						<input type="text" name="username" class="form-control text-lowercase" id="username"
							required="required" autocomplete="nope" />
						<label class="placeholder email" display="none"
							for="username"><?= $arrTranslate['Email or Mobile Number'] ?></label>

						<p class="text-danger" id="msg" style="font-size: 14px;"></p>
						<!-- <span class="mobile-format" id="mobile-format_login" style="display: none;"></span> -->
					</div>

					<div class="form-group col-md-12 col-sm-12 mt-2" id="div_birthdate" data-provide="datepicker">
						<input name="birthdate" class="form-control" id="birthdate" required="required" type="text" />
						<label class="placeholder" for="Birthdate"><?= $arrTranslate['Birthdate'] ?></label>
						<input name="month" id="month" hidden value="">
						<input name="day" id="day" hidden value="">
						<input name="year" id="year" hidden value="">
					</div>
				</div>
				<div class="text-center mt-2">
					<div class="form-row">
						<div class="col-md-12 form-group">
							<input type="button" name="login-submit" id="btnsubmit" value="Log in" style="height: 45px !important;" 
								class="btn btn-primary" disabled />
						</div>
					</div>
				</div>
				<div class="text-center ">
					<div class="form-row">
						<div class="col-md-12 form-group">
							<a href="/v2.0/sis/studios/v1.0/?page=contact-tracing-form&guest=true">
								<input type="button" class="btn btn-not-cancel" value="Check out as guest" style="height: 45px !important;" />
							</a>
						</div>
					</div>
				</div>

			</form>

			<!-- ------------LOGIN FORM SCRIPTS-------------- -->
			<script>
				$(document).ready(function () {
					$('#birthdate').datepicker({
						dateFormat: 'MM dd yy',
						autoclose: true,
						changeMonth: true,
						changeYear: true,
						yearRange: '-100y:c+nn',
						maxDate: '-1d'
					})
				});

				$('#birthdate').on('keydown keypress keyup', function (event) {
					event.preventDefault(); // Prevents typing
				});
				$("#birthdate").change(function () {
					var date = $(this).datepicker('getDate');
					if (date) {
						var month = $.datepicker.formatDate('M', date);
						var day = $.datepicker.formatDate('dd', date);
						var year = $.datepicker.formatDate('yy', date);

						// Set the hidden input values
						$("#month").val(month);
						$("#day").val(day);
						$("#year").val(year);

						//remove errors
						$("#msg").text("");
						$("#username").css("border-bottom", " 1px " + "solid" + " #dcdcdc");
						$(".placeholder.email").removeClass("text-danger");

						//enable button
						if ($('#username').val() != "" && $("#birthdate").val() != "") {
							$('#btnsubmit').prop('disabled', false);
						} else {
							$('#btnsubmit').prop('disabled', true);
						}

					}
				});

				$("#username").on('keydown keypress keyup', function (event) {
					$("#msg").text("");
					$(".placeholder.email").removeClass("text-danger");


					//enable button
					if ($('#username').val() != "" && $("#birthdate").val() != "") {
						$('#btnsubmit').prop('disabled', false);
					} else {
						$('#btnsubmit').prop('disabled', true);
					}
				});
			</script>

			<!-- END OF LOGIN FORM SCRIPTS -->

		</div>
		<script type="text/javascript">
			$('#username').on('input', function () {
					const inputVal = $(this).val();
					
					// Handle empty input case
					if (inputVal.length === 0 || inputVal.length===1) {
							$('#div_area_codes').css('display', 'none');
							$('#mobile-format_login').css('display', 'none');
							$("#username").attr('style', false);
							return;
					}

					// Check if input contains any numbers
					if ($.isNumeric(inputVal)) {
							if ($('#country_codes_login').val() == '') {
									$('#country_codes_login').val('63');
									$('#country_codes_login').select2().trigger('change');
							}
							$('#div_area_codes').css('display', '');
							$("#username").css({
									"border-bottom-left-radius": "0px",
									"border-top-left-radius": "0px",
									"border-left-style": "none"
							});
							paddingUsername($('#country_codes_login'));
					} else {
							$(this).next('label').text('Email Address');
							$('#div_area_codes').css('display', 'none');
							$('#mobile-format_login').css('display', 'none');
							$("#username").attr('style', false);
					}
			});

			$('#country_codes_login').change(function () {
				paddingUsername(this);
			});
			function paddingUsername($this) {
				let code = '';
				code = $($this).val();
				if (code != '') {
					$('#mobile-format_login').text('+' + code);
					let area_code = code.length;
					let userNumber = $('#username').val().substr(0, area_code);
					if ($($this).val() == userNumber)
						$('#username').val($('#username').val().substring(area_code));
					if (code != '') {
						$('#mobile-format_login').text('+' + code);
						// $('#username').css('padding-left', '50px');
						// (code.length == 3) ? $('#username').css('padding-left', '60px') : '';
						// (code.length == 4) ? $('#username').css('padding-left', '65px') : '';
						$('#mobile-format_login').css('display', '');
					} else {
						$('#username').css('padding-left', '');
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

	<div class="account-content  mt-4 mb-4" id="create-content">
		<form name="create_account" id="create_account" action="/v2.0/sis/studios/func/process/store-register-v2.php"
			method="POST" autocomplete="off">
			<input type="hidden" value="v1.0" name="path_loc">
			<input type="hidden" value="<?php echo $password; ?>" name="password2">
			<input type="hidden" value="<?php echo $password; ?>" name="confirmPassword2">
			<input type="hidden" name="specs_branch" value="<?= 'This Store Name' ?>">
			<input type="hidden" name="joining_date" value="<?= date('Y/m/d'); ?>">
			<input type="hidden" name="mname" value="N/A">
			<input type="hidden" name="home_address" value="N/A">
			<input type="hidden" name="age" value="">
			<div class="account-signup-personal">

				<p class="font-bold text-uppercase " style="font-size: 18px; color: #342C29; ">
				Personal Details</p>
				<div class="no-gutters mt-4">

					<div class="form-group">
						<input type="text" name="lname" class="form-control" id="lname"
							value="<?= get_customer_data('last_name') ?>" required>
						<label class="placeholder" for="lname"><?= $arrTranslate['Last Name'] ?></label>
					</div>

					<div class="form-group">
						<input type="text" name="fname" class="form-control" id="fname"
							value="<?= get_customer_data('first_name') ?>" required>
						<label class="placeholder" for="fname"><?= $arrTranslate['First Name'] ?></label>
					</div>


					<!-- <div class="form-group">
						<input type="text" name="bdate" class="form-control" id="bdate" value="<?= get_customer_data('birthday') ?>" required>
						<input type="date" name="bdate2" class="form-control sr-only" id="bdate2" value="<?= get_customer_data('birthday') ?>" required>
						<label class="placeholder" for="bdate2"><?= $arrTranslate['Birthdate'] ?></label>
						<label class="placeholder-overlay" for="bdate2"></label>
						</div> -->

					<div class="form-group col-md-12 col-sm-12 mt-2" id="div_birthdate" data-provide="datepicker">
						<input name="b_date" class="form-control" id="b_date" required="required" type="text" value="" />
						<label class="placeholder" for="b_date"><?= $arrTranslate['Birthdate'] ?></label>
						<input name="b_month" id="b_month" hidden value="">
						<input name="b_day" id="b_day" hidden value="">
						<input name="b_year" id="b_year" hidden value="">
					</div>

				

					<script>
				$(document).ready(function () {
					$('#b_date').datepicker({
						dateFormat: 'MM dd yy',
						autoclose: true,
						changeMonth: true,
						changeYear: true,
						yearRange: '-100y:c+nn',
						maxDate: '-1d'
					})
				});

				$('#b_date').on('keydown keypress keyup', function (event) {
					event.preventDefault(); // Prevents typing
				});
				$("#b_date").change(function () {
					var date = $(this).datepicker('getDate');
					if (date) {
						var month = $.datepicker.formatDate('mm', date);
						var day = $.datepicker.formatDate('dd', date);
						var year = $.datepicker.formatDate('yy', date);

						// Set the hidden input values
						$("#b_month").val(month);
						$("#b_day").val(day);
						$("#b_year").val(year);

						//age

						var today = new Date();
						var birthDate = new Date(year, month - 1, day);
						var age = today.getFullYear() - birthDate.getFullYear();
						var m = today.getMonth() - birthDate.getMonth();
						if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
							age--;
						}

						$("#age").val(age);

						console.log(age);

						console.log(month, day, year);

						//remove errors
						$("#msg").text("");
						$("#username").css("border-bottom", " 1px " + "solid" + " #dcdcdc");
						$(".placeholder.email").removeClass("text-danger");

						//enable button
						if ($('#username').val() != "" && $("#b_date").val() != "") {
							$('#btnsubmit').prop('disabled', false);
						} else {
							$('#btnsubmit').prop('disabled', true);
						}

					}
				});

				$("#username").on('keydown keypress keyup', function (event) {
					$("#msg").text("");
					$(".placeholder.email").removeClass("text-danger");


					//enable button
					if ($('#username').val() != "" && $("#b_date").val() != "") {
						$('#btnsubmit').prop('disabled', false);
					} else {
						$('#btnsubmit').prop('disabled', true);
					}
				});
			</script>



					<div class="form-group gender">
						<input class="sr-only" type="radio" id="gender-male" value="Male" checked='checked' required
							style="display: none;">
						<select class="text-left s-a mh-40 select form-control" name="gender" id="gender" required>
							<option value="" disabled selected></option>

							<?php
							$arrGender = ['male' => 'Man', 'female' => 'Woman', 'Non-binary' => 'Non-binary', 'N/A' => 'Prefer not to say'];
							foreach ($arrGender as $key => $value) {
								$selected = ((isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) && (strtolower($arrCustomer[0]['gender']) == strtolower($key) || (isset($_SESSION['temp_data']) && strtolower($_SESSION['gender']) == strtolower($key)))) ? 'selected' : '';
								?>
								<option value="<?= $key ?>" <?= $selected ?>><?= $arrTranslate[$value] ?></option>
							<?php } ?>

						</select>
						<label class="placeholder" for="gender">I Identify as</label>
					</div>

					<?php
					if (isset($_SESSION['user_type']) && trim($_SESSION['store_type']) == 'vs') {
						?>
						<input type="hidden" name="country" id="country" value="vietnam">
						<input type="hidden" name="province" id="province" value="N/A">
						<input type="hidden" name="city" id="city" value="N/A">
					<?php } else { ?>
						<input type="hidden" name="country" id="country" value="philippines">

						<div class="form-group">
							<select class="text-left s-a mh-40 select form-control" name="province" id="province" required>

								<?php

								if ($specProvince != "") {
									echo '<option value="n" disabled selected></option>';
									echo '<option value="' . $specProvince . '">' . ucwords(str_replace("-", " ", $specProvince)) . '</option>';
								}

								for ($i = 0; $i < sizeOf($arrCC); $i++): ?>

									<option value="<?= $arrCC[$i]["province"] ?>" <?php
									  if (isset($_SESSION['customer_id']) && $arrCustomer[0]['province'] == $arrCC[$i]['province']) {
										  echo 'selected="selected"';
									  }
									  ?>>
										<?= ucwords(str_replace("-", " ", $arrCC[$i]["province"])) ?>
									</option>

								<?php endfor ?>

							</select>
							<label class="placeholder" for="province">Province</label>
						</div>

						<?php if (isset($_SESSION['customer_id'])): ?>

							<script> // LOAD CITY AND BARANGAY AUTOMATICALLY
								var current_city = '<?= $arrCustomer[0]['city'] ?>';

								setTimeout(function () {
									$('#city').val(current_city.replace(/\s/g, '-')).change();

									<?php if ($arrCustomer[0]['barangay'] != ''): ?>

										var current_barangay = '<?= $arrCustomer[0]['barangay'] ?>';

										setTimeout(function () {
											$('#barangay').val(current_barangay.replace(/\s/g, '-')).change();
										}, 1200);

									<?php endif ?>
								}, 800);
							</script>

						<?php endif ?>

						<div class="form-group">
							<div class="p-c-sect">
								<select class="text-left mh-40 select form-control" name="city" id="city" required>
									<option value="n" selected disabled></option>
								</select>
								<label class="placeholder" for="city"><?= $arrTranslate['City'] ?></label>
							</div>
						</div>

						<input type="hidden" name="barangay" value="N/A">
					<?php } ?>
				</div>


				
			</div>


			<div class=" account-signup-contact mt-4">
				<p class="font-bold text-uppercase mt-2" style="font-size: 18px; color: #342C29;">
					<?= $arrTranslate['Contact Details'] ?></p>
				<div class="form-row no-gutters mt-3">
					<div class="col-12">
						<div class="form-group">
							<?php if (isset($_SESSION['customer_id'])): ?>
								<input type="hidden" name="email_confirmation"
									value="<?= ($arrCustomer[0]['email_address'] != '') ? $arrCustomer[0]['email_address'] : '' ?>">
							<?php endif ?>
							<input type="email" name="email" class="form-control" id="s_email"
								value="<?= get_customer_data('email_address') ?>" required>
							<label class="placeholder" for="s_email"><?= $arrTranslate['Email Address'] ?></label>
							<div class="emailAvail"></div>
						</div>
					</div>
					<!-- email end -->


					<div class="col-12 d-flex align-items-end mt-2">
						<div class=" d-flex  form-group  mr-2"
							style='padding-left: 0px; padding-bottom: 6px; margin-right: 2px; border-bottom: 1px solid #dee2e6 '>
							<select class="text-left select form-control" name="country_codes" id="country_codes" required style="padding-bottom: 12px !important;">

								<?php

								usort($countryArray, function ($a, $b) {
									return $a['code'] - $b['code'];
								});
								$countryArray = array_reverse($countryArray);
								$get_num = get_customer_data('phone_number');
								echo '<option></option>';
								$phone_number = '';
								foreach ($countryArray as $key => $value) {
									$ac_length = strlen($value['code']);
									if ($get_num == '') {
										if (isset($_SESSION['user_type']) && trim($_SESSION['store_type']) == 'vs') {
											if ($value['code'] == '84') {
												echo '<option  value="' . $value['code'] . '" selected>' . $value['key'] . ' +' . $value['code'] . '</option>';
												break;
											}
										} else {
											if ($value['code'] == '63') {
												echo '<option  value="' . $value['code'] . '" selected>' . $value['key'] . ' +' . $value['code'] . '</option>';
												break;
											}
										}
									} elseif ($value['code'] == substr($get_num, 0, $ac_length)) {
										echo '<option  value="' . $value['code'] . '" selected>' . $value['key'] . ' +' . $value['code'] . '</option>';
										$phone_number = substr($get_num, $ac_length);
										break;
									}
								}

								?>

							</select>
							<!-- <label class="placeholder" for="country_codes">Countries</label> -->
						</div>
						<div class="form-group  country_num" style="padding-left: 0px; padding-right: 0px; width: 100%;">
							<input type="text"
								style="border-bottom-left-radius: 0px; border-top-left-radius: 0px;"
								name="mnum"
								class="form-control <?= (get_customer_data('phone_number') != '') ? 'active' : '' ?>"
								id="mnum" class="mnum" value="<?= $phone_number ?>" required>
							<label class="placeholder" for="mnum">Mobile Number</label>
							<!-- <span class="mobile-format <?= (get_customer_data('phone_number') == '') ? 'hide' : '' ?>"></span> -->
						</div>
					</div>
					<!-- phone end -->

				</div>

			</div>

			<script type="text/javascript">
				let code = '';
				code = $('#country_codes').val();
				$('.mobile-format').text('+' + code);
				// (code.length == 3) ? $('.mobile-number').css('padding-left', '60px') : '';
				// (code.length == 4) ? $('.mobile-number').css('padding-left', '65px') : '';
				$('#country_codes').change(function () {
					code = $(this).val();
					// $('.mobile-number').css('padding-left', '50px');
					// (code.length == 3) ? $('.mobile-number').css('padding-left', '60px') : '';
					// (code.length == 4) ? $('.mobile-number').css('padding-left', '65px') : '';
					$('.mobile-format').text('+' + code);
				});
			</script>
			<div class="text-center mt-4">

				<?php if (!isset($_SESSION['customer_id'])): ?>

					<?php if (isset($_SESSION['language_setting']) && $_SESSION['language_setting'] == 'vn') { ?>
						<p class="text-secondary mb-3">
							<?= $arrTranslate['By clicking Register, you have read and agreed to our Terms and Conditions'] ?>.
						</p>
					<?php } else { ?>
						<div class="d-flex align-items-center mb-3">
							<!-- <input type="checkbox" id="agree" name="agree" required class="sr-only checkbox"> -->
						

							<!-- <div class="d-flex align-items-center mb-3">
								<div style="display: flex; align-items: center;">
									<input type="checkbox" id="agree" name="agree" required class="sr-only checkbox">
									<label for="agree" class="custom-checkbox" style="margin-right: 10px;"></label>
								</div>
								<div class="flex-grow-1 text-center">
									<label for="agree" class="text-secondary">
									By clicking Register, you have read and agreed<br />
									to our <span class="font-bold text-primary btn-terms">Terms and Conditions</span>.
									</label>
								</div>
							</div>
 -->

							
							<div class="d-flex text-center">
								<div class="flex-grow-1 mr-3">
									<input type="checkbox" id="agree" name="agree" required class="sr-only checkbox">
									<label for="agree" class="custom-checkbox" style="margin-right: 5px;"></label>
								</div>
								<div class="flex-grow-1 text-center">
								<span class="text-secondary text-center" style="font-size: 18px; text-align: left; display: block;">
								By clicking Sign up, you have read and agreed to our <br />
								<span id="termsText" class="font-bold text-primary btn-terms"
									style="cursor: pointer; text-decoration: underline; font-size: 18px;">
									Terms and Conditions.
								</span>
								</span>
								</div>
							</div>

							<script>
								const agreeCheckbox = document.getElementById('agree');
								const termsText = document.getElementById('termsText');

								termsText.addEventListener('click', () => {
									agreeCheckbox.checked = true; // Check the checkbox
									agreeCheckbox.dispatchEvent(new Event('change')); // Trigger any associated change event
								});


								
							</script>


							<style>
								.modal-content {
										width: 100% !important;
										padding: 40px 24px 24px 24px;
										border-radius: 16px;
										height: calc(100vh - 20px) !important; 
									
									}
							</style>

							<div class="d-none" id="termsAndCondition">
								<div class="overlay-backdrop"  id="overlayBackdrop"></div> <!-- Background overlay -->
								<div class="modal-content">
									<center>
									<div class="overlay-title"
										style="background: white; margin-top: 24px; max-width: 696px; border-top-left-radius: 16px; border-top-right-radius: 16px; margin-bottom: 20px;">
										<div class="d-flex justify-content-between align-items-center">
											<span class="close-overlay" data-reload="no">
											<img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="close" class="img-fluid">
											</span>
											<div class="text-center w-100">
											<span class="h2">Terms &amp; Conditions</span>
											</div>
											<span></span>
										</div>
									</div>
									</center>

									<div style="background:white; max-width: 700px; margin: auto; padding: 24px; text-align: start;" class="overlay-body align-items-start">
									<p>
											Welcome to the SUNNIES BY CHARLIE INC. ("Sunnies Specs") Integrated System. By using the
											Sunnies Specs Integrated System and its related services, products, and software
											(collectively, "SSIS"), you agree to be bound by these terms and conditions of use ("Terms
											and Conditions"). You also accept the Terms and Conditions when you create an account, sign
											in as a guest, or log in to SSIS. Additional or separate terms may apply to your
											interactions with your use of individual services or features available on SSIS, such as
											comments and reviews. To the extent that the provisions of any additional or separate terms
											conflict with these Terms and Conditions, the provisions of the additional or separate terms
											will govern. Sunnies Specs may make changes to SSIS and the Terms and Conditions and render
											them effective immediately upon posting in SSIS without prior notice. It is your
											responsibility to review the Terms and Conditions for updates or changes. If you do not
											agree with the Terms and Conditions, you should not use SSIS for any purpose therefore.
										</p>
										<br>
										<ol>
											<li>
												Definitions and Interpretation
												<br>
												Any reference in these Terms and Conditions to any provision of a statute shall be
												construed as a reference to that provision as amended, re-enacted or extended at the
												relevant time. Whenever the words "include", "includes" or "including" are used, they
												will be deemed to be followed by the words "without limitation". Unless expressly
												indicated otherwise, all references to a number of days mean calendar days, and the
												words "month" or "monthly", or any references to a number of months, means calendar
												months. Clause headings are inserted for convenience only and shall not affect the
												interpretation of these Terms and Conditions. In the event of a conflict or
												inconsistency between any two or more provisions under these Terms and Conditions,
												whether such provisions are contained in the same or different documents, such conflict
												or inconsistency shall be resolved in favor of Sunnies Specs and the provision which is
												more favorable to Sunnies Specs shall prevail.
												<br><br>
												Unless the context otherwise requires, the following expressions shall have the
												following meanings in these Terms and Conditions.
												<ul>
													<li>"Account" means the Customer's SSIS account created once the registration
														procedure described in these Terms and Conditions has been completed.</li>
													<li>"Business Day" means a day (excluding Saturdays and Sundays) on which banks
														generally are open for business in the Philippines.</li>
													<li>"Customer" means an authorized user of SSIS, who must be an individual over the
														age of 18 and who possess a valid credit or debit card issued by a bank deemed
														acceptable by Sunnies Specs.</li>
													<li>"Intellectual Property" means all copyright, patents, utility innovations,
														trademarks and service marks, geographical indications, domain names, layout
														design rights, registered designs, design rights, database rights, trade or
														business names, rights protecting trade secrets and confidential information,
														rights protecting goodwill and reputation, and all other similar or
														corresponding proprietary rights and all applications for the same, whether
														presently existing or created in the future, anywhere in the world, whether
														registered or not, and all benefits, privileges, rights to sue, recover damages
														and obtain relief or other remedies for any past, current or future
														infringement, misappropriation or violation of any of the foregoing rights.</li>
													<li>"Listing Price" means the price of Products for sale to Customers, as stated on
														SSIS.</li>
													<li>"Order" refers to the Customer's order for Products he/she wishes to purchase
														through SSIS, which has been placed in accordance with the procedures specified
														in these Terms and Conditions.</li>
													<li>"Password" refers to the valid password that a Customer who has registered an
														account with Sunnies Specs may use in conjunction with the Username to access
														the relevant Services.</li>
													<li>"Personal Data" means data, whether true or not, that can be used to identify,
														contact or locate the Customer. Personal Data includes the Customer's name,
														email address, phone number, date of birth.</li>
													<li>"Product" means a product (including any installment of the product or any parts
														thereof) available for sale to Customers through SSIS.</li>
													<li>"Sunnies Specs" shall refer to SUNNIES BY CHARLIE INC., a company incorporated
														pursuant to the laws of the Philippines, with SEC registration number
														CS201318005 and principal office at 10 Calle Industria, Quezon City, Metro
														Manila.</li>
													<li>"Trademarks" means the trademarks, service marks, trade names and logos
														belonging to Sunnies Specs and used and displayed on SSIS.</li>
													<li>"Username" refers to the unique login identification name or code which
														identifies a Customer who has an account with Sunnies Specs.</li>
												</ul>
											</li>
											<li>
												Use of SSIS
												<br>
												Registration for SSIS: The Customer may register an Account with SSIS by supplying
												Sunnies Specs with his or her name, address, postal code, email address, and such other
												personal information as may be required by the SSIS registration form. The Customer
												shall be responsible for the accuracy of all information which he/she provides Sunnies
												Specs and hereby agrees to indemnify and hold Sunnies Specs free and harmless against
												any claim or liability for the unauthorized or illegal disclosure or use of such
												personal information made by Customer.
												<br><br>
												Valid E-mail required for registration: The Customer must be registered with a valid
												personal email address that is accessed by him regularly. Sunnies Specs reserves the
												right to terminate without notice any Accounts which have been registered with
												erroneous, false or non-existent e-mail addresses and other information.
												<br><br>
												Security of personal information: The Customer is responsible for maintaining the
												confidentiality of his or her Account, Password, and other personal information, and
												Sunnies Specs shall not be held liable or responsible in any manner for any breach of
												security or unauthorized access or use of the Customer's Account resulting from the
												Customer's failure to maintain the confidentiality of such information.
												<br><br>
												Access of SSIS: Sunnies Specs is entitled to treat any use or access of SSIS under a
												Customer's Account as being made by the Customer himself and no other person, and that
												likewise any information, data or communications referable to the Customer's Account
												shall be deemed to have been supplied by the Customer himself and no other person.
												Sunnies Specs shall likewise be fully indemnified by the Customer against any claim,
												action, liability and/or for all losses attributable to any use of SSIS referable to
												said Customer's Username and Password.
												<br><br>
												Modification of SSIS: Sunnies Specs may, from time to time, and without giving any
												reason or prior notice, upgrade, modify, suspend or discontinue the provision of or
												remove, whether in whole or in part, SSIS or any product or service provided through the
												same, without liability for doing so.
												<br><br>
												Rights of Sunnies Specs: Sunnies Specs reserves the right, but not the obligation to:
												<br>
												<ul>
													<li>monitor, screen or otherwise control any activity, content or material on SSIS,
														and, in its sole and absolute discretion, investigate any violation of these
														Terms and Conditions and take any corresponding action it deems appropriate to
														protect its interests as well as those of its Customers and the public in
														general;</li>
													<li>refuse, prevent, or restrict access of any Customer to SSIS for any reason at
														its absolute discretion;</li>
													<li>report any activity it suspects to be in violation of any applicable law,
														statute or regulation to the appropriate authorities, and to cooperate with such
														authorities and provide relevant information as may be required therefore;</li>
													<li>request any information and data from the Customer in connection with the use of
														SSIS at any time; and</li>
													<li>deny, restrict, suspend or revoke service to any Customer who refuses to supply
														the information and/or data requested under the previous item, or where Sunnies
														Specs has reasonable ground to believe that inaccurate, misleading or fraudulent
														information and/or data has been provided by the Customer.</li>
												</ul>
											</li>
											<li>
												Intellectual Property
												<br>
												The Intellectual Property in and to SSIS and the materials found thereon are owned,
												licensed to or controlled by Sunnies Specs and its licensors or service providers.
												Unless the prior written authorization of Sunnies Specs or the appropriate copyright or
												trademark owners has been obtained, no part or parts of SSIS, SSIS or the materials
												found thereon may be reproduced, reverse engineered, decompiled, disassembled,
												separated, altered, distributed, republished, displayed, broadcast, hyperlinked,
												mirrored, framed, transferred or transmitted in any manner or by any means or stored in
												an information retrieval system or installed on any servers, system or equipment.
												Neither shall the Customer remove or alter the trademarks, logos, copyright notices,
												serial numbers, labels, tags or other identifying marks, symbols or legends affixed to
												any Products without the aforementioned prior written authorization of Sunnies Specs or
												the appropriate copyright or trademark owners. Nothing in SSIS, SSIS and in these Terms
												and Conditions shall be construed as granting, by implication, estoppel, or otherwise,
												any license or right to use any materials or trademarks displayed on SSIS or integrated
												system, without the prior written authorization of Sunnies Specs or the appropriate
												copyright or trademark owners. Sunnies Specs reserves the right to enforce its
												Intellectual Property Rights and those of its licensors or service providers to the
												fullest extent of the law.
											</li>
											<li>
												Force Majeure
												<br>
												Sunnies Specs shall not be liable for non-performance, error, interruption or delay in
												the performance of its obligations under these Terms and Conditions or any part thereof,
												or for any inaccuracy, unreliability or unsuitability of SSIS or its contents if this is
												due, in whole or in part, directly or indirectly to an event or failure which is beyond
												Sunnies Specs' reasonable control.
											</li>
											<li>
												Waivers
												<br>
												Sunnies Specs' failure to enforce these Terms and Conditions shall not constitute a
												waiver of these terms or of any continuing or succeeding breach of such provision, or a
												waiver of such provision, or a waiver of any right under these Terms and Conditions, and
												such failure shall not affect the right of Sunnies Specs to later enforce the same.
											</li>
											<li>
												Severability
												<br>
												If at any time any provision of these Terms and Conditions shall be or shall become
												illegal, invalid or unenforceable in any respect, the legality, validity and
												enforceability of the remaining provisions of these Terms and Conditions shall not be
												affected or impaired thereby, and shall continue in force and continue to be binding.
											</li>
											<li>
												Governing Law
												<br>
												These Terms and Conditions shall be governed by, and construed in accordance with the
												laws of Philippines. Any legal action arising from these Terms and Conditions initiated
												by a party shall be brought exclusively in the proper courts of Quezon City, Metro
												Manila, and the prevailing party shall be entitled to reasonable attorney's fees.
											</li>
											<li>
												Amendments
												<br>
												Sunnies Specs reserves the right to amend these Terms and Conditions at any time by
												posting said amendments on SSIS or by using such other method of notification as it may
												determine. Customers who make purchases of Sunnies Specs' Products through SSIS must
												read and agree to the latest Terms and Conditions in force at the time their Orders are
												placed and shall be bound thereby. Customers who successfully place Orders in this
												manner are deemed to have accepted the Terms and Conditions in force at the time the
												Orders are placed.
											</li>
											<li>
												Entire Agreement
												<br>
												These Terms and Conditions of Sale shall constitute the entire agreement between Sunnies
												Specs and the Customer relating to the subject matter hereof, and supersedes and
												replaces in full all prior understandings, communications and agreements whatsoever with
												respect to the subject matter hereof.
											</li>
											<li>
												General Provisions
												<br>
												Cumulative rights and remedies: Unless otherwise provided under these Terms and
												Conditions, Sunnies Specs’ rights and remedies under these Terms and Conditions are
												cumulative and are without prejudice and in addition to any rights or remedies Sunnies
												Specs may have in law or in equity. Further, no exercise by Sunnies Specs of any one
												right or remedy under these Terms and Conditions, or at law or in equity, shall operate
												so as to hinder or prevent its exercise of any other such right or remedy under these
												Terms and Conditions, or at law;
												<br>
												Correction of errors: Any typographical, clerical or other error or omission in any
												acceptance, invoice or other document on Sunnies Specs’ part shall be subject to
												correction without any liability on its part.
												<br>
												Currency: Money references under these Terms and Conditions shall be in Philippines
												Pesos when delivery is to be made in the Philippines. For deliveries to be made in any
												location outside of the Philippines, the money reference shall be in United States
												Dollars.
												Subcontracting and delegation: Sunnies Specs reserves the right to delegate or
												subcontract the performance of any of its functions in connection with the performance
												of its obligations under these Terms and Conditions and reserves the right to use any
												service providers, subcontractors and/or agents on such terms as Sunnies Specs deems
												appropriate.
												<br>
												Disclaimer: Sunnies Specs cannot and does not represent or warrant that SSIS or its
												server will be error-free, uninterrupted, free from unauthorized access (including
												third-party hackers or denial of service attacks), or otherwise meet your requirements.
												SSIS and all information, content, materials, products, services, and user content
												included on or otherwise made available to you through SSIS are provided by Sunnies
												Specs on an "as is," "as available" basis, without representations or warranties of any
												kind. Sunnies Specs makes no representations or warranties of any kind, express or
												implied, as to the operation of SSIS, the accuracy or completeness of SSIS contents, or
												that emails sent from Sunnies Specs are free of malware or other harmful components. You
												expressly agree that your use of SSIS is at your sole risk. Sunnies Specs will not be
												liable for any damages of any kind arising from the use of SSIS or the site contents
												including, without limitation, direct, indirect, consequential, punitive, and
												consequential damages, unless otherwise specified in writing. To the full extent
												permitted by applicable law, Sunnies Specs disclaims any and all representations and
												warranties with respect to SSIS and SSIS contents, whether express or implied,
												including, without limitation, warranties of title, merchantability, and fitness for a
												particular purpose or use.
												<br><br>
												The views and opinions expressed by users of this integrated system are solely those of
												the original authors and other contributors. These views and opinions do not necessarily
												represent those of Sunnies Specs, nor its staff, and/or any/all contributors to this
												integrated system. Sunnies Specs is not responsible for the accuracy of any of the
												information supplied by the users.
											</li>
										</ol>
										<br>
										<p>PRIVACY POLICY</p>
										<br>
										<ol>
											<li>
												Information Collected by Sunnies Specs
												<br>
												This Privacy Policy is intended to assist the Customer in understanding what information
												we gather about you when you utilize this system, how we use that information, and the
												safeguards we have in place for the information. By using SSIS and its related services,
												products, and software (collectively, "SSIS"), you agree to be bound by this Privacy
												Policy and accept the terms hereof without any condition or reservation. Sunnies Specs
												may make changes to SSIS and the Privacy Policy and render them effective immediately
												upon posting in SSIS without prior notice. It is your responsibility to review the
												Privacy Policy for updates or changes. If you do not agree with the Privacy Policy, you
												should not use SSIS for any purpose therefore.
												<br><br>
												This Privacy Policy applies only to information collected offline by SUNNIES BY CHARLIE
												INC. ("Sunnies Specs").
												<br><br>
												General Browsing: Sunnies Specs gathers:
												<ol>
													<li>Navigational information on the frequency of visits by Customers to various
														parts of SSIS;</li>
													<li>Information on technical efficiencies of SSIS including time to connect to the
														site, time to download pages, etc.</li>
												</ol>
												Sunnies Specs may likewise use third parties to provide comparative information on the
												performance of SSIS, provide support for operating SSIS, monitor site activity, conduct
												surveys, maintain our databases, process product reviews and administer and monitor
												emails, surveys, contests.
												<br>
												Collection of Personal Information: Sunnies Specs may request the Customer to provide
												personal information in connection with his or her use of SSIS from time to time,
												including one's name, email address, phone number, home address, date of birth, gender,
												comments on experiences on SSIS or with Sunnies Specs' Products, your questions and
												messages or reminders you create, and other similar information.
												<br><br>
												Use of Personal Information Supplied by the Customer: Sunnies Specs may use the personal
												information collected from Customers for purposes of:
												<ol>
													<li>Registration for SSIS;</li>
													<li>Processing Orders;</li>
													<li>Contacting the Customer to respond to inquiries, to coordinate regarding matters
														concerning Orders placed in SSIS, or to perform other similar Customer Service
														functions;</li>
													<li>Verifying and carrying out financial transactions in relation to payments made;
													</li>
													<li>Auditing the downloading of data from SSIS;</li>
													<li>Carrying out research on our user demographics;</li>
													<li>Sending the Customer emails on news, promotions, contests, surveys, or
														newsletters, provided that the Customer has not objected to being contacted for
														this purpose.</li>
												</ol>
												Use of Disclosed Information by Third Parties: Sunnies Specs may share, transfer, and
												disclose personal information collected from Customers with third parties and affiliates
												in order to accomplish the above mentioned purposes. Sunnies Specs shall endeavor to
												keep its Customers' personal information secure from unauthorized access, collection,
												use, or disclosure by said third parties and affiliates, and shall retain such personal
												information only for as long as necessary to achieve the above mentioned purposes.
												Sunnies Specs may also share, transfer, and disclose personal information to government
												authorities, at their request and only when legally required to do so.
											</li>
											<li>
												Information Collected by Sunnies Specs
												<br>
												Sunnies Specs ensures that all information collected will be safely and securely stored.
												Sunnies Specs achieves this purpose by restricting access to personal information and
												maintaining technology products to prevent unauthorized computer access. Sunnies Specs
												likewise utilizes Secure Sockets Layer ("SSL") security protocols to manage and secure
												server authentication, client authentication and encrypted communication between servers
												and clients.
												<br>
												Nonetheless, the Customer is responsible for maintaining the confidentiality of his or
												her Account, Password, and other personal information, and Sunnies Specs shall not be
												held liable or responsible in any manner for any breach of security or unauthorized
												access or use of the Customer's Account resulting from the Customer's failure to
												maintain the confidentiality of such information. Sunnies Specs is entitled to treat any
												use or access of SSIS under a Customer's Account as being made by the Customer himself
												and no other person, and that likewise any information, data or communications referable
												to the Customer's Account shall be deemed to have been supplied by the Customer himself
												and no other person. Sunnies Specs shall likewise be fully indemnified by the Customer
												against any action and for all losses attributable to any use of SSIS referable to said
												Customer's Username and Password.
												<br><br>
												If it is believed that the privacy of a Customer's Account has been breached, it is
												recommended that Customer contact Sunnies Specs immediately by e-mail at
												help@sunniesspecs.com or by giving us a call at +63 917 6321 483.
											</li>
											<li>
												Links to Other integrated systems
												<br>
												SSIS may contain links to other integrated systems that may not be operated or owned by
												Sunnies Specs. Sunnies Specs is not responsible for the privacy practices, advertising,
												products or the content of such integrated systems that are not owned or operated by it.
												Links that appear on SSIS are not to be necessarily be deemed by implication to be owned
												or operated or endorsed by, or affiliated with Sunnies Specs. It is encouraged that the
												Customer review the separate privacy policies of each of these integrated systems.
											</li>
											<li>
												Sunnies Specs Newsletters
												<br>
												Customers may subscribe to Sunnies Specs' newsletter ("the Newsletter") by filling out
												the newsletter subscription form on SSIS. This will allow the Customer to receive
												on-line exclusive information about special offers, media events, new products and much
												more. Customers wishing to stop receiving future deliveries of the Newsletter may simply
												unsubscribe at any time through SSIS or by clicking the "unsubscribe" link available in
												every e-mail from the Newsletter.
											</li>
											<li>
												Amendments
												<br>
												Sunnies Specs reserves the right to amend the terms of its Privacy Policy at any time
												and without giving any reason or prior notice by posting said amendments on SSIS or by
												using such other method of notification as it may determine at its discretion.
											</li>
											<li>
												Rights of Sunnies Specs to Disclose
												<br>
												Sunnies Specs has the right to disclose the personal information collected by it to any
												legal, regulatory, governmental, tax, law enforcement or other authorities or the
												relevant right owners, if there are reasonable grounds to believe that disclosure of
												said information is necessary for the purpose of meeting any obligations, requirements
												or arrangements, whether voluntary or mandatory under law, or for complying with the
												legal orders, or cooperating with investigations conducted of any nature by the proper
												authorities. To the extent permissible by applicable law, the Customer agrees not to
												take any action and/or waives his or her rights to take any action against Sunnies Specs
												for the disclosure of any personal information under these circumstances.
											</li>
											<li>
												Name And Likeness Authorization And Release
												<br>
												For valuable consideration, receipt and sufficiency of which is hereby acknowledged, I
												hereby grant Sunnies Specs, its parents, subsidiaries, affiliates, agents, and assigns
												(collectively "Sunnies Specs”) the absolute and irrevocable right and permission to use,
												re-use, publish, re-publish, publicly display, perform, transmit, exhibit, and reproduce
												my name, address, e-mail address, statements, video, voice, photograph, or other
												likeness, in whole or in part, individually or in conjunction with other material,
												including without limitation, text, photographs, video, or images, in any medium
												(whether now known or hereafter invented) and for any and all purposes, including but
												not limited to advertising, publicity, promotion, contests, packaging, and trade,
												throughout the world without restriction as to manner, frequency, or duration of usage.
												I agree that my name, address, e-mail address, statements, video, voice, photograph, or
												other likeness may be used with whatever visuals, copy, or other elements Sunnies Specs
												may determine, in its sole discretion, for all media usage (including, but not limited
												to, usage on the Internet), and that nothing herein shall obligate Sunnies Specs to use
												my name, address, e-mail address, statements, video, voice, photograph, or other
												likeness. I acknowledge that any and all contents uploaded by me on this integrated
												system (the “Content”) will be subject to Sunnies Specs’ prior approval. Content must
												not be offensive, insulting, defamatory, slanderous, pornographic, vulgar, obscene, or
												blasphemous, may not in any way violate principles of public order and morality or
												potentially cause damage in any way to minors; may not contain religious or political
												declarations, nor may not be illegal for any reason in any jurisdiction. Content must
												not cause either directly or indirectly damages to any third parties, or solicit illegal
												behavior. Furthermore, Content must not violate the laws in force – including but not
												limited to copyright law and data protection law – or contain distinctive marks,
												intellectual or industrial property rights or other third-party rights; must not include
												content protected under copyright without express authorization from relevant copyright
												holder. Contents may not contain trademarks other than Sunnies Specs, must not contain
												viruses or any software aimed at damaging or interfering in any way with this integrated
												system, including software interfering with the functionalities of the Sunnies Specs or
												its service provider’s’ servers, software to grant access to data or other information
												and communications stored in such servers. Content must not contain advertising or
												promotional materials or other messages not relevant to the brand. In Sunnies Specs sole
												opinion, content which does not meet any of these requirements and/or which Sunnies
												Specs deems negative, offensive or controversial in any way, will be disqualified and
												blocked from public view. I acknowledge that any illegal behavior as well as any
												behavior that may result in danger to third party will be reported to the competent
												authorities. I further agree that all materials produced pursuant to this Authorization
												and Release are and shall remain the sole and exclusive property of Sunnies Specs, and
												that I will not receive any kind of payment, remuneration, compensation or consideration
												of any kind. I represent and warrant that I have the right to grant Sunnies Specs the
												above-mentioned rights without obtaining the permission of, or making any payments to,
												any third party or entity. I hereby waive any right I may have to inspect and approve
												the finished product and the advertising/publicity copy that may be used, and hereby
												release and discharge, indemnify and hold harmless Sunnies Specs and its officers,
												directors, employees, contractors, agents, and any designees, including without
												limitation, Sunnies Specs licensees, successors, and assigns, from and against any and
												all liabilities, losses, claims, demands, costs (including without limitation attorneys
												fees) and expenses arising out of or in connection with any use granted hereunder,
												including but not limited to any claims for defamation, invasion of privacy, right of
												publicity or copyright infringement. Regarding statements or representations
												attributable to me and provided by me, I hereby warrant and represent that such
												statements or representations accurately reflect my true and honest experience and/or
												belief. I agree to execute such additional documents confirming this as Sunnies Specs
												may reasonably require. I represent that I am over the age required by law to enter into
												binding agreements, that all the people shot in the video/photographs are over the age
												required by law and that I have no conflicting contractual obligations that would
												interfere with my granting the rights herein granted. If I am under age, I represent
												that I was assisted by my parent or legal guardian and that such parent or guardian’s
												consent was obtained on my behalf to the terms and conditions of this Authorization and
												Release. If I am underage but either do not have a parent or legal guardian, or have not
												obtained their consent to be bound by these terms and conditions, I acknowledge that I
												am not allowed to, and shall not enter SSIS nor use the same for any purpose, and upon
												Sunnies Specs’ reasonable belief and information that in fact I am underage, Sunnies
												Specs reserves the right to take all reasonable steps to modify or remove Content which
												I uploaded to SSIS and do all other acts and deeds necessary to protect its interests.
												Sunnies Specs further reserves the right to refuse service, terminate accounts or cancel
												orders in its sole discretion.
											</li>
										</ol>
									
									</div>

									<span  data-reload="no">
									<div class="close-overlay btn btn-primary"  data-reload="no">I agree</div>
									</span>

											
								</div>
							</div>

							

						</div>

						
					<?php } ?>

					
					<?php include("terms-and-condition.php") ?>
					<script type="text/javascript">



					$('.btn-terms').click(function () {
						const termsModal = $('#termsAndCondition');

						// Toggle modal visibility
						if (termsModal.hasClass('d-none')) {
							termsModal.removeClass('d-none').addClass('d-flex'); // Show the modal and overlay
						} else {
							termsModal.removeClass('d-flex').addClass('d-none'); // Hide the modal and overlay
						}
					});

					// Close modal when clicking the close button
					$('.close-overlay').click(function () {
						$('#termsAndCondition').removeClass('d-flex').addClass('d-none'); // Hide modal
					});

					

					</script>

				<?php endif ?>

				<button type="submit" class="btn btn-primary"
					id="submitCustomer"><?= isset($_SESSION['customer_id']) ? $arrTranslate['Proceed'] : "Sign Up" ?></button>
				<p class="hide text-danger mt-4 required-warning">Please make sure all required fields are filled</p>

				<?php if (isset($_SESSION['email_taken'])): ?>

					<p class="mt-4 text-danger"><?= $_SESSION['email_taken'] ?></p>

				<?php endif ?>

			</div>
		</form>
	</div>
<?php } ?>
<script>
	let arrVietnam = <?= json_encode($arrVietnam); ?>;
	let user_type = <?= $_SESSION['store_code']; ?>;
	$(document).ready(function () {
		$("input[type=radio]").click(function () {
			if ($(this).attr('value') == 'yes') {
				$(this).parent().parent().parent().next().removeClass('col-lg-10 col-md-10 col-xs-12 d-none').addClass('col-lg-11 col-md-11 col-xs-12')
			} else {
				$(this).parent().parent().parent().next().removeClass('col-lg-11 col-md-11 col-xs-12').addClass('col-lg-11 col-md-11 col-xs-12  d-none');
			}
			if ($(this).attr('value') == 'yes') {
				$(this).parent().parent().parent().next().find('input').focus();
				$(this).parent().parent().parent().next().find('input').attr('required', true);
			} else {
				$(this).parent().parent().parent().next().find('input').removeAttr('required');
			}
		});
		if (user_type == 142 || user_type == 150) {
			//$("#city").removeAttr('disabled');
			let city = '';
			for (let i = 0; i < arrVietnam.length; i++) {
				let sel = (typeof current_city != "undefined" && current_city == arrVietnam[i].city.replace(" ", "-")) ? 'selected' : '';
				city += '<option value="' + arrVietnam[i].city.replace(" ", "-") + '" ' + sel + '>' + arrVietnam[i].city + '</option>';
			}
			setTimeout(function () {
				$('#city').html(city);
			}, 850);
		} else {
			$("#barangay").parent().parent().parent().show();
		}
		$('#country').change(function () {
			if ($(this).val() == 'viet-nam') {
				$(this).parent().parent().attr('class', 'col-6');
				$("#city").removeAttr('disabled');
				let city = '';
				for (let i = 0; i < arrVietnam.length; i++) {
					city += '<option value="' + arrVietnam[i].city.replace(" ", "-") + '">' + arrVietnam[i].city + '</option>';
				}
				$('#city').html(city);
				$('#city').parent().parent().show();
			} else {
				$('#province').change();
				$("#barangay").html('<option value="n" selected></option>');
				$("#barangay").parent().parent().parent().show();

			}
		});
		<?php if (isset($_GET['type']) && $_GET['type'] == 'sign-up') { ?>
			$('.account-option').click();
		<?php } ?>
	});
</script>