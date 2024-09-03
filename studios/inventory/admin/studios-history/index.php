<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'history-admin-studios';
$page_url = 'history-admin-studios';

$filter_page = 'history_admin_studios';
$group_name = 'aim_studios';

////////////////////////////////////////////////

// // Set access for Admin and Store account
// if($_SESSION['user_login']['userlvl'] != '13') {

// 	header('location: /');
// 	exit;

// };

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";

if ( isset($_GET['ref_num']) && $_GET['ref_num'] != '' ) {

	require $sDocRoot."/inventory/includes/grab_all_moving_stock_specific_studios.php";

} else {

	require $sDocRoot."/inventory/includes/grab_all_moving_stock_hq_studios.php";

}

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v4.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$filter_page

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

					<!-- status -->
					<p class="text-uppercase font-bold text-primary mt-4">status</p>
					<div class="row mt-3 transaction-status">
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterStatus" type="radio" id="optionApproval" value="for approval" <?= (isset($_GET['filterStatus']) && $_GET['filterStatus']=='for approval') ? 'checked="checked"' : '' ?>>
								<label for="optionApproval" class="custom_checkbox"></label>
								<label for="optionApproval" class="m-0 ml-2">For Approval</label>
							</div>
						</div>
						<div class="col-12 col-md-6 mb-2">
							<div class="d-flex align-items-center">
								<input class="sr-only checkbox" name="filterStatus" type="radio" id="optionRequested" value="requested" <?= (isset($_GET['filterStatus']) && $_GET['filterStatus']=='requested') ? 'checked="checked"' : '' ?>>
								<label for="optionRequested" class="custom_checkbox"></label>
								<label for="optionRequested" class="m-0 ml-2">Requested</label>
							</div>
						</div>
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

							<a href="/studios/inventory/admin/studios-history" class="ml-0 mt-3 mt-sm-0 mt-sm-3 d-block d-sm-inline ml-sm-3">
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

				<?php if ( !isset($_GET['ref_num']) ) { ?>

					<?php if ( !empty($arrReceivable) ) { ?>
						
						<div class="table-default table-responsive">
							<table class="table table-striped mb-0">
								<thead>
									<tr>
										<!-- <th class="small text-white text-uppercase"></th>
										<th class="small text-white text-uppercase">type</th> -->
										<th class="small text-white text-uppercase">from</th>
										<th class="small text-white text-uppercase">to</th>													
										<th class="small text-white text-uppercase">total Sent</th>
										<th class="small text-white text-uppercase">total Received</th>
										<th class="small text-white text-uppercase">status</th>
										<th class="small text-white text-uppercase">reference number</th>
										<th class="small text-white text-uppercase">date sent</th>
										<th class="small text-white text-uppercase"></th>
									</tr>
								</thead>
								<tbody>

									<?php for ( $i=0; $i<sizeof($arrReceivable); $i++ ) { ?>

										<tr>
											<!-- <td nowrap class=" text-uppercase text-center text-<?= $arrReceivable[$i]['direction'] == 'out' ? 'danger' : 'success' ?>"><?= $arrReceivable[$i]['direction'] ?></td>
											<td nowrap class=""><?= ucwords(str_replace("_", " ", $arrReceivable[$i]['type'])) ?></td> -->
											<td nowrap class=""><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[$i]['stock_from_branch']))) ?></td>
											<td nowrap class=""><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[$i]['stock_to_name']))) ?></td>
											<td nowrap class=""><?= $arrReceivable[$i]['total_items'] ?></td>
											<td nowrap class=""><?= $arrReceivable[$i]['total_items_received'] ?></td>
											<td nowrap class=""><?= ucwords($arrReceivable[$i]['status']) ?></td>
											<td nowrap class=""><?= $arrReceivable[$i]['reference_number'] ?></td>
											<td nowrap class=""><?= cvdate2($arrReceivable[$i]['date_created']) ?></td>
											<td nowrap class=""><a href="view/?ref_num=<?= $arrReceivable[$i]['reference_number'] ?>" class="text-primary text-uppercase font-bold">view</a></td>
										</tr>

									<?php } ?>

								</tbody>
							</table>
						</div>

					<?php } else { ?>
					
						<div class="text-center p-4 mt-4">
							<h4>You don't have any pending to receive</h4>
						</div>
					
					<?php } ?>

				<?php } else { ?>
				
					<?php if ( !empty($arrReceivable) ) { ?>
						
						<div class="table-default table-responsive">
							<table class="table table-striped mb-0">
								<thead>
									<tr>
										<!-- <th class="small text-white text-uppercase"></th>
										<th class="small text-white text-uppercase">type</th> -->
										<th class="small text-white text-uppercase">from</th>
										<th class="small text-white text-uppercase">to</th>													
										<th class="small text-white text-uppercase">total Sent</th>
										<th class="small text-white text-uppercase">total Received</th>
										<th class="small text-white text-uppercase">status</th>
										<th class="small text-white text-uppercase">reference number</th>
										<th class="small text-white text-uppercase">date sent</th>
										<th class="small text-white text-uppercase"></th>
									</tr>
								</thead>
								<tbody>

									<tr>
										<!-- <td nowrap class=" text-uppercase text-center text-<?= $arrReceivable[0]['direction'] == 'out' ? 'danger' : 'success' ?>"><?= $arrReceivable[0]['direction'] ?></td>
										<td nowrap class=""><?= ucwords(str_replace("_", " ", $arrReceivable[0]['type'])) ?></td> -->
										<td nowrap class=""><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[0]['stock_from_branch']))) ?></td>
										<td nowrap class=""><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[0]['stock_to_name']))) ?></td>
										<td nowrap class=""><?= $arrReceivable[0]['total_items'] ?></td>
										<td nowrap class=""><?= $arrReceivable[0]['total_items_received'] ?></td>
										<td nowrap class=""><?= ucwords($arrReceivable[0]['status']) ?></td>
										<td nowrap class=""><?= $arrReceivable[0]['reference_number'] ?></td>
										<td nowrap class=""><?= cvdate2($arrReceivable[0]['date_created']) ?></td>
										<td nowrap class=""><a href="view/?ref_num=<?= $arrReceivable[0]['reference_number'] ?>" class="text-primary text-uppercase font-bold">view</a></td>
									</tr>

								</tbody>
							</table>
						</div>

					<?php } else { ?>
					
						<div class="text-center p-4 mt-4">
							<h4>Sorry, transaction can't be found</h4>
						</div>
					
					<?php } ?>

					<div class="mt-5 text-center">
						<a href="/studios/inventory/admin/studios-history" class="text-uppercase text-danger">clear search</a>
					</div>
				
				<?php } ?>

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