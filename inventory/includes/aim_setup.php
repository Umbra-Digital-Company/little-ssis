<?php

//////////////////////////////////////////////////////////////////////////////////// SET UP QUERY DATA

// Check login position
$aimPosition = $_SESSION['user_login']['position'];
$aimUserLvl  = $_SESSION['user_login']['userlvl'];

switch ($aimPosition) {

	case 'aim-warehouse':
		$aimQueryID = 'warehouse';
		break;

	case 'aim-store':
	case 'aim-lab':
		$aimQueryID = $_SESSION['user_login']['store_code'];
		break;
	
};

?>