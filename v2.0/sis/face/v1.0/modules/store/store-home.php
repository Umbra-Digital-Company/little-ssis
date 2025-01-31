<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '') {

	//include("./modules/xlog.php");
	echo '<script>	window.location.href="/v2.0/sis/face/v1.0"; </script>';
} else { ?>

	<?php

	include("./modules/includes/grab_rates.php");
	include("./modules/includes/products/grab_face_settings.php");
	include("./modules/includes/products/grab_total_top.php");

	/*
	* Set session to detect that you are NOT in the CUSTOMER PAGE
	* this show all the menu for assistant
	*
	*/
	if (isset($_SESSION['customer_page']) || isset($_SESSION['login_customer'])) {
		unset($_SESSION["pickup"]);
		unset($_SESSION["customer_id"]);
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

	<link rel="stylesheet" type="text/css" href="/v2.0/sis/face/v1.0/modules/store/little_sis.css?v=12345">
	<style type="text/css">

		
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
		.bg-black-tile {
			background-color: #352b27;
		}

		.bg-orange-tile>p,
		.bg-blue-tile,
		.bg-green-tile,
		.bg-black-tile {
			color: #fff;
		}

		.bg-yellow-tile>p,
		.bg-pink-tile>p,
		.bg-tan-tile>p {
			color: #352b27;
		}

		.img-holder {
			height: 300px;
			width: 100%;
			background-size: cover;
			background-position: center;
		}
		.video {
			height: 300px;
			width: 100%;
			background-size: cover;
			border-radius: 16px;
		}
	</style>

	<!-- <div class="container "> -->
		<div class="row align-items-stretch row-tiles mb-4" style="display: none;">
			<div class="col-6 align-items-stretch" style="align-self: stretch;">
				<div class="col-12 custom-card h-100 bg-orange-tile">
					<p>Total Orders Today:</p>
					<p class="text-left mt-4" style="font-size: 20px;"><?= (count($arrCount) > 0) ? $arrCount[0]['count'] : 0 ?></p>
				</div>
			</div>
			<div class="col-6 align-items-stretch">
				<div class="col-12 custom-card h-100 bg-yellow-tile">
					<p>Top Selling Item:</p>
					<p class="text-left mt-4" style="font-size: 20px;"> <?= (count($arrTop) > 0) ? strtoupper($arrTop[0]['color']) : '' ?></p>
				</div>
			</div>
		</div>

		<?php if (count($arrTextImages) > 0) { ?>
			<?php if ($arrTextImages[0]['video'] != '' && $arrTextImages[0]['video_status'] == 1) { ?>

				<div class="d-flex justify-content-center mb-4">
					<video autoplay muted loop playsinline style="width: 100%;height: auto; border-radius: 16px;">
						<source src="/face/face-settings/videos/<?= $arrTextImages[0]['video'] ?>" type="video/mp4">
					</video>
				</div>

			<?php } ?>
			
			<!-- recommended -->
			<div class="row">
				<div class="col-12 mb-4">
					<div class="col-md-12 custom-card bg-white-tile " style="border-radius: 16px;">
					<p class="mb-2 font font-weight-bold ml-3 mt-3" style="font-size: 18px"><?= $arrTranslate['Recommended'] ?></p>
						<ol>
							<?php foreach ($arrRecommended as $key => $value) { ?>
								<li style="font-size: 16px"><?= strtoupper($value['item_name']) ?></li>
							<?php } ?>
						</ol>
					</div>
				</div>
			</div>
			<?php if ($arrTextImages[0]['image_1'] != '' && $arrTextImages[0]['image_1_status'] == 1) { ?>

				<div class="d-flex justify-content-center mb-4" style ="border-radius: 16px;">
					<div class="img-holder" style="background-image:url(/face/face-settings/images/<?= $arrTextImages[0]['image_1'] ?>); border-radius: 16px;"></div>
				</div>

			<?php } ?>
			<?php if ($arrTextImages[0]['text'] != '') { ?>

				<div class="d-flex justify-content-center mb-4">
					<?= $arrTextImages[0]['text'] ?>
				</div>

			<?php } ?>
		<?php } ?>

		<?php if ($arrTextImages[0]['image_2'] != ''  && $arrTextImages[0]['image_2_status'] == 1) { ?>

			<div class="d-flex justify-content-center mb-4" style= "padding-bottom: 100px; border-radius: 16px;"> 
				<div class="img-holder" style="background-image:url(/face/face-settings/images/<?= $arrTextImages[0]['image_2'] ?>); border-radius: 16px;"></div>
			</div>

		<?php } ?>

		<div id="bottom-content" class=" d-flex bg-white p-2 text-center align-items-center justify-content-center" style="position: fixed; bottom: 0; left: 0; width: 100%; z-index: 1;">
			<div id="bottom-content-inner" style=" width: 575px; padding: 20px 0;">
		
				<div class="row" >
					<div class="col-12 ">
						<div class="customer-account text-center">
							<a href="modules/store/auto_guest.php">
							<button class="btn btn-primary d-flex align-items-center justify-content-center" style='height: 56px; font-size: 16px;'>
								Start
								<img src="<?= get_url('images/icons') ?>/icon-arrow-right.png" alt="exit" class="img-fluid ml-3" style="max-height: 24px;">
							</button>
							</a>
						</div>
					</div>
					
				</div>
			</div>
		
		</div>
	<!-- </div> -->

<?php } ?>