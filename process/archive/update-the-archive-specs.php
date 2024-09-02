<?php   

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////// GRAB PRODUCTS

// Set array
$arrSpecs = array();

// Set query
$query =    "SELECT
                LOWER(TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1))) AS 'grab_style' ,
                LOWER(REPLACE(item_name,  TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1)), '')) AS 'grab_color',
                p51.item_name,
                p51.product_code,
                p51.product_code2,
                p51.price,            
                pc.color_picker,
                IF(
                    img.image_url IS NOT NULL,
                    img.image_url,
                    IF(
                        shopify.image_url IS NOT NULL,
                        shopify.image_url,
                        '/images/specs/no-image/no_specs_frame_available_b.png'
                    )                       
                ) as 'image_url',                
                gc.code,
                LOWER(gc.name),
                psc.name,                
				psc.hex_code
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
                    LEFT JOIN poll_51_sizes w
                        ON w.code = p51.size
                    LEFT JOIN poll_51_status ps
                        ON ps.product_code = p51.product_code
                    LEFT JOIN poll_51_image_urls img
                        ON img.product_code = p51.product_code
                    LEFT JOIN poll_51_shopify_data shopify
                        ON shopify.product_code = p51.product_code
                    LEFT JOIN poll_51_inventory inv
                        ON inv.product_code = p51.product_code
                    LEFT JOIN poll_51_shopify_colors psc
                        ON psc.name = shopify.color_name
            WHERE 
                p51.price = '1999'
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
    "product_code2",
    "price",    
    "color_picker",
    "image_url",
    "general_color_code",
    "general_color_name",
    "shopify_color_name",
    "color_swatch"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

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

// echo '<pre>';
// print_r($arrSpecs);
// echo '</pre>';
// exit;

//////////////////////////////////////////////////// UPDATE DATABASE

// Cycle through the colors
for ($i=0; $i < sizeOf($arrSpecs); $i++) { 

	// Set current data
	$curProductCode2  = $arrSpecs[$i]['product_code2'];
	$curProductCode   = $arrSpecs[$i]['product_code'];
	$curColorCode 	  = $arrSpecs[$i]['general_color_code'];
	$curGeneralColor  = $arrSpecs[$i]['general_color_name'];
	$curShopifyColor  = $arrSpecs[$i]['shopify_color_name'];
	$curShopifySwatch = $arrSpecs[$i]['color_swatch'];	
	$curColorPicker   = $arrSpecs[$i]['color_picker'];

	// Set query
	$query  =   "INSERT INTO 
					the_archive_specs (
						product_code2,
						product_code,
						general_color,
						general_color_name,
						color_name_shopify,
						color_swatch_shopify,
						color_image_url_shopify,
						color_swatch_alpha
					) 
	            VALUES (
	            	'".mysqli_real_escape_string($conn, $curProductCode2)."',
	            	'".mysqli_real_escape_string($conn, $curProductCode)."',
	            	'".mysqli_real_escape_string($conn, $curColorCode)."',
	            	'".mysqli_real_escape_string($conn, $curGeneralColor)."',
	            	'".mysqli_real_escape_string($conn, $curShopifyColor)."',
	            	'".mysqli_real_escape_string($conn, $curShopifySwatch)."',
	            	'',
	            	'".mysqli_real_escape_string($conn, $curColorPicker)."'
	        	)
	        	ON DUPLICATE KEY UPDATE
	        		product_code2 = VALUES(product_code2),
	        		product_code = VALUES(product_code),
	        		general_color = VALUES(general_color),
	        		general_color_name = VALUES(general_color_name),
	        		color_name_shopify = VALUES(color_name_shopify),
	        		color_swatch_shopify = VALUES(color_swatch_shopify),
	        		color_image_url_shopify = VALUES(color_image_url_shopify),
	        		color_swatch_alpha = VALUES(color_swatch_alpha);";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);		
	    mysqli_stmt_close($stmt);		

	}
	else {

		echo mysqli_error($conn);
		exit;
		
	};
	
};

?>