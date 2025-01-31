<?php 
require $sDocRoot."/process/get_user_access.php";
setUserAccess($_SESSION['id'],$conn);
//////////////////////////////////////////////////////////////////////////////////// USER CLEARANCES

$username 	 	   = $_SESSION['user_login']['username'];
$userLevel   	   = $_SESSION['user_login']['userlvl'];
$userID      	   = $_SESSION['user_login']['id'];
$userPosition 	   = $_SESSION['user_login']['position'];
$userStoreLocation = $_SESSION['user_login']['store_location'];
$userStoreID 	   = $_SESSION['user_login']['store_code'];
$userLogin   	   = $_SESSION['user_login']['login'];
$userDLogin  	   = $_SESSION['user_login']['dashboard_login'];

//////////////////////////////////////////////////////////////////////////////////// SETUP ACTIVE PAGE

switch ($page) {

	case 'dashboard':
		$pDashboard = 'active';
		break;

	case 'dispatch':
	case 'dispatch-customer':
		$pDispatch = 'active';
		$nav_return = '/dashboard/';
		$nav_specific_return = '/dispatch/';
		$nav_inventory = '/inventory/store/';
		$nav_appointment = '/appointment-attach/';
		$online_order = '/online-orders/';
		break;
	case 'appointment-attach':
			$pDispatch = 'active';
			$nav_return = '/dashboard/';
			$nav_specific_return = '/dispatch/';
			$nav_inventory = '/inventory/store/';
			$nav_appointment = '/appointment-attach/';
			$online_order = '/online-orders/';
			break;
	
	case 'lab':
	case 'lab-customer':
		$pLab = 'active';
		$nav_return = '/dashboard/';
		$nav_specific_return = '/list/';
		$nav_inventory = '/inventory/lab/';
		break;

	case 'patients':
	case 'patients-ssis':
	case 'patients-ssis-details':
		$pPatients = 'active';
		break;

	case 'create-user':
		$pCUser = 'active';
		break;

};

// Open Nav
$navbar_return = 	'<nav class="row no-gutters align-items-center justify-content-between">
						<div class="col-6">
							<a href="'.$nav_return.'">
								<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
							</a>
						</div>					
						
						<div class="col-6 text-right">';			
					
						if($_SESSION['user_access']['ssisSUBMENUdispatch']['inventory_SUBMENU']==1){
							$a= $_SESSION['user_access']['ssisSUBMENUdispatch']['inventory_SUBMENU'];
							$navbar_return .= '<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>';
						}
			
						$navbar_return .= '<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>';
	
$navbar_return .= '</div></nav>';


$navbar_return_b = 	'<nav class="row no-gutters align-items-center justify-content-between">
						<div class="col-6">
							<a href="'.$nav_return.'">
								<div class="menu">
									<div id="menu-a"></div>
									<div id="menu-b"></div>
									<div id="menu-c"></div>
								</div>
							</a>
						</div>					
						<div class="col-6 text-right">';

							if($_SESSION['user_access']['ssisSUBMENUdispatch']['inventory_SUBMENU']==1){				
								$navbar_return_b .= '<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>';
							}

							$navbar_return_b .= '<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>

						</div>
					</nav>';



if($_SESSION['store_code']=='787'){
	// Open Nav
	$navbar = 	'<nav class="row no-gutters align-items-center justify-content-between">
					<div class="col-6">
						<a href="'.$nav_specific_return.'">
							<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
						</a>
					</div>					
					<div class="col-6 text-right"';
										
						if($_SESSION['user_access']['ssisSUBMENUdispatch']['inventory_SUBMENU']==1){
							$navbar .= '<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>';
						}

						$navbar .= '<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>';

					$navbar .= '</div>
				</nav>';
				
						
	$navbar_dispatch = 	'<nav class="row no-gutters align-items-center justify-content-between">
			<div class="col-6">
				<a href="'.$nav_specific_return.'">
					<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
				</a>
			</div>					
			<div class="col-6 text-right">';			
				
				if($_SESSION['user_access']['ssisSUBMENUdispatch']['dispatch_SUBMENU']==1){
					$navbar_dispatch .= '<a class="nav-link d-inline" href="'.$nav_specific_return.'">Dispatch</a>';
				}

				if($_SESSION['user_access']['ssisSUBMENUdispatch']['inventory_SUBMENU']==1){
					$navbar_dispatch .= '<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>';	
				}
				
				$navbar_dispatch .= '<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
			</div>
		</nav>';
}else{

	$navbar = '<nav class="row no-gutters align-items-center justify-content-between">
			<div class="col-6">
				<a href="'.$nav_specific_return.'">
					<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
				</a>
			</div>					
			<div class="col-6 text-right">';
					
			
				if($_SESSION['user_access']['ssisSUBMENUdispatch']['inventory_SUBMENU']==1){
					$navbar .= '<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>';
				}
				
				$navbar .= '<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
			</div>
		</nav>';
		
				
	$navbar_dispatch = 	'<nav class="row no-gutters align-items-center justify-content-between">
			<div class="col-6">
				<a href="'.$nav_specific_return.'">
					<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
				</a>
			</div>					
			<div class="col-6 text-right">';
				
				if($_SESSION['user_access']['ssisSUBMENUdispatch']['dispatch_SUBMENU']==1){
					$navbar_dispatch .= '<a class="nav-link d-inline" href="'.$nav_specific_return.'">Dispatch</a>';
				}

				$navbar_dispatch .= '<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>';
				
				$navbar_dispatch .= '<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
			</div>
		</nav>';
}