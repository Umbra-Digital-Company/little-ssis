<?php   

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////// GRAB PRODUCTS

// Set array
$arrSun = array();

// Set query
$query =    'SELECT
                p.item_name,
                p.item_name,
                p.item_name,
                p.product_code,
                p.product_code2,
                p.price,
                REPLACE(REPLACE(pcs.color_picker, "background-color: ", ""), ";", ""),
                IF(
                    s.image_url IS NOT NULL,
                    s.image_url,
                    img.image_url
                ),
                pgc.code,
                LOWER(pgc.name),
                psc.name,                
                psc.hex_code
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
                    LEFT JOIN poll_51_general_colors pgc
                        ON pgc.code = p.general_color
                    LEFT JOIN poll_51_shopify_colors psc
                        ON psc.name = s.color_name
                    LEFT JOIN products_colors_studios pc
                        ON pc.product_code = p.product_code
            WHERE
                p.product_code NOT LIKE "%AC%"
                    AND p.product_code NOT LIKE "%C%"
                    AND p.product_code NOT LIKE "%PL%"
                    ##AND p.product_code NOT LIKE "%P%"
                    AND p.product_code NOT LIKE "%H%"
                    AND p.product_code NOT LIKE "%SC%"
                    AND p.product_code NOT LIKE "%SGC%"
                    AND p.product_code NOT LIKE "%SCL%"
                    AND p.product_code NOT LIKE "%SW%"
                    AND p.product_code NOT LIKE "%SS%"
                    AND p.product_code NOT LIKE "%ST%"
                    AND p.item_name NOT LIKE "%AGENDA%"
                    AND p.product_code NOT LIKE "%AR%"
                    AND (p.vnd_srp > 0 OR p.price > 0 OR p.sr_price > 0)                    
                ORDER BY 
                    p.item_name ASC;';

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

        $arrSun[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

// echo '<pre>';
// print_r($arrSun);
// echo '</pre>';
// exit;

//////////////////////////////////////////////////// UPDATE DATABASE

// Cycle through the colors
for ($i=0; $i < sizeOf($arrSun); $i++) { 

	// Set current data
	$curProductCode2  = $arrSun[$i]['product_code2'];
	$curProductCode   = $arrSun[$i]['product_code'];
	$curColorCode 	  = $arrSun[$i]['general_color_code'];
	$curGeneralColor  = $arrSun[$i]['general_color_name'];
	$curShopifyColor  = $arrSun[$i]['shopify_color_name'];
	$curShopifySwatch = $arrSun[$i]['color_swatch'];	
	$curColorPicker   = $arrSun[$i]['color_picker'];

	// Set query
	$query  =   "INSERT INTO 
					the_archive_studios (
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