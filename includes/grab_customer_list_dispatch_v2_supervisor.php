<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB DATA AND SET LIMITS

if(isset($_GET['search']) && $_GET['search'] != '') {

	$arrSearch = explode(" ", $_GET['search']);

}
else {

	$arrSearch = array();

};

/// without prescription
$queryPrescription = "";


$arrFilterStores = explode(',', $_SESSION['user_login']['store_location']);

if(isset($_GET['store']) && $_GET['store']!='' && $_GET['store']!="all"){

	$specStore = " AND sl.store_id='".$_GET['store']."' ";

}else{

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = " AND sl.store_id IN (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
			$specStore .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {

				$specStore .= ",";

			}

		};

		$specStore .= " ) ";		

	}
	else {

		$specStore = "";

	};

}

$querySearch = "";

if(!empty($arrSearch)) {

	for ($i=0; $i < sizeOf($arrSearch); $i++) { 
	
		$querySearch .= " AND (
							p.last_name like '%".$arrSearch[$i]."%' 
								OR p.first_name like '%".$arrSearch[$i]."%' 
								OR p.middle_name like  '%".$arrSearch[$i]."%'
	 							OR os.po_number like '%".$arrSearch[$i]."%' 
	 					)";

	};	

};





// Dispatch filter
if(isset($_GET['search']) && $_GET['search'] != '') {

	$queryDispatch = '';

	$queryPrescription="";

}
else {
	$queryPrescription=" AND (
		(
			os.lens_option='without prescription' AND date(os.date_created)>='".date("Y-m-d", strtotime("-2 days"))."'
		) 
	OR (
		os.lens_option IN ('with prescription', 'lens only') 
			OR os.lens_code = 'L035'
		)
)  ";

	$queryDispatch = ' 	AND os.store_dispatch <> "y"
						AND os.received_stat <> "r"';

};



//////////////////////////////////////////////////////////////////////////////////// GRAB ORDER NUMBERS

$totalNumberOfOrders = 0;

$query = 	"SELECT 
				COUNT(p.first_name)
			FROM 
				profiles_info p
					LEFT JOIN orders_specs os 
						ON os.profile_id=p.profile_id					
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

				".$queryPrescription."

".$specStore."

 					AND os.status!='for exam'
 					AND os.status!='cancelled' 					
 					
 					".$queryDispatch."
 					".$querySearch."
 			ORDER BY 
				o.date_created";		

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
	$queryLimit = " LIMIT 0, 100;";

};

//////////////////////////////////////////////////////////////////////////////////// GRAB ORDER DETAILS

$datenow = date("Y-m-d h:i:s");
$arrCustomer = array();

$querypn = 	"SELECT 
 				LOWER(p.first_name),
				LOWER(p.last_name),
				os.product_code,
				os.prescription_id,
				IF(
					os.product_upgrade = 'special_order',
					os.product_upgrade,
					IF(
						os.lens_code IN ('L016', 'L018', 'L020', 'L021', 'L023', 'L022', 'L024'),
						'special_order',
						os.product_upgrade
					)
				),
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
				os.lab_print_date,
				os.lab_production_date,
				os.lab_status_date,
				os.received_stat_date,
				os.payment_date,
				os.id,
				os.dispatch_type,
				os.po_number,
				os.orders_specs_id,
				os.target_date,
				os.store_dispatch_date,
 				(
 					SELECT 
 						prescription_id 
 					FROM 
 						profiles_prescription 
 					WHERE 
 						id = os.prescription_id 
							AND profile_id=os.profile_id
 				) AS prescription_check,
				 o.store_id
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

				".$queryPrescription."
".$specStore."

 					AND os.status!='for exam'
 					AND os.status!='cancelled' 					
 					
 					".$queryDispatch."
 					".$querySearch."
 			ORDER BY 
				o.date_created ".$queryLimit;


				// AND (
				// 	o.store_id = '".$_SESSION['store_code']."'
				// 	   OR
				//    (		os.order_id like '".$_SESSION["store_code"]."-%'
				// 			and o.store_id!='".$_SESSION["store_code"]."'
				// 			)
				// 	)

$grabParams = array(
    'first_name',
    'last_name',
    'product_code',
    'prescription_id',
    'product_upgrade',   
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
	'prescription_check',
	'store_id'
);

 $query2 = $querypn;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query2)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30);

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