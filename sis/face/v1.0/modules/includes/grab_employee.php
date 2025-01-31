<?php 
if(!isset($_SESSION)){
        session_start();
    }

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];



$arrEmp=array();

$query="SELECT emp_id,first_name,middle_name,last_name,gender,civil_status,birth_date,department,designation,brand,location FROM emp_table s";

$grabParamLocs=array("emp_id","first_name","middle_name","last_name","gender","civil_status","birth_date","department","designation","brand","location");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 11; $i++) { 

            $tempArray[$grabParamLocs[$i]] = ${'result' . ($i+1)};

        };

        $arrEmp[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>