<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

$generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$OrderSpecsId = "";

for ($i=0; $i < 21; $i++) { 

	$OrderSpecsId .=$generate_id[rand(0, (strlen($generate_id)-1))];

};

$query = 	"INSERT INTO 
				remarks_comm(
					order_po_id,
					profile_id,
					message,
					message_id
				)
			VALUES(
				'".$_POST['order_specsid']."',
				'".$_POST['sender']."',
				'".mysqli_real_escape_string($conn,$_POST['messenger_input'])."',
				'".$OrderSpecsId."'
			) 
			ON DUPLICATE KEY UPDATE
				order_po_id=VALUES(order_po_id),
				profile_id=VALUES(profile_id),
				message=VALUES(message),
				message_id=VALUES(message_id)";

$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);

} 
else {

	echo mysqli_error($conn);
	return false;
	exit;	

};

// $arrMessage = array();

// $query = 	"SELECT 
// 				rc.date_created,
// 				rc.date_updated,
// 				rc.order_po_id,
// 				rc.profile_id,
// 				rc.message,
// 				rc.message_id,
// 				u.first_name,
// 				u.last_name
// 			FROM 
// 				remarks_comm rc
// 					LEFT JOIN users u 
// 						ON u.id=rc.profile_id
// 			WHERE
// 				rc.order_po_id='".$_POST['order_specsid']."'
// 			ORDER BY 
// 				date_created";

// $grabParams = array(

// 	'date_created',
// 	'date_updated',
// 	'order_po_id',
// 	'profile_id',
// 	'message',
// 	'message_id',
// 	'first_name',
// 	'last_name'

// );

// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $query)) {

//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

//     while (mysqli_stmt_fetch($stmt)) {

//         $tempArray = array();

//         for ($i=0; $i < sizeOf($grabParams); $i++) { 

//             $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

//         };

//         $arrMessage[] = $tempArray;

//     };

//     mysqli_stmt_close($stmt);    
                            
// }
// else {

//     echo mysqli_error($conn);

// }; 

// $clientID='24a0e6d148e65636d1e65572740f5aab';
// $clientSecret='b984e72b51567b56a2061b892a139465';
// $storeID='109';
// $staffIDFormated='6'; //$_SESSION['id'] 
// $username='sunnies_specs';

// $signature = $clientID.$clientSecret.$storeID.$staffIDFormated.$username;
// $testSalt = 'sunniesspecs';

// $testSignature = hash('sha256', $signature.$testSalt);

// for($i=0;$i<sizeof($arrMessage);$i++){

// 	$url='http://api.sunniessolutions.com/ssis/api/synch/db/?';
	
// 	$postFields = array(

// 		'client_id'=>'24a0e6d148e65636d1e65572740f5aab',
// 		'client_secret'=>'b984e72b51567b56a2061b892a139465',
// 		'staff_id'=>'6',
// 		'signature'=> $testSignature,
// 		't'=>'message',
// 		't_row[0]'=>mysqli_real_escape_string($conn,$arrMessage[$i]["date_created"]),
// 		't_row[1]'=>mysqli_real_escape_string($conn,$arrMessage[$i]["date_updated"]),
// 		't_row[2]'=>mysqli_real_escape_string($conn,$arrMessage[$i]["order_po_id"]),
// 		't_row[3]'=>mysqli_real_escape_string($conn,$arrMessage[$i]["profile_id"]),
// 		't_row[4]'=>mysqli_real_escape_string($conn,$arrMessage[$i]["message"]),
// 		't_row[5]'=>mysqli_real_escape_string($conn,$arrMessage[$i]["message_id"])
				
// 	);
	
// 	$postFieldsDataMessage = http_build_query($postFields);

// 	$ch = curl_init($url);
// 	curl_setopt($ch, CURLOPT_POST, 1);
// 	curl_setopt($ch, CURLOPT_POSTFIELDS, $postFieldsDataMessage);
// 	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
// 	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
// 	curl_setopt($ch, CURLOPT_HEADER, 0);
// 	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

// 	$response = curl_exec( $ch );
// 	$curlInfo = curl_getinfo($ch);
	
// 	$json_response= json_decode($response,TRUE);

// 	echo "<pre>";
// 		print_r($response);
// 		print_r($json_response);
// 	echo '<pre>';

// 	if($json_response){
		
// 		for($c=0;$c<sizeof($json_response);$c++){
			
// 			echo $queryGET = 	"INSERT INTO 
// 									remarks_comm(
// 										date_created,
// 										date_updated,
// 										order_po_id,
// 										profile_id,
// 										message,
// 										message_id
// 									)
// 								VALUES(
// 									'".$json_response[$c]["t_row[0]"]."',
// 									'".$json_response[$c]["t_row[1]"]."',
// 									'".$json_response[$c]["t_row[2]"]."',
// 									'".$json_response[$c]["t_row[3]"]."',
// 									'".$json_response[$c]["t_row[4]"]."',
// 									'".$json_response[$c]["t_row[5]"]."'
// 								)
// 								ON DUPLICATE KEY UPDATE
// 									order_po_id=VALUES(order_po_id),
// 									profile_id=VALUES(profile_id),
// 									message=VALUES(message),
// 									message_id=VALUES(message_id),
// 									date_created=VALUES(date_created),
// 									date_updated=VALUES(date_updated)";

// 			$stmt = mysqli_stmt_init($conn);
// 			if(mysqli_stmt_prepare($stmt, $queryGET)) {

// 				mysqli_stmt_execute($stmt);
// 				mysqli_stmt_close($stmt);

// 			} 
// 			else {

// 				echo mysqli_error($conn);
// 				return false;
// 				exit;	

// 			};

			
// 		};

// 	};

// };

?>