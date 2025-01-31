<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'runner';
$page_url = 'on-hand';

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
											echo 	'<td style="width:180px"><input class="form-control" name="pickup_count[]" placeholder="#" value="'.$arrReceivable[$i]['runner_count'].'" required></td>';
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
							<p class="h6 large"><?= ucwords(str_replace('_',' ',$arrReceivable[0]['type'])) ?></p>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Reference Number</h5>
							<p class="h6 large"><?= strtoupper($arrReceivable[0]['reference_number']) ?></p>
						</div>

						<?php if ( $arrReceivable[0]['remarks'] != '' ) { ?>
						
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Remarks</h5>
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
								<?php if ($arrReceivable[0]['sender_first_name'] == '' && $arrReceivable[0]['sender_last_name'] == '') {
									echo ucwords($arrReceivable[0]['sender_name']);
								} else {
									echo ucwords(strtolower($arrReceivable[0]['sender_first_name'].' '.$arrReceivable[0]['sender_last_name']));
								} ?>
							</p>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Signature</h5>
							<div class="custom-card mt-3">
								<img class="img-fluid center-block" src="<?= $arrReceivable[0]["signature"]; ?>">
							</div>
						</div>
						
						<div class="mt-5">
							<div class="d-flex justify-content-center">
								<a href="/inventory/process/runner/drop.php?ref_num=<?= $arrReceivable[0]['reference_number'] ?>"><button type="button" class="btn btn-primary mx-3" id="confirmForm">return to sender</button></a>
								<a href="/inventory/runner/on-hand/"><button type="button" class="btn btn-secondary mx-3">Exit</button></a>
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

<?= get_footer() ?>