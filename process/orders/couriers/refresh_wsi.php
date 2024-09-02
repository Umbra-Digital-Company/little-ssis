<?php

require $_SERVER["DOCUMENT_ROOT"]."/couriers/wsi/wsi_includes.php";


// IDs array to hold all results
$arrIDs = array();

// Required Columns from database. Use these during the fetching process.
$grabParams = array("courier_no", "orders_specs_id","order_id", 'service_oid' );

$query = 	'SELECT  os.courier_no,os.orders_specs_id,os.order_id,payo.tracking_no FROM orders_specs os
					LEFT JOIN 
					payo_order_status payo
						ON payo.order_specs_id=os.order_id
					Where os.courier="wsi" 
					and os.courier_no!=""
					and payo.id>"30"
					and os.status!="cancelled"
					and os.status="downloaded"
					and lower(payo.status) not LIKE "%delivered%"
					and lower(payo.status) not LIKE "%completed%"
					and lower(payo.status) not LIKE "%cancelled%"
					and lower(payo.status) not LIKE "%delivered%"
					and lower(payo.status) not LIKE "%closed%"
					and date(os.date_created)>="2022-08-01"
					group by os.order_id
					order by payo.date_updated ASC ,payo.courier_date_update ASC
					LIMIT 45

                ';
// waiting for pickup
// pending
// in transit
// and lower(payo.status) IN ("in transit")

// Grab IDs
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

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

// echo '<pre>';
// print_r($arrIDs);
// echo '</pre>';
// exit;

// Set tracking ID's array
$arrTrackingIDs = array();

// Cycle through the IDs
for ($i=0; $i < sizeOf($arrIDs); $i++) { 
	
	$tracking_no = $arrIDs[$i]['courier_no'];
	$order_id = $arrIDs[$i]['order_id'];
	$service_iod = $arrIDs[$i]['service_oid'];
	// $service_iod ='61d243fdeeb3a342c86d7c02'; //testing purpose
	$arrDetail = apiTrigger('parcel/detail/'.$service_iod.'/'.$tracking_no,'GET','',$arrToken['token']);
	$arrDetail = json_decode($arrDetail,true);

	if(isset($arrDetail['status']) && $arrDetail['status'] == 'error'){
	    wsiLogs($_POST['order_id'], $arrDetail['message']);
	    echo json_encode($arrDetail['message']);
	    exit;
	}

	// echo '<pre>';
	// print_r($arrDetail);
	$status = strtolower($arrDetail['status_text']);
	$UpdatePayo = "UPDATE 
										payo_order_status 
									SET
										status  ='".$status."',
										courier_date_update ='".$arrDetail['status_date']."',
										courier_fee='".$arrDetail["total_charges"]."',
										courier_status='".$status."',
										date_updated = now()
									WHERE 
										order_id='".$arrIDs[$i]['order_id']."'";
	    // echo $UpdatePayo;

	        $stmt = mysqli_stmt_init($conn);
	        if (mysqli_stmt_prepare($stmt, $UpdatePayo)) {

	            mysqli_stmt_execute($stmt);   
	            mysqli_stmt_close($stmt);

	        }
	
};

// $response_array["status"] = "success";

// header('Content-type: application/json');
// echo json_encode($response_array);
// echo 'completed';

?>