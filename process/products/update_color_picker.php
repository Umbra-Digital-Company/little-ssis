<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required scripts
require $sDocRoot."/includes/connect.php";

/////////////////////////////////////// GRAB POST DATA

if(!isset($_POST) || !isset($_POST['product_code']) || !isset($_POST['color_picker'])) {

    exit;

}
else {

    $product_code = $_POST['product_code'];
    $color_picker = $_POST['color_picker'];
    $style        = $_POST['style'];

};

/////////////////////////////////////// UPDATE DATABASE

$query =    'INSERT INTO products_colors (
                product_code,
                color_picker
            )
            VALUES (
                "'.mysqli_real_escape_string($conn, $product_code).'",
                "'.mysqli_real_escape_string($conn, $color_picker).'"
            )
            ON DUPLICATE KEY UPDATE
                product_code = VALUES(product_code),
                color_picker = VALUES(color_picker)';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);
    exit;

};

/////////////////////////////////////// SEND BACK

header('location: /products/frames/edit-frame/?product_code='.$product_code.'&style='.$style);
exit;

?>
