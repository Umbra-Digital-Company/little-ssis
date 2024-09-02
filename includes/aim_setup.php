<?php

//////////////////////////////////////////////////////////////////////////////////// NAV MENU

$nav1 = '';
$nav2 = '';
$nav3 = '';
$nav4 = '';
$nav5 = '';
$nav6 = '';
$nav7 = '';
$nav8 = '';
$nav9 = '';

////////////// Warehouse

////////////// Store

// Set variables
$storeNav = '';

// Switch on pages
switch ($page_nav) {

	case 'inventory-lookup':
		$nav1 = ' active';
		break;

	case 'stock-transfer':
		$nav2 = ' active';
		break;

	case 'pullout':
		$nav3 = ' active';
		break;

	case 'receive':
		$nav4 = ' active';
		break;

	case 'damage':
		$nav5 = ' active';
		break;

	case 'history':
		$nav6 = ' active';
		break;
	
};

// Set tabs
$storeNav .= '<a href="/inventory/store/inventory-lookup" class="text-uppercase font-weight-bold small'.$nav1.'">inventory lookup</a>';
$storeNav .= '<a href="/inventory/store/stock-transfer" class="text-uppercase font-weight-bold small'.$nav2.'">stock transfer</a>';
$storeNav .= '<a href="/inventory/store/pullout" class="text-uppercase font-weight-bold small'.$nav3.'">pull out</a>';
$storeNav .= '<a href="/inventory/store/receive" class="text-uppercase font-weight-bold small'.$nav4.'">receive</a>';
$storeNav .= '<a href="/inventory/store/damage" class="text-uppercase font-weight-bold small'.$nav5.'">damage</a>';
$storeNav .= '<a href="/inventory/store/history" class="text-uppercase font-weight-bold small'.$nav6.'">history</a>';

////////////// Lab

// Set variables
$labNav = '';

// Switch on pages
switch ($page_nav) {

	case 'inventory-lookup':
		$nav1 = ' active';
		break;

	case 'stock-transfer':
		$nav2 = ' active';
		break;

	case 'pullout':
		$nav3 = ' active';
		break;

	case 'receive':
		$nav4 = ' active';
		break;

	case 'damage':
		$nav5 = ' active';
		break;

	case 'history':
		$nav6 = ' active';
		break;
	
};

// Set tabs
$labNav .= '<a href="/inventory/lab/inventory-lookup" class="text-uppercase font-weight-bold small'.$nav1.'">inventory lookup</a>';
$labNav .= '<a href="/inventory/lab/stock-transfer" class="text-uppercase font-weight-bold small'.$nav2.'">stock transfer</a>';
$labNav .= '<a href="/inventory/lab/pullout" class="text-uppercase font-weight-bold small'.$nav3.'">pull out</a>';
$labNav .= '<a href="/inventory/lab/receive" class="text-uppercase font-weight-bold small'.$nav4.'">receive</a>';
$labNav .= '<a href="/inventory/lab/damage" class="text-uppercase font-weight-bold small'.$nav5.'">damage</a>';
$labNav .= '<a href="/inventory/lab/history" class="text-uppercase font-weight-bold small'.$nav6.'">history</a>';

////////////// Runner

// Set variables
$runnerNav = '';

// Switch on pages
switch ($page_nav) {

	case 'orders':
		$nav1 = ' active';
		break;

	case 'history':
		$nav2 = ' active';
		break;
	
};

// Set tabs
$runnerNav .= '<a href="/inventory/runner/orders" class="text-uppercase font-weight-bold small'.$nav1.'">orders</a>';
$runnerNav .= '<a href="/inventory/runner/history" class="text-uppercase font-weight-bold small'.$nav2.'">history</a>';

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