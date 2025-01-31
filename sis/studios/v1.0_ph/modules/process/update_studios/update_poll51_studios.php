<?php 
include("../../connect.php");
if(!isset($_SESSION)){
        session_start();
    }






function formatID($num) {

	$initIDLen = strlen(strval($num));
	$missing   = 5 - $initIDLen;
	$returnID  = str_pad($num, 5, 0, STR_PAD_LEFT);

	return $returnID;

};


$clientID='24a0e6d148e65636d1e65572740f5aab';
$clientSecret='b984e72b51567b56a2061b892a139465';
$storeID=$_SESSION["store_code"];
$staffIDFormated='6'; //$_SESSION['id'] 
$username='sunnies_specs';



$signature = $clientID.$clientSecret.$storeID.$staffIDFormated.$username;
$testSalt = 'sunniesspecs';
 $testSignature = hash('sha256', $signature.$testSalt);



//echo "<pre>";
//print_r($arrPoll51);
//echo "</pre>";



//for($i=0;$i<sizeof($arrPoll51);$i++){
	
				// $url='http://api.sunniessolutions.com/ssis/api/synch/db/?';
				$url = 'http://api.sunniessystems.com/studios/synch/db/?';
	
	$postFields = array(
				'client_id'=>'24a0e6d148e65636d1e65572740f5aab',
				'client_secret'=>'b984e72b51567b56a2061b892a139465',
				'staff_id'=>'6',
				'signature'=>$testSignature,
				't'=>'getStudiosPoll51',
				't_row'=>'poll51'
		
	);
	
	$postFieldsData = http_build_query($postFields);

	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_POST, 1);
	
	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsData);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

	$response = curl_exec( $ch );
	$curlInfo = curl_getinfo($ch);
	
	//$json_encode_res=json_encode($response);
	$json_response= json_decode($response,TRUE);
//$json_response=utf8_encode ($json_response_encoded);
	
	
		
	echo '<pre>';
	// print_r($curlInfo);
// 	print_r($response);
// echo json_last_error_msg();
	// print_r($json_response);
	
//	echo $json_response["t_row[2]"];
			
	
	echo '</pre>';
//}

if($json_response){
	$deleteOldPol5l1 ="DELETE FROM `poll_51` WHERE product_code!='' ";
	$stmtdel = mysqli_stmt_init($conn);
	
	if(mysqli_stmt_prepare($stmtdel, $deleteOldPol5l1)) {
	
		mysqli_stmt_execute($stmtdel); 
	
	
	} 

	for($c=0;$c<sizeof($json_response);$c++){	
		
	 $queryUpdatePoll="INSERT INTO poll_51_studios(
						item_description,
						item_name,
						count,
						item_code,
						stock,
						product_number,
						PIECE,
						price,
						zero1,
						zero2,
						product_code,
						product_code2
						)
				VALUES('".mysqli_real_escape_string($conn,$json_response[$c]["t_row[0]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[1]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[2]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[3]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[4]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[5]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[6]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[7]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[8]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[9]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[10]"])."',
				'".mysqli_real_escape_string($conn,$json_response[$c]["t_row[11]"])."'
				
						)
						ON DUPLICATE KEY UPDATE
						
						item_description=VALUES(item_description),
						item_name=VALUES(item_name),
						count=VALUES(count),
						item_code=VALUES(item_code),
						stock=VALUES(stock),
						product_number=VALUES(product_number),
						PIECE=VALUES(PIECE),
						price=VALUES(price),
						zero1=VALUES(zero1),
						zero2=VALUES(zero2),
						product_code=VALUES(product_code),
						product_code2=VALUES(product_code2)
						
						";
		
		$stmt = mysqli_stmt_init($conn);

								if(mysqli_stmt_prepare($stmt, $queryUpdatePoll)) {

									mysqli_stmt_execute($stmt); 
									mysqli_stmt_close($stmt);

								} 
								else {

									// Connection error
									$qError = mysqli_error($conn);

									// Set extra info
									// $errExtra = "API ERROR - Index File";

									// Send error email
									// sendErrorEmail('101', NULL, $qError, $errExtra);

									echo $qError;
									exit;

								};
	
		
		
		
		
		
		
		
	}
	
}

//	echo "<script>location.reload();</script>";
?>