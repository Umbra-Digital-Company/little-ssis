<?php 

// Set array
$arrCustomer = [];
$date = (!isset($_GET['date'])) ? date('Y-m-d',strtotime(date('Y-m-d H:i:s').'+12 hours')) : $_GET['date'];
$querypn = 	"SELECT 
 				p.first_name,
				p.last_name,
				os.product_code,
				os.prescription_id,
				IF(
					os.product_upgrade = 'special_order',
					os.product_upgrade,
					IF(
						os.lens_code IN ('L016', 'L018', 'L020', 'L021', 'L023', 'L022', 'L024' ,'L049', 'L050', 'L051', 'L052', 'L053' ,'SO1001'),
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
				 os.lens_code
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
			WHERE 
				os.payment='y'  
				AND os.packaging_for != ''
				AND DATE(ADDTIME(os.packaging_date, '12:00')) = '".$date."'
					 AND os.status!='for exam'
					 AND os.status!='for payment'
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
 			ORDER BY 
				os.date_created DESC";


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
	'lens_code'
);

 $query2 = $querypn;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query2)) {

    mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9,
	 $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,
	 $result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32
	 ,$result33,$result34,$result35,$result36,$result37,$result38,$result39,$result40,$result41);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };
        $tempArray['packaging_date'] = date('Y-m-d H:i:s', strtotime($tempArray['packaging_date'].'+12 hours'));
        $arrCustomer[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

//print_r( $arrCustomer);
?>