<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '') {

	//include("./modules/xlog.php");
	echo '<script>	window.location.href="/sis/studios/v1.0/?page=store-home"; </script>';
} else { ?>

	<?php
	if (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) != 'ns') {
		echo '<script>	window.location.href="/sis/studios/v1.0/?page=store-home"; </script>';
	}

	include("./modules/includes/products/grab_for_payments.php");
	include("./modules/includes/date_convert.php");


	$itemsPerPage = 10; // Set how many items you want per page
	$currentPage = isset($_GET['ppage']) ? (int)$_GET['ppage'] : 1; // Get current page or default to 1
	$totalItems = getTotalItems(); // Get total number of items
	$totalPages = ceil($totalItems / $itemsPerPage); // Calculate total pages

	// print_r($totalItems);

	// Fetch items for current page
	$offset = ($currentPage - 1) * $itemsPerPage; // Calculate offset for query
	$arrForPayments = getForPayments($offset, $itemsPerPage);

	// echo '<pre>';
	// print_r($arrForPayments);
	// echo '</pre>';
	?>

	<style>
		.orders-management {
			background-color: #f0f0f0;
			border-radius: inherit;
			margin-top: 20px;

		}

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

		#submit_search_overview {
			width: 65px !important;
			background-image: url(/ssis/assets4/images/icons/icon-search-white.png);
			background-repeat: no-repeat;
			background-position: right 17px center;
			background-size: 27px;
			border-radius: 50% !important;

		}

		#input_search {
			box-shadow: 0px 1px 0 0px rgba(42, 35, 35, 0.25) !important;
			background-image: none !important;
			background-color: transparent;
		}

		#btnsubmit-online {
			text-align: right;
			width: 140px !important;
			background-color: white;
			color: #46797A;
			border: 2px solid #46797A;

			background-image: url(/ssis/assets4/images/icons/icon-sync.png);
			background-repeat: no-repeat;
			background-position: left 17px center;
			background-size: 22px;
		}

		.table-default {
			box-shadow: 0 0 0 0 rgba(42, 35, 35, 0.25) !important;
			border-radius: 16px;
		}

		.table-default-header thead th {

			color: grey;
			font-size: 16px;
			/* width: 100%; */
		}

		.table-default-header,
		table {

			width: 100%;

		}

		.table-default table {
			width: 100%;
			border-collapse: collapse;

		}

		.table-default thead {
			background-color: #f0f0f0;
			font-size: 16px;
			font-weight: 400;

			position: sticky;
			top: 0;
		}

		.table-default th {
			color: #919191;
			font-size: 16px;
			font-weight: 400;
			text-align: left;

		}

		.table-default td {
			text-align: left;
			padding: 20px;
			height: 82px;

		}



		.overview-content .overview-table {
			max-height: 564px !important;
		}



		/* SWITCH */
		.switch {
			position: relative;
			display: inline-block;
			width: 60px;
			height: 34px;
		}

		.switch input {
			opacity: 0;
			width: 0;
			height: 0;
		}

		input:checked+.slider {
			background-color: #96B1A3;
		}

		.slider.round {
			border-radius: 34px;
		}

		.slider {
			position: absolute;
			cursor: pointer;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			transition: .4s;
			background-color: #DCDCDC;
		}

		.slider.round:before {
			border-radius: 50%;
		}

		.slider:before {
			position: absolute;
			content: "";
			height: 26px;
			width: 26px;
			left: 4px;
			bottom: 4px;
			background-color: white;
			-webkit-transition: .4s;
			transition: .4s;
		}

		input:checked+.slider:before {
			-webkit-transform: translateX(26px);
			-ms-transform: translateX(26px);
			transform: translateX(26px);
		}

		/* END SWITCH */
		.no-result-text {
			color: #B7B7B7;
		}

		.date-header {
			background-color: #f7f7f7;
			border-radius: inherit;
			margin-top: 20px;
		}

		.table-fullname {
			font-size: 18px;
			font-weight: 700;
			color: #342C29;
			text-decoration: underline;
		}

		.table-date {
			font-size: 14px;
			font-weight: 500;
			color: #919191;

		}


		.table-order-id {
			font-size: 14px;
			font-weight: 500;
			color: #919191;
		}

		.table-item-name {
			color: #342C29;
			font-size: 16px;
			font-weight: 400;
		}

		.table-po-number {
			font-size: 14px;
			font-weight: 500;
			color: #919191;
		}

		.table-price {
			font-size: 14px;
			font-weight: 400;
			color: #919191;
		}
	</style>

	<div class="orders-management">

		<div class="row align-items-stretch row-tiles">
			<div class="col-12 align-items-stretch">

				<div class=" date-bar">
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


				<div class="d-flex justify-content-between mb-4 mt-4" style="color: #342C29; font-size: 18px; font-weight: 400;">

					<span style="color: #342C29; font-size: 18px; font-weight: 400;">Total Orders
						<span style="margin-left: 5px; color: #342C29; font-size: 18px; font-weight: 700;">
							<?= $totalItems ?>
						</span> </span>


					<?php if (!empty($arrForPayments)): ?>
						<select class="pagination-select" onchange="location = this.value;">
							<?php for ($i = 1; $i <= $totalPages; $i++): ?>
								<option value="?page=transactions&active=payment&date=<?= urlencode((isset($_GET['date']) ? $_GET['date'] : '')) ?>&ppage=<?= $i ?>" <?= $i == $currentPage ? 'selected' : '' ?>>Page <?= $i ?> of <?= $totalPages ?></option>
							<?php endfor; ?>
						</select>
					<?php endif; ?>
				</div>

				<div class="table-responsive ">
					<table class="table-default">
						<thead>
							<tr>
								<th nowrap style="width: 45%; ">Customer Name & Order ID</th>
								<th nowrap style="width: 42%;">Item & PO Number</th>
								<th>Price</th>
							</tr>
						</thead>

					</table>

					<table class="table-default p-0 mt-2">



						<tbody>

							<?php for ($i = 0; $i < count($arrForPayments); $i++) { ?>

								<tr nowrap>

									<!-- <td><?= $i + 1 ?></td> -->
									<td>
										<div class="row" nowwrap>
											<span class="col-12 table-date " style="padding-bottom: 10px">
												<?= cvdate3($arrForPayments[$i]['date_created']) ?>
											</span>
											<div class="col-12">
												<span class="table-fullname">
													<?= ucwords(strtolower($arrForPayments[$i]['first_name'] . ' ' . $arrForPayments[$i]['last_name'])) ?>

												</span>

											</div>
											<span
												class="table-order-id col-12"><?= $arrForPayments[$i]['orders_specs_id'] ?></span>



										</div>
									</td>

									<td style="width: 57%;" nowwrap>
										<div class="row">
											<div class="col-12" style="padding-bottom: 30px"></div>
											<div class="col-12 ">
												<span class="table-item-name"><?= $arrForPayments[$i]['item_description'] ?> <?= $arrForPayments[$i]['product_code'] ?>

												</span>

											</div>
											<span class=" col-12 table-po-number"><?= $arrForPayments[$i]['po_number'] ?>
											</span>
										</div>
									</td>

									<td>
										<div class="col-12"></div>
										<span class="table-price"><?= number_format($arrForPayments[$i]['price'], 2) ?> </span>
									</td>

								</tr>

				</div>

			<?php } ?>


			</tbody>
			</table>
			</div>
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