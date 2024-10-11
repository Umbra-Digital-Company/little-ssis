<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '') {

	echo '<script>	window.location.href="/sis/face/v1.0/?page=store-home"; </script>';
} else {

	if (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) != 'ns') {
		echo '<script>	window.location.href="/sis/face/v1.0/?page=store-home"; </script>';
	}

	include("./modules/includes/products/grab_for_payments.php");
	include("./modules/includes/date_convert.php");


	$itemsPerPage = 5; // Set how many items you want per page
	$currentPage = isset($_GET['ppage']) ? (int)$_GET['ppage'] : 1; // Get current page or default to 1
	$totalItems = getTotalItems(); // Get total number of items
	$totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages

	$offset = ($currentPage - 1) * $itemsPerPage;

	$arrForPayments = getForPayments($offset, $itemsPerPage);
?>


	<style>
		.date-bar {
			margin-top: 0px !important;
			background-color: white;
			margin-bottom: 10px;
			border-radius: 15px;
			padding: 15px;
			box-shadow: 0 1px 3px 0 rgba(54, 72, 46, 0.3);
		}

		.tbody {
			background-color: white;
			border-radius: 16px;
			padding: 15px;
		}
	</style>

	<div class="mx-2 mt-4">

		<div class="row align-items-stretch row-tiles">
			<div class="col-12 align-items-stretch">

				<div class="date-bar mb-4">
					<div class="row" style="margin: 0px,15px	;">
						<div class="d-flex form-group  col-6 justify-content-start align-items-center mt-2">
							<input type="date" name="date-from" id="date-from" class="form-control"
								value="<?= (isset($_GET['date']) && trim($_GET['date']) != '') ? $arrDate[0] : date('Y-m-d', strtotime(date('Y-m-d') . ' +13 hours')) ?>">
							<label class="placeholder" for="date-from">Date From</label>
						</div>
						<div class=" d-flex form-group  col-6 justify-content-start align-items-center mt-2 ">
							<input type="date" name="date-to" id="date-to" class="form-control"
								value="<?= (isset($_GET['date']) && trim($_GET['date']) != '') ? $arrDate[1] : date('Y-m-d', strtotime(date('Y-m-d') . ' +13 hours')) ?>">
							<label class="placeholder" for="date-to">Date To</label>
						</div>
					</div>
				</div>



				<div class="d-flex justify-content-between align-items-center mt-4 mb-3">
					<div class="custom-subtitle">
						Total orders <span class="custom-title" style="margin-left: 5px;"> <?= $totalItems ?> </span>
					</div>



					<?php if (!empty($arrForPayments)): ?>
						<select class="pagination-select custom-subtitle" onchange="location = this.value;">
							<?php for ($i = 1; $i <= $totalPages; $i++): ?>
								<option value="?page=transactions&active=payment&date=<?= urlencode((isset($_GET['date']) ? $_GET['date'] : '')) ?>&ppage=<?= $i ?>" <?= $i == $currentPage ? 'selected' : '' ?>>Page <?= $i ?> of <?= $totalPages ?></option>
							<?php endfor; ?>
						</select>
					<?php endif; ?>
				</div>


				<?php if (empty($arrForPayments)): ?>
					<div class="no-orders-message" style="text-align: center; margin-top: 5rem">
						<img src="/sis/face/assets/images/icons/party-popper.svg" class="btn-custom-svg mb-3" style="height: 30px; width: auto" alt="No Pending Orders">
						<h1 style="color: #B7B7B7;">No Pending Orders</h1>
					</div>
				<?php else: ?>
					<div class="table-responsive mb-5">
						<table class="">
							<thead>
								<tr>
									<th class="text-nowrap">Customer Name & Order ID</th>
									<th class="text-nowrap">Item & PO Number</th>
									<th>Price</th>
								</tr>
							</thead>
							<tbody>
								<?php foreach ($arrForPayments as $payment): ?>
									<tr>
										<td>
											<div class="row text-nowrap">
												<span class="col-12 custom-sub-subtitle" style="padding-bottom: 10px; color: #919191">
													<?= cvdate3($payment['date_created']) ?>
												</span>
												<div class="col-12">
													<span class="custom-title d-block underline" style="text-decoration: underline">
														<?= ucwords(strtolower($payment['first_name'] . ' ' . $payment['last_name'])) ?>
													</span>
												</div>
												<span class="col-12 custom-sub-subtitle" style="color: #919191">
													<?= $payment['orders_specs_id'] ?>
												</span>
											</div>
										</td>

										<td class="text-nowrap">
											<div class="row">
												<div class="col-12" style="padding-bottom: 30px"></div>
												<div class="col-12">
													<span class="custom-sub-subtitle" style="font-size: 16px;">
														<?= $payment['item_description'] ?> <?= $payment['product_code'] ?>
													</span>
												</div>
												<span class="col-12 custom-sub-subtitle mt-1" style="color: #919191">
													<?= $payment['po_number'] ?>
												</span>
											</div>
										</td>

										<td>
											<div class="col-12"></div>
											<span class="custom-sub-subtitle" style="font-size: 16px; color: #919191">
												<?= number_format($payment['price'], 2) ?>
											</span>
										</td>
									</tr>
								<?php endforeach; ?>
							</tbody>
						</table>
					</div>
				<?php endif; ?>



			</div>
		</div>
	</div>


	<script>
		$('#date-from').change(function() {
			searchDate();
		});
		$('#date-to').change(function() {
			searchDate();
		});

		function searchDate() {
			window.location = '?page=transactions&active=payment&date=' + $('#date-from').val() + '|' + $('#date-to').val();
		}
	</script>




<?php } ?>