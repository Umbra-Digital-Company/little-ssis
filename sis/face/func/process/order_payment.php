<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";
if(!isset($_SESSION['user_login']['username'])) {
	header("Location: /");
    exit;
}
	
	if(isset($_SESSION['store_code']) && isset($_SESSION['order_no'])){

		$order_no = $_SESSION["order_no"];
		$profile_id = $_SESSION["customer_id"];
		unset($_SESSION["customer_page"]);
	    unset($_SESSION["login_customer"]);
	    unset($_SESSION["customer_id"]);
	    unset($_SESSION["cust_id"]);
	    unset($_SESSION["priority"]);
	    unset($_SESSION["order_no"]);

	     $status = 'for payment';
	     $payment = 'n';
	     $payment_date = 'NULL';
	   	if($_SESSION['store_type'] == 'ds' || $_SESSION['store_type'] == 'sr' || $_SESSION['store_type'] == 'vs'){
	   		 $status = 'paid';
	   		 $payment = 'y';
	   		 $payment_date = 'ADDTIME(now(), "12:00:00")';
	   	}

	 	$error = false;
		$query = 	'UPDATE orders_face_details
					SET status ="'.$status.'",
						status_date = ADDTIME(now(), "12:00:00"),
						payment ="'.$payment.'",
						payment_date = '.$payment_date.',
						packaging ="y",
						packaging_date = ADDTIME(now(), "12:00:00")
					WHERE order_id = "'.$order_no.'"
						AND status != "cancelled"
						AND dispatch_type != "packaging";';

						// echo $query; exit;
	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);

	    } else {
	    	$error = true;
	    	echo mysqli_error($conn);

	    };

	    $query = 	'UPDATE orders_face_details
					SET status ="'.$status.'",
						status_date = ADDTIME(now(), "12:00:00"),
						payment ="'.$payment.'",
						payment_date = '.$payment_date.',
						packaging ="y",
						packaging_date = ADDTIME(now(), "12:00:00"),
						packaging_for  = "'.$order_no.'",
						packaging_stock = "store"
					WHERE order_id = "'.$order_no.'"
						AND dispatch_type = "packaging";';
	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);

	    } else {
	    	$error = true;
	    	echo mysqli_error($conn);

	    };

	 	$arrOrders = [];
	    $query = 'SELECT  
	                        po_number,
	                        price,
	                        currency,
	                        order_id
	                    FROM 
	                       orders_face_details
	            WHERE 
	                order_id =  "'.$order_no.'"
	                AND status = "paid"
	                ORDER BY id ASC';

	    $grabParamsQF = array(
	        'po_number',
	        'price',
	        'currency',
	        'order_id'
	    );
	    
	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);
	        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

	        while (mysqli_stmt_fetch($stmt)) {

	            $tempArray = array();

	            for ($i=0; $i < sizeOf($grabParamsQF); $i++) { 

	                $tempArray[$grabParamsQF[$i]] = ${'result' . ($i+1)};

	            };
	            
	            $arrOrders[] = $tempArray;

	        };

	        mysqli_stmt_close($stmt);    
	                                
	    }

	    for($i =0; $i < count($arrOrders); $i++ ){
	    	$curr = ($arrOrders[$i]['currency'] == 'PHP') ? strtolower($arrOrders[$i]['currency']) : $arrOrders[$i]['currency'];
		   	$query = 'INSERT INTO payments(
		        po_number, 
		        total,
		        currency,
		        payment_status,
		        payment_method,
		        payment_description,
		        si_number
		    ) VALUES(
		        "'.$arrOrders[$i]['po_number'].'",
		        "'.$arrOrders[$i]['price'].'",
		        "'.$curr.'",
		        "SUCCESS",
		        "cash",
		        "cash",
		        "'.$arrOrders[$i]['order_id'].'"
		        )
		         ON DUPLICATE KEY UPDATE 
		        po_number=values(po_number),
		        total=values(total),						
		        currency=values(currency),
		        payment_status=values(payment_status),
		        payment_method=values(payment_method),
		        payment_description=values(payment_description),											
		        si_number=values(si_number)';
		    $stmt = mysqli_stmt_init($conn);
		    if (mysqli_stmt_prepare($stmt, $query)) {

		        mysqli_stmt_execute($stmt);		

		    }else{
		    	$error = true;
				echo "Invalid Transaction.";
			}
			//echo $query;
		}
		if(!$error){
			$arrName = [];
		    $query = 'SELECT  
		                        first_name,
		                        last_name
		                    FROM 
		                       profiles_info
		            WHERE 
		                profile_id =  "'.$profile_id.'";';

		    $grabParamsQF = array(
		        'first_name',
		        'last_name'
		    );
		    
		    $stmt = mysqli_stmt_init($conn);
		    if (mysqli_stmt_prepare($stmt, $query)) {

		        mysqli_stmt_execute($stmt);
		        mysqli_stmt_bind_result($stmt, $result1, $result2);

		        while (mysqli_stmt_fetch($stmt)) {

		            $tempArray = array();

		            for ($i=0; $i < sizeOf($grabParamsQF); $i++) { 

		                $tempArray[$grabParamsQF[$i]] = ${'result' . ($i+1)};

		            };
		            
		            $arrName[] = $tempArray;

		        };

		        mysqli_stmt_close($stmt);    
		                                
		    }
		    if(isset($_SESSION['guest_customer'])){
			    unset($_SESSION['guest_customer']);
			}
			if(isset($_SESSION['login_set'])){
			    unset($_SESSION['login_set']);
			}
		    $name = ucwords(strtolower($arrName[0]['first_name'].' '.$arrName[0]['last_name']));
			$bdate= $_GET['bdate'];
			echo "<script>  window.location = '/sis/face/".$_GET['path_loc']."/?page=order-dispatched&order_id=".$order_no."&name=".$name."&age=".$bdate."';</script>";
		}else{
			echo "<script> alert('Error Dispatch.'); </script>";
		}
	}else{
		echo "Invalid Transaction..";
	}
	
?>