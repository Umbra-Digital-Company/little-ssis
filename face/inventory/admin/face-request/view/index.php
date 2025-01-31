<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'admin';
$page_url = 'Edit current request face';

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
require $sDocRoot."/face/inventory/includes/grab_all_moving_stock_specific_face.php";
require $sDocRoot."/face/inventory/includes/grab_all_transferable_items_face.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";


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
		<form action="/face/inventory/process/admin/edit_request_face_v2.php" method="POST" id="pullout-form" autocomplete="off">
			<div class="order-details">

				<div class="row align-items-start">
					<div class="col-12 col-lg-8">
						<div class="custom-card">
							<div class="row no-gutters">
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
								<table class="table table-striped table-inventory mb-0" id="multiple-item-row">
									<thead>
										<tr>
											<th class="small text-white text-uppercase">Item Name</th>
											<th class="small text-white text-uppercase text-center">Transferred Count</th>
											<th class="small text-white text-uppercase">Remarks</th>
											<th class="small text-white text-uppercase text-center"> </th>
										</tr>
									</thead>
									<tbody>
									<input type="hidden" name="reference" value="<?= $arrReceivable[0]['reference_number'] ?>">
										<?php for ($i=0; $i < sizeOf($arrReceivable); $i++) {

											echo '<tr>';

											echo '<th style="width: 350px">
														<select name="frame_code[]" class="select2 filled frame_code" required>
															<option value="">Select Item</option>';

															for ($a=0;$a<sizeof($arrItems);$a++) { 

															echo	'<option value="'.$arrItems[$a]['product_code'].'">'. ucwords(strtolower(str_replace("-", " ", $arrItems[$a]['product_style'] . $arrItems[$a]['product_color']))).' '. $arrItems[$a]['product_code'].'</option>';

															}

											echo 		'</select>';
											echo	'</th>';
											echo 	'<td style="vertical-align:middle!important;min-width:100px;max-width:100px;" class="text-center"><input type="number" class="form-control"  name="frame_count[]" value="'.$arrReceivable[$i]['count'].'"></td>';
											echo 	'<td style="vertical-align:middle!important;min-width:300px;"><input type="text" name="item_remark[]" class="form-control filled" placeholder="Remarks for this item" value="'.$arrReceivable[$i]['item_remarks'].'"></td>';
												echo '<input type="hidden" name="off[]" value="'.$arrReceivable[$i]['delivery_unique'].'">';
											// Check if actual count is lower
											echo '<td style="vertical-align:middle!important;cursor:pointer;width:50px;"></td>';
											echo '</tr>';

										}; ?>

									</tbody>
									<tfoot>
										<tr>
											<th class="small text-white text-uppercase">Total</th>
											
											<th class="small text-white text-uppercase text-center" id="total-transfer-calc"><?= array_sum(array_column($arrReceivable, 'count')); ?></th>
											<th class="small"></th>
											
											<th class="small"></th>
										</tr>
									</tfoot>
								</table>
							</div>
							<div class="text-center mt-3">
								<a href="#" class="text-primary text-uppercase" id="add-new-row-item">
									<button type="button" class="btn btn-primary">add items</button>
								</a>
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
								<?= ucwords(strtolower($arrReceivable[0]['admin_name'])) ?>
							</p>
						</div>
						<div class="mt-3">
							<h5 class="text-secondary font-bold">Signature</h5>
							<div class="custom-card mt-3">
								<img class="img-fluid center-block" src="<?= $arrReceivable[0]["admin_signature"]; ?>">
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
									<?php if ($arrReceivable[0]['sender_first_name'] == '' && $arrReceivable[0]['sender_last_name'] == '') {
										echo ucwords(strtolower($arrReceivable[0]['sender_id']));
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

						<?php } ?>
						<input type="submit" value="Save" class="form-control btn btn-primary text-white">
					</div>
				</div>
								
			</div>
			</form>
		</div>

	</div>

</div>

<script src="/js/select2.min.js"></script>
<script src="/js/signature.js"></script>
<script src="/js/inventory.js?v=<?= date('His') ?>"></script>
<script>
	var arrReceivable = <?= json_encode($arrReceivable) ?>;
	i = 0;
	$('.frame_code').each(function(){
		$(this).val(arrReceivable[i].product_code).trigger("change");
		i++;
	});
</script>
<?= get_footer() ?>