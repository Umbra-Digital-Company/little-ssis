<?php



 session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
     session_start();
 // Required includes
 $sDocRoot = $_SERVER["DOCUMENT_ROOT"];	

 // Required includes
 require $sDocRoot."/includes/connect.php";

// IDs array to hold all results
$arrIDs = array();

// Required Columns from database. Use these during the fetching process.
$grabParams = array("courier_no", "orders_specs_id");

$query = 	'SELECT  os.courier_no,os.orders_specs_id FROM orders_specs os
					LEFT JOIN 
					payo_order_status payo
						ON payo.order_specs_id=os.orders_specs_id
					Where os.courier="Payo" 
					and os.courier_no!=""
					
					and os.status!="cancelled"
					and lower(payo.status) not like "%delivered%"
                    and os.orders_specs_id="'.$_GET['orderidspecs'].'"

                ';

// Grab IDs
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1, $result2);

  while (mysqli_stmt_fetch($stmt)) {

    $tempArray = array();

    for ($i=0; $i < sizeOf($grabParams); $i++) { 

      $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

    };

    $arrIDs[] = $tempArray;

  };

  mysqli_stmt_close($stmt);

}
else {

  echo mysqli_error($conn);

};

echo '<pre>';
print_r($arrIDs);
echo '</pre>';
// exit;

// Set tracking ID's array
$arrTrackingIDs = array();

// Cycle through the IDs
for ($i=0; $i < sizeOf($arrIDs); $i++) { 
	
	$api_key = '8e70df3c77c279896bf15081c77d6cfa';
	$invoice = $arrIDs[$i]['courier_no'];
	$client_id = 'specs-on-wheels@payo.asia';
	
	$signStr = "";
  $signStr .= $client_id;
  $signStr .= $invoice;
  $signStr .= $client_id;
  $signStr .= $api_key;	
	$token	= hash('sha256', $signStr);

	$data = array(

    //Contact details
    "contact" => array(

      "invoice_no" => $arrIDs[$i]['invoice_no'],
      "client_id" => 'specs-on-wheels@payo.asia',
			"token"		=> $token
        
		)

	);
			
	$query = http_build_query($data);  
	echo $url = "http://cod.payo.asia/order/get?invoice_no=".$invoice."&client_id=".$client_id."&token=".$token."";

	// $url = "http://api.payo.asia/order/get?invoice_no=".$invoice."&client_id=".$client_id."&token=".$token."";

	// cURL
 	$ch3 = curl_init($url);

	curl_setopt($ch3, CURLOPT_URL, $url);
	curl_setopt($ch3, CURLOPT_CUSTOMREQUEST, "GET"); 
	curl_setopt($ch3, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch3, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch3, CURLOPT_SSL_VERIFYPEER, 0); 
	curl_setopt($ch3, CURLOPT_POSTFIELDS, 	$query);
	curl_setopt($ch3, CURLOPT_FOLLOWLOCATION, true);

  // Responses
  $urlCheck = curl_getinfo($ch3);
  $PayoResponse = curl_exec($ch3);

  // Close cUrl
  curl_close($ch3);

  // Decode the response
  $response_payo = json_decode($PayoResponse, true);

  echo '<pre>';
  print_r($response_payo);
  echo '</pre>';
//   exit;
	
	//UPDATE QUERY FOR PAYO
	if($response_payo["service_status"]!='') {
		
		echo $UpdatePayo = "UPDATE 
										payo_order_status 
									SET
										status  ='".$response_payo["service_status"]."',
										sub_courier ='".$response_payo["courier"]."',
										tracking_no='".$response_payo["tracking_number"]."',
										courier_date_update ='".$response_payo["last_status_date"]."',
										courier_fee='".$response_payo["expected_courier_fee"]."',
										courier_status='".$response_payo["courier_status"]."'
									WHERE 
										order_specs_id='".$arrIDs[$i]['orders_specs_id']."'";

		$stmt4 = mysqli_stmt_init($conn);
		if(mysqli_stmt_prepare($stmt4, $UpdatePayo)) {

			mysqli_stmt_execute($stmt4); 
			mysqli_stmt_close($stmt4);

		} 
		else {

		  // Connection error
		  $qError = mysqli_error($conn);

		  // Send error email
		  // sendErrorEmail('127a', NULL, $qError, NULL);

		};
		
	};
	
};

// $response_array["status"] = "success";

// header('Content-type: application/json');
// echo json_encode($response_array);
// echo 'completed';

?>