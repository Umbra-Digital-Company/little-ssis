<?php
    $arrProducts = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code
                     FROM 
                    poll_51_face_new p
                    WHERE
                         (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

    $arrProductsLips = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code
                     FROM 
                    poll_51_face_new p
                    WHERE
                    p.data_cgc IN("DCGC0037","DCGC0039","DCGC0001","DCGC0002","DCGC0048","DCGC0045","DCGC0041","DCGC0035","DCGC0018","DCGC0006","DCGC0021","DCGC0046","DCGC0007","DCGC0044","DCGC0027")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsLips[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsFace = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE
                    p.data_cgc IN("DCGC0036","DCGC0040","DCGC0042")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsFace[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsBrows = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE
                    p.data_cgc IN("DCGC0005","DCGC0016")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsBrows[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsEyes = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE
                    p.data_cgc IN("DCGC0047","DCGC0029","DCGC0032")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsEyes[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsSkin = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE
                    p.data_cgc IN("DCGC0031","DCGC0030","DCGC0038","DCGC0026")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsSkin[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsCheeks = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE
                    p.data_cgc IN("DCGC0017","DCGC0020","DCGC0024","DCGC0004","DCGC0019")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsCheeks[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsNails = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE
                    p.data_cgc IN("DCGC0025")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsNails[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrProductsSets = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE
                    p.data_cgc IN("DCGC0023")
                        AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

            $arrProductsSets[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrProductsMerch = array();

    $queryAll = 'SELECT DISTINCT
                    p.item_name,
                    p.item_code 
                FROM 
                    poll_51_face_new p
                WHERE p.data_cgc IN("DCGC0043","DCGC0009","DCGC0014","DCGC0034","DCGC0011")
                    AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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
                    FROM face_text_images_settings
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
                    p51.item_code
                     FROM 
                     face_settings ss
                     LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
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
    $arrLips = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                     FROM 
                     face_settings ss
                     LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                     WHERE ss.category = 'lips' AND active = 1
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

            $arrLips[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrFace = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                FROM 
                    face_settings ss
                        LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                WHERE 
                    ss.category = 'face' 
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

            $arrFace[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrBrows = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                FROM 
                    face_settings ss
                        LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                WHERE 
                    ss.category = 'brows' 
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

            $arrBrows[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrEyes = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                FROM 
                    face_settings ss
                        LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                WHERE 
                    ss.category = 'eyes' 
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

            $arrEyes[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrSkin = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                FROM 
                    face_settings ss
                        LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                WHERE 
                    ss.category = 'skin' 
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

            $arrSkin[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrCheeks = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                FROM 
                    face_settings ss
                        LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                WHERE 
                    ss.category = 'cheeks' 
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

            $arrCheeks[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrNails = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                FROM 
                    face_settings ss
                        LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                WHERE 
                    ss.category = 'nails' 
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

            $arrNails[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $arrSets = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                FROM 
                    face_settings ss
                        LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                WHERE 
                    ss.category = 'sets' 
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

            $arrSets[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };


    $arrMerch = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                     FROM 
                     face_settings ss
                     LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
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

?>