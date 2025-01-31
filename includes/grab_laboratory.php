<?php 

if(!isset($_SESSION)){

    session_start();

}

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

$arrLab = array();
$query =    "SELECT 
                lab_id,
                lab_name,
                phone_number 
            FROM 
                labs_locations 
            ORDER BY
                lab_name ASC;";

$grabParams = array(

    'lab_id',
    'lab_name',
    'phone_number'

);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 3; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrLab[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>
