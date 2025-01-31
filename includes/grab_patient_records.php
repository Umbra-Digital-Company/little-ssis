<?php
if(!isset($_SESSION)){
        session_start();
    }

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

$arrCustomerCyware=array();

$query="SELECT 
						c.location_code,
						sl.store_name,
						c.patient_id,
						c.last_name,
						c.first_name,
						c.middle_name,
						c.address,
						c.city,
						c.mobile_number,
						c.birthday,
						c.age,
						c.gender,
						c.email_address,
						joining_date,
						expiry_date,
						od_sph,
						od_cyl,
						od_axis,
						od_add,
						od_ipd,
						od_sg_ht,
						od_va_with_rx,
						os_sph,
						os_cyl,
						os_axis,
						os_add,
						os_ipd,
						os_sg_ht,
						os_va_with_rx,
						frame,
						vision,
						style_name,
						lens,
						p.item_description,
						p.item_name
					FROM 
						cyware c
							LEFT JOIN stores_locations sl
								ON sl.store_id = c.location_code
							LEFT JOIN poll_51 p 
								on p.product_number = c.lens 
									AND item_code = c.style_name
									LIMIT 50";
$grabParams = array('location_code','store_name','patient_id','last_name','first_name','middle_name','address','city','mobile_number','birthday','age','gender','email_address','joining_date','expiry_date','od_sph','od_cyl','od_axis','od_add','od_ipd','od_sg_ht','od_va_with_rx','os_sph','os_cyl','os_axis','os_add','os_ipd','os_sg_ht','os_va_with_rx','frame','vision','style_name','lens','item_description','item_name');

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32,$result33,$result34, $result35);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerCyware[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 







?>