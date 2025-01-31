<?php
include("./modules/includes/grab_order_details.php");
include("./modules/includes/grab_dispatch_details.php");
function cvdate($d){
	$returner = '';
	$datae=date_parse($d); 
	$returner .= getMonth($datae['month'])." ".$datae['day'].", ".$datae['year'];
	$suffix = "AM";
	$hour = $datae['hour'];
	if ($datae['hour']>'12') {
		$hour = $datae['hour']-12;
	}
	if ($datae['hour']>'11' && $datae['hour']<'24') {
		$suffix = "PM";
	}
	$returner .= " ";	
	return $returner;
}

function getMonth($mid){
	switch($mid){
		case '1': return "Jan"; break;
		case '2': return "Feb"; break;
		case '3': return "Mar"; break;
		case '4': return "Apr"; break;
		case '5': return "May"; break;
		case '6': return "Jun"; break;
		case '7': return "Jul"; break;
		case '8': return "Aug"; break;
		case '9': return "Sept"; break;
		case '10': return "Oct"; break;
		case '11': return "Nov"; break;
		case '12': return "Dec"; break;
		
	}
}

function AddZero($num){
	if (strlen($num)=='1') {
		return "0".$num;
	} else {
		return $num;
	}
}
?><style>

	.customer-info {
		margin-top: 105px;
	}

	.customer-info .customer-info-details {
		background: #fff;
		padding-bottom: 10px;
		border-bottom: 1px solid #e6e6e6;
		margin-bottom: 20px;
	}

	.customer-info .customer-info-details h5,
	.customer-info .customer-signature h5,
	.customer-info .prescription-content h5 {
		border-bottom: 1px solid #e6e6e6;
		padding-bottom: 15px;
		font-family: 'Poppins-Medium';
		font-size: 15px;
		text-transform: uppercase;
	}

	.customer-info .prescription-table-form-wrapper {
		position: relative;
		border: 1px solid #e6e6e6;
	}

	.customer-info .prescription-table-form-wrapper > div {
		width: 100%;
	}

	.customer-info .prescription-table-form-wrapper > div:nth-of-type(2) {
		border-left: 1px solid #e6e6e6;
		border-right: 1px solid #e6e6e6;
	}

	.customer-info select.table-row {
		padding: 18px 0;
		border: 0;
		background: #f7f7f7;
		-webkit-appearance: none;
		-moz-appearance: none;
		appearance: none;
		color: #000;
		cursor: pointer;
	}

	.customer-order-details > div {
		padding: 5px 0;
	}

	.prescription-content {
		margin-top: 20px;
	}

	.customer-signature {
		margin: 20px 0 60px;
	}

	.customer-signature .signature-img-wrapper {
		padding: 60px 30px;
	}

	.all-prescription-content > .prescription-list:not(:last-of-type) {
		display: none;
	}

	.customer-info .prescription-table-form .select2-container--default .select2-selection--single {
		padding: 10px 0;
		height: 50px;
		max-height: 50px;
		border: 0;
		-webkit-border-radius: 0;
		-moz-border-radius: 0;
		border-radius: 0;
		background: #f7f7f7;
	}

	.customer-info .prescription-table-form .select2-container--default:not(:last-of-type) .select2-selection--single {
		border-right: 1px solid #e6e6e6;
	}

	.customer-info .prescription-table-form .select2-container--default .select2-selection--single .select2-selection__arrow {
		top: 12px;
		right: 10px;
	}

	.customer-info [class*="table-profile-final-rx-od-"] .select2-container--default .select2-selection--single .select2-selection__arrow,
	.customer-info [class*="table-profile-final-rx-os-"] .select2-container--default .select2-selection--single .select2-selection__arrow,
	.customer-info [class*="table-profile-final-rx-"] .select2-container--default .select2-selection--single .select2-selection__arrow {
		display: none;
	}

	.customer-info .prescription-table-form .select2-container--default .select2-selection--single .select2-selection__rendered {
		padding-left: 10px;
	}

	.customer-info [class*="table-profile-final-rx-od-"] .select2-container--default:first-of-type .select2-selection--single .select2-selection__rendered,
	.customer-info [class*="table-profile-final-rx-os-"] .select2-container--default:first-of-type .select2-selection--single .select2-selection__rendered,
	.customer-info [class*="table-profile-final-rx-"] .select2-container--default .select2-selection--single .select2-selection__rendered {
		padding: 0;
		height: 50px;
		text-align: center;
		font-weight: bold;
	}

	.customer-info .prescription-table-form-wrapper > div:first-of-type .select2-container--default .select2-selection--single .select2-selection__rendered {
		font-size: 12px;
	}

	.customer-info [class*="table-profile-final-rx-od-"] .select2-container--default .select2-selection--single .select2-selection__rendered,
	.customer-info [class*="table-profile-final-rx-os-"] .select2-container--default .select2-selection--single .select2-selection__rendered {
		text-align: center;
		padding: 0;
		height: 50px;
		font-weight: normal;
	}

	.customer-info .prescription-table-form .select2-container--default {
		display: -webkit-box;
		display: -webkit-flex;
		display: -ms-flexbox;
		display: flex;
		-webkit-flex-wrap: wrap;
		    -ms-flex-wrap: wrap;
		        flex-wrap: wrap;
		-webkit-flex-basis: 0;
		    -ms-flex-preferred-size: 0;
		        flex-basis: 0;
		-webkit-box-flex: 1;
		-webkit-flex-grow: 1;
		    -ms-flex-positive: 1;
		        flex-grow: 1;
		max-width: 100%;
		width: 100% !important;
	}

	.customer-info .prescription-table-form .select2-container--default .selection {
		width: 100%;
	}

	.customer-info .agree-to-prescription input {
		margin-top: -3px;
		margin-right: 5px;
		display: inline-block;
		vertical-align: middle;
		cursor: pointer;
	}

	.customer-info .agree-to-prescription label {
		cursor: pointer;
	}

	.customer-info .upgrades-content .upg-opt-list {
		position: relative;
		padding: 10px;
	}

	.customer-info .upgrades-content .upg-opt-list label {
		padding: 20px;
		border: 1px solid #e6e6e6;
		cursor: pointer;
		width: 100%;
		margin: 0;
		height: 150px;
		-webkit-transition: .3s all ease;
		-moz-transition: .3s all ease;
		transition: .3s all ease;
	}

	.customer-info .upgrades-content .upg-opt-list label span {
		display: block;
		font-weight: bold;
		-webkit-transition: .3s all ease;
		-moz-transition: .3s all ease;
		transition: .3s all ease;
	}

	.customer-info .upgrades-content .upg-opt-list input:checked ~ label {
		background: #449d44;
		border: 1px solid #449d44;
	}

	.customer-info .upgrades-content .upg-opt-list input:checked ~ label span {
		color: #fff !important;
	}

	@media screen and (min-width: 768px) {
		.customer-info .table-row:not(:last-of-type) {
			border-right: 1px solid #e6e6e6;
			border-bottom: 0;
		}
		.customer-info .prescription-table-form-wrapper > div:nth-of-type(2) {
			border: 0;
			border-top: 1px solid #e6e6e6;
			border-bottom: 1px solid #e6e6e6;
		}
	}

</style>

<div class="row align-items-center justify-content-between page-header">
	<h5 style="margin: 0;">Customer: <?= ucwords( $arrCustomerP[0]["first_name"] ).' '.ucwords( $arrCustomerP[0]["last_name"] ) ?></h5>
	<button class="btn ssis-btn-primary btn-back" onclick="window.history.go(-1);">Back</button>
</div>

<div class="customer-info">

	<div class="customer-info-details">
		<div class="form-group">
			<h5>Personal Details</h5>
		</div>
		<div class="row">
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_lname">Last Name</label>
					<input type="text" name="p_lname" class="form-control" id="p_lname" value="<?php echo $arrCustomerP[0]["last_name"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_fname">First Name</label>
					<input type="text" name="p_fname" class="form-control" id="p_fname" value="<?php echo $arrCustomerP[0]["first_name"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_mname">Middle Name</label>
					<input type="text" name="p_mname" class="form-control" id="p_mname" value="<?php echo $arrCustomerP[0]["middle_name"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="p_province">Province</label>
					<input type="text" name="p_province" class="form-control" id="p_province" value="<?php echo $arrCustomerP[0]["province"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="p_city">City</label>
					<input type="text" name="p_city" class="form-control" id="p_city" value="<?php echo $arrCustomerP[0]["city"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="p_barangay">Barangay</label>
					<input type="text" name="p_barangay" class="form-control" id="p_barangay" value="<?php echo $arrCustomerP[0]["barangay"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="P_home_address">Home Address</label>
					<input type="text" name="P_home_address" class="form-control" id="P_home_address" value="<?php echo $arrCustomerP[0]["address"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_bdate">Birthdate</label>
					<input type="text" name="p_bdate" class="form-control" id="p_bdate" value="<?php echo cvdate($arrCustomerP[0]["birthday"]); ?>" readonly>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_age">Age</label>
					<input type="text" name="p_age" class="form-control" id="p_age" value="<?php echo $arrCustomerP[0]["age"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_gender">Gender</label>
					<input type="text" name="p_gender" class="form-control" id="p_gender" value="<?php echo $arrCustomerP[0]["gender"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="p_email">Email Address</label>
					<input type="text" name="p_email" class="form-control" id="p_email" value="<?php echo $arrCustomerP[0]["email_address"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="mnum">Mobile Number</label>
					<input type="text" name="mnum" class="form-control" id="mnum" value="<?php echo $arrCustomerP[0]["phone_number"] ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="p_joining_date">Joining Date</label>
					<input type="text" name="p_joining_date" class="form-control" id="p_joining_date" value="<?php echo cvdate($arrCustomerP[0]["date_created"]); ?>" readonly>
				</div>
			</div>
			<div class="col-sm-6 col-md-6">
				<div class="form-group">
					<label class="font-weight-bold" for="p_specs_branch">Branch Applied</label>
					<input type="text" name="p_specs_branch" class="form-control" id="p_specs_branch" value="<?php echo $arrCustomerP[0]["branch"] ?>" readonly>
				</div>
			</div>
		</div>
	</div>

	<div class="customer-info-prescription">

	<?php if ( empty($arrCustomerP) ) : ?>

		<div class="empty-prescription-content text-center" >
			<h3 class="font-weight-bold text-danger text-center">No Prescription Found</h3>
		</div>
	
	<?php else : ?>

		<div class="all-prescription-content">

			<?php

				// Cycle through Prescriptions
				for ($i=0; $i < sizeOf($arrCustomerPrescription); $i++) { 

					// Set current attributes
					if($arrCustomerP[$i]['prescription_name'] == '') {

						if($i == sizeOf($arrCustomerPrescription) - 1) {

							$pTitle = 'My Prescription';

						}
						else {

							$pTitle = 'Prescription #'.($i + 2);

						};

					}
					else {

						$pTitle = $arrCustomerPrescription[$i]['prescription_name'];

					};

					// Current date
					$curDate = date("m/d/Y");

					if($curDate - $arrCustomerPrescription[$i]['prescription_date'] < 7) {

						$disabled = '';	
						$expiration = 'GOOD';						
						$expirationClass = 'text-success';

					}
					else {
						
						$disabled = 'disabled="disabled"';
						$expiration = 'EXPIRED';
						$expirationClass = 'text-danger';

					};

					// Set months offset from today for expiration
					$numM = $curDate - $arrCustomerPrescription[$i]['prescription_date'];

					if($numM == 1) {

						$s = '';

					}
					else {

						$s = 's';

					};

					// Set visions available
					if($arrCustomerPrescription[$i]['add_od'] >= 1 || $arrCustomerPrescription[$i]['add_os'] >= 1) {

						$vTest = 'multi';

					}
					else {

						$vTest = 'single';

					};
					
				
					echo 	'<!-- prescription '.($i + 1).' -->';
					echo 	'<div class="prescription-list" id="prescription-list-'.($i + 1).'">';
					echo 		'<div class="row no-gutters align-items-center justify-content-between form-group">';
					echo 			'<h5 style="margin-bottom: 0;">'.$pTitle.' ';
					echo 				'<span class="prescription-created">('.$arrCustomerPrescription[$i]['prescription_date'].')</span> ';
					echo 				'<span class="small">';
					echo 					'<span class="small '.$expirationClass.' prescription-date">'.$numM.' month'.$s.' ago</span> - ';
					echo 					'<span class="prescription-status '.$expirationClass.' small">'.$expiration.'</span>';
					echo 				'</span>';
					echo 			'</h5>';
//					echo 			'<button type="button" '.$disabled.' class="btn ssis-btn-primary btn-use-prescription" data-prescription-id="'.$i.'" data-profile-id="'.$_GET['profile_id'].'" data-vision="'.$vTest.'">Use Prescription</button>';
					echo 		'</div>';

					echo 		'<div class="prescription-table-form form-group">';
					echo 			'<div class="row no-gutters flex-sm-column prescription-table-form-wrapper">';
					echo 				'<div class="row no-gutters flex-sm-row text-center table-profile-final-rx-'.($i + 1).'">';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">FINAL RX</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">SPH</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">CYL</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">AXIS</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">ADD</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">IPD</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">PH</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row">';
					echo 						'<option value="">VA WITH RX</option>';
					echo 					'</select>';
					echo 				'</div>';
					echo 				'<div class="row no-gutters flex-sm-row table-profile-final-rx-od-'.($i + 1).'">';
					echo 					'<select disabled="disabled" class="row align-items-center justify-content-center col no-gutters table-row text-center">';
					echo 						'<option value="OD">OD</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_sph_od_'.($i + 1).'" name="profile_final_rx_sph_od_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['sph_od'].'">'.$arrCustomerPrescription[$i]['sph_od'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_cyl_od_'.($i + 1).'" name="profile_final_rx_cyl_od_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['cyl_od'].'">'.$arrCustomerPrescription[$i]['cyl_od'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_axis_od_'.($i + 1).'" name="profile_final_rx_axis_od_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['axis_od'].'">'.$arrCustomerPrescription[$i]['axis_od'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_add_od_'.($i + 1).'" name="profile_final_rx_add_od_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['add_od'].'">'.$arrCustomerPrescription[$i]['add_od'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_ipd_od_'.($i + 1).'" name="profile_final_rx_ipd_od_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['ipd_od'].'">'.$arrCustomerPrescription[$i]['ipd_od'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_ph_od_'.($i + 1).'" name="profile_final_rx_ph_od_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['ph_od'].'">'.$arrCustomerPrescription[$i]['ph_od'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_va_od_'.($i + 1).'" name="profile_final_rx_va_od_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['va_od'].'">'.$arrCustomerPrescription[$i]['va_od'].'</option>';
					echo 					'</select>';
					echo 				'</div>';
					echo 				'<div class="row no-gutters flex-sm-row table-profile-final-rx-os-'.($i + 1).'">';
					echo 					'<select disabled="disabled" class="row align-items-center justify-content-center col no-gutters table-row text-center">';
					echo 						'<option value="OS">OS</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_sph_os_'.($i + 1).'" name="profile_final_rx_sph_os_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['sph_os'].'">'.$arrCustomerPrescription[$i]['sph_os'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_cyl_os_'.($i + 1).'" name="profile_final_rx_cyl_os_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['cyl_os'].'">'.$arrCustomerPrescription[$i]['cyl_os'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_axis_os_'.($i + 1).'" name="profile_final_rx_axis_os_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['axis_os'].'">'.$arrCustomerPrescription[$i]['axis_os'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_add_os_'.($i + 1).'" name="profile_final_rx_add_os_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['add_os'].'">'.$arrCustomerPrescription[$i]['add_os'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_ipd_os_'.($i + 1).'" name="profile_final_rx_ipd_os_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['ipd_os'].'">'.$arrCustomerPrescription[$i]['ipd_os'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_ph_os_'.($i + 1).'" name="profile_final_rx_ph_os_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['ph_os'].'">'.$arrCustomerPrescription[$i]['ph_os'].'</option>';
					echo 					'</select>';
					echo 					'<select disabled="disabled" class="row col no-gutters align-items-center justify-content-center table-row text-center" id="profile_final_rx_va_os_'.($i + 1).'" name="profile_final_rx_va_os_'.($i + 1).'">';
					echo 						'<option value="'.$arrCustomerPrescription[$i]['va_os'].'">'.$arrCustomerPrescription[$i]['va_os'].'</option>';
					echo 					'</select>';
					echo 				'</div>';
					echo 			'</div>';
					echo 		'</div>';

					echo 	'</div>';
					echo 	'<!-- /prescription '.($i + 1).' -->';?>
	
	
					
<?php
				};

				?>

			</div>

		<?php endif; ?>

	</div>

	<div class="prescription-content">
		<div class="form-group">
			<h5>Order Details</h5>
		</div>
		<div class="ssis-bg-gray customer-order-details" style="padding: 30px;">
			<?php for($p=0;$p<sizeof($arrCustomerPrescription);$p++) : ?>
				<div class="row no-gutters align-items-center">
					<p class="col text-left">LENS:</p>
					<p class="col text-right"><?= ucwords( $arrCustomerPrescription[$p]["lens_option"] ); ?> - <?= ucwords( str_replace( '_', ' ', $arrCustomerPrescription[$p]['product_upgrade'] ) ); ?></p>
				</div>
				<div class="row no-gutters align-items-center">
					<p class="col text-left">VISION:</p>
					<p class="col text-right"><?= ucwords( $arrCustomerPrescription[$p]["lens_option"] ); ?></p>
				</div>
				<div class="row no-gutters align-items-center">
					<p class="col text-left">PRICE:</p>
					<p class="col text-right" id="format-price"><?= $arrCustomerPrescription[$p]['price'] ?></p>
				</div>
			<?php endfor; ?>
			<div class="row no-gutters align-items-center laboratory-location">
				<p class="col text-left">LABORATORY:</p>
				<p class="col text-right"><?php  echo ucwords($arrCustomerDetail[0]["lab_name"]); ?></p>
			</div>
		</div>
	</div>

	<div class="customer-signature">
		<div class="form-group">
			<h5>Signature</h5>
		</div>
		<div class="ssis-bg-gray signature-img-wrapper">
			<img class="img-fluid center-block" src="<?php echo $arrCustomerDetail[0]["signature"];?>">
		</div>
	</div>

</div>

<script>
	
	function commaSeparateNumber(val){
	   while (/(\d+)(\d{3})/.test(val.toString())){
	     val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
	   }
	   return val;
	}

	var format = $('#format-price'),
		newF = format.text();

	format.text( commaSeparateNumber( '₱' + newF ) );

</script>