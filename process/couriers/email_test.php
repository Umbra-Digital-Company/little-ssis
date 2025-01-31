<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

session_save_path($sDocRoot ."/cgi-bin/tmp");
session_start();

// Required includes
require $sDocRoot."/includes/connect.php";

date_default_timezone_set("Asia/Manila");

////////////////////////////////////////////////// GRAB GET DATA

if(isset($_GET) && isset($_GET['order_id'])) {

    $order_id = $_GET['order_id'];

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
                o.payment_method
            FROM 
                profiles_info p
                    INNER JOIN orders_specs_test os 
                        ON os.profile_id=p.profile_id
                    LEFT  JOIN orders_test o 
                        ON o.order_id=os.order_id
                    LEFT JOIN profiles_prescription pp 
                        ON pp.id=os.prescription_id 
                    LEFT JOIN profiles_shipping_address psa
                        ON psa.orders_specs_id = os.orders_specs_id
                    LEFT JOIN poll_51 p51
                        ON p51.product_code = os.product_code
                    LEFT  JOIN store_codes sc 
                        ON sc.location_code=o.store_id                    
                    LEFT JOIN labs_locations ll 
                        ON  ll.lab_id=o.laboratory         
            WHERE 
                os.order_id = '".$order_id."' ;";

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
    'payment_method'
        
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31, $result32, $result33, $result34, $result35, $result36, $result37, $result38, $result39);

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

echo '<pre>';
print_r($arrOrderDetails);
echo '</pre>';

///////////////////////////////////////////////////////////////////////////////////////////// SEND EMAIL

require $sDocRoot."/process/lib/sendgrid-php/sendgrid-php.php";

// Cycle through number of orders
for ($i=0; $i < 1; $i++) { 

    // Set current data
    $curOrderID = $arrOrderDetails[$i]['order_id'];
    $dateOrdered = date("F j, Y");
    $curName = $arrOrderDetails[$i]['first_name'];

    if($arrOrderDetails[$i]['middle_name'] != "X" && $arrOrderDetails[$i]['middle_name'] != "") {

        $curName .= " ".$arrOrderDetails[$i]['middle_name'];

    };

    $curName .= " ".$arrOrderDetails[$i]['last_name'];
    $curFrameStyle = $arrOrderDetails[$i]['style_name']." in ".$arrOrderDetails[$i]['color_name'];
    $curFrameName = ucwords($arrOrderDetails[$i]['style_name']);
    $curColorName = ucwords($arrOrderDetails[$i]['color_name']);
    $curVision = ucwords(str_replace("_", " ", $arrOrderDetails[$i]['prescription_vision']));

    $curUpgrade = ucwords(str_replace("_", " ", $arrOrderDetails[$i]['product_upgrade']));
    $curImageURL = "https://sunnies-specs-optical-virtual-store.s3-ap-northeast-1.amazonaws.com/frames/aalto/gold.jpg";

    $request_body = json_decode('{

        "personalizations": [{

            "to": [
                
                {"email": "michelhodge@umbradigitalcompany.com"}

            ],
            "substitutions": {       

                "<%order_id%>": "'.$curOrderID.'",
                "<%send_to_address%>": "One Maridien Tower",
                "<%delivery_date%>": "3-5 business days",
                "<%order_date%>": "'.$dateOrdered.'",
                "<%full_name%>": "'.$curName.'",
                "<%frame_style%>": "'.$curFrameStyle.'",
                "<%frame_name%>": "'.$curFrameName.'",
                "<%frame_color%>": "'.$curColorName.'",
                "<%vision%>": "'.$curVision.'",
                "<%vision_price%>": "REPLACE",
                "<%upgrade%>": "'.$curUpgrade.'",
                "<%upgrade_price%>": "REPLACE",
                "<%image_url%>": "'.$curImageURL.'"

            },
            "subject": "Order Confirmation"

        }],
        "from": {

            "email": "help@sunniesspecs.com",
            "name": "Sunnies Specs"

        },
        "template_id": "78c5be27-cc9c-49ac-998f-25c2e6aab23e"

    }');

    // API key
    $apiKey = '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';
    $sg = new \SendGrid($apiKey);

    // Response
    $response = $sg->client->mail()->send()->post($request_body);

};

?>