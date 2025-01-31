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

//////////////////////////////////////////////////////////////////////////////////// SETUP ACTIVE PAGE

switch ($page) {

	case 'dashboard':
		$pDashboard = 'active';
		break;
	
};

// Open Nav
$navbar = 		'<nav class="navbar navbar-expand-md navbar-light bg-light mb-4">
					<a class="navbar-brand text-center" href="/">
						<span class="d-none d-sm-block">Sunnies Specs In-store System</span>
						<span class="d-block d-sm-none">SSIS</span>
					</a>
					<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarCollapse">
						<ul class="navbar-nav mr-auto">';

							if($userlvl == 1) {

								// DASHBOARD LINK
								$navbar .= 	'<li class="nav-item active" style="border: 1px solid #000000;">
												<a class="nav-link" href="/">Dashboard <span class="sr-only">(current)</span></a>
											</li>';

							}
							else {

								$navbar .= 	'<li class="nav-item active" style="border: 1px solid #000000;">
												&nbsp;
											</li>';

							};
							
$navbar .=				'</ul>
						<ul class="navbar-nav mt-2 mt-md-0">';

							// ADMIN
							if($userlvl == 1) {

								$navbar .= 	'<li class="nav-item">
												<a class="nav-link" href="/dispatch">Dispatch</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="/list">Lab</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="/patient">Patients</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="/stores">Stores</a>
											</li>
											<li class="nav-item">
												<a class="nav-link" href="/createuser">Create Account</a>
											</li>';

							}
							// STORE
							else if($userStoreID == '109' || $userPosition == 'store') {

								$navbar .= 	'<li class="nav-item">
												<a class="nav-link" href="/dispatch">Dispatch</a>
											</li>';

							}
							// LAB
							else if($userPosition == 'laboratory') {

								$navbar .= 	'<li class="nav-item">
												<a class="nav-link" href="/list">Lab</a>
											</li>';

							}
							
$navbar .=					'<li class="nav-item">
								<a class="nav-link nav-link-sign-out" href="/process/logout.php">Sign Out</a>
							</li>
						</ul>
					</div>
				</nav>';