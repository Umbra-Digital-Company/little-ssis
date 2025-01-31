<?php 

if(!isset($_SESSION)){ session_start(); }

//////////////////////////////////////////////////// GRAB FRAMES

// Set array
$arrFrames = array();

// Set query
$querypn =  'SELECT DISTINCT
                LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style",';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $querypn .=' p.sr_srp'; 
                }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
                    $querypn .=' p.vnd_srp'; 
                }else{
                    $querypn .=' p.retail';
                }
$querypn .=' FROM 
                poll_51_face_new p
            WHERE';

if($_GET['page'] == 'select-merch') {

    $querypn .= ' (
                    p.item_name LIKE "%AGENDA%" 
                    OR p.item_name LIKE "%HARDCASE%"
                    OR p.item_name LIKE "%ANTI FOG%"
                    OR p.item_name LIKE "%DAILY SHIELD%"
                    OR p.item_name LIKE "%DAILY MASK%"
                    OR p.item_name LIKE "%DAILY DUO%"
                  )
                  AND (p.vnd_srp > 0 OR p.retail > 0)';

}
elseif($_GET['page'] == 'add-paper-bag') {

    $querypn .= ' p.item_name LIKE "%PAPER BAG%"';

}
else {
    
    $querypn .= ' p.item_code NOT LIKE "%AC%"
                    AND p.item_code NOT LIKE "%C%"
                    AND  p.item_code NOT LIKE "%PL%"
                    AND  p.item_code NOT LIKE "%P%"
                    AND  p.item_code NOT LIKE "%H%"
                    AND  p.item_code NOT LIKE "%SC%"
                    AND  p.item_code NOT LIKE "%SGC%"
                    AND  p.item_code NOT LIKE "%SCL%"
                    AND  p.item_code NOT LIKE "%SW%"
                    AND  p.item_code NOT LIKE "%SS%"
                    AND  p.item_code NOT LIKE "%ST%"
                    AND  p.item_name NOT LIKE "%AGENDA%"
                    AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)';
}

$querypn .= ' ORDER BY p.item_name ASC;';
    
$grabFrames = array(
    "item_description", 
    "price"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querypn)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 2; $i++) { 

            $tempArray[$grabFrames[$i]] = ${'result' . ($i+1)};

        };

        $arrFrames[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

//////////////////////////////////////////////////// GRAB PRODUCTS
$arrShapesData = [];
    $queryAll = 'SELECT DISTINCT
                    pcs.code,
                    pcs.name
                    FROM poll_51_studios p
                        INNER JOIN poll_51_shapes pcs ON p.shape = pcs.code
                            WHERE ( p.item_name LIKE "%AGENDA%" 
                                    OR p.item_name LIKE "%HARDCASE%"
                                    OR p.item_name LIKE "%ANTI FOG%"
                                    OR p.item_name LIKE "%DAILY SHIELD%"
                                    OR p.item_name LIKE "%DAILY MASK%"
                                    OR p.item_name LIKE "%DAILY DUO%"
                                ) AND (p.vnd_srp > 0 OR p.price > 0 OR p.sr_price > 0)
                                ORDER BY pcs.name ASC;';

    $grabParams = array(
        'code',
        'name'
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
            $arrShapesData[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);
    }

$arrCollectionsData = [];
    $queryAll = 'SELECT DISTINCT
                    pcs.code,
                    pcs.name
                    FROM poll_51_studios p
                        INNER JOIN poll_51_collections pcs ON p.collection = pcs.code
                            WHERE ( p.item_name LIKE "%AGENDA%" 
                                    OR p.item_name LIKE "%HARDCASE%"
                                    OR p.item_name LIKE "%ANTI FOG%"
                                    OR p.item_name LIKE "%DAILY SHIELD%"
                                    OR p.item_name LIKE "%DAILY MASK%"
                                    OR p.item_name LIKE "%DAILY DUO%"
                                ) AND (p.vnd_srp > 0 OR p.price > 0 OR p.sr_price > 0)
                                ORDER BY pcs.name ASC;';

    $grabParams = array(
        'code',
        'name'
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
            $arrCollectionsData[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);
    }

//////////////////////////////////////////////////// GRAB PRODUCTS
//grab default priority products on first load
$priority = (isset($_GET['search']) && $_GET['search'] !='') ? ' AND p.item_name LIKE "%'.mysqli_real_escape_String($conn,$_GET['search']).'%"' : '';
$getShapes = [];
$getCollections = [];
if(!isset($_GET['filter'])){
    //get priority settings
    $arrPriority = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.item_code
                     FROM 
                     studios_settings ss
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

            $arrPriority[] = $tempArray['product_code'];

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);
    }

        $priority = ' AND p.item_code IN ("'.implode('","', $arrPriority).'")';

}else{
    if(isset($_GET['shapes'])  && $_GET['shapes'] != ''){
        $getShapes = explode(",",mysqli_real_escape_String($conn,$_GET['shapes']));  
        $priority .= ' AND p.shape IN ("'.implode('","', $getShapes).'")';
    }

    if(isset($_GET['collections'])  && $_GET['collections'] != ''){
        $getCollections = explode(",",mysqli_real_escape_String($conn,$_GET['collections']));   
        $priority .= ' AND p.collection IN ("'.implode('","', $getCollections).'")';
    }
}

// Set array
$arrProduct = array();
$arrCheckFramesSection = [];

// Set query
$query =    'SELECT
                p.item_name,
                LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color",
                p.item_code,
                LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style" ,';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $query .=' p.sr_srp,'; 
                }else{
                    $query .=' p.retail,';
                }

$query .=       ' IF(
                    s.image_url IS NOT NULL,
                    s.image_url,
                    img.image_url
                ),
                s.main_description,
                s.tags,
                pcs.color_picker,
                ps.product_description
            FROM
                poll_51_face_new p
                    LEFT JOIN poll_51_shopify_data s
                        ON s.product_code = p.item_code
                    LEFT JOIN products_colors_studios pcs
                        ON p.item_code = pcs.product_code
                    LEFT JOIN products_studios ps
                        ON p.item_code = ps.product_code
                    LEFT JOIN poll_51_image_studios img
                        ON img.product_code = p.item_code
            WHERE';

if($_GET['page'] == 'select-merch'){

    $query .=   ' (
                    p.item_name LIKE "%AGENDA%" 
                    OR p.item_name LIKE "%HARDCASE%"
                    OR p.item_name LIKE "%ANTI FOG%"
                    OR p.item_name LIKE "%DAILY SHIELD%"
                    OR p.item_name LIKE "%DAILY MASK%"
                    OR p.item_name LIKE "%DAILY DUO%"
                  )
                  AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
                  '.$priority.'
                  ';

}
elseif($_GET['page'] == 'add-paper-bag'){

    $query .=   ' p.item_name LIKE "%PAPER BAG%"';

}
else{

    $query .=   ' p.item_code NOT LIKE "%AC%"
                    AND p.item_code NOT LIKE "%C%"
                    AND  p.item_code NOT LIKE "%PL%"
                    AND  p.item_code NOT LIKE "%P%"
                    AND  p.item_code NOT LIKE "%H%"
                    AND  p.item_code NOT LIKE "%SC%"
                    AND  p.item_code NOT LIKE "%SGC%"
                    AND  p.item_code NOT LIKE "%SCL%"
                    AND  p.item_code NOT LIKE "%SW%"
                    AND  p.item_code NOT LIKE "%SS%"
                    AND  p.item_code NOT LIKE "%ST%"
                    AND  p.item_name NOT LIKE "%AGENDA%"
                    AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0) ';
}

$query .=       'ORDER BY 
                    p.item_name ASC;';
// echo $query;
$grabParams = array(
    "description", 
    "color", 
    "product_code", 
    "item_description",
    "price", 
    "image_url",     
    "main_description", 
    "tags",
    "color_picker",
    "product_description"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < count($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrProduct[] = $tempArray;
        if(!in_array($tempArray['item_description'], $arrCheckFramesSection)){
            $arrCheckFramesSection[] = $tempArray['item_description'];
        }

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};



// Sort products
$arrProductsSorted = array();

// Loop through initial product array
for ($i=0; $i < sizeOf($arrProduct); $i++) { 
  
    // Set current data
    $curStyle       = $arrProduct[$i]["item_description"];
    $curPrice       = $arrProduct[$i]["price"];
    $curDescription = $arrProduct[$i]["main_description"];
    $curTags        = $arrProduct[$i]["tags"];
    $color_picker   = $arrProduct[$i]["color_picker"];
    $product_description   = $arrProduct[$i]["product_description"];

    // Push to sorted array
    $arrProductsSorted[$curStyle]["item_description"] = $curStyle;
    $arrProductsSorted[$curStyle]["price"]            = $curPrice;
    $arrProductsSorted[$curStyle]["description"]      = $curDescription;
    $arrProductsSorted[$curStyle]["tags"]             = $curTags;
    $arrProductsSorted[$curStyle]["color_picker"]     = $color_picker;
    $arrProductsSorted[$curStyle]["product_description"]     = $product_description;
    $arrProductsSorted[$curStyle]["colors"]           = array();

};

// Loop through initial product array to sort out colors
for ($i=0; $i < sizeOf($arrProduct); $i++) { 
  
    // Set current data
    $curStyle = $arrProduct[$i]["item_description"];
    $curColor = $arrProduct[$i]["color"];
    $curSKU   = $arrProduct[$i]["product_code"];
    $curImage = $arrProduct[$i]["image_url"];
    $color_picker = $arrProduct[$i]["color_picker"];
    $product_description = $arrProduct[$i]["product_description"];

    // Push to sorted array color array
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["product_code"] = $curSKU;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["color"]        = $curColor;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["image"]        = $curImage;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["color_picker"] = $color_picker;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["product_description"] = $product_description;

};

// Reindex sorted array
$arrProductsSorted = array_values($arrProductsSorted);

// Loop through and reindex color arrays
for ($i=0; $i < sizeOf($arrProductsSorted); $i++) { 
  
    // Reindex color array
    $arrProductsSorted[$i]["colors"] = array_values($arrProductsSorted[$i]["colors"]);

};

function setPoNumberAdd($order_no_Cart){

    global $conn;

    $arrCartQF = array();

    $arrCartQuery = 'SELECT  
                        COUNT(pr.item_code) as count,
                        GROUP_CONCAT( os.orders_specs_id)  as group_orders_specs_id,
                        -- LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), "")) AS "grab_color",
                        -- LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1))) AS "grab_style",
                        IF(
                            pr.item_code LIKE "AR%", 
                            RIGHT(item_name, LENGTH(item_name) - LOCATE(")", item_name)),
                            IF(
                                pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                "",
                                LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), ""))
                            )                            
                        ) AS "grab_color",                        
                        IF(
                            pr.item_code LIKE "AR%",
                            REPLACE(LEFT(pr.item_name, LOCATE(")", pr.item_name) - 1), "ANTI-RADIATION (", ""),
                            IF(
                                pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                pr.item_name,
                                LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)))
                            )                            
                        ) AS "grab_style",
                        os.orders_specs_id,
                        pr.item_name,
                        pr.barcode,
                        pr.item_code,';
                        if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                            $arrCartQuery .=' pr.sr_srp,'; 
                        }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
                            $arrCartQuery .=' pr.vnd_srp,'; 
                        }else{
                            $arrCartQuery .='  pr.retail,';
                        }
    $arrCartQuery .= '  pr.item_code,
                        os.product_upgrade,
                        IF(
                            s.image_url IS NOT NULL,
                            s.image_url,
                            img.image_url
                        ),
                        os.dispatch_type
                    FROM 
                        profiles_info p
                            LEFT JOIN orders_face_details os 
                                ON os.profile_id = p.profile_id
                            LEFT JOIN poll_51_face_new pr 
                                ON pr.item_code = os.product_code OR pr.item_code = os.product_upgrade
                            LEFT JOIN poll_51_shopify_data s
                                ON s.product_code = os.product_code OR s.product_code = os.product_upgrade
                            LEFT JOIN poll_51_image_studios img
                                ON img.product_code = pr.item_code
                            LEFT  JOIN orders_face o 
                                ON o.order_id = os.order_id 
                            LEFT JOIN emp_table u 
                                ON u.emp_id= o.doctor
                    WHERE 
                        o.order_id = "'.$order_no_Cart.'"
                            AND os.status != "cancelled"
                    GROUP BY 
                        pr.item_code, os.product_upgrade ORDER BY os.id ASC';

    $grabParamsQF = array(
        "count",
        "group_orders_specs_id",
        "color",
        "style",
        "orders_specs_id",
        "item_description",
        "product_number",
        "item_code",
        "price",
        "product_code",
        "product_upgrade",
        "image_url",
        "dispatch_type"
    );
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParamsQF); $i++) { 

                $tempArray[$grabParamsQF[$i]] = ${'result' . ($i+1)};

            };
            $tempArray['item_description'] = ucwords(strtolower($tempArray['item_description']));
            $arrCartQF [] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }

    return $arrCartQF;
}

$order_count = (isset($_SESSION['order_no'])) ? setPoNumberAdd($_SESSION['order_no']) : [];
$arrCart     = $order_count;
$order_count = (isset($_SESSION['order_no'])) ? count($order_count) : 0;

//print_r($arrCart); exit;

if($_GET['page'] == 'order-confirmation' || $_GET['page'] == 'order-confirmation-test'){

    $arrPaperBag = array();

    $querypn = 'SELECT
                    p51.item_name,
                    p51.item_code
                     FROM 
                     studios_settings ss
                     LEFT JOIN poll_51_face_new p51 ON p51.item_code = ss.product_code
                     WHERE ss.category = "paperbag" AND active = 1
                    ORDER BY p51.item_name ASC;';
        
    $grabFrames = array("item_name","product_code");

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $querypn)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabFrames); $i++) { 

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


