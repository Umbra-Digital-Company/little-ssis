<?php



if(!isset($_SESSION)){
	session_save_path($sDocRoot ."/cgi-bin/tmp");
	session_start();
}

// echo "<br>";
// print_r($_SESSION);
// echo "</br>";

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

	/*.dropdown:hover .switch {background-color: #3e8e41;}*/
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

						  		$selected = ($valueText == 'Sunnies Specs Optical') ? 'selected': '';

					  	?>
						    
						    <option value="/brand-select/switch.php?brand=<?= $value ?>" <?= $selected ?>><?= $valueText ?></option>

						<?php } ?>
				    </select>
				</nav>

			<?php } ?>

			<?php if(isset($_SESSION['user_access']['dashboard'])) {?>

				<nav class="main-menu sidebar-menu">
					<p class="small text-uppercase">Dashboard</p>
						<ul class="nav flex-column">

						<?php if(issetVal('dashboard','philippines')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/philippines" class="<?= ($page=="dashboard-philippines") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-philippines' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Philippines
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('dashboard','vietnam')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/vietnam" class="<?= ($page=="dashboard-vietnam") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-vietnam' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Vietnam
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('dashboard','virtual_store_ph')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/shopify-ph" class="<?= ($page=="dashboard-virtual-store-philippines") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-virtual-store-philippines' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Virtual Store - PH
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('dashboard','virtual_store_int')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/shopify-int" class="<?= ($page=="dashboard-virtual-store-international") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-virtual-store-international' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Virtual Store - INT
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('dashboard','affiliate')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/affiliate" class="<?= ($page=="dashboard-affiliate") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-affiliate' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Affiliate
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('dashboard','dashboard')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/philippines" class="<?= ($page=="dashboard") ? 'active' :@ '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Dashboard
								</a>
							</li>					

						<?php } ?>

						<?php if(issetVal('dashboard','promo_code')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/promocode" class="<?= ($page=="dashboard-promo Code") ? 'active' :@ '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-promo Code' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Promo Code
								</a>
							</li>					

						<?php } ?>

						<?php if(issetVal('dashboard','dashboard_daily_login')==1) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/daily-login" class="<?= ($page=="dashboard-daily-login") ? 'active' :@ '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-daily-login' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Man Power Count
								</a>
							</li>					

						<?php } ?>

					</ul>
				</nav>

			<?php } ?>	
			<!-- //END:: DASHBOARD -->

			<?php if( isset($_SESSION['user_access']['main_menu']) || isset($_SESSION['user_access']['aim']) ) {?>

				<nav class="main-menu sidebar-menu">
					<p class="small text-uppercase">AIM</p>
					<ul class="nav flex-column">

						<?php if(issetVal('main_menu','stock_movement_runner')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/runner/orders/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									stock movement
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','on_hand_runner')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/runner/on-hand/" class="<?= ($page=="on-hand") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'on-hand' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									on hand
								</a>
							</li>

						<?php } ?>

						
						<?php if(issetVal('main_menu','stock_movement_auditor')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/audit/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									stock movement
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','variance_report_auditor')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/audit/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
									<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
									Variance Report
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','physical_count_page_auditor')==1) { ?>
							
							<li class="text-capitalize">
								<a href="/inventory/audit/physical-count/" class="<?= ($page=="physical-count") ? 'active' : '' ?>">
									<?php $icon_report = ( $page == 'physical-count' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
									Physical Count Page
								</a>
							</li>

						<?php } ?>

						
						<?php if(issetVal('main_menu','stock_movement_warehouse')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									stock movement
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','stock_transfer_warehouse')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
									<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
									Stock Transfer
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','good_issue_warehouse')==1) { ?>

								<li class="text-capitalize">
									<a href="/inventory/warehouse/pullout/" class="<?= ($page=="good-issue") ? 'active' : '' ?>">
										<?php $icon_pullout = ( $page == 'good-issue' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
										<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_pullout) ?>)"></canvas>
										Good Issue
									</a>
								</li>

						<?php } ?>

						<?php if(issetVal('main_menu','receive_warehouse')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
									<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
									Receive
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','inventory_request_warehouse')==1) { ?>

								<li class="text-capitalize">
									<a href="/inventory/warehouse/request/" class="<?= ($page=="inventory-request") ? 'active' : '' ?>">
										<?php $icon_request = ( $page == 'inventory-request' ) ? 'sidebar-request-active.png' : 'sidebar-request.png' ?>
										<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
										Inventory Request
									</a>
								</li>

						<?php } ?>

						<?php if(issetVal('main_menu','variance_report_warehouse')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
									<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
									Variance Report
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','history_warehouse')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									History
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','history_all_warehouse')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/history-all/" class="<?= ($page=="history-all") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'history-all' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									History-all
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','stock_movement_store')==1) { ?>
						
							<li class="text-capitalize">
								<a href="/inventory/store/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									stock movement
								</a>
							</li>

						<?php } ?>


						<?php if(issetVal('main_menu','inventory_lookup_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
									<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
									Inventory Lookup
								</a>
							</li>

						<?php } ?>


						<?php if(issetVal('main_menu','stock_transfer_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
									<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
									Stock Transfer
								</a>
							</li>

						<?php } ?>


						<?php if(issetVal('main_menu','receive_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
									<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
									Receive
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','damage_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/damage/" class="<?= ($page=="damage") ? 'active' : '' ?>">
									<?php $icon_damage = ( $page == 'damage' ) ? 'sidebar-damage-active.png' : 'sidebar-damage.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_damage) ?>)"></canvas>
									Damage
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','variance_report_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
									<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
									Variance Report
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','history_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									History
								</a>
							</li>

						<?php } ?>
						
						<?php if(issetVal('main_menu','stock_movement_lab')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/lab/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									stock movement
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','inventory_lookup_lab')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/lab/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
									<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
									Inventory Lookup
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','stock_transfer_lab')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/lab/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
									<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
									Stock Transfer
								</a>
							</li>

						<?php } ?>

						<!-- <li class="text-capitalize">
							<a href="/inventory/lab/pullout/" class="<?= ($page=="pullout") ? 'active' : '' ?>">
								<?php $icon_pullout = ( $page == 'pullout' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_pullout) ?>)"></canvas>
								Pullout
							</a>
						</li> -->

						<?php if(issetVal('main_menu','receive_lab')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/lab/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
									<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
									Receive
								</a>
							</li>

						<?php } ?>


						<?php if(issetVal('main_menu','damage_lab')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/lab/damage/" class="<?= ($page=="damage") ? 'active' : '' ?>">
									<?php $icon_damage = ( $page == 'damage' ) ? 'sidebar-damage-active.png' : 'sidebar-damage.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_damage) ?>)"></canvas>
									Damage
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','variance_report_lab')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/lab/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
									<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
									Variance Report
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','history_lab')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/lab/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									History
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','stock_movement_admin')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/admin/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									Stock Movement
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','stock_transfer_admin')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/admin/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
									<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
									Stock Transfer
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','receive_admin')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/admin/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
									<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
									Receive
								</a>
							</li>

						<?php } ?>

							
						<?php if(issetVal('main_menu','request_approval_admin')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/admin/request-approval/" class="<?= ($page=="request-approval") ? 'active' : '' ?>">
									<?php $icon_approval = ( $page == 'request-approval' ) ? 'sidebar-approval-active.png' : 'sidebar-approval.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_approval) ?>)"></canvas>
									Request Approval
								</a>
							</li>

						<?php } ?>
								
						<?php if(issetVal('main_menu','inventory_lookup_admin')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
								<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
								Inventory Lookup
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','history_all_admin')==1) { ?>
						
							<li class="text-capitalize">
								<a href="/inventory/admin/history-all/" class="<?= ($page=="history-all") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history-all' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								 History (all branches)
								</a>
							</li>

						<?php } ?>


						<?php if(issetVal('main_menu','variance_report_admin')==1) { ?>
							
							<li class="text-capitalize">
								<a href="/inventory/admin/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
									<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
									Variance Report
								</a>
							</li> 

						<?php } ?>

						<?php if(issetVal('main_menu','history_admin')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/admin/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									History
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','current_request_admin')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/admin/request/" class="<?= ($page=="Current Request") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'Current Request' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									Current Requests
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','beginning_inventory_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/beginning-inventory/" class="<?= ($page=="beginning-inventory") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'beginning-inventory' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									Beginning Inventory
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('main_menu','pull_out_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/pullout/" class="<?= ($page=="pullout") ? 'active' : '' ?>">
									<?php $icon_pullout = ( $page == 'pullout' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_pullout) ?>)"></canvas>
									Pullout
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('aim','stock_movement')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/dashboard/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
									<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
									stock movement
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('aim','inventory_lookup')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
									<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
									Inventory Lookup
								</a>
							</li>

						<?php } ?>


						<?php if(issetVal('aim','variance_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/dashboard/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
									<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
									variance report
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('aim','request_approval')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/dashboard/request/" class="<?= ($page=="request-approval") ? 'active' : '' ?>">
									<?php $icon_request = ( $page == 'request-approval' ) ? 'sidebar-request-active.png' : 'sidebar-request.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Request Approval
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('aim','history')==1) { ?>
							
							<li class="text-capitalize">
								<a href="/inventory/dashboard/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
									<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
									history
								</a>
							</li>

						<?php } ?>

					</ul>
				</nav>

			<?php } ?>	
			<!-- //END:: MAIN MENU -->


			<?php if(isset($_SESSION['user_access']['ssis'])) { ?>

				<?php 

					if($userLevel == 1 || $userLevel == 3) {

						$dispatchLink = '/dispatch/';

					}
					else {

						$dispatchLink = '/dispatch-supervisor/';

					};

				?>

				<nav class="ssis-menu sidebar-menu">
					<p class="small text-uppercase">ssis</p>
					<ul class="nav flex-column">

						<?php if(issetVal('ssis','dispatch')==1) { ?>

							<li class="text-capitalize">
								<a href="<?= $dispatchLink ?>">
									<canvas style="background-image: url(<?= get_url('images/icons/sidebar-dispatch.png') ?>)"></canvas>
									dispatch
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('ssis','daily_login')==1) { ?>

							<li class="text-capitalize">
								<a href="/daily-login/" class="<?= ($page=="daily-login") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'daily-login' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Daily Login
								</a>
							</li>

						<?php } ?>


<!-- 					<?php if(issetVal('ssis','laboratory')==1) { ?>

							<li class="text-capitalize">
								<a href="/list/">
									<canvas style="background-image: url(<?= get_url('images/icons/sidebar-lab.png') ?>)"></canvas>
									laboratory
								</a>
							</li>

						<?php }	?>

						<?php if(issetVal('ssis','reorder_report')==1) { ?>
					
							<li class="text-capitalize">
								<a href="/re-order-report/">
									<canvas style="background-image: url(<?= get_url('images/icons/sidebar-report.png') ?>)"></canvas>
									Reorder Report
								</a>
							</li>

						<?php }	?>

						<?php if(issetVal('ssis','lens_po_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/lens-po-report" class="<?= ($page=="lens-po-report") ? 'active' : '' ?>">
								<?php $icon_request = ( $page == 'lens-P.O.-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Lens P.O. Report
								</a>
							</li>

						<?php }	?>	

						<?php if(issetVal('ssis','lens_sales_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/lens-sales-report" class="<?= ($page=="lens-sales-report") ? 'active' : '' ?>">
								<?php $icon_request = ( $page == 'lens-sales-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Lens Sales Report
								</a>
							</li>

						<?php }	?>	 -->

						<?php if(issetVal('ssis','profiles_report_vietnam')==1) { ?>

							<li class="text-capitalize">
								<a href="/profiles-report/vietnam">
								<?php $icon_request = ( $page == 'profiles-report-vietnam' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Profiles Report Vietnam
								</a>
							</li>

						<?php }	?>	

						<?php if(issetVal('ssis','sales_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/sales-report">
								<?php $icon_request = ( $page == 'sales-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Sales Report
								</a>
							</li>

						<?php }	?>	


<!-- 						<?php if(issetVal('maintenance','patients')==1) { ?>

							<li class="text-capitalize">
								<a href="/patient-records/" class="<?= ($page=="patients") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'patients' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									patient profile
								</a>
							</li>

						<?php } ?> -->

						<?php if(issetVal('ssis','dispatch_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/dispatch-report/" class="<?= ($page=="dispatch-report") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'dispatch-report' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Dispatch Report
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('ssis','product_assortment')==1) { ?>

							<li class="text-capitalize">
								<a href="/product-assortment/" class="<?= ($page=="product-assortment") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'product-assortment' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Product Assortment
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('ssis','the_archive')==1) { ?>

							<li class="text-capitalize">
								<a href="/the-archive/" class="<?= ($page=="the-archive") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'the-archive' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									The Archive
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('ssis','vn_sales_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/report/" class="<?= ($page=="vn-sales-report") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'vn-sales-report' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									VN Sales Report
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('ssis','patient_historical_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/power-bi/patient-historical-store" class="<?= ($page=="patient-historical-store") ? 'active' :@ '' ?>">
									<?php $icon_dashboard = ( $page == 'patient-historical-store' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Patient Historical(Store)
								</a>
							</li>					

						<?php } ?>

						<?php if(issetVal('ssis','lens_calculation')==1) { ?>

							<li class="text-capitalize">
								<a href="/lens-calculation" class="<?= ($page=="doctor-calculation") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'doctor-calculation' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Doctor Calculation
								</a>
							</li>

						<?php } ?>

					</ul>
				</nav>

			<?php } ?>	

			<?php if(isset($_SESSION['user_access']['lab'])) {?>

				<nav class="main-menu sidebar-menu">
					<p class="small text-uppercase">LAB</p>
						<ul class="nav flex-column">

						<?php if(issetVal('lab','laboratory')==1) { ?>

							<li class="text-capitalize">
								<a href="/list/">
									<canvas style="background-image: url(<?= get_url('images/icons/sidebar-lab.png') ?>)"></canvas>
									laboratory
								</a>
							</li>

						<?php }	?>

						<?php if(issetVal('lab','reorder_report')==1) { ?>
					
							<li class="text-capitalize">
								<a href="/re-order-report/">
									<canvas style="background-image: url(<?= get_url('images/icons/sidebar-report.png') ?>)"></canvas>
									Reorder Report
								</a>
							</li>

						<?php }	?>

						<?php if(issetVal('lab','lens_sales_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/lens-sales-report" class="<?= ($page=="lens-sales-report") ? 'active' : '' ?>">
								<?php $icon_request = ( $page == 'lens-sales-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Lens Sales Report
								</a>
							</li>

						<?php }	?>	


						</ul>
				</nav>

			<?php } ?>	
			<!-- //END:: LAB -->

			<?php if(isset($_SESSION['user_access']['profile'])) {?>

				<nav class="main-menu sidebar-menu">
					<p class="small text-uppercase">PROFILE</p>
						<ul class="nav flex-column">

							<?php if(issetVal('profile','patient_records')==1) { ?>

								<li class="text-capitalize">
									<a href="/patient-records/" class="<?= ($page=="patient-records") ? 'active' : '' ?>">
										<?php $icon_users = ( $page == 'inventory-fix' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
										<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
										Patient Records
									</a>
								</li>

							<?php } ?>


							<?php if(issetVal('profile','profile_segmentation')==1) { ?>

								<li class="text-capitalize">
									<a href="/profile-segmentation/" class="<?= ($page=="profile_segmentation") ? 'active' : '' ?>">
										<?php $icon_patients = ( $page == 'profile-segmentation' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
										<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
										Profile Segmentation
									</a>
								</li>

							<?php } ?>

							<?php if(issetVal('profile','patient_profile')==1) { ?>

								<li class="text-capitalize">
									<a href="/patient-profile/" class="<?= ($page=="patient-profile") ? 'active' : '' ?>">
										<?php $icon_patients = ( $page == 'patient-profile' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
										<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
										patient profile - BI
									</a>
								</li>

							<?php } ?>

						</ul>
				</nav>

			<?php } ?>	
			<!-- //END:: PROFILE -->

			<?php if(isset($_SESSION['user_access']['incentives'])) {?>

				<nav class="main-menu sidebar-menu">
					<p class="small text-uppercase">INCENTIVES</p>
						<ul class="nav flex-column">

						<?php if(issetVal('incentives','lens_po_report')==1) { ?>

							<li class="text-capitalize">
								<a href="/lens-po-report" class="<?= ($page=="lens-po-report") ? 'active' : '' ?>">
								<?php $icon_request = ( $page == 'lens-P.O.-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Lens P.O. Report
								</a>
							</li>

						<?php }	?>	

						<?php if(issetVal('incentives','incentives')==1) { ?>

							<li class="text-capitalize">
								<a href="/incentives/" class="<?= ($page=="incentives") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'incentives' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Incentives
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('incentives','incentives_doctors')==1) { ?>

							<li class="text-capitalize">
								<a href="/incentives-doctors/" class="<?= ($page=="incentives-doctors") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'incentives-doctors' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Incentives Doctors
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('incentives','commission_settings')==1) { ?>

							<li class="text-capitalize">
								<a href="/commission-settings/" class="<?= ($page=="incentives-settings") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'commission-settings' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Incentives Settings
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('incentives','commission_settings_doctors')==1) { ?>

							<li class="text-capitalize">
								<a href="/commission-settings-doctors/" class="<?= ($page=="incentives-settings-doctors") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'commission-settings-doctors' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Incentives Settings Doctors
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('incentives','commission_lens_settings')==1) { ?>

							<li class="text-capitalize">
								<a href="/commission-lens-settings/" class="<?= ($page=="commission-lens-settings") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'commission-lens-settings' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Commission Lens Settings
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('incentives','commission')==1) { ?>

							<li class="text-capitalize">
								<a href="/commission" class="<?= ($page=="commission") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'commission' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Commission
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('incentives','commission_store')==1) { ?>

							<li class="text-capitalize">
								<a href="/commission-store" class="<?= ($page=="commission-store") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'commission-store' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									Commission Store
								</a>
							</li>

						<?php } ?>

						</ul>
				</nav>

			<?php } ?>	
			<!-- //END:: INCENTIVE -->

			<?php if(isset($_SESSION['user_access']['virtual_store'])) { ?>

				<nav class="ssis-menu sidebar-menu">
					<p class="small text-uppercase">Virtual Store</p>
					<ul class="nav flex-column">

						<?php if($userLevel == 1 || $userLevel == 18 || $storeCode == '787') { ?>

							<!-- <li class="text-capitalize">
								<a href="/products/" class="<?= ($page=="products") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'products' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Products
								</a>
							</li> -->

						<?php }	?>

						<?php 

						if($userLevel == 1 || $userLevel == 5 || $userLevel == 18 && $storeCode != '787') { 

								if($userLevel == 3) {

									$urlParam = "?store=".$storeCode;

								}
								else {

									$urlParam = "";

								};

						?>

							<!-- <li class="text-capitalize">
								<a href="/store-management/<?= $urlParam ?>" class="<?= ($page=="store-management") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'store-management' ) ? 'sidebar-store-management-active.png' : 'sidebar-store-management.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Store Schedule
								</a>
							</li> -->

						<?php }	?>

						<?php if($userLevel == 1 || $userLevel == 3 || $userLevel == 18 && $storeCode != '787') { ?>

							<!-- <li class="text-capitalize">
								<a href="/appointment/" class="<?= ($page=="appointment") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'appointment' ) ? 'sidebar-store-management-active.png' : 'sidebar-store-management.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Appointments
								</a>
							</li> -->

						<?php }	?>

						<?php if($userLevel == 1 || $userLevel == 18 || $storeCode == '787') { ?>

							<!-- <li class="text-capitalize">
								<a href="/online-orders/" class="<?= ($page=="online-orders") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'online-orders' ) ? 'sidebar-online-orders-active.png' : 'sidebar-online-orders.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Online Orders
								</a>
							</li> -->

						<?php }	?>

						<?php if(issetVal('virtual_store','patient_lookup')==1) { ?>

							<li class="text-capitalize">
								<a href="/virtual-store/doctors/" class="<?= ($page=="patient-lookup") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'patient-lookup' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Patient Lookup
								</a>
							</li>

						<?php }	?>

						<?php if(issetVal('virtual_store','patient_lookup_historical')==1) { ?>
						
							<li class="text-capitalize">
								<a href="/virtual-store/historical/" class="<?= ($page=="historical") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'historical' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Patient Lookup(historical)
								</a>
							</li>

						<?php }	?>			

						<?php if(issetVal('virtual_store','inventory_lookup_vs')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
								<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
								Inventory Lookup
								</a>
							</li>

						<?php } ?>

					</ul>
				</nav>

			<?php } ?>				

			<?php if(isset($_SESSION['user_access']['vvm'])) { ?>


				<nav class="vvm-menu sidebar-menu">
					<p class="small text-uppercase">vvm</p>
					<ul class="nav flex-column">


					<?php if(issetVal('vvm','sunnies_specs_vvm')==1) { ?>

						<li class="text-capitalize">
							<a href="/vvm/specs/">
								<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
								sunnies specs
							</a>
						</li>
					<?php } ?>

					</ul>
				</nav>
						
			<?php } ?>

<!-- 			<?php if(isset($_SESSION['user_access']['aim'])) { ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">aim</p>
					<ul class="nav flex-column">

						

					</ul>
				</nav>

			<?php } ?> -->

			<?php if(isset($_SESSION['user_access']['hr'])) { ?> 

					<nav class="aim-menu sidebar-menu">
						<p class="small text-uppercase">HR</p>
						<ul class="nav flex-column">

							<?php if(issetVal('hr','employees')==1) { ?>

								<li class="text-capitalize">
									<a href="/employees">
									<?php $icon_request = ( $page == 'employees' ) ? 'sidebar-request-active.png' : 'sidebar-request.png' ?>
										<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
										Employees
									</a>
								</li>

							<?php  } ?>

						</ul>

					</nav>
				
			<?php  } ?>

			<?php if(isset($_SESSION['user_access']['distributors'])) {  ?>

				<nav class="ssis-menu sidebar-menu">
					<p class="small text-uppercase">Distributors</p>
					<ul class="nav flex-column">

						<?php if(issetVal('distributors','dashboard_dist')==1) { ?>

							<li class="text-capitalize">
								<a href="/distributors/dashboard/">
									<?php $icon_distributors_dashboard = ( $page == 'distributors' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_dashboard) ?>)"></canvas>
									Dashboard
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('distributors','profiles_dist')==1) { ?>

							<li class="text-capitalize">
								<a href="/distributors/profiles/">
									<?php $icon_distributors_profiles = ( $page == 'distributors-profiles' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_profiles) ?>)"></canvas>
									profiles
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('distributors','orders')==1) { ?>

							<li class="text-capitalize">
								<a href="/distributors/orders/">
									<?php $icon_distributors_orders = ( $page == 'distributors-orders' ) ? 'sidebar-dispatch-active.png' : 'sidebar-dispatch.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_orders) ?>)"></canvas>
									orders
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('distributors','inventory')==1) { ?>

							<li class="text-capitalize">
								<a href="/distributors/inventory/">								
									<?php $icon_distributors_inventory = ( $page == 'distributors-inventory' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_inventory) ?>)"></canvas>
									inventory
								</a>
							</li>

						<?php } ?>

					</ul>
				</nav>

			<?php } ?>

			<?php if(isset($_SESSION['user_access']['poll_51'])) {  ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">Poll 51</p>
					<ul class="nav flex-column">

						<?php if(issetVal('poll_51','sunnies_specs_poll_51')==1) { ?>

							<li class="text-capitalize">
								<a href="/system/poll-51/specs/" class="<?= ($page=="poll-51-specs") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'poll-51-specs' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Sunnies Specs
								</a>
							</li>

						<?php } ?>
						
					</ul>
				</nav>

			<?php  } ?>


			<?php if(isset($_SESSION['user_access']['maintenance'])) {  ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">Maintenance</p>
					<ul class="nav flex-column">

						<?php if(issetVal('maintenance','profiles')==1) { ?>

							<li class="text-capitalize">
								<a href="/profiles/" class="<?= ($page=="profiles") ? 'active' : '' ?>">
									<?php $icon_patients = ( $page == 'profiles' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
									profiles
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('maintenance','stores')==1) { ?>

							<li class="text-capitalize">
								<a href="/store-locations/" class="<?= ($page=="locations") ? 'active' : '' ?>">
									<?php $icon_stores = ( $page == 'locations' ) ? 'sidebar-stores-active.png' : 'sidebar-stores.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_stores) ?>)"></canvas>
									stores
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('maintenance','users')==1) { ?>

							<li class="text-capitalize">
								<a href="/user-management/" class="<?= ($page=="users" || $page=="edit-user") ? 'active' : '' ?>">
									<?php $icon_users = ( $page == 'users' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
									users
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('maintenance','user_access_monitoring')==1) { ?>

							<li class="text-capitalize">
								<a href="/user-access-monitoring/" class="<?= ($page=="user-access-monitoring") ? 'active' : '' ?>">
									<?php $icon_users = ( $page == 'user-access-monitoring' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
									user access monitoring
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('maintenance','orders_fix')==1) { ?>

							<li class="text-capitalize">
								<a href="/orders-fix/" class="<?= ($page=="orders-fix") ? 'active' : '' ?>">
									<?php $icon_users = ( $page == 'orders-fix' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
									Orders Fix
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('maintenance','inventory_fix')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory-fix/" class="<?= ($page=="orders-fix") ? 'active' : '' ?>">
									<?php $icon_users = ( $page == 'inventory-fix' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
									Inventory Fix
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('maintenance','gender_fix')==1) { ?>

							<li class="text-capitalize">
								<a href="/gender-fix/" class="<?= ($page=="gender-fix") ? 'active' : '' ?>">
									<?php $icon_users = ( $page == 'gender-fix' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
									Gender Fix
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('maintenance','laboratory_fix')==1) { ?>

							<li class="text-capitalize">
								<a href="/lab-fix/" class="<?= ($page=="laboratory-fix") ? 'active' : '' ?>">
									<?php $icon_users = ( $page == 'laboratory-fix' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
									Laboratory Fix
								</a>
							</li>

						<?php } ?>

					</ul>
				</nav>

			<?php  } ?>

			<?php if(isset($_SESSION['user_access']['server_query'])) {  ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">Server Query</p>
					<ul class="nav flex-column">

						<?php if(issetVal('server_query','create_query')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/create-query" class="<?= ($page=="create-query") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'create-query' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Create Query
								</a>
							</li>

						<?php } ?>

						<?php if(issetVal('server_query','server_query')==1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/server-query" class="<?= ($page=="server-query") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'server-query' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Server Query
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

<?php function get_sidebar_vvm($page = "") { ?>

	<div id="sidebar-vvm">
		<div class="sidebar-container d-flex flex-column">
			<div class="logo d-flex align-items-center justify-content-between no-gutters">
				<div class="col">
					<div class="d-flex align-items-center">
						<img src="<?= get_url('images/logo/logo-icon-dark.png') ?>" alt="SSIS" class="img-fluid">
						<img src="<?= get_url('images/logo/logo-text-dark.png') ?>" alt="Close" class="img-fluid ml-2">
					</div>
				</div>
				<div class="burger-vvm">
					<span class="burger-icon"></span>
					<span class="burger-icon"></span>
					<span class="burger-icon"></span>
				</div>
			</div>

			<?php if ( $page == 'visualize store' ) { ?>
				<div class="search-vvm">
				</div>

				<form action="/vvm/process/add_templates_in_store.php" method="POST" class="list-vvm" id="update-visualize-store">
					<p class="font-bold text-uppercase text-vvm mb-3" id="title">wall templates</p>
					<nav id="vvm-data" clsas="navbar">
						<ul class="navbar-nav flex-column template-selection activate-scrollbar">

							<?php

								global $arrTemplatesInStore2;
								global $arrTemplates;

								for ( $i = 0; $i < sizeof($arrTemplatesInStore2); $i++ ) {

									$template_id 	= $arrTemplatesInStore2[$i]['template_id'];
									$planogram_id 	= $arrTemplatesInStore2[$i]['planogram_id'];
									$planogram_name = $arrTemplatesInStore2[$i]['planogram_name'];

									echo '<li class="d-block">';
									echo 	'<div class="form-group mb-0">';
									
									echo 		'<p class="font-bold mb-2">Wall #'.$template_id.'</p>';
									echo 		'<select name="planogram_id_'.$template_id.'" id="template_'.$template_id.'" class="select form-control text-secondary">';
									echo 			'<option value="">Select Template</option>';
													
									for ( $x=0; $x<sizeof($arrTemplates); $x++ ) {

										$selected = ($arrTemplates[$x]["planogram_id"] == $planogram_id) ? 'selected="selected"' : '';
													
										echo 		'<option value="'.$arrTemplates[$x]["planogram_id"].'" '.$selected.'>'.$arrTemplates[$x]["planogram_name"].'</option>';
													
									}

									echo		'</select>';

									echo 	'</div>';
									echo '</li>';

								}

							?>

						</ul>
					</nav>
				</form>

			<?php } else { ?>

				<div class="search-vvm">
					<input type="text" class="form-control search vvm-search" name="search" id="search_template" placeholder="Template name">
				</div>
				<div class="list-vvm">
					<p class="font-bold text-uppercase text-vvm mb-3" id="title">recent templates</p>
					<nav id="vvm-data" clsas="navbar">
						<ul class="navbar-nav flex-column activate-scrollbar">

							<?php

								global $arrTemplates;

								// Cycle through stores array
								for ($i=0; $i < sizeOf($arrTemplates); $i++) { 
												
									$curPID   = $arrTemplates[$i]['planogram_id'];
									$curPName = $arrTemplates[$i]['planogram_name'];
									$curDate  = cvdate(3,$arrTemplates[$i]['date_updated']);
									$curTemp  = ( isset($_GET['id']) && $_GET['id'] == $curPID ) ? 'active' : '';

									echo    '<li class="text-dark d-block '.$curTemp.'">';
									echo        '<div class="d-flex no-gutters justify-content-between align-items-center">';
									
									echo            '<div class="col">';
									echo                '<a href="/vvm/templates/?id='.$curPID.'" class="font-bold text-dark">'.$curPName.'</a>';
									echo                '<p class="text-secondary">Last edited <b class="text-secondary">'.$curDate.'</b></p>';
									echo            '</div>';

									echo            '<a href="/vvm/templates/?id='.$curPID.'" class="text-secondary action mr-2" title="Edit"><img src="/assets/images/icons/icon-edit-vvm.png" alt="edit" class="img-fluid edit"></a>';

									echo 			'<a href="/vvm/process/add_new_template.php?delete='.$curPID.'" class="text-secondary action" title="Delete"><img src="/assets/images/icons/icon-remove-vvm.png" alt="Delete" class="img-fluid edit"></a>';

									echo 		'</div>';
									echo    '</li>';

								};

							?>

						</ul>
					</nav>
				</div>

			<?php } ?>

			<div class="main-menu-vvm">
				<div id="vvm-nav" clsas="d=flex flex-column">
					<a href="/vvm/" class="d-flex align-items-center">
						<canvas style="background-image: url(<?= get_url('images/icons/sidebar-template-active.png') ?>)"></canvas>
						<p class="font-bold">View Templates</p>
					</a>
					<a href="/vvm/store/" class="d-flex align-items-center">
						<canvas style="background-image: url(<?= get_url('images/icons/sidebar-stores-active.png') ?>)"></canvas>
						<p class="font-bold">Visualize Store</p>
					</a>

					<?php

						// Check if user should go back to dashboard or logout
						if($_SESSION['user_login']['userLevel'] == 1) {

						    $exitLink = "/dashboard/";

						}
						else {

							$exitLink = "/process/logout.php";

						};

					?>

					<a href="<?= $exitLink ?>" class="d-flex align-items-center">
						<canvas style="background-image: url(<?= get_url('images/icons/sidebar-signout-active.png') ?>)"></canvas>
						<p class="font-bold">Exit</p>
					</a>
				</div>
			</div>
		</div>
	</div>

<?php } ?>