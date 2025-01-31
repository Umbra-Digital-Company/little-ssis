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
				<div class="dropdown form-control pt-0 pr-0 mt-1 ml-3" style="max-height: 20px;">
					<img src="/assets/images/icons/icon-switch-brand.png" style="width: 30px; height: 30px;">
				  	<div class="dropdown-content">
					  	<?php
					  	foreach ($_SESSION['access_brands'] as $key => $value) { 
					  		$valueText = str_replace('_', ' ', $value);
					  		$valueText = ($valueText == 'specs') ? 'Optical' : $valueText;
					  		$valueText = ($valueText == 'studios') ? 'Sun' : $valueText;
					  	?>
						    <a href="/brand-select/switch.php?brand=<?= $value ?>" class="text-center bold" style="color: #fff;"><?=$valueText ?></a>
						<?php } ?>
				  </div>
				</div>
			
		<?php } ?>
			<img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="close-sidebar img-fluid d-block d-xl-none">
		</div>
		<div class="navigation activate-scrollbar">
			<?php if(isset($_SESSION['user_access']['sunnies_studios'])) { ?>

				<nav class="vvm-menu sidebar-menu">
					<p class="small text-uppercase">Sunnies Studios</p>
					<ul class="nav flex-column">
						<?php if(issetVal('sunnies_studios','dashboard_studios')==1) { ?>
							<li class="text-capitalize">
								<a href="/studios/dashboard/studios" class="<?= ($page=="dashboard-studios") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-studios' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Dashboard Studios
								</a>
							</li>
						<?php } ?>

					<?php if(issetVal('sunnies_studios','studios_settings')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/studios-settings/" class="<?= ($page=="studios-settings") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'studios-settings' ) ? 'sidebar-dispatch-active.png' : 'sidebar-dispatch.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								Studios Settings
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('sunnies_studios','dispatch_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/dispatch-studios/" class="<?= ($page=="dispatch-studios") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'dispatch-studios' ) ? 'sidebar-dispatch-active.png' : 'sidebar-dispatch.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								Dispatch Studios
							</a>
						</li>
					<?php } ?>
					</ul>
				</nav>
			<?php } ?>
			<!-- //END:: SUNNIES STUDIOS -->

			<?php if(isset($_SESSION['user_access']['aim_studios'])) { ?>

				<nav class="vvm-menu sidebar-menu">
					<p class="small text-uppercase">AIM Studios</p>
					<ul class="nav flex-column">


					<?php if(issetVal('aim_studios','history_admin_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/admin/studios-history" class="<?= ($page=="history-admin-studios") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history-admin-studios' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								History
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','history_all_admin_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/admin/studios-history-all">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								History All
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','stock_movement_admin_studios')==1) { ?>

						<li class="text-capitalize">
							<a href="/studios/inventory/admin/studios-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Stock Movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','receive_admin_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/admin/studios-receive">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Receive
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','request_approval_admin_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/admin/studios-request-approval">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Request Approval
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','transfer_admin_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/admin/studios-transfer">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Transfer
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','current_request_admin_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/admin/studios-request">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Current Request
							</a>
						</li>
					<?php } ?>

					<!-- AUDIT -->
					<?php if(issetVal('aim_studios','stock_movement_audit_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/audit/studios-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','phyiscal_count_audit_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/audit/studios-physical-count">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								physical count
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','reports_audit_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/audit/studios-reports">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								reports
							</a>
						</li>
					<?php } ?>

					<!-- DASHBOARD -->
					<?php if(issetVal('aim_studios','history_dashboard_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/dashboard/studios-history">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								history
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','request_dashboard_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/dashboard/studios-request">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								inventory request
							</a>
						</li>
					<?php } ?>


					<!-- LAB -->
					<?php if(issetVal('aim_studios','damage_lab_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/lab/studios-damage">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								damage
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','stock_movement_lab_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/lab/studios-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								stock movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','pullout_lab_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/lab/studios-pullout">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								good issue
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','transfer_lab_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/lab/studios-transfer">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								transfer
							</a>
						</li>
					<?php } ?>

					<!-- STORE -->
					<?php if(issetVal('aim_studios','damage_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/studios-damage">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								damage
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','history_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/studios-history">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								history
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','stock_movement_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/studios-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								stock movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','pullout_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/studios-pullout">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								good issue
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','receive_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/studios-receive">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								receive
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','reports_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/studios-reports">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								variance reports
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','transfer_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/studios-transfer">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								transfer
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','inventory_lookup_store_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/store/inventory-lookup-studios">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								Inventory Lookup
							</a>
						</li>
					<?php } ?>

					<!-- WAREHOUSE -->

					<?php if(issetVal('aim_studios','stock_movement_warehouse_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/warehouse/studios-movement">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								stock movement
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','pullout_warehouse_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/warehouse/studios-pullout">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								good issue
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','receive_warehouse_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/warehouse/studios-receive">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								receive
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','reports_warehouse_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/warehouse/studios-reports">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								variance reports
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','transfer_warehouse_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/warehouse/studios-transfer">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								transfer
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','request_warehouse_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/warehouse/studios-request">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								inventory request
							</a>
						</li>
					<?php } ?>

					<?php if(issetVal('aim_studios','history_warehouse_studios')==1) { ?>
						<li class="text-capitalize">
							<a href="/studios/inventory/warehouse/studios-history">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								history
							</a>
						</li>
					<?php } ?>


					</ul>
				</nav>
					
			<?php } ?>
			<!-- //END:: AIM STUDIOS -->
			<?php if(isset($_SESSION['user_access']['poll_51'])) {  ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">Poll 51</p>
					<ul class="nav flex-column">


						<?php if(issetVal('poll_51','sunnies_studios')==1) { ?>

							<li class="text-capitalize">
								<a href="/studios/system/poll-51/studios/" class="<?= ($page=="poll-51") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'poll-51' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Sunnies Studios
								</a>
							</li>

						<?php } ?>
						
					</ul>
				</nav>

			<?php  } ?>
		</div>
		<a href="/process/logout.php" id="admin-logout">
			<button type="button" class="btn">sign out</button>
		</a>
	</div>
<?php } ?>