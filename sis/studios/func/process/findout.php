<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";
    
if(isset($_SESSION['customer_id']) && trim($_SESSION['customer_id']) != ''){
    $findout = $conn->real_escape_string($_POST['findout']);

    $query = 'UPDATE orders_studios SET find_out="'.$findout.'" WHERE order_id = "'.$_SESSION['order_no'].'";';

    // echo $query; exit;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_execute($stmt);		
    };
}else{
    echo 'Invalid findout';
}
?>