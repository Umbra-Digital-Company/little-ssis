<?php 


$grabframe=array("item_description","product_code");
$arrFrames=array();
$queryFrame="SELECT item_name,product_code FROM poll_51 WHERE   
product_code LIKE '%SS1%' ORDER BY item_name";
$stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $queryFrame)) {

    mysqli_stmt_execute($stmt2);
    mysqli_stmt_bind_result($stmt2, $result1,$result2);

    while (mysqli_stmt_fetch($stmt2)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabframe); $i++) { 

            $tempArray[$grabframe[$i]] = ${'result' . ($i+1)};

        };

        $arrFrames[] = $tempArray;

    };

    mysqli_stmt_close($stmt2);    
                            
}
else {

    echo mysqli_error($conn);

}; 

$grabframe2=array("item_description","product_code");
$arrFrames2=array();
$queryFrame2="SELECT item_name,product_code FROM poll_51 WHERE   
product_code LIKE 'L0%'
 AND product_code!='L017' 
 AND product_code!='L034'
 AND product_code!='L035'
 ORDER BY product_code";
$stmt3 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt3, $queryFrame2)) {

    mysqli_stmt_execute($stmt3);
    mysqli_stmt_bind_result($stmt3, $result1,$result2);

    while (mysqli_stmt_fetch($stmt3)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabframe2); $i++) { 

            $tempArray[$grabframe2[$i]] = ${'result' . ($i+1)};

        };

        $arrFrames2[] = $tempArray;

    };

    mysqli_stmt_close($stmt3);    
                            
}
else {

    echo mysqli_error($conn);

}; 




$grabParams23 = array("product_upgrade","prescription_vision","price","lens_option","prescription_id",
 "prescription_date", "prescription_name", "sph_od","cyl_od","axis_od","add_od","ipd_od","ph_od",
 "va_od","sph_os","cyl_os","axis_os","add_os","ipd_os","ph_os","va_os","item_description","lens_option","color","target_date","doctors_remarks",
 "product_code","lab_print","orders_specs_id", "po_number",
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
				"op_add_os","lens_code",
				"item_name_studios",
				"first_name",
				"last_name",
				"lens_name",
				"order_id",
				'order_specs_id',
				"lab_remarks",
				"store_id"

			);
$arrChangeOrderDetails = array();



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
				os.lens_code,
				prs.item_name,
				pi.first_name,
				pi.last_name,
				prl.item_name,
				os.order_id,
				os.orders_specs_id,
				pp.prescription_remarks,
				o.store_id
				

			FROM 
				 orders_specs os 
			LEFT JOIN 
				profiles_prescription pp on pp.id=os.prescription_id AND pp.profile_id='".$_GET['profile_id']."'
			LEFT JOIN
				profile_full_prescription fp on fp.prescription_id=pp.prescription_id
			LEFT JOIN
				profile_old_prescription op on op.prescription_id=pp.prescription_id

			LEFT JOIN
				poll_51 pr 
			ON
				pr.product_code = os.product_code  
			LEFT JOIN 
				orders o
					ON o.order_id=os.order_id
			LEFT JOIN
				poll_51_studios prs 
			ON
				prs.product_code = os.product_code  
				LEFT JOIN
				poll_51 prl
			ON
				prl.product_code = os.lens_code  
			LEFT JOIN 
					profiles_info pi
							ON pi.profile_id=os.profile_id
			WHERE 
				 os.po_number = '".$_GET['po_number']."'
				 AND os.profile_id='".$_GET['profile_id']."'
			-- AND 
			-- 	os.status != 'cancelled'
		
			ORDER BY
				id 
		";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, 
	$result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22,
	 $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33,
	  $result34, $result35, $result36, $result37
		, $result38, $result39, $result40, $result41, $result42, $result43, $result44, $result45, $result46,
		 $result47, $result48, $result49, $result50, $result51, $result52, $result53, $result54, $result55);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams23); $i++) { 

            $tempArray[$grabParams23[$i]] = ${'result' . ($i+1)};

        };

        $arrChangeOrderDetails[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 



?>