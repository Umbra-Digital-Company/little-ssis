<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'admin';
$page_url = 'variance-report';

$filter_page = 'variance_report_admin';
$group_name = 'main_menu';
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
require $sDocRoot."/includes/grab_stores.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v2.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

// Grab Store
$storeName = "";
$MergeStores=array_merge($arrStore, $arrStudiosStore);


// echo $MergeStores[110]['store_id'];

// echo "<pre>";
// print_r($MergeStores);

for ($i=0; $i < sizeOf($MergeStores); $i++) { 

	if($MergeStores[$i]['store_id'] == $_SESSION['user_login']['store_code'] ) {

		$storeName = $MergeStores[$i]['store_name'];

	};
	
};

?>

<?php if ( isset($_GET['ref_num']) && $_GET['ref_num'] != '' ) { ?>

	<?= get_header($page_url) ?>

	<div class="row no-gutters align-items-strech flex-nowrap">

		<?= get_sidebar($page_url,$page) ?>

		<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
				
			<?= get_topbar($page_url) ?>

			<div class="ssis-content">

				<div class="order-details">

					<div class="row align-items-start">
						<form class="col-12 col-lg-8" action="#" method="POST">
							<div class="custom-card">
								<div class="row no-gutters">
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">From</h5>
										<p class="h6 large">Sender</p>
									</div>
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">To</h5>
										<p class="h6 large">Receiver</p>
									</div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th class="small text-white text-uppercase">Item Name</th>
												<th class="small text-white text-uppercase text-center">Sent</th>
												<th class="small text-white text-uppercase text-center">Received</th>
												<th class="small text-white text-uppercase text-center">Variance</th>

												<?php 
													// if the transaction type is OUT ( you sent an item ) you can approve or reject any variance
													// *** sample condition ***
													if ( $_GET['ref_num']=='0987654321' ) { ?>

													<th class="small text-white text-uppercase text-center">Approve</th>
													<th class="small text-white text-uppercase text-center">Reject</th>
												
												<?php } ?>

											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Aalto Gold</td>
												<td align="center">10</td>
												<td align="center">0</td>
												<td align="center">-10</td>

												<?php 
													// if the transaction type is OUT ( you sent an item ) you can approve or reject any variance
													// *** sample condition ***
													if ( $_GET['ref_num']=='0987654321' ) { ?>

													<td align="center">
														<div class="d-flex align-items-center justify-content-center">
															<input class="sr-only checkbox" name="variance_report[1234567890_1]" type="radio" id="variance_approve_1234567890_1" value="approve">
															<label for="variance_approve_1234567890_1" class="custom_checkbox"></label>
														</div>
													</td>
													<td align="center">
														<div class="d-flex align-items-center justify-content-center">
															<input class="sr-only checkbox" name="variance_report[1234567890_1]" type="radio" id="variance_reject_1234567890_1" value="reject">
															<label for="variance_reject_1234567890_1" class="custom_checkbox"></label>
														</div>
													</td>

												<?php } ?>
											</tr>
											<tr>
												<td>Aalto Silver</td>
												<td align="center">0</td>
												<td align="center">10</td>
												<td align="center">10</td>
												<?php 
													// if the transaction type is OUT ( you sent an item ) you can approve or reject any variance
													// *** sample condition ***
													if ( $_GET['ref_num']=='0987654321' ) { ?>

													<td align="center">
														<div class="d-flex align-items-center justify-content-center">
															<input class="sr-only checkbox" name="variance_report[1234567890_2]" type="radio" id="variance_approve_1234567890_2" value="approve">
															<label for="variance_approve_1234567890_2" class="custom_checkbox"></label>
														</div>
													</td>
													<td align="center">
														<div class="d-flex align-items-center justify-content-center">
															<input class="sr-only checkbox" name="variance_report[1234567890_2]" type="radio" id="variance_reject_1234567890_2" value="reject">
															<label for="variance_reject_1234567890_2" class="custom_checkbox"></label>
														</div>
													</td>

												<?php } ?>
											</tr>
										</tbody>
									</table>
								</div>
							</div>

							<?php 
								// if the transaction type is OUT ( you sent an item ) you can approve or reject any variance
								// *** sample condition ***
								if ( $_GET['ref_num']=='0987654321' ) { ?>

								<div class="text-right mt-4">
									<button type="submit" class="btn btn-primary">submit report</button>
								</div>

							<?php } ?>
							
						</form>
						<div class="col-12 col-lg-4 mt-5 mt-lg-0">
							<p class="text-primary text-uppercase font-bold">order details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Transaction</h5>
								<p class="h6 large">Stock Transfer</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Reference Number</h5>
								<p class="h6 large">1234567890</p>
							</div>

							<p class="text-primary text-uppercase font-bold mt-5">sender details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Date Sent</h5>
								<p class="h6 large">Jan 20, 2020 - 10:00 AM</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Authorized by</h5>
								<p class="h6 large">
									Warehouse User
								</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<div class="custom-card mt-3">
									<img class="img-fluid center-block" src="">
								</div>
							</div>
							

							<p class="text-primary text-uppercase font-bold mt-5">recipient details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Date Received</h5>
								<p class="h6 large">Jan 20, 2020 - 4:00 PM</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Received by</h5>
								<p class="h6 large">
									Store User
								</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<div class="custom-card mt-3">
									<img class="img-fluid center-block" src="">
								</div>
							</div>
							
						</div>
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
			})

		})
		
	</script>

	<?= get_footer() ?>

<?php } else {

	header('Location: /inventory/admin/reports/');
	exit;
	
} ?>