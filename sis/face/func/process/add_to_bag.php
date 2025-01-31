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

function getpricePoll51($item_code){
	global $conn;

	$arrPOll51price=array();

		 $queryItem=" Select ";
         if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
            $queryItem .= 'sr_srp';
         }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
            $queryItem .='vnd_srp'; 
         }else{
             $queryItem .= 'retail';
         }
		 $queryItem .= " FROM
							poll_51_face_new
					WHERE
						item_code='".$item_code."' ";
						
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
                orders_face_details
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
				profile_id="'.$_SESSION['customer_id'].'"
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

function setPoNumberAdd($order_no_Cart){
    global $conn;
    $arrCartQF = array();
    $arrCartQuery = 'SELECT  
                        os.po_number
                    FROM 
                       orders_face_details os
                            LEFT  JOIN orders_face o
                                ON o.order_id = os.order_id
                    WHERE   
                        o.order_id = "'.$order_no_Cart.'" ORDER BY os.id DESC LIMIT 1';

    $grabParamsQF = array(
        "po_number"
    );
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParamsQF); $i++) { 

                $tempArray[$grabParamsQF[$i]] = ${'result' . ($i+1)};

            };

            $arrCartQF [] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }

    return $arrCartQF;
}


function finalOrderId(){
    global $conn;

    // $arrOrderid = array();
    // $query = 	"SELECT 
    //                 order_id 
    //             FROM 
    //                 orders c 
    //             WHERE 
    //                 `order_id` NOT REGEXP '_' 
    //                 AND order_id NOT LIKE '%MLA%'
    //                     AND order_id like '".$_SESSION["store_code"]."%'
    //             ORDER By 
    //                 order_id DESC;";

    // $grabParams2 = array( "order_id" );

    // $stmt = mysqli_stmt_init($conn);
    // if (mysqli_stmt_prepare($stmt, $query)) {

    // mysqli_stmt_execute($stmt);
    // mysqli_stmt_bind_result($stmt, $result1);

    // while (mysqli_stmt_fetch($stmt)) {

    //     $tempArray = array();

    //     for ($i=0; $i < 1; $i++) { 

    //     $tempArray[$grabParams2[$i]] = ${'result' . ($i+1)};

    //     };

    //     $arrOrderid[] = $tempArray;

    // };

    // mysqli_stmt_close($stmt);    
                            
    // }
    // else {

    // echo mysqli_error($conn);

    // };

    if(isset($_POST['order_id'])) {
		
        $finalOrderId=$_POST['order_id'];
    
    }
    elseif(isset($_SESSION['order_no'])) {
    
         $finalOrderId=$_SESSION['order_no'];
    
    }
    elseif(!isset($_SESSION["order_no"])) {
    
       // Generate Order NO
        $generate_no = '1234567890';
        $gen_no = "";

        for ($i = 0; $i < 4; $i++) {
            $gen_no .= $generate_no[rand(0, (strlen($generate_no) - 1))];
        };

        $finalOrderId = $_SESSION["store_code"] . '-' . date('ymdHis') . $gen_no;
    };

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
    $setPoNumberAdd = setPoNumberAdd($order_id);
    if(count($setPoNumberAdd) == 0){
        $po_number = '001';
        $po_number = str_replace("-","",$order_id).$po_number;
    }else{
        $po_number = substr($setPoNumberAdd[0]['po_number'], 0, -5);
        $addCount = substr($setPoNumberAdd[0]['po_number'], -5);
        $po_number = $po_number.($addCount + 1);
    }
    $order_specs_id = checkSpecsid($order_id,$order_specs_id);

    return ["order_specs_id"=>$order_specs_id,"po_number"=>$po_number];
}

function insertItem(){
    global $conn;
    $finalOrderId = finalOrderId();
    $total = getpricePoll51($_POST['studios_product_code']);
    $orderSpecsIdPoNumber = setOrderSpecsId($finalOrderId);
    $query = 	'INSERT INTO orders_face(
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
        origin_branch,
        laboratory
    ) VALUES (	
        "'.$finalOrderId.'",
        "'.$_SESSION["customer_id"].'",								
        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["first_name"]).'",
        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["last_name"]).'",
        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["phone_number"]).'",
        "'.mysqli_real_escape_string($conn,getProfilesInfo()[0]["email_address"]).'",
        "0",
        "Cash",';
         $query .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
        $query .= '"'.$_SESSION['store_code'].'",
        "'.getProfilesInfo()[0]["sales_person"].'",
        "'.$_SESSION['store_code'].'",
        "'.( (isset($_SESSION['user_login']['warehouse_code'])) ? $_SESSION['user_login']['warehouse_code'] : '').'"
                                            
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
        origin_branch=values(origin_branch),
        laboratory=values(laboratory)';

    
    $query2 = 	'INSERT INTO orders_face_details(
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
        stock_from
    ) VALUES (
        "'.$finalOrderId.'",
        "'.$_SESSION["customer_id"].'",
        "'.mysqli_real_escape_string($conn,$_POST['studios_product_code']).'",
        "'.mysqli_real_escape_string($conn,$total).'",';
        $query2 .= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? '"VND",' : '"PHP",';
        $query2 .= '"without prescription",
        "",
        "sunnies_face",
        "for confirmation",
        ADDTIME(now(), "13:00:00"),
        "'.$orderSpecsIdPoNumber['po_number'].'",
        "",
        "",
        "'.$orderSpecsIdPoNumber['order_specs_id'].'",
        "n",
        "",
        "'.( (isset($_SESSION['user_login']['warehouse_code'])) ? $_SESSION['user_login']['warehouse_code'] : '').'"
    )';

    $query3 = 	'INSERT INTO order_status_face(
        order_id, 
        status,
        status_date,
        updatee,
        branch
    ) VALUES(
        "'.$finalOrderId.'",
        "for payment",
        now(),
        "'.$_SESSION['id'].'",
        "'.$_SESSION['store_code'].'")';

    $stmt = mysqli_stmt_init($conn);
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

    $_SESSION["order_no"] = $finalOrderId;
    echo $orderSpecsIdPoNumber['order_specs_id'];
}

if(isset($_POST['count_num_value'])){
    for($i = 0; $i < $_POST['count_num_value']; $i++){
        insertItem();
    }

}else{
    insertItem();
}
?>