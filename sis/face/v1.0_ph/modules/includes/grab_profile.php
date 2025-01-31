<?php 

if(!isset($_SESSION)){
        session_start();
    }


$arrCustomerProfile = array();


 $query="SELECT 
 				pi.last_name,
				pi.first_name,
				pi.middle_name,
				pi.occupation,
				pi.sleep_time,
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
				pi.country,
				ll.lab_name,
				(SELECT store_name from stores_locations sl where sl.store_id=o.store_id) as pickup
				FROM profiles_info pi
				LEFT JOIN  store_codes sc on sc.location_code=pi.branch_applied
				LEFT JOIN orders o on o.profile_id=pi.profile_id	AND 	o.store_id='".$_SESSION["store_code"]."'
				LEFT JOIN orders_specs os on os.order_id=o.order_id
				LEFT JOIN labs_locations ll ON ll.lab_id=o.laboratory
		where pi.profile_id='".$_GET['profile_id']."' 
		
				";
//and os.status!='for exam' 

$grabParams = array( "last_name","first_name","middle_name","occupation","sleep_time","email_address","province",
"city","barangay","birthday","age","gender","phone_number","date_created","branch","profile_id","address","country","lab_name","pickup");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14,$result15,$result16,$result17,$result18,$result19,$result20);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 20; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerProfile[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; ?>

