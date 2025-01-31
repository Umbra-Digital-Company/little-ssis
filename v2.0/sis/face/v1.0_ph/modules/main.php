<?php

////////////////////// System Version
$system_version = "v1.0";
/////////////////////////////////////
if($_SESSION['user_type'] != 1){
	echo '<script> window.location = "/"; </script>';
}

$page='';
$col='';
if (isset($_GET['page'])){
	$page = $_GET['page'];
}

if (isset($_GET['col'])){
	$col = $_GET['col'];
}

?>

<div id="ssis_sidebar">
	<div class="sidebar-top d-flex align-items-center no-gutters">
		<div class="col">
			<div class="d-flex align-items-center">
				<a href="#" id="hide_sidebar">
					<img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="left arrow" class="img-fluid">
				</a>
				<p class="h2 mb-0 ml-4"><?= ucwords( $_SESSION['name'] ) ?></p>
			</div>
		</div>
		<img src="<?= get_url('images/logo') ?>/logo-full-dark.png" alt="left arrow" class="img-fluid logo">
	</div>
	<nav id="ssis_menu" class="mt-4">

		<?php //if ( $_SESSION['userlvl'] == '2' ) : ?>

			<ul class="nav-list nav flex-column mb-4">
				<li class="text-secondary text-uppercase mb-3 font-bold">Pages</li>
				<li>
					<a class="d-flex align-items-center" href="./?page=store-home">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-home.png);"></canvas>
						<span class="h3 ml-3">Home</span>
					</a>
				</li>
				<li>
					<a class="d-flex align-items-center" href="https://sunniessystems.com/dispatch" target="_blank">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-order-management.png);"></canvas>
						<span class="h3 ml-3">Order Management</span>
					</a>
				</li>
				
			</ul>

			<!-- <ul class="nav-list nav flex-column mb-4">
				<li class="text-secondary text-uppercase mb-3 font-bold">system</li>
				
				<li>
					<a class="d-flex align-items-center" href="./?page=updatedb">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-maintenance.png);"></canvas>
						<span class="h3 ml-3">Maintenance</span>
					</a>
				</li>
				
			</ul> -->

			<ul class="nav-list nav flex-column">
				<li class="text-secondary text-uppercase mb-3 font-bold">Account</li>
				<!-- <li>
					<a class="d-flex align-items-center" href="#" id="switch_account">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-switch-account.png);"></canvas>
						<span class="h3 ml-3">Switch Account</span>
					</a>
				</li> -->
				<li>
					<a class="d-flex align-items-center" href="/v2.0/sis/studios/func/logout.php?path_loc=ph">
						<canvas class="bg-danger" style="background-image:url(<?= get_url('images/icons') ?>/sidebar-logout.png);"></canvas>
						<span class="h3 ml-3">Logout</span>
					</a>
				</li>
			</ul>

		<?php //elseif ( $_SESSION['userlvl'] == '1') : ?>

			<!-- <ul class="nav-list nav flex-column mb-4">
				<li class="text-secondary text-uppercase mb-3 font-bold">Pages</li>
				<li>
					<a class="d-flex align-items-center" href="./?page=doctor">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-pending-list.png);"></canvas>
						<span class="h3 ml-3">Pending Customers</span>
					</a>
				</li>
				<li>
					<a class="d-flex align-items-center" href="./?page=doctor-complete">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-completed-list.png);"></canvas>
						<span class="h3 ml-3">Completed Customers</span>
					</a>
				</li>
				<li>
					<a class="d-flex align-items-center" href="./?page=change-order">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-order-management.png);"></canvas>
						<span class="h3 ml-3">Change Order</span>
					</a>
				</li>
			</ul> -->

			<!-- <ul class="nav-list nav flex-column">
				<li class="text-secondary text-uppercase mb-3 font-bold">Account</li>
				<li>
					<a class="d-flex align-items-center" href="#" id="switch_account">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-switch-account.png);"></canvas>
						<span class="h3 ml-3">Switch Account</span>
					</a>
				</li>
				
			</ul> -->

		<?php //endif ?>

	</nav>
</div>

<div id="admin-bar" class="d-flex align-items-center <?= ( $page == 'rate-us' ) ? 'justify-content-center' : 'justify-content-between' ?>">

	<?php if ( isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES' && !isset($_SESSION['doctor_progress']) ) : ?>

		<?php if ( $page != 'rate-us' ) : ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page"><img src="<?= get_url('images/icons') ?>/icon-exit.png" alt="exit" class="img-fluid"></a>
			</div>
		<?php endif ?>
		<div class="account-name">
			<p class="small m-0"><?= greetings() ?></p>
		</div>
		<div class="language">
			<img src="<?= get_url('images/icons') ?>/icon-language-primary.png" alt="language" class="img-fluid">
			<div class="lang-opt">
				<a href="/v2.0/sis/studios/func/process/switch_language.php?language_setting=us">English</a>
				<a href="/v2.0/sis/studios/func/process/switch_language.php?language_setting=vn">Vietnamese</a>
			</div>
		</div>

	<?php elseif ( isset($_SESSION['customer_page']) && isset($_SESSION['doctor_progress']) && $_SESSION['doctor_progress'] == 'YES' ) : ?>

		<div class="home">
			<a href="./?page=customer-examine&profile_id=<?= ( isset($_GET['profile_id']) ) ? $_GET['profile_id'] : '' ?>&orderNo=<?= ( isset($_GET['orderNo']) ) ? $_GET['orderNo'] : '' ?>&pres_id=<?= (isset($_GET['pres_id'])) ? $_GET['pres_id'] : '' ?>&comp=exam"><img src="<?= get_url('images/icons') ?>/icon-back.png" alt="back" class="img-fluid"></a>
		</div>
		<div class="account-name">
			<p class="small m-0 "><?= ucwords( $_SESSION['name'] ) ?></p>
		</div>
		<div class="notification">
			<a href="#"><img src="<?= get_url('images/icons') ?>/icon-notificaion.png" alt="notification" class="img-fluid"></a>
			<span class="notif-alert"></span>
		</div>

	<?php else : ?>

		<div class="account">
			<a href="#"><img src="<?= get_url('images/icons') ?>/icon-account.png" alt="account" class="img-fluid"></a>
		</div>
		<div class="account-name">
			<p class="small m-0 "><?= ucwords( $_SESSION['name'] ) ?></p>
		</div>
		<div class="language">
			<img src="<?= get_url('images/icons') ?>/icon-language-primary.png" alt="language" class="img-fluid">
			<div class="lang-opt">
				<a href="/v2.0/sis/studios/func/process/switch_language.php?language_setting=us">English</a>
				<a href="/v2.0/sis/studios/func/process/switch_language.php?language_setting=vn">Vietnamese</a>
			</div>
		</div>

	<?php endif ?>

</div>

<header id="ssis_header">
	<div id="navigation-tab">

		<?php switch ( $page ) {
			// assistant
			case 'store-home' 			: $custom_title = 'Dashboard'; break;
			// customer
			case 'health-declaration-form' 	: $custom_title = 'Health Declaration Form'; break;
			case 'select-store' 		: $custom_title = 'Select Store'; break;
			case 'select-store-studios' 		: $custom_title = 'Sunnies Studios'; break;
			case 'select-merch' 		: $custom_title = 'Merch'; break;
			case 'order-confirmation' : $custom_title = 'Order Confirmation'; break;
			case 'add-paper-bag' : $custom_title = 'Add Paper Bag'; break;
			case "order-dispatched": $custom_title = $arrTranslate['Order Confirmation']; break;

			case 'updatedb' : $custom_title = 'Maintenance'; break;
			

			default : $custom_title = $page;
		} ?>
		
		<?php if ( isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES' ) :

			switch ( $page ) {
				case 'health-declaration-form' 		: $step = 1; break;
				case 'select-store': case 'select-store-studios':   case 'select-merch':	$step = 2; break;
				case 'order-confirmation':	$step = 3; break;
				case 'add-paper-bag':	$step = 3; break;
				
				default : $step = '';
			}
		
		?>

			<div class="text-center">

				<?php if ( $page != 'rate-us' ) : ?>

					<p class="text-uppercase font-bold h2">step <?= $step ?></p>
					<h1 class="h1 mt-2"><?= $custom_title ?></strong></h1>

				<?php else : ?>

					<p class="text-uppercase font-bold">thank you</p>
					<h1 class="h1 mt-2"><?= $custom_title ?></h1>

				<?php endif ?>

			</div>

		<?php else : ?>

			<h1 class="h1 m-0 text-center"><?= $custom_title ?></h1>
			<div id="tabs" class="d-flex align-items-center justify-content-center">

				<?php if ( $_SESSION['userlvl'] == '2' ) : ?>

					<a href="./?page=store-home" class="<?= ( $page == 'store-home' ) ? 'active' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-home<?= ( $page == 'store-home' ) ? '-primary' : '' ?>.png" alt="Dashboard" class="img-fluid">
					</a>
					<a href="./?page=doctorque" class="<?= ( $page == 'doctorque' ) ? 'active' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-pending-list<?= ( $page == 'doctorque' ) ? '-primary' : '' ?>.png" alt="Doctor's Queue" class="img-fluid">
					</a>
					<a href="./?page=ordermanagement" class="<?= ( $page == 'ordermanagement' || $page == 'overview_details' ) ? 'active' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-order-management<?= ( $page == 'ordermanagement' || $page == 'overview_details' ) ? '-primary' : '' ?>.png" alt="Order Management" class="img-fluid">
					</a>

				<?php elseif ( $_SESSION['userlvl'] == '1' ) : ?>

					<?php if ( $page == 'doctor' || $page == 'doctor-complete' || $page == 'customerdetails' || $page == 'customer-examine' || $page == 'change-current-frame' || $page == 'add-services' ) : ?>

						<a href="./?page=doctor" class="<?= ( $page == 'doctor' || $page == 'customer-examine' || $page == 'change-current-frame' || $page == 'add-services' ) ? 'active' : '' ?>">
							<img src="<?= get_url('images/icons') ?>/icon-pending-list<?= ( $page == 'doctor' || $page == 'customer-examine' || $page == 'change-current-frame' || $page == 'add-services' ) ? '-theme-doctor' : '' ?>.png" alt="Pending List" class="img-fluid">
						</a>
						<a href="./?page=doctor-complete" class="<?= ( $page == 'doctor-complete' || $page == 'customerdetails' ) ? 'active' : '' ?>">
							<img src="<?= get_url('images/icons') ?>/icon-completed-list<?= ( $page == 'doctor-complete' || $page == 'customerdetails' ) ? '-theme-doctor' : '' ?>.png" alt="Pending List" class="img-fluid">
						</a>

					<?php else : ?>

						

					<?php endif ?>

				<?php endif ?>

			</div>

		<?php endif ?>

	</div>
</header>

<main class="<?= ( isset($_SESSION['customer_page']) ) ? 'customer-layout ' . $page : '' ?>">

	<?php if ( isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES' && !isset($_SESSION['doctor_progress']) ) : ?>

		<?php

			$progress_class = "";

			if ( isset($_SESSION['customer_id']) ) {
				$progress_class = 'step-1';
			}
			if ( ( $page == 'pickup-location' && isset($_SESSION['prescription']) ) OR ( isset($_SESSION['order_confirmation']) && isset($_SESSION['prescription']) ) ) {
				$progress_class = 'step-2';
			}
			if ( $page == 'order-confirmation' && isset($_SESSION['order_confirmation']) ) {
				$progress_class = 'step-3';
			}

		?>
		
		<?php if ( $page != 'rate-us' ) : ?>

			<?php

			// Check if guest
			if(isset($_GET['checkout']) && $_GET['checkout'] == 'guest') {
				
				$guestParam = "&checkout=guest";
				$guestVal 	= "guest";

			}
			else {

				$guestParam = "";
				$guestVal 	= "regular";

			};

			?>

			<div class="step-progress">
				<div class="d-flex justify-content-center">
					<a href="./?page=health-declaration-form" class="<?= ( isset($_SESSION['customer_id']) || $page == 'health-declaration-form' ) ? 'bg-success-lighten' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-edit-primary.png" alt="Account" class="img-fluid">
					</a>
					<a href="./?page=select-store" class="<?= ( (isset($_SESSION['customer_id']) && $page == 'order-confirmation' ) || $page == 'select-store'  || $page == 'select-store-studios' || $page == 'select-merch' || $page == 'add-paper-bag') ? 'bg-success-lighten' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-store-primary.png" alt="Frame" class="img-fluid">
					</a>
					<a href="./?page=order-confirmation&bpage=<?= (isset($_GET['bpage'])) ? $_GET['bpage'] : $_GET['page'] ?>" class="<?= ( $page == 'order-confirmation' || $page == 'add-paper-bag' ) ? 'bg-success-lighten' : '' ?>">
						<img src="<?= get_url('images/icons') ?>/icon-bag-primary.png" alt="Order confirm" class="img-fluid">
					</a>
					<span class="progress-bar <?= $progress_class ?>"></span>
				</div>
			</div>

			<div class="wrapper">
				<?php include("layout.php");?>
			</div>

		<?php else : ?>

			<div class="wrapper">
				<?php include("layout.php");?>
			</div>

		<?php endif; ?>

	<?php elseif ( isset($_SESSION['customer_page']) && isset($_SESSION['doctor_progress']) && $_SESSION['doctor_progress'] == 'YES' ) : ?>

		<?php

			$progress_class = "";

			if ( isset($_GET['pres_name']) || isset($_GET['vision']) ) {
				$progress_class = 'step-1';
			}
			if ( isset($_GET['vision']) ) {
				$progress_class = 'step-2';
			}
			if ( isset($_GET['vision']) && isset($_GET['lens']) && $page == 'select-upgrades' ) {
				$progress_class = 'step-3';
			}

		?>

		<div class="step-progress">
			<div class="d-flex justify-content-center">
				<a href="./?page=prescription-list&profile_id=<?= ( isset($_GET['profile_id']) ) ? $_GET['profile_id'] : '' ?>&orderNo=<?= ( isset($_GET['orderNo']) ) ? $_GET['orderNo'] : '' ?>&pres_id=<?= ( isset($_GET['pres_id']) ) ? $_GET['pres_id'] : '' ?>&cartID=<?= ( isset($_GET['cartID']) ) ? $_GET['cartID'] : '' ?>&frame_price=<?= (isset($_GET['frame_price'])) ? $_GET['frame_price'] : '1200' ?>" class="<?= ( isset($_GET['pres_name']) || isset($_GET['vision']) ) ? 'bg-doctor-lighten' : '' ?> <?= ( $page == 'prescription-list' ) ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-prescription-theme-doctor.png" alt="Prescription" class="img-fluid">
				</a>
				<a href="#" class="prevent <?= ( isset($_GET['vision']) ) ? 'bg-doctor-lighten' : '' ?> <?= ( $page == 'select-vision' ) ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-vision-theme-doctor.png" alt="Vision" class="img-fluid">
				</a>
				<a href="#" class="prevent <?= ( isset($_GET['lens']) ) ? 'bg-doctor-lighten' : '' ?> <?= ( $page == 'select-lenses' ) ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-lens-theme-doctor.png" alt="Lenses" class="img-fluid">
				</a>
				<a href="#" class="prevent <?= ( $page == 'select-upgrades' ) ? 'bg-doctor-lighten' : '' ?>">
					<img src="<?= get_url('images/icons') ?>/icon-upgrade-theme-doctor.png" alt="Upgrades" class="img-fluid">
				</a>
				<span class="progress-bar <?= $progress_class ?>"></span>
			</div>
		</div>

		<div class="wrapper">
			<?php include("layout.php");?>
		</div>

	<?php else : ?>

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
    .modal-content{
	border-radius: .5rem;
    }
    .modal-header{
        background-color : #F9F3EC !important;
        border-radius: 5px;
    }
    .modal-title, .close{
        
        opacity: 1;
    }
    .modal-dialog{
    max-width: 50%;
	}
	.modal-body{
		background-color : #F3E9DD !important;
	}
    .modal-body .details {
        /* 100% = dialog height, 120px = header + footer */
        max-height: 300px;
        overflow-y: auto;
    }
    .submit{
       
        border-radius:30px;
        padding-left: 30px;
        padding-right: 30px;
    }
    @media only screen and (max-width: 600px) {
        .modal-dialog{
        width: auto;
        max-width: 100%;
        }
    }
</style>
<div class="modal fade" id="modal-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title">View Cart</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body" style="height: 92vh; overflow-y:auto;">
			<hr class="spacing">
			<div id="item_cart">
			</div>
			<hr class="spacing">
        </div>
    </div>
</div>

