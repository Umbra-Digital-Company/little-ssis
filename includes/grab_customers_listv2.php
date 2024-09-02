<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB DATA AND SET LIMITS

// Set the date
$datenow = date("Y-m-d h:i:s");

// Set lab code 
if($_SESSION['store_code'] == '6981YR1OBFVPK2V40HY8') {

	$queryGrab = 'AND (o.laboratory = "'.$_SESSION['store_code'].'" 
					OR  ( sl.lab_id = "'.$_SESSION['store_code'].'" and o.laboratory!="0075YR1OBABCD2V40335" 	 ))
					';
					// and o.laboratory!="YUUH848812ABDF82CC12"
}
elseif($_SESSION['store_code'] != 'YUUH848812ABDF82CC12') {

	$queryGrab = 'AND (o.laboratory = "'.$_SESSION['store_code'].'" 
					OR sl.lab_id = "'.$_SESSION['store_code'].'")
					and o.laboratory!="YUUH848812ABDF82CC12"';

}
else {

	$queryGrab = 'AND (o.laboratory = "'.$_SESSION['store_code'].'"  )';	

};

// Set store code
if(isset($_GET['store']) && $_GET['store'] != NULL && $_GET['store'] != 'all') {

	if($_GET['store']=='999' || $_GET['store']=='102'){

		$queryStore = " AND o.store_id = ".$_GET['store'];					
	//	$queryStore .= "  AND o.laboratory = '".$_SESSION['store_code']."' ";	
	
		if($_SESSION['store_code'] != '0075YR1OBABCD2V40335') {

			$queryStore .=  '  AND (o.laboratory = "'.$_SESSION['store_code'].'" 
							OR sl.lab_id = "'.$_SESSION['store_code'].'")
							and o.laboratory!="0075YR1OBABCD2V40335"  ';
		
		}
		else {
		
			$queryStore .=  '  AND o.laboratory = "'.$_SESSION['store_code'].'" ';	
		
		};

		$queryGrab = "";

	}
	// elseif( $_GET['store']=='122'){

	// 	$queryStore = " AND o.store_id = ".$_GET['store'];					
	// //	$queryStore .= "  AND o.laboratory = '".$_SESSION['store_code']."' ";	
	// 	if($_SESSION['store_code'] != 'YUUH848812ABDF82CC12') {

	// 		$queryStore .=  '  AND (o.laboratory = "'.$_SESSION['store_code'].'" 
	// 						OR sl.lab_id = "'.$_SESSION['store_code'].'")
	// 						and o.laboratory!="YUUH848812ABDF82CC12"  ';
		
	// 	}
	// 	else {
		
	// 		$queryStore .=  '  AND o.laboratory = "'.$_SESSION['store_code'].'" ';	
		
	// 	};

	// 	$queryGrab = "";

	// }
	else{

		$queryStore = " AND o.store_id = ".$_GET['store'];	

	};

}
else {

	
	$queryStore .=  ' ';	

};

// Set search term
$arrSearch = explode(" ", $_GET['search']);

$querySearch = "";

if(isset($_GET['search']) && $_GET['search'] != '') {

	for ($i=0; $i < sizeOf($arrSearch); $i++) { 
	
		$querySearch .= " AND (
							p.last_name like '%".$arrSearch[$i]."%' 
								OR p.first_name like '%".$arrSearch[$i]."%' 
								OR p.middle_name like  '%".$arrSearch[$i]."%'
	 							OR os.po_number like '%".$arrSearch[$i]."%' 
	 					)";

	};	

}
else {

	$querySearch .= " AND os.date_created > DATE_ADD(NOW(), INTERVAL -3 MONTH)";

};

if(isset($_GET['search']) && $_GET['search'] != '') {

	$qDispatch = "";

}
else {

	$qDispatch = 	"AND (
						os.store_dispatch_date IS NULL
						OR os.store_dispatch_date = ''
						OR os.store_dispatch_date = '0000-00-00 00:00:00'
						OR os.store_dispatch_date = '1910-01-01 00:00:00'
						OR DATE_ADD(os.store_dispatch_date, INTERVAL 12 HOUR) > DATE_ADD(NOW(), INTERVAL -1 HOUR) 
					)
					AND os.status!='return'";

};

//////////////////////////////////////////////////////////////////////////////////// GRAB ORDER NUMBERS

$totalNumberOfOrders = 0;

$query = 	"SELECT
				COUNT(o.order_id)
			FROM
				profiles_info p
					LEFT JOIN orders_specs os 
						ON os.profile_id=p.profile_id
					LEFT JOIN orders o
						ON o.order_id = os.order_id
					LEFT JOIN  store_codes sc 
						ON sc.location_code=o.store_id
					LEFT JOIN stores_locations sl
						ON sl.store_id = sc.location_code
					LEFT JOIN labs_locations ll 
						ON ll.lab_id=sl.lab_id
			WHERE
				os.payment='y'  
 					AND os.product_upgrade!='fashion_lens'
 					AND (
 						os.lens_option IN ('with prescription', 'lens only') 
 							OR os.lens_code = 'L035'
 					)
 					AND os.status!='for exam'
 					AND os.status!='cancelled'
 					AND os.product_upgrade!='special_order'
 					AND os.product_upgrade NOT LIKE '%adaptar%'
 					AND os.product_upgrade NOT LIKE '%essilor%'
 					AND os.product_upgrade NOT LIKE '%varilux%'
 					AND os.product_upgrade NOT LIKE '%comfort%'	
 					".$qDispatch."
					".$queryGrab."
 					".$queryStore."
 					".$querySearch;			

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, 	$query)) {

	mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);
    mysqli_stmt_fetch($stmt);
	mysqli_stmt_store_result($stmt);

	$totalNumberOfOrders = $result1;

	mysqli_stmt_close($stmt);

}
else {

	echo mysqli_error($conn);
	exit;

};

// Calculate pages
$numberPages = ceil($totalNumberOfOrders / 100);

// Set Limit
if(isset($_GET['page'])) {

	$page = $_GET['page'];
	$queryLimit = " LIMIT ".( ($page - 1) * 100 ).", 100;";

}
else {

	$page = 1;
	$queryLimit = " LIMIT 100;";

};

//////////////////////////////////////////////////////////////////////////////////// GRAB ORDER DETAILS

// Set array
$arrCustomer = array();

 $querypn = 	"SELECT 
 				p.first_name,
				p.last_name,
				os.product_code,
				os.prescription_id,
				os.order_id,
				p.profile_id,
				os.status,
				os.lens_option,
				sc.branch,
				ll.lab_name,
				os.lab_print,
				os.lab_production,
				os.lab_status,
				os.received_stat,
				os.store_dispatch,
				
				os.payment,
				DATE_ADD(os.lab_print_date,INTERVAL 12 HOUR),
				DATE_ADD(os.lab_production_date,INTERVAL 12 HOUR),
				DATE_ADD(os.lab_status_date,INTERVAL 12 HOUR),
				DATE_ADD(os.received_stat_date,INTERVAL 12 HOUR),
				os.payment_date,
				os.id,
				os.dispatch_type,
				os.po_number,
				os.orders_specs_id,
				os.target_date,
				DATE_ADD(os.store_dispatch_date,INTERVAL 12 HOUR),
 				(
 					SELECT 
 						prescription_id 
 					FROM 
 						profiles_prescription 
 					WHERE 
 						id = os.prescription_id 
						 AND profile_id=os.profile_id
 				) AS prescription_check
			FROM 
				profiles_info p
					LEFT JOIN orders_specs os 
						ON os.profile_id=p.profile_id
					LEFT JOIN users u 
						ON u.id=p.sales_person
					LEFT  JOIN orders o 
						ON o.order_id=os.order_id 
					LEFT JOIN  store_codes sc 
						ON sc.location_code=o.store_id
					LEFT JOIN stores_locations sl
						ON sl.store_id = sc.location_code
					LEFT JOIN labs_locations ll 
						ON ll.lab_id=sl.lab_id
			WHERE 
				os.payment='y'  
 					AND os.product_upgrade!='fashion_lens'
 					AND (
 						os.lens_option IN ('with prescription', 'lens only') 
 							OR os.lens_code = 'L035'
 					)
 					AND os.status!='for exam'
 					AND os.status!='cancelled'
					 AND os.po_number!=''
					 AND os.orders_specs_id!=''
 					AND os.product_upgrade!='special_order' 
 					AND os.product_upgrade NOT LIKE '%adaptar%'
 					AND os.product_upgrade NOT LIKE '%essilor%'
 					AND os.product_upgrade NOT LIKE '%varilux%'
 					AND os.product_upgrade NOT LIKE '%comfort%'	
 					".$qDispatch."
 					".$queryGrab."
 					".$queryStore."
 					".$querySearch;

// Check status filter
if(isset($_GET['status'])){

	if($_GET['status']=='pending'){

		$querypn .= " and os.lab_status='n' ";

	}
	elseif($_GET['status']=='completed'){
		
		$querypn .= " and os.lab_status='y' ";

	}
	elseif($_GET['status']=='all'){
		
		$querypn .= " ";

	};
	
};

if(isset($_GET['labfilter']) ){

	$querypn .=" and sl.laboratory='".$_GET['labfilter']."' ";
	
};

if(isset($_GET['branchfilter']) ){
	
	$querypn .=" and o.store_id='".$_GET['branchfilter']."' ";
	
};

// Add Order By
$querypn .= " ORDER BY 
				o.date_created ";

// Add limit
$querypn .= $queryLimit;

$grabParams = array(

    'first_name',
    'last_name',
    'product_code',
    'prescription_id',   
    'order_id',   
	'profile_id',
	'status',
	'lens_option',
	'branch',
	'lab_name',
	'lab_print',
	'lab_production',
	'lab_status',
	'received_stat',
	'store_dispatch',
	
	'payment',
	'lab_print_date',
	'lab_production_date',
	'lab_status_date',
	'received_stat_date',
	'payment_date',
	'id',
	'dispatch_type',
	'po_number',
	'orders_specs_id',
	'target_date',
	'store_dispatch_date',
	'prescription_check'

);

$query = $querypn;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomer[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>