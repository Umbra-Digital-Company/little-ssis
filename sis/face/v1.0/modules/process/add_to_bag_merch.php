<?php
if ( !isset($_SESSION) ) { session_start(); }

// Included files
include("../connect.php");
//include("../includes/grab_cart.php");

function getpricePoll51($item_code){
	global $conn;

	$arrPOll51price=array();

		 $queryItem=" Select price
					 FROM
							poll_51_studios
					WHERE
						product_code= ? ";
						
    $grabParams = array("price");

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $queryItem)) {

    mysqli_stmt_bind_param($stmt, 's', $item_code);
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
				orders_specs
			WHERE 
				orders_specs_id = '".$order_specs_id."' ";

	$grabParams = array('orders_specs_id');

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_bind_param($stmt, 's', $order_specs_id);
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
				profile_id= ?
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

    mysqli_stmt_bind_param($stmt, 's', $_SESSION['customer_id']);
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
                       orders_specs os
                            LEFT  JOIN orders o
                                ON o.order_id = os.order_id
                    WHERE   
                        o.order_id = ? ORDER BY os.id DESC LIMIT 1';

    $grabParamsQF = array(
        "po_number"
    );
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_bind_param($stmt, 's', $order_no_Cart);
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

    $arrOrderid = array();
    $query = 	"SELECT 
                    order_id 
                FROM 
                    orders c 
                WHERE 
                    `order_id` NOT REGEXP '_' 
                    AND order_id NOT LIKE '%MLA%'
                        AND order_id like '%?%'
                ORDER By 
                    order_id DESC;";

    $grabParams2 = array( "order_id" );

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_bind_param($stmt, 's', $_SESSION["store_code"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 1; $i++) { 

        $tempArray[$grabParams2[$i]] = ${'result' . ($i+1)};

        };

        $arrOrderid[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
    }
    else {

    echo mysqli_error($conn);

    };

    if(isset($_POST['order_id'])) {
		
        $finalOrderId=$_POST['order_id'];
    
    }
    elseif(isset($_SESSION['order_no'])) {
    
         $finalOrderId=$_SESSION['order_no'];
    
    }
    elseif(!isset($_SESSION["order_no"])) {
    
        if(sizeof($arrOrderid)!='0'){
            
            $OrderID=str_replace($_SESSION["store_code"]."-","",$arrOrderid[0]["order_id"])+1;
    
        }
        else{
            
            $idChars = '0123456789';
            $OrderID = "0000000001";	//9999999991
        //	0000000001
        };
        
      $finalOrderId= $_SESSION["store_code"]."-".str_pad($OrderID,10, '0', STR_PAD_LEFT);
        
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
        $po_number = '1';
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
    $query = 	'INSERT INTO orders(
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
        ?,
        ?,								
        ?,
        ?,
        ?,
        ?,
        "0",
        "Cash",
        "PHP",
        ?,
        ?,
        ?
                                            
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

    
    $query2 = 	'INSERT INTO orders_specs(
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
        synched
    ) VALUES (
        ?,
        ?,
        "M100",
        ?,
        "PHP",
        "without prescription",
        "",
        ?,
        "for payment",
        ?,
        ?,
        "",
        "",
        ?,
        "n"
    )';
//echo $OrderSpecsIdF;
    $query3 = 	'INSERT INTO order_status(
        order_id, 
        status,
        status_date,
        updatee,
        branch
    ) VALUES(
        ?,
        "for payment",
        ?,
        ?,
        ?)';

    $stmt = mysqli_stmt_init($conn);
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_bind_param($stmt, 'sssssssss', $finalOrderId, $_SESSION["customer_id"], getProfilesInfo()[0]["first_name"], getProfilesInfo()[0]["last_name"], getProfilesInfo()[0]["phone_number"], getProfilesInfo()[0]["email_address"], $_SESSION['store_code'], getProfilesInfo()[0]["sales_person"], $_SESSION['store_code']);
        mysqli_stmt_execute($stmt);		

    };

    if (mysqli_stmt_prepare($stmt, $query2)) {

    mysqli_stmt_bind_param($stmt, 'sssssss', $finalOrderId, $_SESSION["customer_id"], $total, $_POST['studios_product_code'], now(), $orderSpecsIdPoNumber['po_number'], $orderSpecsIdPoNumber['order_specs_id']); 
    mysqli_stmt_execute($stmt);		

    };

    if (mysqli_stmt_prepare($stmt, $query3)) {

    mysqli_stmt_bind_param($stmt, 'ssss', $finalOrderId, now(), $_SESSION["id"], $_SESSION['store_code']);
    mysqli_stmt_execute($stmt);		

    };

    $_SESSION["order_no"] = $finalOrderId;
    echo $OrderSpecsIdF;
}
if(isset($_POST['count_num_value'])){
    for($i = 0; $i < $_POST['count_num_value']; $i++){
        insertItem();
    }

}else{
    insertItem();
}
?>