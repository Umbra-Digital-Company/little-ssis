<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/dashboard/functions.php";

////////////////////////////////////////////////////////////////////////////////// TOP DATA | 4 BLOCKS

// Grab all the specs orders
$arrAllFrames = grabFrames();

/////////////// frames sold
$totalFramesSold 	   = 0;
$totalFramesDispatched = 0;
$totalFramesCancelled  = 0;
$totalFramesPending    = 0;

/////////////// dispatch on time
$totalFramesOnTime 	   = 0;

// Cycle through frames
for ($i=0; $i < sizeOf($arrAllFrames); $i++) { 

	// total frames dispatched
	if($arrAllFrames[$i]['payment'] == 'y' && $arrAllFrames[$i]['status'] == 'dispatched') {

		$totalFramesDispatched++;

	};

	// total frames sold
	if($arrAllFrames[$i]['payment'] == 'y' && $arrAllFrames[$i]['status'] != 'cancelled') {

		$totalFramesSold++;

	};

	// total frames cancelled
	if($arrAllFrames[$i]['status'] != 'cancelled') {

		$totalFramesCancelled++;

	};

	// total frames pending
	if($arrAllFrames[$i]['payment'] == 'y' && $arrAllFrames[$i]['status'] != 'cancelled' && $arrAllFrames[$i]['status'] != 'dispatched') {

		$totalFramesPending++;

	};

	// total frames on time
	if($arrAllFrames[$i]['payment'] == 'y' && $arrAllFrames[$i]['status'] == 'dispatched' && $arrAllFrames[$i]['on_time'] == 'y') {

		$totalFramesOnTime++;

	}

};

/////////////// DISPATCH RATE

$calc = 0;
if($totalFramesDispatched > 0 && $totalFramesSold > 0) {

	$calc = ($totalFramesDispatched / $totalFramesSold) * 100;

}
$dRate  = number_format($calc, 0, '.', ',');
$dCount = $totalFramesDispatched;

if($dRate > 79) {

	$dRateStyle = "green";

}
else if($dRate > 30) {

	$dRateStyle = "orange";

}
else {

	$dRateStyle = "red";

};

/////////////// top selling frame
$arrTopFrames = grabBestFrames();	
grabBestFrames();

if(!empty($arrTopFrames)) {

	if($arrTopFrames[0]['product_code'] == 'F100') {

		$fID = 1;

	}
	else {

		$fID = 0;

	};

	$bestFrame = ucwords(strtolower($arrTopFrames[$fID]['item_name']));
	$bestFrame = str_replace("No Frame / Lens Only Transaction", "Frame Only", $bestFrame);

	$bestItemSold = $bestFrame;

}
else {

	$bestItemSold = 'N/A';

};

/////////////// dispatch on time
$calcTime = 0;
if($totalFramesOnTime > 0 && $totalFramesDispatched > 0) {

	$calcTime = ($totalFramesOnTime / $totalFramesDispatched) * 100;

}
$timeRate = number_format($calcTime, 0, '.', ',');		

if($timeRate > 79) {

	$timeRateStyle = "green";

}
else if($timeRate > 30) {

	$timeRateStyle = "orange";

}
else {

	$timeRateStyle = "red";

};

/////////////// w w/ prescription

// Cycle through frames
$totalFramesWithPRX = 0;
$totalFramesWithoutPRX = 0;

for ($i=0; $i < sizeOf($arrAllFrames); $i++) { 

	if($arrAllFrames[$i]['payment'] == 'y' && $arrAllFrames[$i]['status'] != 'cancelled') {

		if($arrAllFrames[$i]['lens_option'] == 'with prescription' || $arrAllFrames[$i]['lens_option'] == 'lens only') {

			$totalFramesWithPRX++;

		}
		else if($arrAllFrames[$i]['lens_option'] == 'without prescription') {

			$totalFramesWithoutPRX++;

		};

	};

};

/////////////// prescription types

// Cycle through frames
$arrPrescriptions = array();

for ($i=0; $i < sizeOf($arrAllFrames); $i++) { 

	if($arrAllFrames[$i]['payment'] == 'y' && $arrAllFrames[$i]['prescription_vision'] <> NULL && $arrAllFrames[$i]['status'] != 'cancelled') {

		$arrPrescriptions[$arrAllFrames[$i]['prescription_vision']]['type'] = $arrAllFrames[$i]['prescription_vision'];
		$arrPrescriptions[$arrAllFrames[$i]['prescription_vision']]['count']++;

	};

};
// Set general prescription types array
$arrPRXTypes = array(

	"single_vision_stock" => array(

		"type" => "single_vision_stock",
		"count" => 0

	),
	"single_vision_rx" => array(

		"type" => "single_vision_rx",
		"count" => 0

	),
	"double_vision_stock" => array(

		"type" => "double_vision_stock",
		"count" => 0

	),
	"double_vision_rx" => array(

		"type" => "double_vision_rx",
		"count" => 0

	),
	"progressive_stock" => array(

		"type" => "progressive_stock",
		"count" => 0

	),
	"progressive_rx" => array(

		"type" => "progressive_rx",
		"count" => 0

	),
	"special_order" => array(

		"type" => "special_order",
		"count" => 0

	)

);

// Cycle through prescription types
for ($i=0; $i < sizeOf($arrPrescriptions); $i++) { 

	// Grab current Type
	$curType = array_values($arrPrescriptions)[$i]['type'];	
	$curCount = $arrPrescriptions[$curType]['count'];

	if(isset($arrPRXTypes[$curType])) {

		$arrPRXTypes[$curType]["count"] += $curCount;

	}
	else {

		$arrPRXTypes["special_order"]["count"] += $curCount;

	};

};

////////////////////////////////////////////////////////////////////////////////// SHOW DATA

echo '<div class="loader" id="data-top-blocks" data-items-sold="'.( number_format($totalFramesSold, 0, '.', ',') ).'" data-best-item="'.$bestItemSold.'" data-dispatch-count="'.( number_format($dCount, 0, '.', ',') ).'" data-dispatch-rate="'.$dRate.'" data-dispatch-rate-style="'.$dRateStyle.'" data-items-on-time="'.( number_format($totalFramesOnTime, 0, '.', ',') ).'" data-time-rate="'.$timeRate.'" data-time-rate-style="'.$timeRateStyle.'"></div>';

echo '<div class="loader" id="data-top-blocks-prx" data-with-prx="'.$totalFramesWithPRX.'" data-without-prx="'.$totalFramesWithoutPRX.'"></div>';

echo '<div class="loader" id="data-top-blocks-prx-types" data-sv-stock-count="'.( $arrPRXTypes['single_vision_stock']['count'] ).'" data-sv-rx-count="'.( $arrPRXTypes['single_vision_rx']['count'] ).'" data-dv-stock-count="'.( $arrPRXTypes['double_vision_stock']['count'] ).'" data-dv-rx-count="'.( $arrPRXTypes['double_vision_rx']['count'] ).'" data-p-stock-count="'.( $arrPRXTypes['progressive_stock']['count'] ).'" data-p-rx-count="'.( $arrPRXTypes['progressive_rx']['count'] ).'" data-so-count="'.( $arrPRXTypes['special_order']['count'] ).'"></div>';

?>