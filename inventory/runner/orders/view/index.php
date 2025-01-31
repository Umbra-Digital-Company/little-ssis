<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'runner';
$page_url = 'stock-movement';

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
require $sDocRoot."/inventory/includes/grab_all_moving_stock_specific.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

// Grab Store
$storeName = "";

for ($i=0; $i < sizeOf($arrStore); $i++) { 

	if($arrStore[$i]['store_id'] == $_SESSION['user_login']['store_code']) {

		$storeName = $arrStore[$i]['store_name'];

	};
	
};

?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<div id="inventory-receive">
			
				<form action="/inventory/process/runner/pick_up.php" method="POST" autocomplete="off">
					<input type="hidden" name="ref_num" value="<?= $_GET['ref_num'] ?>">
					<div class="row align-items-start">
						<div class="col-12 col-lg-8">
							<div class="custom-card">
								<div class="row">
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">From</h5>
										<p class="h6 large"><?= ucwords(str_replace(['ali','sm','mw','mtc','hq','-'],['ALI','SM','MW','MTC','HQ',' '],strtolower($arrReceivable[0]['stock_from_branch']))) ?></p>
									</div>
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">To</h5>
										<p class="h6 large"><?= ucwords(str_replace(['ali','sm','mw','mtc','hq','-'],['ALI','SM','MW','MTC','HQ',' '],strtolower($arrReceivable[0]['stock_to_name']))) ?></p>
									</div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th class="small text-white text-uppercase">Item Name</th>
												<th class="small text-white text-uppercase text-center">Transferred Count</th>
												<th class="small text-white text-uppercase text-center">pickup Count</th>
											</tr>
										</thead>
										<tbody>
										
											<?php for ($i=0; $i < sizeOf($arrReceivable); $i++) {

												echo '<tr>';
												echo 	'<td>'.$arrReceivable[$i]['product_style'].$arrReceivable[$i]['product_color'].'<span class="d-block text-secondary">'.$arrReceivable[$i]['product_code'].'</span></td>';
												echo 	'<td style="vertical-align:middle!important;" class="text-center">'.$arrReceivable[$i]['count'].'</td>';
												echo 	'<td style="width:180px"><input class="form-control" name="pickup_count[]" placeholder="#" required></td>';
												echo 	'<input type="hidden" class="form-control" name="pickup_order_id[]" value="'.$arrReceivable[$i]['delivery_unique'].'">';
												echo '</tr>';

											}; ?>

										</tbody>
									</table>
								</div>
							</div>
						</div>
						<div class="col-12 col-lg-4">

							<p class="text-primary text-uppercase font-bold">order details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Transaction</h5>
								<input type="hidden" name="type" value="<?= $arrReceivable[0]['type'] ?>">
								<p class="h6 large"><?= ucwords(str_replace('_',' ',$arrReceivable[0]['type'])) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Reference Number</h5>
								<input type="hidden" name="reference_number" value="<?= $arrReceivable[0]['reference_number'] ?>" readonly>
								<p class="h6 large"><?= strtoupper($arrReceivable[0]['reference_number']) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Reference Number</h5>
								<p class="h6 large"><?= cvdate2($arrReceivable[0]['date_created']) ?></p>
							</div>
					
							<?php if($arrReceivable[0]['type'] == 'damage') { ?>

								<div class="mt-3">
									<h5 class="text-secondary font-bold font-bold">Reason</h5>
									<p><?= $arrReceivable[0]['reason'] ?></p>
								</div>

							<?php } ?>
							
							<?php if($arrReceivable[0]['remarks'] != '') { ?>

								<div class="mt-3">
									<h5 class="text-secondary font-bold font-bold">Remarks</h5>
									<p><?= $arrReceivable[0]['remarks'] ?></p>
								</div>

							<?php } ?>

							<p class="text-primary text-uppercase font-bold mt-5">runner details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Full name</h5>
								<input type="text" name="runner_name" class="form-control" placeholder="Full name">
							</div>
							<div class="mt-3">
								<input type="hidden" name="runner_signature" class="signature64" value="">
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
								<div class="d-flex justify-content-center">
									<button type="submit" class="btn btn-primary mx-3" id="confirmForm" disabled>pick up</button>
									<a href="/inventory/runner/orders/"><button type="button" class="btn btn-secondary mx-3">Exit</button></a>
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

<?= get_footer() ?>