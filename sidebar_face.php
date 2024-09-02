<?php
function issetVal($group,$menu){
    return ( isset( $_SESSION['user_access']["{$group}"]["{$menu}"] ) ) ? $_SESSION['user_access']["{$group}"]["{$menu}"] : 0 ;
}

function get_sidebar($page = "", $main = "") { 

    if(!isset($_SESSION['id'])){
        header("Location: https://sunniessystems.com/");
    }
	// Grab User Position
	$userLevel = $_SESSION['user_login']['userlvl'];
	$username  = $_SESSION['user_login']['username'];
	$storeCode = $_SESSION['user_login']['store_code'];

?>
<style>
	
	.dropdown {
	  position: relative;
	  display: inline-block;
	  border: none;
	}

	.dropdown-content {
	  display: none;
	  position: absolute;
	  background-color: #283033;
	  min-width: 160px;
	  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
	  z-index: 1;
	  margin-left: -8.8vh;
	  border-top-left-radius: 10px;
	  border-bottom-left-radius: 10px;
	}
	@media only screen and (max-width: 800px) {
	  .dropdown-content {
		  margin-left: -9.8vh;
		}
	}

	.dropdown-content a {
	  color: #fff !important;
	  padding: 12px 16px;
	  text-decoration: none;
	  display: block;
	   border-top-left-radius: 10px;
	  border-bottom-left-radius: 10px;
	}

	.dropdown-content a:hover {background-color: #354146; color: #fff !important;}

	.dropdown:hover .dropdown-content {display: block;}
	#topbar{
		background: #B3A89B;
	}
	#topbar h1,#topbar p{
		color : #000 !important;
	}
	/*#sidebar{
		background-color: #3d3933;
	}
	#sidebar .logo {
	    border-bottom: 1px solid #1a1b15;
	}
	#sidebar .navigation .sidebar-menu li a {
	   
	    border-top: 1px solid #1a1b15;
	    border-bottom: 1px solid #1a1b15;
	    color : #d7d3d3 !important;
	}
	#sidebar .navigation .sidebar-menu p {
	    border-top: 1px solid #1a1b15;
	    border-bottom: 1px solid #1a1b15;
	    color : #a19d9d !important;
	}
	#sidebar .navigation .sidebar-menu li a.active {
    background-color: #1a1b15;
    color : #fff !important;
    }
    #sidebar #admin-logout {
	    background: #3d3933;
	}*/
	.table-responsive thead tr th, .table-header-black, .table-responsive tfoot tr th  {
	    background: #B3A89B !important;
	    color: #000 !important;
	}
	table thead tr th{
		color: #000 !important;
	}
</style>
	<div id="sidebar" <?= $userLevel ?>>
		<div class="logo d-flex justify-content-start">
			<img src="<?= get_url('images/logo/logo-full-light-inline.png') ?>" alt="SSIS" class="img-fluid">
			<?php if(isset($_SESSION['access_brands']) && count($_SESSION['access_brands']) > 1){ ?>
				<div class="dropdown form-control pt-0 pr-0 mt-1 ml-3" style="max-height: 20px; display: none;">
					<img src="/assets/images/icons/icon-switch-brand.png" style="width: 30px; height: 30px;">
				  	<div class="dropdown-content">
					  	<?php
					  	foreach ($_SESSION['access_brands'] as $key => $value) { 
					  		$valueText = str_replace('_', ' ', $value);
					  		$valueText = ($valueText == 'specs') ? 'Optical' : $valueText;
					  		$valueText = ($valueText == 'studios') ? 'Sun' : $valueText;
					  		$valueText = ($valueText == 'face') ? 'Face' : $valueText;
					  		$valueText = ($valueText == 'cup point') ? 'Cup Point' : $valueText;
					  	?>
						    <a href="/brand-select/switch.php?brand=<?= $value ?>" class="text-center bold" style="color: #fff;"><?=$valueText ?></a>
						<?php } ?>
				  </div>
				</div>
			
		<?php } ?>
			<img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="close-sidebar img-fluid d-block d-xl-none">
		</div>
		<div class="navigation activate-scrollbar">

			<?php if(isset($_SESSION['access_brands']) && count($_SESSION['access_brands']) > 1){ ?>

				<nav class="main-menu sidebar-menu" style="background-color: #6c6c6c; padding-top: 4px; padding-bottom: 4px;">
				    <select class="form-control" style="color: #fff; border-radius: 0; border: none; cursor: pointer;" onchange="location = this.value;">

				    	<?php

						  	foreach ($_SESSION['access_brands'] as $key => $value) { 

						  		$valueText = str_replace('_', ' ', $value);
						  		$valueText = ($valueText == 'specs') ? 'Sunnies Specs Optical' : $valueText;
						  		$valueText = ($valueText == 'studios') ? 'Sunnies Studios' : $valueText;
						  		$valueText = ($valueText == 'face') ? 'Sunnies Face' : $valueText;
						  		$valueText = ($valueText == 'cup point') ? 'Cup Point' : $valueText;

						  		$selected = ($valueText == 'Sunnies Face') ? 'selected': '';

					  	?>
						    
						    <option value="/brand-select/switch.php?brand=<?= $value ?>" <?= $selected ?>><?= $valueText ?></option>

						<?php } ?>
				    </select>
				</nav>

			<?php } ?>

			<?php if(isset($_SESSION['user_access']['sunnies_face'])) { ?>

				<nav class="vvm-menu sidebar-menu">
					<p class="small text-uppercase">Sunnies Face</p>
					<ul class="nav flex-column">
						<?php if(issetVal('sunnies_face','dashboard_face')==1) { ?>
							<li class="text-capitalize">
								<a href="/face/dashboard/face" class="<?= ($page=="dashboard") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Dashboard
								</a>
							</li>
						<?php } ?>

					<?php if(issetVal('sunnies_face','dispatch_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/dispatch-face/" class="<?= ($page=="dispatch") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'dispatch' ) ? 'sidebar-dispatch-active.png' : 'sidebar-dispatch.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								Dispatch
							</a>
						</li>
					<?php } ?>
					</ul>
				</nav>
			<?php } ?>
			<!-- //END:: SUNNIES FACE -->
			<?php if(isset($_SESSION['user_access']['aim_face'])) { ?>

				<nav class="vvm-menu sidebar-menu">
					<p class="small text-uppercase">AIM Face</p>
					<ul class="nav flex-column">


					<?php if(issetVal('aim_face','history_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-history" class="<?= ($page=="history-admin-face") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history-admin-face' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								History
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','history_all_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-history-all">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								History All
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','stock_movement_admin_face')==1) { ?>

						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Stock Movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','receive_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-receive">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Receive
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','request_approval_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-request-approval">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Request Approval
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','transfer_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-transfer">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Transfer
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','current_request_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-request">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Current Request
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','good_issue_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-good-issue">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Good Issue
							</a>
						</li>
					<?php } ?>
					<?php if(issetVal('aim_face','good_received_admin_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/admin/face-good-received">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Good Received
							</a>
						</li>
					<?php } ?>

					<!-- STORE -->
					<?php if(issetVal('aim_face','damage_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/face-damage">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								damage
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','history_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/face-history">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								history
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','stock_movement_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/face-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								stock movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','pullout_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/face-pullout">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								good issue
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','receive_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/face-receive">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								receive
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','reports_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/face-reports">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								variance reports
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','transfer_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/face-transfer">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								transfer
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','inventory_lookup_store_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/store/inventory-lookup-face">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Inventory Lookup
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','stock_movement_audit_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/audit/face-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','physical_count_audit_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/audit/face-physical-count">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								physical count
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','reports_audit_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/audit/face-reports">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								reports
							</a>
						</li>
					<?php } ?>

					<!-- WAREHOUSE -->

					<?php if(issetVal('aim_face','stock_movement_warehouse_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/warehouse/face-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								stock movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','pullout_warehouse_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/warehouse/face-pullout">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								good issue
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','receive_warehouse_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/warehouse/face-receive">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								receive
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','reports_warehouse_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/warehouse/face-reports">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								variance reports
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','transfer_warehouse_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/warehouse/face-transfer">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								transfer
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','request_warehouse_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/warehouse/face-request">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								inventory request
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_face','history_warehouse_face')==1) { ?>
						<li class="text-capitalize">
							<a href="/face/inventory/warehouse/face-history">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								history
							</a>
						</li>
					<?php } ?>
					</ul>
				</nav>
					
			<?php } ?>
			<!-- //END:: AIM FACE -->
			<?php if(isset($_SESSION['user_access']['poll_51']) && issetVal('poll_51','sunnies_face_poll_51')==1) {  ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">Poll 51</p>
					<ul class="nav flex-column">
							<li class="text-capitalize">
								<a href="/face/system/poll-51/face/" class="<?= ($page=="sunnies-face-poll-51") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'sunnies-face-poll-51' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Sunnies Face
								</a>
							</li>
					</ul>
				</nav>

			<?php  } ?>
		</div>
		<a href="/process/logout.php" id="admin-logout">
			<button type="button" class="btn">sign out</button>
		</a>
	</div>
<?php } ?>