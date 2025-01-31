<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'warehouse';
$page_url = 'studios-inventory-request';

$filter_page = 'request_warehouse_studios';
$group_name = 'aim_studios';
////////////////////////////////////////////////

// Set access for Admin and Warehouse account
// if($_SESSION['user_login']['userlvl'] != '8') {

// 	header('location: /');
// 	exit;

// }

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/header_v2.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_moving_stock_specific_studios.php";

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

					<form action="/inventory/process/warehouse/studios_transfer_request.php" method="POST" autocomplete="off">
						<div class="row align-items-start">
							<div class="col-12 col-lg-8">
								<div class="custom-card">
									<div class="row">
										<div class="col-6">
											<input type="hidden" name="stock_from_id" class="text-capitalize form-control" value="<?= $_SESSION["store_code"] ?>" readonly>
											<h5 class="text-secondary font-bold font-bold">To</h5>
											<input type="hidden" name="recipient_branch" value="<?= $arrReceivable[0]['stock_to_id'] ?>">
											<input type="text" value="<?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],$arrReceivable[0]['stock_to_name'])) ?>" readonly class="form-control">
										</div>
										<div class="col-6">
											<h5 class="text-secondary font-bold font-bold">Reference Number</h5>
											<input type="text" name="reference_number" value="<?= $arrReceivable[0]['reference_number'] ?>" readonly class="form-control">
										</div>
									</div>
									<div class="table-responsive mt-4">
										<table class="table table-striped table-inventory mb-0">
											<thead>
												<tr>
													<th class="small text-white text-uppercase">Item Name</th>
													<th class="small text-white text-uppercase text-center">Requested</th>
													<th class="small text-white text-uppercase text-center">Count</th>
													<th class="small text-white text-uppercase">Remarks</th>
												</tr>
											</thead>
											<tbody>

												<?php for ($i=0; $i < sizeOf($arrReceivable); $i++) { ?>
													
													<tr>
														<th>
															<input type="hidden" name="frame_code[]" value="<?= $arrReceivable[$i]['product_code'] ?>" readonly>
															<input type="hidden" name="delivery_unique[]" value="<?= $arrReceivable[$i]['delivery_unique'] ?>" readonly>
															<?= ucwords(strtolower($arrReceivable[$i]['product_style'].$arrReceivable[$i]['product_color'])) ?>
															<span class="text-secondary small d-block"><?= $arrReceivable[$i]['product_code'] ?></span>
														</th>
														<td  align="center"style="vertical-align:middle;min-width:100px;max-width:100px;">
															<?= $arrReceivable[$i]['count'] ?>
														</td>
														<td style="vertical-align:middle;min-width:100px;max-width:100px;">
															<input min="0" type="number" name="frame_count[]" class="form-control" value="<?= $arrReceivable[$i]['count'] ?>" placeholder="#">
														</td>
														<td style="vertical-align:middle;min-width:300px;">
															<input type="text" name="item_remark[]" class="form-control" placeholder="Remarks for this item" value="<?= $arrReceivable[$i]['item_remarks'] ?>">
														</td>
													</tr>

												<?php } ?>

											</tbody>
											<tfoot>
												<th class="small text-white text-uppercase">Total</th>
												<th class="small text-white text-uppercase text-center"></th>
												<th class="small text-white text-uppercase text-center"><?= array_sum(array_column($arrReceivable, 'count')); ?></th>
												<th class="small text-white text-uppercase text-center" id="total-transfer-calc"><?= array_sum(array_column($arrReceivable, 'count')); ?></th>
											</tfoot>
										</table>
									</div>
								</div>
							</div>
							<div class="col-12 col-lg-4 mt-5 mt-lg-0">
								<p class="text-primary text-uppercase font-bold">requested by</p>
								<div class="mt-4">
									<h5 class="text-secondary font-bold">Authorized by</h5>
									<p>Admin</p>
								</div>

								<?php if ( $arrReceivable[0]['remarks'] != '' ) { ?>
						
									<div class="mt-3">
										<h5 class="text-secondary font-bold">Remarks</h5>
										<p class="h6 large"><?= $arrReceivable[0]['remarks'] ?></p>
									</div>

								<?php } ?>

								<div class="mt-3">
									<h5 class="text-secondary font-bold">Signature</h5>
									<div class="custom-card">
										<img src="<?= $arrReceivable[0]['admin_signature'] != '' ? ucwords($arrReceivable[0]['admin_signature']) : ucwords($arrReceivable[0]['sender_signature'])  ?>" alt="" class="img-fluid">
									</div>
								</div>
								<p class="text-primary text-uppercase font-bold mt-5">sender details</p>
								<div class="mt-4">
									<h5 class="text-secondary font-bold">Employee Name</h5>
									<input type="hidden" name="emp_id" value="<?= $_SESSION['user_login']['store_code'] ?>">
									<input type="text" name="emp_name" class="form-control" placeholder="Full name">
								</div>
								<div class="mt-3">
									<h5 class="text-secondary font-bold">Additional message</h5>
									<textarea name="sender_remarks" id="senderRemarks" class="form-control textarea"></textarea>
								</div>
								<div class="mt-3">
									<h5 class="text-secondary font-bold">Signature</h5>
									<input type="hidden" name="signature" class="signature64" value="">
									<div class="custom-card">
										<div class="canvas-holder text-center">
											<canvas id="thecanvas" width="475" height="148" border="1"></canvas>
											<div class="row no-gutters justify-content-center mt-3">
												<a href="#" class="text-uppercase save text-success pl-3 pr-3 d-block">save</a>
												<a href="#" class="text-uppercase clear text-danger pl-3 pr-3 d-block">clear</a>
											</div>
										</div>
									</div>
								</div>
								<div class="mt-5">
									<div class="d-flex justify-content-center">
										<button type="submit" class="btn btn-primary" id="confirmForm" disabled>transfer stock</button>
									</div>
								</div>
							</div>
						</div>
					</form>

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

	header('Location: /inventory/warehouse/studios-request/');
	exit;
	
} ?>