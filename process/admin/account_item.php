<?php 
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

require $sDocRoot."/includes/connect.php";

//
//print_r($_POST);

 $query="UPDATE admin_item_list SET
owner='".$_POST['emp_id']."',
status='Owned'

where item_id='".$_POST['item']."'";
	
	$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);		

};
	
echo "<script> window.alert('Succesfully Added');	window.location='../../admin_system/details/?profile_id=".$_POST["emp_id"]."'; </script>";





?>