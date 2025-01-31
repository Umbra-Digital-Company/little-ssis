<?php 

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////// GRAB PRODUCTS

// Set array
$arrProduct = array();

// Set query
$query =    'SELECT
                p.item_name,
                LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color",
                p.product_code,
                LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style",
                p.price,     
                IF(
                    s.image_url IS NOT NULL,
                    s.image_url,
                    "/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png"
                ),
                img.image_url,
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
                    AND img.image_url IS NULL                    ';

$query .= ' ORDER BY p.item_name ASC;';

$grabParams = array(
    "description", 
    "color", 
    "product_code", 
    "item_description",
    "price", 
    "image_url", 
    "db_image_url", 
    "main_description", 
    "tags",
    "color_picker",
    "product_description"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

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

//////////////////////////////////////////////// CHECK IMAGES

// Cycle through array
// for ($i=0; $i < sizeOf($arrProduct); $i++) { 
  
//     // Set curent data
//     $curProductCode = $arrProduct[$i]["product_code"];
//     $curImageURL    = $arrProduct[$i]["image_url"];
//     $curDBImageURL  = $arrProduct[$i]["db_image_url"];
//     $curStyle       = trim($arrProduct[$i]["item_description"]);
//     $curColor       = str_replace("-gdt", "-g", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($arrProduct[$i]["color"])))));     

//     if($curImageURL == '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png') {

//         $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';
//         $checkImage = (@getimagesize($curImageURL)) ?  $curImageURL : '/v2.0/sis/studios/assets/images/defaults/no_specs_frame_available_b.png';

//     }
//     else {

//         $checkImage = $curImageURL;

//     };

//     // Set current checked image
//     $arrProduct[$i]["image_url"] = $checkImage;

//     //////////////////////////////////////////////// UPDATE IMAGES IN DB

//     // Set query
//     $query  =   "INSERT INTO 
//                     poll_51_image_studios (
//                         product_code2,
//                         product_code,
//                         image_url
//                     ) 
//                 VALUES (
//                     '".$curProductCode."',
//                     '".$curProductCode."',
//                     '".$checkImage."'
//                 )
//                 ON DUPLICATE KEY UPDATE
//                     product_code2 = VALUES(product_code2),
//                     product_code = VALUES(product_code),
//                     image_url = VALUES(image_url);";

//     $stmt = mysqli_stmt_init($conn);
//     if (mysqli_stmt_prepare($stmt, $query)) {

//         mysqli_stmt_execute($stmt);     
//         mysqli_stmt_close($stmt);       

//     }
//     else {

//         echo mysqli_error($conn);
//         exit;
//     };

// };

// echo '<pre>';
// print_r($arrProduct);
// echo '</pre>';
exit;

//////////////////////////////////////////////// SORT PRODUCTS

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
exit;

?>