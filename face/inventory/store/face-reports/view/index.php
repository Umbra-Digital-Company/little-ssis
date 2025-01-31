<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////

$page = 'store';
$page_url = 'face-variance-report';

$filter_page = 'reports_store_face';
$group_name = 'aim_face';
////////////////////////////////////////////////

// Set access for Admin and Store account
// if($_SESSION['user_login']['userlvl'] != '3' || $_SESSION['user_login']['position'] !== 'store') {

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
require $sDocRoot."/face/inventory/includes/grab_variance_report_details_face.php";

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar_face.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

$_SESSION['permalink'] = $filter_page;  

// Grab Store
$storeName = "";

for ($i=0; $i < sizeOf($arrStoresFace); $i++) { 

	if($arrStoresFace[$i]['store_id'] == $_SESSION['user_login']['store_code']) {

		$storeName = $arrStoresFace[$i]['store_name'];

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
						<form class="col-12 col-lg-8" action="/face/inventory/process/store/face_variance_approve.php" method="POST" autocomplete="off">
							<div class="custom-card">
								<div class="row no-gutters">
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">From</h5>
										<p class="h6 large"><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],strtolower($arrVariance[0]['stock_from_branch']))) ?></p>
									</div>
									<div class="col-6">
										<h5 class="text-secondary font-bold font-bold">To</h5>
										<p class="h6 large"><?= ucwords(str_replace(['ali','mw','sm','mtc','-'], ['ALI','MW','SM','MTC',' '],strtolower($arrVariance[0]['store_to_name']))) ?></p>
									</div>
								</div>
								<div class="table-responsive mt-4">
								<table class="table table-striped mb-0">
										<thead>
											<tr>
												<th class="small text-white text-uppercase">Item Name</th>
												<th class="small text-white text-uppercase">SKU</th>
												<th class="small text-white text-uppercase text-center">Sent</th>
												<th class="small text-white text-uppercase text-center">Received</th>
												<th class="small text-white text-uppercase text-center">Variance</th>
												<?php 
													// if the transaction type is OUT ( you sent an item ) you can approve or reject any variance
													// *** sample condition ***
													if ( $arrVariance[0]['stock_from_id']==$_SESSION['store_code'] ) { ?>
														<?php if($arrVariance[0]["variance_status"]!="" ){ ?>
															<th class="small text-white text-uppercase text-center">Status</th>
														<?php } else{ ?>
															<th class="small text-white text-uppercase text-center">Approve</th>
															<th class="small text-white text-uppercase text-center">Reject</th>
														<?php } 
													} 
												?>
											</tr>
										</thead>
										<tbody>
										
										<?php for($i=0;$i<sizeof($arrVariance);$i++){

											if ( $arrVariance[$i]["count"] > $arrVariance[$i]["actual_count"]) {
												$variance_item=$arrVariance[$i]["count"]-$arrVariance[$i]["actual_count"];
											} elseif ( $arrVariance[$i]["count"] < $arrVariance[$i]["actual_count"] ) {
												$variance_item=$arrVariance[$i]["actual_count"]-$arrVariance[$i]["count"];
											} elseif ( $arrVariance[$i]["count"] == $arrVariance[$i]["actual_count"] ) {
												$variance_item="0";
											} else{
												$variance_item="0";
											}

											if ($variance_item!="0") { ?>

												<input type="hidden" name="off[]" value="<?= $arrVariance[$i]["delivery_unique"] ?>">
												<tr>
													<td><?= ucwords(strtolower($arrVariance[$i]["item_name"])) ?></td>
													<td><?= $arrVariance[$i]['product_code'] ?></td>
													<td align="center"><?= $arrVariance[$i]["count"] ?></td>
													<td align="center"><?= $arrVariance[$i]["actual_count"] ?></td>
													<td align="center"><?php echo $variance_item; ?> </td>
													
												
													<?php 
														// if the transaction type is OUT ( you sent an item ) you can approve or reject any variance
														// *** sample condition ***
														if ( $arrVariance[0]['stock_from_id']==$_SESSION['store_code'] ) { ?>

															<?php if($arrVariance[$i]["variance_status"]!="" ){ ?>
																<td align="center">
																		<div class="d-flex align-items-center justify-content-center">
																			<?php if($arrVariance[$i]["variance_status"]=='approve'){
																					echo "Approved";
																			}else{
																				echo "Rejected";
																			}?>
																		</div>
																	</td>
																	

															<?php }else{ ?>

																	
																	<td align="center">
																		<div class="d-flex align-items-center justify-content-center">
																			<input class="sr-only checkbox" name="variance_report[<?= $arrVariance[$i]["delivery_unique"] ?>]" type="radio" id="variance_approve_<?= $arrVariance[$i]["delivery_unique"] ?>" value="approve">
																			<label for="variance_approve_<?= $arrVariance[$i]["delivery_unique"] ?>" class="custom_checkbox"></label>
																		</div>
																	</td>
																	<td align="center">
																		<div class="d-flex align-items-center justify-content-center">
																			<input class="sr-only checkbox" name="variance_report[<?= $arrVariance[$i]["delivery_unique"] ?>]" type="radio" id="variance_reject_<?= $arrVariance[$i]["delivery_unique"] ?>" value="reject">
																			<label for="variance_reject_<?= $arrVariance[$i]["delivery_unique"] ?>" class="custom_checkbox"></label>
																		</div>
																	</td>
														<?php
																	}
													} ?>
												</tr>

											<?php }
										
										} ?>		
										
										</tbody>
									</table>
								</div>
							</div>

							<?php 
								// if the transaction type is OUT ( you sent an item ) you can approve or reject any variance
								// *** sample condition ***
								if ( $arrVariance[0]['stock_from_id']==$_SESSION['store_code'] && $arrVariance[0]["variance_status"]=="" ) { ?>


								<div class="text-right mt-4">
									<button type="submit" class="btn btn-primary">submit report</button>
								</div>

							<?php } ?>
							
						</form>
						<div class="col-12 col-lg-4 mt-5 mt-lg-0">
							<p class="text-primary text-uppercase font-bold">order details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Transaction</h5>
								<p class="h6 large"><?= ucwords(str_replace("_"," ",$arrVariance[0]["type"])) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Reference Number</h5>
								<p class="h6 large"><?= $arrVariance[0]['reference_number'] ?></p>
							</div>

							<p class="text-primary text-uppercase font-bold mt-5">sender details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Date Sent</h5>
								<p class="h6 large"><?= cvdate2($arrVariance[0]["date_created"]) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Authorized by</h5>
								<p class="h6 large">
								<?= ucwords(strtolower($arrVariance[0]['sender_first_name'] .' '. $arrVariance[0]['sender_last_name'])) ?>
								</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<div class="custom-card mt-3">
									<img class="img-fluid center-block" src="<?= $arrVariance[0]['signature'] ?>">
								</div>
							</div>
							

							<p class="text-primary text-uppercase font-bold mt-5">recipient details</p>
							<div class="mt-4">
								<h5 class="text-secondary font-bold">Date Received</h5>
								<p class="h6 large"><?= cvdate2($arrVariance[0]["status_date"]) ?></p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Received by</h5>
								<p class="h6 large">
									<?= ucwords(strtolower($arrVariance[0]['receiver_firstname'].' '.$arrVariance[0]['receiver_lastname'])) ?>
								</p>
							</div>
							<div class="mt-3">
								<h5 class="text-secondary font-bold">Signature</h5>
								<div class="custom-card mt-3">
									<img class="img-fluid center-block" src="<?= $arrVariance[0]['receiver_signature'] ?>">
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

	header('Location: /face/inventory/store/face-reports/');
	exit;
	
} ?>