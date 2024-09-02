<?php  

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
if($_SESSION['store_code'] != '150' &&  $_SESSION['store_code'] != '142'){

	

	$orders_specs_id = $_POST['orders_specs_id'];

	$arrOrderSpecsId = [];

	foreach ($orders_specs_id as $key => $value) {
		$arrOrderSpecsId[] = $value;
	}
	$po_number = mysqli_real_escape_string($conn,$_POST['po_number']);
	$arrOrderSpecsId = implode("','", $arrOrderSpecsId);
	$queryUpdate = 	"UPDATE 
						orders_specs 
					SET
						`status`='cancelled',
						status_date=now()
					WHERE 
						packaging_for='".$po_number."' AND orders_specs_id IN ('".$arrOrderSpecsId."');";

	//echo $queryUpdate.PHP_EOL;

	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $queryUpdate)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

	} 
	else {

		echo mysqli_error($conn);
		return false;
		exit;	

	};

	$query3 = 	"INSERT INTO 
					order_status(
						order_id,
						status,
						status_date,
						updatee,
						branch
					)
				VALUES(
					'".$po_number."',
					'packaging_remove',
					now(),
					'".$_SESSION['id']."',
					'".$_SESSION["store_code"]."'
				)";
	//echo $query3;
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query3)) {

		mysqli_stmt_execute($stmt);		

	};
	echo '<script>	alert("Packaging removed."); </script>';
	echo '<script>	window.location.href="/dispatch"; </script>';


}
	