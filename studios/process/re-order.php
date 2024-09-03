<?php
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Included files
	require $sDocRoot."/includes/connect.php";
	// echo '<pre>';
	// print_r($_POST);

	if(!isset($_SESSION['user_login']['username'])) {
		header("Location: /");
		exit;
	}
	

	function getpricePoll51($item_code){
		global $conn;

		$arrPOll51price=array();

			 $queryItem=" Select ";
	         if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
	            $queryItem .= 'sr_price';
	         }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
	            $queryItem .='vnd_srp'; 
	         }else{
	             $queryItem .= 'price';
	         }
			 $queryItem .= " FROM
								poll_51_studios_new
						WHERE
							product_code='".$item_code."' ";
							
	    $grabParams = array("price");

	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $queryItem)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	        $tempArray[$grabParams[$i]] = $result1;

	        };

	        $arrPOll51price[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                                
	    }

	    return $arrPOll51price[0]["price"];

	}

	function checkSpecsid($order_id,$order_specs_id){

	    global $conn;

	    $arrOrderspecsid= array();

	    $query= "SELECT 
	                orders_specs_id 
	            FROM 
	                orders_sunnies_studios
	            WHERE 
	                orders_specs_id = '".$order_specs_id."' ";

	    $grabParams = array('orders_specs_id');

	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);
	        mysqli_stmt_bind_result($stmt, $result1);

	        while (mysqli_stmt_fetch($stmt)) {

	            $tempArray = array();

	            for ($i=0; $i < 1; $i++) { 

	                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	            };

	            $arrOrderspecsid[] = $tempArray;

	        };

	        mysqli_stmt_close($stmt);    
	                                            
	    }
	    else {

	        echo mysqli_error($conn);

	    }; 

	    if($arrOrderspecsid){

	        $generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwyz';
	        $OrderSpecsId = "";

	        for ($i=0; $i < 9; $i++) { 

	            $OrderSpecsId .=$generate_id[rand(0, (strlen($generate_id)-1))];

	        };
	        
	        $OrderSpecsIdF = $order_id . '1'.$OrderSpecsId;

	    }
	    else{

	        $OrderSpecsIdF=$order_specs_id;

	    }

	    return $OrderSpecsIdF;

	};

	function getProfilesInfo(){
	    global $conn;
	    $arrCustomer = array();

	    $query = 	'SELECT
					id,
					first_name,
					last_name,
					middle_name,
					phone_number,
					gender,
					birthday,
					email_updates,
					province,
					city,
					barangay,
					age,
					branch_applied,
					email_address,
					sales_person 
				FROM 
					profiles_info c 
				WHERE 
					profile_id="'.$_POST['profile_id'].'"
				ORDER BY 
					id DESC;';

	    $grabParams = array( 
	        "id",
	        "first_name",
	        "last_name",
	        "middle_name",
	        "phone_number",
	        "gender",
	        "birthday",
	        "email_updates",
	        "province",
	        "city",
	        "barangay",
	        "age",
	        "branch_applied",
	        "email_address",
	        "sales_person"
	    );

	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14,$result15);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < 15; $i++) { 

	        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };

	        $arrCustomer[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                                
	    }
	    else {

	    echo mysqli_error($conn);

	    };

	    return $arrCustomer;
	}

	function finalOrderId(){
       // Generate Order NO
        $generate_no = '1234567890';
        $gen_no = "";

        for ($i = 0; $i < 4; $i++) {
            $gen_no .= $generate_no[rand(0, (strlen($generate_no) - 1))];
        };

        $finalOrderId = $_SESSION["store_code"] . '-' . date('ymdHis') . $gen_no;
	    return $finalOrderId;
	}

	function setOrderSpecsId($order_id){
	    $order_specs_id = "";

	    $generate_unique_order_specs= '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqxyz';
	    $gen_id_unique = "";

	    for ($i=0; $i < 9; $i++) { 
	        $gen_id_unique .=$generate_unique_order_specs[rand(0, (strlen($generate_unique_order_specs)-1))];
	    };

	    $order_specs_id = $order_id . '1'.$gen_id_unique;
	    
        $po_number = '1';
        $po_number = str_replace("-","",$order_id).$po_number;
	    
	    $order_specs_id = checkSpecsid($order_id,$order_specs_id);

	    return ["order_specs_id"=>$order_specs_id,"po_number"=>$po_number];
	}


	function insertItem($status,$payment){
	    global $conn;
	    $finalOrderId = finalOrderId();
	    $total = getpricePoll51($_POST['order_product']);


	    $orderSpecsIdPoNumber = setOrderSpecsId($finalOrderId);
	    $query = 	'INSERT INTO orders_studios(
	        order_id,
	        profile_id,
	        first_name,
	        last_name,
	        mobile,
	        email_address,
	        total,
	        payment_method,
	        currency,
	        store_id,
	        sales_person,
	        origin_branch
	    ) VALUES (	
	        "'.$finalOrderId.'",
	        "'.$_POST["profile_id"].'",								
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["first_name"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["last_name"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["phone_number"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["email_address"]).'",
	        "0",
	        "Cash",';
	         $query .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
	        $query .= '"'.$_SESSION['store_code'].'",
	        "'.getProfilesInfo()[0]["sales_person"].'",
	        "'.$_SESSION['store_code'].'"
	                                        
	    ) ON DUPLICATE KEY UPDATE 
	        order_id=values(order_id),
	        profile_id=values(profile_id),						
	        first_name=values(first_name),
	        last_name=values(last_name),
	        mobile=values(mobile),
	        email_address=values(email_address),											
	        total=values(total),
	        payment_method=values(payment_method),
	        currency=values(currency),
	        store_id=values(store_id),
	        sales_person=values(sales_person),
	        origin_branch=values(origin_branch)';

	    // echo $query.PHP_EOL;
	    $query2 = 	'INSERT INTO orders_sunnies_studios(
	        order_id,
	        profile_id,
	        product_code,
	        price,
	        currency,
	        lens_option,
	        reason,
	        product_upgrade,
	        status,
	        status_date,
	        po_number,
	        tints,
	        lens_code,
	        orders_specs_id,
	        synched,
	        dispatch_type,
	        old_po_number,
	        payment,
	        payment_date
	    ) VALUES (
	        "'.$finalOrderId.'",
	        "'.$_POST["profile_id"].'",
	        "'.mysqli_real_escape_string($conn,$_POST['order_product']).'",
	        "'.mysqli_real_escape_string($conn,$total).'",';
	        $query2 .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
	        $query2 .= '"without prescription",
	        "",
	        "sunnies_studios",
	        "'.$status.'",
	        ADDTIME(now(), "12:00:00"),
	        "'.$orderSpecsIdPoNumber['po_number'].'",
	        "",
	        "",
	        "'.$orderSpecsIdPoNumber['order_specs_id'].'",
	        "n",
	        "re-order",
	        "'.mysqli_real_escape_string($conn,$_POST['po_number']).'",
	        "'.$payment.'",
	        ADDTIME(now(), "12:00:00")
	    )';
	    // echo $query2;


	    $query3 = 	'INSERT INTO order_status_studios(
	        order_id, 
	        status,
	        status_date,
	        updatee,
	        branch
	    ) VALUES(
	        "'.$finalOrderId.'",
	        "paid",
	        now(),
	        "'.$_SESSION['id'].'",
	        "'.$_SESSION['store_code'].'")';

	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);		

	    };

	    if (mysqli_stmt_prepare($stmt, $query2)) {

	    mysqli_stmt_execute($stmt);		

	    };

	    if (mysqli_stmt_prepare($stmt, $query3)) {

		    mysqli_stmt_execute($stmt);		

		};
	    if(isset($_SESSION['store_type']) && (trim($_SESSION['store_type']) == 'sr' || trim($_SESSION['store_type']) == 'ds' || trim($_SESSION['store_type']) == 'vs')){
	    	$currency = (trim($_SESSION['store_type']) == 'vs') ? 'vnd' : 'php';

		     $query = 	'INSERT INTO payments(
		        po_number, 
		        total,
		        currency,
		        payment_status,
		        payment_method,
		        payment_description,
		        si_number
		    ) VALUES(
		        "'.$orderSpecsIdPoNumber['po_number'].'",
		        "'.mysqli_real_escape_string($conn,$total).'",
		        "'.$currency.'",
		        "SUCCESS",
		        "cash",
		        "cash",
		        "'.$finalOrderId.'")';

		    $stmt = mysqli_stmt_init($conn);
		    if (mysqli_stmt_prepare($stmt, $query)) {

		        mysqli_stmt_execute($stmt);		

		    };
		}
	}

	function insertItemAntirad($status,$payment){
	    global $conn;
	    $finalOrderId = finalOrderId();
	    $total = getpricePoll51($_POST['order_product']);
	    $orderSpecsIdPoNumber = setOrderSpecsId($finalOrderId);
	    $query = 	'INSERT INTO orders_studios(
	        order_id,
	        profile_id,
	        first_name,
	        last_name,
	        mobile,
	        email_address,
	        total,
	        payment_method,
	        currency,
	        store_id,
	        sales_person,
	        origin_branch
	    ) VALUES (	
	        "'.$finalOrderId.'",
	        "'.$_POST["profile_id"].'",								
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["first_name"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["last_name"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["phone_number"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["email_address"]).'",
	        "0",
	        "Cash",';
	         $query .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
	        $query .= '"'.$_SESSION['store_code'].'",
	        "'.getProfilesInfo()[0]["sales_person"].'",
	        "'.$_SESSION['store_code'].'"
	                                            
	    ) ON DUPLICATE KEY UPDATE 
	        order_id=values(order_id),
	        profile_id=values(profile_id),						
	        first_name=values(first_name),
	        last_name=values(last_name),
	        mobile=values(mobile),
	        email_address=values(email_address),											
	        total=values(total),
	        payment_method=values(payment_method),
	        currency=values(currency),
	        store_id=values(store_id),
	        sales_person=values(sales_person),
	        origin_branch=values(origin_branch)';

	    // echo $query.PHP_EOL;
	    $query2 = 	'INSERT INTO orders_sunnies_studios(
	        order_id,
	        profile_id,
	        product_code,
	        price,
	        currency,
	        lens_option,
	        reason,
	        product_upgrade,
	        status,
	        status_date,
	        po_number,
	        tints,
	        lens_code,
	        orders_specs_id,
	        synched,
	        dispatch_type,
	        old_po_number,
	        payment,
	        payment_date
	    ) VALUES (
	        "'.$finalOrderId.'",
	        "'.$_POST["profile_id"].'",
	        "'.mysqli_real_escape_string($conn,$_POST['order_product']).'",
	        "'.mysqli_real_escape_string($conn,$total).'",';
	        $query2 .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
	        $query2 .= '"without prescription",
	        "",
	        "G100",
	        "'.$status.'",
	        ADDTIME(now(), "12:00:00"),
	        "'.$orderSpecsIdPoNumber['po_number'].'",
	        "",
	        "",
	        "'.$orderSpecsIdPoNumber['order_specs_id'].'",
	        "n",
	        "re-order",
	        "'.mysqli_real_escape_string($conn,$_POST['po_number']).'",
	        "'.$payment.'",
	        ADDTIME(now(), "12:00:00")
	    )';

	    // echo $query2;

	    $query3 = 	'INSERT INTO order_status_studios(
	        order_id, 
	        status,
	        status_date,
	        updatee,
	        branch
	    ) VALUES(
	        "'.$finalOrderId.'",
	        "paid",
	        now(),
	        "'.$_SESSION['id'].'",
	        "'.$_SESSION['store_code'].'")';

	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);		

	    };

	    if (mysqli_stmt_prepare($stmt, $query2)) {

	    mysqli_stmt_execute($stmt);		

	    };
	    if(isset($_SESSION['store_type']) && (trim($_SESSION['store_type']) == 'sr' || trim($_SESSION['store_type']) == 'ds' || trim($_SESSION['store_type']) == 'vs')){
	    	$currency = (trim($_SESSION['store_type']) == 'vs') ? 'vnd' : 'php';

		     $query = 	'INSERT INTO payments(
		        po_number, 
		        total,
		        currency,
		        payment_status,
		        payment_method,
		        payment_description,
		        si_number
		    ) VALUES(
		        "'.$orderSpecsIdPoNumber['po_number'].'",
		        "'.mysqli_real_escape_string($conn,$total).'",
		        "'.$currency.'",
		        "SUCCESS",
		        "cash",
		        "cash",
		        "'.$finalOrderId.'")';

		    $stmt = mysqli_stmt_init($conn);
		    if (mysqli_stmt_prepare($stmt, $query)) {

		        mysqli_stmt_execute($stmt);		

		    };
		}

	}

	function insertItemMerch($status,$payment){
	    global $conn;
	    $finalOrderId = finalOrderId();
	    $total = getpricePoll51($_POST['order_product']);
	    $orderSpecsIdPoNumber = setOrderSpecsId($finalOrderId);
	    $query = 	'INSERT INTO orders_studios(
	        order_id,
	        profile_id,
	        first_name,
	        last_name,
	        mobile,
	        email_address,
	        total,
	        payment_method,
	        currency,
	        store_id,
	        sales_person,
	        origin_branch
	    ) VALUES (	
	        "'.$finalOrderId.'",
	        "'.$_POST["profile_id"].'",								
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["first_name"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["last_name"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["phone_number"]).'",
	        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["email_address"]).'",
	        "0",
	        "Cash",';
	        $query .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
	        $query .= '"'.$_SESSION['store_code'].'",
	        "'.getProfilesInfo()[0]["sales_person"].'",
	        "'.$_SESSION['store_code'].'"
	                                            
	    ) ON DUPLICATE KEY UPDATE 
	        order_id=values(order_id),
	        profile_id=values(profile_id),						
	        first_name=values(first_name),
	        last_name=values(last_name),
	        mobile=values(mobile),
	        email_address=values(email_address),											
	        total=values(total),
	        payment_method=values(payment_method),
	        currency=values(currency),
	        store_id=values(store_id),
	        sales_person=values(sales_person),
	        origin_branch=values(origin_branch)';
	    // echo $query.PHP_EOL;

	    $query2 = 	'INSERT INTO orders_sunnies_studios(
	        order_id,
	        profile_id,
	        product_code,
	        price,
	        currency,
	        lens_option,
	        reason,
	        product_upgrade,
	        status,
	        status_date,
	        po_number,
	        tints,
	        lens_code,
	        orders_specs_id,
	        synched,
	        dispatch_type,
	        old_po_number,
	        payment,
	        payment_date
	    ) VALUES (
	        "'.$finalOrderId.'",
	        "'.$_POST["profile_id"].'",
	        "M100",
	        "'.mysqli_real_escape_string($conn,$total).'",';
	        $query2 .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
	        $query2 .= '"without prescription",
	        "",
	        "'.mysqli_real_escape_string($conn,$_POST['studios_product_code']).'",
	        "'.$status.'",
	        ADDTIME(now(), "12:00:00"),
	        "'.$orderSpecsIdPoNumber['po_number'].'",
	        "",
	        "",
	        "'.$orderSpecsIdPoNumber['order_specs_id'].'",
	        "n",
	        "re-order",
	        "'.mysqli_real_escape_string($conn,$_POST['po_number']).'",
	        "'.$payment.'",
	        ADDTIME(now(), "12:00:00")
	    )';
		// echo $query2;
	    $query3 = 	'INSERT INTO order_status_studios(
	        order_id, 
	        status,
	        status_date,
	        updatee,
	        branch
	    ) VALUES(
	        "'.$finalOrderId.'",
	        "paid",
	        now(),
	        "'.$_SESSION['id'].'",
	        "'.$_SESSION['store_code'].'")';

	    $stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);		

	    };

	    if (mysqli_stmt_prepare($stmt, $query2)) {

	    mysqli_stmt_execute($stmt);		

	    };
	    if(isset($_SESSION['store_type']) && (trim($_SESSION['store_type']) == 'sr' || trim($_SESSION['store_type']) == 'ds' || trim($_SESSION['store_type']) == 'vs')){
	    	$currency = (trim($_SESSION['store_type']) == 'vs') ? 'vnd' : 'php';

		     $query = 	'INSERT INTO payments(
		        po_number, 
		        total,
		        currency,
		        payment_status,
		        payment_method,
		        payment_description,
		        si_number
		    ) VALUES(
		        "'.$orderSpecsIdPoNumber['po_number'].'",
		        "'.mysqli_real_escape_string($conn,$total).'",
		        "'.$currency.'",
		        "SUCCESS",
		        "cash",
		        "cash",
		        "'.$finalOrderId.'")';

		    $stmt = mysqli_stmt_init($conn);
		    if (mysqli_stmt_prepare($stmt, $query)) {

		        mysqli_stmt_execute($stmt);		

		    };
		}
	}

	function updateOrderReturn(){
		global $conn;

		$query = 	'UPDATE orders_sunnies_studios
					SET
						status = "return",
						status_date = ADDTIME(now(), "12:00:00"),
						old_po_number=po_number,
                        po_number = CONCAT(po_number,"7637")
  					WHERE orders_specs_id = "'.mysqli_real_escape_string($conn,$_POST['orderNo']) .'";';

		// echo $query.PHP_EOL;

		$stmt = mysqli_stmt_init($conn);
	    if (mysqli_stmt_prepare($stmt, $query)) {

	        mysqli_stmt_execute($stmt);		

	    };
	}

	$status = 'foy payment';
	$payment =  'n';

	if(isset($_SESSION['store_type']) && (trim($_SESSION['store_type']) == 'sr' || trim($_SESSION['store_type']) == 'ds' || trim($_SESSION['store_type']) == 'vs')){
		$status = 'paid';
		$payment = 'y';
	}

	if($_POST['product_class'] == 'studios'){
		updateOrderReturn();
		insertItem($status,$payment);
	}elseif($_POST['product_class'] == 'antirad'){
		updateOrderReturn();
		insertItemAntirad($status,$payment);
	}elseif($_POST['product_class'] == 'merch'){
		updateOrderReturn();
		insertItemMerch($status,$payment);
	}

	echo '<script> alert("Re - order successfully saved"); window.location ="/studios/dispatch-studios";</script>';

?>