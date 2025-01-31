<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'variance-report';
$page_url = 'variance-report-(Audit)';

$filter_page = 'variance_reports_audit_face';
$group_name = 'aim_face';

////////////////////////////////////////////////

// Set access for Admin and Warehouse account
// if($_SESSION['user_login']['userlvl'] != '13' && $_SESSION['user_login']['userlvl'] != '22') {

// 	header('location: /');
// 	exit;

// };

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/includes/dashboard/set_date.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/face/inventory/includes/grab_stores_face.php";
require $sDocRoot."/inventory/includes/grab_all_transferable_items.php";
require $sDocRoot."/face/inventory/includes/grab_variance_report_audit.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

?>

<?= get_header($page_url) ?>

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

					<!-- transaction type -->
					<p class="text-uppercase font-bold text-primary">transaction type</p>
					<div class="row mt-3 transaction-type">
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterType" type="radio" id="optionStockTransfer" value="stock_transfer" <?= (isset($_GET['filterType']) && $_GET['filterType']=='stock_transfer') ? 'checked="checked"' : '' ?>>
								<label for="optionStockTransfer" class="custom_checkbox"></label>
								<label for="optionStockTransfer" class="m-0 ml-2">Stock Transfer</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterType" type="radio" id="optionInterBranch" value="interbranch" <?= (isset($_GET['filterType']) && $_GET['filterType']=='interbranch') ? 'checked="checked"' : '' ?>>
								<label for="optionInterBranch" class="custom_checkbox"></label>
								<label for="optionInterBranch" class="m-0 ml-2">Inter Branch</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterType" type="radio" id="optionDamage" value="damage" <?= (isset($_GET['filterType']) && $_GET['filterType']=='damage') ? 'checked="checked"' : '' ?>>
								<label for="optionDamage" class="custom_checkbox"></label>
								<label for="optionDamage" class="m-0 ml-2">Damage</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterType" type="radio" id="optionPullout" value="pullout" <?= (isset($_GET['filterType']) && $_GET['filterType']=='pullout') ? 'checked="checked"' : '' ?>>
								<label for="optionPullout" class="custom_checkbox"></label>
								<label for="optionPullout" class="m-0 ml-2">Pullout</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterType" type="radio" id="optionAllType" value="all" <?= ( (isset($_GET['filterType']) && $_GET['filterType']=='all')) || !isset($_GET['filterType']) ? 'checked="checked"' : '' ?>>
								<label for="optionAllType" class="custom_checkbox"></label>
								<label for="optionAllType" class="m-0 ml-2">All</label>
							</div>
						</div>
					</div>

					<!-- sender -->
					<p class="text-uppercase font-bold text-primary mt-4">sender & receiver</p>
					<div class="row mt-2">
						<div class="col-12 col-md-6">
							<div class="d-flex align-items-center">
								<p class="mr-2">From:</p>
								<select name="filterSender" id="filterSender" class="form-control select2">
									<option value="">All Branch</option>
									<optgroup label="STORE NAME">
										<?php for ($i=0;$i<sizeof($arrStoresFace);$i++) { ?>
											<option value="<?= $arrStoresFace[$i]['store_id'] ?>" <?= (isset($_GET['filterSender']) && $_GET['filterSender']==$arrStoresFace[$i]['store_id']) ? 'selected="selected"' : ''; ?>><?= ucwords(str_replace(['ali','mw','sm','-'], ['ALI','MW','SM',' '], strtolower($arrStoresFace[$i]['store_name']))) ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="LAB NAME">
										<?php for ($i=0;$i<sizeof($arrLab);$i++) { ?>
											<option value="<?= $arrLab[$i]['lab_id'] ?>" <?= (isset($_GET['filterSender']) && $_GET['filterSender']==$arrLab[$i]['lab_id']) ? 'selected="selected"' : ''; ?>><?= ucwords(str_replace('mtc', 'MTC', str_replace('-', ' ', $arrLab[$i]['lab_name']))) ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="OTHERS">
										<option value="hq" <?= (isset($_GET['filterSender']) && $_GET['filterSender']=='hq') ? 'selected="selected"' : ''; ?>>Sunnies HQ</option>
										<option value="warehouse" <?= (isset($_GET['filterSender']) && $_GET['filterSender']=='warehouse') ? 'selected="selected"' : ''; ?>>Warehouse</option>
										<option value="warehouse_qa" <?= (isset($_GET['filterSender']) && $_GET['filterSender']=='warehouse_qa') ? 'selected="selected"' : ''; ?>>Warehouse QA</option>
										<option value="warehouse_damage" <?= (isset($_GET['filterSender']) && $_GET['filterSender']=='warehouse_damage') ? 'selected="selected"' : ''; ?>>Warehouse Damage</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="d-flex align-items-center">
								<p class="mr-2">To:</p>
								<select name="filterReceiver" id="filterReceiver" class="form-control select2">
									<option value="">All Branch</option>
									<optgroup label="STORE NAME">
										<?php for ($i=0;$i<sizeof($arrStoresFace);$i++) { ?>
											<option value="<?= $arrStoresFace[$i]['store_id'] ?>" <?= (isset($_GET['filterReceiver']) && $_GET['filterReceiver']==$arrStoresFace[$i]['store_id']) ? 'selected="selected"' : ''; ?>><?= ucwords(str_replace(['ali','mw','sm','-'], ['ALI','MW','SM',' '], strtolower($arrStoresFace[$i]['store_name']))) ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="LAB NAME">
										<?php for ($i=0;$i<sizeof($arrLab);$i++) { ?>
											<option value="<?= $arrLab[$i]['lab_id'] ?>" <?= (isset($_GET['filterReceiver']) && $_GET['filterReceiver']==$arrLab[$i]['lab_id']) ? 'selected="selected"' : ''; ?>><?= ucwords(str_replace('mtc', 'MTC', str_replace('-', ' ', $arrLab[$i]['lab_name']))) ?></option>
										<?php } ?>
									</optgroup>
									<optgroup label="OTHERS">
										<option value="hq" <?= (isset($_GET['filterReceiver']) && $_GET['filterReceiver']=='hq') ? 'selected="selected"' : ''; ?>>Sunnies HQ</option>
										<option value="warehouse" <?= (isset($_GET['filterReceiver']) && $_GET['filterReceiver']=='warehouse') ? 'selected="selected"' : ''; ?>>Warehouse</option>
										<option value="warehouse_qa" <?= (isset($_GET['filterReceiver']) && $_GET['filterReceiver']=='warehouse_qa') ? 'selected="selected"' : ''; ?>>Warehouse QA</option>
										<option value="warehouse_damage" <?= (isset($_GET['filterReceiver']) && $_GET['filterReceiver']=='warehouse_damage') ? 'selected="selected"' : ''; ?>>Warehouse Damage</option>
									</optgroup>
								</select>
							</div>
						</div>
					</div>

					<!-- status -->
					<p class="text-uppercase font-bold text-primary mt-4">status</p>
					<div class="row mt-3 transaction-status">
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterStatus" type="radio" id="optionWaiting" value="waiting for pickup" <?= (isset($_GET['filterStatus']) && $_GET['filterStatus']=='waiting for pickup') ? 'checked="checked"' : '' ?>>
								<label for="optionWaiting" class="custom_checkbox"></label>
								<label for="optionWaiting" class="m-0 ml-2">Waiting for pickup</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterStatus" type="radio" id="optionInTransit" value="in transit" <?= (isset($_GET['filterStatus']) && $_GET['filterStatus']=='in transit') ? 'checked="checked"' : '' ?>>
								<label for="optionInTransit" class="custom_checkbox"></label>
								<label for="optionInTransit" class="m-0 ml-2">In transit</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterStatus" type="radio" id="optionReceived" value="received" <?= (isset($_GET['filterStatus']) && $_GET['filterStatus']=='received') ? 'checked="checked"' : '' ?>>
								<label for="optionReceived" class="custom_checkbox"></label>
								<label for="optionReceived" class="m-0 ml-2">Received</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterStatus" type="radio" id="optionRequested" value="requested" <?= (isset($_GET['filterStatus']) && $_GET['filterStatus']=='requested') ? 'checked="checked"' : '' ?>>
								<label for="optionRequested" class="custom_checkbox"></label>
								<label for="optionRequested" class="m-0 ml-2">requested</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterStatus" type="radio" id="optionAllStatus" value="all" <?= ( (isset($_GET['filterStatus']) && $_GET['filterStatus']=='all') || !isset($_GET['filterStatus']) ? 'checked="checked"' : '' ) ?>>
								<label for="optionAllStatus" class="custom_checkbox"></label>
								<label for="optionAllStatus" class="m-0 ml-2">All</label>
							</div>
						</div>
					</div>

					<div class="mt-4" id="showDateTime">
						<p class="text-uppercase font-bold text-primary">Select date Range</p>
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

														$xy = "";
														if ($i<9) {
															$xy = "0";
														} else {
															$xy = "";
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

														$xy = "";
														if ($i<9) {
															$xy = "0";
														} else {
															$xy = "";
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

							<a href="/inventory/dashboard/history/" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline ml-sm-3">
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
							<p class="h3 font-bold">Showing Data ( <?= sizeof($arrReceivable) ?> )</p>
							<p class="text-secondary mt-2"><?= $date_title ?></p>
						</section>
					</div>
				</div>
				<form method="GET" class="d-flex" style="width:500px">
					<input type="text"name="ref_num" class="search form-control col" placeholder="Search reference number" value="<?= $_GET['ref_num']!='' ? $_GET['ref_num'] : '' ?>">
					<button type="submit" class="btn btn-primary ml-3">search</button>
				</form>
				<a href="#" id="toggle-filter"><img src="<?= get_url('images/icons/icon-filter.png') ?>" width="30" alt="filter" class="img-fluid"></a>
			</div>

			<div id="inventory-receive" class="mt-4">

				<?php 
				// print_r($arrVariance);
				 if ( !empty($arrVariance) ) { ?>
					
					<div class="table-default table-responsive">
						<table class="table table-striped mb-0">
							<thead>
								<tr>
									<th class="small text-white text-uppercase">type</th>
									<th class="small text-white text-uppercase">from</th>
									<th class="small text-white text-uppercase">to</th>
									<th class="small text-white text-uppercase">reference number</th>
									<th class="small text-white text-uppercase">date sent</th>
									<th class="small text-white text-uppercase"></th>
								</tr>
							</thead>
							<tbody>

							<?php for($i=0;$i<sizeof($arrVariance);$i++){ ?>
								<tr>
								<td nowrap class=" text-uppercase"><?= ucwords(str_replace("_"," ",$arrVariance[0]["type"])) ?></td>
									<td nowrap class=""><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],$arrVariance[$i]['stock_from_branch'])) ?></td>
									<td nowrap class=""><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],$arrVariance[$i]['store_to_name'])) ?></td>
									<td nowrap class=""><?= $arrVariance[$i]['reference_number'] ?></td>
									<td nowrap class=""><?= cvdate2($arrVariance[$i]['date_created']) ?></td>
									<td nowrap class=""><a href="view/?ref_num=<?= $arrVariance[$i]['reference_number'] ?>" class="text-primary text-uppercase font-bold">view</a></td>	
								</tr>
							<?php } ?>
							</tbody>
						</table>
					</div>

				<?php  } else { ?>
				
					<div class="text-center p-4 mt-4">
						<h4>You don't have any variance report</h4>
					</div>
				
				<?php  } ?>
				
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
		})

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

	})

</script>

<?= get_footer() ?>