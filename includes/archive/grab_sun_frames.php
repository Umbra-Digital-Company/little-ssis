<?php
	
//////////////////////////////////////////////////// GRAB POST DATA

// Filter lines in Query
$query_colors    = "";
$query_styles    = "";
$query_widths    = "";
$query_materials = "";
$query_finishes  = "";

if(isset($_POST) && !empty($_POST)) {     

    ///// Cycle through filters

    // COLORS
    if(isset($_POST['filterColors'])) {

        // Set array
        $arrFilterColors = $_POST['filterColors'];

        // Set query line
        $queryColor = "AND gc.code IN (";

        // Cycle through array
        for ($i=0; $i < sizeOf($arrFilterColors); $i++) { 
            
            $queryColor .= "'".$arrFilterColors[$i]."'";

            if($i < (sizeOf($arrFilterColors)-1)) {

                $queryColor .= ",";

            }

        };

        // End query line
        $queryColor .= ")";

        // Add to main query line
        $query_colors = $queryColor;

    }
    else {

        $query_colors .= "";

    };

    // STYLES
    if(isset($_POST['filterStyles'])) {

        // Set array
        $arrFilterStyles = $_POST['filterStyles'];

        // Set query line
        $queryStyles = "AND s.code IN (";

        // Cycle through array
        for ($i=0; $i < sizeOf($arrFilterStyles); $i++) { 
            
            $queryStyles .= "'".$arrFilterStyles[$i]."'";

            if($i < (sizeOf($arrFilterStyles)-1)) {

                $queryStyles .= ",";

            }

        };

        // End query line
        $queryStyles .= ")";

        // Add to main query line
        $query_styles = $queryStyles;

    }
    else {

        $query_styles .= "";

    };

    // WIDTHS
    if(isset($_POST['filterWidths'])) {

        // Set array
        $arrFilterWidths = $_POST['filterWidths'];

        // Set query line
        $queryWidths = "AND w.code IN (";

        // Cycle through array
        for ($i=0; $i < sizeOf($arrFilterWidths); $i++) { 
            
            $queryWidths .= "'".$arrFilterWidths[$i]."'";

            if($i < (sizeOf($arrFilterWidths)-1)) {

                $queryWidths .= ",";

            }

        };

        // End query line
        $queryWidths .= ")";

        // Add to main query line
        $query_widths = $queryWidths;

    }
    else {

        $query_widths .= "";

    };

    // MATERIALS
    if(isset($_POST['filterMaterials'])) {

        // Set array
        $arrFilterMaterials = $_POST['filterMaterials'];

        // Set query line
        $queryMaterials = "AND m.code IN (";

        // Cycle through array
        for ($i=0; $i < sizeOf($arrFilterMaterials); $i++) { 
            
            $queryMaterials .= "'".$arrFilterMaterials[$i]."'";

            if($i < (sizeOf($arrFilterMaterials)-1)) {

                $queryMaterials .= ",";

            }

        };

        // End query line
        $queryMaterials .= ")";

        // Add to main query line
        $query_materials = $queryMaterials;

    }
    else {

        $query_materials .= "";

    };

    // FINISHES
    if(isset($_POST['filterFinishes'])) {

        // Set array
        $arrFilterFinishes = $_POST['filterFinishes'];

        // Set query line
        $queryFinishes = "AND f.code IN (";

        // Cycle through array
        for ($i=0; $i < sizeOf($arrFilterFinishes); $i++) { 
            
            $queryFinishes .= "'".$arrFilterFinishes[$i]."'";

            if($i < (sizeOf($arrFilterFinishes)-1)) {

                $queryFinishes .= ",";

            }

        };

        // End query line
        $queryFinishes .= ")";

        // Add to main query line
        $query_finishes = $queryFinishes;

    }
    else {

        $query_finishes .= "";

    };

}  

//////////////////////////////////////////////////// SET QUERY

$arrSun = array();

$query =    "SELECT
                LOWER(TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1))) AS 'grab_style' ,
                LOWER(REPLACE(item_name,  TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1)), '')) AS 'grab_color',
                p51.item_name,
                p51.product_code,
                p51.price,            
                pc.color_picker,
                IF(
                    img.image_url IS NOT NULL,
                    img.image_url,
                    IF(
                        shopify_img.image_url IS NOT NULL,
                        shopify_img.image_url,
                        '/images/specs/no-image/no_specs_frame_available_b.png'
                    )                       
                ) as 'image_url',                
                s.code,
                LOWER(s.name),
                m.code,
                LOWER(m.name),
                gc.code,
                LOWER(gc.name),
                f.code,
                LOWER(f.name),
                w.code,
                LOWER(w.name),
                ps.status,
                inv.stock,
                arch.general_color_name,
                arch.color_swatch_shopify,
                arch.color_swatch_alpha,
                arch.color_swatch_new
            FROM 
                poll_51_studios p51
                    LEFT JOIN products_colors pc
                        ON pc.product_code = p51.product_code
                    LEFT JOIN poll_51_shapes s
                        ON s.code = p51.shape
                    LEFT JOIN poll_51_materials m
                        ON m.code = p51.material
                    LEFT JOIN poll_51_general_colors gc
                        ON gc.code = p51.general_color
                    LEFT JOIN poll_51_finish f
                        ON f.code = p51.finish
                    LEFT JOIN poll_51_sizes w
                        ON w.code = p51.size
                    LEFT JOIN poll_51_status ps
                        ON ps.product_code = p51.product_code                    
                    LEFT JOIN poll_51_image_studios img
                        ON img.product_code = p51.product_code  
                    LEFT JOIN poll_51_shopify_data shopify_img
                        ON shopify_img.product_code = p51.product_code
                    LEFT JOIN poll_51_inventory inv
                        ON inv.product_code = p51.product_code
                    LEFT JOIN the_archive_studios arch
                        ON arch.product_code = p51.product_code
            WHERE 
                p51.product_code NOT LIKE '%AC%'
                    AND p51.product_code NOT LIKE '%C%'
                    AND p51.product_code NOT LIKE '%PL%'
                    ##AND p51.product_code NOT LIKE '%P%'
                    AND p51.product_code NOT LIKE '%H%'
                    AND p51.product_code NOT LIKE '%SC%'
                    AND p51.product_code NOT LIKE '%SGC%'
                    AND p51.product_code NOT LIKE '%SCL%'
                    AND p51.product_code NOT LIKE '%SW%'
                    AND p51.product_code NOT LIKE '%SS%'
                    AND p51.product_code NOT LIKE '%ST%'
                    AND p51.item_name NOT LIKE '%AGENDA%'
                    AND p51.product_code NOT LIKE '%AR%'
                    AND (p51.vnd_srp > 0 OR p51.price > 0 OR p51.sr_price > 0)
                    ".$query_colors."
                    ".$query_styles."
                    ".$query_widths."
                    ".$query_materials."
                    ".$query_finishes."
            ORDER BY
                grab_style ASC, p51.product_code ASC";

$arrParams= array(
    "item_description",
    "color",
    "full_item_description",
    "product_code",
    "price",    
    "color_picker",
    "image_url",
    "shapes_code",
    "shapes_name",
    "material_code",
    "material_name",
    "general_color_code",
    "general_color_name",
    "finish_code",
    "finish_name",
    "width_code",
    "width_name",
    "status",
    "stock",
    "general_color_name",
    "color_swatch_shopify",
    "color_swatch_alpha",
    "color_swatch_new"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($arrParams); $i++) { 

            $tempArray[$arrParams[$i]] = ${'result' . ($i+1)};

        };

        $arrSun[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

//////////////////////////////////////////////////// SET FILTER ARRAYS

$arrColors    = array();
$arrShapes    = array();
$arrWidths    = array();
$arrMaterials = array();
$arrFinishes  = array();

//////////////////////////////////////////////////// SORT ITEMS

$arrItemNamesInit = array();
$arrItemNames = array();
$arrSunFrames = array();

// Grab all item descriptions
for ($i=0; $i < sizeOf($arrSun); $i++) { 
  
    // Set current data
    $curItemName     = $arrSun[$i]['item_description'];
    $curColor        = $arrSun[$i]['general_color_name'];
    $curColorCode    = $arrSun[$i]['general_color_code'];
    $curShape        = $arrSun[$i]['shapes_name'];
    $curShapeCode    = $arrSun[$i]['shapes_code'];
    $curWidth        = $arrSun[$i]['width_name'];
    $curWidthCode    = $arrSun[$i]['width_code'];
    $curMaterial     = $arrSun[$i]['material_name'];
    $curMaterialCode = $arrSun[$i]['material_code'];
    $curFinish       = $arrSun[$i]['finish_name'];
    $curFinishCode   = $arrSun[$i]['finish_code'];

    if($curItemName != '') {        
        array_push($arrItemNamesInit, $curItemName);        
    };

    if($curColor != '') {        
        $arrColors[$curColor]['color_name'] = $curColor;
        $arrColors[$curColor]['color_code'] = $curColorCode;
    };

    if($curShape != '') {        
        $arrShapes[$curShape]['shapes_name'] = $curShape;
        $arrShapes[$curShape]['shapes_code'] = $curShapeCode;
    };

    if($curWidth != '') {        
        $arrWidths[$curWidth]['width_name'] = $curWidth;
        $arrWidths[$curWidth]['width_code'] = $curWidthCode;
    };

    if($curMaterial != '') {
        $arrMaterials[$curMaterial]['material_name'] = $curMaterial;
        $arrMaterials[$curMaterial]['material_code'] = $curMaterialCode;
    };

    if($curFinish != '') {
        $arrFinishes[$curFinish]['finish_name'] = $curFinish;
        $arrFinishes[$curFinish]['finish_code'] = $curFinishCode;
    };

};

// Remove duplicates
$arrItemNames = array_values(array_unique($arrItemNamesInit));

// Sort arrays
sort($arrColors);
sort($arrShapes);
sort($arrWidths);
sort($arrMaterials);
sort($arrFinishes);

// Cycle through and insert into sorted array
for ($i=0; $i < sizeOf($arrItemNames); $i++) { 

    // Set current data
    $curItemName = $arrItemNames[$i];
  
    $arrSunFrames[$curItemName] = array();

};

// Cycle through and insert into sorted array
for ($i=0; $i < sizeOf($arrSun); $i++) { 

    // Set current data
    $curItemName = $arrSun[$i]['item_description'];

    if($curItemName != NULL) {

        // Insert into array
        array_push($arrSunFrames[$curItemName], $arrSun[$i]);

    }

};

// echo '<pre>';
// print_r($arrSunFrames);
// echo '</pre>';
// exit;

?>
