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
		body>.container {
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
	</style>

	<div class="container-fluid little-sis">
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
					<video autoplay muted loop playsinline style="width: 100%;height: auto;">
						<source src="https://www.sunniessystems.com/face/face-settings/videos/<?= $arrTextImages[0]['video'] ?>" type="video/mp4">
					</video>
				</div>

			<?php } ?>
			<?php if ($arrTextImages[0]['image_1'] != '' && $arrTextImages[0]['image_1_status'] == 1) { ?>

				<div class="d-flex justify-content-center mb-4">
					<div class="img-holder" style="background-image:url(https://www.sunniessystems.com/face/face-settings/images/<?= $arrTextImages[0]['image_1'] ?>);"></div>
				</div>

			<?php } ?>
			<?php if ($arrTextImages[0]['text'] != '') { ?>

				<div class="d-flex justify-content-center mb-4">
					<?= $arrTextImages[0]['text'] ?>
				</div>

			<?php } ?>
		<?php } ?>
		<div class="row">
			<div class="col-12 mb-4">
				<div class="col-md-12 custom-card bg-pink-tile">
					<p class="mb-4">Recommended:</p>
					<ol>
						<?php foreach ($arrRecommended as $key => $value) { ?>
							<li style="font-size: 14px"><?= strtoupper($value['item_name']) ?></li>
						<?php } ?>
					</ol>
				</div>
			</div>
		</div>
		<?php if ($arrTextImages[0]['image_2'] != ''  && $arrTextImages[0]['image_2_status'] == 1) { ?>

			<div class="d-flex justify-content-center mb-4">
				<div class="img-holder" style="background-image:url(https://www.sunniessystems.com/face/face-settings/images/<?= $arrTextImages[0]['image_2'] ?>);"></div>
			</div>

		<?php } ?>
		<div class="row">
			<div class="col-6 mt-4">
				<div class="customer-account text-center">
					<a href="./?page=contact-tracing-form&type=sign-up">
						<button class="btn bg-tan-tile" style="padding-top: 30px; padding-bottom: 30px; font-size: 16px; height: auto;"><?= $arrTranslate['Sign Up'] ?></button>
					</a>
				</div>
			</div>
			<div class="col-6 mt-4">
				<div class="customer-account text-center">
					<a href="./?page=contact-tracing-form&type=log-in">
						<button class="btn bg-tan-tile" style="padding-top: 30px; padding-bottom: 30px; font-size: 16px; height: auto;">Log In</button>
					</a>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-12 mt-4">
				<div class="customer-account text-center">
					<a href="./?page=contact-tracing-form&guest=true">
						<button class="btn bg-tan-tile" style="padding-top: 30px;padding-bottom: 30px;font-size: 16px;height: auto;">Continue as Guest</button>
					</a>
				</div>
			</div>			
		</div>
	</div>

<?php } ?>