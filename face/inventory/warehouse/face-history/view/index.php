<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'warehouse';
$page_url = 'history-warehouse-face';

$filter_page = 'history_warehouse_face';
$group_name = 'aim_face';
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
require $sDocRoot."/face/includes/grab_stores_face.php";
require $sDocRoot."/face/inventory/includes/grab_all_moving_stock_specific_face.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

// Grab Store
$storeName = "";

$MergeStores= $arrStoresFace;


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

			<div class="order-details">

				<div class="row align-items-start">
					<div class="col-12 col-lg-8">
						<div class="custom-card">
							<div class="row no-gutters">
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">From</h5>
									<p class="h6 large"><?= ucwords(str_replace(['ali','sm','mw','mtc','hq','qa','-'],['ALI','SM','MW','MTC','HQ','QA',' '],strtolower($arrReceivable[0]['stock_from_branch']))) ?></p>
								</div>
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">To</h5>
									<p class="h6 large"><?= ucwords(str_replace(['ali','sm','mw','mtc','hq','qa','-'],['ALI','SM','MW','MTC','HQ','QA',' '],strtolower($arrReceivable[0]['stock_to_name']))) ?></p>
								</div>
							</div>
							<div class="table-responsive mt-4">
								<table class="table table-striped table-inventory mb-0">
									<thead>
										<tr>
											<th class="small text-white text-uppercase">Item Name</th>
											<th class="small text-white text-uppercase">Remarks</th>
											<th class="small text-white text-uppercase text-center">Transferred Count</th>
											<th class="small text-white text-uppercase text-center">Received Count</th>
										</tr>
									</thead>
									<tbody>

										<?php for ($i=0; $i < sizeOf($arrReceivable); $i++) {

											echo '<tr>';
											echo 	'<th>'.ucwords(strtolower($arrReceivable[$i]['product_style'].$arrReceivable[$i]['product_color'])).'<span class="d-block text-secondary">'.$arrReceivable[$i]['product_code'].'</span></th>';
											echo 	'<td style="vertical-align:middle!important;min-width:300px;">'.$arrReceivable[$i]['item_remarks'].'</td>';
											echo 	'<td style="vertical-align:middle!important;" class="text-center">'.$arrReceivable[$i]['count'].'</td>';

											// Check if actual count is lower
											if($arrReceivable[$i]['actual_count'] < $arrReceivable[$i]['count']) {

												$styleRed = 'text-danger';

											}
											else {

												$styleRed = '';

											};

											echo 	'<td style="vertical-align:middle!important;" class="text-center '.$styleRed.'">'.$arrReceivable[$i]['actual_count'].'</td>';
											echo '</tr>';

										}; ?>

									</tbody>
									<tfoot>
										<tr>
											<th class="small text-white text-uppercase">Total</th>
											<th class="small"></th>
											<th class="small text-white text-uppercase text-center"><?= array_sum(array_column($arrReceivable, 'count')); ?></th>
											<th class="small text-white text-uppercase text-center"><?= array_sum(array_column($arrReceivable, 'actual_count')); ?></th>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-4 mt-5 mt-lg-0">
						<p class="text-primary text-uppercase font-bold">order details</p>


						<div class="mt-4">
							<h5 class="text-secondary font-bold">Transaction</h5>
							<p class="h6 large"><?= strtoupper($arrReceivable[0]['direction']) .' - '. ucwords(str_replace('_',' ',$arrReceivable[0]['type'])) ?></p>
						</div>
						<?php if ($arrReceivable[0]['type']=='pullout'){ ?>

						
						<div class="mt-2">
						<h5 class="text-secondary font-bold">Requestor</h5>
							<p class="h6 large <?= $statusClass ?>"><?= ucwords($arrReceivable[0]['i_requestor']) ?></p>
						</div>
						<?php } ?>
						<div class="mt-3">
							<?php switch ($arrReceivable[0]['status']) { 
								case 'waiting for pickup' : $statusClass = 'text-danger'; break;
								case 'in transit' : $statusClass = 'text-warning'; break;
								case 'received' : $statusClass = 'text-success'; break;
								default : $statusClass = '';
							} ?>
							<h5 class="text-secondary font-bold">Status</h5>
							<p class="h6 large <?= $statusClass ?>"><?= ucwords($arrReceivable[0]['status']) ?></p>
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
								<?php if ($arrReceivable[0]['admin_id']=='overseer') {
									echo $arrReceivable[0]['admin_name'];
								} elseif ($arrReceivable[0]['sender_id']=='warehouse' || $arrReceivable[0]['sender_id']=='warehouse_qa') {
									echo $arrReceivable[0]['sender_name'];
								} else {
									echo ucwords(strtolower($arrReceivable[0]['sender_first_name'].' '.$arrReceivable[0]['sender_last_name']));
								} ?>
							</p>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Signature</h5>
							<div class="custom-card mt-3">
								<img class="img-fluid center-block" src="<?= ($arrReceivable[0]["signature"]!='') ? $arrReceivable[0]['signature'] : $arrReceivable[0]['admin_signature']; ?>">
							</div>
						</div>

						<?php if ( $arrReceivable[0]['status'] == 'received' ) { ?>

							<p class="text-primary text-uppercase font-bold mt-5">recipient details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Date Received</h5>
								<p class="h6 large"><?= cvdate2($arrReceivable[0]['date_updated']) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Received by</h5>
								<p class="h6 large">
									<?php if ($arrReceivable[0]['receiver_id']=='overseer') {
										echo 'Admin';
									} elseif ($arrReceivable[0]['receiver_id']=='warehouse') {
										echo 'Warehouse';
									} elseif ($arrReceivable[0]['receiver_id']=='warehouse_qa') {
										echo 'Warehouse QA';
									} else {
										echo ucwords(strtolower($arrReceivable[0]['sender_first_name'].' '.$arrReceivable[0]['sender_last_name']));
									} ?>
								</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<div class="custom-card mt-3">
									<img class="img-fluid center-block" src="<?= $arrReceivable[0]["receiver_signature"]; ?>">
								</div>
							</div>

						<?php } ?>

						<div class="text-center mt-5">
							<a href="/face/inventory/warehouse/face-history/"><button class="btn btn-primary">back to history</button></a>
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