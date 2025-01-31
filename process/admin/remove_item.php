<?php 
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

require $sDocRoot."/includes/connect.php";

$query="UPDATE admin_item_list SET
			owner='',
			status='stock'
where product_code='".$_GET['item_code']."'
and active!='n' 
and owner='".$_GET['emp_code']."' ";


		$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);		

};
echo "<script> window.alert('Succesfully Removed');	window.location='../../admin_system/details/?profile_id=".$_GET["emp_code"]."'; </script>";

//print_r($_GET);

?>