<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();


////////////////////////////////////////////////

$page = 'admin';
$page_url = 'face-current-request';

$filter_page = 'current_request_admin_face';
$group_name = 'aim_face';
////////////////////////////////////////////////

// Set access for Admin and Store account
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
require $sDocRoot."/face/includes/grab_stores_face.php";
if ( isset($_GET['ref_num']) && $_GET['ref_num'] != '' ) {

	require $sDocRoot."/face/inventory/includes/grab_all_moving_stock_specific_face.php";

} else {

	require $sDocRoot."/face/inventory/includes/grab_all_for_approval_face.php";

}

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
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

			
			
			<!-- <div class="d-flex no-gutters align-items-center" id="data-filter">
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
			</div> -->

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
											<td nowrap class=""><a href="view/?ref_num=<?= $arrReceivable[$i]['reference_number'] ?>" class="text-primary text-uppercase font-bold">edit</a></td>
										</tr>

									<?php } ?>

								</tbody>
							</table>
						</div>

					<?php } else { ?>
					
						<div class="text-center p-4 mt-4">
							<h4>You don't have any for approval to edit</h4>
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
						<a href="/face/inventory/admin/face-request/" class="text-uppercase text-danger">clear search</a>
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