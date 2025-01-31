<?php
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";
	require $sDocRoot."/face/inventory/includes/face_checker_functions.php";

	$running_data = 0;
	if($_GET['filterStores'] != ''){
		if($_GET['branch']=='warehouse'){
			$running_data = WarehouseChecker_auditor($_GET['product_code'],$_GET['ds'],$_GET['de']); 

		}elseif($_GET['branch']=='store'){
			if($_GET['filterStores']=='SF-MPWHC' ){
				$running_data = StoreChecker_auditor_MS($_GET['product_code'],'SF-MPWHC',$_GET['ds'],$_GET['de']);
			}else{
			$running_data = StoreChecker_auditor($_GET['product_code'],$_GET['filterStores'],$_GET['ds'],$_GET['de']);
			}
		}elseif($_GET['branch']=='lab'){
			$running_data = labChecker_auditor($_GET['product_code'],$_GET['filterStores'],$_GET['ds'],$_GET['de']);
		}
	}

	echo $running_data;
?>