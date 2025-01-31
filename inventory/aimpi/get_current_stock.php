<?php 
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, OPTIONS");
header("Access-Control-Allow-Headers: Origin, Content-Type, oassis-api-key");
header("Access-Control-Max-Age: 86400");


require $sDocRoot."/includes/connect.php";
require $sDocRoot."/inventory/aimpi/aimpi_v1.php";



$dateStart = date('Y-m-d');
$dateEnd= date('Y-m-t');

// $FrameData[$_GET['frame_code']][$arrStore[$i]["store_id"]]= storeChecker_smr($_GET['frame_code'],$arrStore[$i]["store_id"],$dateStart,$dateEnd);
function showStatus($arrStatus) {

	echo '<pre>';
	print_r($arrStatus);
	echo '</pre>';

	return $arrStatus;

};



// exit;

if(isset($_GET['frame_code']) ){
    $frame_code= $_GET['frame_code'];

}else{
	$json_status['status'] = 'failure';
		$json_status['description'] = 'frame code parameter  is missing ';
		$json_status['result'] = array();

		showStatus($json_status);
		exit;
}

if(isset($_GET['store_code']) ){
    $store_code=$_GET['store_code'];

}else{
    $json_status['status'] = 'failure';
    $json_status['description'] =  'store code parameter is missing1.';
    $json_status['result'] = array();

    showStatus($json_status);
    exit;
}

$FrameData= array();
$FrameData= storeChecker_smr($_GET['frame_code'],$_GET['store_code'],$dateStart,$dateEnd);


// showStatus($FrameData);




################## innset checking iin poll51#########


#################


if($_GET['store_code']!='' && $_GET['frame_code'] !='' ){


	GetStock( $_GET['frame_code']);


}else{

    $json_status['status'] = 'failure';
	$json_status['description'] = 'no stock found';
	$json_status['result'] = array();

	showStatus($json_status);
	exit;
}






?>