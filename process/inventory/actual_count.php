<?php


session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();


$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";


// echo "<pre>";
// print_r($_POST);
// echo "</pre>";


$actual_count_id=$_POST['product_code']."_".$_POST['dateEndRange'];

  $queryadded_count="INSERT INTO inventory_actual_count(product_code,
                                                    `count`,
                                                    actual_count_id,
                                                    date_count,
                                                    date_start,
                                                    date_end,
                                                    store_audited,
                                                    auditor)
                                                    VALUES('".$_POST['product_code']."',
                                                    '".$_POST['actual_count']."',
                                                    '".$actual_count_id."',
                                                    '".$_POST['date']."',
                                                    '".$_POST['dateStartRange']."',
                                                    '".$_POST['dateEndRange']."',
                                                    '".$_POST['store_audited']."',
                                                    '".$_SESSION['id'] ."'
                                                    )
                                                    ON DUPLICATE KEY UPDATE 
                                                    product_code=VALUES(product_code),
                                                    `count`=VALUES(`count`),
                                                    actual_count_id=VALUES(actual_count_id),
                                                    date_count=VALUES(date_count),
                                                    date_start=VALUES(date_start),
                                                    date_end=VALUES(date_end),
                                                    store_audited=VALUES(store_audited),
                                                    auditor=VALUES(auditor)
";


$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryadded_count)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}
else {

    echo mysqli_error($conn);
    exit;

};

echo "<script> window.history.go(-1) </script>";

?>