<?php   

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

exit;

//////////////////////////////////////////////// SEND CUSTOMER

////////// SHOPIFY API

// Set headers
$headers    = array();
$headers[0] = 'X-Shopify-Storefront-Access-Token: 1c8d3be06323615f303c9b0ff09dbf29';
$headers[1] = 'Content-type: application/json';

// Authentication ENDPOINT
$url = "https://06f63a7fd0a5115c6d1b1694cb558579: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@sunniesstudios.myshopify.com/admin/api/2021-10/customers.json";

// Set up order array
$payload_customer = array();

$payload_customer['customer']['first_name'] 	= "Michel";
$payload_customer['customer']['last_name'] 		= "Test";
$payload_customer['customer']['email'] 			= "michelhodge@sunniesstudios.com";
$payload_customer['customer']['phone'] 			= "9171331735";
$payload_customer['customer']['verified_email'] = true;

$payload_customer['customer']['addresses'] = array();
$payload_customer['customer']['addresses'][0]['address1'] 	 = "28th Street";
$payload_customer['customer']['addresses'][0]['city'] 		 = "Taguig";
$payload_customer['customer']['addresses'][0]['province'] 	 = "Metro Manila";
$payload_customer['customer']['addresses'][0]['phone'] 	 	 = "639171331735";
$payload_customer['customer']['addresses'][0]['zip'] 		 = "1634";
$payload_customer['customer']['addresses'][0]['last_name']   = "Test";
$payload_customer['customer']['addresses'][0]['first_name']  = "Michel";
$payload_customer['customer']['addresses'][0]['country']     = "PH";

$payload_customer['customer']['send_email_invite'] = true;

echo '<pre>';
print_r($payload_customer);
echo '</pre>';

// Encode
$order_json_encode = json_encode($payload_customer);

echo '<pre>';
print_r($order_json_encode);
echo '</pre>';
// exit;

// Initiate cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_POSTFIELDS, $order_json_encode);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

// Responses
$body = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Close cUrl
curl_close($ch);

$body_dec = json_decode($body, true);  

echo '<pre>';
print_r($body_dec);
echo '</pre>';
exit;

// Cycle through the products
for ($i=0; $i < sizeOf($arr_products); $i++) { 

	// Set current data
	$cur_product_type 		 = $arr_products[$i]["product_type"];
	$cur_product_description = $arr_products[$i]["body_html"];
	$cur_product_tags		 = $arr_products[$i]["tags"];

	$cur_variants 	  		 = $arr_products[$i]["variants"];
	$cur_images		  		 = $arr_products[$i]["images"];

	$arr_query_data[$cur_variants[0]["product_id"]] = array();	

	if(strtolower($cur_product_type) == "sun frame" || strtolower($cur_product_type) == "optical frame") {

		// Cycle through variants
		for ($a=0; $a < sizeOf($cur_variants); $a++) { 
		
			// Set current data
			$curProductCode = $cur_variants[$a]["sku"];
			$curID 			= $cur_variants[$a]["id"];
			$curProductID 	= $cur_variants[$a]["product_id"];				

			// Push to current query data array
			$arr_query_data[$curProductID][$curID]["sku"] 		   = $curProductCode;
			$arr_query_data[$curProductID][$curID]["id"] 		   = $curID;
			$arr_query_data[$curProductID][$curID]["product_id"]   = $curProductID;
			$arr_query_data[$curProductID][$curID]["product_type"] = $cur_product_type;
			$arr_query_data[$curProductID][$curID]["description"]  = $cur_product_description;
			$arr_query_data[$curProductID][$curID]["tags"]  	   = $cur_product_tags;

		};	

		// Cycle through images
		for ($a=0; $a < sizeOf($cur_images); $a++) { 
		
			// Set current data
			if(!empty($cur_images[$a]["variant_ids"][0])) {

				$arr_query_data[$curProductID][$cur_images[$a]["variant_ids"][0]]["image_url"] = $cur_images[$a]["src"];

			};

		};

		// Reindex array
		$arr_query_data = array_values($arr_query_data)[0];
		$arr_query_data = array_values($arr_query_data);

		// echo '<pre>';
		// print_r($arr_query_data);
		// echo '</pre>';
		// exit;

		// Cycle through query data array
		for ($a=0; $a < sizeOf($arr_query_data); $a++) { 
		
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
			            VALUES (
			            	'".mysqli_real_escape_string($conn, $arr_query_data[$a]["sku"])."',
			            	'".mysqli_real_escape_string($conn, $arr_query_data[$a]["id"])."',
			            	'".mysqli_real_escape_string($conn, $arr_query_data[$a]["product_id"])."',
			            	'".mysqli_real_escape_string($conn, $arr_query_data[$a]["product_type"])."',
			            	'".mysqli_real_escape_string($conn, $arr_query_data[$a]["image_url"])."',
			            	'".mysqli_real_escape_string($conn, $arr_query_data[$a]["description"])."',
			            	'".mysqli_real_escape_string($conn, $arr_query_data[$a]["tags"])."'
			        	)
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

			    mysqli_stmt_execute($stmt);		
			    mysqli_stmt_close($stmt);		

			}
			else {

				echo mysqli_error($conn);
				exit;
			};

		};

	}
	
};

?>