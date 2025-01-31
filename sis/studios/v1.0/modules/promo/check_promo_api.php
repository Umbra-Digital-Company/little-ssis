<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";

// echo "<pre>";
// print_r($_GET);
// echo "</pre>";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, oassis-api-key");
header("Access-Control-Max-Age: 86400");

$email =  ( isset( $_GET['email'] ) ) ? $_GET['email'] : "" ;
$voucher_code =  ( isset( $_GET['voucher_code'] ) ) ? $_GET['voucher_code'] : "" ;
// $store_name =  ( isset( $_POST['store_name'] ) ) ? $_POST['store_name'] : "" ;



$url = "https://sunnies-circle-kml5x.ondigitalocean.app/user-rewards/status/email/$email/in-store-code/$voucher_code";

// $curl = curl_init();
// curl_setopt_array($curl, array(
//   CURLOPT_URL => $url,
  
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,

//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
//   CURLOPT_HTTPHEADER => array(
//     'X-SCS-API-KEY: 2e9e9be8-508b-4d30-a59e-ed5c2a5f2de2'
//   ),
// ));

// $response = curl_exec($curl);


$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, 0);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-SCS-API-KEY: f06996cd-dcyu-6447-ycfi-19dfde719b23'));
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

$response = curl_exec( $ch );
$curlInfo = curl_getinfo($ch);





$result = json_decode($response,TRUE);

echo $response;


// print_r($result);
if($result["message"]=="Voucher valid."  && $_GET['type']=='use'){
  // echo "success";

 $query="UPDATE orders_studios SET
                promo_code='".$voucher_code."',
                promo_code_amount='".$result["points"]."',
                promo_code_type='sunnies-circle'
              where order_id='".$_GET['order_id']."'

  ";


  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);		

	}


  // echo "<script>alert('succes');location.reload(true);</script>";
}




// curl_close($curl);



?>