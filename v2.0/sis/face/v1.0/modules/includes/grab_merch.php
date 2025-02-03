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
            WHERE ';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $querypn .= 'p.data_cgc IN("DCGC0006","DCGC0023","DCGC0045","DCGC0046","DCGC0054")
                        AND  p.sr_srp > 0';
                }else{
                     $querypn .= 'p.data_cgc IN("DCGC0006","DCGC0023","DCGC0045","DCGC0046","DCGC0054")
                        AND  p.retail > 0 ';
                }
                $querypn .=' ORDER BY 
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
            WHERE ';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $query .= 'p.data_cgc IN("DCGC0006","DCGC0023","DCGC0045","DCGC0046","DCGC0054")
                        AND (p.sr_srp > 0)';
                }else{
                    $query .= 'p.data_cgc IN("DCGC0006","DCGC0023","DCGC0045","DCGC0046","DCGC0054")
                        AND ( p.retail > 0)';
                }
                    
                $query .=$priority.'
            ORDER BY 
                p.item_name ASC;';
// echo $query; exit;
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
        // $tempArray['price'] = ($tempArray['price'] == '' || $tempArray['price'] == '0') '0' : $tempArray['price'];
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
    $curPrice       = ($arrProduct[$i]["price"] == '' || $arrProduct[$i]["price"] == 0) ? 0 : $arrProduct[$i]["price"];
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

     //sunnies gc
    if(strpos($curItemDescription, 'sunnies-gc') !== false) {

        $itemID = 'gift card';

    }

    if(strpos($curItemDescription, 'eyecrayon') !== false) {

        $itemID = 'eyecrayon';
        $curArr['colors'][0]['color'] = str_replace("EYECRAYON ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'airblush') !== false) {

        $itemID = 'airblush';
        $curArr['colors'][0]['color'] = str_replace("AIRBLUSH ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'dream cream') !== false) {

        $itemID = 'dream cream';
        $curArr['colors'][0]['color'] = str_replace("DREAM CREAM ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'fluffmatte') !== false) {

        $itemID = 'fluffmatte';
        $curArr['colors'][0]['color'] = str_replace("FLUFFMATTE ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'lip treat') !== false) {

        $itemID = 'lip treat';
        $curArr['colors'][0]['color'] = str_replace("LIP TREAT ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'skin so good') !== false) {

        $itemID = 'skin so good';
        $curArr['colors'][0]['color'] = str_replace("SKIN SO GOOD ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'lip dip') !== false) {

        $itemID = 'lip dip';
        $curArr['colors'][0]['color'] = str_replace("LIP DIP ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'play paint') !== false) {

        $itemID = 'play paint';
        $curArr['colors'][0]['color'] = str_replace("PLAY PAINT ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'sunnies face') !== false) {

        $itemID = 'sunnies face';
        $curArr['colors'][0]['color'] = str_replace("SUNNIES FACE ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'cup point') !== false) {

        $itemID = 'cup point';
        $curArr['colors'][0]['color'] = str_replace("CUP POINT ", "", $curArr['colors'][0]['color']);

    }

     //Kids cap
    if(strpos($curItemDescription, 'kids cap') !== false) {

        $itemID = 'sunnies studios kids cap';
        $curArr['colors'][0]['color'] = str_replace("SUNNIES STUDIOS KIDS CAP ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'teddy laptop sleeve') !== false) {

        $itemID = 'teddy laptop sleeve';
        $curArr['colors'][0]['color'] = str_replace("TEDDY LAPTOP SLEEVE ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, '2023 summer t-shirt') !== false) {

        $itemID = '2023 summer t-shirt';
        $curArr['colors'][0]['color'] = str_replace("2023 SUMMER T-SHIRT ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'duo sac') !== false) {

        $itemID = 'duo sac';
        $curArr['colors'][0]['color'] = str_replace("DUO SAC ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'slim sac') !== false) {

        $itemID = 'slim sac';
        $curArr['colors'][0]['color'] = str_replace("SLIM SAC ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'sun sac') !== false) {

        $itemID = 'sun sac';
        $curArr['colors'][0]['color'] = str_replace("SUN SAC ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'scarf') !== false) {

        $itemID = '2023 SUMMER SCARF';
        $curArr['colors'][0]['color'] = str_replace("SUMMER SCARF", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, "valentines day") !== false) {

        $itemID = "valentines day";
        $curArr['colors'][0]['color'] = str_replace("VALENTINES DAY ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, "web sticker") !== false) {

        $itemID = "web sticker";
        $curArr['colors'][0]['color'] = str_replace("WEB STICKER ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'sustainable cleanlens') !== false) {

        $itemID = 'sustainable cleanlens';
        $curArr['colors'][0]['color'] = str_replace("SUSTAINABLE CLEANLENS ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'sbc') !== false) {

        $itemID = 'sbc';
        $curArr['colors'][0]['color'] = str_replace("SBC ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'sbs') !== false) {

        $itemID = 'sbs';
        $curArr['colors'][0]['color'] = str_replace("SBS ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'holiday staples') !== false) {

        $itemID = 'holiday staples';
        $curArr['colors'][0]['color'] = str_replace("HOLIDAY STAPLES ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'on the daily kit') !== false) {

        $itemID = 'on the daily kit';
        $curArr['colors'][0]['color'] = str_replace("ON THE DAILY KIT ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'team sunnies') !== false) {

        $itemID = 'team sunnies';
        $curArr['colors'][0]['color'] = str_replace("TEAM SUNNIES ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'holiday 2022') !== false) {

        $itemID = 'holiday 2022';
        $curArr['colors'][0]['color'] = str_replace("HOLIDAY 2022 ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'holiday tag') !== false) {

        $itemID = 'holiday tag';
        $curArr['colors'][0]['color'] = str_replace("HOLIDAY TAG ", "", $curArr['colors'][0]['color']);

    }


    //sunnies studios nalgene
    if(strpos($curItemDescription, 'sunnies studios nalgene') !== false) {

        $itemID = 'sunnies studios nalgene';
        $curArr['colors'][0]['color'] = str_replace("SUNNIES STUDIOS NALGENE ", "", $curArr['colors'][0]['color']);

    }

    if(strpos($curItemDescription, 'kids lunchbox') !== false) {

        $itemID = 'kids lunchbox';
        $curArr['colors'][0]['color'] = str_replace("KIDS LUNCHBOX ", "", $curArr['colors'][0]['color']);

    }

   
    

    //adult cap
    if(strpos($curItemDescription, 'adult cap') !== false) {

        $itemID = 'sunnies studios adult cap';
        $curArr['colors'][0]['color'] = str_replace("SUNNIES STUDIOS ADULT CAP ", "", $curArr['colors'][0]['color']);

    }

    //classic cap
    if(strpos($curItemDescription, 'classics cap') !== false) {

        $itemID = 'offbeat classics cap';
        $curArr['colors'][0]['color'] = str_replace("OFFBEAT CLASSICS CAP ", "", $curArr['colors'][0]['color']);

    }

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
    // World of Welness
    if(strpos($curItemDescription, 'world of wellness') !== false) {

        $itemID = 'world of wellness';
        $curArr['colors'][0]['color'] = str_replace("WORLD OF WELLNESS ", "", $curArr['colors'][0]['color']);

    };

    // Planners
    if(strpos($curItemDescription, 'planner') !== false) {

        $itemID = 'planner';
        $curArr['colors'][0]['color'] = str_replace(" AGENDA PLANNER", "", $curArr['colors'][0]['color']);
        $curArr['colors'][0]['color'] = str_replace("SUNNIES AGENDA ", "", $curArr['colors'][0]['color']);

    };

    if(strpos($curItemDescription, 'eyewear pouch') !== false) {

        $itemID = 'pouch';
        $curArr['colors'][0]['color'] = str_replace(" eyewear pouch", "", $curArr['colors'][0]['color']);
     
    };

    if(strpos($curItemDescription, 'tote') !== false) {

        $itemID = 'bag';
        $curArr['colors'][0]['color'] = str_replace(" tote bag", "", $curArr['colors'][0]['color']);
     
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
// print_r($arrMerchSorted);
// echo '</pre>';
// exit;

include 'grab_cart.php';

//print_r($arrCart); exit;

?>


