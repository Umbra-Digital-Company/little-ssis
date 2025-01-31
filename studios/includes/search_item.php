<?php
	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	
	session_save_path($sDocRoot."/cgi-bin/tmp");
	session_start();
	require $sDocRoot."/includes/connect.php";

	if(!isset($_SESSION['user_login']['username'])) {
		header("Location: /");
		exit;
	}
	


$priority = (isset($_POST['search']) && $_POST['search'] !='') ? ' AND (p.item_name LIKE "%'.mysqli_real_escape_String($conn,$_POST['search']).'%" OR p.product_code LIKE "%'.mysqli_real_escape_String($conn,$_POST['search']).'%")' : '';

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
	                ps.product_description,
	                "studios"
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
	                p.stock IN ("115")
	                    '.$priority.'
	                ORDER BY 
	                    p.item_name ASC LIMIT 10;';
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
	    "product_description",
	    "product_class"
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13);

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

	$query =    'SELECT
	                p.item_name,
	                RIGHT(REPLACE(REPLACE(p.item_name, "ANTI-RADIATION (", ""), ")", ""), LENGTH(REPLACE(REPLACE(p.item_name, "ANTI-RADIATION (", ""), ")", ""))-LOCATE(" ", REPLACE(REPLACE(p.item_name, "ANTI-RADIATION (", ""), ")", ""))),
	                p.product_code,
	                LEFT(REPLACE(REPLACE(p.item_name, "ANTI-RADIATION (", ""), ")", ""),LOCATE(" ", REPLACE(REPLACE(p.item_name, "ANTI-RADIATION (", ""), ")", ""))),';
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
	                ps.product_description,
	                "antirad"
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
	                p.product_code LIKE "AR%"
	                    '.$priority.'
	                ORDER BY 
	                    p.item_name ASC LIMIT 10;';
	                    // AND p.product_code NOT LIKE "%i%"

	// echo '<pre>';
	// echo $query;
	// echo '</pre>';
	// exit;

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
	    "product_description",
	    "product_class"
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13);

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

	$query =    'SELECT
	                p.item_name,
	                p.item_name,
	                p.product_code,
	                p.item_name,';
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
	                ps.product_description,
	                "merch"
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
	            WHERE ';
	                if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
	                    $query .= '(p.item_name LIKE "%CLEAN LENS%"
	                        OR p.item_name LIKE "%CARE KIT%"
	                        OR p.item_name LIKE "%ADULT CAP%"
	                        OR p.item_name LIKE "%KIDS CAP%"
	                        OR p.item_name LIKE "%CLASSICS CAP%"
	                        OR p.product_code = "AFC002"
	                        OR p.product_code = "SM0001-1"
	                        OR p.product_code = "KLB00-01")
	                        AND p.product_code <> "PL0011-A"
	                        AND (p.sr_price > 0)';
	                }else{
	                    $query .= '(p.product_code LIKE "MC1016%"
	                        OR p.product_code LIKE "MC1015%"
	                        OR p.product_code LIKE "MGC%"
	                        OR p.product_code LIKE "MLBC%"
	                        OR p.product_code LIKE "MHC%"
	                        OR p.product_code LIKE "MH1007%"
	                        OR p.product_code LIKE "MH1008%"
	                        OR p.product_code LIKE "MCK%"
	                        OR p.product_code LIKE "MSPVHC%" 
	                        OR p.product_code LIKE "PL%"
	                        OR p.product_code LIKE "HC%"
	                        OR p.product_code LIKE "MSAC%"
	                        OR p.product_code LIKE "MSKC%"
	                        OR p.item_code = "H1001"
	                        OR p.product_code = "SM0001-1"
	                        OR p.product_code LIKE "%MSCL%"
	                        OR p.product_code = "AFC002"
	                        OR p.product_code = "KLB00-01")
	                        AND ( p.price > 0)';
	                }
	                    
	                $query .=$priority.'
	            ORDER BY 
	                p.item_name ASC LIMIT 10;';
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
	    "product_description",
	    "product_class"
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
	        // $tempArray['price'] = ($tempArray['price'] == '' || $tempArray['price'] == '0') '0' : $tempArray['price'];
	        $arrProduct[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

	echo json_encode($arrProduct);
?>