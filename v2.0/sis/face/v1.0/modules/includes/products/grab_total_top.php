<?php
    //get text/images
    $arrCount = array();

    $queryAll = "SELECT
                    COUNT(os.id)
                    FROM orders_face o
                    LEFT JOIN orders_face_details os ON o.order_id = os.order_id
                    WHERE status = 'paid'
                        AND DATE(payment_date) = '".date('Y-m-d')."'
                        AND o.origin_branch = '".$_SESSION['user_login']['store_code']."';";
                    

    $grabParams = array(
        'count'
    );

    $query = $queryAll;

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };

            $arrCount[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrTop = array();

    $queryAll = 'SELECT 
                    COUNT(os.id) as count_id,
                    p.item_name
                    FROM orders_face o
                        LEFT JOIN
                            orders_face_details os ON o.order_id = os.order_id
                        LEFT JOIN
                        poll_51_studios_new p ON os.product_code = p.item_code
                    WHERE 
                        os.status = "paid"
                        AND o.origin_branch = "'.$_SESSION['user_login']['store_code'].'"
                     ORDER BY count_id DESC LIMIT 1';
                    

    $grabParams = array(
        'count',
        'color'
    );

    $query = $queryAll;

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };

            $arrTop[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };
?>