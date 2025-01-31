<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '' ) {

	//include("./modules/xlog.php");
	echo '<script>	window.location.href="/sis/studios/v1.0"; </script>';	
} else { ?>

	<?php

	include("./modules/includes/grab_rates.php");
	include("./modules/includes/grab_studios_settings.php");
	include("./modules/includes/grab_total_top.php");

	/*
	* Set session to detect that you are NOT in the CUSTOMER PAGE
	* this show all the menu for assistant
	*
	*/
	if ( isset( $_SESSION['customer_page'] ) || isset( $_SESSION['login_customer'] ) ) {
		unset($_SESSION["pickup"]);
		unset( $_SESSION["customer_id"] );
		unset($_SESSION["order_no"]);
		unset($_SESSION['prescription']);
		unset($_SESSION['step_3']);
		unset($_SESSION['customer_page']);
		unset($_SESSION['cust_id']);
		unset($_SESSION['permalink']);
		unset($_SESSION['order_confirmation']);
		unset($_SESSION['priority']);
		unset($_SESSION['login_customer']);
		unset($_SESSION['login_set']);
		echo "<script>window.location.reload(true)</script>";
	}

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
	</style>

	<div class="container-fluid">
		<div class="row align-items-stretch row-tiles mb-4">
			<div class="col-6 align-items-stretch" style="align-self: stretch;">
				<div class="col-12 custom-card h-100 bg-orange-tile">
					<p>Total Orders Today:</p>
					<p class="text-left mt-4" style="font-size: 20px;"><?= (count($arrCount) > 0) ? $arrCount[0]['count'] : 0 ?></p>
				</div>
			</div>
			<div class="col-6 align-items-stretch">
				<div class="col-12 custom-card h-100 bg-yellow-tile">
					<p>Top Selling Item</p>
					<p class="text-left mt-4" style="font-size: 20px;"> <?= (count($arrTop) > 0) ? strtoupper($arrTop[0]['color']) : '' ?></p>
				</div>
			</div>
		</div>
		<?php if(count($arrTextImages) > 0) { ?>
			<?php if($arrTextImages[0]['image_1'] != ''){ ?>
				<!-- <div class="row">
					<div class="col-12 mb-4">
						<div class="img-holder" style="background-image:url(/studios/studios-settings/images/<?= $arrTextImages[0]['image_1']?>);"></div>
					</div>
				</div> -->
			<?php } ?>
			<div class="row">
				<div class="col-12 mb-4">
					<video autoplay="" muted="" loop="" playsinline="" class="home-page-video" style="right: 0;bottom: 0;width: 83vw;height: auto;">
                		<source src="/sis/studios/assets/videos/main-banner.mp4" type="video/mp4">
            		</video>
            	</div>
            </div>
			<?php if($arrTextImages[0]['image_2'] != ''){ ?>
				<div class="row">
					<div class="col-12">
						<div class="img-holder" style="background-image:url(/studios/studios-settings/images/<?= $arrTextImages[0]['image_2']?>);"></div>
					</div>
				</div>
			<?php } ?>
			<?php if($arrTextImages[0]['text'] != ''){ ?>
				<div class="row">
					<div class="col-12 mt-4">
						<?= $arrTextImages[0]['text']?>
					</div>
				</div>
			<?php } ?>
		<?php } ?>
		<div class="row">
			<div class="col-12 mt-4">
				<div class="col-md-12 custom-card bg-pink-tile">
					<p class="mb-4">Recommended:</p>
					<ol>
						<?php foreach ($arrRecommended as $key => $value) { ?>
						<li style="font-size: 20px"><?= strtoupper($value['item_name']) ?></li>
						<?php } ?>					
					</ol>
				</div>
			</div>			
		</div>
		<div class="row">
			<div class="col-6 mt-4">
				<div class="customer-account text-center">
					<a href="./?page=contact-tracing-form">
						<button class="btn bg-blue-tile" style="padding-top: 30px; padding-bottom: 30px; font-size: 16px; height: auto;"><?= $arrTranslate['Sign Up'] ?></button>
					</a>
				</div>
			</div>
			<div class="col-6 mt-4">
				<div class="customer-account text-center">
					<a href="./?page=contact-tracing-form&guest=true">
						<button class="btn bg-green-tile" style="padding-top: 30px; padding-bottom: 30px; font-size: 16px; height: auto;">Guest</button>
					</a>
				</div>
			</div>
		</div>		
	</div>

<?php } ?>
