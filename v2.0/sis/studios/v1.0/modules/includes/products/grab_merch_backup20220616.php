<?php 

if(!isset($_SESSION)){ session_start(); }

//////////////////////////////////////////////////// GRAB Merch

// Set array
$arrFrames = array();

// Set query
$querypn =  'SELECT DISTINCT
                item_name,';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                $querypn .=' p.sr_price'; 
            }else{
                $querypn .=' p.price';
            }
$querypn .=' FROM 
                poll_51_studios p
            WHERE
                (
                    p.item_name LIKE "%CLEAN LENS%"
                    OR p.item_name LIKE "%CARE KIT%"
                    OR p.product_code = "AFC002"
                  )
                    AND p.product_code <> "PL0011-A"
                    AND (p.vnd_srp > 0 OR p.price > 0 OR p.sr_price > 0)
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

//////////////////////////////////////////////////// FIRST LOAD

//grab default priority products on first load

$priority = (isset($_GET['search']) && $_GET['search'] !='') ? ' AND p.item_name LIKE "%'.mysqli_real_escape_String($conn,$_GET['search']).'%"' : '';

$getColors = [];
$getShapes = [];
$getCollections = [];

//////////////////////////////////////////////////// GRAB PRODUCTS

// Set array
$arrProduct = array();

// Set query
$query =    'SELECT
                p.item_name,
                p.item_name,
                p.product_code,
                p.item_name,';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $query .=' p.sr_price,'; 
                }else{
                    $query .=' p.price,';
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
                poll_51_studios p
                    LEFT JOIN poll_51_shopify_data s
                        ON s.product_code = p.product_code
                    LEFT JOIN products_colors_studios pcs
                        ON p.product_code = pcs.product_code
                    LEFT JOIN products_studios ps
                        ON p.product_code = ps.product_code
                    LEFT JOIN poll_51_image_studios img
                        ON img.product_code = p.product_code
            WHERE 
                (
                    p.item_name LIKE "%CLEAN LENS%"
                    OR p.item_name LIKE "%CARE KIT%"
                    OR p.product_code = "AFC002"
                )
                AND p.product_code <> "PL0011-A"
                AND (p.vnd_srp > 0 OR p.price > 0 OR p.sr_price > 0)
                '.$priority.'
            ORDER BY 
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
        if($tempArray['price'] == '') continue;
        $arrProduct[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

//////////////////////////////////////////////////// SORT PRODUCTS

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

// echo '<pre>';
// print_r($arrProductsSorted);
// echo '</pre>';
// exit;

// Custom Resorting of Merch
$arrMerchSorted = array(
    "care kit" => array(),
    "clean lens" => array(),
    "anti-fog wipe" => array()
);

// Cycle through array
for ($i=0; $i < sizeOf($arrProductsSorted); $i++) { 

    // Set current data
    $curArr = $arrProductsSorted[$i];
    $curItemDescription = strtolower($curArr['item_description']);
    $itemID = "";

    // Care Kits
    if(strpos($curItemDescription, 'care kit') !== false) {

        $itemID = 'care kit';
        $curArr['colors'][0]['color'] = str_replace("CARE KIT 2021 - ", "", $curArr['colors'][0]['color']);

    };
    // Clean Lens
    if(strpos($curItemDescription, 'clean lens') !== false) {

        $itemID = 'clean lens';
        $curArr['colors'][0]['color'] = str_replace("CLEAN LENS ", "", $curArr['colors'][0]['color']);

    };
    // Anti Fog
    if(strpos($curItemDescription, 'anti-fog') !== false) {

        $itemID = 'anti-fog';
        $curArr['colors'][0]['color'] = str_replace("ANTI-FOG ", "", $curArr['colors'][0]['color']);

    };
    // Planners
    if(strpos($curItemDescription, 'planner') !== false) {

        $itemID = 'planner';
        $curArr['colors'][0]['color'] = str_replace(" AGENDA PLANNER", "", $curArr['colors'][0]['color']);
        $curArr['colors'][0]['color'] = str_replace("SUNNIES AGENDA ", "", $curArr['colors'][0]['color']);

    };
    // Hardcases
    if(strpos($curItemDescription, 'hardcase') !== false) {

        // Velvet Hardcases
        if(strpos($curItemDescription, 'velvet') !== false && strpos($curItemDescription, 'hardcase') !== false) {

            $itemID = 'velvet hardcase';
            $curArr['colors'][0]['color'] = str_replace("SUNNIES VELVET HARDCASE ", "", $curArr['colors'][0]['color']);

        }
        // SBC Hardcases
        elseif(strpos($curItemDescription, 'sbc') !== false && strpos($curItemDescription, 'hardcase') !== false) {

            $itemID = 'sbc hardcase';
            $curArr['colors'][0]['color'] = str_replace("SBC HARDCASE ", "", $curArr['colors'][0]['color']);
            $curArr['colors'][0]['color'] = str_replace(" W/ CLOTH", "", $curArr['colors'][0]['color']);

        }
        // Hardcases + Cloth
        elseif(strpos($curItemDescription, 'w/ cloth') !== false && strpos($curItemDescription, 'hardcase') !== false) {

            $itemID = 'hardcase + cloth';
            $curArr['colors'][0]['color'] = str_replace("HARDCASE ", "", $curArr['colors'][0]['color']);
            $curArr['colors'][0]['color'] = str_replace(" w/ CLOTH", "", $curArr['colors'][0]['color']);
            $curArr['colors'][0]['color'] = str_replace(" W/ CLOTH", "", $curArr['colors'][0]['color']);
            $curArr['colors'][0]['color'] = str_replace("SUN COLOR STORY - ", "", $curArr['colors'][0]['color']);

        }
        // Hardcases + Cloth
        elseif(strpos($curItemDescription, '+ cloth') !== false && strpos($curItemDescription, 'hardcase') !== false) {

            $itemID = 'hardcase + cloth';
            $curArr['colors'][0]['color'] = str_replace("HARDCASE ", "", $curArr['colors'][0]['color']);
            $curArr['colors'][0]['color'] = str_replace(" + CLOTH", "", $curArr['colors'][0]['color']);
            $curArr['colors'][0]['color'] = str_replace("SUN COLOR STORY - ", "", $curArr['colors'][0]['color']);

        }
        else {

            $itemID = 'hardcase';
            $curArr['colors'][0]['color'] = str_replace("HARDCASE ", "", $curArr['colors'][0]['color']);
            $curArr['colors'][0]['color'] = str_replace(" HARDCASE", "", $curArr['colors'][0]['color']);

        };

    };   
    // Daily Duo
    if(strpos($curItemDescription, 'daily duo') !== false) {

        $itemID = 'daily duo';
        $curArr['colors'][0]['color'] = str_replace("THE DAILY DUO ", "", $curArr['colors'][0]['color']);

    };
    // Daily Mask
    if(strpos($curItemDescription, 'daily mask') !== false) {

        $itemID = 'daily mask';
        $curArr['colors'][0]['color'] = str_replace("THE DAILY MASK W/ POUCH ", "", $curArr['colors'][0]['color']);

    };
    // Daily Shield
    if(strpos($curItemDescription, 'daily shield') !== false) {

        $itemID = 'daily shield';
        $curArr['colors'][0]['color'] = str_replace("THE DAILY SHIELD ", "", $curArr['colors'][0]['color']);

    };

    // Push to Sorted Array
    $arrMerchSorted[$itemID]['item_description']    = strtoupper($itemID);
    $arrMerchSorted[$itemID]['price']               = $curArr['price'];
    $arrMerchSorted[$itemID]['description']         = $curArr['description'];
    $arrMerchSorted[$itemID]['tags']                = $curArr['tags'];
    $arrMerchSorted[$itemID]['color_picker']        = $curArr['color_picker'];
    $arrMerchSorted[$itemID]['product_description'] = $curArr['product_description'];

    // Push color
    $arrMerchSorted[$itemID]['colors'][$i] = $curArr['colors'][0];    

};

// Remove empty objects
$arrMerchSorted = array_filter($arrMerchSorted);

// Reindex
$arrMerchSorted = array_values($arrMerchSorted);

for ($i=0; $i < sizeOf($arrMerchSorted); $i++) { 
  
    // Reindex
    $arrMerchSorted[$i]['colors'] = array_values($arrMerchSorted[$i]['colors']);

};

// echo '<pre>';
// print_r($arrMerchSorted);
// echo '</pre>';
// exit;

function setPoNumberAdd($order_no_Cart){

    global $conn;

    $arrCartQF = array();

    $arrCartQuery = 'SELECT  
                        COUNT(pr.product_code) as count,
                        GROUP_CONCAT( os.orders_specs_id)  as group_orders_specs_id,
                        -- LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), "")) AS "grab_color",
                        -- LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1))) AS "grab_style",
                        IF(
                            pr.product_code LIKE "AR%", 
                            RIGHT(item_name, LENGTH(item_name) - LOCATE(")", item_name)),
                            IF(
                                pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                "",
                                LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), ""))
                            )                            
                        ) AS "grab_color",                        
                        IF(
                            pr.product_code LIKE "AR%",
                            REPLACE(LEFT(pr.item_name, LOCATE(")", pr.item_name) - 1), "ANTI-RADIATION (", ""),
                            IF(
                                pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                pr.item_name,
                                LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)))
                            )                            
                        ) AS "grab_style",
                        os.orders_specs_id,
                        pr.item_name,
                        pr.product_number,
                        pr.item_code,';
                        if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                            $arrCartQuery .=' pr.sr_price,'; 
                        }else{
                            $arrCartQuery .='  pr.price,'; 
                        }

    $arrCartQuery .= '  pr.product_code,
                        os.product_upgrade,
                        os.product_code AS product_code_order,
                        IF(
                            s.image_url IS NOT NULL,
                            s.image_url,
                            img.image_url
                        )
                    FROM 
                        profiles_info p
                            LEFT JOIN orders_sunnies_studios os 
                                ON os.profile_id = p.profile_id
                            LEFT JOIN poll_51_studios pr 
                                ON pr.product_code = os.product_code OR pr.product_code = os.product_upgrade
                            LEFT JOIN poll_51_shopify_data s
                                ON s.product_code = os.product_code OR s.product_code = os.product_upgrade
                            LEFT JOIN poll_51_image_studios img
                                ON img.product_code = pr.product_code
                            LEFT  JOIN orders_studios o 
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
        "product_code_order",
        "image_url"
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


