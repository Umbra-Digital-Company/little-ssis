<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	
 session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
     session_start();
 // Required includes
 require $sDocRoot."/includes/connect.php";
//  require $sDocRoot."/includes/promo_code_function.php";
 


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
                                LOWER(p51b.item_description)
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
                                        ON psa.orders_specs_id=os.orders_specs_id
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
        'item_description_2'
            
    );
    $query= $queryDispatchDetail;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
    
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7,
         $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, 
         $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, 
         $result28, $result29, $result30, $result31 , $result32, $result33, $result34, $result35, $result36, $result37, $result38, $result39, $result40, $result41, $result42, $result43
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

// if(isset($_GET['orderspecsid'])){
//     $Details=GetDetails($_GET['orderspecsid'],'order_specs_id');

// }elseif(isset($_GET['order_id'])){
//     $Details=GetDetails($_GET['order_id'],'order_id');

// }

$Details=GetDetails($_GET['orderspecsid']);

// $promoDetails= PromoCodeUsed($Details[0]["promo_code"],$_GET['order_id']);
// $promoDetails=PromoCodeUsed('GNTXKZ','michelhodge2@hotmail.com');
echo "<pre>";
print_r($Details);

// print_r($promoDetails);

// exit;


$total_price = 0;
$frame =array();
$frameCount="0";

for($i=0;$i<sizeof($Details);$i++){
    $countFrame[$Details[$i]["product_code"]]="0";
    $framecountPrice[$Details[$i]["product_code"]]="0";
};

for($i=0;$i<sizeof($Details);$i++){


    $countFrame[$Details[$i]["product_code"]] += "1";
    $framecountPrice[$Details[$i]["product_code"]] =$Details[$i]["price"];
    
    $total_price +=$Details[$i]["price"];
    $frameCount +="1";

    if($Details[$i]["product_code"]=='M100'){
            $item_description = $Details[$i]["item_description_2"];
    }else{
        $item_description = $Details[$i]["item_description"];
    }


    $frame[$i] =$item_description."(".$Details[$i]["prescription_vision"]." ".$Details[$i]["product_upgrade"].")";




}

                        // if($promoDetails["message"]=='good'){
						// 								if($promoDetails[0]["percentage_ph"]!='0'){
						// 										$percent =$promoDetails[0]["percentage_ph"]/100;
						// 										$discountx= $total_price* $percent;
                        //                                     // $grand_total= $total_price - $discount;
                        //                                     $discount="-".$discountx;
						// 								}
						// 								else{
                        //                                     $discount="-". $promoDetails[0]["amount_ph"];
						// 								}	
						// 							}else{
						// 								$discount=0;
						// 							}




echo "discount".$discount;

echo print_r($framecountPrice);
echo "<br>";
echo $frameCount;
echo "<br>";
echo $final_frame= str_replace("_"," ",strtoupper(implode(",",$frame)));

// exit;
// case 'payo':

        // $sunniesSkuPayo = array();
        
        // for($p=0;$p<sizeof($_SESSION["shopping_cart"]["sunnies"]);$p++) {

        //     $sunniesSkuPayo["details"] =$_SESSION["shopping_cart"]["sunnies"][$p]["sku"] ;

        // };
        
        $PaymentM = array(    

            "CC" =>  "Paid",   //Paypal
            "credit" =>  "Paid",  //Credit card
            "Cash"    =>  "COD", //COD                
            "CASH"    =>  "COD" ,
            "cash"    =>  "COD",
            "CREDIT"    =>  "Paid",
            "cod"        => "COD",
            "COD"        => "COD"     
        );
        
        class Api {

            /** Url */
            protected $url = "http://cod.payo.asia/order/create";      
            // protected $url = "http://api.payo.asia/order/create";      
            /** API Key */
            protected $apiKey = "8e70df3c77c279896bf15081c77d6cfa";

            /** Client ID */
            protected $client_id = 'specs-on-wheels@payo.asia';

            /** Error */
            protected $errors = array();

            /** Validator */
            protected $validator = array(

                "contact" => array(
                    "firstname",
                    "lastname",
                    "email"
                ),
                "items",
                "shipping" => array(
                    "country",
                    "state",
                    "city",
                    "street",
                    "zip"
                ),
                "billing" => array(
                    "country",
                    "state",
                    "city",
                    "street",
                    "zip"
                ),
                "client_id",
                "order_id",
                "External_ID"
            );

            /**
            * Constructor
            * 
            * @param array configuration
            *      url URL of the api service
            *      api_key API Key
            */
            public function __construct($config = array()) {

                if(isset($config["url"])){

                    $this->url = $config["url"];

                };

                if(isset($config["api_key"])){

                    $this->apiKey = $config["api_key"];                    

                };

                if(isset($config["client_id"])){

                    $this->client_id = $config["client_id"];                    

                };

            }

            /**
            * Send data
            * 
            * @param array data     
            */
            public function send($data) {

                $this->errors = array();

                if(!$this->url){

                    $this->errors[] = "API url is not set";
                    return false;

                };

                if(!$this->client_id){

                    $this->errors[] = "Client Id is not set";
                    return false;

                };

                if(!$this->_validate($data, $this->validator)){

                    return false;

                };

                $data["client_id"] = $this->client_id;
                $data["signature"] = $this->getSignature($data);

                $query = http_build_query($data);        
                $ch = curl_init();        
                curl_setopt($ch, CURLOPT_URL, $this->url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                $res = curl_exec($ch);
                curl_close($ch);            
echo "<pre>";
                print_r($res);
                echo "</pre>";
                if(!$res){

                    $this->errors[] = "Could not connect to API service";
                    return false;

                };

                $data = json_decode($res, true);

                if(!$data || !isset($data["success"])){

                    $this->errors[] = "Invalid response";
                    return false;

                };

                if(!$data["success"]){

                    $this->errors[] = $data["error"];
                    return false;

                };

                return $data;

            }

            /**
            * Get error
            * @return string
            */
            public function getErrors() {

                return $this->errors;

            }

            /**
            * Linear validator
            * @param array data
            * @param array validator
            * @param string parent element
            * @return true | array     
            */
            protected function _validate($data, $fields, $parent = null) {

                $errors = array();

                foreach($fields as $k => $v){

                    if(is_numeric($k) && is_string($v)){

                        $field = $v;                

                    } 
                    else {

                        $field = $k;

                    };

                    if(!isset($data[$field]) || empty($data[$field])){

                        $errors[] = ($parent !== null ? $parent."." : "").$field . " is required";
                        continue;

                    };

                    if(is_array($v)){

                        $res = $this->_validate($data[$field], $v, ($parent !== null ? $parent."." : "").$field);
                        
                        if($res !== true){

                            $errors = array_merge($errors, $res);

                        };

                    };

                };

                return empty($errors) ? true : $errors;

            }

            /**
            * Get signature
            * @param data
            * @return string
            */
            protected function getSignature($data){

                $signStr = $data["contact"]["email"];
                $signStr .= $data["contact"]["firstname"];
                $signStr .= $data["contact"]["lastname"];
                $signStr .= count($data["items"]);
                $signStr .= $this->client_id;
                $signStr .= $this->apiKey;

                return hash('sha256', $signStr);

            }

        };
        
        //API Service
        $api = new Api(array(

            "api_key" => "8e70df3c77c279896bf15081c77d6cfa",
            "client_id" => 'specs-on-wheels@payo.asia'

        ));

        // $Details[0]["psa_email_address"],
        // $Details[0]["psa_phone_number"]
        //Invoice data

        for($i=0;$i<sizeof($Details);$i++){
            $items[$i]=array(
                   
                        "id" => "6x1309936", //Product ID from Vtiger
                        "price" => $framecountPrice[$Details[$i]["product_code"]], //Price
                        "quantity" => $countFrame[$Details[$i]["product_code"]]//Quantity

                    
            );
       
        }


        $data = array(

            //Contact details
            "contact" => array(

                "firstname" => $Details[0]["first_name"],
                "lastname" => $Details[0]["last_name"],
                "email" =>  $Details[0]["psa_email_address"],
                "phone" => $Details[0]["psa_phone_number"]
            ),
            //Invoice items
            "items" => $items,
            //Shipping address
            "shipping" => array(

                "country" => "Phillipines",
                "state" =>  str_replace("-"," ",$Details[0]["s_province"]),
                "city" =>  str_replace("-"," ",$Details[0]["s_city"]),
                "street" => $Details[0]["s_address1"],
                "barangay"=> str_replace("-"," ",$Details[0]["s_barangay"]),
                "zip" => $Details[0]["s_zip_code"]

            ),
            //Billing address            
            // "billing" => array(

            //     "country" => "Phillipines",
            //     "state" => "PH",
            //     "city" => "Quezon City",
            //     "street" => "10 calle industria",
            //     "zip" => "1110"
            // ),
            //Supplier Order ID
            "order_id" =>  $Details[0]["po_number"],
            
                // "charges"=> $discount,

            //Shipping method $PaymentM[$_SESSION["order"]["payment_method"]]
            "shipping_method" => "Paid",
            //Tracking number
            //"dr_number" => "1234567", 
            "description" =>  $final_frame,
            "External_ID"   =>"Sunnies Specs"
        );

       
// print_r($items);

// array_push($data,$items);

        $res = $api->send($data);   

    echo "<pre>";
    print_r($data);
   






                for($i=0;$i<sizeof($Details);$i++){

                    
   echo    $queryUpdateOrder="UPDATE orders_specs SET 
      courier='Payo',
      courier_no='".$res["invoice_no"]."'
      WHERE order_id='".$Details[0]["order_id"]."'
      AND orders_specs_id= '".$Details[$i]["orders_specs_id"]."'
      and status!='cancelled'
      ";
                $stmt3 = mysqli_stmt_init($conn);        
                if(mysqli_stmt_prepare($stmt3, $queryUpdateOrder)) {

                    mysqli_stmt_execute($stmt3); 
                    mysqli_stmt_close($stmt3);

                } 
                else {

                    //Connection error
                    $qError = mysqli_error($conn);

                    // Send error email


                }; 


     echo   $queryPayo =    "INSERT INTO         
                            payo_order_status(
                                order_id,
                                order_specs_id,
                                invoice_no,
                                customer_id,
                                `status`
                            )
                        VALUES
                            (
                                '".$Details[0]["order_id"]."',
                                '".$Details[$i]["orders_specs_id"]."',
                                '".$res["invoice_no"]."',
                                '".$Details[0]["profile_id"]."',
                                'pending'
                            )
                            ON DUPLICATE KEY UPDATE 
                            order_specs_id=VALUES(order_specs_id),
                                invoice_no=VALUES(invoice_no),
                                customer_id=VALUES(customer_id),
                                status=VALUES(status)
                            ";    
                        $stmt3 = mysqli_stmt_init($conn);        
                        if(mysqli_stmt_prepare($stmt3, $queryPayo)) {

                            mysqli_stmt_execute($stmt3); 
                            mysqli_stmt_close($stmt3);

                        } 
                        else {

                            //Connection error
                            $qError = mysqli_error($conn);

                            // Send error email
                        

                        };   
                }     

        // $aCommID = "N/A";
        // $trackingID = $res["invoice_no"];
        
        // break;        
        
        // case 'fedex': // FedEx        
        // case 'no-courier': // Special promo

        //     $aCommID = "N/A";
        //     $trackingID = "N/A";

        //     break;
        echo "</pre>";
    
?>