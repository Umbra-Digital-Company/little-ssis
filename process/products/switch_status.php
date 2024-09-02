<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required scripts
require $sDocRoot."/includes/connect.php";

/////////////////////////////////////// GRAB POST DATA

if(!isset($_POST) || !isset($_POST['product_code']) || !isset($_POST['switch'])) {

    exit;

}
else {

    $product_code = $_POST['product_code'];
    $switch       = $_POST['switch'];

};

/////////////////////////////////////// UPDATE DATABASE

$query =    'UPDATE
                poll_51_status
            SET
                status = "'.mysqli_real_escape_string($conn, $switch).'"
            WHERE
                product_code = "'.$product_code.'"';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);
    exit;

};

?>
