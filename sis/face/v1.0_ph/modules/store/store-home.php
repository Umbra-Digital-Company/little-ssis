<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if (!isset($_SESSION["store_code"]) && $_SESSION["store_code"] == '' ) {

	//include("./modules/xlog.php");
	echo '<script>	window.location.href="/sis/studios/'.$system_version.'"; </script>';	
} else { ?>

	<?php

	include("./modules/includes/grab_rates.php");

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
		echo "<script>window.location.reload(true)</script>";
	}

	?>

	<!-- <p class="text-center h3"><?= $arrTranslate[greetings()] ?>!</p> -->

	<?php

		$arrRating = ["angry","confused","cool","excited","amused"];
		if ( !empty($arrTotalRates) ) {
			$countRating = array(
				"angry" 	=> get_rates_status('angry'),
				"confused" 	=> get_rates_status('confused'),
				"cool" 		=> get_rates_status('cool'),
				"excited" 	=> get_rates_status('excited'),
				"amused" 	=> get_rates_status('amused')
			);

			$maxs = array_keys($countRating, max($countRating));
			$rate = $maxs[0];
		} else {
			$rate = '';
		}

		switch ( $rate ) {
			case 'angry' :
				$class = 'text-dark';
				$text  = 'Angry';
				$img   = '/ssis/assets/images/icons/rate-angry.png';
				break;
			case 'confused' :
				$class = 'text-dark';
				$text  = 'Confused';
				$img   = '/ssis/assets/images/icons/rate-confused.png';
				break;
			case 'cool' :
				$class = 'text-dark';
				$text  = 'Cool';
				$img   = '/ssis/assets/images/icons/rate-cool.png';
				break;
			case 'excited' :
				$class = 'text-dark';
				$text  = 'Excited';
				$img   = '/ssis/assets/images/icons/rate-excited.png';
				break;
			case 'amused' :
				$class = 'text-dark';
				$text  = 'Amused';
				$img   = '/ssis/assets/images/icons/rate-amused.png';
				break;
			default :
				$class = 'text-secondary';
				$text  = 'No feedback yet';
				$img = '';
				break;
		}

	?>

	<div class="ratings-status d-none <?= $rate != '' ? '' : 'starting' ?>">
		<div class="emoji-status">

			<?php if ( $rate != 'ignored' ) : ?>

				<img src="<?= $img ?>" alt="<?= $rate ?>" class="img-fluid d-block">

			<?php endif ?>

		</div>

		<p class="text-center <?= $class ?> mt-3 mb-3">Current Rating: <?= $text ?></p>
	</div>

	<?php if ( $rate != '' ) : ?>

		<div class="store-statistics mt-4 d-none">
			<div class="rate-stats mt-4">
				<div class="d-flex m-0">

					<?php for ( $i = 0; $i < sizeof($arrRating); $i++ ) : ?>

						<div class="col text-center">
							<img src="<?= get_url('images/icons') ?>/rate-<?= $arrRating[$i] ?>.png" alt="rate-<?= $arrRating[$i] ?>" class="img-fluid">
							<!-- <p class="small mt-2"><?= $arrRating[$i] ?></p> -->
							<span class="count  text-center small"><?= get_rates_status($arrRating[$i]) ?></span>
						</div>

					<?php endfor ?>

				</div>
			</div>
			<div class="mt-3 d-flex flex-row flex-nowrap">
				<div class="col-6">
					<div class="stats-wrap text-center">
						<p class="count"><?= count($arrTotalRates) ?></p>
						<p class="text-center text-secondary font-bold">Total Customers</p>
					</div>
				</div>
				<div class="col-6">
					<div class="stats-wrap text-center">
						<p class="count"><?= get_total_rates('ignored') ?></p>
						<p class="text-center text-secondary font-bold">Total Reactions</p>
					</div>
				</div>
			</div>
		</div>

	<?php endif ?>

	<div class="mt-4 custom-card">
		<p class="h2 font-bold text-center"><?= $arrTranslate['Data and Statistics coming soon'] ?></p>
	</div>

	<div class="customer-account text-center mt-5">
		<a href="./?page=health-declaration-form"><button class="btn btn-primary"><?= $arrTranslate['Go To Customer Page'] ?></button></a>
	</div>

	<div class="customer-account text-center mt-4">
		<a href="./?page=health-declaration-form&guest=true"><button class="btn btn-black"><?= $arrTranslate['Continue as Guest'] ?></button></a>
	</div>

<?php } ?>
