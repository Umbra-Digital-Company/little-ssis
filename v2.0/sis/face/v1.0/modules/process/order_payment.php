<?php
	if ( !isset($_SESSION) ) { session_start(); }

	if(isset($_SESSION['store_code']) && isset($_SESSION['order_no'])){
		// Included files
		include("../connect.php");

		$query = 	'UPDATE orders_specs
					SET status ="paid",
						status_date = now(),
						payment ="y",
						payment_date = now()
					WHERE order_id = "'.$_SESSION['order_no'].'"
						AND status = "for payment";';
	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);
	        unset($_SESSION["customer_page"]);
	        unset($_SESSION["login_customer"]);
	        unset($_SESSION["customer_id"]);
	        unset($_SESSION["cust_id"]);
	        unset($_SESSION["priority"]);
	        unset($_SESSION["order_no"]);
	        echo "<script> alert('Orders Successfuly paid.'); window.location = '/ssis/?page=store-home'; </script>";

	    } else {

	    	echo mysqli_error($conn);

	    };
	}else{
		echo "Invalid Transaction.";
	}
?>