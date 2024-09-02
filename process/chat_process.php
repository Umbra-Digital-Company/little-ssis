<?php


 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();
// Required includes
require $sDocRoot."/includes/connect.php";
if(!isset($_SESSION)){
        session_start();
    }


$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';
	$OrderSpecsId = "";

	for ($i=0; $i < 21; $i++) { 

	    $OrderSpecsId .=$generate_id[rand(0, (strlen($generate_id)-1))];

	};
	$Messageid_final=date('YmdHis').$OrderSpecsId;

$query="INSERT INTO remarks_comm(order_po_id,profile_id,message,message_id)
				VALUES('".$_POST['order_specsid']."',
						'".$_POST['sender']."',
						'".mysqli_real_escape_string($conn,$_POST['messenger_input'])."',
						'".$Messageid_final."'
				)";


					$stmt = mysqli_stmt_init($conn);
										if(mysqli_stmt_prepare($stmt, $query)) {

										mysqli_stmt_execute($stmt);
										mysqli_stmt_close($stmt);

										} 
										else {

										echo mysqli_error($conn);
										return false;
										exit;	

										};
	




?>