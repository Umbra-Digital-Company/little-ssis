<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
require $sDocRoot."/includes/connect.php";
///////////////////////////////////////////////////// UPDATE DATABASE

$arrData = [];

$arrData['profile_id'] = mysqli_real_escape_string($conn, $_POST['profile_id']);
$arrData['orders_specs_id'] = mysqli_real_escape_string($conn, $_POST['orders_specs_id']);
$arrData['order_id'] = mysqli_real_escape_string($conn, $_POST['order_id']);
$arrData['address1'] = mysqli_real_escape_string($conn, $_POST['sa_address_1']);
$arrData['address2'] = mysqli_real_escape_string($conn, $_POST['sa_address_2']);
$arrData['country'] = mysqli_real_escape_string($conn, $_POST['sa_country']);
$arrData['province'] = mysqli_real_escape_string($conn, $_POST['sa_province']);
$arrData['city'] = mysqli_real_escape_string($conn, $_POST['sa_city']);
$arrData['barangay'] = mysqli_real_escape_string($conn, $_POST['sa_barangay']);
$arrData['zip_code'] = mysqli_real_escape_string($conn, $_POST['sa_zip_code']);
$arrData['special_instructions'] = mysqli_real_escape_string($conn, $_POST['sa_special_instructions']);
$arrData['email_address'] = mysqli_real_escape_string($conn, $_POST['sa_email_address']);
$arrData['phone_number'] = mysqli_real_escape_string($conn, $_POST['sa_phone_number']);


$query =    'INSERT INTO
                profiles_shipping_address
                ('.implode(",".PHP_EOL, array_keys($arrData)).')
            VALUES
            ("'.implode('",'.PHP_EOL.'"', $arrData).'")
            ON DUPLICATE KEY UPDATE
                address1 = VALUES(address1),
                address2 = VALUES(address2),
                country = VALUES(country),
                province = VALUES(province),
                city = VALUES(city),
                barangay = VALUES(barangay),
                zip_code = VALUES(zip_code),
                special_instructions = VALUES(special_instructions),
                email_address = VALUES(email_address),
                phone_number = VALUES(phone_number);';
//echo $query;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt); 
    mysqli_stmt_close($stmt);       

};

?>