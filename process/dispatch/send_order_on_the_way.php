<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot . "/includes/connect.php";

///////////////////////////////////////////////////////////////////////////////////////////// SEND EMAIL

require $sDocRoot . "/process/lib/sendgrid-php/sendgrid-php.php";





function GetDetails($order_specs_id){
    global $conn;

  
   
    $arrCustomerDetail = array();
    $queryDispatchDetail ="";
    $queryDispatchDetail .= 	"SELECT  
                                p.first_name,
                                p.middle_name,
                                p.last_name,
                                if( os.product_code='M100',
                                os.product_upgrade,
                                os.product_code
                                ),
                                os.order_id,						
                             
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
                                sc.branch,
                                os.prescription_id,
                                os.product_upgrade,
                                os.prescription_vision,
                                pss.item_description,
                                os.price,
                                o.currency,
                                pp.prescription_name,
                                
                                
                                os.po_number,
                                os.tints,
                                pol.item_description as pol_item,
                                os.remarks,
               IF(
                ppic.order_specs_id IS NOT NULL &&  ppic.status='paid',
            'credit',
            o.payment_method
            ) as payment_type,
                                psa.address1,
                                psa.address2,
                                psa.zip_code,
                                psa.province,
                                psa.city,
                                psa.barangay,
                                psa.email_address,
                                psa.phone_number,
                                os.orders_specs_id,
                                o.promo_code,
                                o.email_address,
                                LOWER(p51b.item_description),
                                date(o.date_created)
                            FROM 
                                profiles_info p
                                    INNER JOIN orders_specs os 
                                        ON os.profile_id=p.profile_id
                                    LEFT JOIN users u 
                                        ON u.id=p.sales_person
                                  
                                    LEFT  JOIN orders o 
                                        ON o.order_id=os.order_id
                                    LEFT  JOIN store_codes sc 
                                        ON sc.location_code=o.store_id
                                    LEFT JOIN profiles_prescription pp 
                                        ON pp.id=os.prescription_id  AND pp.profile_id=p.profile_id
                                    LEFT JOIN labs_locations ll 
                                        ON  ll.lab_id=o.laboratory
                                    LEFT JOIN poll_51 pol 
                                        ON pol.product_code=os.lens_code
                                    LEFT JOIN poll_51 pss
                                        ON	pss.product_code=os.product_code
                                    LEFT JOIN profiles_shipping_address psa
                                        ON psa.order_id=os.order_id
                                        LEFT JOIN poll_51 p51b
                                             ON p51b.product_code = os.product_upgrade
                                     LEFT JOIN paymongo_payment_intents_completed ppic 
                                              ON ppic.order_specs_id = o.order_id 
                                    WHERE os.status!='cancelled' 
                                     
                                        AND os.product_upgrade NOT IN ('SP-DGC500','SP-DGC1000','SP-DGC2000')
                                       
                                        AND os.orders_specs_id ='".$order_specs_id."'
                                        ORDER BY psa.address1 DESC
                                        ";

    // if($type=='order_specs_id'){
    //                    $queryDispatchDetail .= 	"      AND   os.orders_specs_id='".$orders_specs_id."'  ;";
               
    //                 }elseif($type=='order_id'){

    //                                 $queryDispatchDetail .= 	"      AND   os.order_id='".$orders_specs_id."'  ;";

    //                             }
    
    $grabParams = array(
    
        'first_name',
        'middle_name',
        'last_name',
        'product_code',
        'order_id',
      
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
        'branch',
        'prescription_id',
        'product_upgrade',
        'prescription_vision',
        'item_description',
        'price',
        'currency',
        'prescription_name',
      
        'po_number',
        'tints',
        'pol_item',
        'remarks',
        'payment_method',
       's_address1',
        's_address2',
        's_zip_code',
        's_province',
        's_city',
        's_barangay',
        'psa_email_address',
        'psa_phone_number',
        'orders_specs_id',
        'promo_code',
        'o_email_address',
        'item_description_2',
        'o_date_created'
            
    );
    $query= $queryDispatchDetail;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
    
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7,
         $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, 
         $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, 
         $result28, $result29, $result30, $result31 , $result32, $result33, $result34, $result35, $result36, $result37, $result38, $result39, $result40, $result41, $result42, $result43, $result44
         );
    
        while (mysqli_stmt_fetch($stmt)) {
    
            $tempArray = array();
    
            for ($i=0; $i < sizeOf($grabParams); $i++) { 
    
                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
    
            };
    
            $arrCustomerDetail[] = $tempArray;
    
        };
    
        mysqli_stmt_close($stmt);    
                                
    }
    else {
    
        echo mysqli_error($conn);
    
    }; 

    return $arrCustomerDetail;

}

function cvdate($d){
	$returner = '';
	$datae=date_parse($d); 
	$returner .= getMonth($datae['month'])." ".$datae['day'].", ".$datae['year'];
	$suffix = "AM";
	$hour = $datae['hour'];
	if ($datae['hour']>'12') {
		$hour = $datae['hour']-12;
	}
	if ($datae['hour']>'11' && $datae['hour']<'24') {
		$suffix = "PM";
	}
	// $returner .= " at ".AddZero($hour).":".AddZero($datae['minute']).":".AddZero($datae['second'])." ".$suffix."<br>";	
	return $returner;
}

function getMonth($mid){
	switch($mid){
		case '1': return "Jan"; break;
		case '2': return "Feb"; break;
		case '3': return "Mar"; break;
		case '4': return "Apr"; break;
		case '5': return "May"; break;
		case '6': return "Jun"; break;
		case '7': return "Jul"; break;
		case '8': return "Aug"; break;
		case '9': return "Sep"; break;
		case '10': return "Oct"; break;
		case '11': return "Nov"; break;
		case '12': return "Dec"; break;
		
	}
}

function AddZero($num){
	if (strlen($num)=='1') {
		return "0".$num;
	} else {
		return $num;
	}
}

$Details=GetDetails($_GET['orders_specs_id']);
$Price = 0;
for($i=0;$i<sizeof($Details);$i++){
    $Price +=$Details[$i]["price"];
}
// echo "<pre>";
// print_r($Details);
// echo "</pre>";
///////////////////////////////////////////////////////////////////////////////////////////// SETUP EMAIL VARIABLES

$email_address = "nylangeles@umbradigitalcompany.com";
// $email_address = ""; // customer's email address
$orderID = $Details[0]["order_id"]; // 12312312312321
$orderDate =  cvdate($Details[0]["o_date_created"]); // April 25, 2020
$totalPrice = $Price; // P3099.08
$shippingAddress = $Details[0]["s_address1"]; // 29 Ursua Street Sangandaan
$shippingCity = str_replace("-"," ",$Details[0]["s_city"])." ".str_replace("-"," ",$Details[0]["s_province"]); // Caloocan City, Metro Manila
$shippingCountry = "Philippines"; // Philippines



// exit;
$request_body = json_decode('{

    "personalizations": [{
        "to": [
            {"email": "' . $email_address . '"}
        ],
        "substitutions": {       
            "%order_id%": "' . $orderID . '",
            "%order_date%": "' . $orderDate . '",
            "%total_price%": "' . $totalPrice . '",
            "%shipping_address%": "' . $shippingAddress . '",
            "%shipping_city%": "' . $shippingCity . '",
            "%shipping_country%": "' . $shippingCountry . '"
        },
        "subject": "Eyewear is on the way"
    }],
    "from": {
        "email": "hello@sunniesspecs.com",
		"name": "Sunnies Specs"
    },
    "template_id": "a72811cb-c9f9-4f23-bf43-3ce8531122fe"

}');

// API key
$apiKey = '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';
$sg = new \SendGrid($apiKey);

// Response
$response = $sg->client->mail()->send()->post($request_body);

// echo '<pre>';
// print_r($response);
// echo '</pre>';

///////////////////////////////////////////////////////////////////////////////////////////// SEND BACK

// redirect code here
