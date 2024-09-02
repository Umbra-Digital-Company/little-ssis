<pre>

<?php  

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)){

	session_start();

}

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
function genOrdersSpecsId($order_id){
	$order_specs_id = "";

	$generate_unique_order_specs= '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwqxyz';
	$gen_id_unique = "";

	for ($i=0; $i < 11; $i++) { 
		$gen_id_unique .=$generate_unique_order_specs[rand(0, (strlen($generate_unique_order_specs)-1))];
	};

	$order_id = explode('-', $order_id);
	
	$order_specs_id = $order_id[0].'-'.date('mdYHis').$gen_id_unique;

	return $order_specs_id;
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
						os.lens_code,
						o.origin_branch,
						os.price,
						os.stock_from
					FROM 
						orders o
							LEFT JOIN orders_specs os 
								ON os.order_id=o.order_id
					WHERE 
						os.orders_specs_id = '".$_POST['order_specs_id']."'";
	//echo $queryData1;
			
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
		'lens_code',
		'origin_branch',
		'price',
		'stock_from'

	);

	$stmt2 = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt2, $queryData1)) {

    	mysqli_stmt_execute($stmt2);
		mysqli_stmt_bind_result($stmt2, $result1, $result2, $result3, $result4, $result5, $result6, $result7, 
		$result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27);

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
												
	// $order_id_new = "";		
	$order_id_new = $arrData1[0]["order_id"]."_remake";
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
 								laboratory,
								 doctor,
								 origin_branch
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
							'".$arrData1[0]["laboratory"]."',
							'".$arrData1[0]["doctor"]."',
							'".$arrData1[0]["origin_branch"]."'
						)";
	//echo $queryInsertOrder;
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

	$OrderSpecsId = genOrdersSpecsId($arrData1[0]["order_id"]);

	$payment_date= date("Y-m-d H:i:s", strtotime(date("Y-m-d H:i:s").'+12 hours'));

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
 								synched,
								 stock_from
 							)
						VALUES(
							'".$order_id_new."',
							'".$arrData1[0]["profile_id"]."',
							'".$arrData1[0]['product_code']."',
							'".$arrData1[0]['price']."',
							'PHP',
							'".$arrData1[0]['lens_option']."',
							'".$arrData1[0]['prescription_id']."',
							'".$arrData1[0]['product_upgrade']."',
							'".$arrData1[0]['prescription_vision']."',
							'paid',
							'".$payment_date."',
							'y',
							'".$payment_date."',
							'".$_POST['dispatch_type']."',
							'".$_POST['dispatch_staff']."',
							'".$arrData1[0]['po_number']."',
							'".$arrData1[0]['si_number']."',
							'".$OrderSpecsId."',
							'".$arrData1[0]['lens_code']."',
							'y',
							'".$arrData1[0]['stock_from']."'
						)";		
	//echo $queryInsertOS;
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
						status_date=now(),
						
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
						orders_specs_id='".mysqli_real_escape_string($conn,$_POST['order_specs_id'])."' AND po_number ='".mysqli_real_escape_string($conn,$_POST['po_number'])."'";
//echo $queryUpdate;
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
					'".$arrData1[0]["order_id"]."',
					'remake',
					now(),
					'".$_SESSION['id']."',
					'".$_SESSION["store_code"]."'
				)";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query3)) {

		mysqli_stmt_execute($stmt);		

	};
	echo '<script>	alert("Success") </script>';
	echo '<script>	window.location.href="/dispatch"; </script>';
	
}

?>


</pre>