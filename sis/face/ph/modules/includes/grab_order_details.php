<?php 

if(!isset($_SESSION)) {

    session_start();

};

$arrCustomerP = array();

$grabParams = array("prescription_id", "prescription_date", "prescription_name", "sph_od","cyl_od","axis_od","add_od","ipd_od","ph_od","va_od","sph_os","cyl_os","axis_os","add_os","ipd_os","ph_os","va_os","last_name","first_name","middle_name","email_address","province","city","barangay","birthday","age","gender","phone_number","date_created","branch","profile_id","address","doctors_remarks" );

 $query = 	"SELECT
				pp.id,
				DATE_FORMAT(pp.date_created, '%m/%d/%Y'),
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
				pi.last_name,
				pi.first_name,
				pi.middle_name,
				pi.email_address,
				pi.province,
				pi.city,
				pi.barangay,
				pi.birthday,
				pi.age,
				pi.gender,		
				pi.phone_number,
				pi.date_created,
				sc.branch,			
				pi.profile_id,
				pi.address,
				pp.doctors_remarks
			FROM 
				profiles_prescription pp
			LEFT JOIN profiles_info pi 
			ON
				pi.profile_id = pp.profile_id
			LEFT JOIN 
				store_codes sc on sc.location_code=pi.branch_applied
			LEFT JOIN
				orders_specs os on os.prescription_id=pp.id	
			WHERE 
				pi.profile_id = '".$_GET['profile_id']."'
			AND
				os.order_id = '".$_GET['orderNo']."'
			AND
				os.status != 'cancelled'
			ORDER BY
				pp.date_created ASC;";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerP[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 


$grabParams23 = array("product_upgrade","prescription_vision","price","lens_option","prescription_id", "prescription_date", "prescription_name", "sph_od","cyl_od","axis_od","add_od","ipd_od","ph_od","va_od","sph_os","cyl_os","axis_os","add_os","ipd_os","ph_os","va_os","item_description","lens_option","color","target_date","doctors_remarks","product_code","lab_print","orders_specs_id", "po_number",
				"fp_sph_od",
				"fp_cyl_od",
				"fp_axis_od",
				"fp_add_od",
				
				"fp_sph_os",
				"fp_cyl_os",
				"fp_axis_os",
				"fp_add_os",
				"op_sph_od",
				"op_cyl_od",
				"op_axis_od",
				"op_add_od",
				
				"op_sph_os",
				"op_cyl_os",
				"op_axis_os",
				"op_add_os","lens_code"

			);
$arrCustomerPrescription = array();



$query = 	"SELECT 
 				os.product_upgrade,
				os.prescription_vision,
				os.price,
				os.lens_option,
				pp.id,
				DATE_FORMAT(pp.date_created, '%m/%d/%Y'),
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
				TRIM(LEFT(LOWER(pr.item_name) , LOCATE(' ', LOWER(pr.item_name)) - 1)),
				os.lens_option,
					REPLACE(LOWER(pr.item_name),  TRIM(LEFT(LOWER(pr.item_name) , LOCATE(' ', LOWER(pr.item_name)) - 1)), ''),
				os.target_date,
				pp.doctors_remarks,
				os.product_code,
				os.lab_print,
				os.orders_specs_id,
				os.po_number,
				fp.sph_od,
				fp.cyl_od,
				fp.axis_od,
				fp.add_od,
				
				fp.sph_os,
				fp.cyl_os,
				fp.axis_os,
				fp.add_os,
				
				op.sph_od,
				op.cyl_od,
				op.axis_od,
				op.add_od,
				
				op.sph_os,
				op.cyl_os,
				op.axis_os,
				op.add_os,
				os.lens_code
				

			FROM 
				 orders_specs os 
			LEFT JOIN 
				profiles_prescription pp on pp.id=os.prescription_id AND pp.profile_id= '".$_GET['profile_id']."'
			LEFT JOIN
				profile_full_prescription fp on fp.prescription_id=pp.prescription_id
			LEFT JOIN
				profile_old_prescription op on op.prescription_id=pp.prescription_id

			LEFT JOIN
				poll_51 pr 
			ON
				pr.product_code = os.product_code  
			WHERE 
				 os.orders_specs_id = '".$_GET['orderspecsid']."'
			-- AND 
			-- 	os.status != 'cancelled'
			AND 
				os.profile_id = '".$_GET['profile_id']."'
			ORDER BY
				id 
		";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33, $result34, $result35, $result36, $result37
    	, $result38, $result39, $result40, $result41, $result42, $result43, $result44, $result45, $result46, $result47);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams23); $i++) { 

            $tempArray[$grabParams23[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerPrescription[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 



?>