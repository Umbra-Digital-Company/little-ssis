<?php
    if($_GET['page'] == 'order-confirmation'){

        $arrPaperBag = array();

        $querypn = 'SELECT 
                        item_name,
                        product_code
                    FROM 
                        poll_51_studios_new
                    WHERE 
                        product_code IN ("P1008-32", "P1008-33", "P1008-34")';

        $querypn .= 'ORDER BY item_name ASC;';
            
        $grabFrames = array("item_name","product_code");

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $querypn)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < 2; $i++) { 

                    $tempArray[$grabFrames[$i]] = ${'result' . ($i+1)};

                };

                $arrPaperBag[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);

        };
    }
?>