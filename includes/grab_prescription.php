<?php 



 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

$arrCustomerDetail = array();
$queryDispatchDetail="SELECT  
						p.first_name,
						p.middle_name,
						p.last_name,
						os.product_code,
						os.order_id,
						
						pr.color,
						p.profile_id,
						os.signature,
						os.status,
						p.address,
						p.email_address,
						p.province,
						p.city,
						p.barangay,
						p.birthday,
						p.age,
						p.gender,		
						p.phone_number,
						p.date_created,
						sc.branch,
						os.prescription_id,
						os.product_upgrade,
						os.prescription_vision,
						pr.item_description,
						os.price,
						pp.prescription_name,
						o.date_created as order_date,
						(SELECT id From users where id=o.doctor) as doctor,
						lab_name,
						os.po_number,
						os.tints,
						pol.item_description as pol_item,
						os.remarks,
						os.target_date,
						(select store_name FROM stores_locations where store_id=p.branch_applied) as branch_applied,
						pol.item_description,
						(select  item_name
						 FROM 
						 		poll_51 pti 
								where pti.product_code=os.product_code
								) as  item_name_poll,
						pp.prescription_remarks

						FROM profiles_info p
						INNER JOIN orders_specs os on os.profile_id=p.profile_id
						LEFT JOIN users u on u.id=p.sales_person
						LEFT JOIN products pr on pr.product_code=os.product_code
						LEFT  JOIN orders o on o.order_id=os.order_id
						LEFT  JOIN store_codes sc on sc.location_code=o.store_id
						LEFT JOIN profiles_prescription pp on pp.id=os.prescription_id
						LEFT JOIN labs_locations ll on  ll.lab_id=o.laboratory
						LEFT JOIN poll_51 pol on pol.product_code=os.lens_code
						
						
						where p.profile_id='".$_GET['profile_id']."'
						 ;";


				$grabParams = array(
					'first_name',
					'middle_name',
					'last_name',
					'product_code',
					'order_id',
					'color',
					'profile_id',
					'signature',
					'status',
					'address',
					'email_address',
					'province',
					'city',
					'barangay',
					'birthday',
					'age',
					'gender',
					'phone_number',
					'date_created',
					'branch',
					'prescription_id',
					'product_upgrade',
					'prescription_vision',
					'item_description',
					'price',
					'prescription_name',
					'order_date',
					'doctor',
					'lab_name',
					'po_number',
					'tints',
					'pol_item',
					'remarks',
					'target_date',
					'branch_applied',
					'item_description_poll',
					'item_name_poll',
					'prescription_remarks'
					
					
				);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryDispatchDetail)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31 , $result32
						   , $result33, $result34, $result35, $result36, $result37, $result38);
    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 38; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerDetail[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 
	
	//////////////////////////				


$grabParams2 = array("product_upgrade","prescription_vision","price","lens_option","prescription_id", "prescription_date", "prescription_name", "sph_od","cyl_od","axis_od","add_od","ipd_od","ph_od","va_od","sph_os","cyl_os","axis_os","add_os","ipd_os","ph_os","va_os","item_description","lens_option","color");
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
				pr.item_description,
				os.lens_option,
				pr.color
			
			FROM profiles_prescription pp
				
			LEFT JOIN 
				 orders_specs os on os.prescription_id=pp.id
		LEFT JOIN
				products pr on pr.product_code=os.product_code  
			WHERE 
				pp.profile_id= '".$_GET['profile_id']."'
			";

$stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $query)) {

    mysqli_stmt_execute($stmt2);
    mysqli_stmt_bind_result($stmt2, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24);

    while (mysqli_stmt_fetch($stmt2)) {

        $tempArray2 = array();

        for ($i=0; $i < 24; $i++) { 

            $tempArray2[$grabParams2[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerPrescription[] = $tempArray2;

    };

    mysqli_stmt_close($stmt2);    
                            
}
else {

    echo mysqli_error($conn);

}; 


?>