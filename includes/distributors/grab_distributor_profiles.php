<?php 

//////////////////////////////////////////////////////////////////////////////////// GRAB DISTRIBUTORS

$arrProfiles = array();

$query  =   "SELECT 
                du.distributor_id,
                du.first_name,
                du.last_name,
                du.email_address,
                du.company,
                du.phone_number,
                du.percentage,
                du.country_code
            FROM 
                distributors_users du
            ORDER BY
                du.first_name";

$grabParams = array(
    'distributor_id',
    'first_name',
    'last_name',
    'email_address',
    'company',
    'phone_number',
    'percentage',
    'country_code'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrProfiles[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>
