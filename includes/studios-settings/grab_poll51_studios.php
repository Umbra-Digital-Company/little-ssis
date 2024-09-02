<?php
    $arrProducts = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.product_code
                     FROM 
                    poll_51_studios p
                    WHERE
                    p.product_code NOT LIKE "%AC%"
                    AND p.product_code NOT LIKE "%C%"
                    AND p.product_code NOT LIKE "%PL%"
                    -- AND p.product_code NOT LIKE "%P%"
                    AND p.product_code NOT LIKE "%H%"
                    AND p.product_code NOT LIKE "%SC%"
                    AND p.product_code NOT LIKE "%SGC%"
                    AND p.product_code NOT LIKE "%SCL%"
                    AND p.product_code NOT LIKE "%SW%"
                    AND p.product_code NOT LIKE "%SS%"
                    AND p.product_code NOT LIKE "%ST%"
                    AND p.item_name NOT LIKE "%AGENDA%"
                    AND p.product_code NOT LIKE "%AR%"
                    ORDER BY p.item_name ASC;
                    ';

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrProducts[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsAntirad = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.product_code 
                FROM 
                    poll_51_studios p
                WHERE
                    p.product_code LIKE "AR%"
                        AND p.product_code NOT LIKE "%i%"
                        AND (p.vnd_srp > 0 OR p.price > 0)
                ORDER BY 
                    p.item_name ASC;
                    ';

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrProductsAntirad[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrProductsMerch = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.product_code 
                FROM 
                    poll_51_studios p
                WHERE (
                        p.item_name LIKE "%AGENDA%" 
                        OR p.item_name LIKE "%HARDCASE%"
                        OR p.item_name LIKE "%ANTI FOG%"
                        OR p.item_name LIKE "%DAILY SHIELD%"
                        OR p.item_name LIKE "%DAILY MASK%"
                        OR p.item_name LIKE "%DAILY DUO%"
                    ) AND (p.vnd_srp > 0 OR p.price > 0)
                ORDER BY 
                    p.item_name ASC;
                    ';

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrProductsMerch[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrProductsPaperBag = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.product_code 
                FROM 
                    poll_51_studios p
                WHERE p.item_name LIKE "%PAPER BAG%"
                ORDER BY 
                    p.item_name ASC;
                    ';

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrProductsPaperBag[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

//get text/images
    $arrTextImages = array();

    $queryAll = "SELECT
                    text,
                    image_1,
                    image_1_status,
                    image_2,
                    image_2_status,
                    video,
                    video_status
                    FROM studios_text_images_settings
                    WHERE active = 1;
                    ";

    $grabParams = array(
        'text',
        'image_1',
        'image_1_status',
        'image_2',
        'image_2_status',
        'video',
        'video_status'
    );

    $query = $queryAll;

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1,$result2, $result3 , $result4, $result5, $result6, $result7);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };

            $arrTextImages[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

//get recommended settings
    $arrRecommended = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.product_code
                     FROM 
                     studios_settings ss
                     LEFT JOIN poll_51_studios p51 ON p51.product_code = ss.product_code
                     WHERE ss.category = 'recommended' AND active = 1
                    ORDER BY p51.item_name ASC;
                    ";

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrRecommended[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };
//get priority settings
    $arrPriority = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.product_code
                     FROM 
                     studios_settings ss
                     LEFT JOIN poll_51_studios p51 ON p51.product_code = ss.product_code
                     WHERE ss.category = 'priority' AND active = 1
                    ORDER BY p51.item_name ASC;
                    ";

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrPriority[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrAntirad = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.product_code
                FROM 
                    studios_settings ss
                        LEFT JOIN poll_51_studios p51 ON p51.product_code = ss.product_code
                WHERE 
                    ss.category = 'antirad' 
                        AND ss.product_code NOT LIKE '%i%'
                        AND active = 1
                ORDER BY 
                    p51.item_name ASC;";

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrAntirad[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrMerch = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.product_code
                     FROM 
                     studios_settings ss
                     LEFT JOIN poll_51_studios p51 ON p51.product_code = ss.product_code
                     WHERE ss.category = 'merch' AND active = 1
                    ORDER BY p51.item_name ASC;
                    ";

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrMerch[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrPaperBag = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.product_code
                     FROM 
                     studios_settings ss
                     LEFT JOIN poll_51_studios p51 ON p51.product_code = ss.product_code
                     WHERE ss.category = 'paperbag' AND active = 1
                    ORDER BY p51.item_name ASC;
                    ";

    $grabParams = array(
        'item_name',
        'product_code'
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

            $arrPaperBag[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

?>