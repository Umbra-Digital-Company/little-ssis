<?php

if(!isset($_SESSION)){
	session_start();
}

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

//Required files
//require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";

// =================================================== PENDING

$arrCustomerPending = array();
$querypn = " SELECT
				p.first_name,
				p.middle_name,
				p.last_name,
				p.suffix_name,
				p.gender,
				os.product_code,
				os.prescription_id,
				os.order_id,
				LOWER(u.first_name) as uf,
				u.middle_name as um,
				LOWER(u.last_name) as ul,
				
				p.profile_id,
				os.status,
				
				os.lens_option,
				lab_name,
				os.lab_print,
				os.lab_production,
				os.lab_status,
				os.received_stat,
				os.store_dispatch,
				
				os.payment,
				
				os.id,
				os.status as osstatus,
				p.priority,
				os.lab_remarks,
				os.po_number,
				os.dispatch_type,
				os.orders_specs_id,
				os.product_upgrade,
				os.target_date,
				os.store_dispatch_date,
				o.doctor,
				LOWER(d.first_name) as ofirst,
				LOWER(d.last_name) as olast
			FROM 
				profiles_info p
			INNER JOIN 
				orders_specs os ON os.profile_id = p.profile_id
			LEFT JOIN 
				emp_table u ON u.emp_id = p.sales_person
	
			LEFT JOIN 
				orders o ON o.order_id = os.order_id 
			LEFT JOIN 
				emp_table d ON d.emp_id = o.doctor
			LEFT JOIN 
				labs_locations ll ON ll.lab_id=o.laboratory
			WHERE 
				o.store_id != ''
				 AND os.status = 'for exam' 
				AND ( o.doctor is NULL OR o.doctor='' )
 				
				and ( o.origin_branch like '".$_SESSION["store_code"]."%'   or o.store_id like '".$_SESSION["store_code"]."%')
					and date(os.date_created)  >='2019-07-01'
					and p.branch_applied!=''

			GROUP by
				os.order_id
			ORDER by
				o.date_created
";

$grabParams = array(
	'first_name',
	'middle_name',
	'last_name',
	'suffix_name',
	'gender',
	'product_code',
	'prescription_id',
	'order_id',
	'uf',
	'um',
	'ul',
	
	'profile_id',
	'status',
	
	'lens_option',
	'lab_name',
	'lab_print',
	'lab_production',
	'lab_status',
	'received_stat',
	'store_dispatch',
	
	'payment',

	'id',
	'osstatus',
	'priority',
	'lab_remarks',
	'po_number',
	'dispatch_type',
	'orders_specs_id',
	'product_upgrade',
	'target_date',
	'store_dispatch_date',
	'doctor',
	'ofirst',
	'olast'
);

$query = $querypn;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, 
	$result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,
	$result28,$result29,$result30,$result31,$result32,$result33,$result34);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i <34; $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrCustomerPending[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);

};

// =================================================== PROGRESS

$arrCustomerProgress = array();
 $querypn = " SELECT
				p.first_name,
				p.middle_name,
				p.last_name,
				p.suffix_name,
				p.gender,
				os.product_code,
				os.prescription_id,
				os.order_id,
				LOWER(u.first_name) as uf,
				u.middle_name as um,
				LOWER(u.last_name) as ul,
				
				p.profile_id,
				os.status,
			
				os.lens_option,
				lab_name,
				
			
				os.payment,
				os.lab_print_date,
				os.lab_production_date,
				os.lab_status_date,
				os.received_stat_date,
				os.payment_date,
				os.id,
				os.status as osstatus,
				p.priority,
				os.lab_remarks,
				os.po_number,
				os.dispatch_type,
				os.orders_specs_id,
				os.product_upgrade,
				os.target_date,
				os.store_dispatch_date,
				o.doctor,
				LOWER(d.first_name) as ofirst,
				LOWER(d.last_name) as olast
			FROM 
				profiles_info p
			INNER JOIN 
				orders_specs os ON os.profile_id = p.profile_id
			LEFT JOIN 
				emp_table u ON u.emp_id = p.sales_person
			
			LEFT JOIN 
				orders o ON o.order_id = os.order_id 
			LEFT JOIN 
				emp_table d ON d.emp_id = o.doctor
			LEFT JOIN 
				labs_locations ll ON ll.lab_id=o.laboratory
			WHERE 
				o.store_id != '' AND os.status = 'for exam' 
				AND o.doctor is not NULL
				AND o.doctor!=''
				and ( o.origin_branch like '".$_SESSION["store_code"]."%'   or o.store_id like '".$_SESSION["store_code"]."%')
				and date(os.date_created)  >='2019-07-01'
				and p.branch_applied!=''



				
			GROUP by
				os.order_id
			ORDER by
				o.date_created
";

$grabParams = array(
	'first_name',
	'middle_name',
	'last_name',
	'suffix_name',
	'gender',
	'product_code',
	'prescription_id',
	'order_id',
	'uf',
	'um',
	'ul',
	
	'profile_id',
	'status',
	
	'lens_option',
	'lab_name',
	'lab_print',
	'lab_production',
	'lab_status',
	'received_stat',
	'store_dispatch',
	
	'payment',
	
	'id',
	'osstatus',
	'priority',
	'lab_remarks',
	'po_number',
	'dispatch_type',
	'orders_specs_id',
	'product_upgrade',
	'target_date',
	'store_dispatch_date',
	'doctor',
	'ofirst',
	'olast'
);

$query = $querypn;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11,
	 $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,
	 $result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < 34; $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrCustomerProgress[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);

};

?>