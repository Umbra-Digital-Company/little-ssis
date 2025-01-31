<?php 

if(!isset($_SESSION)){ session_start(); }

//////////////////////////////////////////////////// GRAB FRAMES

// Set array
$arrFrames = array();
if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
    $priceWhere ='AND p.sr_price > 0'; 
}elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
    $priceWhere ='AND p.vnd_srp > 0'; 
}else{
    $priceWhere ='AND p.price > 0';
}

// Set query
$querypn =  'SELECT DISTINCT
                LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style",';
        if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
            $querypn .=' p.sr_price'; 
        }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
            $querypn .=' p.vnd_srp'; 
        }else{
            $querypn .=' p.price';
        }
$querypn .=' FROM 
                poll_51_studios_new p
            WHERE
                 p.data_cgc IN("DCGC0003")
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

$arrColorData = [];
    $queryAll = 'SELECT DISTINCT
                    ps.color_picker,
                    LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color"
                    FROM
                        poll_51_studios_new p
                        INNER JOIN products_colors_studios ps ON ps.product_code = p.product_code
                    WHERE 
                         p.data_cgc IN("DCGC0003")
                     ORDER BY grab_color ASC;
                    ';

    $grabParams = array(
        'color_picker',
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
            if($tempArray['color_picker'] != '' && $tempArray['color_picker'] != null){
                $tempColor = explode('#',$tempArray['color_picker']);
                $tempColor = explode(';',$tempColor[1]);
                $tempArray['color_picker'] = $tempColor[0];
            }
            $arrColorData[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);
    }


//////////////////////////////////////////////////// HARD CODED COLOR FILTER ARRAY

$arrFilterColors = array(
    array(
        "color" => "black",
        "color_picker" => "#000"
    ),
    array(
        "color" => "brown",
        "color_picker" => "#000"
    ),
    array(
        "color" => "tort",
        "color_picker" => "#000"
    ),
    array(
        "color" => "white",
        "color_picker" => "#000"
    ),
    array(
        "color" => "pink",
        "color_picker" => "#000"
    ),
    array(
        "color" => "blue",
        "color_picker" => "#000"
    ),
    array(
        "color" => "green",
        "color_picker" => "#000"
    ),
    array(
        "color" => "yellow",
        "color_picker" => "#000"
    ),
    array(
        "color" => "gold",
        "color_picker" => "#000"
    ),
    array(
        "color" => "silver",
        "color_picker" => "#000"
    ),
    array(
        "color" => "clear",
        "color_picker" => "#000"
    ),
    array(
        "color" => "red",
        "color_picker" => "#000"
    ),
    array(
        "color" => "grey",
        "color_picker" => "#000"
    ),
    array(
        "color" => "rose_gold",
        "color_picker" => "#000"
    ),
    array(
        "color" => "purple",
        "color_picker" => "#000"
    ),
    array(
        "color" => "nude",
        "color_picker" => "#000"
    )
);

$arrShapesData = [];
    $queryAll = 'SELECT
                    DISTINCT
                    pcs.code,
                    pcs.name
                    FROM poll_51_studios_new p
                        INNER JOIN poll_51_shapes pcs ON p.shape = pcs.code
                            WHERE p.data_cgc IN("DCGC0003")
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
                    FROM poll_51_studios_new p
                        INNER JOIN poll_51_collections pcs ON p.collection = pcs.code
                        WHERE  p.data_cgc IN("DCGC0003")
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



    // print_r($arrColorData); exit;
//grab default priority products on first load
$priority = (isset($_GET['search']) && $_GET['search'] !='') ? ' AND (p.item_name LIKE "%'.mysqli_real_escape_String($conn,$_GET['search']).'%" OR p.product_code LIKE "%'.mysqli_real_escape_String($conn,$_GET['search']).'%")' : '';
$getColors = [];
$getShapes = [];
$getCollections = [];
if(!isset($_GET['filter'])){
    //get priority settings
    $arrPriority = array();

    $queryAll = "SELECT
                    p51.item_name,
                    p51.product_code
                     FROM 
                     studios_settings ss
                     LEFT JOIN poll_51_studios_new p51 ON p51.product_code = ss.product_code
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

            $arrPriority[] = $tempArray['product_code'];

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);
    }

        $priority = ' AND p.product_code IN ("'.implode('","', $arrPriority).'")';

}else{
    //get filter
    if(isset($_GET['colors']) && $_GET['colors'] != ''){
        $arrPriority = array();
        $getColors = explode(",",mysqli_real_escape_String($conn,$_GET['colors']));
        $row = 0;
        $queryColor = '';
        foreach ($getColors as $value) {
            $or = ($row > 0) ? ' OR' : '';
            $queryColor .= $or. ' pcs.'.$value.' = "y"';
            $row++;
        }

        $priority .= ' AND ('.$queryColor.')';
        
    }

    if(isset($_GET['shapes'])  && $_GET['shapes'] != ''){
        $getShapes = explode(",",mysqli_real_escape_String($conn,$_GET['shapes']));   
        $priority .= ' AND p.shape IN ("'.implode('","', $getShapes).'")';
    }

    if(isset($_GET['collections'])  && $_GET['collections'] != ''){
        $getCollections = explode(",",mysqli_real_escape_String($conn,$_GET['collections']));   
        $priority .= ' AND p.collection IN ("'.implode('","', $getCollections).'")';
    }
}


//////////////////////////////////////////////////// GRAB PRODUCTS

// Set array
$arrProduct = array();

// Set query
$query =    'SELECT
                p.item_name,
                LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color",
                p.product_code,
                LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style" ,';
                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                    $query .=' p.sr_price,'; 
                }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
                    $query .=' p.vnd_srp,'; 
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
                p.general_color,
                IF(
                    psc.hex_code IS NOT NULL,
                    psc.hex_code,
                    IF(
                        pcs.color_picker IS NOT NULL,
                        REPLACE(REPLACE(pcs.color_picker, "background-color: ", ""), ";", ""),
                        pgc.filter_hex_code
                    )                    
                ),
                ps.product_description
            FROM
                poll_51_studios_new p
                    LEFT JOIN poll_51_shopify_data s
                        ON s.product_code = p.product_code
                    LEFT JOIN products_colors_studios pcs
                        ON p.product_code = pcs.product_code
                    LEFT JOIN products_studios ps
                        ON p.product_code = ps.product_code
                    LEFT JOIN poll_51_image_studios img
                        ON img.product_code = p.product_code
                    LEFT JOIN poll_51_general_colors pgc
                        ON pgc.code = p.general_color
                    LEFT JOIN poll_51_shopify_colors psc
                        ON psc.name = s.color_name
            WHERE
                p.data_cgc IN("DCGC0003")
                    '.$priority.'
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
    "color_code",
    "color_swatch",
    "product_description"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

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


//check if the product have a stock
// include $sDocRoot.'/inventory/aimpi/aimpi_studios_v1.php';
// $arrStockRunning = [];

// for($i = 0; $i < count($arrProduct); $i++){


//     $dateStart = date('Y-m-d');
//     $dateEnd= date('Y-m-t');

//     $FrameData= array();
//     $FrameData= storeChecker_smr($arrProduct[$i]['product_code'],$_SESSION['user_login']['store_code'],$dateStart,$dateEnd);


//     $store_id = $_SESSION['user_login']['store_code'];
//     $frame_code = $arrProduct[$i]['product_code'];

//        // print_r($FrameData); exit;
//     $current_total_stock = GetStock($arrProduct[$i]['product_code']);
//     if( $current_total_stock > 0){
//        $arrStockRunning[$arrProduct[$i]['product_code']] = $current_total_stock;
//     }else{
//         $arrStockRunning[$arrProduct[$i]['product_code']] = 0;
//     }

// }

//end check

// echo date('Y-m-d H:i:s');
// print_r($arrStockRunning);

//////////////////////////////////////////////////// SORT PRODUCTS

$arrProductsSorted = array();

// Loop through initial product array
for ($i=0; $i < sizeOf($arrProduct); $i++) { 
    // if($arrStockRunning[$arrProduct[$i]['product_code']] == 0){
    //     continue;
    // }
    // Set current data
    $curStyle            = $arrProduct[$i]["item_description"];
    $curPrice            = $arrProduct[$i]["price"];
    $curDescription      = strip_tags($arrProduct[$i]["main_description"]);
    $curTags             = $arrProduct[$i]["tags"];
    $color_picker        = $arrProduct[$i]["color_picker"];
    $color_code          = $arrProduct[$i]["color_code"];
    $color_swatch        = $arrProduct[$i]["color_swatch"];
    $product_description = $arrProduct[$i]["product_description"];

    // Push to sorted array
    $arrProductsSorted[$curStyle]["item_description"]    = $curStyle;
    $arrProductsSorted[$curStyle]["price"]               = $curPrice;
    $arrProductsSorted[$curStyle]["description"]         = $curDescription;
    $arrProductsSorted[$curStyle]["tags"]                = $curTags;
    $arrProductsSorted[$curStyle]["color_picker"]        = $color_picker;
    $arrProductsSorted[$curStyle]["color_code"]          = $color_code;
    $arrProductsSorted[$curStyle]["color_swatch"]        = $color_swatch;
    $arrProductsSorted[$curStyle]["product_description"] = $product_description;
    $arrProductsSorted[$curStyle]["colors"]              = array();

};

// Loop through initial product array to sort out colors
for ($i=0; $i < sizeOf($arrProduct); $i++) { 
    // if($arrStockRunning[$arrProduct[$i]['product_code']] == 0){
    //     continue;
    // }
    // Set current data
    $curStyle = $arrProduct[$i]["item_description"];
    $curPrice = $arrProduct[$i]["price"];
    $curColor = $arrProduct[$i]["color"];
    $curSKU   = $arrProduct[$i]["product_code"];
    $curImage = $arrProduct[$i]["image_url"];
    $color_picker = $arrProduct[$i]["color_picker"];
    $color_code   = $arrProduct[$i]["color_code"];
    $color_swatch = $arrProduct[$i]["color_swatch"];
    $product_description = $arrProduct[$i]["product_description"];

    // Push to sorted array color array
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["product_code"] = $curSKU;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["price"]        = $curPrice;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["color"]        = $curColor;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["image"]        = $curImage;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["color_picker"] = $color_picker;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["color_code"]   = $color_code;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["color_swatch"] = $color_swatch;
    $arrProductsSorted[$curStyle]["colors"][$curSKU]["product_description"] = $product_description;

};

// Reindex sorted array
$arrProductsSorted = array_values($arrProductsSorted);

// Loop through and reindex color arrays
for ($i=0; $i < sizeOf($arrProductsSorted); $i++) { 
  
    // Reindex color array
    $arrProductsSorted[$i]["colors"] = array_values($arrProductsSorted[$i]["colors"]);

};

//////////////////////////////////////////////////// FUNCTIONS

function trimColor($color_name) {


    // Remove abbreviations and classes
    $color_name =         
        str_replace("/", " ",
        str_replace("blk", "black",
            str_replace("brown lns", "",
        str_replace("brn", "brown",        
        str_replace("mt", "m",
        str_replace("matte", "m",
        str_replace("flt", "f",
        str_replace("lens", "",
        str_replace("flat", "f",
        str_replace("grn", "",
        str_replace("gdt", "",
        str_replace("/crml", "",
            trim($color_name)
        ))))))))))));

    return $color_name;

};

function setPoNumberAdd($order_no_Cart){

    global $conn;

    $arrCartQF = array();

    $arrCartQuery = 'SELECT  
                        COUNT(pr.product_code) as count,
                        GROUP_CONCAT( os.orders_specs_id)  as group_orders_specs_id,
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
                        }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
                            $arrCartQuery .=' pr.vnd_srp,'; 
                        }else{
                            $arrCartQuery .='  pr.price,';
                        }

$arrCartQuery .=       ' pr.product_code,
                        os.product_upgrade,
                        os.product_code AS product_code_order,
                        IF(
                            s.image_url IS NOT NULL,
                            s.image_url,
                            img.image_url
                        ),
                        os.dispatch_type
                    FROM 
                        profiles_info p
                            LEFT JOIN orders_sunnies_studios os 
                                ON os.profile_id = p.profile_id
                            LEFT JOIN poll_51_studios_new pr 
                                 ON (pr.product_code = os.product_code OR pr.product_code = os.product_upgrade) AND pr.item_code!="LENS001"
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
        "image_url",
        "dispatch_type"
    );
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14);

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

?>


