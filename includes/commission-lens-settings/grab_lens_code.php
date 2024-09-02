<?php

$arrLens = array();
$query = "SELECT 
                item_name,
                item_code,
                product_code
            FROM
                poll_51
            WHERE item_code = 'LENS001' AND house_brand IN ('HBR0001','HBR0002')
            ORDER BY
                item_name ASC;";

$grabParams = array(
    'item_name',
    'item_code',
    'product_code'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrLens[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

?>
