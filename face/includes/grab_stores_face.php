<?php 

// Check position
$userPosition = $_SESSION['user_login']['position'];

// $arrStoresStudios = array();
$arrStoresFace = array();
$queryAll = "";
$queryAll .= "SELECT 
                store_code,
                store_name_proper,
                warehouse_code,
                warehouse_name
            FROM 
                store_codes_face
            WHERE store_code != ''
            ORDER BY
                store_name_proper ASC";

$grabParams = array(
    'store_id',
    'store_name',
    'warehouse_code',
    'warehouse_name'
);

$query = $queryAll;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

        };

        // $arrStoresStudios[] = $tempArray;
        $arrStoresFace[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>
