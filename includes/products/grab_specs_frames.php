<?php
	
$arrSpecs = array();

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
                    '/images/specs/no-image/no_specs_frame_available_b.png'
                ) as 'image_url',                
                s.code,
                LOWER(s.name),
                m.code,
                LOWER(m.name),
                gc.code,
                LOWER(gc.name),
                f.code,
                LOWER(f.name),
                ps.status,
                inv.stock
            FROM 
                poll_51 p51
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
                    LEFT JOIN poll_51_status ps
                        ON ps.product_code = p51.product_code
                    LEFT JOIN poll_51_image_urls img
                        ON img.product_code = p51.product_code
                    LEFT JOIN poll_51_inventory inv
                        ON inv.product_code = p51.product_code
            WHERE 
                p51.price = '1200'
                    AND p51.product_code NOT LIKE 'SGC%'
                    AND p51.product_code NOT LIKE 'AC%'
                    AND p51.product_code NOT LIKE 'HS%'
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
    "status",
    "stock"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($arrParams); $i++) { 

            $tempArray[$arrParams[$i]] = ${'result' . ($i+1)};

        };

        $arrSpecs[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

//////////////////////////////////////////////////// SORT ITEMS

// $arrItemNamesInit = array();
// $arrItemNames = array();
// $arrSpecsFrames = array();

// // Grab all item descriptions
// for ($i=0; $i < sizeOf($arrSpecs); $i++) { 
  
//     // Set current data
//     $curItemName = $arrSpecs[$i]['item_description'];

//     if($curItemName != '') {

//         // Push to names array
//         array_push($arrItemNamesInit, $curItemName);

//     };

// };

// // Remove duplicates
// $arrItemNames = array_values(array_unique($arrItemNamesInit));

// // Cycle through and insert into sorted array
// for ($i=0; $i < sizeOf($arrItemNames); $i++) { 

//     // Set current data
//     $curItemName = $arrItemNames[$i];
  
//     $arrSpecsFrames[$curItemName] = array();

// };

// // Cycle through and insert into sorted array
// for ($i=0; $i < sizeOf($arrSpecs); $i++) { 

//     // Set current data
//     $curItemName = $arrSpecs[$i]['item_description'];

//     if($curItemName != NULL) {

//         // Insert into array
//         array_push($arrSpecsFrames[$curItemName], $arrSpecs[$i]);

//     }

// };

//////////////////////////////////////////////////// ADD IMAGE URLS

// // Cycle through the frames to see which images exist
// for ($i=0; $i < sizeOf($arrSpecs); $i++) { 

//     // Set current data
//     $style = $arrSpecs[$i]['item_description'];
//     $color = str_replace(' ', '-', trim($arrSpecs[$i]['color']));

//     // Set up images
//     $img = "/images/specs/".$style."/".$color."/front.png";
//     $img_placeholder = "/images/specs/no-image/no_specs_frame_available_b.png";
//     $img_thumbnail = file_exists($_SERVER["DOCUMENT_ROOT"] . $img) ? $img : $img_placeholder;

//     $arrSpecs[$i]['image_url'] = $img_thumbnail;

// };

?>
