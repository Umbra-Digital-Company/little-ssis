<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required scripts
require $sDocRoot."/includes/connect.php";

/////////////////////////////////////// GRAB POST DATA

if(!isset($_POST) || !isset($_POST['product_code']) || !isset($_POST['new_stock'])) {

    exit;

}
else {

    $product_code  = $_POST['product_code'];
    $current_stock = $_POST['current_stock'];
    $new_stock     = $_POST['new_stock'];

};

/////////////////////////////////////// UPDATE DATABASE

$query =    'UPDATE
                poll_51_inventory
            SET
                stock = '.mysqli_real_escape_string($conn, $new_stock).',
                stock_history = '.mysqli_real_escape_string($conn, $current_stock).'
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
