<?php

	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
  	session_start();

  $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

  // Required includes
  require $sDocRoot."/includes/connect.php";
	
	$queryUser =  "INSERT INTO 
              profiles_shipping_address(
              	profile_id,
                orders_specs_id,
                order_id,
                address1,
                address2,
                country,
                province,
                city,
                barangay,
                zip_code,
                email_address,
                phone_number
              )
            VALUES(
            '".mysqli_real_escape_string($conn,$_GET['profile_id'])."',
              '".mysqli_real_escape_string($conn,$_GET['order_id'])."',
              '".mysqli_real_escape_string($conn,$_GET['order_id'])."',
              '".mysqli_real_escape_string($conn,$_POST['address1'])."',
              '".mysqli_real_escape_string($conn,$_POST['address2'])."',
              'ph',
              '".mysqli_real_escape_string($conn,$_POST['province'])."',
              '".mysqli_real_escape_string($conn,$_POST['city'])."',
              '".mysqli_real_escape_string($conn,$_POST['barangay'])."',
             '".mysqli_real_escape_string($conn,$_POST['zip-code'])."',
              '".mysqli_real_escape_string($conn,$_POST['email'])."',
              '".mysqli_real_escape_string($conn,str_replace('-', '',$_POST['phone-number']))."'
            ) ON DUPLICATE KEY UPDATE
               address1 = VALUES(address1),
               address2 = VALUES(address2),
               country = VALUES(country),
               province = VALUES(province),
               city = VALUES(city),
               barangay = VALUES(barangay),
               zip_code = VALUES(zip_code),
               email_address = VALUES(email_address),
               phone_number = VALUES(phone_number)
            ;
            ";

    // echo $queryUser;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $queryUser)) {

        mysqli_stmt_execute($stmt);   
        mysqli_stmt_close($stmt);   

    }

    echo 'Shipping address successfully saved';
?>