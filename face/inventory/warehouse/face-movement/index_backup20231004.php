<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'warehouse';
$page_url = 'face-movement';

$filter_page = 'stock_movement_warehouse_face';
$group_name = 'aim_face';
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// error_reporting(0);
////////////////////////////////////////////////

// Set access for Admin and Warehouse account
// if($_SESSION['user_login']['userlvl'] != '8') {

// 	header('location: /');
// 	exit;

// }

ini_set('memory_limit', '1G');
ini_set("auto_detect_line_endings", true);



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
// require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/face/includes/grab_stores_face.php";
	
require $sDocRoot."/face/inventory/includes/grab_poll_51_face.php";
// require $sDocRoot."/inventory/includes/w_admin_function.php";
require $sDocRoot."/inventory/includes/s_admin_function.php";

// require $sDocRoot."/inventory/includes/grab_inventory_products_v2.php";
// require $sDocRoot."/inventory/includes/grab_inventory_products_pd.php";
require $sDocRoot."/inventory/includes/inventory_functions.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";



// $_SESSION['permalink'] = $filter_page; 

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
	}elseif($_GET['date']=='day'){
		$datecurrentH = date('Y-m');
		$dateStartpdh = date('Y-m-d');
		$dateEndpdh = date('Y-m-d');
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

							<a href="/face/inventory/warehouse/face-movement/" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline d-lg-none">
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

					<a href="/face/inventory/warehouse/face-movement/" class="mr-3 d-none d-lg-block">
						<button class="btn btn-danger" type="button">reset filter</button>
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
			<div id="inventory-receive" class="mt-4">
				<input type="hidden" id="count_poll51" value ="0">
				<div class="table-default table-responsive" style="max-width: 100%;">
					<table class="table-striped table-inventory">
                    <thead>
							<tr class="row100 head">
								<th class="cell100 text-uppercase small column1">SKU <span style="float: right;" class="loading_all"><img src="/images/loading_gray.gif" width="30px" height="30px"></span></th>
								<th class="cell100 text-uppercase text-center small column3" nowrap>beginning<br/>inventory</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-daily" nowrap>daily<br/>sales</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-change-order" nowrap>Stock<br/>Transfer (+)</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-delivery" nowrap>Stock<br/>Transfer (-)</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-inc" nowrap>inter<br/>branch (+)</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-dec" nowrap>inter<br/>branch (-)</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-pullout" nowrap>pullout</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-damage" nowrap>damage</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-transit" nowrap>in<br/>transit(+)</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-transit" nowrap>in<br/>transit(-)</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-running" nowrap>running<br/>inventory</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-physical" nowrap>physical<br/>count</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-variance" nowrap>variance</th>
							</tr>
						</thead>
                        <tbody>
                        <?php 					
		$branch = 'warehouse';
for ($i=0;$i<sizeof($arrPoll51_items);$i++) {
								
								?>
							
							<tr class="row100 body" filterStores="warehouse" product_code="<?= $arrPoll51_items[$i]["product_code"] ?>" dateStart="<?= $dateStart ?>" dateEnd="<?= $dateEnd ?>" datecurrentH ="<?= $datecurrentH ?>" dateStartpdh ="<?= $dateStartpdh ?>" dateEndpdh ="<?= $dateEndpdh ?>">
									<th nowrap class="cell100 small column1 " sku_desc ="<?= $arrPoll51_items[$i]['product_style']. " ".$arrPoll51_items[$i]['product_color'] ?>">
										<?= $arrPoll51_items[$i]['product_style'] . " " . $arrPoll51_items[$i]['product_color'] ?>
										<span style="float: right;"><img src="/images/loading_gray.gif" width="23px" height="23px"></span>
										<p class="small text-secondary m-0"><?= $arrPoll51_items[$i]['product_code'] ?></p>
									</th>

									<td nowrap class="cell100 small text-center beginning_inventory_value">
										-
									</td>
									<td nowrap class="cell100 small text-center daily_sales_value">
										-
									</td>
									<td nowrap class="cell100 small text-center stock_transfer_plus_value">
										-
									</td>
									<td nowrap class="cell100 small text-center stock_transfer_minus_value">
										-
									</td>
									<td nowrap class="cell100 small text-center inter_branch_plus_value">
										-
									</td>
									<td nowrap class="cell100 small text-center inter_branch_minus_value">
										-
									</td>
									<td nowrap class="cell100 small text-center pullout_value">
										-
									</td>
									<td nowrap class="cell100 small text-center damage_value">
										-
									</td>
									<td nowrap class="cell100 small text-center in_transit_plus_value">
										-
									</td>
									<td nowrap class="cell100 small text-center in_transit_minus_value">
										-
									</td>
									<td nowrap class="cell100 small text-center running_inventory_value">
										-
									</td>
									<td nowrap class="cell100 small text-center physical_count_value">
										-
									</td>
									<td nowrap class="cell100 small text-center variance_value">
										-
									</td>
									
								</tr>

							<?php  
							// exit;
							 } ?>

						</tbody>
						<tfoot >
							<tr class="row100 foot footerc" >
								<th class="cell100 text-uppercase small column1   text-white" style="padding: 20px 15px;">Total <span style="float: right;" class="loading_all"><img src="/images/loading_gray.gif" width="30px" height="30px"></span></th>
								<th class="cell100 text-uppercase text-center small column3  text-white" style="padding: 20px 15px;" nowrap>-</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-daily" nowrap>-</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"  style="padding: 20px 15px;" id="col-change-order" nowrap>-</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"   style="padding: 20px 15px;"  id="col-delivery" nowrap>-</th>
							
								<th class="cell100 text-uppercase text-center small column3 toggle-column  text-white"    style="padding: 20px 15px;"  id="col-inter-inc" nowrap>-</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;"  id="col-inter-dec" nowrap>-</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-pullout" nowrap>-</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-damage" nowrap>-</th>
							
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"   style="padding: 20px 15px;" id="col-transit" nowrap>-</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-transit" nowrap>-</th>
								
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-running" nowrap>-</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-physical" nowrap>-</th>
								<th class="cell100 text-uppercase text-center small column3 toggle-column text-white"  style="padding: 20px 15px;"  id="col-variance" nowrap>-</th>
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
<script src="/js/signature.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>
<style type="text/css">
	.dataTables_wrapper .pull-left, .dataTables_wrapper .pull-right{
		display: none;
	}
</style>
<link rel="stylesheet" type="text/css" href="/js/dataTables/datatables.min.css"/>
<script type="text/javascript" src="/js/dataTables/datatables.min.js"></script>
<script>
	let count_poll51 = JSON.parse(JSON.stringify(<?= json_encode(count($arrPoll51_items)); ?>));
	let oTable;
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
		function totalSumPerColumns(){
			arrSumData = {
			   	td1: 0,
			   	td2: 0,
			   	td3: 0,
			   	td4: 0,
			   	td5: 0,
			   	td6: 0,
			   	td7: 0,
			   	td8: 0,
			   	td9: 0,
			   	td10: 0,
			   	td11: 0,
			   	td12:0,
			   	td13:0
		   };
		   
		   	tableTr = $('.table-inventory tbody tr').each(function(){
		   		count = 1;
	   			$(this).find('td').each(function(){
	   				value = parseFloat($(this).text().trim());
	   				value = (!isNaN(value)) ? value : 0;
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

		let filter_store = '<?= $_GET['filterStores'] ?>';
		let branch = '<?= $branch ?>';

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
			if(arrHeaderNoModal.indexOf(column_header) === -1){
				let sku_desc = $(this).parent().find('th').attr("sku_desc");
				let sku_code = $(this).parent().find('th').find('p').html();

				$('#selected_desc').text(sku_desc+' ('+sku_code+')');
				$('#selected_header').html(column_header);
				
				$("#details").load("/face/inventory/details/details_face.php","branch="+encodeURIComponent(branch)+"&filterStores="+encodeURIComponent(filter_store)+"&column_header="+encodeURIComponent(column_header)+"&sku_desc="+encodeURIComponent(sku_desc)+"&sku_code="+encodeURIComponent(sku_code)+"&dateStart="+encodeURIComponent(dStart)+"&dateEnd="+encodeURIComponent(dEnd));

				$("#skuModal").modal('show');
			}
		});

		let count_r = 0;
		let arrDataResult = [];
		$(".table-inventory tbody tr").each(function(){
	    	product_code = $(this).attr('product_code');
	    	filter_stores = $(this).attr('filterStores');
	    	ds = $(this).attr('dateStart');
	    	de = $(this).attr('dateEnd');
	    	datecurrentH = $(this).attr('datecurrentH');
	    	dateStartpdh = $(this).attr('dateStartpdh');
	    	dateEndpdh = $(this).attr('dateEndpdh');

	    	let _this = $(this);
	    	arrDataResult.push({
	    		product_code : product_code,
	    		filter_stores : filter_stores,
	    		ds : ds,
	    		de: de,
	    		datecurrentH : datecurrentH,
	    		dateStartpdh : dateStartpdh,
	    		dateEndpdh : dateEndpdh,
	    		_this : count_r
	    	});
	    	count_r++;
	    });

		function getDataStudios(row){
			product_code = arrDataResult[row].product_code;
	    	filter_stores = arrDataResult[row].filter_stores;
	    	ds = arrDataResult[row].ds;
	    	de = arrDataResult[row].de;
	    	datecurrentH = arrDataResult[row].datecurrentH;
	    	dateStartpdh = arrDataResult[row].dateStartpdh;
	    	dateEndpdh = arrDataResult[row].dateEndpdh;
	    	_this = $(".table-inventory tbody tr").eq(arrDataResult[row]._this);

			$.get("/face/inventory/details/columns_data_face.php",{filterStores:encodeURIComponent(filter_stores),product_code:encodeURIComponent(product_code),dateStart:encodeURIComponent(ds),dateEnd:encodeURIComponent(de), datecurrentH:encodeURIComponent(datecurrentH), dateStartpdh:encodeURIComponent(dateStartpdh), dateEndpdh:encodeURIComponent(dateEndpdh)}, function(result){

	    		_this.find('img').remove();
	    		_this.find('.beginning_inventory_value').text((result.beg_inventory != null) ? result.beg_inventory : 0 );
	    		_this.find('.daily_sales_value').text((result.sales != null) ? result.sales : 0 );
	    		_this.find('.stock_transfer_plus_value').text((result.stock_transfer_in_c != null) ? result.stock_transfer_in_c : 0 );
	    		_this.find('.stock_transfer_minus_value').text((result.stock_transfer_out_c != null) ? result.stock_transfer_out_c : 0 );

	    		_this.find('.inter_branch_plus_value').text((result.interbranch_in_c != null) ? result.interbranch_in_c : 0 );
	    		_this.find('.inter_branch_minus_value').text((result.interbranch_out_c != null) ? result.interbranch_out_c : 0 );
	    		_this.find('.pullout_value').text((result.pullout_c != null) ? result.pullout_c : 0 );

	    		_this.find('.damage_value').text((result.damage_c != null) ? result.damage_c : 0 );
	    		_this.find('.in_transit_plus_value').text((result.transit_in != null) ? result.transit_in : 0 );
	    		_this.find('.in_transit_minus_value').text((result.transit_out_c != null) ? result.transit_out_c : 0 );
	    		_this.find('.running_inventory_value').text((result.running_total != null) ? result.running_total : 0 );
	    		_this.find('.physical_count_value').text((result.physical_count != null) ? result.physical_count : 0 );
	    		_this.find('.variance_value').text((result.variance != null) ? result.variance : 0 );

	    		let count = parseInt($("#count_poll51").val());
	    		count = count + 1;
	    		$("#count_poll51").val(count);

	    		if(parseInt($("#count_poll51").val()) == parseInt(count_poll51)){
	    			totalSumPerColumns();
	    			Otable();
					$('.loading_all').remove();
					$('#btn_download').show();
					$('#btn_download_all').show();
	    		}else{
	    			getDataStudios(row+1);
	    		}

	    	},'JSON');
		}
		getDataStudios(0);
		
		 $('#sku_search').keyup(function(){
	    	if(parseInt($("#count_poll51").val()) == parseInt(count_poll51)){
	    		oTable.search( $(this).val() ).draw();
		   		totalSumPerColumns();
	    	}
		});
		function Otable(){
			oTable = $('.table-inventory').DataTable({
					"dom": '<"pull-left"f><"pull-right"l>tip',
					paging: false,
					"info": false
				});
		}

	})

</script>

<?= get_footer() ?>