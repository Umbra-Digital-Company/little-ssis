<?php 

// Check position
$userPosition = $_SESSION['user_login']['position'];

// $arrStoresStudios = array();
$arrStoresFace = array();
$queryAll = "";
$queryAll .= "SELECT 
                 if(store_code IN('5000470','5000472','5000473','6008053','6008054','6008055'),

'SF-MPWHC',
`store_code`) as store_code_proper,
if(store_code IN('5000470','5000472','5000473','6008053','6008054','6008055'),

    'SF Marketplace',
    `store_name_proper`) as store_name_proper
            FROM 
                store_codes_face
            WHERE store_code != ''
            group by store_code_proper
            ORDER BY
                store_name_proper ASC
                ";

$grabParams = array(
    'store_id',
    'store_name'
);

$query = $queryAll;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2);

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
