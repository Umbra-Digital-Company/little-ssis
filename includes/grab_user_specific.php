<?php 

// Check user level | MUST BE SUPER USER
if($_SESSION['user_login']['userlvl'] != 1) {

    return false;
    exit;

};


// Set users array
$arrUser = array();

$query  =   'SELECT 
                u.id,
                u.username,
                u.first_name,
                u.middle_name,
                u.last_name,
                u.isadmin,
                u.position,
                u.store_code,
                u.init_pass,
                u.online,
                u.date_log,
                u.store_location,
                u.user_type,
                u.store_type,
                u.access_brands,
                u.department,
                u.designation
            FROM 
                users u
            WHERE
                u.username = "'.$_GET['username'].'"
            ORDER BY
                u.position ASC';

$grabParams = array(

    'id',
    'username',
    'first_name',
    'middle_name',
    'last_name',
    'isadmin',
    'position',
    'store_code',
    'init_pass',
    'online',
    'date_log',
    'store_location',
    'user_type',
    'store_type',
    'access_brands',
    'department',
    'designation'

);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrUser[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>
