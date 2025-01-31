<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'warehouse';
$page_url = 'stock-movement';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// error_reporting(0);
////////////////////////////////////////////////

// Set access for Admin and Warehouse account
if($_SESSION['user_login']['userlvl'] != '8') {

	header('location: /');
	exit;

}

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/inventory/includes/aim_setup_test.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_moving_stock.php";
// require $sDocRoot."/inventory/includes/inventory_functions.php";

// inventory table
// require $sDocRoot."/inventory/includes/grab_inventory_products.php";
require $sDocRoot."/inventory/includes/grab_inventory_products_v2.php";
require $sDocRoot."/inventory/includes/grab_inventory_products_pd.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

// Grab Store
// $storeName = "";

// for ($i=0; $i < sizeOf($arrStore); $i++) { 

// 	if($arrStore[$i]['store_id'] == $_SESSION['user_login']['store_code']) {

// 		$storeName = $arrStore[$i]['store_name'];

// 	};
	
// };

?>

<?= get_header($page_url) ?>

<?php 
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
	 	$dateEndpdh = date('Y-m-d',strtotime("-1 days"));
	}elseif($_GET['date']=='week'){
		$datecurrentH = date( 'Y-m-d', strtotime( 'sunday this week' ) );
		$dateStartpdh = date( 'Y-m-d', strtotime( 'monday this week' ) );
		 $dateEndpdh = date( 'Y-m-d', strtotime( 'sunday this week' ) );
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

}
else{
	$datecurrentH = date('Y-m');
	$dateStartpdh = date('Y-m').'-1';
		$dateEndpdh = date('Y-m-t');
}

if($_SESSION['user_login']['userlvl']=='1'){

	$inventory_user ="warehouse";
}
else{
	$inventory_user = $_SESSION['user_login']['store_code'] ;
}


if(isset($_GET['filterStores'])){

	$store_id=$_GET['filterStores'][0];

}else{
	if($_SESSION['user_login']['userlvl'] == '13'){
			$store_id='warehouse';

	}else{
			$store_id=$_SESSION['store_code'];
	}
}

$datediff = strtotime($dateStartpdh) - strtotime($dateEndpdh);

 $dayscount= str_replace("-","",round($datediff / (60 * 60 * 24)));
?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

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

														echo '<option value="'.$i.'"'.$daySelect.'>'.(sprintf("%02d", $i)).'</option>';
														
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

														echo '<option value="'.$i.'"'.$daySelect.'>'.(sprintf("%02d", $i)).'</option>';
														
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

							<a href="/inventory/warehouse/stock-movement/" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline d-lg-none">
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

					<a href="/inventory/warehouse/stock-movement/" class="mr-3 d-none d-lg-block">
						<button class="btn btn-danger" type="button">reset filter</button>
					</a>

				<?php }; ?>
				<a href="#" id="toggle-filter"><img src="<?= get_url('images/icons/icon-filter.png') ?>" width="30" alt="filter" class="img-fluid"></a>
			</div>

			<div id="inventory-receive" class="mt-4">
			
				<div class="table-default table-responsive" style="max-width: 100%;">
					<table class="table-striped table-inventory">
						<thead>
							<tr class="row100 head">
								<th class="cell100 text-uppercase small column1">SKU</th>
								<th class="cell100 text-uppercase text-center small column3" nowrap>beginning<br/>inventory</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" nowrap>requested<br/>inventory</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-stock-transfer-in" nowrap>Stock<br/>Transfer (+)</th>
								<?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="stock-transfer-in"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-stock-transfer-out" nowrap>Stock<br/>Transfer (-)</th>
								<?php for ($x=0;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="stock-transfer-out"><?= 	date('m-d',strtotime($dateStartpdh .'+'.$x.' days')) ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-pullout" nowrap>pullout</th>
								<?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="pullout"><?= $x ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-damage" nowrap>damage(in)</th>
								<?php for ($x=1;$x<=$dayscount;$x++) { ?>
									<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="damage"><?= $x ?></th>
								<?php } ?>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" nowrap>in<br/>transit (+)</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" nowrap>in<br/>transit (-)</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" nowrap>running<br/>inventory</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" nowrap>physical<br/>count</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" nowrap>variance</th>
							</tr>
						</thead>
						<tbody>

						<?php 
						$arrframeday= array();
						$arrframeDayX=array();
for ($i=0;$i<sizeof($arrIntPerday);$i++) {

	if($arrIntPerday[$i]["type"]=='replenish'){
		$type ='stock_in';
	}
	elseif($arrIntPerday[$i]["type"]=='stock_transfer' && $arrIntPerday[$i]["stock_from"]!=$store_id ){
			$type='stock_in';
	}
	elseif($arrIntPerday[$i]["type"]=='stock_transfer' && $arrIntPerday[$i]["stock_from"]==$store_id){
		$type='stock_out';
	} else {
		$type=str_replace(" ","_",$arrIntPerday[$i]["type"]);
	}

	$arrframeDayX=$arrIntPerday[$i]["product_code"];
	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["total"] = "0";

	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["product_code"] = $arrIntPerday[$i]["product_code"];
	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["total"] += $arrIntPerday[$i]["total"];
	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["type"] = $arrIntPerday[$i]["type"];
	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["status"] = $arrIntPerday[$i]["status"];
	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["status_date"] = $arrIntPerday[$i]["status_date"];
	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["stock_from"] = $arrIntPerday[$i]["stock_from"];
	$arrframeday[$arrIntPerday[$i]["product_code"]][$arrIntPerday[$i]["status_date"]][$type]["store_id"] = $arrIntPerday[$i]["store_id"];
}

// $arrSalesDay =array();

// for ($i=0;$i<sizeof($arrDailySales);$i++) {
// 	$arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["payment_date"]]["product_code"] =$arrDailySales[$i]["product_code"];
// 	$arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["payment_date"]]["status_date"] = $arrDailySales[$i]["payment_date"];
// 	$arrSalesDay[$arrDailySales[$i]["product_code"]][$arrDailySales[$i]["payment_date"]]["sales_count"] += $arrDailySales[$i]["sales"];
// 	$arrSalesDay[$arrDailySales[$i]["product_code"]]["total_frame"] += $arrDailySales[$i]["sales"];
// 	$arrSalesDay[$arrDailySales[$i]["product_code"]]["product_code"] =$arrDailySales[$i]["product_code"];
// }


                            $arrActual=array();
							$arrActualX=array();
							for ($i=0;$i<sizeof($arrActualCount);$i++) {
								$arrActualX[$i]=$arrActualCount[$i]["product_code"];
								$arrActual[$arrActualCount[$i]["product_code"]][$arrActualCount[$i]["date_end"]]["product_code"]=$arrActualCount[$i]["product_code"];
								$arrActual[$arrActualCount[$i]["product_code"]][$arrActualCount[$i]["date_end"]]["count"]=$arrActualCount[$i]["count"];
								$arrActual[$arrActualCount[$i]["product_code"]][$arrActualCount[$i]["date_end"]]["date_end"]=$arrActualCount[$i]["date_end"];
								$arrActual[$arrActualCount[$i]["product_code"]][$arrActualCount[$i]["date_end"]]["input_count"]=$arrActualCount[$i]["input_count"];

							}
// echo "<pre>";
// print_r($arrFrames);
// echo "</pre>";

// 	exit;								
							for ($i=0;$i<sizeof($arrFrames);$i++) { ?>
							
								<tr class="row100 body">
									<th nowrap class="cell100 small column1 ">
										<?= $arrFrames[$i]['product_style'] . " " . $arrFrames[$i]['product_color'] ?>
										<p class="small text-secondary m-0"><?= $arrFrames[$i]['product_code'] ?></p>
									</th>

								
									
									<td nowrap class="cell100 small text-center">
												<?php 
													// $beginning_inventory =GetTotalBegInventory($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
													// echo $beginning_inventory;
													// echo $arrFrames[$i]["beginning_inventory"];
													$beg_inventory =$arrFrames[$i]["beginning_inventory"]-$arrFrames[$i]["pullout"]-$arrFrames[$i]["damage"]-$arrFrames[$i]["stock_transfer_out"]
													- $arrFrames[$i]["transit_out_past"];
													echo $beg_inventory;
													// echo $arrFrames[$i]["stock_transfer_out"];
												?>
									</td>

									<td nowrap class="cell100 small text-center"><?= $arrFrames[$i]["requested"] ?></td>
									
									<td nowrap class="cell100 small text-center">
										<?php echo $arrFrames[$i]["stock_transfer_in_c"];?>
									</td>
									<?php  for ($x=0;$x<=$dayscount;$x++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="stock-transfer-in">
														<?php
															$plusDay[$x] = '"+'.$x.' days"';
															//// perday stock_indate('Y-m')
															$stock_in_date["stock_transfer_in"] =date('Y-m-d',strtotime($dateStartpdh .'+'.$x.' days')) ;
															//$datecurrentH ."-".$x;
															

														if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
															if($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["status_date"]==$stock_in_date["stock_transfer_in"] 
															&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["type"]=='stock_transfer'
																|| $arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["type"]=='replenish')
															&& 	($arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["store_id"]==$_SESSION['store_code'] 
															&& $arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["status"]=='received'
																	
																) ){
																echo	$stock_in_per_day=$arrframeday[$arrFrames[$i]['product_code']][$stock_in_date["stock_transfer_in"]]["stock_in"]["total"];
															}else{
																echo	$stock_in_per_day="0";
															}
														}else{
															echo	$stock_in_per_day="0";
														}
														?>
										</td>
									<?php } ?>


									<td nowrap class="cell100 small text-center"><?php 
									
											echo  $arrFrames[$i]["stock_transfer_out_c"];
											
									?></td>

									<?php for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="stock-transfer-out">
													<?php	 
													
														//// perday stock_transfer_out
													$stock_out_date["stock_transfer_out"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
												
											if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
													if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["status_date"]==$stock_out_date["stock_transfer_out"] 
													&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["type"]=='stock_transfer' 
															)
													&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["stock_from"]==$_SESSION['store_code'] 
													&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["status"]=='received')
													{
														echo 	$stock_out_per_day=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["stock_transfer_out"]]["stock_out"]["total"];
													}else{
														echo $stock_out_per_day="0";
													}
												}else{
													echo $stock_out_per_day="0";
												}

													?>
										</td>
									<?php } ?>


									<td nowrap class="cell100 small text-center"><?= $arrFrames[$i]['pullout_c'] ?></td>


											<?php for ($ost=0;$ost<=$dayscount;$ost++) { ?>
												<td nowrap class="cell100 small text-center d-none" data-col="pullout">
															<?php	 
															
																//// perday stock_transfer_out
															$stock_out_date["pullout"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
														
														if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
															if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["status_date"]==$stock_out_date["pullout"] 
															&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["type"]=='pullout' 
																	)
															&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["stock_from"]==$_SESSION['store_code']
															&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["status"]=='received' ){
																echo 	$pulloutday=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["pullout"]]["pullout"]["total"];
															}else{
																echo $pulloutday="0";
															}
														}else{
															echo $pulloutday="0";
														}
															?>
													</td>
											<?php } ?>
									<td nowrap class="cell100 small text-center"><?= $arrFrames[$i]['damage_i'] ?></td>
									<?php for ($ost=0;$ost<=$dayscount;$ost++) { ?>
										<td nowrap class="cell100 small text-center d-none" data-col="damage">
													<?php	 
										
														//// perday stock_transfer_out
													$stock_out_date["damage"]=date('Y-m-d',strtotime($dateStartpdh .'+'.$ost.' days')) ;
												
											if(in_array($arrFrames[$i]['product_code'],$arrframeDayX)){
													if($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["status_date"]==$stock_out_date["damage"] 
													&&  ($arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["type"]=='damage' 
															)
													&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["store_id"]=='warehouse_damage'
													&& $arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["status"]=='received'){
														echo 	$damage=$arrframeday[$arrFrames[$i]['product_code']][$stock_out_date["damage"]]["damage"]["total"];
													}else{
														echo $damage="0";
													}
												}else{
													echo $damage="0";
												}

														?>
										</td>
									<?php } ?>
									<td nowrap class="cell100 small text-center"><?php 
												
										// $total_in_transit= GetTotalOnTransit($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
										// echo $total_in_transit;
										echo	$arrFrames[$i]['transit_in'];
										
									?></td>
									<td nowrap class="cell100 small text-center"><?php 
												
										// $total_in_transit2= GetTotalOnTransit2($arrFrames[$i]['product_code'],$inventory_user,$i_date_start,$i_date_end);
										// echo $total_in_transit2;
									echo	$arrFrames[$i]['transit_out'];

										
									?></td>
									<td nowrap class="cell100 small text-center"><?php

// echo $beg_inventory."<br>"; 
// echo  $arrFrames[$i]["stock_transfer_in_c"]."<br>"; 
// echo $arrFrames[$i]["interbranch_in_c"]."<br>"; 
// echo  $arrFrames[$i]["stock_transfer_out_c"]."<br>"; 
// echo $arrFrames[$i]["interbranch_out_c"]."<br>"; 
// echo $arrFrames[$i]["damage_c"]."<br>"; 
// echo $arrFrames[$i]["pullout_c"]."<br>"; 
// echo $arrFrames[$i]["damage_i"]."<br>"; 
// echo  $arrFrames[$i]["transit_out"]."<br>"; 

												
										$runningtotal=  $beg_inventory +$arrFrames[$i]["stock_transfer_in_c"]
										+$arrFrames[$i]["interbranch_in_c"]- $arrFrames[$i]["stock_transfer_out_c"]-
										$arrFrames[$i]["interbranch_out_c"]-$arrFrames[$i]["damage_c"]-$arrFrames[$i]["pullout_c"]+$arrFrames[$i]["damage_i"]- $arrFrames[$i]["transit_out"]; 
										echo $runningtotal;
									
									?></td>
									
									<td nowrap class="cell100 small text-center"><?php 
												// echo $dateEndpdh;
												// echo "<br>";
                                                // echo date('Y-m-d',$arrActual[$arrFrames[$i]['product_code']]["date_end"];
                                                if(in_array($arrFrames[$i]['product_code'],$arrActualX )){
												if($arrActual[$arrFrames[$i]['product_code']]["date_end"]==$dateEndpdh 
												&& $arrFrames[$i]['product_code']==$arrActual[$arrFrames[$i]['product_code']]["product_code"]){

															$actual=$arrActual[$arrFrames[$i]['product_code']]["count"];
												}else{
														$actual="0";
                                                }
                                            }else{
                                                $actual="0";
                                        }
												?>
												<?= $actual ?></td>
									<td nowrap class="cell100 small text-center"><?php  if($actual!="0"){
																$variance =$actual-$runningtotal;
												}else{
														$variance ="0";
												} 
												echo $variance; ?></td>
								</tr>

							<?php 
						
						
						} ?>

						</tbody>
					</table>
				</div>

			</div>

		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>

<script>
	
	$(document).ready(function() {

		$('input[name="pullout_option"]').on('click', function() {
			if ($('#pullout_option_1').is(':checked')) {
				$('select[name="recipient_branch"]').prop('disabled', false).prop('required', true);
			} else {
				$('select[name="recipient_branch"]').prop('disabled', true).prop('required', false);
			}
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

		
			
		//////////////// TOGGLE COLUMN

		$('.toggle-column').on('click', function() {
			var col = $(this).attr('id').replace('col-','');
			$('[data-col='+col+']').toggleClass('d-none');
		})

	})

</script>

<?= get_footer() ?>