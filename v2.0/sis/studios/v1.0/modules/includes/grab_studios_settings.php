<?php
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
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

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
?>