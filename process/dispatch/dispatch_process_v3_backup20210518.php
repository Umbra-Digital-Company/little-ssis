<pre>

<?php  

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
$pbag ="";
$hcase ="";


if($_POST['dispatch_type']!='remake' && $_SESSION['store_code'] != '150' &&  $_SESSION['store_code'] != '142'){
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

		$order_id = explode('-', $order_id);
		
		$order_specs_id = $order_id[0].'-'.date('mdYHis').$gen_id_unique;
		if($merch == 'paper_bag'){
			$po_number = '22501'.$count_paperbag;
		}elseif($merch == 'hard_case'){
			$po_number = '227301';
		}
		$po_number = $order_id[0].date('YmdHis').'0'.$po_number;

		return ["order_specs_id"=>$order_specs_id,"po_number"=>$po_number];
	}

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
		$arrOrdersSpecs['payment_date'] = date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s").'+12 hours'));
		$arrOrdersSpecs['dispatch_type'] = "packaging";
		$arrOrdersSpecs['packaging_employee'] = mysqli_real_escape_string($conn, $_POST['dispatch_staff']);

		// $arrOrdersSpecs['status_remarks'] = "re-order";
		// $arrOrdersSpecs['status_date'] = date("Y-m-d H:i:s");

		$query =    'INSERT INTO orders_specs('.implode(','.PHP_EOL.'',array_keys($arrOrdersSpecs)).')
					VALUES ("'.implode('",'.PHP_EOL.'"',$arrOrdersSpecs).'")';

		//echo $query;
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);
		    mysqli_stmt_close($stmt);

		    //return true;                   
		}
		else {

		    echo mysqli_error($conn);
		    exit;

		};
	}

	$genOrderId = genOrderId();
	$pbag = 'p-n';
	$hcase = '_h-n';
	if(isset($_POST['paper_bag']) && trim($_POST['paper_bag']) != ''){
		if($_POST['paper_bag_quantity'] != '' || $_POST['paper_bag_quantity'] != 0 || $_POST['paper_bag_quantity'] <= 5){
			$quantity = mysqli_real_escape_string($conn,$_POST['paper_bag_quantity']);
			for($i = 1; $i<=$quantity; $i++){
				$genOrdersPoSpecsIdPaperBag = genOrdersSpecsId($genOrderId, 'paper_bag',$i);
				insertData($genOrderId,$genOrdersPoSpecsIdPaperBag, $_POST['paper_bag'], $_POST['po_number'], 'store');
			}
			$pbag = 'p-y';
		}
	}
	if(isset($_POST['hard_case']) && trim($_POST['hard_case']) != ''){
		$genOrdersPoSpecsIdHardCase = genOrdersSpecsId($genOrderId, 'hard_case','');
		insertData($genOrderId,$genOrdersPoSpecsIdHardCase, $_POST['hard_case'], $_POST['po_number'], $_POST['hardcase_from']);
		$hcase = '_h-y';
	}
		$query = 'UPDATE orders_specs
					SET packaging = "y", packaging_date = "'.date("Y-m-d H:i:s").'", packaging_employee = "'.mysqli_real_escape_string($conn, $_POST['dispatch_staff']).'"
					WHERE order_id ="'.$genOrderId.'" AND orders_specs_id = "'.mysqli_real_escape_string($conn,$_POST['orders_specs_id']).'";';

		//echo $query;
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);
		    mysqli_stmt_close($stmt);

		    //return true;                   
		}
		else {

		    echo mysqli_error($conn);
		    exit;

		};

	//var_dump($order_id, $ordersPoSpecsId); exit;
	//////////////////////////////////////////////// GRAB POST

	
}

$arrChecker = array();

$querydetect = 	"SELECT 
					coalesce(sum(if(status = 'received',1,0)),0) AS total,
					count(product_code) AS count,order_id 
				FROM 
					orders_specs o 
				WHERE 
					id = '".$_POST['order_id']."'";

$grabParams = array(
    
    'total',
    'count',
	'order_id'

);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querydetect)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2,$result3);

	while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrChecker[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

if($_POST['dispatch_type']=='remake'){
	
	$arrData1= array();
			
	$queryData1 = 	"SELECT 
						os.order_id,
						o.profile_id,
						first_name,
						last_name,
						mobile,
						email_address,
						total,
						payment_method,
						o.currency,
						store_id,
						order_confirmed_at,
						sales_person,
						os.`status`,
						doctor,
						os.product_code,
						prescription_id,
						product_upgrade,
						prescription_vision,
						lens_option,
						o.laboratory,
						payment_date,
						os.po_number,
						os.si_number,
						os.lens_code
					FROM 
						orders o
							LEFT JOIN orders_specs os 
								ON os.order_id=o.order_id
					WHERE 
						os.id = '".$_POST['order_id']."'";
			
	$grabParamsData1 = array(

		'order_id',
		'profile_id',
		'first_name',
		'last_name',
		'mobile',
		'email_address',
		'total',
		'payment_method',
		'currency',
		'store_id',
		'order_confirmed_at',
		'sales_person',
		'status',
		'doctor',
		'product_code',
		'prescription_id',
		'product_upgrade',
		'prescription_vision',
		'lens_option',
		'laboratory',
		'payment_date',
		'po_number',
		'si_number',
		'lens_code'

	);

	$stmt2 = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt2, $queryData1)) {

    	mysqli_stmt_execute($stmt2);
    	mysqli_stmt_bind_result($stmt2, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24);

    	while (mysqli_stmt_fetch($stmt2)) {

        	$tempArray = array();

        	for ($i=0; $i < sizeOf($grabParamsData1); $i++) { 

            	$tempArray[$grabParamsData1[$i]] = ${'result' . ($i+1)};

        	};

        	$arrData1[] = $tempArray;

    	};

    	mysqli_stmt_close($stmt2);    
                            
	}
	else {

	    echo mysqli_error($conn);

	};
												
	$order_id_new = "";		
	$order_id_new = $arrData1[0]["order_id"]."_".$_POST['dispatch_type'];
			
 	$queryInsertOrder = "INSERT INTO 
 							orders(
 								order_id ,
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
 								laboratory
 							)
						VALUES(	
							'".$order_id_new."',
							'".$arrData1[0]["profile_id"]."',
							'".$arrData1[0]["first_name"]."',
							'".$arrData1[0]["last_name"]."',
							'".$arrData1[0]["mobile"]."',
							'".$arrData1[0]["email_address"]."',
							'".$arrData1[0]['total']."',
							'Cash',
							'PHP',
							'".$arrData1[0]["store_id"]."',
							'".$arrData1[0]["sales_person"]."',							
							'".$arrData1[0]["laboratory"]."'
						)";

	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $queryInsertOrder)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

	} 
	else {

		echo mysqli_error($conn);
		return false;
		exit;	

	};

	$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$OrderSpecsId = "";

	for ($i=0; $i < 20; $i++) { 

	    $OrderSpecsId .=$generate_id[rand(0, (strlen($generate_id)-1))];

	};

 	$queryInsertOS = 	"INSERT INTO 
 							orders_specs(
 								order_id,
 								profile_id,
 								product_code,
 								price,
 								currency,
 								lens_option,
 								prescription_id,
 								product_upgrade,
 								prescription_vision,
 								status,
 								status_date,
 								payment,
 								payment_date,
 								dispatch_type,
 								remake_staff,
 								po_number,
 								si_number,
 								orders_specs_id,
 								lens_code,
 								synched
 							)
						VALUES(
							'".$order_id_new."',
							'".$arrData1[0]["profile_id"]."',
							'".$arrData1[0]['product_code']."',
							'".$arrData1[0]['total']."',
							'PHP',
							'".$arrData1[0]['lens_option']."',
							'".$arrData1[0]['prescription_id']."',
							'".$arrData1[0]['product_upgrade']."',
							'".$arrData1[0]['prescription_vision']."',
							'paid',
							now(),
							'y',
							now(),
							'".$_POST['dispatch_type']."',
							'".$_POST['dispatch_staff']."',
							'".$arrData1[0]['po_number']."',
							'".$arrData1[0]['si_number']."',
							'".$OrderSpecsId."',
							'".$arrData1[0]['lens_code']."',
							'y'
						)";		
			
	$stmt = mysqli_stmt_init($conn);
	if(mysqli_stmt_prepare($stmt, $queryInsertOS)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

	} 
	else {

		echo mysqli_error($conn);
		return false;
		exit;	

	};

 	$queryUpdate = 	"UPDATE 
 						orders_specs 
 					SET
						`status`='".$_POST['dispatch_type']."',
						status_date=now(),
						signature='".$_POST['sig']."',
						store_dispatch='n',
						store_dispatch_date=now(),
						`status`='returned',
						store_dispatch='n',
						store_dispatch_date=now(),
						lab_production='n',
						lab_production_date=now(),
						lab_status='n',
						lab_status_date=now(),
						received_stat='r',
						received_stat_date=now(),
						remarks='".mysqli_real_escape_string($conn,$_POST['cancel_remark'])."',
						remake_staff = '".$_POST['dispatch_staff']."',
						synched='y'
					WHERE 
						id='".$_POST['order_id']."'";
	
}
else{

	$queryUpdate = 	"UPDATE 
						orders_specs 
					SET
						`status`='dispatched',
						status_date=now(),
						signature='".$_POST['sig']."',
						store_dispatch='y',
						store_dispatch_date=now(),
						dispatch_staff = '".$_POST['dispatch_staff']."',
						dispatch_doctor = '".$_POST['dispatch_doctor']."',
						synched='y'
					WHERE 
						id='".$_POST['order_id']."' AND orders_specs_id = '".$_POST['orders_specs_id']."';";

}

$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $queryUpdate)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

} 
else {

	echo mysqli_error($conn);
	return false;
	exit;	

};

$query3 = 	"INSERT INTO 
				order_status(
					order_id,
					status,
					status_date,
					updatee,
					branch
				)
			VALUES(
				'".$arrChecker[0]["order_id"]."',
				'dispatched_".$pbag.$hcase."',
				now(),
				'".$_SESSION['id']."',
				'".$_SESSION["store_code"]."'
			)";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query3)) {

	mysqli_stmt_execute($stmt);		

};
echo '<script>	alert("Success"); </script>';
echo '<script>	window.location.href="/dispatch"; </script>';

?>


</pre>