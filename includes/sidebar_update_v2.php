<?php 
if (!isset($_SESSION)) {
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

};
//////////////////////////////////////////////////////////////////////////////////// USER CLEARANCES

$username 	 	   = $_SESSION['user_login']['username'];
$userLevel   	   = $_SESSION['user_login']['userlvl'];
$userID      	   = $_SESSION['user_login']['id'];
$userPosition 	   = $_SESSION['user_login']['position'];
$userStoreLocation = $_SESSION['user_login']['store_location'];
$userStoreID 	   = $_SESSION['user_login']['store_code'];
$userLogin   	   = $_SESSION['user_login']['login'];
$userDLogin  	   = $_SESSION['user_login']['dashboard_login'];

$pDashboard = "";
$pDispatch  = "";
$pLab 	 	= "";
$pVVM 		= "";
$pPatients  = "";
$pLocations = "";
$pCUser 	= "";

//////////////////////////////////////////////////////////////////////////////////// SETUP ACTIVE PAGE
if(isset($page)){


		switch ($page) {

			case 'dashboard':
				$pDashboard = 'active';
				break;

			case 'dispatch':
			case 'dispatch-customer':
				$pDispatch = 'active';
				break;

			case 'lab':
			case 'lab-customer':
				$pLab = 'active';
				break;

			case 'vvm':
			case 'vvm-setup':
				$pVVM = 'active';
				break;

			case 'patients':
			case 'patients-ssis':
			case 'patients-ssis-details':
				$pPatients = 'active';
				break;

			case 'locations':
				$pLocations = 'active';
				break;

			case 'create-user':
				$pCUser = 'active';
				break;

		};
}

// Open Nav
$navbar = 		'<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
					<a class="navbar-brand text-center" href="/">
						<span class="d-none d-sm-block">Sunnies Specs In-store System</span>
						<span class="d-block d-sm-none">SSIS</span>
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarCollapse">
						<ul class="navbar-nav mr-auto">';

							if($userLevel == 1) {

								// DASHBOARD LINK
								$navbar .= 	'<li class="nav-item hide-m '.$pDashboard.'" style="border: 1px solid #ffffff;">
												<a class="nav-link" href="/dashboard">Dashboard <span class="sr-only">(current)</span></a>
											</li>';

							}
							else {

								$navbar .= 	'<li class="nav-item">
												&nbsp;
											</li>';

							};
							
$navbar .=				'</ul>
						<ul class="navbar-nav mt-2 mt-md-0">';

							// ADMIN
							if($userLevel == 1) {

								$navbar .= 	'<li class="nav-item hide-d '.$pDashboard.'">
												<a class="nav-link" href="/dashboard">Dashboard</a>
											</li>
											<li class="nav-item '.$pDispatch.'">
												<a class="nav-link" href="/dispatch">Dispatch</a>
											</li>
											<li class="nav-item '.$pLab.'">
												<a class="nav-link" href="/list">Lab</a>
											</li>
											<li class="nav-item '.$pVVM.'">
												<a class="nav-link" href="/vvm">VVM</a>
											</li>
											<li class="nav-item '.$pPatients.'">
												<a class="nav-link" href="/patient">Patients</a>
											</li>
											<li class="nav-item '.$pLocations.'">
												<a class="nav-link" href="/store-locations">Stores</a>
											</li>
											<li class="nav-item '.$pCUser.'">
												<a class="nav-link" href="/createuser">Create Account</a>
											</li>
											<!-- <li class="nav-item '.$pCUser.'">
												<select>
													<option>Create Account</option>
													<option>Upload Poll 51</option>
													<option>Upload Employees</option>
												</select>
											</li> -->';

							}
							// STORE
							else if($userStoreID == '109' || $userPosition == 'store') {

								$navbar .= 	'<li class="nav-item '.$pDispatch.'">
												<a class="nav-link" href="/dispatch">Dispatch</a>
											</li>';

							}
							// LAB
							else if($userPosition == 'laboratory') {

								$navbar .= 	'<li class="nav-item '.$pLab.'">
												<a class="nav-link" href="/list">Lab</a>
											</li>';

							}
							
$navbar .=					'<li class="nav-item">
								<a class="nav-link nav-link-sign-out" href="/process/logout.php">Sign Out</a>
							</li>
						</ul>
					</div>
				</nav>';