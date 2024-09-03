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
if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '' ) {
	echo '<script>	window.location.href="/"; </script>';	
}
if(isset($_SESSION['login_set'])){
    echo '<script>window.location="./?page=select-store"</script>';
}
if ( isset($_SESSION['customer_id']) ) {
	include("./modules/includes/grab_customer_hdf.php");
}
/*
 * Set session to detect that you are in the CUSTOMER PAGE
 * this hides all the menu for assistant
 *
 */
if ( !isset($_SESSION['customer_page']) ) {
	$_SESSION['customer_page'] = 'YES';
	echo "<script>window.location.reload(true)</script>";
}

$generate_pass = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$password = "";
$password2 = "";

for ($i=0; $i < 8; $i++) {
	$password .= $generate_pass[rand(0, (strlen($generate_pass)-1))];
	$password2 .=$password;
}

function get_customer_data($data) {
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

function get_customer_hdf($data) {
	global $arrCustomerHdf;
	if ( isset($_SESSION['customer_id']) ) {
		if ( $arrCustomerHdf[0][$data] != 'n' && $arrCustomerHdf[0][$data] != ''  ) {
			$value = 'checked';
		} else {
			$value = '';
		}
	} else {
		$value = '';
	}

	return $value;
}

function get_customer_hdf_N($data) {
	global $arrCustomerHdf;
	if ( isset($_SESSION['customer_id']) ) {
		if ( $arrCustomerHdf[0][$data] == 'n') {
			$value = 'checked';
		} else {
			$value = '';
		}
	} else {
		$value = '';
	}

	return $value;
}

if ( !isset($_SESSION['customer_id']) ) {
	unset($_SESSION['step_3']);
	unset($_SESSION['pickup']);
	unset($_SESSION['prescription']);
	unset($_SESSION['order_no']);
	unset($_SESSION['order_confirmation']);
}


 //showArray($_SESSION);
?>
<?php if(isset($_GET['guest'])){ ?>
	
		<div class="wrapper">
			<form action="/sis/studios/func/process/guest_register.php?path_loc=v1.0" method="post">
		        <div class="d-flex form-group justify-content-center mt-4">
					<input type="text" name="firstname" class="form-control" id="firstname" required="required"  autocomplete="nope" autofocus />
					<label class="placeholder" for="firstname" style="margin-left:15px;"><?= $arrTranslate['First Name'] ?></label>
				</div>
				<div class="d-flex form-group justify-content-center mt-4">
					<input type="text" name="lastname" class="form-control" id="lastname" required="required"  autocomplete="nope" />
					<label class="placeholder" for="lastname" style="margin-left:15px;"><?= $arrTranslate['Last Name'] ?></label>
				</div>
		        <div class="text-center mt-4">
		            <input type="submit" class="btn btn-primary" value="Proceed">
		        </div>
		    </form>
	    </div>
<?php }else{ ?>
	<?php if ( !isset($_SESSION['customer_id']) ) : ?>
		<div class="switch-layout">
			<span class="switch-animation <?= ( isset($_SESSION['customer_id']) || isset($_SESSION['temp_data']) ) ? 'slide' : '' ?>"></span>
			<div class="account-navigation d-flex no-gutters">
					<a href="#create-content" class="col-6 text-center account-option <?= ( !isset($_SESSION['customer_id']) && !isset($_SESSION['temp_data']) ) ? 'active' : '' ?>"><?= $arrTranslate['Register'] ?></a>
					<a href="#use-content" class="col-6 text-center account-option <?= ( isset($_SESSION['customer_id']) || isset($_SESSION['temp_data']) ) ? 'active' : '' ?>"><?= $arrTranslate['Log In'] ?></a>
			</div>
		</div>
	<?php endif ?>

	<div class="account-content mt-4" id="use-content">
		<!-- <p class="font-bold text-uppercase text-primary">log in account</p> -->
		<form method="post" id="use_account" name="use_account" autocomplete="off" class="mt-0">
			<div class="d-flex form-group justify-content-center">
				<div class="form-group col-md-1 col-sm-1" id="div_area_codes" style='padding-left: 0px; padding-right: 0px; margin: 0px; display: none'>
					<select class="text-left select mh-40 form-control" name="country_codes_login" id="country_codes_login" style="margin: 0px; padding-top:0px; padding-bottom:0px; border-bottom-right-radius: 0px; border-top-right-radius: 0px;">
						<option></option>
					</select>
					<label class="placeholder" for="country_codes_login">Area Code</label>
				</div>
				<div class="form-group col-md-6 col-sm-5" id="div_mobile_number" >
					<input type="text" name="username" class="form-control text-lowercase" id="username" required="required"  autocomplete="nope" />
					<label class="placeholder" for="username" style="margin-left:15px;"><?= $arrTranslate['Email or Mobile Number'] ?></label>
					<span class="mobile-format" id="mobile-format_login" style="display: none;"></span>
				</div>
			</div>
			<div class="d-flex justify-content-center">
				<div class="col-lg-2 col-md-12 col-xs-12 form-group">
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
				<div class="col-lg-2 col-md-12 col-xs-12 form-group">
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
				<div class="col-lg-2 col-md-12 col-xs-12 form-group">
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
				<input type="button" name="btnsubmit" id="btnsubmit" value="Continue" class="btn btn-primary" />
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

	<div class="account-content active mt-4 mb-4" id="create-content">
		<form name="create_account" id="create_account" action="/sis/studios/func/process/store-register.php" method="POST" autocomplete="off">
			<input type="hidden" value="v1.0" name="path_loc">
			<input type="hidden" value="<?php echo $password; ?>" name="password2">
			<input type="hidden" value="<?php echo $password; ?>" name="confirmPassword2">
			<input type="hidden" name="specs_branch" value="<?= 'This Store Name' ?>">
			<input type="hidden" name="joining_date" value="<?= date('Y/m/d'); ?>">

			<p class="font-bold text-uppercase text-primary"><?= $arrTranslate['Personal Details'] ?></p>
			<div class="form-row no-gutters mt-3">
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="lname" class="form-control" id="lname" value="<?= get_customer_data('last_name') ?>" required>
						<label class="placeholder" for="lname"><?= $arrTranslate['Last Name'] ?></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="fname" class="form-control" id="fname" value="<?= get_customer_data('first_name') ?>" required>
						<label class="placeholder" for="fname"><?= $arrTranslate['First Name'] ?></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="mname" class="form-control" id="mname" value="<?= get_customer_data('middle_name') ?>" required>
						<label class="placeholder" for="mname"><?= $arrTranslate['Middle Name'] ?></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
					<input type="text" name="bdate" class="form-control" id="bdate" value="<?= get_customer_data('birthday') ?>" required>
						<input type="date" name="bdate2" class="form-control sr-only" id="bdate2" value="<?= get_customer_data('birthday') ?>" required>
						<label class="placeholder" for="bdate2"><?= $arrTranslate['Birthdate'] ?></label>
						<label class="placeholder-overlay" for="bdate2"></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<input type="text" name="age" class="form-control" id="age" value="<?= get_customer_data('age') ?>" required>
						<label class="placeholder" for="age"><?= $arrTranslate['Age'] ?></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group gender">
						<div class="d-flex no-gutters">
							<div class="col">
								<input class="sr-only" type="radio" id="gender-male" name="gender" value="Male" <?php if( (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data']) ) && ($arrCustomer[0]['gender']=='male' || ( isset($_SESSION['temp_data']) && $_SESSION['gender']=='Male') ) ){ ?> checked='checked' <?php  } ?>>
								<label class="form-control col" for="gender-male"><?= $arrTranslate['Male'] ?></label>
							</div>
							<div class="col">
								<input class="sr-only" type="radio" id="gender-female" name="gender" value="Female" <?php if( (isset($_SESSION['customer_id']) || isset($_SESSION['temp_data']) ) && ($arrCustomer[0]['gender']=='female' || ( isset($_SESSION['temp_data']) && $_SESSION['gender']=='Female') ) ){ ?> checked='checked' <?php  } ?>>
								<label class="form-control col" for="gender-female"><?= $arrTranslate['Female'] ?></label>
							</div>
						</div>
					</div>
				</div>
			</div>

			<p class="font-bold text-uppercase text-primary mt-2"><?= $arrTranslate['Present Address'] ?></p>
			<div class="form-row no-gutters mt-3">
				<div class="col-12">
					<div class="form-group">
						<input type="text" name="home_address" class="form-control" id="home_address" value="<?= get_customer_data('address') ?>" required>
						<label class="placeholder" for="home_address"><?= $arrTranslate['Home Address'] ?></label>
					</div>
				</div>
				<div class="col-6">
					<div class="form-group">
						<select class="text-left select mh-40 form-control" name="country" id="country" required>

							<?php

								sort($arrAllCountries);

								$country_cov = "";
								for ($i=0; $i < sizeOf($arrAllCountries); $i++) {
									// guest = 1, normal =2, international = 3
									if($_SESSION['store_code'] == 142 || $_SESSION['store_code'] == 150){
										echo '<option value="'.$arrAllCountries[$i].'" '.(($arrAllCountries[$i] == "viet-nam") ? "selected" : "").'>'.ucwords(str_replace("-", " ", $arrAllCountries[$i])).'</option>';
										$country_cov = 'Vietnam';
									}else{
										echo '<option value="'.$arrAllCountries[$i].'" '.(($arrAllCountries[$i] == "philippines") ? "selected" : "").'>'.ucwords(str_replace("-", " ", $arrAllCountries[$i])).'</option>';
										$country_cov = 'Philippines';
									}
								};

							?>

						</select>
						<label class="placeholder" for="country"><?= $arrTranslate['Country'] ?></label>
					</div>
				</div>
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
				<div class="col-6" <?= ($_SESSION['store_code'] == 142 || $_SESSION['store_code'] == 150) ? 'style="display:none;"' : '' ?> >
					<div class="form-group">
						<div class="c-b-sect">
							<select class="text-left mh-40 select form-control" name="barangay" id="barangay" disabled required>
								<option value="n" selected></option>
							</select>
							<label class="placeholder" for="barangay">Barangay</label>
						</div>
					</div>
				</div>
			</div>
			<p class="font-bold text-uppercase text-primary mt-2"><?= $arrTranslate['Contact Details'] ?></p>
			<div class="form-row no-gutters mt-3">
				<div class="col-12">
					<div class="form-group">
						<?php if ( isset($_SESSION['customer_id']) ) : ?>
							<input type="hidden" name="email_confirmation" value="<?= ( $arrCustomer[0]['email_address'] != '' ) ? $arrCustomer[0]['email_address'] : '' ?>">
						<?php endif ?>
						<input type="email" name="email" class="form-control" id="s_email" value="<?= get_customer_data('email_address') ?>" required>
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
								$get_num = get_customer_data('phone_number');
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
						<input type="text" style="border-bottom-left-radius: 0px; border-top-left-radius: 0px; border-left-style: none;" name="mnum" class="form-control mobile-number <?= (get_customer_data('phone_number')!='') ? 'active' : '' ?>" id="mnum" class="mnum" value="<?= $phone_number ?>" required>
						<label class="placeholder" for="mnum"><?= $arrTranslate['Mobile Number'] ?></label>
						<span class="mobile-format <?= (get_customer_data('phone_number') == '') ? 'hide' : '' ?>"></span>
					</div>
				</div>
			</div>
			<br>
			<style>
				input[type='radio']{
					transform: scale(1.5);
				}
				input[type='radio']:after {
					width: 15px;
					height: 15px;
					border-radius: 15px;
					top: -2px;
					left: -1px;
					position: relative;
					background-color: #F9F3EC;
					content: '';
					display: inline-block;
					visibility: visible;
					border: 1px solid white;
				}
				
				input[type='radio']:checked:after {
					width: 15px;
					height: 15px;
					border-radius: 15px;
					top: -2px;
					left: -1px;
					position: relative;
					background-color: #DB9075;
					content: '';
					display: inline-block;
					visibility: visible;
					border: 1px solid white;
				}

				.d-none{
					display: none;
				}
				.hdf label{
					margin-left:12px;
					margin-right:12px;
				}
				.hdf > div {
					margin-top: 20px !important;
				}
			</style>
			<div class="row hdf">
				
				<div class="col-md-12">
					<div class="row">
						<div style="margin-top:5px;" class="col-lg-10 col-md-10 col-xs-12">
							<label class="font-weight-bold">Foreign countries you have worked, visited, transited in the past 14 days </label>
						</div>					
						<div  class="col-lg-2 col-md-2 col-xs-12 " style="text-align:right;">
							<div class="d-flex justify-content-center mt-2">
								<div class="radio">
									<input type="radio" name="question_1" value = "no" <?= get_customer_hdf_N('travel_countries') ?> required>
								</div><label><?= $arrTranslate['No'] ?></label>
								<div class="radio">
									<input type="radio" name="question_1" value = "yes" <?= get_customer_hdf('travel_countries') ?>>
								</div><label><?= $arrTranslate['Yes'] ?></label>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-xs-12 <?= (get_customer_hdf('travel_countries')  =='') ? 'd-none' : '' ?>">
							<input type="text" name="travel_countries" class="form-control" id="travel_countries" value="<?= (get_customer_hdf('travel_countries')  !='') ?  $arrCustomerHdf[0]['travel_countries'] : '' ?>" <?= (get_customer_hdf('travel_countries')!= '') ? 'required' : '' ?>>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="row">
						<div style="margin-top:5px;" class="col-lg-10 col-md-10 col-xs-12">
							<label class="font-weight-bold">Cities in the <?= $country_cov ?> you have worked, lived or transited in the past 14 days </label>
						</div>					
						<div  class="col-lg-2 col-md-2 col-xs-12" style="text-align:right;">
							<div class="d-flex justify-content-center mt-2">
								<div class="radio">
									<input type="radio" name="question_2" value = "no" <?= get_customer_hdf_N('travel_ph') ?> required>
								</div><label><?= $arrTranslate['No'] ?></label>
								<div class="radio">
									<input type="radio" name="question_2" value = "yes"  <?= get_customer_hdf('travel_ph') ?>>
								</div><label><?= $arrTranslate['Yes'] ?></label>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-xs-12 <?= (get_customer_hdf('travel_ph')  =='') ? 'd-none' : '' ?>">
							<input type="text" name="travel_ph" class="form-control" id="travel_ph" value="<?= (get_customer_hdf('travel_ph')  !='') ?  $arrCustomerHdf[0]['travel_ph'] : '' ?>" <?= (get_customer_hdf('travel_ph')!= '') ? 'required' : '' ?>>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="row">
						<div style="margin-top:5px;" class="col-lg-10 col-md-10 col-xs-12">
							<label class="font-weight-bold">Have you been sick in the past 30 days? Hospital visited if any?</label>
						</div>					
						<div  class="col-lg-2 col-md-2 col-xs-12" style="text-align:right;">
							<div class="d-flex justify-content-center mt-2">
								<div class="radio">
									<input type="radio" name="question_3" value = "no" <?= get_customer_hdf_N('sick') ?> required>
								</div><label><?= $arrTranslate['No'] ?></label>
								<div class="radio">
									<input type="radio" name="question_3" value = "yes" <?= get_customer_hdf('sick') ?>>
								</div><label><?= $arrTranslate['Yes'] ?></label>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-xs-12 <?= (get_customer_hdf('sick')  =='') ? 'd-none' : '' ?>">
							<input type="text" name="sick" class="form-control" id="sick" value="<?= (get_customer_hdf('sick')  !='') ?  $arrCustomerHdf[0]['sick'] : '' ?>" <?= (get_customer_hdf('sick')!= '') ? 'required' : '' ?>>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="row">
						<div style="margin-top:5px;" class="col-lg-10 col-md-10 col-xs-12">
							<label class="font-weight-bold">In the last 14 days, did you have any of the following: fever, colds, cough, sore throat, loss of smell and taste, muscle pain, headache or difficulty in breathing?</label>
						</div>					
						<div  class="col-lg-2 col-md-2 col-xs-12" style="text-align:right;">
							<div class="d-flex justify-content-center mt-2">
								<div class="radio">
									<input type="radio" name="question_4" value = "no" <?= get_customer_hdf_N('symptoms') ?> required>
								</div><label><?= $arrTranslate['No'] ?></label>
								<div class="radio">
									<input type="radio" name="question_4" value = "yes" <?= get_customer_hdf('symptoms') ?>>
								</div><label><?= $arrTranslate['Yes'] ?></label>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-xs-12 <?= (get_customer_hdf('symptoms') =='') ? 'd-none' : '' ?>">
							<input type="text" name="symptoms" class="form-control" id="symptoms" value="<?= (get_customer_hdf('symptoms')  !='') ?  $arrCustomerHdf[0]['symptoms'] : '' ?>" <?= (get_customer_hdf('symptoms')!= '') ? 'required' : '' ?>>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="row">
						<div style="margin-top:5px;" class="col-lg-10 col-md-10 col-xs-12">
							<label class="font-weight-bold">In the last 14 days, have you been in close contact or exposed to any person suspected of or confirmed with COVID-19?</label>
						</div>					
						<div  class="col-lg-2 col-md-2 col-xs-12" style="text-align:right;">
							<div class="d-flex justify-content-center mt-2">
								<div class="radio">
									<input type="radio" name="question_5" value = "no" <?= get_customer_hdf_N('close_contact') ?> required>
								</div><label><?= $arrTranslate['No'] ?></label>
								<div class="radio">
									<input type="radio" name="question_5" value = "yes" <?= get_customer_hdf('close_contact') ?>>
								</div><label><?= $arrTranslate['Yes'] ?></label>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-xs-12 <?= (get_customer_hdf('close_contact') =='') ? 'd-none' : '' ?>">
							<input type="text" name="close_contact" class="form-control" id="close_contact" value="<?= (get_customer_hdf('close_contact')  !='') ?  $arrCustomerHdf[0]['close_contact'] : '' ?>" <?= (get_customer_hdf('close_contact')!= '') ? 'required' : '' ?>>
						</div>
					</div>
				</div>

				<div class="col-md-12">
					<div class="row">
						<div style="margin-top:5px;" class="col-lg-10 col-md-10 col-xs-12">
							<label class="font-weight-bold">In the last 14 days, have you been in close contact with farm animals or exposed to wild animals?</label>
						</div>					
						<div  class="col-lg-2 col-md-2 col-xs-12" style="text-align:right;">
							<div class="d-flex justify-content-center mt-2">
								<div class="radio">
									<input type="radio" name="question_6" value = "no" <?= get_customer_hdf_N('close_contact_animals') ?> required>
								</div><label><?= $arrTranslate['No'] ?></label>
								<div class="radio">
									<input type="radio" name="question_6" value = "yes" <?= get_customer_hdf('close_contact_animals') ?>>
								</div><label><?= $arrTranslate['Yes'] ?></label>
							</div>
						</div>
						<div class="col-lg-12 col-md-12 col-xs-12 <?= (get_customer_hdf('close_contact_animals') =='') ? 'd-none' : '' ?>">
							<input type="text" name="close_contact_animals" class="form-control" id="close_contact_animals" value="<?= (get_customer_hdf('close_contact_animals')  !='') ?  $arrCustomerHdf[0]['close_contact_animals'] : '' ?>" <?= (get_customer_hdf('close_contact_animals')!= '') ? 'required' : '' ?>>
						</div>
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
				
				<button type="submit" class="btn btn-primary" id="submitCustomer"><?= isset($_SESSION['customer_id']) ? 'Proceed' : 'Submit' ?></button>
				<p class="hide text-danger mt-4 required-warning">Please make sure all required fields are filled</p>

				<?php if ( isset($_SESSION['email_taken']) ) : ?>

					<p class="mt-4 text-danger"><?= $_SESSION['email_taken'] ?></p>

				<?php endif ?>

			</div>
		</form>
	</div>
<?php } ?>
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
