<?php   

function genOrderId(){
	global $conn;
    // Generate Order NO
    // $generate_no = '1234567890';
    // $gen_no = "";

    // for ($i = 0; $i < 4; $i++) {
    //     $gen_no .= $generate_no[rand(0, (strlen($generate_no) - 1))];
    // };

    // $order_id = '787' . '-' . date('ymdHis') . $gen_no;
    $order_id = mysqli_real_escape_string($conn,$_POST['order_id_value']);
    return $order_id;
}
function genOrdersSpecsId($order_id, $merch, $count_paperbag){
	$order_specs_id = "";

	$generate_unique_order_specs= '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqxyz';
	$gen_id_unique = "";

	for ($i=0; $i < 11; $i++) { 
		$gen_id_unique .=$generate_unique_order_specs[rand(0, (strlen($generate_unique_order_specs)-1))];
	};

	
	$order_specs_id = $_SESSION['store_code'].'-'.date('mdYHis').$gen_id_unique;
	if($merch == 'paper_bag'){
		$po_number = '22501'.$count_paperbag;
	}elseif($merch == 'hard_case'){
		$po_number = '227301';
	}
	$po_number = str_replace("-","",$order_id).$po_number;

	return ["order_specs_id"=>$order_specs_id,"po_number"=>$po_number];
}

if($_POST['paper_bag'] == '' || $_POST['hard_case'] == ''){
	echo json_encode(['Please Select Paper bag and Harcase']);
	exit;
}
$genOrderId = genOrderId();
if(isset($_POST['paper_bag']) && $_POST['paper_bag'] != '' && $_POST['paper_bag_quantity'] != ''){
	$quantity = mysqli_real_escape_string($conn,$_POST['paper_bag_quantity']);
	for($i = 1; $i<=$quantity; $i++){
		$genOrdersPoSpecsIdPaperBag = genOrdersSpecsId($genOrderId, 'paper_bag',$i);
		insertData($genOrderId,$genOrdersPoSpecsIdPaperBag, $_POST['paper_bag'], $_POST['po_number'], $_POST['paper_bag_from']);
	}
}
if(isset($_POST['hard_case']) && $_POST['hard_case'] != ''){
	$genOrdersPoSpecsIdHardCase = genOrdersSpecsId($genOrderId, 'hard_case','');
	insertData($genOrderId,$genOrdersPoSpecsIdHardCase, $_POST['hard_case'], $_POST['po_number'], $_POST['hardcase_from']);
}
	$query = 'UPDATE orders_specs
				SET packaging = "y", packaging_date = "'.date("Y-m-d H:i:s").'", packaging_employee = "'.mysqli_real_escape_string($conn, $_POST['dispatch_staff']).'"
				WHERE order_id ="'.$genOrderId.'" AND orders_specs_id = "'.mysqli_real_escape_string($conn,$_POST['orders_specs_id']).'";';

	// echo $query;
	// $stmt = mysqli_stmt_init($conn);
	// if (mysqli_stmt_prepare($stmt, $query)) {

	//     mysqli_stmt_execute($stmt);
	//     mysqli_stmt_close($stmt);

	//     //return true;                   
	// }
	// else {

	//     echo mysqli_error($conn);
	//     exit;

	// };

	// echo 'Packaging done.';

//var_dump($order_id, $ordersPoSpecsId); exit;
//////////////////////////////////////////////// GRAB POST

function insertData($genOrderId, $genOrdersPoSpecsId, $product_code, $po_number, $from){
	
	global $conn;
	$arrProfiles = array();
	$query = 	'SELECT
				profile_id,
				currency
				FROM orders
				WHERE
					order_id ="'.mysqli_real_escape_string($conn, $genOrderId).'";';
	//$query_result .= $query.PHP_EOL;						
	$grabParams = array(

		"profile_id",
		"currency"

	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };

	        $arrProfiles = $tempArray;

    	};

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

	$arrOrdersSpecs['order_id'] = $genOrderId;
	$arrOrdersSpecs['orders_specs_id'] = $genOrdersPoSpecsId['order_specs_id'];
	$arrOrdersSpecs['profile_id'] = $arrProfiles['profile_id'];
	$arrOrdersSpecs['currency'] = $arrProfiles['currency'];
	$arrOrdersSpecs['product_code'] = 'M100';
	$arrOrdersSpecs['product_upgrade'] = $product_code;
	$arrOrdersSpecs['po_number'] = $genOrdersPoSpecsId['po_number'];
	$arrOrdersSpecs['packaging'] = 'y';
	$arrOrdersSpecs['packaging_date'] = date("Y-m-d H:i:s");
	$arrOrdersSpecs['packaging_for'] = mysqli_real_escape_string($conn,$po_number);
	$arrOrdersSpecs['packaging_stock'] = mysqli_real_escape_string($conn,$from);
	$arrOrdersSpecs['price'] = "0";
	$arrOrdersSpecs['status'] = "paid";
	$arrOrdersSpecs['status_date'] = date("Y-m-d H:i:s");
	$arrOrdersSpecs['lens_option'] = "without prescription";
	$arrOrdersSpecs['lens_code'] = "";
	$arrOrdersSpecs['signature'] = "signature";
	$arrOrdersSpecs['payment'] = "y";
	$arrOrdersSpecs['payment_date'] = date("Y-m-d H:i:s");
	$arrOrdersSpecs['dispatch_type'] = "packaging";
	$arrOrdersSpecs['packaging_employee'] = mysqli_real_escape_string($conn, $_POST['dispatch_staff']);

	// $arrOrdersSpecs['status_remarks'] = "re-order";
	// $arrOrdersSpecs['status_date'] = date("Y-m-d H:i:s");

	$query =    'INSERT INTO orders_specs('.implode(','.PHP_EOL.'',array_keys($arrOrdersSpecs)).')
				VALUES ("'.implode('",'.PHP_EOL.'"',$arrOrdersSpecs).'")';

	// echo $query;
	// $stmt = mysqli_stmt_init($conn);
	// if (mysqli_stmt_prepare($stmt, $query)) {

	//     mysqli_stmt_execute($stmt);
	//     mysqli_stmt_close($stmt);

	//     //return true;                   
	// }
	// else {

	//     echo mysqli_error($conn);
	//     exit;

	// };
}
?>
