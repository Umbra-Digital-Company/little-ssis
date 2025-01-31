<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'store';
$page_url = 'damage';

$filter_page = 'damage_store';
$group_name = 'main_menu';

////////////////////////////////////////////////

// Set access for Admin and Store account
// if($_SESSION['user_login']['userlvl'] != '3' || $_SESSION['user_login']['position'] !== 'store') {

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
require $sDocRoot."/inventory/includes/grab_all_transferable_items.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_v2.php";
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
				<form action="/inventory/process/store/damage.php" method="POST" id="damage-form" autocomplete="off">
					<div class="row align-items-start">
						<div class="col-12 col-lg-8">
							<div class="custom-card">
								<div class="row">
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">From</h5>
										<input type="hidden" name="stock_from_id" value="<?= $_SESSION['user_login']['store_code'] ?>">
										<input type="text" class="form-control" value="<?= ucwords(str_replace(['ali','mw'],['ALI','MW'],strtolower($storeName))) ?>" readonly>
									</div>
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">To</h5>
										<input type="hidden" name="recipient_branch" id="recipient_branch" class="form-control" value="warehouse_damage">
										<input type="text" class="form-control" value="Warehouse ( Dmg )" readonly>
									</div>
								</div>
								<div class="table-responsive mt-4">
									<table class="table table-striped table-inventory mb-0" id="multiple-item-row">
										<thead>
											<tr>
												<th class="small text-white text-uppercase">Item Name</th>
												<th class="small text-white text-uppercase text-center">Count</th>
												<th class="small text-white text-uppercase">Remarks</th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>

											<?php for ($x=0;$x<5;$x++) { ?>
												
												<tr>
													<th style="width: 360px">
														<select name="frame_code[]" class="select2 filled frame_code" <?= ($x==0) ? 'required' : '' ?>>
															<option value="">Select Item</option>

															<?php for ($i=0;$i<sizeof($arrItems);$i++) { ?>

																<option value="<?= $arrItems[$i]['product_code'] ?>"><?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))) ?> ( <?= $arrItems[$i]['product_code'] ?> )</option>

															<?php } ?>

														</select>
													</th>
													<td style="min-width:100px;max-width:100px;">
														<input min="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#" <?= ($x==0) ? 'required' : '' ?>>
													</td>
													<td style="min-width:300px;">
														<input type="text" name="item_remark[]" class="form-control filled" placeholder="Remarks for this item">
													</td>
													<td style="vertical-align:middle!important;cursor:pointer;width:50px;"><img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>
												</tr>

											<?php } ?>

										</tbody>
										<tfoot>
											<tr>
												<th class="small text-white text-uppercase">Total</th>
												<th class="small text-white text-uppercase text-center" id="total-transfer-calc">0</th>
												<th class="small text-white text-uppercase"></th>
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
							<p class="text-primary text-uppercase font-bold">sender details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Employee ID</h5>
								<input type="text" name="emp_id" class="form-control" placeholder="ID #" required>
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
								<div class="d-flex justify-content-end">
									<button type="submit" class="btn btn-primary" id="confirmForm" disabled>submit</button>
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