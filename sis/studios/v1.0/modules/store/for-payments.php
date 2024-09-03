<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '' ) {

	//include("./modules/xlog.php");
	echo '<script>	window.location.href="/sis/studios/v1.0/?page=store-home"; </script>';	
} else { ?>

	<?php
	if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) != 'ns') {
		echo '<script>	window.location.href="/sis/studios/v1.0/?page=store-home"; </script>';
	}
	
	include("./modules/includes/products/grab_for_payments.php");
	include("./modules/includes/date_convert.php");
	// echo '<pre>';
	// print_r(getForPayments());
	// echo '</pre>';

	$arrForPayments = getForPayments();
	?>

	<style type="text/css">
		body > .container {
			overflow-y: scroll !important;
		}
		.h-100 {
			height: 100%;
		}
		.row-tiles {
			height: auto;
		}
		.bg-orange-tile {
			background-color: #D36327;
		}
		.bg-yellow-tile {
			background-color: #E8C560;
		}
		.bg-pink-tile {
			background-color: #F0DBD5;
		}		
		.bg-tan-tile {
			background-color: #EADFCD;
		}
		.bg-blue-tile {
			background-color: #054A70;
		}
		.bg-green-tile {
			background-color: #9BA17B;
		}
		.bg-orange-tile > p,
		.bg-blue-tile,
		.bg-green-tile {
			color: #fff;
		}
		.bg-yellow-tile > p,
		.bg-pink-tile > p,
		.bg-tan-tile > p {
			color: #352b27;
		}	
		.img-holder {
			height: 400px; 
			width: 100%;
			background-size: cover; 
			background-position: center;
		}
		.custom-card thead{
			background-color: #e8e8e4;
		}
	</style>

	<div class="container-fluid">
		<div class="row align-items-stretch row-tiles">
			<div class="col-12 align-items-stretch">
				<div class="row">
					<div class="col-lg-8"></div>
					<div class="col-12 col-lg-2 form-group">
						<input type="date" name="date-from" id="date-from" class="form-control" value="<?= (isset($_GET['date']) && trim($_GET['date']) != '') ? $arrDate[0] : date('Y-m-d',strtotime(date('Y-m-d').' +13 hours') ) ?>">
						<label class="placeholder" for="date-from">Date From</label>
					</div>
					<div class="col-12 col-lg-2 form-group">
						<input type="date" name="date-to" id="date-to" class="form-control" value="<?= (isset($_GET['date']) && trim($_GET['date']) != '') ? $arrDate[1] : date('Y-m-d',strtotime(date('Y-m-d').' +13 hours') ) ?>">
						<label class="placeholder" for="date-to">Date To</label>
					</div>
				</div>
				<div class="col-12 custom-card table-responsive">
					<table class="table table-hover">
					  <thead>
					    <tr>
					      <th scope="col">#</th>
					      <th scope="col">Customer Name</th>
					      <th scope="col">Item Name</th>
					      <th scope="col">Order Id</th>
					      <th scope="col">Po Number</th>
					      <th scope="col">Price</th>
					      <th scope="col">Date</th>
					    </tr>
					  </thead>
					  <tbody>
					  	<?php for ($i=0; $i < count($arrForPayments); $i++) { ?>
					  		<tr>
					  			<td><?= $i+1 ?></td>
					  			<td><?= ucwords(strtolower($arrForPayments[$i]['first_name'].' '.$arrForPayments[$i]['last_name'])) ?></td>
					  			<td><?= $arrForPayments[$i]['item_description'] ?><br><span><?= $arrForPayments[$i]['product_code'] ?></<span></td>
					  			<td><?= $arrForPayments[$i]['order_id'] ?></td>
					  			<td><?= $arrForPayments[$i]['po_number'] ?></td>
					  			<td><?= number_format($arrForPayments[$i]['price'],2) ?></td>
					  			<td><?= cvdate3($arrForPayments[$i]['date_created']) ?></td>
					  		</tr>
					  	<?php } ?>
					    
					    
					  </tbody>
					</table>
				</div>
			</div>
		</div>
		
	</div>
<script>
	$('#date-from').change(function(){
		searchDate();
	});
	$('#date-to').change(function(){
		searchDate();
	});
	function searchDate(){
		window.location = '?page=for-payments&date='+$('#date-from').val()+'|'+$('#date-to').val();
	}
</script>

<?php } ?>
