<style>

.page-transaction main {
  top: 90px !important;
  margin-top: auto !important;
}

</style>

<?php

////////////////////////////////////////////////////////////////

if ($_SESSION['user_type'] != 1) {
	echo '<script> window.location = "/"; </script>';
}


if (!isset($_SESSION['user_login']['warehouse_code'])) {

	$query = "SELECT 
                warehouse_code 
            FROM 
                store_codes_studios
            WHERE 
                store_code = '" . $_SESSION['user_login']['store_code'] . "' LIMIT 1;";

	$grabParams = array('warehouse_code');

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i = 0; $i < 1; $i++) {

				$tempArray[$grabParams[$i]] = ${'result' . ($i + 1)};
			};

			$_SESSION['user_login']['warehouse_code'] = $tempArray['warehouse_code'];
		};

		mysqli_stmt_close($stmt);
	} else {

		echo mysqli_error($conn);
	};
}


////////////////////////////////////////////////////////////////

$page = '';
$col = '';

if (isset($_GET['page'])) {
	$page = $_GET['page'];
}

if (isset($_GET['col'])) {
	$col = $_GET['col'];
}

switch ($page) {
	case 'health-declaration-form':
		$step = 1;
		break;
	case 'contact-tracing-form':
		$step = 1;
		break;
	case 'select-store':
	case 'select-store-studios':
	case 'select-antirad':
	case 'select-merch':
		$step = 2;
		break;
	case 'select-merch':
		$step = 2;
		break;
	case 'order-confirmation':
		$step = 3;
		break;
	case 'add-paper-bag':
		$step = 3;
		break;
	default:
		$step = '';
}

////////////////////////////////////////////////////////////////
// echo '<pre>';
// print_r($page);
// echo '</pre>';
?>


<style>
	.sidebar-top .container {
		max-width: 100% !important;
	}

	/* .doctor-hover:hover {
		
	} */
	#ssis_sidebar #ssis_menu li .doctor-hover:hover {
		background-color: #F8F5EF;
	}

</style>

<div id="ssis_sidebar">
	<div class="sidebar-top d-flex align-items-center justify-content-end no-gutters pb-3">
		<div class="col">

			<div class="d-flex align-items-center justify-content-between">
				<p style="font-size: 14px; color: #B7B7B7;"> SSIS v 4.0.0 </p>

				<p class="h2 mb-0 ml-4"><?= (isset($_SESSION['name'])) ? ucwords($_SESSION['name']) : '' ?></p>
				<a href="#" id="hide_sidebar">
					<img src="<?= get_url('images/icons') ?>/icon-close.png" alt="left arrow" class="img-fluid">
				</a>
			</div>
		</div>
	</div>

	<nav id="ssis_menu">

		<ul class="nav-list nav flex-column mb-2">
			<li class="text-secondary text-uppercase mb-3 font-bold"></li>
			<li style='padding: 0; margin-bottom: 16px;'>
				<a class="d-flex align-items-center" href="./?page=store-home">
					<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-home.png);"></canvas>

					<span class="h3 ml-3"><?= $arrTranslate['Home'] ?></span>
				</a>
			</li>

			<li style='padding: 0; margin-bottom: 16px;'>
				<a class="d-flex align-items-center" href="./?page=transactions&active=dispatch">
					<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-dispatch.png);"></canvas>
					<span class="h3 ml-3"><?= $arrTranslate['Dispatch'] ?></span>
				</a>
			</li>

			<?php if (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'ns') { ?>
				<li style='padding: 0;'>
					<a class="d-flex align-items-center" href="./?page=transactions&active=payment">
						<canvas
							style="background-image:url(<?= get_url('images/icons') ?>/icon-order-management.png);"></canvas>
						<span class="h3 ml-3">For Payment</span>
					</a>
				</li>
			<?php } ?>
		</ul>
		<hr style="border-top: 2px solid #eeeeee; max-width: 100%; margin: 1rem 2rem;">

		<ul class="nav-list nav flex-column">
			<li style='padding: 0; margin-bottom: 16px;'>
				<a class="d-flex align-items-center mb-4" href="/v2.0/sis/face/func/logout.php?path_loc=v1.0">
					<canvas style="background-image:url(<?= get_url('images/icons') ?>/sidebar-logout.png);"></canvas>
					<span class="h3 ml-3"><?= $arrTranslate['Logout'] ?></span>
				</a>
			</li>
		</ul>

	</nav>
</div>

<div id="admin-bar"
	class="d-flex align-items-center <?= ($page == 'rate-us') ? 'justify-content-center' : 'justify-content-between' ?> <?= ($page == 'order-confirmation' || $page == 'cart' || $page == 'transactions' || $page == 'account-form' || $page == 'order-dispatched') ? 'order-confirmation' : '' ?>"
	style="box-shadow: none;">

	<?php if (isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES'): ?>
		<?php if ($page != 'rate-us' && $page != 'account-form' && $page != 'cart' && $page != 'transactions' &&  $page != 'order-confirmation'): ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page"><img
						src="<?= get_url('images/icons') ?>/icon-arrow-left.png" alt="exit" class="img-fluid"></a>
			</div>
			<div class="account-name">
				<!-- <p class="small m-0 "><?= $arrTranslate['Dashboard']; ?></p> -->
				<img class="img-fluid" src="<?= get_url('images/logo') ?>/sunnies-face-logo-black.webp?v=1614047286"
					style=" max-width: 170px;">
			</div>
			<div class="account">
				<a href="#"><img src="<?= get_url('images/icons') ?>/icon-menu.png" alt="account" class="img-fluid"></a>
			</div>
		<?php endif ?>

		<?php if ($page == 'account-form'): ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page" style="height: 32px ; width: 32px;"><img
						src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>
			</div>
			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFFFFF; font-weight: 700; font-size: 20px"><?= strtoupper("Account") ?></p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>
		<?php endif ?>



		<?php if ($page == 'transactions'): ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page" style="height: 32px ; width: 32px;"><img
						src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>
			</div>
			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFFFFF; font-weight: 700; font-size: 20px"><?= strtoupper("Transactions") ?>
				</p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>
		<?php endif ?>


		<?php if ($page == 'cart'): ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page" style="height: 32px ; width: 32px;"><img
						src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>
			</div>
			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFFFFF; font-weight: 700; font-size: 20px"><?= strtoupper("Bag") ?>
				</p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>
		<?php endif ?>

		<?php if ($page == 'order-confirmation'): ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page" style="height: 32px ; width: 32px;" style="height: 32px ; width: 32px;"><img
						src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>
			</div>
			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFF; font-weight: 700; font-size: 20px">
					<?= strtoupper("Order Confirmation") ?>
				</p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>
		<?php endif ?>

	<?php else: ?>
		<?php if ($page == 'order-confirmation'): ?>
			<a href="./?page=select-store" class="exit-frame-selection" style="height: 32px ; width: 32px;"><img
					src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>

			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFF; font-weight: 700; font-size: 20px">
					<?= strtoupper("Order Confirmation") ?>
				</p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>


		<?php elseif ($page == 'transactions'): ?>
			<a href="./?page=select-store" class="exit-frame-selection" style="height: 32px ; width: 32px;"><img
					src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>
			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFFFFF; font-weight: 700; font-size: 20px"><?= strtoupper("Transactions") ?>
				</p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>

		<?php elseif ($page == 'cart'): ?>
			<a href="./?page=select-store" class="exit-frame-selection" style="height: 32px ; width: 32px;"><img
					src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>
			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFFFFF; font-weight: 700; font-size: 20px"><?= strtoupper("Bag") ?>
				</p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>

		<?php elseif ($page == 'order-dispatched'): ?>
			<a href="./?page=store-home" class="exit-frame-selection" style="height: 32px ; width: 32px;"><img
					src="<?= get_url('images/icons') ?>/icon-left-arrow-white.png" alt="exit" class="img-fluid"></a>
			<div class="account-name">
				<p class="h3 mb-0" style="color: #FFFFFF; font-weight: 700; font-size: 20px">
					<?= strtoupper("Order sent to cashier") ?>
				</p>
			</div>
			<div class="account">
				<a href="#" style="height: 32px ; width: 32px;"><img src="<?= get_url('images/icons') ?>/icon-menu-white.png"
						alt="account" class="img-fluid"></a>
			</div>

		<?php else: ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page"><img
						src="<?= get_url('images/icons') ?>/icon-arrow-left.png" alt="exit" class="img-fluid"></a>
			</div>

			<div class="account-name">
				<!-- <p class="small m-0 "><?= $arrTranslate['Dashboard']; ?></p> -->
				<img class="img-fluid" src="<?= get_url('images/logo') ?>/sunnies-face-logo-black.webp?v=1614047286"
					style=" max-width: 120px;">
			</div>
			<div class="account">
				<a href="#"><img src="<?= get_url('images/icons') ?>/icon-menu.png" alt="account" class="img-fluid"></a>
			</div>
		<?php endif ?>
	<?php endif ?>

</div>

<header id="ssis_header">
	<div id="navigation-tab">

		<?php

		switch ($page) {
				// assistant
			case 'store-home':
				$custom_title = $arrTranslate[greetings()];
				break;
				// customer
			case 'health-declaration-form':
				$custom_title = 'Health Declaration Form';
				break;
			case 'contact-tracing-form':
				$custom_title = 'Register Form';
				break;
			case 'select-store':
				$custom_title = 'Sunnies Studios';
				break;
			case 'select-store-lips':
				$custom_title = 'Lips';
				break;
			case 'select-store-face':
				$custom_title = 'Face';
				break;
			case 'select-store-brows':
				$custom_title = 'Brows';
				break;
			case 'select-store-eyes':
				$custom_title = 'Eyes';
				break;
			case 'select-store-skin':
				$custom_title = 'Skin';
				break;
			case 'select-store-cheeks':
				$custom_title = 'Cheeks';
				break;
			case 'select-store-nails':
				$custom_title = 'Nails';
				break;
			case 'select-store-sets':
				$custom_title = 'Sets';
				break;
			case 'select-store-all':
				$custom_title = ucwords($_GET['categoryAll']);
				break;
			case 'select-merch':
				$custom_title = 'Merch';
				break;
			case 'select-antirad':
				$custom_title = 'Anti-Radiation';
				break;
			case 'select-readers':
				$custom_title = 'Readers';
				break;
			case 'order-confirmation':
				$custom_title = $arrTranslate['Order Confirmation'];
				break;
			case 'add-paper-bag':
				$custom_title = 'Add Paper Bag';
				break;
			case "order-dispatched":
				$custom_title = $arrTranslate['Order Confirmation'];
				break;
			case "for-payments":
				$custom_title = 'For Payments';
				break;
			case 'updatedb':
				$custom_title = 'Maintenance';
				break;
			case 'transactions':
				$custom_title = 'Transactions';
				// Default
			default:
				$custom_title = $page;
		}

		if ($page == 'contact-tracing-form' && $_GET['guest'] == "true") {
			$custom_title = "Guest Checkout";
		}

		?>

		<?php if (isset($_SESSION['customer_page']) && isset($_SESSION['customer_page']) == 'YES'):
			switch ($page) {
				case 'health-declaration-form':
					$step = 1;
					break;
				case 'contact-tracing-form':
					$step = 1;
					break;
				case 'select-store':
				case 'select-store-studios':
				case 'select-merch':
					$step = 2;
					break;
				case 'order-confirmation':
					$step = 3;
					break;
				case 'add-paper-bag':
					$step = 3;
					break;

				default:
					$step = '';
			}

		?>

			<div class="text-center">

				<?php if ($page != 'rate-us') { ?>


					<?php if (strtolower($custom_title) == 'sunnies studios') { ?>

						<img class="img-fluid" src="<?= get_url('images/logo') ?>/sunnies-face-logo-black.webp?v=1614047286"
							style=" max-width: 120px;">

					<?php } else { ?>

						<h1 class="h1 mt-2">
							<?= isset($arrTranslate[$custom_title]) ? $arrTranslate[$custom_title] : $custom_title ?></strong>
						</h1>

					<?php } ?>

				<?php } else { ?>

					<p class="text-uppercase font-bold">thank you</p>
					<h1 class="h1 mt-2">
						<?= isset($arrTranslate[$custom_title]) ? $arrTranslate[$custom_title] : $custom_title ?>
					</h1>

				<?php } ?>

			</div>

		<?php else: ?>

			<h1 class="h1 m-0 text-center"><?= $custom_title ?></h1>
			<div id="tabs" class="d-flex align-items-center justify-content-center">

				<?php if ($_SESSION['userlvl'] == '2'): ?>

					<a href="./?page=store-home" class="<?= ($page == 'store-home') ? 'active' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-home<?= ($page == 'store-home') ? '-primary' : '' ?>.png"
							alt="Dashboard" class="img-fluid">
					</a>
					<a href="./?page=doctorque" class="<?= ($page == 'doctorque') ? 'active' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-pending-list<?= ($page == 'doctorque') ? '-primary' : '' ?>.png"
							alt="Doctor's Queue" class="img-fluid">
					</a>
					<a href="./?page=ordermanagement"
						class="<?= ($page == 'ordermanagement' || $page == 'overview_details') ? 'active' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-order-management<?= ($page == 'ordermanagement' || $page == 'overview_details') ? '-primary' : '' ?>.png"
							alt="Order Management" class="img-fluid">
					</a>

				<?php elseif ($_SESSION['userlvl'] == '1'): ?>

					<?php if ($page == 'doctor' || $page == 'doctor-complete' || $page == 'customerdetails' || $page == 'customer-examine' || $page == 'change-current-frame' || $page == 'add-services'): ?>

						<a href="./?page=doctor"
							class="<?= ($page == 'doctor' || $page == 'customer-examine' || $page == 'change-current-frame' || $page == 'add-services') ? 'active' : '' ?>">
							<img src="<?= get_url('images/icons') ?>/icon-pending-list<?= ($page == 'doctor' || $page == 'customer-examine' || $page == 'change-current-frame' || $page == 'add-services') ? '-theme-doctor' : '' ?>.png"
								alt="Pending List" class="img-fluid">
						</a>
						<a href="./?page=doctor-complete"
							class="<?= ($page == 'doctor-complete' || $page == 'customerdetails') ? 'active' : '' ?>">
							<img src="<?= get_url('images/icons') ?>/icon-completed-list<?= ($page == 'doctor-complete' || $page == 'customerdetails') ? '-theme-doctor' : '' ?>.png"
								alt="Pending List" class="img-fluid">
						</a>

					<?php else: ?>

					<?php endif ?>

				<?php else: ?>

					Welcome

				<?php endif ?>

			</div>

		<?php endif ?>

	</div>
</header>

<main class="<?= (isset($_SESSION['customer_page'])) ? 'customer-layout main-customer' . $page : '' ?> <?= ($page == 'customer-details') ? '"style="top: 90px; margin-top: auto;' : '' ?>">

	<?php if (isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES' && !isset($_SESSION['doctor_progress'])): ?>

		<?php
		$progress_class = "";

		if (isset($_SESSION['customer_id'])) {
			$progress_class = 'step-1';
		}
		if (($page == 'pickup-location' && isset($_SESSION['prescription'])) or (isset($_SESSION['order_confirmation']) && isset($_SESSION['prescription']))) {
			$progress_class = 'step-2';
		}
		if ($page == 'order-confirmation' && isset($_SESSION['order_confirmation'])) {
			$progress_class = 'step-3';
		}

		?>

		<?php if ($page != 'rate-us'): ?>

			<?php

			// Check if guest
			if (isset($_GET['checkout']) && $_GET['checkout'] == 'guest') {

				$guestParam = "&checkout=guest";
				$guestVal = "guest";
			} else {

				$guestParam = "";
				$guestVal = "regular";
			};

			?>

			<!-- <div class="step-progress">
				<div class="d-flex justify-content-center">
					<?php if (!isset($_SESSION['login_set'])) { ?>
						<a href="./?page=contact-tracing-form" class="<?= (isset($_SESSION['customer_id']) || $page == 'contact-tracing-form') ? 'bg-success-lighten' : '' ?>">
							<img src="<?= get_url('images/icons') ?>/icon-edibt-primary.png" alt="Account" class="img-fluid">
						</a>
					<?php } ?>
					<a href="./?page=select-store" class="<?= ((isset($_SESSION['customer_id']) && $page == 'order-confirmation') || $page == 'select-store' || $page == 'select-store-studios' || $page == 'select-merch' || $page == 'add-paper-bag') ? 'bg-success-lighten' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-store-primary.png" alt="Frame" class="img-fluid">
					</a>
					<a href="./?page=order-confirmation&bpage=<?= (isset($_GET['bpage'])) ? $_GET['bpage'] : $_GET['page'] ?>" class="<?= ($page == 'order-confirmation' || $page == 'add-paper-bag') ? 'bg-success-lighten' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-bag-primary.png" alt="Order confirm" class="img-fluid">
					</a>
					<span class="progress-bar <?= $progress_class ?>"></span>
				</div>
			</div> -->

			<div class="wrapper-2">
				<?php include("layout.php"); ?>
			</div>

		<?php else: ?>

			<div class="wrapper-2">
				<?php include("layout.php"); ?>
			</div>

		<?php endif; ?>

	<?php elseif (isset($_SESSION['customer_page']) && isset($_SESSION['doctor_progress']) && $_SESSION['doctor_progress'] == 'YES'): ?>

		<?php

		$progress_class = "";

		if (isset($_GET['pres_name']) || isset($_GET['vision'])) {
			$progress_class = 'step-1';
		}
		if (isset($_GET['vision'])) {
			$progress_class = 'step-2';
		}
		if (isset($_GET['vision']) && isset($_GET['lens']) && $page == 'select-upgrades') {
			$progress_class = 'step-3';
		}

		?>

		<div class="step-progress">
			<div class="d-flex justify-content-center">
				<a href="./?page=prescription-list&profile_id=<?= (isset($_GET['profile_id'])) ? $_GET['profile_id'] : '' ?>&orderNo=<?= (isset($_GET['orderNo'])) ? $_GET['orderNo'] : '' ?>&pres_id=<?= (isset($_GET['pres_id'])) ? $_GET['pres_id'] : '' ?>&cartID=<?= (isset($_GET['cartID'])) ? $_GET['cartID'] : '' ?>&frame_price=<?= (isset($_GET['frame_price'])) ? $_GET['frame_price'] : '1200' ?>"
					class="<?= (isset($_GET['pres_name']) || isset($_GET['vision'])) ? 'bg-doctor-lighten' : '' ?> <?= ($page == 'prescription-list') ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-prescription-theme-doctor.png" alt="Prescription"
						class="img-fluid">
				</a>
				<a href="#"
					class="prevent <?= (isset($_GET['vision'])) ? 'bg-doctor-lighten' : '' ?> <?= ($page == 'select-vision') ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-vision-theme-doctor.png" alt="Vision" class="img-fluid">
				</a>
				<a href="#"
					class="prevent <?= (isset($_GET['lens'])) ? 'bg-doctor-lighten' : '' ?> <?= ($page == 'select-lenses') ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-lens-theme-doctor.png" alt="Lenses" class="img-fluid">
				</a>
				<a href="#" class="prevent <?= ($page == 'select-upgrades') ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-upgrade-theme-doctor.png" alt="Upgrades"
						class="img-fluid">
				</a>
				<span class="progress-bar <?= $progress_class ?>"></span>
			</div>
		</div>

		<div class="wrapper">
			<?php include("layout.php"); ?>
		</div>

	<?php else: ?>

		<?php include("layout.php"); ?>

	<?php endif ?>

</main>



<div class="ssis-overlay"></div>
<div class="ssis-backdrop"></div>

<div class="ssis-searching">
	<div class="search-icon text-center">
		<img src="<?= get_url('images/icons') ?>/icon-search-animation.png" alt="Search" class="img-fluid">
		<p class="h1  mt-4">Searching...</p>
	</div>
</div>

<div class="ssis-loading">
	<div class="loading-icon text-center">
		<img src="<?= get_url('images/icons') ?>/icon-loading.png" alt="Search" class="img-fluid">
		<p class="h1  mt-4">Loading...</p>
	</div>
</div>
<style>
	body {
		background-color: #f0f0f0;
	}

	body #admin-bar {
		background-color: #fff;
		border-radius: 16px;
	}

	.order-confirmation {
		background-color: #956E46 !important;
	}

	#ssis_header {
		background-color: #e8e8e4;
	}

	.btn {
		border-radius: 28px;
		width: 100%;
		height: 45px;
	}

	.btn-black {
		background: #000000;
		color: #ffffff;
	}

	.modal-content {
		max-width: 747px !important;
		width: 100% !important;
		border-radius: .5rem;
	}

	.modal-header {
		padding-top: 20px;
		height: 72px;
		align-items: center;
		border-radius: 5px;
	}

	.modal-title,
	.close {
		opacity: 1;
	}

	.modal-body .details {
		/* 100% = dialog height, 120px = header + footer */
		max-height: 300px;
		overflow-y: auto;
	}

	.cart-item {
		background-color: #e8e8e4;
		border-radius: 15px;
	}

	.cart-item .product-title span {
		font-size: 12px;
		color: #b3a89b !important;
	}

	.cart-item .cart-item-image {
		width: 100%;
	}

	.submit {
		border-radius: 30px;
		padding-left: 30px;
		padding-right: 30px;
	}

	.color-list div {
		cursor: pointer;
	}
</style>
<div class="modal fade" id="modal-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="height: 94vh;">
			<div class="modal-header">
				<h5 class="modal-title"><?= $arrTranslate['View Cart'] ?></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-y:auto;">
				<div id="item_cart"></div>
			</div>
		</div>
	</div>
</div>

</style>
<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document" style="max-width: 100%; width: 590px; ">
		<div class="modal-content filter" style="height: 95vh; border-radius: 16px; margin: 15px auto ; ">
			<div class="modal-header">
				<h5 class="modal-title">Filter</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-y:auto;">

				<?php if (isset($arrCollectionsData)) { ?>

					<p class="h2 font-bold">Collections</p>
					<div class="row collections-list mb-5">

						<?php foreach ($arrCollectionsData as $value) {

							$selected = (in_array($value['code'], $getCollections)) ? 'btn-filter-selected' : 'btn-no-filter';

						?>
							<div class="col-6 mt-3">
								<div class="my-collection btn <?= $selected ?>" collectionsData="<?= $value['code'] ?>">
									<?= $value['name'] ?>
								</div>
							</div>

						<?php } ?>

					</div>

				<?php } ?>

				<?php if (isset($arrShapesData)) { ?>

					<p class="h2 font-bold mt-3">Shapes</p>
					<div class="row shapes-list mb-5">

						<?php foreach ($arrShapesData as $value) {

							$selected = (in_array($value['code'], $getShapes)) ? 'btn-filter-selected' : 'btn-no-filter';

						?>

							<div class="col-6 mt-3">
								<div class="my-shapes btn <?= $selected ?>" shapesData="<?= $value['code'] ?>">
									<?= $value['name'] ?>
								</div>
							</div>

						<?php } ?>

					</div>

				<?php } ?>

				<?php if (isset($arrFilterColors)) { ?>

					<p class="h2 font-bold mt-3">Colors</p>
					<div class="row color-list mb-5">

						<?php foreach ($arrFilterColors as $value) {

							$selected = (in_array($value['color'], $getColors)) ? 'btn-' . $value['color'] : 'btn-no-filter';

						?>

							<div class="col-6 mt-3">
								<div class="my-color btn <?= $selected ?>" colorData="<?= $value['color'] ?>">
									<?= ucwords(str_replace("_", " ", $value['color'])) ?>
								</div>
							</div>

						<?php } ?>

					</div>

				<?php } ?>

				<div class="d-flex justify-content-center">
					<input type="submit" class="btn btn-primary" id="filter-search-data" value="Filter">
				</div>

				<?php if (isset($_GET['filter']) && $_GET['filter']) { ?>

					<div class="d-flex justify-content-center mt-4">
						<a href="/v2.0/sis/face/v1.0/?page=<?= $_GET['page'] ?>">
							<div class="btn btn-link"
								style="color: #000 !important; text-decoration: underline !important;">Reset Filter</div>
						</a>
					</div>

				<?php } ?>

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-signup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="height: 94vh;">
			<div class="modal-header">
				<h5 class="modal-title">Sign up</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-y:auto;">
				<?php include 'store/sign-up-form-v2.php' ?>

			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="height: 94vh;">
			<div class="modal-header">
				<h5 class="modal-title">Login</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-y:auto;">
				<?php include 'store/login-form.php' ?>

			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-guest-register" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content" style="height: 94vh;">
			<div class="modal-header">
				<h5 class="modal-title">Register</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body" style="overflow-y:auto;">
				<?php include 'store/guest-register.php' ?>

			</div>
		</div>
	</div>
</div>
<div id="loading" class="modal" role="dialog" data-backdrop="static">
	<div class="modal-dialog modal-dialog-centered">

		<!-- Modal content-->
		<div class="modal-content" style="background-color: rgb(0, 0, 0, 0); border-style: none;">
			<div class="modal-body">
				<div class="d-flex justify-content-center">
					<img src="/images/loading.gif" width="100px" height="100px">
				</div>
			</div>
		</div>

	</div>
</div>

<?php if(isset($arrCart)){ ?>
<div id="myModal" class="modal">
  <div class="modal-content-promo">
      
    <div id="modal-data" class="modal-data" style="padding-top: 15px; padding-left: 17px;">
      <form id="form-check-promo" method="post">
        <div class="row mb-3">
			<div class="col-sm-2">	
				<label class="placeholder" style="margin-bottom: 0;">Email</label>
			</div>
          
          <div class="col-sm-10" style="font-size: 16px;">
            <input type="hidden" name="order_id" value="<?= $_SESSION['order_no']?>">
            <input type="hidden" name="email" class="form-control" value="<?= $arrCart[0]['email_address']?>">
            <?= $arrCart[0]['email_address']?>
          </div>
        </div>

        <div class="form-group d-flex">
          <input type="text" name="voucher_code" class="form-control" required>
		  <label for="voucher_code" class="placeholder">Voucher Code</label>
        </div>

        <div class="text-center">
          <i style="font-size: 14px;">Note that this promo code/voucher is used for sunniesclub only</i>
        </div>

        <div class="mt-2 text-center" style="font-size: 16px;">
          <span id="check-promo-message"></span>
        </div>

        <div class="form-group button-group d-flex flex-column">
          <button type="submit" class="btn btn-primary check_code">Check Code</button>
          <button type="submit" class="btn btn-primary use_code mt-2">Use Code</button>
		  <button type="button" class="btn btn-cancel close-button-promo mt-4"  id="closemodalx">Cancel</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>