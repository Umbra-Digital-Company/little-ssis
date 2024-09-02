<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'runner';
$page_url = 'stock-movement';

$filter_page = 'stock_movement_runner';
$group_name = 'main_menu';

////////////////////////////////////////////////

// Set access for Admin and Store account
if($_SESSION['user_login']['userlvl'] != '11') {

	header('location: /');
	exit;

};

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_moving_stock_runners.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
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
						<p class="text-uppercase font-bold text-primary mt-4">select laboratory</p>
						<div class="row mt-3 lab-form">

							<?php

								// Grab all labs
								$arrLabs = grabLabs();

								// Cycle through labs array
								for ($i=0; $i < sizeOf($arrLabs); $i++) { 

									// if($arrLabs[$i]['active'] == 'y') {

										$curLabName = ucwords(str_replace("mtc", "MTC", str_replace("-", " ", $arrLabs[$i]['lab_name'])));
										$checked = checkFilter($arrLabs[$i]['lab_id']);

										echo 	'<div class="col-12 col-md-6 mb-2">
													<div class="d-flex align-items-center">
														<input class="sr-only checkbox" name="filterLabs[]" type="checkbox" id="option'.$curLabName.'" value="'.$arrLabs[$i]['lab_id'].'" '.$checked.'>
														<label for="option'.$curLabName.'" class="custom_checkbox"></label>
														<label for="option'.$curLabName.'" class="m-0 ml-2">'.$curLabName.'</label>
													</div>
												</div>';

									// };

								};

								// Echo select all option
								echo 	'<div class="col-12 col-md-6">
											<div class="d-flex align-items-center">
												<input class="sr-only checkbox" type="checkbox" id="optionAllLabs" value="all-labs" '.( ($_GET['lab'] == $arrLabs[$i]['lab_id'] ? "selected" : "") ).'>
												<label for="optionAllLabs" class="custom_checkbox"></label>
												<label for="optionAllLabs" class="m-0 ml-2" id="optionAllLabsLabel">All Laboratory</label>
											</div>
										</div>';

							?>

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

			<div class="d-flex no-gutters justify-content-end align-items-center" id="data-filter">
				<?php if(isset($_GET['date'])) { ?>

					<a href="/dashboard/" class="mr-3 d-none d-lg-block">
						<button class="btn btn-danger" type="button">reset filter</button>
					</a>

				<?php }; ?>
				<a href="#" id="toggle-filter"><img src="<?= get_url('images/icons/icon-filter.png') ?>" width="30" alt="filter" class="img-fluid"></a>
			</div>

			<div class="mt-4" id="inventory-receive">
				
				<?php if ( !empty($arrReceivable) ) { ?>
					
					<div class="table-default table-responsive" style="max-height: 500px;">
						<table class="table table-hover mb-0">
							<thead>
								<tr>
									<th class="small">from</th>
									<th class="small">to</th>													
									<th class="small">total items</th>
									<th class="small">status</th>
									<th class="small">reference number</th>
									<th class="small">date</th>
									<th class="small"></th>
								</tr>
							</thead>
							<tbody>

								<?php for ( $i=0; $i<sizeof($arrReceivable); $i++ ) { ?>

									<tr>
										<td nowrap><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[$i]['stock_from_branch']))) ?></td>
										<td nowrap><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],strtolower($arrReceivable[$i]['stock_to_name']))) ?></td>
										<td nowrap><?= $arrReceivable[$i]['total_items'] ?></td>
										<td nowrap><?= ucwords($arrReceivable[$i]['status']) ?></td>
										<td nowrap><?= strtoupper($arrReceivable[$i]['reference_number']) ?></td>
										<td nowrap><?= cvdate2($arrReceivable[$i]['date_created']) ?></td>
										<td nowrap>
										
											<?php if($arrReceivable[$i]['status'] != 'in transit') { ?>

												<a href="view/?ref_num=<?= $arrReceivable[$i]['reference_number'] ?>" class="text-primary text-uppercase font-bold">pick up</a>

											<?php } else { ?>

												<p class="text-center">
													<img src="<?= get_url('images/icons/icon-check-primary.png') ?>" alt="picked up" class="img-fluid">
												</p>

											<?php } ?>
												
										</td>
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

			</div>
		
		</div>

	</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
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

	})

</script>

<?= get_footer() ?>