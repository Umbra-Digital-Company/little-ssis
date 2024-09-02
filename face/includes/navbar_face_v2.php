<?php 

//////////////////////////////////////////////////////////////////////////////////// USER CLEARANCES

$username 	 	   = $_SESSION['user_login']['username'];
$userLevel   	   = $_SESSION['user_login']['userlvl'];
$userID      	   = $_SESSION['user_login']['id'];
$userPosition 	   = $_SESSION['user_login']['position'];
$userStoreLocation = $_SESSION['user_login']['store_location'];
$userStoreID 	   = $_SESSION['user_login']['store_code'];
$userLogin   	   = $_SESSION['user_login']['login'];
$userDLogin  	   = $_SESSION['user_login']['dashboard_login'];
$user_type         = $_SESSION['user_type'];

switch ($page) {

	case 'dashboard':
		$pDashboard = 'active';
		break;

	case 'dispatch-face':
	case 'dispatch-customer':
		$pDispatch = 'active';
		$nav_return = '/dashboard/';
		$nav_specific_return = '/face/dispatch-face/';
		$nav_inventory = '/face/inventory/store/face-receive/';
		$nav_appointment = '/appointment-attach/';
		$online_order = '/online-orders/';
		break;

	case 'appointment-attach':
			$pDispatch = 'active';
			$nav_return = '/dashboard/';
			$nav_specific_return = '/face/dispatch-face/';
			$nav_inventory = '/face/inventory/store/face-receive/';
			$nav_appointment = '/appointment-attach/';
			$online_order = '/online-orders/';
			break;

	case 'code-connect':
		$pDispatch = 'active';
		$nav_return = '/dashboard/';
		$nav_specific_return = '/face/dispatch-face/';
		$nav_inventory = '/face/inventory/store/face-receive/';
		$nav_appointment = '/appointment-attach/';
		$online_order = '/online-orders/';
		break;		

	case 'promo-code-checker':
		$pDispatch = 'active';
		$nav_return = '/dashboard/';
		$nav_specific_return = '/face/dispatch-face/';
		$nav_inventory = '/face/inventory/store/face-receive/';
		$nav_appointment = '/appointment-attach/';
		$online_order = '/online-orders/';
		break;		

	case 'lab':
	case 'lab-customer':
		$pLab = 'active';
		$nav_return = '/dashboard/';
		$nav_specific_return = '/list/';
		$nav_inventory = '/face/inventory/lab/';
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

$arrReceivableCount = (count($arrReceivable) > 0) ? $arrReceivable[0]["count"] : '';

//////////////////////////////////////////////////////////////////////////////////// SETUP ACTIVE PAGE


//////////////////////////////////////////////////////////////////////////////////// NAVBAR

$dispatchBrandLink = (isset($_SESSION['user_access']['ssis']['dispatch']) && $_SESSION['user_access']['ssis']['dispatch'] == 1) ? '<div class="col-md-6 col-xs-12 mb-3">
											<a href="/dispatch"><button class="btn btn-primary" style="height: 100px; width: 100%;">Dispatch Optical</button></a>	
										</div>' : '';
if (isset($_SESSION['user_access']['sunnies_studios']['dispatch_studios']) && $_SESSION['user_access']['sunnies_studios']['dispatch_studios'] == 1) {
	$dispatchBrandLink .='<div class="col-md-6 col-xs-12 mb-3">
											<a href="/studios/dispatch-studios/"><button class="btn btn-primary" style="height: 100px; width: 100%;">Dispatch Sun</button></a>	
										</div>';
									}

if((isset($_SESSION['user_access']['general']['concern_general']) && $_SESSION['user_access']['general']['concern_general'] == 1)){
	$dispatchBrandLink .= '<div class="col-md-6 col-xs-12 mb-3">
											<a href="/general/sunnies-concern/concern"><button class="btn btn-primary" style="height: 100px; width: 100%;">Concern</button></a>	
										</div>';	
}

$faceDashboardLink = (isset($_SESSION['user_access']['sunnies_face']['dashboard_face']) && $_SESSION['user_access']['sunnies_face']['dashboard_face'] == 1) ? '<div class="col-md-6 col-xs-12 mb-3">
											<a href="/face/dashboard/face"><button class="btn btn-primary" style="height: 100px; width: 100%;">Dashboard Face</button></a>	
										</div>' : '';

// Open Nav
$navbar = 	'<nav class="row no-gutters justify-content-between">
							<div class="col-6">
								<a href="'.$nav_specific_return.'">
									<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
								</a>
							</div>					
							<div class="col-6 text-right justify-content-between">	
								<div class="nav-link d-inline btn-spoiler" type="button" data-toggle="collapse" data-target="#tabletMenu" aria-expanded="false" aria-controls="tabletMenu" style="color: #ffffff; padding-right: 5;">MENU</div><span style="float:right;" class="mt-1"><a href="'.$nav_inventory.'"><img src="/assets/images/icons/icon-notificaion.png" class="img-fluid" style="max-width: 3vh; max-height:3vh;" > '.$arrReceivableCount.'</a></span>
							</div>
							<div class="col-12 mt-3 collapse" id="tabletMenu">
								<div class="card card-body" style="background: #e6e6e6;">
									<div class="row text-center justify-content-between">	
									
										<div class="col-md-6 col-xs-12 mb-3">
											<a href="/face/dispatch-face"><button class="btn btn-primary" style="height: 100px; width: 100%;">Dispatch Face</button></a>	
										</div>
										'.$dispatchBrandLink.'
										'.$faceDashboardLink;
$navbar	.=						'<div class="col-md-6 col-xs-12">
											<a class="nav-link-sign-out" href="/process/logout.php"><button class="btn btn-danger" style="height: 100px; width: 100%;">Sign Out</button></a>
										</div>
									</div>
								</div>
							</div>
						</nav>';

$navbar_return = $navbar;