<?php

function get_sidebar($page = "", $main = "") { 

	// Grab User Position
	$userLevel = $_SESSION['user_login']['userlvl'];
	$username  = $_SESSION['user_login']['username'];
	$storeCode = $_SESSION['user_login']['store_code'];

?>

	<div id="sidebar" <?= $userLevel ?>>
		<div class="logo">
			<img src="<?= get_url('images/logo/logo-full-light-inline.png') ?>" alt="SSIS" class="img-fluid">
			<img src="<?= get_url('images/icons/icon-close-danger.png') ?>" alt="Close" class="close-sidebar img-fluid d-block d-xl-none">
		</div>
		<div class="navigation activate-scrollbar">

			<?php if($userLevel == 1 || $userLevel == 5 || $userLevel == 6 || $userLevel == 7 || $userLevel == 14 || $userLevel == 17) { ?>

				<nav class="main-menu sidebar-menu">
					<p class="small text-uppercase">Dashboard</p>
						<ul class="nav flex-column">

						<?php if($userLevel == 1 || $userLevel == 6 || $userLevel == 7 || $userLevel == 14 || $userLevel == 17) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/philippines" class="<?= ($page=="dashboard-philippines") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-philippines' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Philippines
								</a>
							</li>
							<?php if($username!='SBanares' &&  $username!='RGabriel' ){ ?> 
							<li class="text-capitalize">
								<a href="/dashboard/vietnam" class="<?= ($page=="dashboard-vietnam") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-vietnam' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Vietnam
								</a>
							</li>
							<?php } ?>

							<li class="text-capitalize">
								<a href="/dashboard/shopify-ph" class="<?= ($page=="dashboard-virtual-store-philippines") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-virtual-store-philippines' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Virtual Store - PH
								</a>
							</li>

							<li class="text-capitalize">
								<a href="/dashboard/shopify-int" class="<?= ($page=="dashboard-virtual-store-international") ? 'active' : '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard-virtual-store-international' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Virtual Store - INT
								</a>
							</li>

						<?php } ?>

						<?php if($userLevel == 5) { ?>

							<li class="text-capitalize">
								<a href="/dashboard/supervisor" class="<?= ($page=="dashboard") ? 'active' :@ '' ?>">
									<?php $icon_dashboard = ( $page == 'dashboard' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_dashboard) ?>)"></canvas>
									Dashboard
								</a>
							</li>					

						<?php } ?>

					</ul>
				</nav>

			<?php } ?>	

			<nav class="main-menu sidebar-menu">
				<p class="small text-uppercase">main menu</p>
				<ul class="nav flex-column">

					<?php if ( $userLevel == 11 ) { // RUNNER INVENTORY MENU ?>

						<li class="text-capitalize">
							<a href="/inventory/runner/orders/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								stock movement
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/runner/on-hand/" class="<?= ($page=="on-hand") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'on-hand' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								on hand
							</a>
						</li>

					<?php } ?>

					<?php if ( $userLevel == 15 ) { // AUDITOR INVENTORY MENU ?>

						<li class="text-capitalize">
							<a href="/inventory/audit/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								stock movement
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/audit/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
								<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
								Variance Report
							</a>
						</li>

						
						<li class="text-capitalize">
							<a href="/inventory/audit/physical-count/" class="<?= ($page=="physical-count") ? 'active' : '' ?>">
								<?php $icon_report = ( $page == 'physical-count' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
								Physical Count Page
							</a>
						</li>

					<?php } ?>

					<?php if ( $userLevel == 8 ) { // WAREHOUSE INVENTORY MENU ?>

						<li class="text-capitalize">
							<a href="/inventory/warehouse/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								stock movement
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/warehouse/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
								<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
								Stock Transfer
							</a>
						</li>

						<?php if ( $_SESSION['user_login']['store_code'] == 'warehouse' ) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/pullout/" class="<?= ($page=="pullout") ? 'active' : '' ?>">
									<?php $icon_pullout = ( $page == 'pullout' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_pullout) ?>)"></canvas>
									Good Issue
								</a>
							</li>

						<?php } ?>

						<li class="text-capitalize">
							<a href="/inventory/warehouse/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
								<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
								Receive
							</a>
						</li>

						<?php if ( $_SESSION['user_login']['store_code'] == 'warehouse' ) { ?>

							<li class="text-capitalize">
								<a href="/inventory/warehouse/request/" class="<?= ($page=="inventory-request") ? 'active' : '' ?>">
									<?php $icon_request = ( $page == 'inventory-request' ) ? 'sidebar-request-active.png' : 'sidebar-request.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
									Inventory Request
								</a>
							</li>

						<?php } ?>

						<li class="text-capitalize">
							<a href="/inventory/warehouse/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
								<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
								Variance Report
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/warehouse/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								History
							</a>
						</li>
						<?php if ($_SESSION['user_login']['username']=='garrygarduque@sunniesstudios.com') { ?>
						<li class="text-capitalize">
							<a href="/inventory/warehouse/history-all/" class="<?= ($page=="history-all") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history-all' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								History-all
							</a>
						</li>
					<?php } ?>
					<?php } ?>

					<?php if ( $userLevel == 3 && $_SESSION['user_login']['position']!='laboratory' ) { // STORE INVENTORY MENU ?>

						<li class="text-capitalize">
							<a href="/inventory/store/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								stock movement
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/store/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
								<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
								Inventory Lookup
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/store/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
								<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
								Stock Transfer
							</a>
						</li>

						<!-- <li class="text-capitalize">
							<a href="/inventory/store/pullout/" class="<?= ($page=="pullout") ? 'active' : '' ?>">
								<?php $icon_pullout = ( $page == 'pullout' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_pullout) ?>)"></canvas>
								Pullout
							</a>
						</li> -->

						<li class="text-capitalize">
							<a href="/inventory/store/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
								<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
								Receive
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/store/damage/" class="<?= ($page=="damage") ? 'active' : '' ?>">
								<?php $icon_damage = ( $page == 'damage' ) ? 'sidebar-damage-active.png' : 'sidebar-damage.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_damage) ?>)"></canvas>
								Damage
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/store/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
								<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
								Variance Report
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/store/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								History
							</a>
						</li>

					<?php } ?>

					<?php if ( $userLevel == 3 && $_SESSION['user_login']['position']=='laboratory' ) { // LAB INVENTORY MENU ?>

						<li class="text-capitalize">
							<a href="/inventory/lab/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								stock movement
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/lab/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
								<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
								Inventory Lookup
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/lab/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
								<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
								Stock Transfer
							</a>
						</li>

						<!-- <li class="text-capitalize">
							<a href="/inventory/lab/pullout/" class="<?= ($page=="pullout") ? 'active' : '' ?>">
								<?php $icon_pullout = ( $page == 'pullout' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_pullout) ?>)"></canvas>
								Pullout
							</a>
						</li> -->

						<li class="text-capitalize">
							<a href="/inventory/lab/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
								<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
								Receive
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/lab/damage/" class="<?= ($page=="damage") ? 'active' : '' ?>">
								<?php $icon_damage = ( $page == 'damage' ) ? 'sidebar-damage-active.png' : 'sidebar-damage.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_damage) ?>)"></canvas>
								Damage
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/lab/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
								<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
								Variance Report
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/lab/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								History
							</a>
						</li>

					<?php } ?>

					<?php if ( $userLevel == 13 ) { // ADMIN INVENTORY MENU ?>

						<li class="text-capitalize">
							<a href="/inventory/admin/stock-movement/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								Stock Movement
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/admin/stock-transfer/" class="<?= ($page=="stock-transfer") ? 'active' : '' ?>">
								<?php $icon_transfer = ( $page == 'stock-transfer' ) ? 'sidebar-transfer-active.png' : 'sidebar-transfer.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_transfer) ?>)"></canvas>
								Stock Transfer
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/admin/receive/" class="<?= ($page=="receive") ? 'active' : '' ?>">
								<?php $icon_receive = ( $page == 'receive' ) ? 'sidebar-receive-active.png' : 'sidebar-receive.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_receive) ?>)"></canvas>
								Receive
							</a>
						</li>

						<?php if ($_SESSION['user_login']['username']=='dana_aim') { ?>

							<li class="text-capitalize">
								<a href="/inventory/admin/request-approval/" class="<?= ($page=="request-approval") ? 'active' : '' ?>">
									<?php $icon_approval = ( $page == 'request-approval' ) ? 'sidebar-approval-active.png' : 'sidebar-approval.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_approval) ?>)"></canvas>
									Request Approval
								</a>
							</li>
							<?php } ?>
							<?php if ($_SESSION['user_login']['username']=='dana_aim' || $_SESSION['user_login']['username']=='dorsgonzalo@sunniesstudios.com' 
							|| $_SESSION['user_login']['username']=='joshuadeguzman@sunniesstudios.com'  	|| $_SESSION['user_login']['username']=='neilsimbulan@sunniesstudios.com' ) { ?>
							<li class="text-capitalize">
								<a href="/inventory/admin/history-all/" class="<?= ($page=="history-all") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history-all' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								 History(all)
								</a>
							</li>

							<?php  if ($_SESSION['user_login']['username']=='dana_aim' || $_SESSION['user_login']['username']=='dorsgonzalo@sunniesstudios.com' 
							|| $_SESSION['user_login']['username']=='joshuadeguzman@sunniesstudios.com') { ?>

									<li class="text-capitalize">
									<a href="/inventory/store/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
									<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
									Inventory Lookup
									</a>
									</li>

									<?php } ?>

						<?php } ?>

						<!-- <li class="text-capitalize">
							<a href="/inventory/admin/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
								<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
								Variance Report
							</a>
						</li> -->

						<li class="text-capitalize">
							<a href="/inventory/admin/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								History
							</a>
						</li>

					<?php } ?>					

					<?php if($userLevel == 1 || $userLevel == 6 || $userLevel == 7 || $userLevel == 17) { ?>

						<li class="text-capitalize">
							<a href="/patient-profile/" class="<?= ($page=="patient-profile") ? 'active' : '' ?>">
								<?php $icon_patients = ( $page == 'patient-profile' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
								patient profile
							</a>
						</li>

					<?php } ?>

					<?php if($userLevel == 1 || $userLevel == 6 || $userLevel == 7) { ?>

						<li class="text-capitalize">
							<a href="/patient-records/" class="<?= ($page=="patients") ? 'active' : '' ?>">
								<?php $icon_patients = ( $page == 'patients' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_patients) ?>)"></canvas>
								patients
							</a>
						</li>

					<?php } ?>

					<?php if($userLevel == 1) { ?>

						<li class="text-capitalize">
							<a href="/store-locations/" class="<?= ($page=="locations") ? 'active' : '' ?>">
								<?php $icon_stores = ( $page == 'locations' ) ? 'sidebar-stores-active.png' : 'sidebar-stores.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_stores) ?>)"></canvas>
								stores
							</a>
						</li>

					<?php } ?>

					<?php if($username == 'michelh' || $username == 'ssis_admin' || $username == 'EnyelC') { ?>

						<li class="text-capitalize">
							<a href="/user-management/" class="<?= ($page=="users" || $page=="edit-user") ? 'active' : '' ?>">
								<?php $icon_users = ( $page == 'users' ) ? 'sidebar-users-active.png' : 'sidebar-users.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_users) ?>)"></canvas>
								users
							</a>
						</li>

					<?php } ?>

				</ul>
			</nav>

			<?php if($userLevel == 1 || $userLevel == 3 || $userLevel == 5 || $userLevel == 16) { ?>

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


						<?php if ( $userLevel == 1 || $userLevel == 5 || ($userLevel == 3 && $_SESSION['user_login']['position']!='laboratory') ) { ?>

							<li class="text-capitalize">
								<a href="<?= $dispatchLink ?>">
									<canvas style="background-image: url(<?= get_url('images/icons/sidebar-dispatch.png') ?>)"></canvas>
									dispatch
								</a>
							</li>

						<?php } ?>

						<?php if($userLevel == 1 || $userLevel == 5 || ($userLevel == 3 && $_SESSION['user_login']['position']=='laboratory') ) { ?>

							<li class="text-capitalize">
								<a href="/list/">
									<canvas style="background-image: url(<?= get_url('images/icons/sidebar-lab.png') ?>)"></canvas>
									laboratory
								</a>
							</li>

						<?php }	?>

						<?php if ( $userLevel == 1 || $userLevel == 5 || $userLevel == 16 ) { ?>
							<?php if($username!='SBanares' &&  $username!='RGabriel'){ ?>
									<li class="text-capitalize">
										<a href="/re-order-report/">
											<canvas style="background-image: url(<?= get_url('images/icons/sidebar-report.png') ?>)"></canvas>
											Reorder Report
										</a>
									</li>
									<li class="text-capitalize">
										<a href="/lens-po-report">
										<?php $icon_request = ( $page == 'lens-P.O.-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
											<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
											Lens P.O. Report
										</a>
									</li>
									<?php }	?>

						<?php }	?>

					</ul>
				</nav>

			<?php } ?>	

			<?php if($userLevel == 1  || $userLevel == 3 || $userLevel == 5 || $userLevel == 6 || $userLevel == 18 || $userLevel == 19 || $username == 'ACunanan') { ?>

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

						<?php if($userLevel == 1 || $userLevel == 6 || $userLevel == 18 || $userLevel == 19 || $username == 'ACunanan') { ?>

							<li class="text-capitalize">
								<a href="/virtual-store/doctors/" class="<?= ($page=="patient-lookup") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'patient-lookup' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Patient Lookup
								</a>
							</li>

						<?php }	?>

						<?php if($userLevel == 1 || $userLevel == 5 || $userLevel == 6 || $userLevel == 18 || $userLevel == 19 || $username == 'ACunanan') { ?>
						
							<li class="text-capitalize">
								<a href="/virtual-store/historical/" class="<?= ($page=="historical") ? 'active' : '' ?>">
									<?php $icon_store_management = ( $page == 'historical' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
									Patient Lookup(historical)
								</a>
							</li>

						<?php }	?>						
									<?php if($userLevel == 19) { ?>

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

			<?php if($userLevel == 1 || $userLevel == 4 || $userLevel == 6 || $userLevel == 7) { ?>

				<?php if($userLevel == 1 || $userLevel == 4 || $userLevel == 6 || $userLevel == 7     ) { ?>
						<?php if($username!='SBanares' &&  $username!='RGabriel'){ ?>

						<nav class="vvm-menu sidebar-menu">
							<p class="small text-uppercase">vvm</p>
							<ul class="nav flex-column">
								<li class="text-capitalize">
									<a href="/vvm/specs/">
										<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
										sunnies specs
									</a>
								</li>
								<li class="text-capitalize">
									<a href="/vvm/studios/">
										<canvas style="background-image: url(<?= get_url('images/icons/sidebar-visualize.png') ?>)"></canvas>
										sunnies studios
									</a>
								</li>
							</ul>
						</nav>
							
						<?php } ?>
				<?php } ?>

				<?php if($userLevel == 1 || $userLevel == 4 || $userLevel == 7) { ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">aim</p>
					<ul class="nav flex-column">
						<li class="text-capitalize">
							<a href="/inventory/dashboard/" class="<?= ($page=="stock-movement") ? 'active' : '' ?>">
								<?php $icon_inventory = ( $page == 'stock-movement' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_inventory) ?>)"></canvas>
								stock movement
							</a>
						</li>

						<?php if($userLevel == 1) { ?>

							<li class="text-capitalize">
								<a href="/inventory/store/inventory-lookup/" class="<?= ($page=="inventory-lookup") ? 'active' : '' ?>">
									<?php $icon_lookup = ( $page == 'inventory-lookup' ) ? 'sidebar-lookup-active.png' : 'sidebar-lookup.png' ?>
									<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_lookup) ?>)"></canvas>
									Inventory Lookup
								</a>
							</li>

						<?php } ?>

						<li class="text-capitalize">
							<a href="/inventory/dashboard/reports/" class="<?= ($page=="variance-report") ? 'active' : '' ?>">
								<?php $icon_report = ( $page == 'variance-report' ) ? 'sidebar-report-active.png' : 'sidebar-report.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_report) ?>)"></canvas>
								variance report
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/inventory/dashboard/request/" class="<?= ($page=="request-approval") ? 'active' : '' ?>">
								<?php $icon_request = ( $page == 'request-approval' ) ? 'sidebar-request-active.png' : 'sidebar-request.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
								Request Approval
							</a>
						</li>
						
						<li class="text-capitalize">
							<a href="/inventory/dashboard/history/" class="<?= ($page=="history") ? 'active' : '' ?>">
								<?php $icon_history = ( $page == 'history' ) ? 'sidebar-history-active.png' : 'sidebar-history.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_history) ?>)"></canvas>
								history
							</a>
						</li>

					</ul>
				</nav>

				<?php } ?>

			<?php } ?>
			<?php if($username!='SBanares' &&  $username!='RGabriel' ){ ?> 
				<?php if($userLevel == 1 || $userLevel == 21) { ?>

					<nav class="aim-menu sidebar-menu">
						<p class="small text-uppercase">HR</p>
						<ul class="nav flex-column">
						<li class="text-capitalize">
							<a href="/employees">
							<?php $icon_request = ( $page == 'employees' ) ? 'sidebar-request-active.png' : 'sidebar-request.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_request) ?>)"></canvas>
								Employees
							</a>
						</li>
					</nav>

				<?php  } ?>
			<?php  } ?>

			<?php if($userLevel == 12) { ?>

				<nav class="ssis-menu sidebar-menu">
					<p class="small text-uppercase">Distributors</p>
					<ul class="nav flex-column">
						<li class="text-capitalize">
							<a href="/distributors/dashboard/">
								<?php $icon_distributors_dashboard = ( $page == 'distributors' ) ? 'sidebar-chart-active.png' : 'sidebar-chart.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_dashboard) ?>)"></canvas>
								Dashboard
							</a>
						</li>
						<li class="text-capitalize">
							<a href="/distributors/profiles/">
								<?php $icon_distributors_profiles = ( $page == 'distributors-profiles' ) ? 'sidebar-patients-active.png' : 'sidebar-patients.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_profiles) ?>)"></canvas>
								profiles
							</a>
						</li>
						<li class="text-capitalize">
							<a href="/distributors/orders/">
								<?php $icon_distributors_orders = ( $page == 'distributors-orders' ) ? 'sidebar-dispatch-active.png' : 'sidebar-dispatch.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_orders) ?>)"></canvas>
								orders
							</a>
						</li>
						<li class="text-capitalize">
							<a href="/distributors/inventory/">								
								<?php $icon_distributors_inventory = ( $page == 'distributors-inventory' ) ? 'sidebar-inventory-active.png' : 'sidebar-inventory.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_distributors_inventory) ?>)"></canvas>
								inventory
							</a>
						</li>
					</ul>
				</nav>

			<?php } ?>

			<?php if($username == 'michelh' || $username == 'ssis_admin' || $username == 'EnyelC' || $userLevel == 20) { ?>

				<nav class="aim-menu sidebar-menu">
					<p class="small text-uppercase">Poll 51</p>
					<ul class="nav flex-column">
						<li class="text-capitalize">
							<a href="/system/poll-51/specs/" class="<?= ($page=="poll-51-specs") ? 'active' : '' ?>">
								<?php $icon_store_management = ( $page == 'poll-51-specs' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
								Sunnies Specs
							</a>
						</li>

						<li class="text-capitalize">
							<a href="/system/poll-51/studios/" class="<?= ($page=="poll-51") ? 'active' : '' ?>">
								<?php $icon_store_management = ( $page == 'poll-51' ) ? 'sidebar-products-active.png' : 'sidebar-products.png' ?>
								<canvas style="background-image: url(<?= get_url('images/icons/'.$icon_store_management) ?>)"></canvas>
								Sunnies Studios
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