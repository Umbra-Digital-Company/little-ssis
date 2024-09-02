<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
// Required includes
// require $sDocRoot . "/includes/connect.php";

require $sDocRoot . "/process/lib/sendgrid-php/sendgrid-php.php";

function GetDetails($order_specs_id){
    global $conn;

  
   
    $arrCustomerDetail = array();
    $queryDispatchDetail ="";
    $queryDispatchDetail .= 	"SELECT  
                                     p.first_name,
                                    p.middle_name,
                                    p.last_name,
                                    o.order_id,
                                    sc.store_name_proper,
                                    p.email_address,
                                     o.email_sent,
                                    o.origin_branch
                                FROM 
                                    profiles_info p
                                        INNER JOIN orders_face_details os 
                                            ON os.profile_id=p.profile_id
                                        LEFT JOIN users u 
                                            ON u.id=p.sales_person
                                    
                                        LEFT  JOIN orders_face o 
                                            ON o.order_id=os.order_id
                                        LEFT  JOIN store_codes_face sc 
                                            ON sc.store_code=o.store_id
                                        LEFT JOIN profiles_prescription pp 
                                            ON pp.id=os.prescription_id  AND pp.profile_id=p.profile_id
                                    
                                        
                                        WHERE os.status!='cancelled' 
                                       
                                        AND os.orders_specs_id ='".$order_specs_id."'
                                             ORDER BY p.first_name DESC
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
         'order_id',
        'branch',
        'email_address',
        'email_sent',
        'origin_branch'
            
    );
    $query= $queryDispatchDetail;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
    
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7,
         $result8
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



$Get_data= GetDetails($_POST['orders_specs_id']);

// $test='108210802JFPJGXQ76MOZA8WDX08AOM09LA';

// $Get_data= GetDetails($test);
// $email_address = "michelhodge@umbradigitalcompany.com";
// $email_address = "glendeiparine@sunniesstudios.com";
// $email_address = "karinarobles@sunniesstudios.com";
// $email_address = "nicolecruz@sunniesstudios.com";
// $email_address = "nylangeles@umbradigitalcompany.com";


$email_address = $Get_data[0]["email_address"];

$name=$Get_data[0]["first_name"];
$branch=$Get_data[0]["branch"];
$origin_branch=$Get_data[0]["origin_branch"];

// echo "<pre>";
// print_r($Get_data);
// echo "</pre>";

// echo $Get_data[0]['email_sent'];

    if($Get_data[0]['email_sent']=='n' &&  ($origin_branch!='787' && $origin_branch!='788' && $origin_branch!='142'  && $origin_branch!='150' && $origin_branch!='155') 
        && !preg_match("/specsguest@sunniesspecsoptical/i", $email_address) &&  $name!='guest'
    ){




            $request_body = json_decode('{

                "personalizations": [{
                    "to": [
                        {"email": "' . $email_address . '"}
                    ],
                    "dynamic_template_data": {       
                        "branch": "' . $branch . '",
                        
                        "firstName":  "' . $name . '",
                    "subject": "Seeing with your new item? "
                    }
                }],
                "from": {
                    "email": "hello@sunniesface.com",
                    "name": "Sunnies Face"
                },
                "template_id": "d-7919f3bf377d47589ccdfd8324acf1d8"
            }');

            // API key
            $apiKey = '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';
            $sg = new \SendGrid($apiKey);

            // Response
            $response = $sg->client->mail()->send()->post($request_body);

    // echo '<pre>';
    // print_r($response);
    // echo '</pre>';

           $queryupdateEmail="UPDATE orders_face SET 
                        email_sent='y'
                    where order_id='".$Get_data[0]["order_id"]."'
                    ";


                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $queryupdateEmail)) {

                mysqli_stmt_execute($stmt);		

                };

    }else{


    }



?>