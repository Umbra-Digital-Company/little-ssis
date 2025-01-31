<?php 

if(!isset($_SESSION)){
        session_start();
    }


$arrCustomer = array();

 $query="SELECT profile_id,first_name,last_name,middle_name,email_address,phone_number FROM profiles_info p
		where profile_id='".$_SESSION["customer_id"]."' ORDER By id DESC;";


$grabParams = array( "profile_id","first_name", "last_name", "middle_name", "email_address", "phone_num");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 6; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomer[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; ?>

