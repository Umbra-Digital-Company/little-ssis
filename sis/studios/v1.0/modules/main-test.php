<?php 

////////////////////////////////////////////////////////////////

if($_SESSION['user_type'] != 1){
	echo '<script> window.location = "/"; </script>';
}

////////////////////////////////////////////////////////////////

$page = '';
$col  = '';

if (isset($_GET['page'])){
	$page = $_GET['page'];
}

if (isset($_GET['col'])){
	$col = $_GET['col'];
}

switch ( $page ) {
	case 'health-declaration-form' : $step = 1; break;
	case 'contact-tracing-form'    : $step = 1; break;
	case 'select-store'			   : 
	case 'select-store-studios'	   :   
	case 'select-antirad'	   :   
	case 'select-merch'			   : $step = 2; break;
	case 'select-merch'			   : $step = 2; break;
	case 'order-confirmation' 	   : $step = 3; break;
	case 'add-paper-bag' 		   : $step = 3; break;	
	default : $step = '';
}

////////////////////////////////////////////////////////////////

?>

<style type="text/css">
	
	main {
		top: 0;
		border-radius: 0;
    	background: #fff;
	}
	body header {
		position: relative;		
    	padding: 60px 10px !important;
	}
	#ssis_sidebar {
		background-color: #ffffff;
	}
	.form-control:focus ~ label.placeholder, .form-control:valid ~ label.placeholder {
		background-color: #ffffff;
	}	
	p {
		font-family: SharpGroteskSemiBold,serif;
	}

</style>

<div id="ssis_sidebar">
	<div class="sidebar-top d-flex align-items-center no-gutters">
		<div class="col">
			<div class="d-flex align-items-center">
				<a href="#" id="hide_sidebar">
					<img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="left arrow" class="img-fluid">
				</a>
				<p class="h2 mb-0 ml-4"><?= (isset($_SESSION['name'])) ? ucwords( $_SESSION['name'] ) : '' ?></p>
			</div>
		</div>
		<img src="<?= get_url('images/logo') ?>/logo-full-dark.png" alt="left arrow" class="img-fluid logo">
	</div>
	<nav id="ssis_menu" class="mt-4">

		<ul class="nav-list nav flex-column mb-4">
			<li class="text-secondary text-uppercase mb-3 font-bold"><?= $arrTranslate['Pages'] ?></li>
			<li>
				<a class="d-flex align-items-center" href="./?page=store-home">
					<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-home.png);"></canvas>
					<span class="h3 ml-3"><?= $arrTranslate['Home'] ?></span>
				</a>
			</li>
			<?php if(isset($_SESSION['dispatch_studios_no_access']) && !$_SESSION['dispatch_studios_no_access']) { ?>
				<li>
					<a class="d-flex align-items-center" href="/studios/dispatch-studios">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-order-management.png);"></canvas>
						<span class="h3 ml-3"><?= $arrTranslate['Dispatch'] ?></span>
					</a>
				</li>
			<?php } ?>
			<?php if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'ns') { ?>
				<li>
					<a class="d-flex align-items-center" href="./?page=for-payments">
						<canvas style="background-image:url(<?= get_url('images/icons') ?>/icon-order-management.png);"></canvas>
						<span class="h3 ml-3">For Payments</span>
					</a>
				</li>
			<?php } ?>
		</ul>

		<ul class="nav-list nav flex-column">
			<li class="text-secondary text-uppercase mb-3 font-bold"><?= $arrTranslate['Account'] ?></li>
			<li>
				<a class="d-flex align-items-center" href="/sis/studios/func/logout.php?path_loc=v1.0">
					<canvas class="bg-danger" style="background-image:url(<?= get_url('images/icons') ?>/sidebar-logout.png);"></canvas>
					<span class="h3 ml-3"><?= $arrTranslate['Logout'] ?></span>
				</a>
			</li>
		</ul>

	</nav>
</div>

<div id="admin-bar" class="d-flex align-items-center <?= ( $page == 'rate-us' ) ? 'justify-content-center' : 'justify-content-between' ?>" style="box-shadow: none;">

	<?php if ( isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES' && !isset($_SESSION['doctor_progress']) ) : ?>

		<?php if ( $page != 'rate-us' ) : ?>
			<div class="home">
				<a href="#" class="prevent" id="exit_customer_page"><img src="<?= get_url('images/icons') ?>/icon-home-primary.png" alt="exit" class="img-fluid"></a>
			</div>
		<?php endif ?>
		<div class="account-name">
			<p class="small m-0"><?= $arrTranslate['Step '.$step] ?></p>
		</div>
		<?php if ( $page != 'rate-us' ) : ?>
			<div class="language">
				<img src="<?= get_url('images/icons') ?>/icon-language-primary.png" alt="language" class="img-fluid">
				<div class="lang-opt">
					<a href="/sis/studios/func/process/switch_language.php?language_setting=us">English</a>
					<a href="/sis/studios/func/process/switch_language.php?language_setting=vn">Vietnamese</a>
				</div>
			</div>
		<?php endif ?>

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
			<a href="#"><img src="<?= get_url('images/icons') ?>/icon-account-assistant.png" alt="account" class="img-fluid"></a>
		</div>
		<div class="account-name">
			<!-- <p class="small m-0 "><?= $arrTranslate['Dashboard']; ?></p> -->
			<img class="img-fluid" src="<?= get_url('images/logo') ?>/sunnies-studios-logo-black.png?v=1614047286" style=" max-width: 120px;">
		</div>
		<div class="language">
			<img src="<?= get_url('images/icons') ?>/icon-language-primary.png" alt="language" class="img-fluid">
			<div class="lang-opt">
				<a href="/sis/studios/func/process/switch_language.php?language_setting=us">English</a>
				<a href="/sis/studios/func/process/switch_language.php?language_setting=vn">Vietnamese</a>
			</div>
		</div>

	<?php endif ?>

</div>

<header id="ssis_header">
	<div id="navigation-tab">

		<?php 

			switch ( $page ) {
				// assistant
				case 'store-home' 			   : $custom_title = $arrTranslate[greetings()]; break;
				// customer
				case 'health-declaration-form' : $custom_title = 'Health Declaration Form'; break;
				case 'contact-tracing-form'    : $custom_title = 'Register Form'; break;
				case 'select-store' 		   : $custom_title = 'Sunnies Studios'; break;
				case 'select-store-studios'    : $custom_title = 'Sunnies Studios'; break;
				case 'select-merch' 		   : $custom_title = 'Merch'; break;
				case 'select-antirad' 		   : $custom_title = 'Anti-Radiation'; break;
				case 'order-confirmation'      : $custom_title = $arrTranslate['Order Confirmation']; break;
				case 'add-paper-bag' 		   : $custom_title = 'Add Paper Bag'; break;
				case "order-dispatched" 	   : $custom_title = $arrTranslate['Order Confirmation']; break;
				case "for-payments" 	   	   : $custom_title = 'For Payments'; break;
				case 'updatedb' 			   : $custom_title = 'Maintenance'; break;			
				// Default
				default 					   : $custom_title = $page;
			} 

			if($page == 'contact-tracing-form' && $_GET['guest'] == "true") {
				$custom_title = "Guest Checkout";
			}

		?>
		
		<?php if ( isset($_SESSION['customer_page']) && $_SESSION['customer_page'] == 'YES' ) :

			switch ( $page ) {
				case 'health-declaration-form' 		: $step = 1; break;
				case 'contact-tracing-form' 		: $step = 1; break;
				case 'select-store'					: 
				case 'select-store-studios'			:   
				case 'select-merch'					: $step = 2; break;
				case 'order-confirmation' 			: $step = 3; break;
				case 'add-paper-bag' 				: $step = 3; break;
				
				default : $step = '';
			}
		
		?>

			<div class="text-center">

				<?php if ( $page != 'rate-us' ) { ?>

					
					<?php if(strtolower($custom_title) == 'sunnies studios') { ?>

						<img class="img-fluid" src="<?= get_url('images/logo') ?>/sunnies-studios-logo-black.png?v=1614047286" style=" max-width: 120px;">						

					<?php } else { ?>						

						<h1 class="h1 mt-2"><?= $custom_title ?></strong></h1>

					<?php } ?>

				<?php } else { ?>

					<p class="text-uppercase font-bold">thank you</p>
					<h1 class="h1 mt-2"><?= $custom_title ?></h1>

				<?php } ?>

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

				<?php else : ?>

					Welcome

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
						<a href="./?page=contact-tracing-form" class="<?= ( isset($_SESSION['customer_id']) || $page == 'contact-tracing-form' ) ? 'bg-success-lighten' : '' ?>">
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

	<?php if(!isset($_SESSION['guest_customer'])) { ?>
		<style>
			.step-progress{
		        display: inline !important;
		    }
		    #search_frame{
		    	margin-top: 30px !important;
		    }
		</style>
	<?php } ?>
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

	body #admin-bar {
		background-color: #ffffff;
		border-radius: 0;
	    -webkit-border-radius: 0;
	    -moz-border-radius: 0;
	    -ms-border-radius: 0;
	    -o-border-radius: 0;
	}
	#ssis_header {
        background-color: #e8e8e4;
    }

	.btn {
		border-radius: 10px;
		width: 100%;
		height: 45px;
	}
	.btn-black {
		background: #000000;
		color: #ffffff;
	}
    .modal-content{
		border-radius: .5rem;
    }
    .modal-header{
        /*background-color : #F9F3EC !important;*/
        border-radius: 5px;
    }
    .modal-title, .close{        
        opacity: 1;
    }
    .modal-dialog{
    	max-width: 60%;
	}
	#modal-filter .modal-dialog, #modal-signup .modal-dialog{
    	max-width: 90%;
	}
	.modal-body{
		/*background-color : #F3E9DD !important;*/
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
    .submit{       
        border-radius:30px;
        padding-left: 30px;
        padding-right: 30px;
    }
    @media only screen and (max-width: 992px) {

    	body > .container {
    		overflow-y: hidden;
    		height: 100vh;
    	}
	}
    @media only screen and (max-width: 600px) {

    	.cart-item .mt-xs-0 {
	    	margin-top: 0 !important;
	    }
         #modal-signup .modal-dialog, #modal-filter .modal-dialog, .modal-dialog{
        	width: auto;
        	max-width: 100%;
        }
    }

    .color-list div{
    	cursor: pointer;
    }
    
</style>
<div class="modal fade" id="modal-item" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: 94vh;">
	        <div class="modal-header">
	            <h5 class="modal-title">View Cart</h5>
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

<style type="text/css">
	.my-collection,
	.my-shapes,
	.my-color {
		text-align: center;
		white-space: break-spaces;
		font-size: 8px;
	}
	.btn-no-filter {
		border: 1px solid #ccc;
	}
	.btn-filter-selected {
		border: 2px solid #000;
	}
	.btn-brown {
		background-color: #8c674c;
		color: #fff;
	}
	.btn-tort {
		background: url(//cdn.shopify.com/s/files/1/0172/4383/2374/files/tort_color_group_48x.png?v=1599802863);
		background-repeat: no-repeat;
		background-size: cover;
		color: #fff;
	}
	.btn-white {
		border: 2px solid #000;
	}
	.btn-pink {
		background-color: #e8a29c;
		color: #fff;
	}
	.btn-blue {
		background-color: #4479b1;
		color: #fff;
	}
	.btn-green {
		background-color: #698a49;
		color: #fff;
	}
	.btn-yellow {
		background-color: #eacf63;
		color: #fff;
	}
	.btn-gold {
		background-color: gold;
		color: #000;
	}
	.btn-silver {
		background-color: silver;
		color: #fff;
	}
	.btn-clear {
		border: 2px solid #000;
	}
	.btn-red {
		background-color: #bf3e3e;
		color: #fff;
	}
	.btn-grey {
		background-color: #b2b2b5;
		color: #fff;
	}
	.btn-rose_gold {
		background-color: #ff9a78;
		color: #fff;
	}
	.btn-purple {
		background-color: #be92c4;
		color: #fff;
	}
	.btn-nude {
		background-color: #e2ceae;
		color: #fff;
	}
</style>
<div class="modal fade" id="modal-filter" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document" style="max-width: 100%; margin: 0;">
        <div class="modal-content" style="height: 100vh; border-radius: 0; padding: 15px;">
	        <div class="modal-header">
	            <h5 class="modal-title">Filter</h5>
	            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	            <span aria-hidden="true">&times;</span>
	            </button>
	        </div>
	        <div class="modal-body" style="overflow-y:auto;">

	        	<?php if(isset($arrCollectionsData)){ ?>

		        	<p class="h2 font-bold">Collections</p>
		        	<div class="row collections-list mb-5">

						<?php foreach ($arrCollectionsData as $value) {

							$selected = (in_array($value['code'], $getCollections)) ? 'btn-filter-selected' : 'btn-no-filter';

						?>
							<div class="col-6 mt-3">
								<div class="my-collection btn <?= $selected?>" collectionsData="<?= $value['code'] ?>"><?= $value['name'] ?></div>
							</div>

						<?php } ?>

					</div>

				<?php } ?>

				<?php if(isset($arrShapesData)){ ?>

					<p class="h2 font-bold mt-3">Shapes</p>
					<div class="row shapes-list mb-5">

						<?php foreach ($arrShapesData as $value) {

							$selected = (in_array($value['code'], $getShapes)) ? 'btn-filter-selected' : 'btn-no-filter';

						?>

							<div class="col-6 mt-3">
								<div class="my-shapes btn <?= $selected?>" shapesData="<?= $value['code'] ?>"><?=  $value['name'] ?></div>
							</div>

						<?php } ?>

					</div>					

				<?php } ?>

				<?php if(isset($arrFilterColors)){ ?>

					<p class="h2 font-bold mt-3">Colors</p>
					<div class="row color-list mb-5">

						<?php foreach ($arrFilterColors as $value) {

							$selected = (in_array($value['color'], $getColors)) ? 'btn-'.$value['color'] : 'btn-no-filter';

						?>

							<div class="col-6 mt-3">
								<div class="my-color btn <?= $selected?>" colorData="<?= $value['color'] ?>"><?= ucwords(str_replace("_", " ", $value['color'])) ?></div>
							</div>

						<?php } ?>

					</div>

				<?php } ?>
				
				<div class="d-flex justify-content-center">
					<input type="submit" class="btn btn-primary" id="filter-search-data" value="Filter">
				</div>

				<?php if(isset($_GET['filter']) && $_GET['filter']) { ?>

					<div class="d-flex justify-content-center mt-4">
						<a href="/sis/studios/v1.0/?page=<?= $_GET['page'] ?>">
							<div class="btn btn-link" style="color: #000 !important; text-decoration: underline !important;">Reset Filter</div>
						</a>
					</div>

				<?php } ?>
				
	        </div>
	    </div>
	 </div>
</div>

<div class="modal fade" id="modal-signup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content" style="height: 94vh;">
            <div class="modal-header">
                <h5 class="modal-title">Sign up</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" style="overflow-y:auto;">
                <?php include 'store/sign-up-form.php' ?>
                
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

