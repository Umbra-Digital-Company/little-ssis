<?php   

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

exit;

//////////////////////////////////////////////// GRAB PRODUCTS

////////// SHOPIFY API

// Set headers
$headers    = array();
$headers[0] = 'X-Shopify-Storefront-Access-Token: 1c8d3be06323615f303c9b0ff09dbf29';
$headers[1] = 'Content-type: application/json';

// Authentication ENDPOINT | Optical+frame | Sun+Frame | Anti-Radiation+Frame | Merch
$url = "https://06f63a7fd0a5115c6d1b1694cb558579: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@sunniesstudios.myshopify.com/admin/api/2020-10/products.json?limit=250&product_type=Sun+Frame";

// Initiate cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

// Responses
$body = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cUrl
curl_close($ch);

$body_dec = json_decode($body, true);  
$arr_products = $body_dec["products"];
$arr_products_sorted = array();

// echo '<pre>';
// print_r($arr_products);
// echo '</pre>';
exit;

// Cycle through the products
for ($i=0; $i < sizeOf($arr_products); $i++) { 

	// Set current data
	$cur_product_type 		 = $arr_products[$i]["product_type"];
	$cur_product_description = $arr_products[$i]["body_html"];
	$cur_product_tags		 = $arr_products[$i]["tags"];
	$cur_variants 	  		 = $arr_products[$i]["variants"];
	$cur_images		  		 = $arr_products[$i]["images"];
	$cur_product_id 		 = $cur_variants[0]["product_id"];

	$arr_products_sorted[$cur_product_id] = array();

	// Cycle through variants
	for ($a=0; $a < sizeOf($cur_variants); $a++) { 
	
		// Set current data
		$curProductCode = $cur_variants[$a]["sku"];
		$curID 			= $cur_variants[$a]["id"];
		$curProductID 	= $cur_variants[$a]["product_id"];				

		// Insert into Array
		$arr_products_sorted[$cur_product_id][$curID]["sku"] 		  = $curProductCode;
		$arr_products_sorted[$cur_product_id][$curID]["id"] 		  = $curID;
		$arr_products_sorted[$cur_product_id][$curID]["product_id"]   = $cur_product_id;
		$arr_products_sorted[$cur_product_id][$curID]["product_type"] = $cur_product_type;
		$arr_products_sorted[$cur_product_id][$curID]["description"]  = $cur_product_description;
		$arr_products_sorted[$cur_product_id][$curID]["tags"]  	   	  = $cur_product_tags;
		

	};		

	// Cycle through images
	for ($a=0; $a < sizeOf($cur_images); $a++) { 
	
		// Set current data
		if(!empty($cur_images[$a]["variant_ids"][0])) {

			$arr_products_sorted[$cur_product_id][$cur_images[$a]["variant_ids"][0]]["image_url"] = $cur_images[$a]["src"];

		}
		else {

			// $arr_products_sorted[$cur_product_id][$cur_images[$a]["variant_ids"][0]]["image_url"] = "";

		}

	};

	// Reindex array
	$arr_products_sorted = array_values($arr_products_sorted);

	// Cycle through to reindex
	for ($a=0; $a < sizeOf($arr_products_sorted); $a++) { 

		$arr_products_sorted[$a] = array_values($arr_products_sorted[$a]);

	};	
	
};

// echo '<pre>';
// print_r($arr_products_sorted);
// echo '</pre>';
// exit;

// Cycle through sorted array to insert data
for ($i=0; $i < sizeOf($arr_products_sorted); $i++) { 

	// Set current data
	$curDataArray = $arr_products_sorted[$i];

	// Cycle through to query data
	for ($a=0; $a < sizeOf($curDataArray); $a++) { 
	
		$query  =   "INSERT INTO 
						poll_51_shopify_data (
							product_code,
							shopify_id,
							product_id,
							product_type,
							image_url,
							main_description,
							tags
						) 
		            VALUES (?,?,?,?,?,?,?)
		        	ON DUPLICATE KEY UPDATE
		        		product_code = VALUES(product_code),
						shopify_id = VALUES(shopify_id),
						product_id = VALUES(product_id),
						product_type = VALUES(product_type),
						image_url = VALUES(image_url),
						main_description = VALUES(main_description),
						tags = VALUES(tags)";

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_bind_param($stmt, 'sssssss', $curDataArray[$a]["sku"], $curDataArray[$a]["id"], $curDataArray[$a]["product_id"], $curDataArray[$a]["product_type"], $curDataArray[$a]["image_url"], $curDataArray[$a]["description"], $curDataArray[$a]["tags"]);

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);		

		}
		else {

			echo mysqli_error($conn);
			exit;
		};

	};
	
};

?>