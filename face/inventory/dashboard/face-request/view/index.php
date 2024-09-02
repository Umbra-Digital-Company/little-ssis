<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'dashboard';
$page_url = 'request-approval';

$filter_page = 'request_dashboard_studios';
$group_name = 'aim_studios';
////////////////////////////////////////////////

// Set access for Admin and Warehouse account
if($_SESSION['user_login']['userlvl'] != '1') {

	header('location: /');
	exit;

}

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_moving_stock_specific_studios.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $_GET['page']; 

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

			<div class="order-details">

				<div class="row align-items-start">
					<div class="col-12 col-lg-8">
						<div class="custom-card">
							<div class="row no-gutters">
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">From</h5>
									<p class="h6 large"><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],$arrReceivable[0]['stock_from_branch'])) ?></p>
								</div>
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">To</h5>
									<p class="h6 large"><?= ucwords(str_replace(['mw','ali','mtc','sm','hq','-'],['MW','ALI','MTC','SM','HQ',' '],$arrReceivable[0]['stock_to_name'])) ?></p>
								</div>
							</div>
							<div class="table-responsive mt-4">
								<table class="table table-striped table-inventory mb-0">
									<thead>
										<tr>
											<th class="small text-white text-uppercase">Item Name</th>
											<th class="small text-white text-uppercase text-center">Count</th>
											<th class="small text-white text-uppercase">Remarks</th>
										</tr>
									</thead>
									<tbody>

										<?php for ($i=0; $i < sizeOf($arrReceivable); $i++) {

											echo '<tr>';
											echo 	'<th style="vertical-align:middle!important;width:360px;">'.ucwords(strtolower($arrReceivable[$i]['product_style'].$arrReceivable[$i]['product_color'])).'<span class="d-block small text-secondary">'.$arrReceivable[$i]['product_code'].'</span></th>';
											echo 	'<td style="vertical-align:middle!important;min-width:100px;max-width:100px;" class="text-center">'.$arrReceivable[$i]['count'].'</td>';
											echo 	'<td style="vertical-align:middle!important;min-width:300px;">'.$arrReceivable[$i]['item_remarks'].'</td>';
											echo '</tr>';

										}; ?>

									</tbody>
									<tfoot>
										<tr>
											<th class="small text-white text-uppercase">Total</th>
											<th class="small text-white text-uppercase text-center"><?= array_sum(array_column($arrReceivable, 'count')); ?></th>
											<th class="small text-white text-uppercase"></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-4 mt-5 mt-lg-0">
						<p class="text-primary text-uppercase font-bold">order details</p>

				<!-- <pre style="height:300px;overflow:scroll;"><?= print_r($arrReceivable) ?></pre> -->
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Transaction</h5>
							<p class="h6 large"><?= ucwords(str_replace('_',' ',$arrReceivable[0]['type'])) ?></p>
						</div>

						<div class="mt-3">
							<h5 class="text-secondary font-bold">Reference Number</h5>
							<p class="h6 large"><?= strtoupper($arrReceivable[0]['reference_number']) ?></p>
						</div>

						<?php if ( $arrReceivable[0]['remarks'] != '' ) { ?>

							<div class="mt-3">
								<h5 class="text-secondary font-bold">Additional message</h5>
								<p class="h6 large"><?= $arrReceivable[0]['remarks'] ?></p>
							</div>

						<?php } ?>

						<p class="text-primary text-uppercase font-bold mt-5">sender details</p>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Date Sent</h5>
							<p class="h6 large"><?= cvdate2($arrReceivable[0]['date_created']) ?></p>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Authorized by</h5>
							<p class="h6 large">
								<?= $arrReceivable[0]['admin_name'] ?>
							</p>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Signature</h5>
							<div class="custom-card mt-3">
								<img class="img-fluid center-block" src="<?= $arrReceivable[0]["admin_signature"]; ?>">
							</div>
						</div>

						<div class="mt-5 text-center">
							<a href="/inventory/process/admin/studios_approve.php?ref=<?= $_GET['ref_num'] ?>"><button class="btn btn-primary mx-2">approve</button></a>
							<a href="/inventory/process/admin/studios_reject.php?ref=<?= $_GET['ref_num'] ?>"><button class="btn btn-danger mx-2">reject</button></a>
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

<?= get_footer() ?>