<?php 
if(!isset($_SESSION)){
        session_start();
    }

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];



$arrLoc=array();

$query="    SELECT 
                sc.id,
                sc.branch,
                sc.location_code,
                sl.address,
                sl.province,
                sl.city
            FROM 
                store_codes sc
            JOIN
                stores_locations sl
            ON sl.store_id=sc.location_code
            WHERE 
                sl.store_id!='101'
            AND 
                sl.store_id!='900'
            ORDER BY sc.branch";

$grabParamLocs=array("id","branch","location_code","address","province","city");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 6; $i++) { 

            $tempArray[$grabParamLocs[$i]] = ${'result' . ($i+1)};

        };

        $arrLoc[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>