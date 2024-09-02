<?php $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();
	
	require $sDocRoot."/includes/connect.php";

//
//print_r($_POST);
$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$itemID = "";

	for ($i=0; $i < 25; $i++) { 

	    $itemID .=$generate_id[rand(0, (strlen($generate_id)-1))];

	};

$ItemIDF=$itemID;





 $query="INSERT INTO admin_item_list(item_name,item_description,product_code,item_id,status)
				VALUES('".$_POST['item']."',
						'".$_POST['description']."',
						'".$_POST['snumber']."',
						'".$ItemIDF."',
						'stock'
				)
				ON DUPLICATE KEY UPDATE 
				item_name=VALUES(item_name),
				item_description=VALUES(item_description),
				product_code=VALUES(product_code),
				item_id=VALUES(item_id),
				status=VALUES(status)
				
				";



$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);		

};


echo "<script> window.alert('Succesfully Added');	window.location='../../admin_system/inventory/'; </script>";
?>