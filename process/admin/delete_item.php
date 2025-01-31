<?php $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	

	// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();
	
	require $sDocRoot."/includes/connect.php";


   $query="UPDATE admin_item_list SET
    
    status='disposed',
    active='n'
where item_id='".$_GET['item_code']."'";

// echo $query="DELETE  FROM admin_item_list where item_id='".$_GET['item_code']."'"; 


	$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);		

};
echo "<script> window.alert('Succesfully Deleted');	window.location='../../admin_system/inventory'; </script>";

?>