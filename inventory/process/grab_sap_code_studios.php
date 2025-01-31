<?php
    function getStoreName($store_code){
        global $conn;
        $storeName = '';

        $query="SELECT 
                    store_name_proper,
                    store_code,
                    warehouse_code
                FROM 
                    store_codes_studios
                    where store_code = '".mysqli_real_escape_string($conn,$store_code)."';";
        $grabParams = array(
            'store_name',
            'store_id',
            'warehouse_code');

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $storeName = ['store_name' => $tempArray['store_name'], 'store_code' => $tempArray['store_id'], 'warehouse_code' => $tempArray['warehouse_code']];

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);

        };

        return $storeName;
    }
?>