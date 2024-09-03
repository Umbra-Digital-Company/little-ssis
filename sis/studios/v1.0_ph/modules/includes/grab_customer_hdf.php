<?php 

if(!isset($_SESSION)){
        session_start();
    }

$profile_id="";
if(isset($_GET['profile_id'])){
	$profile_id=$_GET['profile_id'];
}
else{
	$profile_id=$_SESSION["customer_id"];
}

$arrCustomer = array();

$query="SELECT id,first_name,last_name,middle_name,email_address,phone_number,gender,birthday,email_updates,address,province,city,barangay,age,branch_applied  FROM profiles_info c 
		where profile_id='".$profile_id."' ORDER By id DESC;";


$grabParams = array( "id","first_name","last_name","middle_name","email_address","phone_number","gender","birthday","email_updates","address","province","city","barangay","age","branch_applied" );

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 15; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomer[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

$arrCustomerHdf = array();

$query="SELECT travel_countries, travel_ph, sick, symptoms, close_contact, close_contact_animals
        FROM profiles_info pi
        LEFT JOIN lil_health_declaration  lhd ON lhd.profile_id = pi.profile_id
		where pi.profile_id='".$profile_id."' ORDER By lhd.id DESC LIMIT 1;";


$grabParams = array( "travel_countries","travel_ph","sick","symptoms","close_contact","close_contact_animals");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < count($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerHdf[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};
?>


