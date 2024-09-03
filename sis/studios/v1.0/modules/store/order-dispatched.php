<?php include "./modules/includes/products/orders_confirmed.php"; ?>

<style type="text/css">
	body > .container {overflow-y: auto !important;}
	.card {border: none;}
</style>

<div class="frame-style" data-style="">
	<div class="row">
		<p class="col-12 text-uppercase font-bold mb-3 mt-3">Order Details</p>
		<p class="col-12 text-secondary mt-3"><b>Name</b>: <?= $_GET['name'] ?></p>
		<p class="col-12 text-secondary mb-3"><b>Order ID</b>: <?= $_GET['order_id'] ?></p>
	</div>
    <?php $total_price = 0 ?>
	<?php for($i = 0; $i < count($arrOrdersConfirmed); $i++){ ?>

			<?php
				$dataMid = '';
				$arrOrdersConfirmed[$i]['type'] = '';
				$item_name =strtoupper($arrOrdersConfirmed[$i]['item_description']);
				$product_code =strtoupper($arrOrdersConfirmed[$i]['product_code']);
				if(strstr($item_name, 'PAPER BAG')){
					 $arrOrdersConfirmed[$i]['type'] = 'Merch';
					 $dataMid = 'Merch';
				}elseif(strstr($item_name, 'HARDCASE') || strstr($item_name, 'ANTI FOG') || strstr($item_name, 'DAILY SHIELD') || strstr($item_name, 'DAILY MASK') || strstr($item_name, 'DAILY DUO')){
						 $arrOrdersConfirmed[$i]['type'] = 'Merch';
						 $dataMid = 'Merch';
				}
				elseif(!strstr($product_code, 'C') && !strstr($product_code, 'PL') && !strstr($product_code, 'P') && !strstr($product_code, 'H') && !strstr($product_code, 'SC') && !strstr($product_code, 'SGC') && !strstr($product_code, 'SCL') && !strstr($product_code, 'SW') && !strstr($product_code, 'SS') && !strstr($product_code, 'ST') && !strstr($item_name, 'AGENDA')){
						 $arrOrdersConfirmed[$i]['type'] = 'Frame Style';

						 $dataMid = 'Frame';
				}
                if($arrOrdersConfirmed[$i]['price'] > 0){}
                elseif(strstr(strtolower($arrOrdersConfirmed[$i]['item_description']),'paper bag') || strstr(strtolower($arrOrdersConfirmed[$i]['item_description']),'sac') || strstr(strtolower($arrOrdersConfirmed[$i]['item_description']),'receipt')){

                    continue;

                }

                if($arrOrdersConfirmed[$i]['dispatch_type'] == 'packaging'){
                    continue;
                }
			?>



		<div class="card mt-4">
                <div class="card-body cart-item">
                    <div class="d-flex justify-content-between">
                        <div class="col-6">
                            <div class="justify-content-center">
                                 <div class="image-wrapper" style="height: 100px; width: 100%; background-image: url(<?= $arrOrdersConfirmed[$i]['image_url'] ?>); background-repeat: no-repeat; background-size: 80%; background-position: center;"></div>                                
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="row no-gutters d-flex justify-content-start mt-5 mt-xs-0">
                                <h2 style="text-transform: uppercase; font-size: 14px;" class="mt-2 product-title">
                                    <?= $arrOrdersConfirmed[$i]['item_description'] ?> 
                                    <br>
                                    <span style="font-size: 12px;"><?= $product_code ?></span>
                                    <br>
                                    <span  class="mt-1" style="color: #000 !Important;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?><?= number_format($arrOrdersConfirmed[$i]['price'], 2) ?> X <?= $arrOrdersConfirmed[$i]['count'] ?></span>    
                                </h2>                                
                            </div>                            
                            <div class="row no-gutters d-flex justify-content-start mt-3">
                                
                            </div>

                            <?php

                                $total = $arrOrdersConfirmed[$i]['count'] * $arrOrdersConfirmed[$i]['price'];
                                $total_price += $total;

                            ?>

                            <div class="row no-gutters d-flex justify-content-start mt-3">
                                <p class="mt-1"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?><?= number_format( $total, 2) ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	<?php } ?>
</div>
<hr class="spacing">
<div class="col-12 mt-4">
    <div class="card order-total">
        <div class="card-body">
            <div class="row">
                <div class="col-4 text-center">
                    <p style="font-size: 16px;"><b>Total:</b></p>
                </div>
                <div class="col-8 text-center">
                    <?php $voucher_amount = ($arrOrdersConfirmed[0]['promo_code_amount'] > 0) ? $arrOrdersConfirmed[0]['promo_code_amount'] : 0; ?>
                    <p><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?><?= number_format($total_price - $voucher_amount, 2) ?> </p>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="spacing">
<div class="col-12">
	<div class="card">		
	  <div class="card-body">	  	
	  	<div class="d-flex justify-content-center">
	  		<?php if(isset($_SESSION['dispatch_studios_no_access']) && !$_SESSION['dispatch_studios_no_access']) { ?>
		  		<a href="/studios/dispatch-studios" style="color: #fff; margin-right: 30px;"><button type="button" class="btn btn-black">Go to Dispatch</button></a>
		  	<?php } ?>	
		  	<a href="/sis/studios/v1.0/?page=store-home" style="color: #fff"><button type="button" class="btn btn-primary">Go to Home Page</button></a>
		  </div>
	  </div>
	</div>
</div>