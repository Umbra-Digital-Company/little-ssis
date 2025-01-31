<pre>

<?php  

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if(!isset($_SESSION['user_login']['username'])) {
    header("Location: /");
    exit;
}


// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

function genOrderId(){
	global $conn;
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
				o.profile_id,
				o.currency,
				os.stock_from
				FROM orders_face o
				LEFT JOIN orders_face_details os ON o.order_id = os.order_id
				WHERE
					o.order_id ="'.mysqli_real_escape_string($conn, $genOrderId).'"
					AND os.po_number = "'.mysqli_real_escape_string($conn, $po_number).'" LIMIT 1;';
	//$query_result .= $query.PHP_EOL;						
	$grabParams = array(

		"profile_id",
		"currency",
		"stock_from"

	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

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
	$arrOrdersSpecs['stock_from'] = $arrProfiles['stock_from'];
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

	$query =    'INSERT INTO orders_face_details('.implode(','.PHP_EOL.'',array_keys($arrOrdersSpecs)).')
				VALUES ("'.implode('",'.PHP_EOL.'"',$arrOrdersSpecs).'")';

	// echo $query.PHP_EOL;
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


$pbag ="p-n";
$arrChecker = array();

$querydetect = 	"SELECT 
					coalesce(sum(if(status = 'received',1,0)),0) AS total,
					count(product_code) AS count,order_id 
				FROM 
					orders_face_details o 
				WHERE 
					order_id = '".$_POST['order_id_value']."'";

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
$genOrderId = genOrderId();

if(isset($_POST['paper_bag']) && trim($_POST['paper_bag']) != ''){
	if($_POST['paper_bag_quantity'] != '' || $_POST['paper_bag_quantity'] != 0 || $_POST['paper_bag_quantity'] <= 5){
		$quantity = mysqli_real_escape_string($conn,$_POST['paper_bag_quantity']);

		$arrSow = ['147','148','149'];
		$fromData = (in_array($_SESSION['store_code'], $arrSow)) ? $_POST['paper_bag_from'] : 'store';
		for($i = 1; $i<=$quantity; $i++){
			$genOrdersPoSpecsIdPaperBag = genOrdersSpecsId($genOrderId, 'paper_bag',$i);
			insertData($genOrderId,$genOrdersPoSpecsIdPaperBag, $_POST['paper_bag'], $_POST['po_number'], $fromData);
		}
		$pbag = 'p-y';
	}
}

	$queryUpdate = 	"UPDATE 
						orders_face_details
					SET
						`status`='dispatched',
						status_date=now(),
						signature='".$_POST['sig']."',
						store_dispatch='y',
						store_dispatch_date=now(),
						dispatch_staff = '".$_POST['dispatch_staff']."',
						dispatch_doctor = '',
						synched='y',
						packaging = 'y',
						packaging_date = '".date("Y-m-d H:i:s")."',
						packaging_employee = '".mysqli_real_escape_string($conn, $_POST['dispatch_staff'])."'
					WHERE 
						order_id='".$_POST['order_id_value']."' AND orders_specs_id = '".$_POST['orders_specs_id']."';";

// echo $queryUpdate.PHP_EOL; 
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
				'dispatched_face_".$pbag."',
				now(),
				'".$_SESSION['id']."',
				'".$_SESSION["store_code"]."'
			)";
// echo $query3.PHP_EOL; exit;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query3)) {

	mysqli_stmt_execute($stmt);		

};
echo '<script>	alert("Success"); </script>';
echo '<script>	window.location.href="/face/dispatch-face"; </script>';

?>


</pre>