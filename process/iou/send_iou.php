<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

///////////////////////////////////////////////////////////////////////////////////////////// GRAB DETAILS

$iou_order_id 	 		= $_GET['iou_order_id'];
$iou_profile_id  		= $_GET['iou_profile_id'];
$iou_store_name  		= $_GET['iou_origin_branch'];
$iou_branch_applied 	= $_GET['iou_branch_applied'];
$iou_date_option 		= ucwords($_GET['iou_date_pick']);
$iou_custom_date_option = $_GET['iou_custom_date'];

if($iou_custom_date_option != '') {

	$iou_date_switch = 'custom';

}
else {

	$iou_date_switch = $iou_date_option;

};

$iou_time_option		= $_GET['iou_time_range'];
$iou_email_address 		= $_GET['iou_email_address'];
$iou_phone_number 		= $_GET['iou_phone_number'];

// SORT FRAMES
$iou_product_codes 		= $_GET['iou_frame_product_code'];
$iou_vision 			= $_GET['iou_prescription_vision'];
$iou_styles 			= $_GET['iou_frame_style'];
$iou_upgrades 			= $_GET['iou_frame_upgrade'];
$iou_prices 			= $_GET['iou_frame_price'];
$arrIOUFrames			= array();

for ($i=0; $i < sizeOf($iou_product_codes); $i++) { 

	$arrIOUFrames[$i]['product_code'] = $iou_product_codes[$i];
	$arrIOUFrames[$i]['vision'] 	  = ucwords(str_replace("_", " ", $iou_vision[$i]));
	$arrIOUFrames[$i]['style'] 		  = strtok($iou_styles[$i], " ");
	$arrIOUFrames[$i]['color'] 		  = substr($iou_styles[$i], strpos($iou_styles[$i], " ") + 1);    
	$arrIOUFrames[$i]['upgrade'] 	  = $iou_upgrades[$i];
	$arrIOUFrames[$i]['price'] 		  = $iou_prices[$i];
	
};

///////////////////////////////////////////////////////////////////////////////////////////// FUNCTIONS

function validateData($data_to_validate) {

	global $iou_id;
	global $iou_profile_id;

	if($data_to_validate == '' || $data_to_validate == NULL) {

		// echo "<script> window.location='/dispatch/iou?profile_id=".$iou_profile_id."&order_id=".$iou_order_id."'; </script>";
		echo 'BAD';
		exit;

	};

};

///////////////////////////////////////////////////////////////////////////////////////////// VALIDATE DATA + CUSTOM ERROR REPORTING

validateData($iou_order_id);
validateData($iou_profile_id);
validateData($iou_store_name);
validateData($iou_date_option);
validateData($iou_time_option);

////// SET DATE AND TIME 

date_default_timezone_set("Asia/Manila");

switch (strtolower($iou_date_switch)) {

	case 'today':
		$queryDate = 'DATE_FORMAT(DATE_ADD(NOW(), INTERVAL 13 hour), "%Y-%m-%d")';
		$iouDate   = "Today &mdash; ".date('M d, Y');
		break;

	case 'tomorrow':
		$queryDate = 'DATE_FORMAT(DATE_ADD(DATE_ADD(NOW(), INTERVAL 13 hour), INTERVAL 1 day), "%Y-%m-%d")';		
		$iouDate   = "Today &mdash; ".date('M d, Y', strtotime("+1 day"));
		break;

	case 'custom':
		$queryDate = $iou_custom_date_option;
		$iouDate   = date('M d, Y', strtotime($iou_custom_date_option));
		break;

};

///////////////////////////////////////////////////////////////////////////////////////////// INSERT INTO DATABASE

// Cycle through number of packages
for ($i=0; $i < sizeOf($arrIOUFrames); $i++) { 

	$query = 	"INSERT INTO 
					iou(
						iou_id,
						iou_profile_id,
						iou_origin_branch,
						iou_branch_applied,
						iou_date_pick,
						iou_custom_date,
						iou_time_range,
						iou_query_date,
						iou_email_address,
						iou_phone_number,
						iou_frame_product_code,
						iou_prescription_vision,
						iou_frame_upgrade,
						iou_frame_price
					)
				VALUES(
					'".mysqli_real_escape_string($conn, $iou_order_id)."-".($i+1)."',
					'".mysqli_real_escape_string($conn, $iou_profile_id)."',
					'".mysqli_real_escape_string($conn, $iou_store_name)."',
					'".mysqli_real_escape_string($conn, $iou_branch_applied)."',
					'".mysqli_real_escape_string($conn, $iou_date_option)."',
					'".mysqli_real_escape_string($conn, $iou_custom_date_option)."',
					'".mysqli_real_escape_string($conn, $iou_time_option)."',
					".$queryDate.",
					'".mysqli_real_escape_string($conn, $iou_email_address)."',
					'".mysqli_real_escape_string($conn, $iou_phone_number)."',
					'".mysqli_real_escape_string($conn, $arrIOUFrames[$i]['product_code'])."',
					'".mysqli_real_escape_string($conn, $arrIOUFrames[$i]['vision'])."',
					'".mysqli_real_escape_string($conn, $arrIOUFrames[$i]['upgrade'])."',
					'".mysqli_real_escape_string($conn, $arrIOUFrames[$i]['price'])."'
				)";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_close($stmt);

	}
	else {

		echo mysqli_error($conn);
		exit;

	};
	
};

///////////////////////////////////////////////////////////////////////////////////////////// SEND EMAIL

require $sDocRoot."/process/lib/sendgrid-php/sendgrid-php.php";

// Cycle through number of packages
for ($i=0; $i < sizeOf($arrIOUFrames); $i++) { 

	// Check current frame image
	$curImageURL = "https://sunniesspecs.s3-ap-northeast-1.amazonaws.com/frames/".strtolower($arrIOUFrames[$i]['style'])."/".str_replace(" ", "-", strtolower($arrIOUFrames[$i]['color']))."/front.png";

	// if(file_exists($curImageURL)) {

		$iou_image_url = $curImageURL;

	// }
	// else {

		// $iou_image_url = "https://sunniesspecs.s3-ap-northeast-1.amazonaws.com/frames/no-image/no_specs_frame_available_a.png";		

	// };

	$request_body = json_decode('{

	    "personalizations": [{

	        "to": [
	            
	            {"email": "michelhodge@umbradigitalcompany.com"}

	        ],
	        "substitutions": {       

	        	"-orderID-": "'.$iou_order_id.'",
	            "-storeName-": "'.$iou_store_name.'",
	            "-date-": "'.$iouDate.'",
	            "-time-": "'.$iou_time_option.'pm",
	            "-itemStyle-": "'.$arrIOUFrames[$i]['style'].'",
	            "-itemColor-": "'.$arrIOUFrames[$i]['color'].'",
	            "-itemLens-": "'.$arrIOUFrames[$i]['vision'].' Lens",
	            "-itemUpgrade-": "'.$arrIOUFrames[$i]['upgrade'].'",
	            "-upgradePrice-": "P'.number_format((str_replace(",", "", $arrIOUFrames[$i]['price']) - 1200), 0, '.', ',').'",
	            "-totalPrice-": "P'.$arrIOUFrames[$i]['price'].'",
	            "-imageURL-": "'.$iou_image_url.'"

	        },
	        "subject": "Thank you for your purchase!"

	    }],
	    "from": {

	        "email": "help@sunniesspecs.com",
	        "name": "Sunnies Specs"

	    },
	    "template_id": "56507120-1a9a-473a-b156-3eb13c2e6b5c"

	}');

	// API key
	$apiKey = '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';
	$sg = new \SendGrid($apiKey);

	// Response
	$response = $sg->client->mail()->send()->post($request_body);

};

///////////////////////////////////////////////////////////////////////////////////////////// HEAD BACK

echo '<script> window.location="/dispatch/iou?profile_id='.$iou_profile_id.'&order_id='.$iou_order_id.'" </script>';

?>