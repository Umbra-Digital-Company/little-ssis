<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'lab';
$page_url = 'stock-transfer';

////////////////////////////////////////////////

// Set access for Admin and Store account
if($_SESSION['user_login']['userlvl'] !== '3' || $_SESSION['user_login']['position'] !== 'laboratory') {

	header('location: /');
	exit;

};

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/v2/functions.php";
require $sDocRoot."/inventory/includes/aim_setup.php";
require $sDocRoot."/includes/grab_laboratory.php";
require $sDocRoot."/includes/grab_stores.php";
require $sDocRoot."/inventory/includes/grab_all_transferable_items.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page; 

// Grab Lab
$labName = "";

for ($i=0; $i < sizeOf($arrLab); $i++) { 

	if($arrLab[$i]['lab_id'] == $_SESSION['user_login']['store_code']) {

		$labName = $arrLab[$i]['lab_name'];

	};
	
};

?>

<?= get_header($page_url) ?>

<div class="row no-gutters align-items-strech flex-nowrap">

	<?= get_sidebar($page_url,$page) ?>

	<div id="ssis-main" class="col <?= str_replace(' ','-',$page_url) ?>">
			
		<?= get_topbar($page_url) ?>

		<div class="ssis-content">

			<form action="/inventory/process/lab/stock_transfer.php" method="POST" id="pullout-form" autocomplete="off">
				<div class="row align-items-start">
					<div class="col-12 col-lg-8">
						<div class="custom-card">
							<div class="row">
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">From</h5>
									<input type="hidden" name="stock_from_id" value="<?= $_SESSION['user_login']['store_code'] ?>">
									<input type="text" class="form-control" value="<?= ucwords(str_replace(['mtc','-'],['MTC',' '],strtolower($labName))) ?>" readonly>
								</div>
								<div class="col-6">
									<h5 class="text-secondary font-bold font-bold">To</h5>
									<select name="recipient_branch" id="recipient_branch" class="select2 form-control" required>
										<option value="">-- Select Branch --</option>
										<optgroup label="WAREHOUSE">
											<option value="warehouse">Warehouse</option>
										<optgroup label="STORE NAME">
											<?php for ($i=0;$i<sizeof($arrStores);$i++) { ?>
												<option value="<?= $arrStores[$i]['store_id'] ?>"><?= ucwords(str_replace(['ali','mw','sm'],['ALI','MW','SM'],strtolower($arrStores[$i]['store_name']))) ?></option>
											<?php } ?>
										</optgroup>
										<optgroup label="LAB NAME">
											<?php for ($i=0;$i<sizeof($arrLab);$i++) { ?>
												<option value="<?= $arrLab[$i]['lab_id'] ?>"><?= ucwords(str_replace(['mtc','-'],['MTC',' '],strtolower($arrLab[$i]['lab_name']))) ?></option>
											<?php } ?>
										</optgroup>
									</select>
								</div>
							</div>
							<div class="table-responsive mt-4">
								<table class="table table-striped mb-0" id="multiple-item-row">
									<thead>
										<tr>
											<th class="small text-white text-uppercase">Item Name</th>
											<th class="small text-white text-uppercase text-center">Transferred Count</th>
											<th>&nbsp;</th>
										</tr>
									</thead>
									<tbody>

										<?php for ($x=0;$x<5;$x++) { ?>

											<tr>
												<td style="width: 50%">
													<select name="frame_code[]" class="select2 filled frame_code" <?= ($x==0) ? 'required' : '' ?>>
														<option value="">Select Item</option>

														<?php for ($i=0;$i<sizeof($arrItems);$i++) { ?>

															<option value="<?= $arrItems[$i]['product_code'] ?>"><?= ucwords(strtolower(str_replace("-", " ", $arrItems[$i]['product_style'] . $arrItems[$i]['product_color']))) ?> ( <?= $arrItems[$i]['product_code'] ?> )</option>

														<?php } ?>

													</select>
												</td>
												<td>
													<input type="text" name="frame_count[]" class="form-control filled" placeholder="#" <?= ($x==0) ? 'required' : '' ?>>
												</td>
												<td style="vertical-align:middle!important;cursor:pointer;"><img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>
											</tr>

										<?php } ?>
										
									</tbody>
								</table>
								<div class="text-center mt-3">
									<a href="#" class="text-primary text-uppercase" id="add-new-row-item">
										<button type="button" class="btn btn-primary">add items</button>
									</a>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-lg-4">
						<p class="text-primary text-uppercase font-bold">remarks</p>
						<div class="mt-4">
							<textarea name="sender_remarks" id="senderRemarks" class="form-control textarea"></textarea>
						</div>
						<p class="text-primary text-uppercase font-bold mt-5">sender details</p>
						<div class="mt-4">
							<h5 class="text-secondary font-bold">Employee ID</h5>
							<input type="text" name="emp_id" class="form-control" placeholder="ID #" required>
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