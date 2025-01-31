<?php 

if(!isset($_SESSION)){ session_start(); }

//////////////////////////////////////////////////// GRAB FRAMES

// Set array
$arrFrames = array();

// Set query
$querypn =  'SELECT DISTINCT
                LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style",
                p.price 
            FROM 
                poll_51_studios p
            WHERE
                 p.product_code NOT LIKE "%AC%"
                    AND p.product_code NOT LIKE "%C%"
                    AND  p.product_code NOT LIKE "%PL%"
                    AND  p.product_code NOT LIKE "%P%"
                    AND  p.product_code NOT LIKE "%H%"
                    AND  p.product_code NOT LIKE "%SC%"
                    AND  p.product_code NOT LIKE "%SGC%"
                    AND  p.product_code NOT LIKE "%SCL%"
                    AND  p.product_code NOT LIKE "%SW%"
                    AND  p.product_code NOT LIKE "%SS%"
                    AND  p.product_code NOT LIKE "%ST%"
                    AND  p.item_name NOT LIKE "%AGENDA%"
                    AND (p.vnd_srp > 0 OR p.price > 0)
            ORDER BY 
                p.item_name ASC;';
    
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

// Set array
$arrProduct = array();

// Set query
$query =    'SELECT
                p.item_name,
                LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color",
                p.product_code,
                LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style" ,
                p.price,
                IF(
                    s.image_url IS NOT NULL,
                    s.image_url,
                    img.image_url
                ),
                s.main_description,
                s.tags,
                pcs.color_picker,
                ps.product_description
            FROM
                poll_51_studios p
                    LEFT JOIN poll_51_shopify_data s
                        ON s.product_code = p.product_code
                    LEFT JOIN products_colors_studios pcs
                        ON p.product_code = pcs.product_code
                    LEFT JOIN products_studios ps
                        ON p.product_code = ps.product_code
                    LEFT JOIN poll_51_image_studios img
                        ON img.product_code = p.product_code
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
                  AND (p.vnd_srp > 0 OR p.price > 0)';

}
elseif($_GET['page'] == 'add-paper-bag'){

    $query .=   ' p.item_name LIKE "%PAPER BAG%"';

}
else{

    $query .=   ' p.product_code NOT LIKE "%AC%"
                    AND p.product_code NOT LIKE "%C%"
                    AND  p.product_code NOT LIKE "%PL%"
                    AND  p.product_code NOT LIKE "%P%"
                    AND  p.product_code NOT LIKE "%H%"
                    AND  p.product_code NOT LIKE "%SC%"
                    AND  p.product_code NOT LIKE "%SGC%"
                    AND  p.product_code NOT LIKE "%SCL%"
                    AND  p.product_code NOT LIKE "%SW%"
                    AND  p.product_code NOT LIKE "%SS%"
                    AND  p.product_code NOT LIKE "%ST%"
                    AND  p.item_name NOT LIKE "%AGENDA%"
                    AND (p.vnd_srp > 0 OR p.price > 0) ';
}

$query .=       'ORDER BY 
                    p.item_name ASC;';

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
                        COUNT(pr.product_code) as count,
                        GROUP_CONCAT( os.orders_specs_id)  as group_orders_specs_id,
                        LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), "")) AS "grab_color",
                        LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1))) AS "grab_style",
                        os.orders_specs_id,
                        pr.item_name,
                        pr.product_number,
                        pr.item_code,';

    $arrCartQuery .= ($_SESSION['store_code'] == 142 || $_SESSION['store_code'] == 150) ? 'pr.vnd_srp,' : ' pr.price,'; 

    $arrCartQuery .= '  pr.product_code,
                        os.product_upgrade,
                        IF(
                            s.image_url IS NOT NULL,
                            s.image_url,
                            img.image_url
                        )
                    FROM 
                        profiles_info p
                            LEFT JOIN orders_specs os 
                                ON os.profile_id = p.profile_id
                            LEFT JOIN poll_51_studios pr 
                                ON pr.product_code = os.product_code OR pr.product_code = os.product_upgrade
                            LEFT JOIN poll_51_shopify_data s
                                ON s.product_code = os.product_code OR s.product_code = os.product_upgrade
                            LEFT JOIN poll_51_image_studios img
                                ON img.product_code = pr.product_code
                            LEFT  JOIN orders o 
                                ON o.order_id = os.order_id 
                            LEFT JOIN emp_table u 
                                ON u.emp_id= o.doctor
                    WHERE 
                        o.order_id = "'.$order_no_Cart.'"
                            AND os.status != "cancelled"
                    GROUP BY 
                        pr.product_code, os.product_upgrade ORDER BY os.id ASC';

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
        "image_url"
    );
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

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

if($_GET['page'] == 'order-confirmation'){

    $arrPaperBag = array();

    $querypn = 'SELECT 
                    item_name,
                    product_code
                FROM 
                    poll_51_studios
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


