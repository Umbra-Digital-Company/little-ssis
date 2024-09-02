<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/dashboard_affiliate_v2/functions.php";

////////////////////////////////////////////////////////////////////////////////// TOP DATA BLOCKS - SPECS

// Grab all the specs orders
$arrTopFrames = grabItemsSoldSpecs();

/////////////// frames data
$totalFramesSold 	   = 0;
$totalFramesDispatched = 0;
$totalFramesCancelled  = 0;
$totalFramesReturned   = 0;

$totalFramesWithPRX    = 0;
$totalFramesWithoutPRX = 0;

$totalFramesSVStock = 0;
$totalFramesSVRx 	= 0;
$totalFramesDVStock = 0;
$totalFramesDVRx 	= 0;
$totalFramesPStock  = 0;
$totalFramesPRx 	= 0;
$totalFramesSO 		= 0;
$totalFramesNone 	= 0;


$totalFramesSOSV	= 0;
$totalFramesSODV 	= 0;
$totalFramesSOPV 	= 0;

$totalFramesEssilor 	= 0;
$totalFramesHousebrand	= 0;
$totalSumEssilor 	= 0;
$totalSumHousebrand	= 0;

// Cycle through array
for ($i=0; $i < sizeOf($arrTopFrames); $i++) { 

	// Sold
	$totalFramesSold += $arrTopFrames[$i]['count_complete'];
	$totalFramesSold += $arrTopFrames[$i]['count_dispatched'];
	$totalFramesSold += $arrTopFrames[$i]['count_paid'];
	$totalFramesSold += $arrTopFrames[$i]['count_received'];

	// Dispatched
	$totalFramesDispatched += $arrTopFrames[$i]['count_dispatched'];

	// Cancelled
	$totalFramesCancelled += $arrTopFrames[$i]['count_cancelled'];

	// Returned
	$totalFramesReturned += $arrTopFrames[$i]['count_return'];
	$totalFramesReturned += $arrTopFrames[$i]['count_returned'];

	// With Prescription
	$totalFramesWithPRX += $arrTopFrames[$i]['count_with_prescription'];	
	$totalFramesWithPRX += $arrTopFrames[$i]['count_lens_only'];

	// Without Prescription
	$totalFramesWithoutPRX += $arrTopFrames[$i]['count_without_prescription'];

	// Prescription Types
	$totalFramesSVStock += $arrTopFrames[$i]['count_single_vision_stock'];
	$totalFramesSVRx 	+= $arrTopFrames[$i]['count_single_vision_rx'];
	$totalFramesDVStock += $arrTopFrames[$i]['count_double_vision_stock'];
	$totalFramesDVRx 	+= $arrTopFrames[$i]['count_double_vision_rx'];
	$totalFramesPStock 	+= $arrTopFrames[$i]['count_progressive_stock'];
	$totalFramesPRx 	+= $arrTopFrames[$i]['count_progressive_rx'];
	$totalFramesSO 		+= $arrTopFrames[$i]['count_special_order'];
	$totalFramesNone 	+= $arrTopFrames[$i]['count_none'];



	$totalFramesSOSV	+= $arrTopFrames[$i]['count_special_sv'];
	$totalFramesSODV 	+= $arrTopFrames[$i]['count_special_dv'];
	$totalFramesSOPV 	+= $arrTopFrames[$i]['count_special_px'];

	$totalFramesEssilor 	+= $arrTopFrames[$i]['count_essilor'];
	$totalFramesHousebrand 	+= $arrTopFrames[$i]['count_housebrand'];
	$totalSumEssilor 		+= $arrTopFrames[$i]['sum_essilor'];
	$totalSumHousebrand 	+= $arrTopFrames[$i]['sum_housebrand'];
	
};

/////////////// top selling frame

if(!empty($arrTopFrames)) {

	$fID = 0;

	if($arrTopFrames[$fID]['product_code'] == 'F100') {

		$fID = 1;

	};

	$bestFrame = ucwords(strtolower($arrTopFrames[$fID]['item_name']));
	// $bestFrame = str_replace("No Frame / Lens Only Transaction", "Frame Only", $bestFrame);

	$bestItemSold = $bestFrame;

}
else {

	$bestItemSold = 'N/A';

};


////////////////////////////////////////////////////////////////////////////////// PRESCRIPTION TYPES BREAKDOWN

$arrPrescriptions = grabPrescriptions();
$arrTopLenses = array();

// Fill up top lenses
for ($i=0; $i < sizeOf($arrPrescriptions); $i++) { 

	// Set current data
	$curVision 	 = $arrPrescriptions[$i]['prescription_vision'];
	$curLensCode = $arrPrescriptions[$i]['lens_code'];
	$curItemName = ucwords(str_replace("pcrx-", "",str_replace("pcs-", "",str_replace("dvrx-", "",str_replace("dv-", "",str_replace("svrx-", "",str_replace("svs-", "", strtolower($arrPrescriptions[$i]['item_name']))))))));	
	$curUpgrade  = $arrPrescriptions[$i]['product_upgrade'];	
	$curPrice 	 = $arrPrescriptions[$i]['price'];

	// Add in to the top lenses array
	$arrTopLenses[$curLensCode]["lens_code"] 	   = $curLensCode;
	$arrTopLenses[$curLensCode]["item_name"] 	   = $curItemName;
	$arrTopLenses[$curLensCode]["product_upgrade"] = $curUpgrade;
	$arrTopLenses[$curLensCode]["count"]++;
	
};

$bestLensSold = "";

// Reindex top lenses
$arrTopLenses = array_values($arrTopLenses);

// Sort
usort($arrTopLenses, function($a, $b) {
    return $b['count'] <=> $a['count'];
});

$bestLensSold = $arrTopLenses[0]["item_name"];

/////////////// prescription types

// Set general prescription types array
$arrPRXTypes = array(

	"single_vision_stock" => array(

		"type" => "single_vision_stock",
		"count" => $totalFramesSVStock,
		"lenses" => array()

	),
	"single_vision_rx" => array(

		"type" => "single_vision_rx",
		"count" => $totalFramesSVRx,
		"lenses" => array()

	),
	"double_vision_stock" => array(

		"type" => "double_vision_stock",
		"count" => $totalFramesDVStock,
		"lenses" => array()

	),
	"double_vision_rx" => array(

		"type" => "double_vision_rx",
		"count" => $totalFramesDVRx,
		"lenses" => array()

	),
	"progressive_stock" => array(

		"type" => "progressive_stock",
		"count" => $totalFramesPStock,
		"lenses" => array()

	),
	"progressive_rx" => array(

		"type" => "progressive_rx",
		"count" => $totalFramesPRx,
		"lenses" => array()

	),
	"special_order" => array(

		"type" => "special_order",
		"count" => $totalFramesSO,
		"lenses" => array()

	)	,
	"special_order_single_vision" => array(

		"type" => "special_order_single_vision",
		"count" => $totalFramesSOSV,
		"lenses" => array()

	)
	,
	"special_order_double_vision" => array(

		"type" => "special_order_double_vision_order",
		"count" =>$totalFramesSODV,
		"lenses" => array()

	)
	,
	"special_order_progressive" => array(

		"type" => "special_order_progressive",
		"count" => $totalFramesSOPV,
		"lenses" => array()

	),
	"essilor" => array(

		"type" => "essilor",
		"count" => $totalFramesEssilor,
		"sum" => $totalSumEssilor,
		"lenses" => array()

	),
	"house_brand" => array(

		"type" => "house_brand",
		"count" => $totalFramesHousebrand,
		"sum" => $totalSumHousebrand,
		"lenses" => array()

	)

);


// Cycle through prescription POs
for ($i=0; $i < sizeOf($arrPrescriptions); $i++) { 

	// Set current data
	$curVision 	 = $arrPrescriptions[$i]['prescription_vision'];
	$curLensCode = $arrPrescriptions[$i]['lens_code'];
	$curItemName = $arrPrescriptions[$i]['item_name'];	
	$curUpgrade  = $arrPrescriptions[$i]['product_upgrade'];	
	$curPrice 	 = $arrPrescriptions[$i]['price'];	

	// Set current temp array
	$curTmpArr = array(

		"lens_code" => $curLensCode,
		"item_name" => $curItemName,
		"price" => $curPrice

	);
	
	if( ($curUpgrade == 'special_order' && ($curLensCode!='SO001' || $curLensCode!='SO002' || $curLensCode!='SO003') ) || $curLensCode == 'L052') {

		$curVision = 'special_order';

	};

	// Push to main array
	
	$arrPRXTypes[$curVision]['lenses'][$curLensCode]['lens_code'] = $curLensCode;
	$arrPRXTypes[$curVision]['lenses'][$curLensCode]['item_name'] = $curItemName;
	$arrPRXTypes[$curVision]['lenses'][$curLensCode]['price'] = $curPrice;

	if(!isset($arrPRXTypes[$curVision]['lenses'][$curLensCode]['count'])) {

		$arrPRXTypes[$curVision]['lenses'][$curLensCode]['count'] = 1;

	}
	else {

		$arrPRXTypes[$curVision]['lenses'][$curLensCode]['count']++;

	};
	
};

// Cycle through array to sort lenses inner array
for ($i=0; $i < sizeOf($arrPrescriptions); $i++) { 

	$curVision = $arrPrescriptions[$i]['prescription_vision'];

	if($arrPrescriptions[$i]['product_upgrade'] == 'special_order'  && ($curLensCode!='SO001' || $curLensCode!='SO002' || $curLensCode!='SO003') ) {

		$curVision = 'special_order';

	};

	asort($arrPRXTypes[$curVision]['lenses']);

};

////////////////////////////////////////////////////////////////////////////////// SHOW DATA

echo '<div class="loader" id="data-top-blocks" data-items-sold="'.( number_format($totalFramesSold, 0, '.', ',') ).'" data-items-sold-raw="'.$totalFramesSold.'" data-best-item="'.$bestItemSold.'" data-best-lens="'.$bestLensSold.'"></div>';

echo '<div class="loader" id="data-top-blocks-prx" data-with-prx="'.$totalFramesWithPRX.'" data-without-prx="'.$totalFramesWithoutPRX.'"></div>';

echo '<div class="loader" id="data-top-blocks-prx-types" data-sv-stock-count="'.( $arrPRXTypes['single_vision_stock']['count'] ).'"
 data-sv-rx-count="'.( $arrPRXTypes['single_vision_rx']['count'] ).'" data-dv-stock-count="'.( $arrPRXTypes['double_vision_stock']['count'] ).'" data-dv-rx-count="'.( $arrPRXTypes['double_vision_rx']['count'] ).'" 
data-p-stock-count="'.( $arrPRXTypes['progressive_stock']['count'] ).'" data-p-rx-count="'.( $arrPRXTypes['progressive_rx']['count'] ).'"
 data-so-count="'.( $arrPRXTypes['special_order']['count'] ).'"
 data-sos-count="'.( $arrPRXTypes['special_order_single_vision']['count'] ).'"
 data-sodv-count="'.( $arrPRXTypes['special_order_double_vision']['count'] ).'"
 data-sop-count="'.( $arrPRXTypes['special_order_progressive']['count'] ).'"
 data-essilor="'.( $arrPRXTypes['essilor']['count'] ).'"
 data-sum-essilor="'.( $arrPRXTypes['essilor']['sum'] ).'"
 data-housebrand="'.( $arrPRXTypes['house_brand']['count'] ).'"
 data-sum-housebrand="'.( $arrPRXTypes['house_brand']['sum'] ).'"
 ></div>';

////////////////////////////////////////////////////////////////////////////////// TOP DATA BLOCKS - STUDIOS

// Grab all the Studios orders
$arrItemsStudios = grabItemsSoldStudios();

/////////////// count frames

$totalStudiosCount = 0;

for ($i=0; $i < sizeOf($arrItemsStudios); $i++) { 

	$totalStudiosCount += $arrItemsStudios[$i]['count_frames'];
	
};

/////////////// top selling frame

if(!empty($arrItemsStudios)) {

	$bestStudiosFrame = ucwords(strtolower($arrItemsStudios[0]['item_name']));

}
else {

	$bestStudiosFrame = 'N/A';

};

////////////////////////////////////////////////////////////////////////////////// SHOW DATA STUDIOS

echo '<div class="loader" id="data-top-blocks-studios" data-studios-items-sold="'.( number_format($totalStudiosCount, 0, '.', ',') ).'" data-studios-items-sold-raw="'.$totalStudiosCount.'" data-best-studios-item="'.$bestStudiosFrame.'"></div>';

////////////////////////////////////////////////////////////////////////////////// TOP DATA BLOCKS - MERCH

// Grab all the Merch orders
$arrItemsMerch = grabItemsSoldMerch();

/////////////// count frames

$totalMerchCount = 0;

for ($i=0; $i < sizeOf($arrItemsMerch); $i++) { 

	// Set current data
	if($arrItemsMerch[$i]['item_name'] != NULL && $arrItemsMerch[$i]['item_name'] != '') {

		$curItem = $arrItemsMerch[$i]['item_name'];

	}
	else {

		$curItem = $arrItemsMerch[$i]['item_name_sunnies'];

	};	

	$totalMerchCount += $arrItemsMerch[$i]['count_merch'];
	
};

/////////////// top selling frame

if(!empty($arrItemsMerch)) {

	$bestMerchItem = ucwords(strtolower($arrItemsMerch[0]['item_name']));

}
else {

	$bestMerchItem = 'N/A';

};

////////////////////////////////////////////////////////////////////////////////// SHOW DATA MERCH

echo '<div class="loader" id="data-top-blocks-merch" data-merch-items-sold="'.( number_format($totalMerchCount, 0, '.', ',') ).'" data-merch-items-sold-raw="'.$totalMerchCount.'" data-best-merch-item="'.$bestMerchItem.'"></div>';

////////////////////////////////////////////////////////////////////////////////// TOP DATA BLOCKS - SERVICES

// Grab all the Merch orders
$arrItemsServices = grabItemsSoldServices();

/////////////// count frames

$totalMerchCount = 0;

for ($i=0; $i < sizeOf($arrItemsServices); $i++) { 

	// Set current data
	if($arrItemsServices[$i]['item_name'] != NULL && $arrItemsServices[$i]['item_name'] != '') {

		$curItem = $arrItemsServices[$i]['item_name'];

	}
	else {

		$curItem = $arrItemsServices[$i]['item_name_sunnies'];

	};	

	$totalServicesCount += $arrItemsServices[$i]['count_merch'];
	
};

/////////////// top selling frame

if(!empty($arrItemsServices)) {

	$bestServicesItem = ucwords(strtolower($arrItemsServices[0]['item_name']));

}
else {

	$bestServicesItem = 'N/A';

};

////////////////////////////////////////////////////////////////////////////////// SHOW DATA SERVICES

echo '<div class="loader" id="data-top-blocks-services" data-services-items-sold="'.( number_format($totalServicesCount, 0, '.', ',') ).'" data-services-items-sold-raw="'.$totalServicesCount.'" data-best-services-item="'.$bestServicesItem.'"></div>';

////////////////////////////////////////////////////////////////////////////////// SINGLE VISION STOCK

echo 	"<script type=\"text/javascript\">

			google.charts.load('current', {
			  packages: ['bar', 'line', 'corechart', 'table']
			});
			
		</script>";
////////////////////////////////////////////////////////////////////////////////// CALLBACKS      	

echo 	"<script type=\"text/javascript\">
			google.charts.setOnLoadCallback(drawChartCollections);
			google.charts.setOnLoadCallback(drawChartCategories);
			google.charts.setOnLoadCallback(drawChartFinish);
			google.charts.setOnLoadCallback(drawChartGeneralColors);
			google.charts.setOnLoadCallback(drawChartMaterials);
			google.charts.setOnLoadCallback(drawChartProductSeasonality);
			google.charts.setOnLoadCallback(drawChartShapes);
			google.charts.setOnLoadCallback(drawChartSizes);
			
		</script>";  

?>