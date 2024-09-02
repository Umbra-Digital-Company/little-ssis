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

	case 'code-connect':
		$pDispatch = 'active';
		$nav_return = '/dashboard/';
		$nav_specific_return = '/dispatch/';
		$nav_inventory = '/inventory/store/';
		$nav_appointment = '/appointment-attach/';
		$online_order = '/online-orders/';
		break;		

	case 'promo-code-checker':
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

//////////////////////////////////////////////////////////////////////////////////// LITTLE SIS

$lil_ssis = "";

if($user_type==1){

	$lil_ssis = "<a href='https://sunniessystems.com/sis/studios/v1.0/?page=store-home'><div class='btn btn-primary' style='
    height: 100px; width: 100%;'>Little SIS</div></a>";

};

//////////////////////////////////////////////////////////////////////////////////// APPOINTMENT CONNECT

$appointment_connect = "";

if($_SESSION['user_login']['store_code']=='147' || $_SESSION['user_login']['store_code']=='148'|| $_SESSION['user_login']['store_code']=='149'){

	$appointment_connect = "<a href='/dispatch/appointment-connect/'><button class='btn btn-primary' style='
    height: 100px; width: 100%;'>Connect Appointment</button></a>";

};

//////////////////////////////////////////////////////////////////////////////////// Email Checker

$promo_code_checker="";
$code_connect = "";

if($_SESSION['store_code']!='142' &&  $_SESSION['store_code']!='150' && $_SESSION['store_code']!='155'){

	$promo_code_checker = "<a href='/dispatch/promocode-checker/'><button class='btn btn-primary' style='height: 100px; width: 100%;'>Email Checker</button></a>";

}
else{

	$promo_code_checker = "";

}

if($_SESSION['store_code']!='142' &&  $_SESSION['store_code']!='150' && $_SESSION['store_code']!='155' && $_SESSION['user_login']['store_code']!='147'  &&  $_SESSION['user_login']['store_code']!='148' && $_SESSION['user_login']['store_code']!='149'){

	$code_connect="<a href='/dispatch/code-connect/'><button class='btn btn-primary' style='height: 100px; width: 100%;'>Code Connector</button></a>";

}
elseif($_SESSION['user_login']['store_code']=='147' ||  $_SESSION['user_login']['store_code']=='148' ||  $_SESSION['user_login']['store_code']=='149'){

	$code_connect="<a href='/dispatch/appointment-connect/'><button class='btn btn-primary' style='height: 100px; width: 100%;'>Appointment Connect</button></a>";

}
else{

	$code_connect="";

}

//////////////////////////////////////////////////////////////////////////////////// NAVBAR


// Open Nav
$navbar_return = 	'<nav class="row no-gutters align-items-center justify-content-between">
						<div class="col-6">
							<a href="'.$nav_return.'">
								<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
							</a>
						</div>												
						<div class="col-6 text-right">		
							'.$promo_code_checker.' 
							'.$code_connect.' 	
							<a class="nav-link d-inline" href="'.$nav_specific_return.'">Dispatch</a>	
							'.$lil_ssis.'
							<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>	
							<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
						</div>
					</nav>';

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
						<div class="col-6 text-right justify-content-between">	
						'.$promo_code_checker.' 
						'.$code_connect.' 		
						<a class="nav-link d-inline" href="'.$nav_specific_return.'">Dispatch</a>	
							'.$lil_ssis.'				
							<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>	
							<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
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
					<div class="col-12 text-right">			
					'.$promo_code_checker.' 
					'.$code_connect.' 		
					'.$appointment_connect.'	
						'.$lil_ssis.'
						<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>	
						<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
					</div>
				</nav>';
			
					
	$navbar_dispatch = 	'<nav class="row no-gutters align-items-center justify-content-between">
							<div class="col-12">
								<a href="'.$nav_specific_return.'">
									<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
								</a>
							</div>					
							<div class="col-6 text-right">			
							'.$promo_code_checker.' 
							'.$code_connect.' 		
							'.$appointment_connect.'	
								'.$lil_ssis.'
								<a class="nav-link d-inline" href="'.$nav_specific_return.'">Dispatch</a>	
								<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>	
								<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
							</div>
						</nav>';
}
else{

	$navbar = 	'<nav class="row no-gutters justify-content-between">
					<div class="col-6">
						<a href="'.$nav_specific_return.'">
							<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
						</a>
					</div>					
					<div class="col-6 text-right justify-content-between">	
						<div class="nav-link d-inline btn-spoiler" type="button" data-toggle="collapse" data-target="#tabletMenu" aria-expanded="false" aria-controls="tabletMenu" style="color: #ffffff; padding-right: 0;">MENU</div>	
					</div>
					<div class="col-12 mt-3 collapse" id="tabletMenu">
						<div class="card card-body" style="background: #e6e6e6;">
							<div class="row text-center justify-content-between">	
								<div class="col-md-6 col-xs-12 mb-3">
									'.$promo_code_checker.' 
								</div>
								<div class="col-md-6 col-xs-12 mb-3">
									'.$code_connect.' 	
								</div>
								<div class="col-md-6 col-xs-12 mb-3">
									'.$appointment_connect.'
								</div>
								<div class="col-md-6 col-xs-12 mb-3">
									'.$lil_ssis.'				
								</div>
								<div class="col-md-6 col-xs-12 mb-3">
									<a href="'.$nav_inventory.'"><button class="btn btn-info" style="height: 100px; width: 100%;">Inventory</button></a>
								</div>
								<div class="col-md-6 col-xs-12">
									<a class="nav-link-sign-out" href="/process/logout.php"><button class="btn btn-danger" style="height: 100px; width: 100%;">Sign Out</button></a>
								</div>
							</div>
						</div>
					</div>
				</nav>';
			
					
	$navbar_dispatch = 	'<nav class="row no-gutters align-items-center justify-content-between">
							<div class="col-12">
								<a href="'.$nav_specific_return.'">
									<img class="img-fluid" src="/assets/images/logo/logo-full-light-inline.png" />
								</a>
							</div>					
							<div class="col-6 text-right">	
							'.$promo_code_checker.' 
							'.$code_connect.' 		
							'.$appointment_connect.'	
								'.$lil_ssis.'
								<a class="nav-link d-inline" href="'.$nav_specific_return.'">Dispatch</a>	
								<a class="nav-link d-inline" href="'.$nav_inventory.'">Inventory</a>	
								<a class="nav-link nav-link-sign-out d-inline" href="/process/logout.php">Sign Out</a>
								<div class="btn-spoiler" type="button" data-toggle="collapse" data-target="#collapseA" aria-expanded="false" aria-controls="collapseA">?</div>	
							</div>
						</nav>';
}