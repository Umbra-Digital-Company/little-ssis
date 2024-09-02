<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

$datenow = date("Y-m-d h:i:s");
$arrCustomer = array();

$querypn = 	"SELECT 
 				LOWER(p.first_name),
				LOWER(p.last_name),
				os.product_code,
				os.prescription_id,
				os.product_upgrade,
				os.order_id,
				pr.color,
				p.profile_id,
				os.status,
				pr.item_description,
				os.lens_option,
				sc.branch,
				ll.lab_name,
				os.lab_print,
				os.lab_production,
				os.lab_status,
				os.received_stat,
				os.store_dispatch,
				os.signature,
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
						 and profile_id=os.profile_id
 				) AS prescription_check
			FROM 
				profiles_info p
					LEFT JOIN orders_specs os 
						ON os.profile_id=p.profile_id
					LEFT JOIN users u 
						ON u.id=p.sales_person
					LEFT JOIN products pr 
						ON pr.product_code=os.product_code  
					LEFT  JOIN orders o 
						ON o.order_id=os.order_id 
					LEFT JOIN  store_codes sc 
						ON sc.location_code=o.store_id
					LEFT JOIN labs_locations ll 
						ON ll.lab_id=o.laboratory
			WHERE 
				os.payment='y'  
 					AND os.product_upgrade!='fashion_lens'
 					AND os.lens_option != 'without prescription'
 					AND os.status!='for exam'
 					AND os.status!='cancelled' 					
 					AND o.store_id = '".$_SESSION['store_code']."' 
 			ORDER BY 
				o.date_created ";

$grabParams = array(

    'first_name',
    'last_name',
    'product_code',
    'prescription_id',
    'product_upgrade',   
    'order_id',   
	'color',
	'profile_id',
	'status',
	'item_description',
	'lens_option',
	'branch',
	'lab_name',
	'lab_print',
	'lab_production',
	'lab_status',
	'received_stat',
	'store_dispatch',
	'signature',
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
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31, $result32);

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