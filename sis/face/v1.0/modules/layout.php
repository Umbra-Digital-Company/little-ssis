<?php
// include("connect.php");
$page='';
$col='';
if (isset($_GET['page'])){
	$page = $_GET['page'];
}

if (isset($_GET['col'])){
	$col = $_GET['col'];
}

$arrPage = ['home', 'store-home', 'health-declaration-form', 'contact-tracing-form', 'contact-tracing-form-test', 'store-signup', 'store-signin', 'logout', 'customerdetails', 'success', 'returns', 'returns-login', 'returns-search', 'returns-details', 'createuser' , 'return-confirm', 'logout2', 'select-store', 'select-store-studios','select-merch','select-readers','order-confirmation','add-paper-bag', 'updatedb','order-dispatched', 'select-store-studios-test', 'select-store-test', 'store-home-test', 'select-antirad','for-payments', 'select-antirad-test', 'select-merch-test', 'order-confirmation-test',"select-store-lips","select-store-face","select-store-brows","select-store-eyes","select-store-skin","select-store-cheeks","select-store-nails","select-store-sets","select-store-all"];

if (in_array($page, $arrPage)){
	switch($page){

		case "home": include("account/welcome.php"); break;


		// store
		case "store-home": include("store/store-home.php"); break;
		case "store-signup": include("store/store-signup.php"); break;
		case "store-signin": include("store/store-signin.php"); break;
		//lil_ssis includes
		case "select-store": 
		case "select-store-all": include("store/select-store.php"); break;
		case "select-store-lips": 
		case "select-store-face":
		case "select-store-brows":
		case "select-store-eyes":
		case "select-store-skin":
		case "select-store-cheeks":
		include("store/sunnies-product-face.php");break;
		case "select-store-nails":
		case "select-store-sets":
		include("store/sunnies-product-default.php");break;
		case "select-merch": include("store/sunnies-merch.php"); break;
		case "select-antirad": include("store/sunnies-antirad.php"); break;
		case "select-readers": include("store/sunnies-readers.php"); break;
		case "order-confirmation": include("store/order-confirmation.php"); break;
		case "add-paper-bag": include("store/add-paper-bag.php"); break;
		case "order-dispatched": include("store/order-dispatched.php"); break;
		case "for-payments": include("store/for-payments.php"); break;

		case "health-declaration-form": include("store/health-declaration-form.php"); break;
		case "contact-tracing-form": include("store/contact-tracing-form.php"); break;

		case "returns": include("dispatch/returns.php"); break;
		case "returns-login": include("dispatch/returns-login.php"); break;
		case "returns-search": include("dispatch/returns-search.php"); break;
		case "returns-details": include("dispatch/return-details.php"); break;
		case "return-confirm": include("dispatch/return-confirmation.php"); break;

		case "updatedb": include("maintenance/maintenance.php"); break;

		case "success": include("success.php"); break;


		case "logout": include("logout.php"); break;
		case "logout2": include("logout2.php"); break;

		//testing page
		case "select-store-studios-test": include("store/sunnies-studios-test.php"); break;
		case "select-merch-test": include("store/sunnies-merch-test.php"); break;
		case "select-antirad-test": include("store/sunnies-antirad-test.php"); break;
		case "select-store-test": include("store/select-store-test.php"); break;
		case "store-home-test": include("store/store-home-test.php"); break;
		case "contact-tracing-form-test": include("store/contact-tracing-form-test.php"); break;
		case "order-confirmation-test": include("store/order-confirmation-test.php"); break;

	}

}
