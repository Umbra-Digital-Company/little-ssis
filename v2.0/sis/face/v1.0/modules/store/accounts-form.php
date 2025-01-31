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



	<div class="wrapper-guest customer-guest mt-2">
		<h1 class="text-start mb-4 font-weight-bold text-uppercase" style="font-size: 18px;">GUEST ACCOUNT</h1>
		<form action="/v2.0/sis/face/func/process/guest_register_manual.php?path_loc=v1.0&bpage=<?= $_GET['bpage'] ?>" method="post" class="form-guest"
			id="guestForm" id="guestForm">
				<input type="hidden" name="specs_branch" value="<?= 'This Store Name' ?>">
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
					<!-- <option value="1">0-12</option>
					<option value="13">13-17</option> -->
					<option value="18">18-24</option>
					<option value="25">25-34</option>
					<option value="35">35-44</option>
					<option value="45">45-54</option>
					<!-- <option value="55">55-64</option>
					<option value="65">65+</option> -->
				</select>
				<label class="placeholder" for="age_range">Age Group</label>
			</div>


			<div class="text-center mt-4">
				<input type="submit" id="guest-submit" class="btn btn-primary" value="<?= $arrTranslate['Proceed'] ?>"
					disabled>
			</div>
			<div class="text-center mt-4">
				<a href="/v2.0/sis/face/v1.0/?page=account-form&type=log-in&bpage=<?= $_GET['bpage'] ?>">
					<div style="color: #919191; font-size: 18px; font-weight: 400">Log in with your Sunnies Club account to
						earn points</div>
				</a>
			</div>
		</form>
	</div>
<?php } else { ?>
	<?php if (isset($_SESSION['autologin'])): ?>
		<div class="switch-layout mt-2	">
			<span
				class="switch-animation <?= (isset($_SESSION['customer_id']) || !isset($_SESSION['temp_data'])) ? '' : 'slide' ?>"></span>
			<div class="account-navigation d-flex no-gutters">
				<a href="#use-content"
					class="col-6 text-center account-option <?= (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) ? 'active' : '' ?>">Log
					in</a>
				<a href="#create-content"
					class="col-6 text-center account-option <?= (!isset($_SESSION['customer_id']) && !isset($_SESSION['temp_data'])) ? '' : '' ?>">Sign
					up</a>
			</div>
		</div>
	<?php endif ?>


	<div class="account-content active mt-4" id="use-content">

	<!-- <?php print_r($_SESSION) ?> -->
		<!-- <p class="font-bold text-uppercase text-primary">log in account</p> -->
		<div class="account-login">
			<form method="post" id="use_account" name="use_account" autocomplete="off" class="form-login" >

				<!-- <p class="font-bold text-uppercase text-primary"><?= $arrTranslate['Personal Details'] ?> </p> -->

				<div class="d-flex form-row form-group justify-content-center mt-3">



					<div class="form-group col-md-2 col-sm-2" id="div_area_codes"
						style='padding-left: 0px; padding-right: 0px; margin: 0px; display: none'>
						<select class="text-left select mh-40 form-control" name="country_codes_login"
							id="country_codes_login"
							style="margin: 0px; padding-top:0px; padding-bottom:0px; border-bottom-right-radius: 0px; border-top-right-radius: 0px;">
							<option></option>
						</select>
						<label class="placeholder" for="country_codes_login">Area Code</label>
					</div>


					<div class="form-group col-md-12 col-sm-12" id="div_mobile_number">
						<input type="text" name="username" class="form-control text-lowercase" id="username"
							required="required" autocomplete="nope" />
						<label class="placeholder email" display="none"
							for="username"><?= $arrTranslate['Email or Mobile Number'] ?></label>

						<p class="text-danger" id="msg" style="font-size: 14px;"></p>
						<span class="mobile-format" id="mobile-format_login" style="display: none;"></span>
					</div>

					<div class="form-group col-md-12 col-sm-12 mt-2" id="div_birthdate" data-provide="datepicker">
						<input name="birthdate" class="form-control" id="birthdate" required="required" type="text" />
						<label class="placeholder" for="Birthdate"><?= $arrTranslate['Birthdate'] ?></label>
						<input name="month" id="month" hidden value="">
						<input name="day" id="day" hidden value="">
						<input name="year" id="year" hidden value="">
					</div>
				</div>

				<!-- <p class="font-bold text-uppercase text-primary mt-3"><?= $arrTranslate['Birthdate'] ?></p> -->

				<!-- <div class="d-flex form-row form-group justify-content-center mt-3">
					<div class="col-4 form-group">
						<select name="month" id="birthMonth" class="form-control">
							<option value=""><?= strtoupper($arrTranslate['Month']) ?></option>
							<?php
							$arrMonth = ['Jan' => 'January', 'Feb' => 'February', 'Mar' => 'March', 'Apr' => 'April', 'May' => 'May', 'Jun' => 'June', 'Jul' => 'July', 'Aug' => 'August', 'Sep' => 'September', 'Oct' => 'October', 'Nov' => 'November', 'Dec' => 'December'];

							foreach ($arrMonth as $key => $value) {
								?>
							<option value="<?= $key ?>"><?= $value ?></option>
								<?php } ?>
						</select>
					</div>
					<div class="col-4 form-group">
						<select name="day" id="birthDay" class="form-control ">
							<option value=""><?= strtoupper($arrTranslate['Day']) ?></option>
							<?php
							for ($i = 1; $i <= 31; $i++) {
								$i = ($i < 10) ? '0' . $i : $i;
								?>
								<option value="<?= $i ?>"><?= $i ?></option>
							<?php } ?>
						</select>
					</div>
					<div class="col-4 form-group">
						<select name="year" id="birthYear" class="form-control ">
							<option value=""><?= strtoupper($arrTranslate['Year']) ?></option>
							<?php
							$year = date('Y');
							for ($i = $year; $i >= 1920; $i--) {
								?>
								<option value="<?= $i ?>"><?= $i ?></option>
							<?php } ?>
						</select>
					</div>
				</div> -->
				<div class="text-center mt-2">
					<div class="form-row">
						<div class="col-md-12 form-group">
							<input type="button" name="login-submit" id="btnsubmit-login" value="<?= $arrTranslate['Continue'] ?>"
								class="btn btn-primary" disabled />
						</div>
					</div>
				</div>
				<div class="text-center ">
					<div class="form-row">
						<div class="col-md-12 form-group">
							<a href="/v2.0/sis/face/v1.0/?page=account-form&guest=true&bpage=<?= $_GET['bpage'] ?>">
								<input type="button" class="btn btn-not-cancel" value="Check out as Guest" />
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
							$('#btnsubmit-login').prop('disabled', false);
						} else {
							$('#btnsubmit-login').prop('disabled', true);
						}

					}
				});

				$("#username").on('keydown keypress keyup', function (event) {
					$("#msg").text("");
					$(".placeholder.email").removeClass("text-danger");


					//enable button
					if ($('#username').val() != "" && $("#birthdate").val() != "") {
						$('#btnsubmit-login').prop('disabled', false);
					} else {
						$('#btnsubmit-login').prop('disabled', true);
					}
				});

				$(document).ready(function(){
	
	$(".form-control").on('keyup', function (e) {
    if (e.keyCode == 13) {
      
		
		
		$.post("/v2.0/sis/face/v1.0/modules/store/store-sign-master.php?bpage=<?= $_GET['bpage'] ?>",$("form#use_account").serialize(),function(d){
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	
					window.location="./?page=order-confirmation&bpage=<?= $_GET['bpage'] ?>";
					
				} else {
						
					$("#msg").html(d);
					
				}
		
		});
    }
});

	$("#btnsubmit-login").click(function(){
		
	
		$.post("/v2.0/sis/face/v1.0/modules/store/store-sign-master.php?bpage=<?= $_GET['bpage'] ?>",$("form#use_account").serialize(),function(d){
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	 
					window.location="./?page=order-confirmation&bpage=<?= $_GET['bpage'] ?>";
					
				} else {
						
					$("#msg").html(d);
					
				}
			});
	});
	

});
			</script>

			<!-- END OF LOGIN FORM SCRIPTS -->

		</div>
		<script type="text/javascript">
			$('#username').on('keydown', function () {
				if ($.isNumeric($(this).val())) {
					if ($('#country_codes_login').val() == '') {
						$('#country_codes_login').val('63');
						$('#country_codes_login').select2().trigger('change');
					}
					$('#div_area_codes').css('display', '');
					//$("#div_mobile_number").attr('class', 'form-group col-md-11 col-sm-9');
					$("#username").css({ "border-bottom-left-radius": "0px", "border-top-left-radius": "0px", "border-left-style": "none" });
					paddingUsername($('#country_codes_login'));
				} else {
					$(this).next('label').text('Email Address');
					//$("#div_mobile_number").attr('class', 'form-group col-md-12 col-sm-9');
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
						$('#username').css('padding-left', '50px');
						(code.length == 3) ? $('#username').css('padding-left', '60px') : '';
						(code.length == 4) ? $('#username').css('padding-left', '65px') : '';
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
		<form name="create_account" id="create_account" action="/v2.0/sis/face/func/process/store-register-v2.php?bpage=<?= $_GET['bpage'] ?>"
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

				<p class="font-bold text-uppercase" style="font-size: 18px">
					<?= $arrTranslate['Profile Details'] ?></p>
				<div class="no-gutters mt-4">

					<div class="form-group">
						<input type="text" name="lname" class="form-control" id="lname"
							value="" required>
						<label class="placeholder" for="lname"><?= $arrTranslate['Last Name'] ?></label>
					</div>

					<div class="form-group">
						<input type="text" name="fname" class="form-control" id="fname"
							value="" required>
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
								$selected = ((isset($_SESSION['customer_id']) || isset($_SESSION['temp_data'])) && (strtolower($arrCustomer[0]['gender']) == strtolower($key) || (isset($_SESSION['temp_data']) && strtolower($_SESSION['gender']) == strtolower($key)))) ? '' : '';
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
				(code.length == 3) ? $('.mobile-number').css('padding-left', '60px') : '';
				(code.length == 4) ? $('.mobile-number').css('padding-left', '65px') : '';
				$('#country_codes').change(function () {
					code = $(this).val();
					$('.mobile-number').css('padding-left', '50px');
					(code.length == 3) ? $('.mobile-number').css('padding-left', '60px') : '';
					(code.length == 4) ? $('.mobile-number').css('padding-left', '65px') : '';
					$('.mobile-format').text('+' + code);
				});
			</script>
			<div class="text-center mt-4">

				<?php if (isset($_SESSION['autologin'])): ?>

					<?php if (isset($_SESSION['language_setting']) && $_SESSION['language_setting'] == 'vn') { ?>
						<p class="text-secondary mb-3">
							<?= $arrTranslate['By clicking Submit, you have read and agreed to our Terms and Conditions'] ?>.
						</p>
					<?php } else { ?>
						<div class="d-flex align-items-center justify-content-center mb-3">
							<div>
								<input type="checkbox" id="agree" name="agree" required class="sr-only checkbox">
								<label for="agree" class="custom-checkbox"></label>
							</div>
							<div style="padding:0 15px !important;">
									<label for="agree" class="text-secondary">
											By clicking Register, you have read and agreed to our <br/>
												<span class="font-bold text-primary btn-terms">Terms and Conditions</span> and <span class="font-bold text-primary btn-policy">Privacy Policy</span>.
									</label>
							</div>
					</div>
					<?php } ?>

					<?php include("terms-and-condition.php") ?>
					<?php include("privacy-policy.php") ?>

					<script type="text/javascript">

						$('.btn-policy').click(function () {

							if ($('.policy-well').hasClass('open')) {

								$('.policy-well').removeClass('open').addClass('closed');

							}
							else {

								$('.policy-well').removeClass('closed').addClass('open');

							};

						});

						$('.btn-terms').click(function () {

							if ($('.terms-well').hasClass('open')) {

								$('.terms-well').removeClass('open').addClass('closed');

							}
							else {

								$('.terms-well').removeClass('closed').addClass('open');

							};

						});

					</script>

				<?php endif ?>

				<button type="submit" class="btn btn-primary"
					id="submitCustomer"><?= isset($_SESSION['customer_id']) ? $arrTranslate['Proceed'] : "Sign Up" ?></button>
				<p class="hide text-danger mt-4 required-warning">Please make sure all required fields are filled</p>
<!-- 
				<?php if (isset($_SESSION['email_taken'])): ?>

					<p class="mt-4 text-danger"><?= $_SESSION['email_taken'] ?></p>

				<?php endif ?> -->
				<div id="responseMessage"></div>
			</div>
		</form>
	</div>
	<script>

		$('#s_email').on('change', function() {
			//remove errors
			$("#err_msg").text("");
						$("#s_email").css("border-bottom", " 1px " + "solid" + " #dcdcdc");
						$(".placeholder.s_email").removeClass("text-danger");

		});
		
		$(document).ready(function() {
			<?php if (isset($_SESSION['email_taken'])){
				unset($_SESSION['email_taken']);
			} ?>
        $('#create_account').on('submit', function(e) {
            e.preventDefault(); // Prevent the default form submission
            var formData = $(this).serialize(); // Serialize form data

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'), // Get the form action URL
                data: formData,
                success: function(response) {
                  
                    $('#err_msg').html(response);
					
                },
                error: function() {
                    // Handle error
                    $('#responseMessage').html('<p>There was an error processing your signup.</p>');
                }
            });
        });
    });
	</script>
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
		<?php if (isset($_GET['type']) && $_GET['type'] == 'sixgn-up') { ?>
			$('.account-option').click();
		<?php } ?>
	});
</script>