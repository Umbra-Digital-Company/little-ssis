<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot . "/includes/connect.php";

$concern = $_POST['selected'];
$data_id = $_POST['orders_specs_id'];
$date = date("Y-m-d H:i:s");

// $selected_concern= implode(",",$concern);

    if(isset($_POST['selected']) && $_POST['orders_specs_id']){
        $query = 	'UPDATE
                    orders_specs
                SET
                    concern = CONCAT_WS(",",concern,"'.mysqli_real_escape_string($conn, $concern).'")
                    
                WHERE
                    orders_specs_id = "'.$data_id.'"';

            // echo $query;
            echo "<br>";
            echo "<br>";

    // $stmt = mysqli_stmt_init($conn);
    // if (mysqli_stmt_prepare($stmt, $query)) {

    //     mysqli_stmt_execute($stmt);		
    //     mysqli_stmt_close($stmt);		

    // };

    // echo '<script> window.location="/store-locations/"; </script>';

        $query = 	'INSERT INTO
                order_status(
                    order_id,
                    status,
                    status_date
        )
        VALUES(
            "'.mysqli_real_escape_string($conn, $data_id).'",
            "'.mysqli_real_escape_string($conn, $concern).'",
            "'.mysqli_real_escape_string($conn, $date).'"
        )';

        // echo $query;
        // $stmt = mysqli_stmt_init($conn);
        // if (mysqli_stmt_prepare($stmt, $query)) {

        // mysqli_stmt_execute($stmt);		
        // mysqli_stmt_close($stmt);		

        // };

        // echo '<script> window.location="/store-locations"; </script>';


    }else{
        echo "Please select an item in the list.";
    }
?>