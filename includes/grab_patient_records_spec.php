<?php 

//////////////////////////////////////////////////////////////////////////////////// GRAB CUSTOMER DETAILS

// Set array
$arrCustomer = array();

$grabParams = array(	
	"profile_id",
	"first_name",
	"middle_name",
	"last_name",
	"email_address",
	"phone_number",
	"gender",
	"birthday",
	"country",
	"province",
	"city",
	"barangay",
	"age",
	"branch_applied_code",
	"branch_applied_name",
	"joining_date",
	"sales_person",
	"address",
	"priority"
);

$query  = 	"SELECT					
				pi.profile_id,
				pi.first_name,
				pi.middle_name,
				pi.last_name,
				pi.email_address,
				pi.phone_number,
				pi.gender,
				pi.birthday,
				pi.country,
				pi.province,
				pi.city,
				pi.barangay,
				pi.age,
				pi.branch_applied,
				sl.store_name,
				DATE_FORMAT(pi.joining_date, '%b %d, %Y'),
				pi.sales_person,
				pi.address,
				pi.priority
			FROM 
				profiles p
					LEFT JOIN profiles_info pi
						ON pi.profile_id = p.profile_id
					LEFT JOIN stores_locations sl
						ON sl.store_id = pi.branch_applied
			WHERE
				p.profile_id = '".$_GET['profile_id']."'";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19);

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

//////////////////////////////////////////////////////////////////////////////////// GRAB CUSTOMER PRESCRIPTIONS

$arrPrescriptions = array();

$grabParams2 = array(
	"prescription_id",
	"prescription_date",
	"prescription_name", 
	"sph_od",
	"cyl_od",
	"axis_od",
	"add_od",
	"ipd_od",
	"ph_od",
	"va_od",
	"sph_os",
	"cyl_os",
	"axis_os",
	"add_os",
	"ipd_os",
	"ph_os",
	"va_os",
	"doctors_remarks"
);

$query = 	"SELECT 
				pp.id,
				DATE_FORMAT(pp.date_created, '%m/%d/%Y %h:%is'),
				pp.prescription_name,
				pp.sph_od,
				pp.cyl_od,
				pp.axis_od,
				pp.add_od,
				pp.ipd_od,
				pp.ph_od,
				pp.va_od,
				pp.sph_os,
				pp.cyl_os,
				pp.axis_os,
				pp.add_os,
				pp.ipd_os,
				pp.ph_os,
				pp.va_os,
				pp.doctors_remarks
			FROM 
				profiles_prescription pp
					LEFT JOIN orders_specs os
						ON os.prescription_id = pp.prescription_id
			WHERE 
				pp.profile_id = '".$_GET['profile_id']."'
			ORDER BY
				pp.date_created DESC";

$stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $query)) {

    mysqli_stmt_execute($stmt2);
    mysqli_stmt_bind_result($stmt2, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18);

    while (mysqli_stmt_fetch($stmt2)) {

        $tempArray2 = array();

        for ($i=0; $i < sizeOf($grabParams2); $i++) { 

            $tempArray2[$grabParams2[$i]] = ${'result' . ($i+1)};

        };

        $arrPrescriptions[] = $tempArray2;

    };

    mysqli_stmt_close($stmt2);    
                            
}
else {

    echo mysqli_error($conn);

}; 

//////////////////////////////////////////////////////////////////////////////////// GRAB CUSTOMER ORDERS

$arrOrdersInit = array();
$arrOrders = array();

$grabParams2 = array(
	"date_created",
	"order_id",
	"orders_specs_id",
	"profile_id",
	"product_code",
	"style",
	"color",
	"prescription_id",
	"product_upgrade",
	"tints",
	"lens_code",
	"prescription_vision",
	"price",
	"currency",
	"lens_option",
	"status",
	"status_date",
	"signature",
	"lab_print",
	"lab_print_date",
	"lab_status",
	"lab_status_date",
	"lab_production_date",
	"lab_production",
	"received_stat",
	"received_stat_date",
	"payment",
	"payment_date",
	"store_dispatch",
	"store_dispatch_date",
	"remarks",
	"po_number",
	"si_number",
	"dispatch_type",
	"lab_remarks",
	"target_date",
	"prescription_id",
	"prescription_date",
	"prescription_name", 
	"sph_od",
	"cyl_od",
	"axis_od",
	"add_od",
	"ipd_od",
	"ph_od",
	"va_od",
	"sph_os",
	"cyl_os",
	"axis_os",
	"add_os",
	"ipd_os",
	"ph_os",
	"va_os",
	"doctors_remarks"
);

$query = 	"SELECT 
				os.date_created,
				os.order_id,
				os.orders_specs_id,
				os.profile_id,
				os.product_code,
				LOWER(TRIM(LEFT(pr.item_name , LOCATE(' ', pr.item_name) - 1))),
				LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(' ', pr.item_name) - 1)), '')),
				os.prescription_id,
				os.product_upgrade,
				os.tints,
				os.lens_code,
				os.prescription_vision,
				os.price,
				os.currency,
				os.lens_option,
				os.status,
				os.status_date,
				os.signature,
				os.lab_print,
				os.lab_print_date,
				os.lab_status,
				os.lab_status_date,
				os.lab_production_date,
				os.lab_production,
				os.received_stat,
				os.received_stat_date,
				os.payment,
				os.payment_date,
				os.store_dispatch,
				os.store_dispatch_date,
				os.remarks,
				os.po_number,
				os.si_number,
				os.dispatch_type,
				os.lab_remarks,
				os.target_date,
				pp.id,
				DATE_FORMAT(pp.date_created, '%m/%d/%Y %h:%is'),
				pp.prescription_name,
				pp.sph_od,
				pp.cyl_od,
				pp.axis_od,
				pp.add_od,
				pp.ipd_od,
				pp.ph_od,
				pp.va_od,
				pp.sph_os,
				pp.cyl_os,
				pp.axis_os,
				pp.add_os,
				pp.ipd_os,
				pp.ph_os,
				pp.va_os,
				pp.doctors_remarks
			FROM 
				orders_specs os
					LEFT JOIN poll_51 pr 
						ON pr.product_code = os.product_code  
					LEFT JOIN profiles_prescription pp
						ON pp.id = os.prescription_id
							AND pp.profile_id = os.profile_id
			WHERE 
				os.profile_id = '".$_GET['profile_id']."'
			ORDER BY
				os.date_created DESC";

$stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $query)) {

    mysqli_stmt_execute($stmt2);
    mysqli_stmt_bind_result($stmt2, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33, $result34, $result35, $result36, $result37, $result38, $result39, $result40, $result41, $result42, $result43, $result44, $result45, $result46, $result47, $result48, $result49, $result50, $result51, $result52, $result53, $result54);

    while (mysqli_stmt_fetch($stmt2)) {

        $tempArray3 = array();

        for ($i=0; $i < sizeOf($grabParams2); $i++) { 

            $tempArray3[$grabParams2[$i]] = ${'result' . ($i+1)};

        };

        $arrOrdersInit[] = $tempArray3;

    };

    mysqli_stmt_close($stmt2);    
                            
}
else {

    echo mysqli_error($conn);

}; 

// Sort the orders
for ($i=0; $i < sizeOf($arrOrdersInit); $i++) { 

	// Set current Order ID
	$curOrderID = $arrOrdersInit[$i]['order_id'];

	// Sort together PO Numbers into respective orders
	$arrOrders[$curOrderID][$i] = $arrOrdersInit[$i];
	
};

// Reindex main array
$arrOrders = array_values($arrOrders);

for ($i=0; $i < sizeOf($arrOrders); $i++) { 

	$arrOrders[$i] = array_values($arrOrders[$i]);
	
};

?>