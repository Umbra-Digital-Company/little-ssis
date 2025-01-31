<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'lab';
$page_url = 'stock-movement';

$filter_page = 'stock_movement_lab_studios';
$group_name = 'aim_studios';

// if($_SESSION['user_login']['userlvl'] !== '3' || $_SESSION['user_login']['position'] !== 'laboratory') {

//     header('location: /');
//     exit;
    
//     };
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

error_reporting(0);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/dashboard/functions.php";
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_stores_studios.php";
	
require $sDocRoot."/inventory/includes/grab_poll_51_studios.php";
// require $sDocRoot."/inventory/includes/w_admin_function.php";
require $sDocRoot."/inventory/includes/w_admin_functionv10.php";

// require $sDocRoot."/inventory/includes/grab_inventory_products_v2.php";
// require $sDocRoot."/inventory/includes/grab_inventory_products_pd.php";
require $sDocRoot."/inventory/includes/inventory_functions.php";
require $sDocRoot."/inventory/includes/grab_inventory_sales_lab.php";


// require $sDocRoot."/inventory/includes/grab_inventory_products_pd.php";
// require $sDocRoot."/inventory/includes/grab_inventory_sales.php";
// require $sDocRoot."/inventory/includes/grab_inventory_sales_v3.php";



// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v2.php";
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
					<p class="h2 ml-3">Activity Filter</p>
				</div>
				<form method="GET" class="filter-body">
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
										<input class="sr-only checkbox" name="date" type="radio" id="optionDay" value="day" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "day" ) ? 'checked="checked"' : '' ?>>
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
										<input class="sr-only checkbox" name="date" type="radio" id="optionMonth" value="month" <?= ( isset( $_GET["date"] ) && $_GET["date"] == "month" || !isset($_GET["date"]) ) ? 'checked="checked"' : '' ?>>
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

							<a href="/inventory/lab/stock-movement/" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline d-lg-none">
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
                        <p class="h3 font-bold">Showing Data</p>
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

			<style>
				#btn_search{
					border-bottom-right-radius: 10px;
					border-top-right-radius: 10px;
					color: #fff;
					background-color: #36482e;
					border: none;
				}
				.table-inventory tbody tr td{
					cursor: pointer;
				}
			</style>
			<div class ="col-md-3 col-xs-12 input-group" style="padding-left: 0px;">
				<input type="text" name="sku_search" class="form-control" id="sku_search"/>
				<div class="input-group-prepend">
			    	<span class="input-group-text" id="btn_search">Search</span>
			  	</div>
			</div><br>

			<div id="excel-inventory" class="custom-card p-0">

				<div class="table-default table-responsive" style="max-width: 100%;">
					<table class="table-striped table-inventory">
						<thead>
							<tr class="row100 head">
								<th class="cell100 text-uppercase small column1">SKU</th>
								<th class="cell100 text-uppercase text-center small column3" nowrap>beginning<br/>inventory</th>
                                <?php for ($i=0;$i<sizeof($arrStore);$i++){?>
								
                                <th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-daily-<?= $i ?>" nowrap style="background:#4f6e42;"><?= $arrStore[$i]["store_name"] ?> <br/>daily sales</th>
                            
                            <?php } ?>
								<!-- <?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="daily" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-change-order" nowrap>Stock<br/>Transfer (+)</th>
								<!-- <?php for ($x=0;$x<=$dayscount;$x++) { 
							// date('Y-m-d',strtotime($dateStartpdh .'+'.$x.' days')) ;
									?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="change-order" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-delivery" nowrap>Stock<br/>Transfer (-)</th>
								<!-- <?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="delivery" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-inc" nowrap>inter<br/>branch (+)</th>
								<!-- <?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="inter-inc" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-dec" nowrap>inter<br/>branch (-)</th>
								<!-- <?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="inter-dec" style="background:#4f6e42 !important;"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-pullout" nowrap>pullout</th>
								<!-- <?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="pullout"><?= $x ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-damage" nowrap>damage</th>
								<!-- <?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="damage"><?= $x ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-transit" nowrap>in<br/>transit(+)</th>
								<!-- <?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="transit"><?= $x ?></th>
								<?php } ?> -->
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-transit" nowrap>in<br/>transit(-)</th>
								<!-- <?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="transit"><?= $x ?></th>
								<?php } ?> -->
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
					
		
$arrSalesDay =array();
$arrXSales=array();
$arrXSalesDate=array();
$arrXSalesStore = array();

for ($i=0;$i<sizeof($arrDailySales);$i++) {
    $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]]["total_frame"] ="0";
}


for ($i=0;$i<sizeof($arrDailySales);$i++) {
    $arrXSales[$i] =$arrDailySales[$i]["product_code"];
    $arrXSalesStore[$arrDailySales[$i]["product_code"]][$i] = $arrDailySales[$i]["store_id"];
    $arrXSalesDate[$arrDailySales[$i]["product_code"]][$i]=$arrDailySales[$i]["payment_date"];

        //   $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]]["total_frame"] ="0";
          $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]][$arrDailySales[$i]["payment_date"]]["sales_count"]="0"; 

        $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]][$arrDailySales[$i]["payment_date"]]["product_code"] =$arrDailySales[$i]["product_code"];
        $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]][$arrDailySales[$i]["payment_date"]]["status_date"] = $arrDailySales[$i]["payment_date"];
        $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]][$arrDailySales[$i]["payment_date"]]["sales_count"] += $arrDailySales[$i]["sales"];
        $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]]["total_frame"] += $arrDailySales[$i]["sales"];
        $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]]["product_code"] =$arrDailySales[$i]["product_code"];
        $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]]["origin_branch"] =$arrDailySales[$i]["origin_branch"];
        $arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["store_id"]]["store_id"] =$arrDailySales[$i]["store_id"];

}
$arrSalesDay2=array();
$arrSalesDay2X=array();
$arrSalesDay2XCount=array();
for ($i=0;$i<sizeof($arrDailySalespast);$i++) {
    $arrSalesDay2[$arrDailySalespast[$i]["product_code"]][$arrDailySalespast[$i]["payment_date"]]["sales_count"] ="0";
    $arrSalesDay2[$arrDailySalespast[$i]["product_code"]]["total_frame"] ="0";
    $arrSalesDay2X[$i] =$arrDailySalespast[$i]["product_code"];
    $arrSalesDay2XCount[$arrDailySalespast[$i]["product_code"]][$i] =$arrDailySalespast[$i]["payment_date"];
   

    $arrSalesDay2[$arrDailySalespast[$i]["product_code"]][$arrDailySalespast[$i]["payment_date"]]["product_code"] =$arrDailySalespast[$i]["product_code"];
    $arrSalesDay2[$arrDailySalespast[$i]["product_code"]][$arrDailySalespast[$i]["payment_date"]]["status_date"] = $arrDailySalespast[$i]["payment_date"];
    $arrSalesDay2[$arrDailySalespast[$i]["product_code"]][$arrDailySalespast[$i]["payment_date"]]["sales_count"] += $arrDailySalespast[$i]["sales"];
    $arrSalesDay2[$arrDailySalespast[$i]["product_code"]]["total_frame"] += $arrDailySalespast[$i]["sales"];
    $arrSalesDay2[$arrDailySalespast[$i]["product_code"]]["product_code"] =$arrDailySalespast[$i]["product_code"];
}



for ($i=0;$i<sizeof($arrPoll51_items);$i++) {
								
								
										$branch ='lab';
										 $FrameData[$arrPoll51_items[$i]["product_code"]]=labChecker_smr($arrPoll51_items[$i]["product_code"],$_SESSION['user_login']['store_code'],$dateStart,$dateEnd);
									
							
								
								
										
								

								

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

							// if($arrPoll51_items[$i]['product_code']=='SO1001'){
							// 	echo "<pre>";
							// print_r($FrameData);
							// echo "</pre>";	
							// exit;
							// }
									
						
								?>
							
							<tr class="row100 body">
									<th nowrap class="cell100 small column1" sku_desc="<?= $arrPoll51_items[$i]['product_style'] . " " . $arrPoll51_items[$i]['product_color'] ?>">
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

											

										
										if(strpos($FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"],"-")){
											
														$beg_inventory=$beg_inventoryx-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["past_variance"];
													
													}else{
													




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

											echo 	$beg_inventory;
											
										
                                       
									?>
									</td>
                         <?php for ($ix=0;$ix<sizeof($arrStore);$ix++){
							
							 ?>

                        <td nowrap class="cell100 small text-center">
                            <?php 
                             if($arrStore[$ix]["store_id"]=='787'){
								$sale_frame="0";
							}
										else{
												if( in_array($arrPoll51_items[$i]["product_code"],$arrXSales) && in_array($arrStore[$ix]["store_id"],$arrXSalesStore[$arrPoll51_items[$i]["product_code"]]) ){
													
															if($arrPoll51_items[$i]["product_code"]==$arrSalesDay[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]]["product_code"] 
															&& $arrSalesDay[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]]["store_id"] == $arrStore[$ix]["store_id"] ){
															$tot_sale[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]] ="0";
														
																$sale_frame=$arrSalesDay[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]]["total_frame"];
																$tot_sale[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]] +=$arrSalesDay[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]]["total_frame"];
															}else{
															
																	$tot_sale[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]] ="0";
																$sale_frame="0";
															}
										}else{
										
												$tot_sale[$arrPoll51_items[$i]["product_code"]][$arrStore[$ix]["store_id"]] ="0";
											$sale_frame="0";
										}
							}
                                $final_tot[$arrPoll51_items[$i]["product_code"]]=array_sum( $tot_sale[$arrPoll51_items[$i]["product_code"]]);
                                echo $sale_frame;
                            
                            ?>
                            
                        </td>
                      <?php   } ?>

									<td nowrap class="cell100 small text-center"><?php 
									
										// $total_transfer_plus =GetTotalStockTransferP($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
										// echo $total_transfer_plus;
								echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_in_c"];
								
									?></td>

									<td nowrap class="cell100 small text-center"><?php 
									
										// $total_transfer_minus= GetTotalStockTransferM($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
										// echo $total_transfer_minus;

										echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_out_c"];
										
									?></td>
									
									<td nowrap class="cell100 small text-center"><?php
										echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_c"];
										
										?>
										
									<td nowrap class="cell100 small text-center"><?php
										echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_c"];
										
										?></td>

									<td nowrap class="cell100 small text-center"><?php echo $FrameData[$arrPoll51_items[$i]["product_code"]][0]['pullout_c']; ?></td>
									
									<td nowrap class="cell100 small text-center"><?= $FrameData[$arrPoll51_items[$i]["product_code"]][0]['damage_c'] ?></td>
									
									<td nowrap class="cell100 small text-center"><?php 
									
										// $total_in_transit= GetTotalOnTransit($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
                                        // echo $total_in_transit;
                                        echo	$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_in'];
										
									?></td>
									
									<td nowrap class="cell100 small text-center"><?php 
									
										// $total_in_transit= GetTotalOnTransit($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
                                        // echo $total_in_transit;
                                        echo	$FrameData[$arrPoll51_items[$i]["product_code"]][0]['transit_out_c'];
										
									?></td>
									
									<td nowrap class="cell100 small text-center"><?php
									
											$runningtotal=  $beg_inventory +$FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_in_c"]
											+$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_in_c"]- $FrameData[$arrPoll51_items[$i]["product_code"]][0]["stock_transfer_out_c"]-
											$FrameData[$arrPoll51_items[$i]["product_code"]][0]["interbranch_out_c"]-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["damage_c"]-$FrameData[$arrPoll51_items[$i]["product_code"]][0]["pullout_c"]-$FrameData[$arrPoll51_items[$i]["product_code"]][0]['sales']; 
											// -$sale_frame;
											echo $runningtotal;

											
									
									?></td>
									
									<td nowrap class="cell100 small text-center">
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
							
									?>
									<?= $actual ?>
									</td>
									
									<td nowrap class="cell100 small text-center">
									<?php  if($compute=='y'){
									
													$variance =$actual-$runningtotal;
									}else{
									
											$variance ="0";
									} 
									echo $variance;
									?>
									
									</td>
									
								</tr>

							<?php  
							// exit;
							 } ?>

						</tbody>
						<tfoot >
							<tr class="row100 foot footerc" >
								<th class="cell100 text-uppercase small column1   text-white" style="padding: 20px 15px;">Total</th>
								<?php for ($i=0;$i<sizeof($arrStore);$i++){?>
									<th class="cell100 text-uppercase text-center small column3  text-white" style="padding: 20px 15px;" nowrap></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-daily" nowrap></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-stock-inc" nowrap></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"   style="padding: 20px 15px;"  id="col-stock-dec" nowrap></th>
							
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"    style="padding: 20px 15px;"  id="col-inter-inc" nowrap></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;"  id="col-inter-dec" nowrap></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-pullout" nowrap></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-damage" nowrap></th>
							
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;" id="col-transit-inc" nowrap></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-transit-dec" nowrap></th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-running" nowrap></th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-physical" nowrap></th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-variance" nowrap></th>
							</tr>
						</tfoot>
					</table>
				</div>

			</div>

		</div>

	</div>

</div>
<style>
	.modal-header
	{
	display: block!important;
	}
	.modal-title
	{
	float: left;
	}
	.modal-header .close
	{
	float: right;
	}
</style>

<div id="skuModal" class="modal fade" role="dialog">
  	<div class="modal-dialog" style="max-width: 80%;">

	    <!-- Modal content-->
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal">&times;</button>
	        <h4 class="modal-title">Details</h4>
	      </div>
		      <div class="modal-body">
			      	
			    	<div class="custom-card">
			    		<div class="row">
		    				<div class="col-lg-2 col-sm-12">
		    					<label><strong><span id="selected_place" style="margin-left: 10px;"><?= ucwords(str_replace(['ali','sm','mw','mtc','hq','-'],['ALI','SM','MW','MTC','HQ',' '],strtolower($branchName))) ?></span> </strong></label>
		    				</div>
		    				<div class="col-lg-3 col-sm-12">
		    					<label><strong>SKU : </strong><span id="selected_desc" style="margin-left: 10px;"></span></label>
		    				</div>
		    				<div class="col-lg-3 col-sm-12">
		    					<label><strong>Column header : </strong><span id="selected_header" style="margin-left: 10px;"></span></label>
		    				</div>
		    				<div class="col-lg-4 col-sm-12" id="time_range">
		    					<label><strong>Time Range : </strong><span id="selected_time_range" style="margin-left: 10px;"></span></label>
		    				</div>
			    		</div>
			    		<div class="row">
		    				<div class="col-lg-6 col-sm-12">
		    					<div id='details'>
		    					</div>
		    				</div>
			    		</div>
			    	</div>
		      </div>
	      <br>
	    </div>

  	</div>
</div>
<script src="/js/select2.min.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>
<style type="text/css">
	.dataTables_wrapper .pull-left, .dataTables_wrapper .pull-right{
		display: none;
	}
</style>
<link rel="stylesheet" type="text/css" href="/js/dataTables/datatables.min.css"/>
<script type="text/javascript" src="/js/dataTables/datatables.min.js"></script>
<script>
	let arrStore = JSON.parse(JSON.stringify(<?= json_encode($arrStore); ?>));
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

		$('.toggle-column').on('click', function() {
			var col = $(this).attr('id').replace('col-','');
			$('[data-col='+col+']').toggleClass('d-none');
		});
		function totalSumPerColumns(){
			arrSumData = {};
			for(i = 0; i < arrStore.length+12; i++){
				arrSumData['td'+(i+1)] = 0;
			}
		   	tableTr = $('.table-inventory tbody tr').each(function(){
		   		count = 1;
	   			$(this).find('td').each(function(){
	   				value = parseFloat($(this).text());
	   				arrSumData['td' + count.toString()] = parseFloat(arrSumData['td' + count.toString()]) + value;
	   				count++;
	   			});
		   	});
		   	$.when(tableTr).done(function() {
			   	count = 0;
			   	$("tfoot .footerc th").each(function(){
			   		(count > 0)? $(this).text(arrSumData['td' + count.toString()] ) : '';
			   		count++;
			   	});
			});
		}
		totalSumPerColumns();
		let oTable = $('.table-inventory').DataTable({
			"dom": '<"pull-left"f><"pull-right"l>tip',
			paging: false,
			"info": false
		});

		$('#sku_search').keyup(function(){
		   oTable.search( $(this).val() ).draw();
		});

		let filter_store = '<?= $_SESSION["user_login"]["store_code"] ?>';
		let branch = '<?= $branch ?>';
		$('#sku_search').keyup(function(){
		   oTable.search( $(this).val() ).draw();
		   totalSumPerColumns();
		});

		let dStart = "<?= $dateStart ?>";
		let dEnd = "<?= $dateEnd ?>";
		<?php
			$dateStart = explode("-", $dateStart);
			$dateEnd = explode("-", $dateEnd);
			$dateObjStart   = DateTime::createFromFormat('!m', $dateStart[1]);
			$dateStart[1] = $dateObjStart->format('F');
			$dateObjEnd   = DateTime::createFromFormat('!m', $dateEnd[1]);
			$dateEnd[1] = $dateObjEnd->format('F');

			$dateStart = $dateStart[1].' '.$dateStart[2].', '.$dateStart[0];
			$dateEnd = $dateEnd[1].' '.$dateEnd[2].', '.$dateEnd[0];
		?>

		$('#selected_time_range').html('<?= $dateStart ?>'+' - '+'<?= $dateEnd ?>');

		$('.table-inventory tbody tr td').click(function(){
			$(this).parent().find('td').removeAttr('column_selected');
			$(this).attr('column_selected', true);
			let count_td = 1;
			$(this).parent().find('td').each(function(){
				if($(this).attr('column_selected')){
					return false;
				}
				count_td++;
			});
			arrHeaderNoModal = ['BEGINNING INVENTORY', 'RUNNING INVENTORY', 'PHYSICAL COUNT', 'VARIANCE'];
			let column_header = $(this).parent().parent().parent().find('thead tr th').eq(count_td).html();
			column_header = column_header.replace(/<br>/g,' ');
			column_header = column_header.toUpperCase();
			if(arrHeaderNoModal.indexOf(column_header) === -1 && column_header.indexOf('DAILY SALES') === -1){
				let sku_desc = $(this).parent().find('th').attr("sku_desc");
				let sku_code = $(this).parent().find('th').find('p').html();

				$('#selected_desc').text(sku_desc+' ('+sku_code+')');
				$('#selected_header').html(column_header);
				
				$("#details").load("/inventory/details/details.php","branch="+encodeURIComponent(branch)+"&filterStores="+encodeURIComponent(filter_store)+"&column_header="+encodeURIComponent(column_header)+"&sku_desc="+encodeURIComponent(sku_desc)+"&sku_code="+encodeURIComponent(sku_code)+"&dateStart="+encodeURIComponent(dStart)+"&dateEnd="+encodeURIComponent(dEnd));

				$("#skuModal").modal('show');
			}
		});
	})

</script>

<?= get_footer() ?>