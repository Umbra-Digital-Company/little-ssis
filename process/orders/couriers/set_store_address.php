<?php

	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
  	session_start();

  $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

  // Required includes
  require $sDocRoot."/includes/connect.php";
	
	$queryUser =  "UPDATE stores_locations
                  SET
                address = '".mysqli_real_escape_string($conn,$_POST['address'])."',
                province = '".mysqli_real_escape_string($conn,$_POST['province'])."',
                city = '".mysqli_real_escape_string($conn,$_POST['city'])."',
                barangay = '".mysqli_real_escape_string($conn,$_POST['barangay'])."',
                email_address = '".mysqli_real_escape_string($conn,$_POST['email'])."',
                phone_number = '".mysqli_real_escape_string($conn,str_replace('-', '',$_POST['phone-number']))."'
                WHERE store_id = '".$_SESSION['user_login']['store_code']."';";

    // echo $queryUser;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $queryUser)) {

        mysqli_stmt_execute($stmt);   
        mysqli_stmt_close($stmt);   

    }

    echo 'Store address successfully saved';
?>