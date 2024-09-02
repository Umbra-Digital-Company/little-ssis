<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'admin';
$page_url = 'studios-receive';

$filter_page = 'receive_admin_studios';
$group_name = 'aim_studios';

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

// Check for GET 
if(isset($_GET['id']) && $_GET['id'] != '') {

	require $sDocRoot."/inventory/includes/grab_all_receivable_specific_studios.php";

}
else {

	require $sDocRoot."/inventory/includes/grab_all_receivable_studios.php";

};

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

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">
								
			<?php if ( isset($_GET['id']) && $_GET['id'] != '' ) { ?>
				
				<form action="/inventory/process/admin/studios_receive.php" method="POST" id="receive-form">
					<div class="row align-items-start">
						<div class="col-12 col-lg-8">
							<div class="custom-card">
								<div class="row">
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">From</h5>
										<input type="hidden" name="stoc_from_id" value="<?= $arrReceivableItems[0]['stock_from_id'] ?>" readonly>
										<input type="text" name="stock_from_branch" class="form-control" value="<?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],$arrReceivableItems[0]['stock_from_branch'])) ?>" readonly>
									</div>
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">To</h5>
										<input type="hidden" name="stock_to_id" value="<?= $_SESSION['user_login']['store_code'] ?>">
										<input class="form-control" value="Sunnies HQ" readonly>
									</div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table table-striped mb-0" id="multiple-item-row">
										<thead>
											<tr>
												<th class="small text-white text-uppercase">Item Name</th>
												<th class="small text-white text-uppercase">Remarks</th>
												<th class="small text-white text-uppercase text-center">Transferred Count</th>
												<th class="small text-white text-uppercase text-center">Pickup Count</th>
												<th class="small text-white text-uppercase text-center">Received Count</th>
											</tr>
										</thead>
										<tbody>

											<?php for ($i=0; $i < sizeOf($arrReceivableItems); $i++) { ?>

												<tr>
													<td style="vertical-align:middle!important;">
														<input type="hidden" name="frame_code[]" value="<?= $arrReceivableItems[$i]['product_code'] ?>" readonly>
														<input type="hidden" name="delivery_id[]" value="<?= $arrReceivableItems[$i]['delivery_unique'] ?>" readonly>
														<?= ucwords(strtolower($arrReceivableItems[$i]['product_style'].$arrReceivableItems[$i]['product_color'])) ?>
													</td>
													<td style="vertical-align:middle!important;">
														<?= $arrReceivableItems[$i]['item_remarks'] ?>
													</td>
													<td align="center" style="vertical-align:middle!important;">
														<input type="hidden" name="delivered_count[]" class="form-control" value="<?= $arrReceivableItems[$i]['count'] ?>" readonly>
														<?= $arrReceivableItems[$i]['count'] ?>
													</td>
													<td align="center" style="vertical-align:middle!important;">
														<input type="hidden" name="pickup_count[]" class="form-control" value="<?= $arrReceivableItems[$i]['runner_count'] ?>" readonly>
														<?= $arrReceivableItems[$i]['runner_count'] ?>
													</td>
													<td>
														<?php if ($arrReceivableItems[0]['status']=='in transit') { ?>
															<input type="text" name="received_count[]" class="form-control" placeholder="#" value="<?= $arrReceivableItems[$i]['count'] ?>"  required>
														<?php } else { ?>
															&nbsp;
														<?php } ?>
													</td>
												</tr>

											<?php } ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4">
							<p class="text-primary text-uppercase font-bold">order details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Transaction</h5>
								<input type="hidden" name="type" value="<?= $arrReceivableItems[0]['type'] ?>">
								<p class="h6 large"><?= ucwords(str_replace('_',' ',$arrReceivableItems[0]['type'])) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Reference Number</h5>
								<input type="hidden" name="reference_number" value="<?= $arrReceivableItems[0]['reference_number'] ?>" readonly>
								<p class="h6 large"><?= strtoupper($arrReceivableItems[0]['reference_number']) ?></p>
							</div>

							<?php if ( $arrReceivableItems[0]['reason'] != '' ) { ?>
							
								<div class="mt-3">
									<h5 class="text-secondary font-bold">Reason</h5>
									<p class="h6 large"><?= $arrReceivableItems[0]['reason'] ?></p>
								</div>
								
							<?php } ?>

							<?php if ( $arrReceivableItems[0]['remarks'] != '' ) { ?>
							
								<div class="mt-3">
									<h5 class="text-secondary font-bold">Additional message</h5>
									<p class="h6 large"><?= $arrReceivableItems[0]['remarks'] ?></p>
								</div>
								
							<?php } ?>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Remarks</h5>
								<p class="h6 large"><?= ($arrReceivableItems[0]['transaction_reason'] != '') ? $arrReceivableItems[0]['transaction_reason'] : '-' ?></p>
							</div>
							
							<p class="text-primary text-uppercase font-bold mt-5">sender details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Date Sent</h5>
								<p class="h6 large"><?= cvdate2($arrReceivableItems[0]['date_created']) ?></p>
							</div>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Authorized by</h5>
								<p class="h6 large">
									<?php if ($arrReceivableItems[0]['sender_first_name'] == '' && $arrReceivableItems[0]['sender_last_name'] == '') {
										echo ucwords(strtolower($arrReceivableItems[0]['sender_id']));
									} else {
										echo ucwords(strtolower($arrReceivableItems[0]['sender_first_name'].' '.$arrReceivableItems[0]['sender_last_name']));
									} ?>
								</p>
								<!-- <input type="text" name="emp_id" class="form-control" placeholder="ID #" required> -->
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<div class="custom-card mt-3">
									<img class="img-fluid center-block" src="<?= $arrReceivableItems[0]["signature"]; ?>">
								</div>
							</div>

							<?php if ( $arrReceivableItems[0]['status']=='in transit') { ?>

								<p class="text-primary text-uppercase font-bold mt-5">recipient details</p>
								<div class="mt-4">
									<h5 class="text-secondary font-bold">Employee Name</h5>
									<input type="hidden" name="emp_id" value="overseer">
									<input type="text" name="emp_name" class="form-control" placeholder="Full name" required>
								</div>
								<div class="mt-3">
									<input type="hidden" name="signature" class="signature64" value="">
									<div class="custom-card">
										<div class="canvas-holder text-center">
											<canvas id="thecanvas" width="475" height="148" border="0"></canvas>
											<div class="row no-gutters justify-content-center mt-3">
												<a href="#" class="text-uppercase save text-success pl-3 pr-3 d-block">save</a>
												<a href="#" class="text-uppercase clear text-danger pl-3 pr-3 d-block">clear</a>
											</div>
										</div>
									</div>
								</div>
								<div class="mt-5">
									<div class="d-flex justify-content-end">
										<a href="/inventory/admin/studios-receive/" class="mr-3"><button type="button" class="btn btn-secondary">Exit</button></a>
										<button type="submit" class="btn btn-primary" id="confirmForm" disabled>receive</button>
									</div>
								</div>

							<?php } ?>

						</div>
					</div>
				</form>

			<?php } else {

				if ( !empty($arrReceivable) ) { ?>

					<div id="inventory-receive">
					
						<div class="table-default table-responsive">
							<table class="table table-striped mb-0">
								<thead>
									<tr>
										<th class="small text-white text-uppercase">from</th>
										<th class="small text-white text-uppercase">to</th>													
										<th class="small text-white text-uppercase">total items</th>
										<th class="small text-white text-uppercase">reference number</th>
										<th class="small text-white text-uppercase">status</th>
										<th class="small text-white text-uppercase">date sent</th>
										<th class="small text-white text-uppercase"></th>
									</tr>
								</thead>
								<tbody>

									<?php for ( $i=0; $i<sizeof($arrReceivable); $i++ ) { ?>

										<tr>
											<td nowrap class=""><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],$arrReceivable[$i]['stock_from_branch'])) ?></td>
											<td nowrap class="">Sunnies HQ</td>
											<td nowrap class=""><?= $arrReceivable[$i]['total_items_pickup']=='' ? $arrReceivable[$i]['total_items'] : $arrReceivable[$i]['total_items_pickup'] ?></td>
											<td nowrap class=""><?= $arrReceivable[$i]['reference_number'] ?></td>
											<td nowrap class=""><?= ucwords($arrReceivable[$i]['status']) ?></td>
											<td nowrap class=""><?= cvdate2($arrReceivable[$i]['date_created']) ?></td>
											<td nowrap class=""><a href="/inventory/admin/studios-receive/?id=<?= $arrReceivable[$i]['reference_number'] ?>" class="text-success text-uppercase font-weight-bold"><?= $arrReceivable[$i]['status']=='in transit' ? 'receive' : 'view' ?></a></td>
										</tr>

									<?php } ?>

								</tbody>
							</table>
						</div>

					</div>

				<?php } else { ?>
				
					<div class="text-center p-4 mt-4">
						<h4>You don't have any pending to receive</h4>
						<!-- <a href="/warehouse/pullout/" class="btn-primary text-uppercase mt-3 d-inline-block pt-3 pb-3 pr-4 pl-4">request now</a> -->
					</div>
				
				<?php }

			} ?>

		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js"></script>

<?= get_footer() ?>
