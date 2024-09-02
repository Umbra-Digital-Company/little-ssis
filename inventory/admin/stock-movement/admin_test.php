<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'dashboard';
$page_url = 'stock-movement';

////////////////////////////////////////////////
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);
// Set access for Admin and Warehouse account
if($_SESSION['user_login']['userlvl'] != '13') {

	header('location: /');
	exit;

}

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/dashboard/functions.php";
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
	
require $sDocRoot."/inventory/includes/grab_poll_51.php";
// require $sDocRoot."/inventory/includes/w_admin_function.php";

require $sDocRoot."/inventory/includes/w_admin_functionv5.php";


// require $sDocRoot."/inventory/includes/grab_inventory_products_v2.php";
// require $sDocRoot."/inventory/includes/grab_inventory_products_pd.php";
require $sDocRoot."/inventory/includes/inventory_functions.php";
require $sDocRoot."/inventory/includes/grab_inventory_sales_v2.php";





// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

/////SET USER
if($_SESSION['user_login']['userlvl']=='1'){

	$inventory_user ="warehouse";
}
else{
	$inventory_user = $_SESSION['user_login']['store_code'] ;
}
	
$total_transfer_plus = "";
$total_transfer_minus = "";



if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$i_date_start = date('Y-m').'-1';
		$i_date_end= date('Y-m-t');
	}

}
else{
	$i_date_start = date('Y-m').'-1';
	$i_date_end = date('Y-m-t');
}

if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$datecurrentH = date('Y-m');
		$dateStartpdh = date('Y-m').'-1';
		$dateEndpdh = date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
		$datecurrentH = date('Y-m',strtotime("-1 days"));
	 	$dateStartpdh = date('Y-m-d',strtotime("-1 days"));
	 	$dateEndpdh = date('Y-m-t');
	}elseif($_GET['date']=='week'){
		$datecurrentH = date( 'Y-m-d', strtotime( 'sunday this week' ) );
		$dateStartpdh = date( 'Y-m-d', strtotime( 'sunday this week' ) );
		 $dateEndpdh = date( 'Y-m-d', strtotime( 'saturday this week' ) );
	}
	elseif($_GET['date']=='custom'){
		$datecurrentH = $_GET['data_range_start_year']."-".$_GET['data_range_start_month'];
		 $dateStartpdh = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		 $dateEndpdh = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
	}
	elseif($_GET['date']=='all-time'){
		$datecurrentH = date('Y-m');
		$dateStartpdh = date('Y-m').'-1';
		$dateEndpdh = date('Y-m-t');
	}
	else{
		$datecurrentH = date('Y-m');
		$dateStartpdh = date('Y-m').'-1';
		$dateEndpdh = date('Y-m-t');
	}

}
else{
	$datecurrentH = date('Y-m');
	$dateStartpdh = date('Y-m').'-1';
		$dateEndpdh = date('Y-m-t');
}

$branchName = "";

if(isset($_GET['filterStores'])){

	$store_id=$_GET['filterStores'];

	for ($i=0; $i < sizeOf($arrStore); $i++) { 
		if($arrStore[$i]['store_id'] == $store_id) {
			$branchName = $arrStore[$i]['store_name'];
		}
	};

	if ( $branchName=="" ) {
		for ($i=0; $i < sizeOf($arrLab); $i++) { 
			if($arrLab[$i]['lab_id'] == $store_id) {
				$branchName = $arrLab[$i]['lab_name'];
			};
		};
	}

}else{
	if($_SESSION['user_login']['userlvl'] == '13' || $_SESSION['user_login']['userlvl'] == '1' ){
		$branchName = "Warehouse";
		$store_id='warehouse';

	}else{
		$store_id=$_SESSION['store_code'];
	}
}

$datediff = strtotime($dateStartpdh) - strtotime($dateEndpdh);

 $dayscount= str_replace("-","",round($datediff / (60 * 60 * 24)));


if(isset($_GET['filterStores'])){
	$branchtype=	$_GET['filterStores'];
}else{
	$branchtype="";
}


if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-d',strtotime("-1 days"));
	}elseif($_GET['date']=='week'){
		$dateStart = date( 'Y-m-d', strtotime( 'monday this week' ) );
		 $dateEnd = date( 'Y-m-d', strtotime( 'sunday this week' ) );
	}
	elseif($_GET['date']=='custom'){
		 $dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		 $dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
	}
	elseif($_GET['date']=='all-time'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	else{
		$dateStart = date('Y-m-d');
			$dateEnd= date('Y-m-t');
	}
	
}
else{
	$dateStart = date('Y-m-d');
		$dateEnd= date('Y-m-t');
}




?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url) ?>
	
	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>
		
		<div class="ssis-content">

			<div class="dashboard-filter">
				<div class="filter-header row no-gutters align-items-center">
					<img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid" id="close-filter">
					<p class="h2 ml-3">Inventory Filter</p>
				</div>
				<form method="GET" class="filter-body">
					<div id="showLocation">
						<p class="text-uppercase font-bold text-primary">select store or lab</p>
						<div class="row mt-3 store-form">

							<div class="col-12">
								<select name="filterStores" id="filterStores" class="select2 form-control">
									<option value="">-- Select Branch --</option>
									<optgroup label="STORE NAME">
										<?php for ($i=0;$i<sizeof($arrStore);$i++) { ?>
											<option value="<?= $arrStore[$i]['store_id'] ?>" <?=$branchtype==$arrStore[$i]['store_id'] ? 'selected' : '' ?>><?= ucwords(str_replace(['ali','sm','mw'],['ALI','SM','MW'],strtolower($arrStore[$i]['store_name']))) ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="LAB NAME">
										<?php for ($i=0;$i<sizeof($arrLab);$i++) { ?>
											<option value="<?= $arrLab[$i]['lab_id'] ?>" <?= $branchtype==$arrLab[$i]['lab_id'] ? 'selected' : '' ?>><?= ucwords(str_replace('mtc', 'MTC', str_replace('-', ' ', $arrLab[$i]['lab_name']))) ?></option>
										<?php } ?>
									</optgroup>
								</select>
							</div>

						</div>
					</div>
					<div class="mt-4" id="showDateTime">
						<p class="text-uppercase font-bold text-primary">Select Time Range</p>
						<div class="mt-3 row no-gutters">
							<div class="col-12 col-md-6">
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionYesterday" value="yesterday" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "yesterday" ) ? 'checked="checked"' : '' ?>>
										<label for="optionYesterday" class="custom_checkbox"></label>
										<label for="optionYesterday" class="m-0 ml-2">Yesterday</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionDay" value="day" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "day" || !isset($_GET["date"]) ) ? 'checked="checked"' : '' ?>>
										<label for="optionDay" class="custom_checkbox"></label>
										<label for="optionDay" class="m-0 ml-2">Today</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionWeek" value="week" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "week" ) ? 'checked="checked"' : '' ?>>
										<label for="optionWeek" class="custom_checkbox"></label>
										<label for="optionWeek" class="m-0 ml-2">This Week</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionMonth" value="month" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "month" ) ? 'checked="checked"' : '' ?>>
										<label for="optionMonth" class="custom_checkbox"></label>
										<label for="optionMonth" class="m-0 ml-2">This Month</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionYear" value="year" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "year" ) ? 'checked="checked"' : '' ?>>
										<label for="optionYear" class="custom_checkbox"></label>
										<label for="optionYear" class="m-0 ml-2">This Year</label>
									</div>
								</div>
								<div class="mb-2">
									<div class="d-flex align-items-center">
										<input class="sr-only checkbox" name="date" type="radio" id="optionAllTime" value="all-time" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "all-time" ) ? 'checked="checked"' : '' ?>>
										<label for="optionAllTime" class="custom_checkbox"></label>
										<label for="optionAllTime" class="m-0 ml-2">All Time</label>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="d-flex align-items-center">
									<input class="sr-only checkbox" name="date" type="radio" id="optionCustom" value="custom" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "custom" ) ? 'checked="checked"' : '' ?>>
									<label for="optionCustom" class="custom_checkbox"></label>
									<label for="optionCustom" class="m-0 ml-2">Custom Range</label>
								</div>								    		

								<?php

									// General Date
									$sTimeMonth = date('F');
									$sTimeDay 	= date('d');
									$sTimeYear  = date('Y');

									// Data Range Start
									if(isset($_GET['data_range_start_month'])) {

										$sStartMonth = $_GET['data_range_start_month'];
										$sStartDay 	 = $_GET['data_range_start_day'];
										$sStartYear  = $_GET['data_range_start_year'];

									}
									else {

										$sStartMonth = '';
										$sStartDay 	 = '';
										$sStartYear  = '';

									};

									// Data Range End
									if(isset($_GET['data_range_end_month'])) {

										$sEndMonth = $_GET['data_range_end_month'];
										$sEndDay   = $_GET['data_range_end_day'];
										$sEndYear  = $_GET['data_range_end_year'];

									}
									else {

										$sEndMonth = '';
										$sEndDay   = '';
										$sEndYear  = '';

									};

								?>

								<div class="row-data-range mt-3">	
									<p>Start Date</p>
									<div class="row no-gutters mt-2">
										<div class="col-12 col-md-6 mb-2 mb-md-0 pr-0 pr-md-2">												    		
											<select class="form-control select" id="data_range_start_month" name="data_range_start_month"

											<?php

												if(!isset($_GET['data_range_start_month'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=0; $i < 12; $i++) { 
													
														$month = date("F", strtotime('01.'.($i + 1).'.2001')); 
														$sStartMonthName = date("F", strtotime('01.'.$sStartMonth.'.2001')); 
														$monthSelect = "";

														if($sStartMonth != "" && $sStartMonthName == $month) {

															$monthSelect = " selected";

														};
														$xy="";
														if($i<9){
																$xy="0";
														}else{
																$xy="";
														}

														echo '<option value="'.$xy.($i + 1).'" data-month="'.$month.'"'.$monthSelect.'>'.$month.'</option>';

													};

												?>													    			

											</select>
										</div>
										<div class="col-12 col-md-3 mb-2 mb-md-0 pr-0  pr-md-2">
											<select class="form-control select" id="data_range_start_day" name="data_range_start_day"

											<?php

												if(!isset($_GET['data_range_start_day'])) {

													echo ' disabled';
													$numDays = $arrMonthDays["January"];

												}
												else {

													$curMonth = date('F');
													$numDays = $arrMonthDays[$curMonth];

												};

											?>

											>

												<?php

													for ($i=1; $i < ($numDays + 1); $i++) { 

														$daySelect = "";

														if($sStartDay != "" && $sStartDay == $i) {

															$daySelect = " selected";

														};
														$xy="";
														if($i<9){
																$xy="0";
														}else{
																$xy="";
														}

														echo '<option value="'.$xy.$i.'"'.$daySelect.'>'.(sprintf("%02d", $i)).'</option>';
														
													};

												?>													    			
												
											</select>		
										</div>
										<div class="col-12 col-md-3">											    		
											<select class="form-control select mr-2" id="data_range_start_year" name="data_range_start_year"

											<?php

												if(!isset($_GET['data_range_start_year'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=2016; $i <= $sTimeYear; $i++) { 

														$yearSelect = "";

														if($sStartYear != "" && $i == $sStartYear) {

															$yearSelect = " selected";

														}
														else if($sStartYear == "" && $i == $sTimeYear) {

															$yearSelect = " selected";

														}
														
														echo '<option value="'.$i.'"'.$yearSelect.'>'.$i.'</option>';

													};

												?>

											</select>
										</div>
									</div>
								</div>
								<div class="row-data-range mt-3">			
									<p>End Date</p>
									<div class="row no-gutters mt-2">	
										<div class="col-12 col-md-6 mb-2 mb-md-0 pr-0  pr-md-2">
											<select class="form-control select" id="data_range_end_month" name="data_range_end_month"

											<?php

												if(!isset($_GET['data_range_end_month'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=0; $i < 12; $i++) { 
													
														$month = date("F", strtotime('01.'.($i + 1).'.2001')); 
														$sEndMonthName = date("F", strtotime('01.'.$sEndMonth.'.2001')); 
														$monthSelect = "";

														if($sEndMonth != "" && $sEndMonthName == $month) {

															$monthSelect = " selected";

														}
														else if($sEndMonth == "" && $sTimeMonth == $month) {

															$monthSelect = " selected";

														};
														$xy="";
														if($i<9){
																$xy="0";
														}else{
																$xy="";
														}
														echo '<option value="'.$xy.($i + 1).'" data-month="'.$month.'"'.$monthSelect.'>'.$month.'</option>';

													};

												?>													    			

											</select>
										</div>
										<div class="col-12 col-md-3 mb-2 mb-md-0 pr-0  pr-md-2">
											<select class="form-control select" id="data_range_end_day" name="data_range_end_day"

											<?php

												if(!isset($_GET['data_range_end_day'])) {

													echo ' disabled';
													$numDays = $arrMonthDays["January"];

												}
												else {

													$curMonth = date('F');
													$numDays = $arrMonthDays[$curMonth];

												};

											?>

											>

												<?php													    		

													for ($i=1; $i < ($numDays + 1); $i++) { 

														$daySelect = "";

														if($sEndDay != "" && $sEndDay == $i) {

															$daySelect = " selected";

														}
														else if($sEndDay == "" && $sTimeDay == $i) {

															$daySelect = " selected";

														};		
														$xy="";
														if($i<=9){
																$xy="0";
														}else{
																$xy="";
														}
														echo '<option value="'.$xy.$i.'"'.$daySelect.'>'.(sprintf("%02d", $i)).'</option>';
														
													};

												?>													    			
												
											</select>	
										</div>	
										<div class="col-12 col-md-3">											    		
											<select class="form-control select mr-2" id="data_range_end_year" name="data_range_end_year"

											<?php

												if(!isset($_GET['data_range_end_year'])) {

													echo ' disabled';

												};

											?>

											>

												<?php

													for ($i=2016; $i <= $sTimeYear; $i++) { 

														$yearSelect = "";

														if($sEndYear != "" && $i == $sEndYear) {

															$yearSelect = " selected";

														}
														else if($sEndYear == "" && $i == $sTimeYear) {

															$yearSelect = " selected";

														}
														
														echo '<option value="'.$i.'"'.$yearSelect.'>'.$i.'</option>';

													};

												?>

											</select>
										</div>
									</div>
								</div>		
							</div>
						</div>
					</div>

					<div class="text-center mt-5 pb-5">
						<button class="btn btn-primary" type="submit">Submit</button>
						<?php if(isset($_GET['date'])) { ?>

							<a href="/inventory/dashboard/" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline d-lg-none">
								<button class="btn btn-danger" type="button">reset filter</button>
							</a>

						<?php }; ?>
					</div>
				</form>
			</div>

			<div class="d-flex no-gutters align-items-center" id="data-filter">
				<div class="col">
					<div class="d-flex align-items-center">
						<img src="<?= get_url('images/icons/icon-dashboard.png') ?>" alt="dashboard" class="img-fluid d-none d-md-block">
						<section class="ml-0 ml-md-3">
							<p class="h3 font-bold" id="store_str"><?= ucwords(str_replace(['ali','sm','mw','mtc','hq','-'],['ALI','SM','MW','MTC','HQ',' '],strtolower($branchName))) ?></p>
							<p class="text-secondary mt-2"><?= $date_title ?></p>
						</section>
					</div>
				</div>
				<?php if(isset($_GET['date'])) { ?>

					<a href="/inventory/dashboard/" class="d-none d-lg-block text-danger text-uppercase font-bold">
						reset filter
					</a>

				<?php }; ?>
				<a href="#" id="toggle-filter"><img src="<?= get_url('images/icons/icon-filter.png') ?>" width="30" alt="filter" class="img-fluid"></a>
			</div>
			<style type="text/css">
				.download_section{
					display: none; justify-content: flex-end; border-radius: 5px;
				}
				.left_button{
					border-top-left-radius: 5px; border-bottom-left-radius: 5px;
					cursor: pointer;
					padding-top: 2px;
					padding-bottom: 2px;
					font-size: 13px;
					color: #383232 !important;
				}
				.left_button:hover{
					border-top-left-radius: 5px; border-bottom-left-radius: 5px;
					background-color: #e1e1e1;
					padding-top: 2px;
					padding-bottom: 2px;
					font-size: 13px;
					color: #383232 !important;
				}
				.left_button:active{
					border-top-left-radius: 5px; border-bottom-left-radius: 5px;
					background-color: #cbcccc;
					padding-top: 2px;
					padding-bottom: 2px;
					font-size: 13px;
					color: #383232 !important;
				}
				.right_select, .right_select option{
					border-top-right-radius: 5px; border-bottom-right-radius: 5px;
					background-color: #e9ecef;
    				border: 1px solid #ced4da;
    				cursor: pointer;
    				outline: none;
    				padding-top: 2px;
					padding-bottom: 2px;
					font-size: 13px;
					color: #383232 !important;
				}
				.right_select:hover{
					border-top-right-radius: 5px; border-bottom-right-radius: 5px;
					background-color: #e1e1e1;
    				border: 1px solid #e1e1e1;
    				cursor: pointer;
    				padding-top: 2px;
					padding-bottom: 2px;
					font-size: 13px;
					color: #383232 !important;
				}
			</style>			
			<div id="excel-inventory" class="custom-card p-0">
				<div class="table-default table-responsive" style="max-width: 100%;">
					<table class="table-striped table-inventory">
						<thead><?php 

						$total_top_beginning="0";
						$total_top_sales="0";
						$total_top_stock_transfer_plus="0";
						$total_top_stock_transfer_minus="0";
						$total_top_interbranch_plus="0";
						$total_top_interbranch_minus="0";
						$total_top_pullout="0";
						$total_top_damage="0";
						$total_top_in_transit_plus="0";
						$total_top_in_transit_minus="0";
						$total_top_running="0";
						$total_top_physical_count="0";
						// $total_top_$variance="0";
						?>
							<tr class="row100 head">
								<th class="cell100 text-uppercase small column1">SKU</th>
								<th class="cell100 text-uppercase text-center small column3" nowrap>beginning<br/>inventory</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-daily" nowrap>daily<br/>sales</th>
								<?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="daily" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-change-order" nowrap>Stock<br/>Transfer (+)</th>
								<?php for ($x=0;$x<=$dayscount;$x++) { 
							// date('Y-m-d',strtotime($dateStartpdh .'+'.$x.' days')) ;
									?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="change-order" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-delivery" nowrap>Stock<br/>Transfer (-)</th>
								<?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="delivery" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-inc" nowrap>inter<br/>branch (+)</th>
								<?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="inter-inc" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-dec" nowrap>inter<br/>branch (-)</th>
								<?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="inter-dec" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-pullout" nowrap>pullout</th>
								<?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="pullout"><?= $x ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-damage" nowrap>damage</th>
								<?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="damage"><?= $x ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-transit" nowrap>in<br/>transit(+)</th>
								<?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="transit"><?= $x ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-transit" nowrap>in<br/>transit(-)</th>
								<?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="transit"><?= $x ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-running" nowrap>running<br/>inventory</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-physical" nowrap>physical<br/>count</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-variance" nowrap>variance</th>
							</tr>
						</thead>

						
						<tbody>
                        <?php 


$arrActualX3=array();
        for ($i=0;$i<sizeof($arrActualCount);$i++) {
            $arrActualX3[$i]=$arrActualCount[$i]["product_code"];
		}

		$arrActual=array();
		$arrActualX=array();
		for ($i=0;$i<sizeof($arrActualCount2);$i++) {
			$arrActualX[$i]=$arrActualCount2[$i]["product_code"];
			$arrActual[$arrActualCount2[$i]["product_code"]][$arrActualCount2[$i]["date_end"]]["product_code"]=$arrActualCount2[$i]["product_code"];
			$arrActual[$arrActualCount2[$i]["product_code"]][$arrActualCount2[$i]["date_end"]]["count"]=$arrActualCount2[$i]["count"];
			$arrActual[$arrActualCount2[$i]["product_code"]][$arrActualCount2[$i]["date_end"]]["date_end"]=$arrActualCount2[$i]["date_end"];
			$arrActual[$arrActualCount2[$i]["product_code"]][$arrActualCount2[$i]["date_end"]]["input_count"]=$arrActualCount2[$i]["input_count"];

		}
					
		
for ($i=0;$i<sizeof($arrPoll51_items);$i++) {
								
	if(isset($_GET['filterStores'])){

		$storelength=strlen($_GET['filterStores']);
	   if($_GET['filterStores']=='warehouse'){
		   $branch ='warehouse';
		   $FrameData[$arrPoll51_items[$i]["product_code"]]= WarehouseChecker_smr($arrPoll51_items[$i]["product_code"],$dateStart,$dateEnd);
	   }elseif($storelength=='3'){
		   $branch ='store';
		   $FrameData[$arrPoll51_items[$i]["product_code"]]=storeChecker_smr($arrPoll51_items[$i]["product_code"],$_GET['filterStores'],$dateStart,$dateEnd);
	   }
	   elseif($_GET['filterStores']=='1000'){
		   $branch ='store';
		   $FrameData[$arrPoll51_items[$i]["product_code"]]=storeChecker_smr($arrPoll51_items[$i]["product_code"],$_GET['filterStores'],$dateStart,$dateEnd);
	   }
	   elseif($storelength>'5'){
		   $branch ='lab';
			$FrameData[$arrPoll51_items[$i]["product_code"]]=labChecker_smr($arrPoll51_items[$i]["product_code"],$_GET['filterStores'],$dateStart,$dateEnd);
	   }else{
		   $branch ='warehouse';
		   $FrameData[$arrPoll51_items[$i]["product_code"]]= WarehouseChecker_smr($arrPoll51_items[$i]["product_code"],$dateStart,$dateEnd);
	   }
   }else{
	   $branch ='warehouse';
	   $FrameData[$arrPoll51_items[$i]["product_code"]] = WarehouseChecker_smr($arrPoll51_items[$i]["product_code"],$dateStart,$dateEnd);
   }
   		
								
								
								// echo "<pre>";
								// print_r($FrameData);
								// echo "</pre>";			
								



							// }  [store_name] => mtc-makati
							
							

							if(empty($FrameData[$arrPoll51_items[$i]["product_code"]])){

								$FrameData[$arrPoll51_items[$i]["product_code"]][0]['beg_inventory'] ='0';
								$FrameData[$arrPoll51_items[$i]["product_code"]][0]['pullout'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['stock_transfer_out'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['stock_transfer_in_c'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['stock_transfer_out_c'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['interbranch_out_c'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['interbranch_in_c'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['pullout_c'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage_c'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage_i'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['sales'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['number'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_out'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['requested'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_in'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_out_c'] ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['past_variance']  ='0';
							$FrameData[$arrPoll51_items[$i]["product_code"]][0]['sales_past']  ='0';
							}
						
								?>
							
							<tr class="row100 body">
									<th nowrap class="cell100 small column1 " sku_desc ="<?= $arrPoll51_items[$i]['product_style']. " ".$arrPoll51_items[$i]['product_color'] ?>">
										<?= $arrPoll51_items[$i]['product_style'] . " " . $arrPoll51_items[$i]['product_color'] ?>
										<p class="small text-secondary m-0"><?= $arrPoll51_items[$i]['product_code'] ?></p>
									</th>

									<td nowrap class="cell100 small text-center">
									<?php 
											//
											
											if(($FrameData[$arrPoll51_items[$i]["product_code"]][0]['beg_inventory'] =='0'  || $FrameData[$arrPoll51_items[$i]["product_code"]][0]['beg_inventory'] ==''  ) &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['pullout'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['stock_transfer_out'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['stock_transfer_in_c'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['stock_transfer_out_c'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['interbranch_out_c'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['interbranch_in_c'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['pullout_c'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage_c'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage_i'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['sales'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['number'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_out'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['requested'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_in'] =='0' &&
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_out_c'] =='0' 
										
											 ){
												// echo "aaaaaaaaaa";
											 	$beg_inventory= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"];

											}
											else{
										
											
									$beg_inventoryx =$FrameData[$arrPoll51_items[$i]["product_code"]][0]["beg_inventory"]
											-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["pullout"]
											-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["damage"]
											-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_out"]
											-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["sales_past"]
											-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["transit_out"];

	// echo "<br>";
											
											if($branch=='warehouse'){

											
													if(strpos($FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"],"-")){
														$beg_inventory=$beg_inventoryx-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"];
		
													}else{
															$beg_inventory=$beg_inventoryx+$FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"];
													}
												}else{

											

											// 	}
											// if($beg_inventoryx<'0'){
											// 	// echo "aaaaaa";
											// 		$beg_inventory=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"];
											// }else{
											// 	echo "dddd";
										if(strpos($FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"],"-")){
											
														$beg_inventory=$beg_inventoryx-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"];
													
													}else{
														//
														// echo "cccc"; 
													// 	// $beg_inventory=$beg_inventoryx+$FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"];

													// 	echo $beg_inventoryx."<br>";
													// 	echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"]."<br>";
													// echo 	$varriable  = $beg_inventoryx - $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"];
													// echo "<br>";

													// 	if( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"]=='0'){
													// 		echo "xx";
													// 		$beg_inventory= $beg_inventoryx;
													// 	}elseif($FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"]>'0' && $varriable!='0' ){
													// 		echo "nn";
													// 	echo	$beg_inventory=$beg_inventoryx - $varriable; 
													// 	}elseif($FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"]>'0' ){
													// 		echo "vv";
													// 		$beg_inventory= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"] ;
													// 	}else{
													// 		echo "zz";
													// 		$beg_inventory= $beg_inventoryx - $varriable;
													// 	}




														if(  ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] >=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_status_date"] 
																 &&  !empty($FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_status_date"] )  && empty($FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_past_date"]  )  )
															
														){
															
													
                                                            $beg_inventory= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"] - $FrameData[$arrPoll51_items[$i]["product_code"]][0]["sales_deduct_physical"];
                                                            

														}
														elseif($FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] >=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_past_date"] 
														&&  !empty($FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_past_date"]) && empty($FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_status_date"] ) ){


															$beg_inventory= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"] - $FrameData[$arrPoll51_items[$i]["product_code"]][0]["sales_deduct_physical"];
														}
														
														elseif( ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_status_date"]) 
                                                                  || ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["damage_past_date"]) 
																|| ($FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"]!=''  &&  empty($FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_status_date"]))
																|| ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_minus_date"]) 
																|| ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_past_date"])
																||  ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_past_date"])
																){
																
                                                                    if( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_status_date"] || 
                                                                    ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"]!=''  &&  empty($FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_status_date"])) ) {
																		
                                                                         $stok_transfer_beg=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_in_past"];
                                                                    }else{
                                                                        $stok_transfer_beg="0";

																	}
																	


																	if( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_past_date"] || 
                                                                    ( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"]!=''  &&  empty($FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_past_date"])) ) {
																		
                                                                         $interbranch_in_past=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_past"];
                                                                    }else{
                                                                        $interbranch_in_past="0";

																	}
																	


                                                                    if( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["damage_past_date"]) {
                                                                            $damage_beg =$FrameData[$arrPoll51_items[$i]["product_code"]][0]["damage"];
                                                                    }else{
                                                                        $damage_beg ="0";
																	}

																	if( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <= $FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_past_date"]) {
																		$past_interbranch =$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_past"];
																}else{
																	$past_interbranch ="0";
																}
																
																	
																	if(( $FrameData[$arrPoll51_items[$i]["product_code"]][0]["audit_date"] <=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_minus_date"]) ){
																		$stock_transfer_beg_minus =$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_minus"];

																	}else{
																		$stock_transfer_beg_minus = "0";
																	}

                                                                 


                                                                     
															// echo "bbb";
															$beg_inventory=  $FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance_2"]+ $stok_transfer_beg
															+$interbranch_in_past
                                                            - $FrameData[$arrPoll51_items[$i]["product_code"]][0]["sales_deduct_physical"]
															- $damage_beg
															-$stock_transfer_beg_minus
															-$past_interbranch;
                                                        
                                                        }else{
															echo "aaa";
																$beg_inventory=$beg_inventoryx;
															}
													}
													// }	
												}		
											}
											$total_top_beginning+=$beg_inventory;
											echo 	$beg_inventory;
											
										
                                       
									?>
									</td>
									<td nowrap class="cell100 small text-center daily_sales_value"><?php 
									// if( in_array($arrFrames[$i]['product_code'],$arrXSales	) ){
                                    //     if($arrFrames[$i]['product_code']==$arrSalesDay[$arrFrames[$i]['product_code']]["product_code"]){
                                    //         // echo "a";
                                    //         $sale_frame =count($arrXSalesDateCount[$arrFrames[$i]['product_code']]);
                                    //         $tot_sale[$arrFrames[$i]['product_code']]=count($arrXSalesDateCount[$arrFrames[$i]['product_code']]);
                                    //     }else{
                                    //         $tot_sale[$arrFrames[$i]['product_code']] ="0";
                                    //         // echo "b";
                                    //         $sale_frame="0";
                                    //     }
                                    

                                    // }else{
                                    //     $tot_sale[$arrFrames[$i]['product_code']] ="0";
                                    //     // echo "c";
                                    //     $sale_frame ="0";
                                    // }

                // echo $arrSalesDay[$arrFrames[$i]['product_code']]["total_frame"];
                                // if($arrFrames[$i]['product_code']==$arrSalesDay[$arrFrames[$i]['product_code']]["product_code"]){
                                // 	$sale_frame=$sales_day;
                                // }else{
                                // 	$sale_frame="0";
								// }
								$total_top_sales+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["sales"];
                                echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["sales"];
								      
									?>
                                    
                                    </td>
                                    <?php
									// for($xss=0;$xss<=$dayscount;$xss++) {

									// 	$stock["sales_per_day"][$xss] =date('Y-m-d',strtotime($dateStartpdh .'+'.$xss.' days')) ;
									// 	if( in_array($arrFrames[$i]['product_code'],$arrXSales	) && in_array($stock["sales_per_day"][$xss],$arrXSalesDate) ){
									// 	if($arrFrames[$i]['product_code']==$arrSalesDay[$arrFrames[$i]['product_code']][$stock["sales_per_day"][$xss]]["product_code"]
									// 		&& $stock["sales_per_day"][$xss] ==$arrSalesDay[$arrFrames[$i]['product_code']][$stock["sales_per_day"][$xss]]["status_date"]
									// 	){
									// 		$sale_frame_day=$arrSalesDay[$arrFrames[$i]['product_code']][$stock["sales_per_day"][$xss]]["sales_count"];
									// 	}else{
									// 		$sale_frame_day="0";
									// 	} 
									// }else{
									// 	$sale_frame_day="0";
									// }
										?>
										<td nowrap class="cell100 small text-center d-none" data-col="daily"><?php // echo $sale_frame_day ?></td>
                                    <?php  //} 
                                    
                                    ?>

									<td nowrap class="cell100 small text-center stock_transfer_plus_value"><?php 
									
										// $total_transfer_plus =GetTotalStockTransferP($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
										// echo $total_transfer_plus;

										$total_top_stock_transfer_plus+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_in_c"];

								echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_in_c"];
								
									?></td>


									<?php//  for ($x=0;$x<=$dayscount;$x++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="change-order">
										<?php
										// $plusDay[$x] = '"+'.$x.' days"';
										// //// perday stock_indate('Y-m')
										// $stock_in_date["stock_transfer_in"] =date('Y-m-d',strtotime($dateStartpdh .'+'.$x.' days')) ;
										// //$datecurrentH ."-".$x;
										

										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 			if($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["status_date"]==$stock_in_date["stock_transfer_in"] 
										// 			&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["type"]=='stock_transfer'
										// 				|| $arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["type"]=='replenish')
										// 			&& 	($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["store_id"]==$store_id
										// 			&& $arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["status"]=='received'
															
										// 				) ){
										// 				echo	$stock_in_per_day=$arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["total"];
										// 			}else{
										// 				echo	$stock_in_per_day="0";
										// 			}
										// 		}else{
										// 			echo	$stock_in_per_day="0";
										// 		}
											?></td>
									<?php
									//} ?>
									<td nowrap class="cell100 small text-center stock_transfer_minus_value"><?php 
									
										// $total_transfer_minus= GetTotalStockTransferM($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
										// echo $total_transfer_minus;
										$total_top_stock_transfer_minus+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_out_c"];
										echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_out_c"];
										
									?></td>
									<?php // for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="delivery">
										<?php	 
										
										// 	//// perday stock_transfer_out
										// $stock_out_date["stock_transfer_out"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
									
										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 		if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["status_date"]==$stock_out_date["stock_transfer_out"] 
										// 		&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["type"]=='stock_transfer' 
										// 				)
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["stock_from"]==$store_id
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["status"]=='received')
										// 		{
										// 			echo 	$stock_out_per_day=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["total"];
										// 		}else{
										// 			echo $stock_out_per_day="0";
										// 		}
										// 	}else{
										// 		echo $stock_out_per_day="0";
										// 	}		
											?></td>
									<?php  //} ?>
									<td nowrap class="cell100 small text-center inter_branch_plus_value"><?php
										$total_top_interbranch_plus+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_c"];
										echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_c"];
										
										?>
										</td>
										<?php  // for ($x=0;$x<=$dayscount;$x++) { 
										
										
									
										?>
										<td nowrap class="cell100 small text-center d-none" data-col="inter-inc">
										<?php
										// $plusDay[$x] = '"+'.$x.' days"';
										// //// perday stock_indate('Y-m')
										// $stock_in_date["interbranch_in"] =date('Y-m-d',strtotime($dateStartpdh .'+'.$x.' days')) ;
										// //$datecurrentH ."-".$x;
										

										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 		if($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["interbranch_in"]]["interbranch"]["status_date"]==$stock_in_date["interbranch_in"] 
										// 		&&  $arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["interbranch_in"]]["interbranch"]["type"]=='interbranch'
													
										// 		&& 	($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["interbranch_in"]]["interbranch"]["store_id"]==$store_id
														
										// 			)
										// 			&& $arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["interbranch_in"]]["interbranch"]["status"]=='received'
										// 			){
										// 			echo	$interbranch_in_perday=$arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["interbranch_in"]]["interbranch"]["total"];
										// 		}else{
										// 			echo	$interbranch_in_perday="0";
										// 		}
										// 	}else{
										// 		echo	$interbranch_in_perday="0";
										// 	}
											?></td>
									<?php
								//	} ?>
									<td nowrap class="cell100 small text-center inter_branch_minus_value"><?php
										$total_top_interbranch_minus+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_c"];
										echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_c"];
										
										?></td>
									<?php // for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="inter-dec">
										<?php	 
										
											//// perday intebrnach_out
										// $stock_out_date["interbranch_out"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
									
										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 		if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["interbranch_out"]]["interbranch"]["status_date"]==$stock_out_date["interbranch_out"] 
										// 		&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["interbranch_out"]]["interbranch"]["type"]=='interbranch' 
										// 				)
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["interbranch_out"]]["interbranch"]["stock_from"]==$store_id
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["interbranch_out"]]["interbranch"]["status"]=='received'
										// 		){
										// 			echo 	$stock_out_per_day=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["interbranch_out"]]["interbranch"]["total"];
										// 		}else{
										// 			echo $stock_out_per_day="0";
										// 		}
										// 	}else{
										// 		echo $stock_out_per_day="0";
										// 	}
											?></td>
									<?php //} ?>


									<td nowrap class="cell100 small text-center pullout_value"><?php 
										$total_top_pullout+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]['pullout_c'];
									echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]['pullout_c']; ?></td>
									<?php //for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="pullout">
										<?php	 
										
										// 	//// perday stock_transfer_out
										// $stock_out_date["pullout"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
									
										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 		if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["status_date"]==$stock_out_date["pullout"] 
										// 		&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["type"]=='pullout' 
										// 				)
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["stock_from"]==$store_id
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["status"]=='received' ){
										// 			echo 	$pulloutday=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["total"];
										// 		}else{
										// 			echo $pulloutday="0";
										// 		}
										// 	}else{
										// 		echo $pulloutday="0";
										// 	}

											?></td>
									<?php //} ?>
									<td nowrap class="cell100 small text-center damage_value"><?php 
										$total_top_damage+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage_c'];
									echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage_c'] ?></td>
									<?php 
								
									// for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="damage">
										<?php	 
										
											//// perday stock_transfer_out
										// $stock_out_date["damage"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
									
										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 		if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["status_date"]==$stock_out_date["damage"] 
										// 		&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["type"]=='damage' 
										// 				)
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["stock_from"]==$store_id
										// 		&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["status"]=='received'){
										// 			echo 	$damage=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["total"];
										// 		}else{
										// 			echo $damage="0";
										// 		}
										// 	}else{
										// 		echo $damage="0";
										// 	}
											?></td>
									<?php // } ?>
									<td nowrap class="cell100 small text-center in_transit_value"><?php 
									
										$total_top_in_transit_plus+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_in'];
                                        echo	$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_in'];
										
									?></td>
									<?php //for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="transit">
										<?php	 
										
											//// perday stock_transfer_out
										// $stock_out_date["transit"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
									
										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 			if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["status_date"]==$stock_out_date["transit"] 
													
										// 			&& ($arrframeday[$arrFrames[$i]['product_code']]["interbranch"]["store_id"]==$store_id
										// 					|| $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["stock_from"]==$store_id) 
										// 			&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["status"]=='in transit'){
										// 				echo 	$transit=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["total"];
										// 			}else{
										// 				echo $transit="0";
										// 			}
										// 	}else{
										// 		echo $transit="0";
										// 	}
											?></td>
									<?php //} ?>
									<td nowrap class="cell100 small text-center in_transit_value"><?php 
									
									
										$total_top_in_transit_minus+=$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_out_c'];
                                        echo	$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_out_c'];
										
									?></td>
									<?php //for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="transit">
										<?php	 
										
											//// perday stock_transfer_out
										// $stock_out_date["transit"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
									
										// if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
										// 			if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["status_date"]==$stock_out_date["transit"] 
													
										// 			&& ($arrframeday[$arrFrames[$i]['product_code']]["interbranch"]["store_id"]==$store_id
										// 					|| $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["stock_from"]==$store_id) 
										// 			&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["status"]=='in transit'){
										// 				echo 	$transit=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["transit"]]["in_transit"]["total"];
										// 			}else{
										// 				echo $transit="0";
										// 			}
										// 	}else{
										// 		echo $transit="0";
										// 	}
											?></td>
									<?php //} ?>
									<td nowrap class="cell100 small text-center running_inventory_value"><?php
									
											$runningtotal=  $beg_inventory +$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_in_c"]
											+$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_c"]- $FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_out_c"]-
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_c"]-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["damage_c"]-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["pullout_c"]-$FrameData[$arrPoll51_items[$i]["product_code"]][0]['sales']; 
											// -$sale_frame;
											$total_top_running+= $runningtotal;
											echo $runningtotal;

											
									
									?></td>
									<?php for ($x=1;$x<=$dayscount;$x++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="running">0</td>
									<?php } ?>
									<td nowrap class="cell100 small text-center physical_count_value">
									<?php 
									 if(in_array($arrPoll51_items[$i]['product_code'],$arrActualX )){
									
										if($arrActual[$arrPoll51_items[$i]['product_code']][$dateEndpdh]["date_end"]==$dateEndpdh 
										&& $arrPoll51_items[$i]['product_code']==$arrActual[$arrPoll51_items[$i]['product_code']][$dateEndpdh]["product_code"]){
											$compute ="y";
													$actual=$arrActual[$arrPoll51_items[$i]['product_code']][$dateEndpdh]["input_count"];
										}else{
											$compute ="n";
												$actual="0";
										}
									}else{
										$compute ="n";
										$actual="0";
								}
								$total_top_physical_count+=$actual;
									?>
									<?= $actual ?>
									</td>
									<?php for ($x=1;$x<=$dayscount;$x++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="physical">0</td>
									<?php } ?>
									<td nowrap class="cell100 small text-center variance_value">
									<?php  if($compute =="y"){
													$variance =$actual-$runningtotal;
									}else{
											$variance ="0";
									} 

									
									echo $variance;
									?>
									
									</td>
									<?php for ($x=1;$x<=$dayscount;$x++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="variance">0</td>
									<?php } ?>
								</tr>

							<?php   } ?>

						</tbody>
						<tfoot >
						<tr class="row100 foot footerc" >
								<th class="cell100 text-uppercase small column1   text-white" style="padding: 20px 15px;">Total</th>
								<th class="cell100 text-uppercase text-center small column3  text-white" style="padding: 20px 15px;" nowrap><?php echo  $total_top_beginning; ?></th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-daily" nowrap><?= $total_top_sales ?></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-change-order" nowrap><?= $total_top_stock_transfer_plus ?></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"   style="padding: 20px 15px;"  id="col-delivery" nowrap><?= $total_top_stock_transfer_minus ?></th>
							
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"    style="padding: 20px 15px;"  id="col-inter-inc" nowrap><?= $total_top_interbranch_plus ?></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;"  id="col-inter-dec" nowrap><?= $total_top_interbranch_minus ?></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-pullout" nowrap><?= $total_top_pullout ?></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-damage" nowrap><?= $total_top_damage ?></th>
							
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;" id="col-transit" nowrap><?= $total_top_in_transit_plus ?></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-transit" nowrap><?= $total_top_in_transit_minus ?></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-running" nowrap><?= $total_top_running ?></th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-physical" nowrap><?= $total_top_physical_count ?></th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-variance" nowrap>-</th>
							</tr>
						</tfoot>
					</table>
				</div>

			</div>

			<hr class="spacing">

			<div class="custom-card row flex-column flex-md-row no-gutters align-items-md-center justify-content-md-between">
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<p class="h3 font-bold">Download Running Inventory</p>
							<p class="text-secondary mt-1">Click the button to download the current running inventory of the branch selected.</p>
						</section>
					</div>
				</div>
				<div class="col-12 col-md-auto">
					<div class="d-flex align-items-center">
						<section>
							<div class="download_section">
								<div class="input-group-prepend" id="downloading_img">
							    	<img src="/images/downloading.gif" width="30px" height="30px">
							  	</div>
								<div class="dl_btn input-group-prepend">
							    	<span class="btn btn-primary text-white input-group-text" id="btn_download">Download</span>
							  	</div>
							  	<div class="dl_btn input-group-prepend" style="display: none;">
							    	<select class="btn select right_select">
							    		<option value="running">RUNNING</option>
							    		<!-- <option value="all">ALL</option> -->
							    	</select>
							  	</div>

							</div>
						</section>
					</div>
				</div>
			</div>

		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>

<script>

	$(document).ready(function() {

		var allStores = false;
		var allLabs = false;

		$('#optionAllStores').click(function() {
			if(allStores) {
				$('.store-form').find('input').attr('checked', false);
				allStores = false;
			}
			else {
				$('.store-form').find('input').attr('checked', true);
				allStores = true;
			};
		});

		$('#optionAllLabs').click(function() {
			if(allLabs) {
				$('.lab-form').find('input').attr('checked', false);
				allLabs = false;
			}
			else {
				$('.lab-form').find('input').attr('checked', true);
				allLabs = true;
			};
		});

		var customSwitch = false;

		$('input[type=radio][name=date]').change(function() {
			var thisID = $(this).attr('id');

			if(thisID != 'optionCustom') {
				$('#data_range_start_month').prop('disabled', true);
				$('#data_range_start_day').prop('disabled', true);
				$('#data_range_start_year').prop('disabled', true);
				$('#data_range_end_month').prop('disabled', true);
				$('#data_range_end_day').prop('disabled', true);
				$('#data_range_end_year').prop('disabled', true);
				customSwitch = false;						
			} else {
				if(customSwitch) {
					$('#data_range_start_month').prop('disabled', true);
					$('#data_range_start_day').prop('disabled', true);
					$('#data_range_start_year').prop('disabled', true);
					$('#data_range_end_month').prop('disabled', true);
					$('#data_range_end_day').prop('disabled', true);
					$('#data_range_end_year').prop('disabled', true);
					customSwitch = false;
				} else {
					$('#data_range_start_month').prop('disabled', false);
					$('#data_range_start_day').prop('disabled', false);
					$('#data_range_start_year').prop('disabled', false);
					$('#data_range_end_month').prop('disabled', false);
					$('#data_range_end_day').prop('disabled', false);
					$('#data_range_end_year').prop('disabled', false);
					customSwitch = true;
				}
			};

		});

		$('#data_range_start_month').change(function() {
			var thisMonth = $(this).val();
			$('#data_range_start_day').load('../includes/date_select_dropdown.php?month=' + thisMonth);
		});

		$('#data_range_end_month').change(function() {
			var thisMonth = $(this).val();
			$('#data_range_end_day').load('../includes/date_select_dropdown.php?month=' + thisMonth);
		});


		if ( $('.table100-nextcols tbody tr').length > 10 ) {

			$('.wrap-table100').addClass('scroll');

		};
		
		//////////////// TOGGLE COLUMN

		// $('.toggle-column').on('click', function() {
		// 	var col = $(this).attr('id').replace('col-','');
		// 	$('[data-col='+col+']').toggleClass('d-none');
		// });

		//DOWNLOAD CSV EXCEL
		let arrHeader = [];
		let arrData = [];
		$(".download_section").css("display", "flex");
		$(".left_button").attr("title", "Download " + $(".right_select").val().toUpperCase()+" csv file");
		 $(".right_select").change(function(){
		 	$(".left_button").attr("title", "Download " + $(this).val().toUpperCase()+" csv file");
		 });
		$("#downloading_img").hide();
		$("#btn_download").click(function(){
			$("#downloading_img").show();
			if($(".right_select").val() == 'all'){
				arrHeader = [];
				arrData = [];
				getDataHeaderAll();
				getDataToDownloadAll();
			}else{
				arrHeader = [];
				arrData = [];
				getDataHeaderRunning();
				getDataToDownloadRunning();
			}
			
			$.post("/process/inventory/admin/download_warehouse.php",
            {data:JSON.stringify(arrData),header:JSON.stringify(arrHeader),
            content :$(".right_select").val(), store:$("#store_str").text()},
            function(response){
                window.open('/downloads/'+response.filename);
                setTimeout(function(){
                    $.post('/process/inventory/admin/download_warehouse.php',
                    {filename:response.filename});
                },500);
                $("#downloading_img").hide();
            },'JSON');
			
					
		});

		function getDataHeaderRunning(){
			$(".table-inventory thead tr").each(function(){
				let arrThData = [];
				let thLength = $(this).find("th").length;
				for(let i = 0;  i < thLength ; i++){
					let className = $(this).find("th:eq("+i+")").attr("class");
					(className.indexOf("d-none") > -1) ? '' : arrThData.push($.trim($(this).find("th:eq("+i+")").html())); 
				}
				arrHeader.push({
					sku_desc : arrThData[0] +' description',
					header_product_code : 'product code',
					header_running_inventory : "running inventory",
				})
			});
		}
		function getDataToDownloadRunning(){
			$(".table-inventory tbody tr").each(function(){
				let sku_desc = $.trim($(this).find("th").attr('sku_desc'));
				let sku_code = $.trim($(this).find("p").text());
				
				arrData.push({
					sku_desc: sku_desc,
					sku_code : sku_code,
					get_running_inventory : $.trim($(this).find(".running_inventory_value").text())
				})
			});
		}

		function getDataHeaderAll(){
			$(".table-inventory thead tr").each(function(){
				let arrThData = [];
				let thLength = $(this).find("th").length;
				for(let i = 0;  i < thLength ; i++){
					let className = $(this).find("th:eq("+i+")").attr("class");
					(className.indexOf("d-none") > -1) ? '' : arrThData.push($.trim($(this).find("th:eq("+i+")").html())); 
				}
				arrHeader.push({
					sku_desc : arrThData[0]+' description',
					header_product_code : 'product code',
					header_beginning_inventory : arrThData[1],
					header_daily_sales : arrThData[2],
					header_stock_transfer_plus : arrThData[3],
					header_stock_transfer_minus : arrThData[4],
					header_inter_branch_plus : arrThData[5],
					header_inter_branch_minus : arrThData[6],
					header_pullout : arrThData[7],
					header_damage : arrThData[8],
					header_in_transit : arrThData[9],
					header_running_inventory : arrThData[10],
					header_physical_count : arrThData[11],
					header_variance : arrThData[12]
				})
			});
		}
		function getDataToDownloadAll(){
			$(".table-inventory tbody tr").each(function(){
				let arrTdData = [];
				let sku_desc = $.trim($(this).find("th").attr('sku_desc'));
				let sku_code = $.trim($(this).find("p").text());

				arrData.push({
					sku_desc: sku_desc,
					sku_code : sku_code,
					get_beginning_inventory : $.trim($(this).find(".beginning_inventory_value").text()),
					get_daily_sales : $.trim($(this).find(".daily_sales_value").text()),
					get_stock_transfer_plus : $.trim($(this).find(".stock_transfer_plus_value").text()),
					get_stock_transfer_minus : $.trim($(this).find(".stock_transfer_minus_value").text()),
					get_inter_branch_plus : $.trim($(this).find(".inter_branch_plus_value").text()),
					get_inter_branch_minus : $.trim($(this).find(".inter_branch_minus_value").text()),
					get_pullout : $.trim($(this).find(".pullout_value").text()),
					get_damage : $.trim($(this).find(".damage_value").text()),
					get_in_transit : $.trim($(this).find(".in_transit_value").text()),
					get_running_inventory : $.trim($(this).find(".running_inventory_value").text()),
					get_physical_count : $.trim($(this).find(".physical_count_value").text()),
					get_variance : $.trim($(this).find(".variance_value").text())
				})
			});
		}

	})

</script>

<?= get_footer() ?>