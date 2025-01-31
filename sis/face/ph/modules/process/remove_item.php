<?php
if ( !isset($_SESSION) ) { session_start(); }

// Included files
include("../connect.php");
//include("../includes/grab_cart.php");
$arrOrderSpecsId = explode(",", mysqli_real_escape_string($conn, $_POST['orders_specs_id']));
$query = 'UPDATE orders_specs SET status="cancelled" WHERE orders_specs_id IN("'.implode('","', $arrOrderSpecsId).'")';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);		
};
?>