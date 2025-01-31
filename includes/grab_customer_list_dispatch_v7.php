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






$querySearch = "";
$queryDispatch = "";

if(isset($_GET['search'])){
	if((isset($_GET['lname']) && $_GET['lname'] != '') || (isset($_GET['fname']) && $_GET['fname'] != '') || (isset($_GET['order_po']) && $_GET['order_po'] != '')){
		$querySearch .= (isset($_GET['lname']) && $_GET['lname'] != '') ? " AND p.last_name like '%".$_GET['lname']."%'" : '';
		$querySearch .= (isset($_GET['fname']) && $_GET['fname'] != '') ? " AND p.first_name like '%".$_GET['fname']."%'" : '';
		$querySearch .= (isset($_GET['order_po']) && $_GET['order_po'] != '') ? " AND os.po_number like '%".$_GET['order_po']."%'" : '';
	}else{
		$querySearch .= " AND os.date_created > DATE_ADD(NOW(), INTERVAL -3 MONTH)";
	}

}else{
	$querySearch .= " AND os.date_created > DATE_ADD(NOW(), INTERVAL -3 MONTH)";
};





// Dispatch filter
if(isset($_GET['search'])) {
   
	if((isset($_GET['lname']) && $_GET['lname'] != '') || (isset($_GET['fname']) && $_GET['fname'] != '') || (isset($_GET['order_po']) && $_GET['order_po'] != '')){
		$queryDispatch = '';

		$queryPrescription="";
	}else{
		$queryPrescription=" AND (
	                (
	                    os.lens_option='without prescription' AND date(os.date_created)>='".date("Y-m-d", strtotime("-2 days"))."'
	                ) 
	            OR (
	                os.lens_option IN ('with prescription', 'lens only') 
	                    OR os.lens_code = 'L035'
	                )
	   
	    ) 
		AND os.status!='return'";

		$queryDispatch = ' 	AND os.store_dispatch <> "y"
							AND os.received_stat <> "r"';

	}
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
) 
	AND os.status!='return' ";

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


					 AND os.status!='for exam'
					 AND os.status!='for payment'
 					AND os.status!='cancelled' 		
                     and os.dispatch_type!='packaging'			
 					AND (
						 	o.store_id = '".$_SESSION['store_code']."'
								OR
								(	
									o.origin_branch ='".$_SESSION["store_code"]."'
                  				 	  and o.store_id!='".$_SESSION["store_code"]."'
									 )
					 		)
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
 				p.first_name,
				p.last_name,
				os.product_code,
				os.prescription_id,
				IF(
					os.product_upgrade = 'special_order',
					os.product_upgrade,
					IF(
						os.lens_code IN ('L016', 'L018', 'L020', 'L021', 'L023', 'L022', 'L024' ,'L049', 'L050', 'L051', 'L052', 'L053' ,'SO1001') OR os.lens_code like'EL%',
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
				DATE_ADD(os.lab_print_date, INTERVAL 12 HOUR),
				DATE_ADD(os.lab_production_date, INTERVAL 12 HOUR),
				DATE_ADD(os.lab_status_date, INTERVAL 12 HOUR),
				DATE_ADD(os.received_stat_date, INTERVAL 12 HOUR),
				os.payment_date,
				os.id,
				os.dispatch_type,
				os.po_number,
				os.orders_specs_id,
				os.target_date,
				DATE_ADD(os.store_dispatch_date, INTERVAL 12 HOUR),
 				(
 					SELECT 
 						prescription_id 
 					FROM 
 						profiles_prescription 
 					WHERE 
 						id = os.prescription_id 
							AND profile_id=os.profile_id
							group by id
 				) AS prescription_check,
				 o.store_id,
				 ps.item_name,
				 psp.item_name,
				 courier,
				 courier_no,
				 payo.tracking_no,
				 payo.status,
				 payo.sub_courier,
				 os.packaging,
				 os.packaging_date,
				 os.packaging_for,
				 os.lens_code,
				pay.total,
				os.stock_from,
				os.concern
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
						ON ll.lab_id=o.laboratory
					LEFT JOIN 
							poll_51_studios ps 
							ON  ps.product_code=os.product_code
					LEFT JOIN 
							poll_51 psp
							ON  psp.product_code=os.product_code
					LEFT JOIN 
						payo_order_status payo
							ON payo.order_specs_id=os.orders_specs_id
					LEFT JOIN 
						 payments  pay
							ON pay.po_number=os.po_number
			WHERE 
				os.payment='y'  

				".$queryPrescription."

					 AND os.status!='for exam'
					 AND os.status!='for payment'
                     and os.dispatch_type!='packaging'
					 AND os.po_number!=''
					 AND os.orders_specs_id!=''
					AND os.status!='cancelled' 					
 					AND (
						 	o.store_id = '".$_SESSION['store_code']."'
								OR
							(		o.origin_branch ='".$_SESSION["store_code"]."'
                  				   and o.store_id!='".$_SESSION["store_code"]."'
									 )
					 		)
 					".$queryDispatch."
 					".$querySearch."
 			ORDER BY 
				os.payment_date ".$queryLimit;


				// os.order_id like '".$_SESSION["store_code"]."-%'
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
	'store_id',
	'item_description_studios',
	'item_description_specs',
	'courier',
	'courier_no',
	'tracking_no',
	'payo_status',
	'sub_courier',
	'packaging',
	'packaging_date',
	'packaging_for',
	'lens_code',
	'payment_check',
	'stock_from',
	'concern'
);

 $query2 = $querypn;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query2)) {

    mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9,
	 $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,
	 $result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32
	 ,$result33,$result34,$result35,$result36,$result37,$result38,$result39,$result40,$result41,$result42,$result43,$result44);

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