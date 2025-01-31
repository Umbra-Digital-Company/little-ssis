<?php

include("./modules/includes/grab_customers_list.php");
$arrCustomerP = array();

$grabParams = array("prescription_id", "prescription_date", "prescription_name", "sph_od","cyl_od","axis_od","add_od","ipd_od","ph_od","va_od","sph_os","cyl_os","axis_os","add_os","ipd_os","ph_os","va_os","last_name","first_name","middle_name","email_address","province","city","barangay","birthday","age","gender","phone_number","date_created","branch","profile_id","address" );

  $query = 	"SELECT
				pp.id,
				DATE_FORMAT(pp.date_created, '%m/%d/%Y'),
				pp.prescription_name,
				pp.sph_od,
				pp.cyl_od,
				pp.axis_od,
				pp.add_od,
				pp.ipd_od,
				pp.ph_od,
				pp.va_od,
				pp.sph_os,
				pp.cyl_os,
				pp.axis_os,
				pp.add_os,
				pp.ipd_os,
				pp.ph_os,
				pp.va_os,
				pi.last_name,
				pi.first_name,
				pi.middle_name,
				pi.email_address,
				pi.province,
				pi.city,
				pi.barangay,
				pi.birthday,
				pi.age,
				pi.gender,		
				pi.phone_number,
				pi.date_created,
				sc.branch,			
				pi.profile_id,
				pi.address
			FROM 
				profiles_prescription pp
					LEFT JOIN profiles_info pi 
						ON pi.profile_id = pp.profile_id
					LEFT JOIN  store_codes sc on sc.location_code=pi.branch_applied
					
			WHERE 
				pi.profile_id = '".$_GET['profile_id']."'
				
			ORDER BY
				pp.date_created ASC;";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerP[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 


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
		position: relative;
	}

	.customer-order-details > div:after {
		content: '';
		display: block;
		width: 100%;
		height: 10px;
		position: absolute;
		left: 0;
		border-bottom: 1px dotted #000;
	}

	.customer-order-details > div p {
		position: relative;
		z-index: 5;
		background: #eee;
	}

	.customer-order-details > div p:first-of-type {
		padding-right: 20px;
	}

	.customer-order-details > div p:last-of-type {
		padding-left: 20px;
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

	.customer-info .order-history-overlay {
		position: fixed;
		z-index: 5;
		height: 100%;
		width: 100%;
		top: 0;
		left: 0;
		display: none;
		background: rgba(0,0,0,0.65);
	}

	.customer-info .order-history {
		position: fixed;
		width: 600px;
		top: 150px;
		left: 50%;
		margin-left: -300px;
		background: #fff;
		z-index: 500;
		display: none;
		padding: 20px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}

	.customer-info .order-history #close-order-history {
		font-size: 20px;
		color: tomato;
		cursor: pointer;
		padding: 0 10px;
		margin-right: -10px;
	}

	.table-flex {
		border: 1px solid #e6e6e6;
	}

	.table-flex .table-flex-head {
		padding: 10px 15px;
		background: #f5f5f5;
		text-transform: uppercase;
		font-family: 'Poppins-Medium';
	}

	.table-flex .table-flex-body {
		padding: 10px 15px;
		border-top: 1px solid #e6e6e6;
	}

	.btn-back {
		margin-right: 15px;
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
	<div>
		<button class="btn ssis-btn-secondary btn-back" onclick="window.history.go(-1);">Back</button>
		<?php if ( $_GET['comp'] == 'pending' || $_GET['comp']=='release' ) : ?>
<!--			<button id="open_history" class="btn ssis-btn-primary">Order History</button>-->
		<?php elseif ( $_GET['comp'] == 'recieve' ) : ?>
			<a href="modules/process/receive.php?orderNo=<?= $_GET['orderNo'] ?>" class="btn ssis-btn-primary">Receive</a>
		<?php endif; ?>
	</div>
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
					<input type="text" name="p_lname" class="form-control" id="p_lname" value="<?php echo ucwords( $arrCustomerP[0]["last_name"] ); ?>" readonly>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_fname">First Name</label>
					<input type="text" name="p_fname" class="form-control" id="p_fname" value="<?php echo ucwords( $arrCustomerP[0]["first_name"] ); ?>" readonly>
				</div>
			</div>
			<div class="col-sm-4 col-md-4">
				<div class="form-group">
					<label class="font-weight-bold" for="p_mname">Middle Name</label>
					<input type="text" name="p_mname" class="form-control" id="p_mname" value="<?php echo ucwords( $arrCustomerP[0]["middle_name"] ); ?>" readonly>
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

	

	<div class="prescription-content">
		<div class="form-group">
			<h5>Order Details</h5>
		</div>
		<div class="ssis-bg-gray customer-order-details" style="padding: 30px;">
			<?php for ( $i = 0; $i < sizeof($arrCustomer); $i++ ): ?>
				<div class="row no-gutters align-items-center justify-content-between">
					<p>PO number:</p>
					<p><?= ucwords( $arrCustomer[$i]['order_id'] )." - ". ucwords( $arrCustomer[$i]['item_description'] )." - ".ucwords($arrCustomer[$i]['lens_option'])?></p>
				</div>
				
			<?php endfor; ?>
			
		</div>
	</div>

	
<!--
	<?php if ( $_GET['comp'] == 'pending' || $_GET['comp']=='release' ) : ?>
		<div class="order-history-overlay"></div>
		<div class="order-history" >
			<div class="form-group row no-gutters align-items-center justify-content-between">
				<h5>Order History</h5>
				<span id="close-order-history">&times;</span>
			</div>
			<div class="table-flex">
				<div class="table-flex-head row no-gutters align-items-center justify-content-start">
					<p class="col">status</p>
					<p class="col">Date</p>
					<p class="col">Branch</p>
				</div>

				<?php for ( $h=0; $h < sizeof($arrHistory); $h++ ) : ?>

					<div class="table-flex-body row no-gutters align-items-center justify-content-start">
						<p class="col"><?= ucwords( $arrHistory[$h]['status'] ); ?></p>
						<p class="col"><?= cvdate( $arrHistory[$h]['status_date'] ); ?></p>
						<p class="col"><?= ( $arrHistory[$h]['store_name'] != '' ) ? $arrHistory[$h]['store_name'] : $arrHistory[$h]['lab_name']; ?></p>
					</div>

				<?php endfor; ?>
				
			</table>
		</div>
		
	<?php endif; ?>
-->

</div>

<script>

	$('.prescription-list select').select2();
	
	function commaSeparateNumber(val){
	   while (/(\d+)(\d{3})/.test(val.toString())){
	     val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
	   }
	   return val;
	}

	var format = $('#format-price'),
		newF = format.text();

	format.text( commaSeparateNumber( '₱' + newF ) );

	// ===================================== order history

	$('#open_history').click(function() {
		$('.order-history, .order-history-overlay').fadeIn();
	});

	$('.order-history-overlay, #close-order-history').click(function() {
		$('.order-history, .order-history-overlay').fadeOut();
	});

</script>