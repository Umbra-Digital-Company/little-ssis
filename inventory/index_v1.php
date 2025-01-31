<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page_url = 'inventory';

////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/dashboard/functions.php";
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/includes/grab_inventory_products.php";
require $sDocRoot."/includes/inventory_functions.php";
// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// Send away if not super user
if($_SESSION['user_login']['userlvl'] != '1')  {

	header('location: /');
	exit;

};



/////SET USER
if($_SESSION['user_login']['userlvl']=='1'){

	$inventory_user ="warehouse";
}
else{
	$inventory_user = $_SESSION['user_login']['store_code'] ;
}

if(!isset($_SESSION['dashboard_login'])){

	echo "<script>window.location = 'www.sunniessytems.com'</script>";	
	
} else { ?>

	<?= get_header($page_url) ?>

	<style>

		.table100 {
		  width: 100%;
		  position: relative;
		  border: 0;
		}

		.wrap-table100 {
			border: 0;
		}

		.wrap-table100.scroll {
			overflow: auto;
			height: 640px;
			border: 0;
			max-width: 100%;
		}

		.table100 table {
			width: auto;
		  border: 0;
		}

		.table100-firstcol {
		  background-color: #fff;
		  position: absolute;
		  z-index: 100;
		  top: 0;
		  left: 0;
		}

		.table100-firstcol table {
		  background-color: #fff;
		  width: 100%;
		}

		.table100-nextcols th {
			/*border-right: 1px solid #e6e6e6;*/
		}

		.wrap-table100-nextcols {
		  width: 100%;
		  width: 100%;
		}

		.table100-nextcols table{
		  table-layout: fixed;
		  min-width: 100%;
		}

		.shadow-table100-firstcol {
		  box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
		  -moz-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
		  -webkit-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
		  -o-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
		  -ms-box-shadow: 8px 0px 10px 0px rgba(0, 0, 0, 0.05);
		}

		.table100-firstcol table {
		  background-color: transparent;
		  border-right: 1px solid #e6e6e6;
		}

		.table100.ver1 th {
		  color: #fff;
		}

		.table100.ver1 th a {
			color: #fff;
			text-decoration: none !important;
		}

		.table100.ver1 td {
		  font-family: 'Poppins-Regular';
		  padding: 15px 10px;
		  height: auto;
		}

		.table100.ver1 .table100-firstcol td a {
			color: #000;
			border-bottom: 1px solid #808080;
			text-decoration: none !important;
		}

		.table100.ver1 .table100-firstcol td,
		.table100.ver1 .table100-nextcols td {
		  color: #000;
		  /*height: 60px;*/
		}

		.table100.ver1 .table100-nextcols td {
			padding: 7px 10px;
			border: 1px solid #dee2e6;
		}


		.table100.ver1 tr {
		  border-bottom: 1px solid #e6e6e6;
		}

		.table100.ver1 td a {
			border: 0;
			line-height: 2.7;
		}

		.table-wrapper {
			position: relative;
			height: 100%;
			width: 100%;
		}

		.wrap-table100 {
			border-radius: 5px;
			overflow: hidden;
			box-shadow: 0px 6px 10px -2px gray;
		}
		.wrap-table100 tr.head th {
			background-color: #36482E !important;
			padding: 20px 10px;
		}

		table thead th {
			top: 0px !important;
			position: sticky !important;
		}

		<?php if ( !isset($_GET['date']) || (isset($_GET['date']) && $_GET['date']=='day') ) { ?>
		
			.calc-physical {
				cursor: pointer;
				/* position: relative; */
				background-image: url(<?= get_url('images/icons/icon-calculator.png') ?>);
				background-size: 20px;
				background-repeat: no-repeat;
				background-position: right 10px center;
				padding-right: 40px !important;
			}
		
		<?php } ?>

	</style>
<?php 
$total_transfer_plus = "";
$total_transfer_minus = "";
?>
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
							<p class="text-uppercase font-bold text-primary">select stores</p>
							<div class="row mt-3 store-form">

								<?php

									// Grab all stores
									$arrStores = grabStores();

									// Cycle through stores arra
									for ($i=0; $i < sizeOf($arrStores); $i++) { 

										if($arrStores[$i]['active'] == 'y') {

											$curStoreName = ucwords(str_replace("u.p.", "UP", str_replace("sm", "SM", str_replace("-", " ", $arrStores[$i]['store_name']))));
											$checked = checkFilter($arrStores[$i]['store_id']);

											echo 	'<div class="col-12 col-md-6 mb-2">
														<div class="d-flex align-items-center">
															<input class="sr-only checkbox" name="filterStores[]" type="checkbox" id="option'.$curStoreName.'" value="'.$arrStores[$i]['store_id'].'" '.$checked.'>
															<label for="option'.$curStoreName.'" class="custom_checkbox"></label>
															<label for="option'.$curStoreName.'" class="m-0 ml-2">'.$curStoreName.'</label>
														</div>
													</div>';

										};

									};

									// Echo select all option
									echo 	'<div class="col-12 col-md-6">
												<div class="d-flex align-items-center">
													<input class="sr-only checkbox" type="checkbox" id="optionAllStores" value="all-stores" '.( ($_GET['store'] == $arrStores[$i]['store_id'] ? "selected" : "") ).'>
													<label for="optionAllStores" class="custom_checkbox"></label>
													<label for="optionAllStores" class="m-0 ml-2" id="optionAllStoresLabel">All Stores</label>
												</div>
											</div>';

								?>

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

															echo '<option value="'.($i + 1).'" data-month="'.$month.'"'.$monthSelect.'>'.$month.'</option>';

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

															echo '<option value="'.($i + 1).'" data-month="'.$month.'"'.$monthSelect.'>'.$month.'</option>';

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

								<a href="/dashboard/" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline d-lg-none">
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

						<a href="/dashboard/" class="d-none d-lg-block text-danger text-uppercase font-bold">
							reset filter
						</a>

					<?php }; ?>
					<a href="#" id="toggle-filter"><img src="<?= get_url('images/icons/icon-filter.png') ?>" width="30" alt="filter" class="img-fluid"></a>
				</div>

				<hr class="spacing">

				<div id="excel-inventory" class="custom-card p-0">

					<div class="wrap-table100 non-search">
					<div class="table100 ver1">
						<div class="wrap-table100-nextcols">
							<div class="table100-nextcols">
								<table cellpadding="0" cellspacing="0" style="border-collapse: collapse">
									<thead>
										<tr class="row100 head">
											<th class="cell100 text-uppercase small column1">SKU</th>
											<th class="cell100 text-uppercase text-center small column3" nowrap>beginning<br/>inventory</th>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-daily" nowrap>daily<br/>sales</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="daily"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-change-order" nowrap>Stock Transfer<br/>+</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="change-order"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-delivery" nowrap>Stock Transfer<br/>-</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="delivery"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-inc" nowrap>inter<br/>(+)</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="inter-inc"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-inter-dec" nowrap>inter<br/>(-)</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="inter-dec"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-pullout" nowrap>pullout</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="pullout"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-damage" nowrap>damage</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="damage"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-transit" nowrap>in<br/>transit</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="transit"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-running" nowrap>running</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="running"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-physical" nowrap>physical<br/>count</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="physical"><?= $x ?></th>
												<?php }
											} ?>
											<th class="cell100 text-uppercase text-center small column3 toggle-column" id="col-variance" nowrap>variance</th>
											<?php if (isset($_GET['date']) && $_GET['date']!='day') {
												for ($x=1;$x<=31;$x++) { ?>
													<th class="cell100 text-uppercase text-center small column3 d-none" nowrap data-col="variance"><?= $x ?></th>
												<?php }
											} ?>
										</tr>
									</thead>
									<tbody>

										<?php for ($i=0;$i<sizeof($arrFrames);$i++) { ?>
										
											<tr class="row100 body">
												<td nowrap class="cell100 small column1 ">
													<?= $arrFrames[$i]['product_style'] . " " . $arrFrames[$i]['product_color'] ?>
													<p class="small text-secondary m-0"><?= $arrFrames[$i]['product_code'] ?></p>
												</td>
												<td nowrap class="cell100 small text-center">0</td>
												<td nowrap class="cell100 small text-center">0</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="daily">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center"><?php 
												$total_transfer_plus =GetTotalStockTransferP($arrFrames[$i]['product_code'],$inventory_user,date('m'));
												echo $total_transfer_plus;

														?></td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="change-order">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center"><?php 
												$total_transfer_minus= GetTotalStockTransferM($arrFrames[$i]['product_code'],$inventory_user,date('m'));
												echo $total_transfer_minus;
														?></td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="delivery">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center">0</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="inter-inc">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center">0</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="inter-dec">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center"><?= $arrFrames[$i]['pulloutcount'] ?></td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {

													
													for ($x=1;$x<=31;$x++) {
														$pulldate=date('Y')."-".date('m')."-".$x;
														if($arrFrames[$i]['pulloutdate']==$pulldate){
												?>
														<td nowrap class="cell100 small text-center d-none" data-col="pullout">
																	<?= $arrFrames[$i]['pulloutcount'] ?>
														</td>
												<?php
															}else{?>
														<td nowrap class="cell100 small text-center d-none" data-col="pullout">
																	0
														</td>
														<?php 	}
													 }
												} ?>
												<td nowrap class="cell100 small text-center">0</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="transit">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center">0</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="damage">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center">
															<?php  $runningtotal=  $arrFrames[$i]['count'] + $total_transfer_plus- $total_transfer_minus; 
																echo $runningtotal;
															?>
												</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="running">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center <?= ( !isset($_GET['date']) || (isset($_GET['date']) && $_GET['date']=='day') ) ? 'calc-physical' : '' ?>" data-target="#calculatePhysicalCount" data-toggle="modal" data-frame="<?= $arrFrames[$i]['product_style'] . $arrFrames[$i]['product_color'] ?>" data-sku="<?= $arrFrames[$i]['product_code'] ?>" data-day="<?= date('Y-m-d') ?>">0</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="physical">0</td>
													<?php }
												} ?>
												<td nowrap class="cell100 small text-center">0</td>
												<?php if (isset($_GET['date']) && $_GET['date']!='day') {
													for ($x=1;$x<=31;$x++) { ?>
														<td nowrap class="cell100 small text-center d-none" data-col="variance">0</td>
													<?php }
												} ?>
											</tr>

										<?php } ?>

									</tbody>
								</table>
							</div>
						</div>
					</div>
					</div>
				</div>

			</div>

		</div>

	</div>

	<div class="modal fade" id="calculatePhysicalCount">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title font-bold text-primary">Frame name</h4>
				</div>
				<div class="modal-body">
					<form action="#" method="POST">
						<input type="hidden" name="product_code" value="">
						<input type="hidden" name="date" value="">
						<p class="mb-2 text-secondary">Input your calculation for physical count</p>
						<input type="text" class="form-control" value="0">
					</form>
				</div>
				<div class="modal-footer">
					<a href="#" class="text-uppercase text-danger font-bold small mr-3" data-dismiss="modal">discard</a>
					<a href="#submit" class="text-uppercase text-primary font-bold small">save</a>
				</div>
			</div>
		</div>
	</div>

	<script src="/js/jquery-3.2.1.min.js"></script>

	<script>
	
		$(document).ready(function() {


			if ( $('.table100-nextcols tbody tr').length > 10 ) {

				$('.wrap-table100').addClass('scroll');

			};
			
			//////////////// TOGGLE COLUMN

			$('.toggle-column').on('click', function() {
				var col = $(this).attr('id').replace('col-','');
				$('[data-col='+col+']').toggleClass('d-none');
			})
			
			//////////////// CALCULATE PHYSICAL COUNT

			$('.calc-physical').on('click', function() {
				var sku = $(this).data('sku'),
					style = $(this).data('frame'),
					date = $(this).data('day');

				$('.modal-title').text(style);
				$('input[name="product_code"]').val(sku);
				$('input[name="date"]').val(date);
			})
		})
	
	</script>

	<?= get_footer() ?>

	<?php require $sDocRoot."/includes/notification.php";	

} ?>