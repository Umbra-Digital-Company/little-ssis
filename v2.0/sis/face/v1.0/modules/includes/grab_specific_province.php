<?php 

if(!isset($_SESSION)){

    session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Set special province variable
$specProvince = "";

// Check if store code is present in session
if(isset($_SESSION["store_code"])) {    

    $query =    "SELECT
                    sl.province
                FROM
                    stores_locations sl
                WHERE
                    sl.store_id = '".$_SESSION["store_code"]."'";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_store_result($stmt); 

        $specProvince = $result1;        

        mysqli_stmt_close($stmt);
                                
    };
    // else {

    //     echo mysqli_error($conn);

    // }; 

};

?>