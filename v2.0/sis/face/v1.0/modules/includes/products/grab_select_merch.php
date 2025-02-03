<?php 

if(!isset($_SESSION)){ session_start(); }

//////////////////////////////////////////////////// GRAB Merch

// Set array
$arrFrames = array();

// Set query
$querypn =  'SELECT DISTINCT
                item_name,';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $querypn .=' p.sr_srp'; 
                }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
                    $querypn .=' p.vnd_srp'; 
                }else{
                    $querypn .=' p.retail';
                }
$querypn .=' FROM 
                poll_51_face_new p
            WHERE
                p.data_cgc IN("DCGC0043","DCGC0009","DCGC0014","DCGC0034","DCGC0011", "DCGC0078")
                    AND (p.vnd_srp > 0 OR p.retail > 0 OR p.sr_srp > 0)
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

$priority = (isset($_GET['search']) && $_GET['search'] !='') ? ' AND (p.item_name LIKE "%'.mysqli_real_escape_String($conn,$_GET['search']).'%" OR p.item_code LIKE "%'.mysqli_real_escape_String($conn,$_GET['search']).'%")' : '';

$getColors = [];
$getShapes = [];
$getCollections = [];

if(!isset($_GET['filter'])){
    //get priority settings
    $arrPriority = array();

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

            $arrPriority[] = $tempArray['product_code'];

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);
    }

        $priority = ' AND p.item_code IN ("'.implode('","', $arrPriority).'")';

}

//////////////////////////////////////////////////// GRAB PRODUCTS

// Set array
$arrProduct = array();

// Set query
$query =    'SELECT
                p.item_name,
                p.item_name,
                p.item_code,
                p.item_name,';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $query .=' p.sr_srp,'; 
                }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
                    $query .=' p.vnd_srp,'; 
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
                ps.product_description,
                p.data_cgc,
                p.sub_category,
                p.category
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
            WHERE ';
                if(!isset($_GET['filter'])){
                    $query .= '
                            p.data_cgc IN("DCGC0043","DCGC0009","DCGC0014","DCGC0034","DCGC0011", "DCGC0078")
                            AND (p.retail > 0 OR p.sr_srp > 0)';
                }
                elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $query .= 'p.data_cgc IN("DCGC0043","DCGC0009","DCGC0014","DCGC0034","DCGC0011", "DCGC0078")
                        AND (p.sr_srp > 0)';
                }else{
                    $query .= 'p.data_cgc IN("DCGC0043","DCGC0009","DCGC0014","DCGC0034","DCGC0011", "DCGC0078")
                        AND ( p.retail > 0)';
                }

                if(isset($_GET['page']) && $_GET['page'] == 'select-store-all'){
                    $query .= ' GROUP BY p.data_cgc,p.sub_category, p.category ';
                }  
                else{
                    $query .=$priority;
                }
                
            $query .='ORDER BY 
                p.item_name ASC;';

                // OR p.item_name LIKE "%KIDS CAP%"
                // OR p.item_name LIKE "%CLASSICS CAP%"
                // OR p.item_name LIKE "%ADULT CAP%"
                // OR p.item_code like "KLB00%"
                // OR p.item_code like  "MSEP%"


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
    "product_description",
    "data_cgc",
    "sub_category",
    "category"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10 , $result11, $result12, $result13);

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
    $arrProductsSorted[$curStyle]["data_cgc"]     = $arrProduct[$i]["data_cgc"];
    $arrProductsSorted[$curStyle]["sub_category"]     = $arrProduct[$i]["sub_category"];
    $arrProductsSorted[$curStyle]["category"]     = $arrProduct[$i]["category"];
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

    // Push to Sorted Array
    $arrMerchSorted[$itemID]['item_description']    = strtoupper($itemID);
    $arrMerchSorted[$itemID]['price']               = $curArr['price'];
    $arrMerchSorted[$itemID]['description']         = $curArr['description'];
    $arrMerchSorted[$itemID]['tags']                = $curArr['tags'];
    $arrMerchSorted[$itemID]['color_picker']        = $curArr['color_picker'];
    $arrMerchSorted[$itemID]['product_description'] = $curArr['product_description'];

    // Push color
    $arrMerchSorted[$itemID]['colors'][$i] = $curArr['colors'][0];    
    $arrMerchSorted[$itemID]['colors'][$i]['data_cgc'] = $curArr["data_cgc"];
    $arrMerchSorted[$itemID]['colors'][$i]['sub_category'] = $curArr["sub_category"];
    $arrMerchSorted[$itemID]['colors'][$i]['category'] = $curArr["category"];
    $arrMerchSorted[$itemID]['colors'][$i]['price'] = $curArr['price'];
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
// print_r($arrMerchSorted); exit;

?>


