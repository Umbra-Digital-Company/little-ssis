<?php
include("connect.php");
$page='';
$col='';
if (isset($_GET['page'])){
	$page = $_GET['page'];
}

if (isset($_GET['col'])){
	$col = $_GET['col'];
}

$arrPage = ['home', 'store-home', 'health-declaration-form', 'store-signup', 'store-signin', 'logout', 'customerdetails', 'success', 'returns', 'returns-login', 'returns-search', 'returns-details', 'createuser' , 'return-confirm', 'logout2', 'select-store', 'select-store-studios','select-merch','order-confirmation','add-paper-bag', 'updatedb','order-dispatched'];

if (in_array($page, $arrPage)){
	switch($page){

		case "home": include("account/welcome.php"); break;


		// store
		case "store-home": include("store/store-home.php"); break;
		case "store-signup": include("store/store-signup.php"); break;
		case "store-signin": include("store/store-signin.php"); break;
		//lil_ssis includes
		case "select-store": include("store/select-store.php"); break;
		case "select-store-studios": include("store/sunnies-studios.php"); break;
		case "select-merch": include("store/sunnies-merch.php"); break;
		case "order-confirmation": include("store/order-confirmation.php"); break;
		case "add-paper-bag": include("store/add-paper-bag.php"); break;
		case "order-dispatched": include("store/order-dispatched.php"); break;

		case "health-declaration-form": include("store/health-declaration-form.php"); break;

		case "returns": include("dispatch/returns.php"); break;
		case "returns-login": include("dispatch/returns-login.php"); break;
		case "returns-search": include("dispatch/returns-search.php"); break;
		case "returns-details": include("dispatch/return-details.php"); break;
		case "return-confirm": include("dispatch/return-confirmation.php"); break;

		case "updatedb": include("maintenance/maintenance.php"); break;

		case "success": include("success.php"); break;


		case "logout": include("logout.php"); break;
		case "logout2": include("logout2.php"); break;


	}

}
