<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";


////////////////////////////////////////////////// GRAB GET DATA

if(isset($_GET) && isset($_GET['orders_specs_id'])) {

    $orders_specs_id = $_GET['orders_specs_id'];

}
else {

    exit;

};

////////////////////////////////////////////////// GRAB FROM DATABASE

$arrOrderDetails = array();

 $query =    "SELECT  
                p.first_name,
                p.middle_name,
                p.last_name,
                os.product_code,
                os.order_id,                        
                LOWER(TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1))) AS 'grab_style' ,
                LOWER(REPLACE(item_name,  TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1)), '')) AS 'grab_color',
                p.profile_id,
                os.signature,
                os.status,
                p.address,
                p.email_address,
                p.province,
                p.city,
                p.barangay,
                p.birthday,
                p.age,
                p.gender,       
                p.phone_number,
                DATE_ADD(p.date_created, INTERVAL 12 HOUR),
                psa.address1,
                IF(
                    psa.address2 IS NOT NULL,
                    psa.address2,
                    ''
                ),
                psa.country,
                psa.province,
                psa.city,
                psa.barangay,
                psa.zip_code,
                psa.special_instructions,
                sc.branch,
                os.prescription_id,
                os.product_upgrade,
                os.prescription_vision,
                os.price,
                o.currency,
                pp.prescription_name,                                            
                os.po_number,
                os.tints,
                os.remarks,
                o.payment_method,
                                
                                psa.email_address,
                               psa.phone_number
            FROM 
                profiles_info p
                    INNER JOIN orders_specs os 
                        ON os.profile_id=p.profile_id
                    LEFT  JOIN orders o 
                        ON o.order_id=os.order_id
                    LEFT JOIN profiles_prescription pp 
                        ON pp.id=os.prescription_id 
                    LEFT JOIN profiles_shipping_address psa
                        ON psa.order_id = os.order_id
                    LEFT JOIN poll_51 p51
                        ON p51.product_code = os.product_code
                    LEFT  JOIN store_codes sc 
                        ON sc.location_code=o.store_id                    
                    LEFT JOIN labs_locations ll 
                        ON  ll.lab_id=o.laboratory        
                          
            WHERE 
                os.orders_specs_id = '".$orders_specs_id."' ;";

$grabParams = array(

    'first_name',
    'middle_name',
    'last_name',
    'product_code',
    'order_id',
    'style_name',
    'color_name',
    'profile_id',
    'signature',
    'status',
    'address',
    'email_address',
    'province',
    'city',
    'barangay',
    'birthday',
    'age',
    'gender',
    'phone_number',
    'date_created',
    'address1',
    'address2',
    'country',
    'province',
    'city',
    'barangay',
    'zip_code',
    'special_instructions',
    'branch',
    'prescription_id',
    'product_upgrade',
    'prescription_vision',
    'price',
    'currency',
    'prescription_name',  
    'po_number',
    'tints',
    'remarks',
    'payment_method',
     'psa_email_address',
    'psa_phone_number'
        
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, 
    $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22,
     $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33, $result34, $result35, $result36, $result37, $result38, $result39, $result40
     , $result41);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrOrderDetails[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);
    exit;

}; 

if(empty($arrOrderDetails)) {

    exit;

};

// echo '<pre>';
// print_r($arrOrderDetails);
// echo '</pre>';

// exit;
////////////////////////////////////////////////// 

// Set token variable
$nvToken = "";

// Set response ariable
$response_dec_nv;

// Set fail variables
$errToken = false;
$errSend = false;

////////////////////////////////////////////////// FUNCTIONS TOKEN

function grabCachedToken() {

    global $sDocRoot;
    global $nvToken;

    // Grab the cached token
    $f = fopen("ninja-van.txt", "r");
    $nvToken = fgets($f);
    fclose($f);

};

function grabNJToken() {

    global $sDocRoot;
    global $ninja_van_client_id;
    global $ninja_van_client_secret;
    global $nvToken;
    global $errToken;
    global $endpoint;

    // Set up order array
    $order_data = array();

    $order_data['client_id']     = $ninja_van_client_id;
    $order_data['client_secret'] = $ninja_van_client_secret;
    $order_data['grant_type']    = "client_credentials";

    // Create order json
    $order_json_encode = json_encode($order_data, JSON_PRETTY_PRINT);

    // Set headers
    $headers    = array();    
    $headers[0] = 'Content-type: application/json';
    $headers[1] = 'Accept: application/json';

    // Authentication ENDPOINT
    $url = $endpoint."/sg/2.0/oauth/access_token";

    // Initiate cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $order_json_encode);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    // Responses
    $body = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cUrl
    curl_close($ch);

    $response_dec_nv = json_decode($body, true);     

    echo '<pre>';
    echo '----------------';
    echo '</pre>';
    echo '<pre>';
    print_r($response_dec_nv);
    echo '</pre>';
    echo '<pre>';
    echo '----------------';
    echo '</pre>';        

    // Set the access token
    if(isset($response_dec_nv)) {

        if(isset($response_dec_nv["access_token"])) {

            $nvToken = $response_dec_nv["access_token"]; // Token successfully set

            // Cache the token
            $f = fopen("ninja-van.txt", "w");
            fwrite($f, $nvToken);
            fclose($f);

        }
        else {            

            // Check if the function has already been fired a second time
            if(!$errToken) {

                // Request has not returned a token. Warning is sent to developer and the function is fired a second time.
                // sendErrorEmail('123', NULL, $response, "Request has failed to return a NJ authentication token. This is the first time.");

                // Set errToken to true to indicate the function is firing a second time.
                $errToken = true;

                // Attempt to regrab a token
                grabNJToken();                

            }
            else {

                // sendErrorEmail('124', NULL, $response, "Request has failed to return a NJ authentication token. This is the second time.");

                // UPDATE DATABASE WITH ERROR

            };

        };

    }
    else {

        // Request has failed. Warning is sent to developer and the function is fired again a first time.
        if(!$errToken) {

            // Reques't has failed. Warning is sent to developer and the function is fired a second time.
            // sendErrorEmail('125', NULL, $response, "Request has failed. This is the first time.");

            // Set errToken to true to indicate the function is firing a second time.
            $errToken = true;

            // Attempt to regrab a token
            grabNJToken();

        }
        else {

            // Request has failed. Warning is sent to developer and the function is fired a second time.
            // sendErrorEmail('126', NULL, $response, "Request has failed. This is the second time.");

            // UPDATE DATABASE WITH ERROR

        };

    };

};



////////////////////////////////////////////////// SET UP JSON DATA

// Set timezone
date_default_timezone_set('Asia/Manila');

// Set dates
$orderDate         = date("Y-m-d");
$orderPickupDate   = date("Y-m-d", strtotime($orderDate.' +1 day'));
$orderDeliveryDate = date("Y-m-d", strtotime($orderDate.' +2 day'));

// Set up order array
$order_data = array();

// Main data
$order_data['service_type']                                  = "Parcel";
$order_data['service_level']                                 = "Standard";
// $order_data['requested_tracking_number']                     = "";

// Reference
$order_data['reference']['merchant_order_number']            = "SPECS-".$arrOrderDetails[0]['po_number'];

// From
$order_data['from']['name']                                  = "Sunnies Specs Optical";
$order_data['from']['phone_number']                          = "+639171331735";
$order_data['from']['email']                                 = "help@sunniesspecs.com";

// From Address
$order_data['from']['address']['address1']                   = "179 Yakal Street San Antonio Village";
$order_data['from']['address']['address2']                   = "CEI Makati";
$order_data['from']['address']['subdivision']                = "makati";
$order_data['from']['address']['city']                       = "makati-city";
$order_data['from']['address']['province']                   = "metro-manila";
$order_data['from']['address']['country']                    = "PH";
$order_data['from']['address']['postcode']                   = "1630";

// To
$order_data['to']['name']                                    = $arrOrderDetails[0]['first_name']." ".$arrOrderDetails[0]['last_name'];
$order_data['to']['phone_number']                            = "+".str_replace("-", "", $arrOrderDetails[0]['psa_phone_number']);
$order_data['to']['email']                                   = $arrOrderDetails[0]['psa_email_address'];

// To Address
$order_data['to']['address']['address1']                     = $arrOrderDetails[0]['address1'];
$order_data['to']['address']['address2']                     = $arrOrderDetails[0]['address2'];
$order_data['to']['address']['subdivision']                  = str_replace("-", " ",$arrOrderDetails[0]['barangay']);
$order_data['to']['address']['city']                         = str_replace("-", " ",$arrOrderDetails[0]['city']);
$order_data['to']['address']['province']                     = str_replace("-", " ",$arrOrderDetails[0]['province']);
$order_data['to']['address']['country']                      = "PH";
$order_data['to']['address']['postcode']                     = $arrOrderDetails[0]['zip_code'];

// Parcel Job
$order_data['parcel_job']['is_pickup_required']              = true;
// $order_data['parcel_job']['pickup_address_id']               = 98989012;
$order_data['parcel_job']['pickup_service_type']             = "Scheduled";
$order_data['parcel_job']['pickup_service_level']            = "Standard";
$order_data['parcel_job']['pickup_date']                     = $orderPickupDate;

// Parcel Job Pickup Timeslot
$order_data['parcel_job']['pickup_timeslot']['start_time']   = "12:00";
$order_data['parcel_job']['pickup_timeslot']['end_time']     = "15:00";
$order_data['parcel_job']['pickup_timeslot']['timezone']     = "Asia/Manila";

// Parcel Job
$order_data['parcel_job']['pickup_instructions']             = "Pickup with care!";
$order_data['parcel_job']['delivery_instructions']           = "";
$order_data['parcel_job']['delivery_start_date']             = $orderDeliveryDate;

// Parcel Job Delivery Timeslot
$order_data['parcel_job']['delivery_timeslot']['start_time'] = "09:00";
$order_data['parcel_job']['delivery_timeslot']['end_time']   = "22:00";
$order_data['parcel_job']['delivery_timeslot']['timezone']   = "Asia/Manila";

// CASH ON DELIVERY
if($arrOrderDetails[0]['payment_method'] == 'cash') {

    $order_data['parcel_job']['cash_on_delivery']            = $arrOrderDetails[0]['price'];

};

// Weekend Delivery
$order_data['parcel_job']['allow_weekend_delivery']          = true;

//Parcel Job Dimensions
$order_data['parcel_job']['dimensions']['size']              = "m";
$order_data['parcel_job']['dimensions']['weight']            = 0;
$order_data['parcel_job']['dimensions']['length']            = 0;
$order_data['parcel_job']['dimensions']['width']             = 0;
$order_data['parcel_job']['dimensions']['height']            = 0;

// Create order json
$order_json = array();
$order_json = $order_data;
 $orderID=$orders_specs_id;
 $orderCustomerID =$arrOrderDetails[0]['profile_id'];
echo '<pre>';
print_r(json_encode($order_json, JSON_PRETTY_PRINT));
echo '</pre>';


// exit;
//////////////////////////////////////////////////

/*

    sendNJOrder() function to send a shipping
    order.

*/

//////////////////////////////////////////////////

function sendNJOrder() {

    global $conn;
    global $errSend;
    global $nvToken;
    global $order_json;
    global $orderID;
    global $orderCustomerID;
    global $endpoint;

    if(!$errSend) {

        // Grab the cached token
        grabCachedToken();

    }
    else {

        // Grab a new token
        grabNJToken();

    };

    echo '<pre>';
    echo '----------------';
    echo '</pre>';
    echo '<pre>';
    print_r($nvToken);
    echo '</pre>';
    echo '<pre>';
    echo '----------------';
    echo '</pre>'; 

    // Set headers
    $headers    = array();
    $headers[0] = 'Authorization: Bearer '.$nvToken;
    $headers[1] = 'Content-type: application/json';
    $headers[2] = 'Accept: application/json';

    // Authentication ENDPOINT
    $url = $endpoint."/sg/4.1/orders";

    // Initiate cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($order_json));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

    // Responses
    $body = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    // Close cUrl
    curl_close($ch);

    // Email request and response for administrative purposes
    // sendNJRequest($orderID, json_encode($order_json), $body);

    $body_dec = json_decode($body, true);   

    echo '<pre>';
    echo '----------------';
    echo '</pre>';
    echo '<pre>';
    print_r($body_dec);
    echo '</pre>';
    echo '<pre>';
    echo '----------------';
    echo '</pre>';   

    $nvID         = "";
    $nvStatus     = "";
    $nvMessage    = "";
    $nvOrderRefNo = "";
    $nvTrackingID = "";

    // Ninja Van ID
    if(isset($body_dec['id'])) {

        $nvID = $body_dec['id'];

    };

    // Ninja Van Status
    if(isset($body_dec['status'])) {

        $nvStatus = $body_dec['status'];

    };

    // Ninja Van Message
    if(isset($body_dec['message'])) {

        $nvMessage = $body_dec['message'];

    };

    // Ninja Van Order Reference Number
    if(isset($body_dec['reference']['merchant_order_number'])) {

        $nvOrderRefNo = $body_dec['reference']['merchant_order_number'];

    };

    // Ninja Van Tracking ID
    if(isset($body_dec['tracking_number'])) {

        $nvTrackingID = $body_dec['tracking_number'];

    };

   echo  $nvQuery =   "INSERT INTO 
                        ninja_van_order_status 
                            (
                                order_id,
                                ninja_van_id,
                                status,
                                message,
                                order_ref_no,
                                tracking_id
                            ) 
                    VALUES 
                        (
                            '".$orderID."',
                            '',
                            '',
                            '',
                            '".$nvOrderRefNo."',
                            '".$nvTrackingID."'
                        ) 
                    ON DUPLICATE KEY UPDATE 
                        order_id=VALUES(order_id),
                        ninja_van_id=VALUES(ninja_van_id),
                        status=VALUES(status),
                        message=VALUES(message),
                        order_ref_no=VALUES(order_ref_no),
                        tracking_id=VALUES(tracking_id);";

    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $nvQuery)) {

        mysqli_stmt_execute($stmt); 
        mysqli_stmt_close($stmt);

    } 
    else {  

        // Connection error
        $qError = mysqli_error($conn);

        // Send error email
        // sendErrorEmail('127a', NULL, $qError, NULL);

    };

    if($http_code == 200) {

        // Decode the body
        $body = json_decode($body);
echo "<pre>";
print_r($body);
echo "</pre>";
        // Set parcel variables
        // $parcel_id = $body[0]->id;
        // $parcel_creation_status = $body[0]->status;
        $parcel_message = $body[0]->message;
        // $parcel_order_ref_no = $body[0]->order_ref_no;

        // Check if error is present
        if($parcel_message == "ERROR") {

            // Parcel has not been attached to the order.
            // sendErrorEmail('127', NULL, $response, "Parcel has not been added to NJ order.");

        }; 

    }
    else if($http_code == 401 || $http_code == 0) {

        if(!$errSend) {

            // Set $errSend to true
            $errSend = true;

            // Grab a new token
            grabNJToken();

            // Attempt order again
            sendNJOrder();

        }
        else {

            // Parcel has not been attached to the order.
            // sendErrorEmail('128', NULL, $response, "Order has not been created.");

        };

    }
    else{

        // Parcel has not been attached to the order.
        // sendErrorEmail('129', NULL, $response, "Order has not been created.");        

    };

};

// Send the order
sendNJOrder();

?>