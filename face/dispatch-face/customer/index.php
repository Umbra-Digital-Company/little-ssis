<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	
session_save_path($sDocRoot."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page_url = 'dispatch-face';
$page = 'dispatch-face';
$filter_page = 'dispatch_face';
$group_name = 'sunnies_face';

////////////////////////////////////////////////



require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/face/dispatch-face/dispatch_receivable.php";
require $sDocRoot."/face/includes/navbar_face_v2.php";
require $sDocRoot."/face/includes/grab_order_details_face.php";
// require $sDocRoot."/includes/v2/navbar.php";
require $sDocRoot."/includes/v2/functions.php";

function Getnamedispatching($emp_id){
	global $conn;

	$arrstaffName=array();

		$queryName=" SELECT  first_name,middle_name,last_name FROM
					emp_table
					WHERE emp_id='".$emp_id."'
				 ";

	
	$grabParamsNames=array('first_name','middle_name','last_name' );


$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryName)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParamsNames); $i++) { 

            $tempArray[$grabParamsNames[$i]] = ${'result' . ($i+1)};

        };

        $arrstaffName[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

$fullname= $arrstaffName[0]["first_name"]." ".$arrstaffName[0]["last_name"];

return $fullname;


}

?>

<!DOCTYPE html>
<html>

<head>
	<!-- meta tag -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=1.0, minimum-scale=1.0, maximum-scale=1.0">

	<title>Sunnies Specs Optical</title>

	<!-- fonts link -->

	<!-- css files -->
	<?= $header_css; ?>

	<?= $favicon; ?>

	<?= $ie ?>

	<link rel="stylesheet" type="text/css" href="/css/navbar.css">
    <link rel="stylesheet" type="text/css" href="/css/header.css">

    <style type="text/css">
    	html, .ssis-body, .filter-select{
			background-color: #B3A89B !important;
		}
		.filter-select{
			color: #000 !important;
		}
		.ssis-2 .section-header > div h2, .ssis-2 .section-header > div p{
			color: #000 !important;
		}

    	.jumbotron {
    		background: #ffffff;
    	}

    	#customer-personal-details .more-details {
    		display: none;
    	}
    	#customer-personal-details #toggleDetails {
    		text-decoration: underline;
    	}

    	#customer-order-details .table-order-details,
    	#customer-order-details .table-order-details * {
    		border: none;
    	}

    	#target-date h2 {
    		margin-bottom: 0;
    	}

    </style>
    <style type="text/css">
    	
    	.small, small {
		    font-size: 80%;
		    font-weight: 400;
		}

    	.mt-50 {
			margin-top: 50px;
		}
		.mt-80 {
			margin-top: 80px;
		}

		html, 
		body,
		.ssis-body {
			background-color: #5f0000;
		}
		.ssis-2 nav a,
		.ssis-2 .section-header {
			color: #ffffff;
		}
		.ssis-2 .section-header > div {
			padding-bottom: 100px;
		}
		#ssis-content {
			padding: 0;
		}
		#ssis-content .ssis-section .section-inner {
			position: relative;
			background-color: #e6e6e6;
			margin-top: 0;
			border-radius: 40px 40px 0 0;
			box-shadow: 0px -8px 16px 0px rgba(100, 100, 100, 0.35)
		}
		#ssis-content .ssis-section .section-inner .module {
			padding: 20px;
			margin-bottom: 30px;
			border-radius: 12px;
			border: 1px solid;		    
		    box-shadow: 0px 6px 10px -2px grey;
		}
		#ssis-content .ssis-section .section-inner .module .section-header h2 {
			margin-bottom: 30px;
			color: #000000;
		}
		#ssis-content .ssis-section .section-inner #prescription-details .row-b {
			padding: 0 15px;
    		margin-bottom: 20px;
		}
		.wrap-table100 {
			width: 100%;
			border-radius: 12px;
			overflow: hidden;						
		}
		.wrap-table100 tr.head th {
			background-color: #5f0000 !important;
			padding: 25px 30px;
			text-align: center;
		}
		.dispatch-scrollable {
			overflow-x: scroll;
		}

    </style>
    <style>
    	.btnUpdateClass{
    		display: none;
    	}
    	.btnUpdate{
    	  cursor: pointer;
		  top: 0;
		  left: 0;
		  right: 0;
		  bottom: 0;
		  background-color: #5F0000;
		  -webkit-transition: .4s;
		  transition: .4s;
		  border-radius: 34px;
		  width: 90px;
		  height: 34px;
		  margin-right: 5px;
		  color: #fff;
    	}
    	.btnUpdate:hover{
    		background-color: #960404;
    		border-color: #960404;
    		 color: #fff;
    	}
    	.btnUpdate:active{
    		background-color: #b30404;
    		border-color: #b30404;
    		 color: #fff;
    	}
    	.form-group{
    		margin-bottom: 0px;
    	}
		.switch {
		  position: relative;
		  display: inline-block;
		  width: 90px;
		  height: 34px;
		  margin-right: 5px;
		}

		.switch input {display:none;}

		.slider {
		  position: absolute;
		  cursor: pointer;
		  top: 0;
		  left: 0;
		  right: 0;
		  bottom: 0;
		  background-color: #898484;
		  -webkit-transition: .4s;
		  transition: .4s;
		   border-radius: 34px;
		}

		.slider:before {
		  position: absolute;
		  content: "";
		  height: 26px;
		  width: 26px;
		  left: 4px;
		  bottom: 4px;
		  background-color: white;
		  -webkit-transition: .4s;
		  transition: .4s;
		  border-radius: 50%;
		}

		input:checked + .slider {
		  background-color: #5F0000;
		}

		input:focus + .slider {
		  box-shadow: 0 0 1px #2196F3;
		}

		input:checked + .slider:before {
		  -webkit-transform: translateX(26px);
		  -ms-transform: translateX(26px);
		  transform: translateX(55px);
		}

		/*------ ADDED CSS ---------*/
		.slider:after
		{
		 content:'EDIT';
		 color: white;
		 display: block;
		 position: absolute;
		 transform: translate(-50%,-50%);
		 top: 50%;
		 left: 55%;
		 font-size: 13px;
		 font-family: Poppins-Regular;
		}

		input:checked + .slider:after
		{  
		  content:'ON';
		  left: 50%;
		}

		.switch-disabled{
			margin: 10px 15px 0px 0px;
		    color: red;
		}
		
	</style>

</head>
<body class="ssis-2" id="ssis-admin">
<div class="ssis-body">


	<!-- =============================================================== TOP BAR -->

	<?= $navbar ?>
	
	<!-- =============================================================== CONTENT -->

	<div id="ssis-content">

		<section class="ssis-section" id="customer-personal-details">

			<div class="section-header row no-gutters align-items-top justify-content-between">
                <div>                	
                	<i class="zmdi zmdi-hc-5x zmdi-face"></i>
                    <h2><?php echo ucfirst($arrCustomerDetail[0]["first_name"]).' '.ucfirst($arrCustomerDetail[0]["last_name"]); ?></h2>
                    <p>Customer Profile</p>
                    <p><strong><?= $arrCustomerDetail[0]["po_number"] ?></strong></p>
                </div>                    
            </div>	

		</section>

		<section class="ssis-section" id="customer-personal-details">
			<div class="section-inner">

				<div class="module" id="personal-details">
					<div class="section-header row no-gutters align-items-center justify-content-between">
						<h2>Personal Details</h2>
					</div>					
					<div class="row align-items-top justify-content-between">

						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label class="font-weight-bold" for="p_lname">Last Name</label>
								<input type="text" name="p_lname" class="form-control" id="p_lname" value="<?= ucwords(strtolower($arrCustomerDetail[0]["last_name"])) ?>" readonly>
							</div>
						</div>
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label class="font-weight-bold" for="p_fname">First Name</label>
								<input type="text" name="p_fname" class="form-control" id="p_fname" value="<?= ucwords(strtolower($arrCustomerDetail[0]["first_name"])) ?>" readonly>
							</div>
						</div>
						<div class="col-sm-4 col-md-4">
							<div class="form-group">
								<label class="font-weight-bold" for="p_mname">Middle Name</label>
								<input type="text" name="p_mname" class="form-control" id="p_mname" value="<?= ucwords(strtolower($arrCustomerDetail[0]["middle_name"])) ?>" readonly>
							</div>
						</div>
						<div class="more-details col-md-12">
							<div class="row">
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_province">Province</label>
										<input type="text" name="p_province" class="form-control" id="p_province" value="<?= ucwords(strtolower(str_replace("-", " ", $arrCustomerDetail[0]["province"]))) ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_city">City</label>
										<input type="text" name="p_city" class="form-control" id="p_city" value="<?= ucwords(strtolower(str_replace("-", " ", $arrCustomerDetail[0]["city"]))) ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_barangay">Barangay</label>
										<input type="text" name="p_barangay" class="form-control" id="p_barangay" value="<?= ucwords(strtolower(str_replace("-", " ", $arrCustomerDetail[0]["barangay"]))) ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="P_home_address">Home Address</label>
										<input type="text" name="P_home_address" class="form-control" id="P_home_address" value="Value" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_bdate">Birthdate</label>
										<input type="text" name="p_bdate" class="form-control" id="p_bdate" value="<?= cvdate2($arrCustomerDetail[0]["birthday"]); ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_age">Age</label>
										<input type="text" name="p_age" class="form-control" id="p_age" value="<?= $arrCustomerDetail[0]["age"] ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_gender">Gender</label>
										<input type="text" name="p_gender" class="form-control" id="p_gender" value="<?= ucwords($arrCustomerDetail[0]["gender"]) ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_email">Email Address</label>
										<input type="text" name="p_email" class="form-control" id="p_email" value="<?= $arrCustomerDetail[0]["email_address"] ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="mnum">Mobile Number</label>
										<input type="text" name="mnum" class="form-control" id="mnum" value="<?= $arrCustomerDetail[0]["phone_number"] ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_joining_date">Joining Date</label>
										<input type="text" name="p_joining_date" class="form-control" id="p_joining_date" value="<?= cvdate2($arrCustomerDetail[0]["date_created"]); ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_specs_branch">Branch Applied</label>
										<input type="text" name="p_specs_branch" class="form-control" id="p_specs_branch" value="<?= str_replace("Up Town Center", "UP Town Center", str_replace("Sm ", "SM ", str_replace("Mw ", "MW ", str_replace("Ali ", "ALI ", ucwords( strtolower($arrCustomerDetail[0]["branch_applied"]) ))))) ?>" readonly>
									</div>
								</div>
								<div class="col-sm-4 col-md-4">
									<div class="form-group">
										<label class="font-weight-bold" for="p_specs_branch">Target Date</label>
										<input type="text" name="p_specs_branch" class="form-control" id="p_specs_branch" value="<?= cvdate2($arrCustomerDetail[0]["target_date"]) ?>" readonly>
									</div>
								</div>
								<div class="col-sm-12 col-md-12">
									<div class="form-group">
										<label class="font-weight-bold" for="p_specs_branch">Remarks</label>
										<input type="text" name="p_specs_branch" class="form-control" id="p_specs_branch" value="<?= $arrCustomerDetail[0]["remarks"]; ?>" readonly>
									</div>
								</div>
							</div>
						</div>
							<div class="col-md-12 text-center" style="padding: 10px 0;">
								<a id="toggleDetails" class="small text-uppercase" href="#" data-action="show">Show more</a>
							</div>
					</div>
				</div>
				<?php
				if($_SESSION['store_code'] == 147 || $_SESSION['store_code'] == 148 || $_SESSION['store_code'] == 149){
					$collapse = '';
					$readonly = '';
					if($arrCustomerDetail[0]["psa_orders_specs_id"] == '' || $arrCustomerDetail[0]["psa_orders_specs_id"] == null){
						$email_address = $arrCustomerDetail[0]["email_address"];
						$phone_number = $arrCustomerDetail[0]["phone_number"];
						$country = $arrCustomerDetail[0]["country"];
						$province = $arrCustomerDetail[0]["province"];
						$city = $arrCustomerDetail[0]["city"];
						$barangay = $arrCustomerDetail[0]["barangay"];
						$address1 = $arrCustomerDetail[0]["address"];
						echo '<div class="d-flex justify-content-end">
								<button class="btn btn-link" data-toggle="collapse" data-target="#shipping-address">Shipping Details <span><strong>+</strong></span></button>
							</div>';
						$collapse = 'collapse';
					}else{
						$email_address = $arrCustomerDetail[0]["psa_email_address"];
						$phone_number = $arrCustomerDetail[0]["psa_phone_number"];
						$country = $arrCustomerDetail[0]["psa_country"];
						$province = $arrCustomerDetail[0]["psa_province"];
						$city = $arrCustomerDetail[0]["psa_city"];
						$barangay = $arrCustomerDetail[0]["psa_barangay"];
						$address1 = $arrCustomerDetail[0]["psa_address1"];
						$readonly = 'readonly';
					}
				?>
				<div class="module <?=$collapse?>" id="shipping-address">
					<form action="#" id="update-shipping_adddress" method="post">
						<input type="hidden" name="order_id" value="<?=$arrCustomerDetail[0]['order_id']?>" />
						<input type="hidden" name="profile_id" value="<?=$_GET['profile_id']?>" />
						<input type="hidden" name="orders_specs_id" value="<?=$_GET['orderNo']?>" />
						<div class="section-header row no-gutters align-items-center justify-content-between row-a">
							<h2 style="margin-bottom: 10px;">Contact Details</h2>
						</div>
						<div class="row align-items-top justify-content-start row-b">		
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_phone_number">Email Address</label>
									<input type="text" name="sa_email_address" class="form-control" id="sa_email_address" value="<?= str_replace("-", "", $email_address) ?>"<?= $readonly?> required>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_phone_number">Mobile Phone Number</label>
									<input type="text" name="sa_phone_number" class="form-control" id="sa_phone_number" value="<?= str_replace("-", "", $phone_number) ?>"<?= $readonly?> required>
								</div>
							</div>
						</div>
						<br>
						<div class="section-header row no-gutters align-items-center justify-content-between row-a">
							<h2 style="margin-bottom: 10px;">Shipping Address</h2>
						</div>
						<div class="row align-items-top justify-content-start row-b">		
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_country">Country</label>
									<input type="text" name="sa_country" class="form-control" id="sa_country" value="<?= strtoupper($country) ?>"<?= $readonly?> required>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_province">Province</label>
									<input type="text" name="sa_province" class="form-control" id="sa_province" value="<?= ucwords(str_replace("-", " ", $province)) ?>"<?= $readonly?> required>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_city">City</label>
									<input type="text" name="sa_city" class="form-control" id="sa_city" value="<?= ucwords(str_replace("-", " ", $city)) ?>"<?= $readonly?> required>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_barangay">Barangay</label>
									<input type="text" name="sa_barangay" class="form-control" id="sa_barangay" value="<?= $barangay?>"<?= $readonly?>>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_zip_code">Zip Code</label>
									<input type="text" name="sa_zip_code" class="form-control" id="sa_zip_code" value="<?= $arrCustomerDetail[0]["psa_zip_code"] ?>"<?= $readonly?> required>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_address_1">Address 1</label>
									<input type="text" name="sa_address_1" class="form-control" id="sa_address_1" value="<?= strtoupper($address1) ?>"<?= $readonly?> required>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_address_2">Address 2</label>
									<input type="text" name="sa_address_2" class="form-control" id="sa_address_2" value="<?= $arrCustomerDetail[0]["psa_address2"] ?>"<?= $readonly?>>
								</div>
							</div>
							<div class="col-sm-4 col-md-4">
								<div class="form-group">
									<label class="font-weight-bold" for="sa_special_instructions">Special Instructions</label>
									<input type="text" name="sa_special_instructions" class="form-control" id="sa_special_instructions" value="<?= $arrCustomerDetail[0]["psa_special_instructions"] ?>"<?= $readonly?>>
								</div>
							</div>
						</div>
						<br>
						<?php if($arrCustomerDetail[0]["psa_orders_specs_id"] == ''|| $arrCustomerDetail[0]["psa_orders_specs_id"] == null){ ?>
							<div class="d-flex justify-content-center">
								
								<div class="form-group ">
									<input type="submit" class="btn" value="Submit" style="border-radius: 30px; padding-left: 30px;padding-right: 30px; background-color: #5f0000 !important; color: #ffff; font-weight: bold;"/>
								</div>
							</div>

						<?php }else{ ?>
							<div class="d-flex justify-content-end">
								
								<div class="form-group text-center btnUpdateClass">
									<input type="submit" class="btn btnUpdate" value="Submit" />
								</div>
								<label class="switch">
								  <input type="checkbox" id="shipping_address_update"  />
								  <div class="slider round"><span class="on"></span><span class="off"></span></div>
								</label>
							</div>
						<?php } ?>
					</form>
				</div>
				<?php } ?>
				<!-- <div class="module" id="prescription-details">
					<div class="section-header row no-gutters align-items-center justify-content-between row-a">
						<h2>Prescription Details</h2>
					</div>
					<div class="row align-items-top justify-content-between row-b">
						<p style="font-size: 18px;">
							<u><b>Optometrist</b></u>: 

							<?php									

								if($arrCustomerPrescription[0]['doc_first_name'] != "") {

									echo ucwords(strtolower($arrCustomerPrescription[0]['doc_first_name']." ".$arrCustomerPrescription[0]['doc_middle_name']." ".$arrCustomerPrescription[0]['doc_last_name']));

								}	
								else {

									echo 'N/A';

								};

							?>

						</p>
					</div>
					<div class="row align-items-top justify-content-between row-b">
						<div class="wrap-table100 non-search js-pscroll">
							<div class="table100 ver1 dispatch-scrollable">
								<table class="table-prescription-details">
									<tbody>

										<?php if ($arrCustomerPrescription[0]['lens_option']=='without prescription'   ) : ?>

											<tr>
												<td align="center" colspan="8"><p>No Prescription Found</p></td>
											</tr>

										<?php elseif ($arrCustomerPrescription[0]['lens_option']=='with prescription'   && $arrCustomerPrescription[0]['prescription_id'] =='') : ?>

											<tr>
												<td align="center" colspan="8"><p>No Prescription Found</p></td>
											</tr>

										<?php else : ?>

											<thead class="dispatch-table-header">
												<tr class="row100 head">
													<th align="center">FINAL RX</th>
													<th align="center">SPH</th>
													<th align="center">CYL</th>
													<th align="center">AXIS</th>
													<th align="center">ADD</th>
													<th align="center">IPD</th>
													<th align="center">PH</th>
													<th align="center">VA WITH RX</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td nowrap class="cell100 text-center small"><p>OD</p></td>
													<td nowrap class="cell100 column2 small text-center">

														<?php 

															if($arrCustomerPrescription[0]['sph_od']=='0'){ 

																echo "plano"; 

															} 
															else{

																if($arrCustomerPrescription[0]['sph_od']>0){ 

																	$signSphOD="+"; 

																}
																else{ 

																	$signSphOD="";

																}

																echo $signSphOD.number_format($arrCustomerPrescription[0]['sph_od'], 2, '.', ''); 

															} 

														?>
															
													</td>
													<td nowrap class="cell100 column3 small text-center">

														<?php 

															echo number_format($arrCustomerPrescription[0]['cyl_od'], 2, '.', ''); 

														?>
															
													</td>
													<td nowrap class="cell100 column4 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['axis_od']; 

														?>
															
													</td>
													<td nowrap class="cell100 column5 small text-center">

														<?php 

															$addSignOD="";

															if($arrCustomerPrescription[0]['add_od']>0){ 

																$addSignOD="+";

															}
															else{ 

																$addSignOD=""; 

															};

															echo $addSignOD.number_format($arrCustomerPrescription[0]['add_od'], 2, '.', ''); 

														?>
															
													</td>
													<td nowrap class="cell100 column6 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['ipd_od']; 

														?>
															
													</td>
													<td nowrap class="cell100 column7 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['ph_od']; 

														?>
															
													</td>
													<td nowrap class="cell100 column8 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['va_od']; 

														?>
															
													</td>
												</tr>
												<tr>
													<td nowrap class="cell100 small text-center"><p>OS</p></td>
													<td nowrap class="cell100 column2 small text-center">

														<?php 

															if($arrCustomerPrescription[0]['sph_os']=='0'){ 

																echo "plano"; 

															}
															else{

																$signSphOS="";

																if($arrCustomerPrescription[0]['sph_os']>0){ 

																	$signSphOS="+"; 

																}
																else{ 

																	$signSphOS=""; 

																};

																echo $signSphOS.number_format($arrCustomerPrescription[0]['sph_os'], 2, '.', ''); 

															}

														?>
															
													</td>
													<td nowrap class="cell100 column3 small text-center">

														<?php 

															echo number_format($arrCustomerPrescription[0]['cyl_os'], 2, '.', ''); 

														?>
															
													</td>
													<td nowrap class="cell100 column4 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['axis_os']; 

														?>
															
													</td>
													<td nowrap class="cell100 column5 small text-center">

														<?php 

															$signAddOs="";

															if($arrCustomerPrescription[0]['add_os']>0){ 

																$signAddOs="+"; 
															}
															else{ 

																$signAddOS =""; 

															};
												
															echo $signAddOs.number_format($arrCustomerPrescription[0]['add_os'], 2, '.', ''); 

														?>
															
													</td>
													<td nowrap class="cell100 column6 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['ipd_os']; 

														?>
															
													</td>
													<td nowrap class="cell100 column7 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['ph_os']; 

														?>
															
													</td>
													<td nowrap class="cell100 column8 small text-center">

														<?php 

															echo $arrCustomerPrescription[0]['va_os']; 

														?>
															
													</td>
												</tr>
											</tbody>

										<?php endif; ?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div> -->

				<div class="module" id="order-summary">
					<div class="section-header row no-gutters align-items-center justify-content-between row-a">
						<h2>Order Summary</h2>
					</div>
					<div class="row align-items-top justify-content-between row-b">		
					<!-- <div class="col-sm-6 col-md-3">
							<div class="form-group">
							<label class="font-weight-bold" for="order_frame">Dispatch Staff :</label>
							<input type="text" name="order_frame" class="form-control" id="order_frame" value="<?php echo Getnamedispatching($arrCustomerDetail[0]['dispatch_staff']) ?>" readonly>
							</div>
					</div>	
					<div class="col-sm-6 col-md-3">
							<div class="form-group">
							<label class="font-weight-bold" for="order_frame">Dispatch doctor:</label>
							<input type="text" name="order_frame" class="form-control" id="order_frame" value="<?php echo Getnamedispatching($arrCustomerDetail[0]['dispatch_doctor']) ?>" readonly>
							</div>
					</div>	 -->


						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="font-weight-bold" for="order_frame">Frame</label>
								<?php 
										if($arrCustomerDetail[0]['product_upgrade']=='sunnies_face'){
												$frame_ordered=$arrCustomerDetail[0]['item_description_face'];
										}else{
											$frame_ordered=$arrCustomerDetail[0]['item_name_poll'];
										}
								?>
								<input type="text" name="order_frame" class="form-control" id="order_frame" value="<?= ucwords( $frame_ordered ); ?>" readonly>
							</div>
						</div>
						<!-- <div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="font-weight-bold" for="order_vision">Vision</label>
								<input type="text" name="order_vision" class="form-control" id="order_vision" value="<?= ucwords( str_replace("_",' ',$arrCustomerDetail[0]['prescription_vision']) ) ?>" readonly>
							</div>
						</div> -->
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="font-weight-bold" for="order_upgrade">Upgrade</label>
								<input type="text" name="order_upgrade" class="form-control" id="order_upgrade" value="<?= ucwords( str_replace("_",' ',$arrCustomerDetail[0]['product_upgrade'])); ?>" readonly>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="font-weight-bold" for="order_price">Price</label>

								<?php

									if(strtolower($arrCustomerDetail[0]['currency']) == 'php') {

										$curA = "₱";
										$curB = "";

									}
									elseif(strtolower($arrCustomerDetail[0]['currency']) == 'vnd') {

										$curA = "";
										$curB = " VND";

									}

								?>

								<input type="text" name="order_price" class="form-control" id="order_price" value="<?= $curA ?><?= number_format($arrCustomerDetail[0]['price'], 2, '.', ',') ?><?= $curB ?>" readonly>
							</div>
						</div>

						<?php 

							date_default_timezone_set("Asia/Manila");
							$date = date('Y-m-d h:i:s');

							$targetDate = $arrCustomerDetail[0]['target_date'];
							
							if($date < $targetDate) {

								$dStatus = 'On Time';
								$dStatusStyle = 'green';

							}
							else {

								$dStatus = 'Late';
								$dStatusStyle = 'red';

							};

						?>

						<!-- <div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="font-weight-bold" for="order_target_date">Target Date</label>
								<input type="text" name="order_target_date" class="form-control" id="order_target_date" value="<?= $targetDate ?>" readonly>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="form-group">
								<label class="font-weight-bold" for="order_timing">&nbsp;</label>
								<input type="text" name="order_timing" class="form-control" id="order_timing" value="<?= $dStatus ?>" style="color: <?= $dStatusStyle ?>;" readonly>
							</div>
						</div> -->

						<!-- <?php if ( ! empty($arrCustomerDetail[0]['signature']) ) : ?>

						<div class="col-sm-6 col-md-6">
							<div class="form-group">
								<label class="font-weight-bold" for="order_signature">Signature</label>
								<div id="order_signature" style="background-color: #ffffff;">
									<img class="img-fluid center-block" src="<?php echo $arrCustomerDetail[0]["signature"]; ?>">
								</div>
							</div>
						</div>

						<?php endif; ?> -->

					</div>
				</div>

			</div>
		</section>

	</div>

</div>

<!-- ===================================================================================================================================== -->

<script src="/js/jquery-3.2.1.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/tether.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/select2.min.js"></script>

<script>

	$('.prescription-used select').select2();
	$('#order_status_action').select2({
		minimumResultsForSearch: -1,
	});
	
	// format price value
	function commaSeparateNumber(val){
	   while (/(\d+)(\d{3})/.test(val.toString())){
	     val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
	   }
	   return val;
	}

	var orderTotal = $('.dispatch-total td p').text(),
		lensTotal = $('.dispatch-lens td p').text();

	// $('.dispatch-total td p').text( '₱' + commaSeparateNumber(orderTotal) );
	// $('.dispatch-lens td p').text( '₱' + commaSeparateNumber(lensTotal) );

	// date picker
	var dateToday = new Date();
	$( "#extend-date" ).datepicker({
        showButtonPanel: true,
        minDate: dateToday
    });

	// order status change
    $('#order_status_action').change(function() {
    	if ( $(this).val() != null ) {
    		$('#save_status').removeAttr('disabled');
    	}

    	// check if extend
    	if ( $(this).val() == 'extend' ) {
    		$('#order_note, #extend-date').val('').parent().removeClass('d-none');
    	} else {
    		$('#order_note, #extend-date').val('').parent().addClass('d-none');
    	}
    });

    // submit form
    $('.order-status-form').submit(function(e) {
    	if ( $('#order_status_action').val() == 'extend' ) {
    		if ( $('#extend-date').val() == '' ) {
    			e.preventDefault();
    			$('#extend-date').css('border-color', 'tomato');
    		} else {
    			$('#extend-date').css('border-color', '#e6e6e6');
    		}
    	}
    });

    // ======================= show more details

	$('#toggleDetails').click(function(e) {
		e.preventDefault();
		var action = $(this).data('action'),
			btn = $(this);
		$('.more-details').slideToggle(300, function() {
			$(this).toggleClass('open');

			if ( $(this).hasClass('open') ) {
				$('#toggleDetails').text('show less');
			} else {
				$('#toggleDetails').text('show more');
			}
		});
	});

	// ======================= style fixes

	setTimeout(function() {

		var maH = $('.match-this-a').height();
		var pCalc = maH - $('.match-this-b').height();
		var pad = pCalc / 2;

		$('.match-this-b').css('padding-top', pad).css('padding-bottom', pad);

	}, 1000);

	setTimeout(function() {

		var maH = $('.match-this-a').height();
		var pCalc = maH - $('.match-this-b').height();
		var pad = pCalc / 2;

		$('.match-this-b').css('padding-top', pad).css('padding-bottom', pad);

	}, 3000);

	$("#update-shipping_adddress").submit(function(e){
		e.preventDefault();
		$.ajax({
	        url: "/process/dispatch/shipping_detail.php",
	        type: "POST",
	        data: $(this).serialize(),
	        dataType: 'html',
	        success: function(response){
	        	//console.log(response);
	        	window.location.reload(true);
	        },
	        error: function(){
	        }
        });//END :: AJAX
	});
	$("#shipping_address_update").change(function(){
		let divClassUpdate = $(this).parent().parent().find('.btnUpdateClass');
		($(this).is(':checked')) ? divClassUpdate.show() : divClassUpdate.hide();

		($(this).is(':checked')) ? $("#update-shipping_adddress").find('input').removeAttr('readonly') : $("#update-shipping_adddress").find('input').attr('readonly',true);
	});

</script>

</div>
</body>
</html>