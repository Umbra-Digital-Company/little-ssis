<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/dashboard_supervisor/functions_test.php";

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
	
};


// echo "<pre>";
// // print_r($arrTopFrames);
// echo "</pre>";
// exit;
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

////////////////////////////////////////////////////////////////////////////////// REASONS

// Grab reasons
$arrReasons = grabReasons();

// Set up sorted arrays
$arrReasonsFrameOnly = array(

	"purchased as gift" => array(

		"reason" => "Purchased as Gift",
		"count" => 0

	),
	"lens transfer" => array(

		"reason" => "Lens Transfer",
		"count" => 0

	),
	"lens from other optical brand" => array(

		"reason" => "Lens from other Optical Brand",
		"count" => 0

	),
	"intended for fashion use" => array(

		"reason" => "Intended for Fashion Use",
		"count" => 0

	),
	"frame os from other branch" => array(

		"reason" => "Frame OS from other Branch",
		"count" => 0

	),
	"guest checkout" => array(

		"reason" => "Guest Checkout",
		"count" => 0

	)

);

$arrReasonsLensOnly = array(

	"change in grade" => array(

		"reason" => "Change in grade",
		"count" => 0

	),
	"damaged lens out of warranty" => array(

		"reason" => "Damaged Lens (Out of Warranty)",
		"count" => 0			

	),
	"return customer frame only purchase" => array(

		"reason" => "Return customer (Frame only purchase)",
		"count" => 0

	),
	"frame in good condition" => array(

		"reason" => "Frame in good condition",
		"count" => 0

	),
	"received as gift" => array(

		"reason" => "Received as gift",
		"count" => 0

	),
	"others" => array(

		"reason" => "Others",
		"count" => 0

	),

);

// Cycle through reasons array
for ($i=0; $i < sizeOf($arrReasons); $i++) { 

	// Set current data
	$curReason = $arrReasons[$i]['reason'];
	$curOption = $arrReasons[$i]['lens_option'];
	$curCount  = $arrReasons[$i]['count'];

	// Add to count
	if($curOption == 'lens only') {

		if(isset($arrReasonsLensOnly[$curReason])) {

			$arrReasonsLensOnly[$curReason]['count'] = $curCount;

		};

	}
	elseif($curOption == 'without prescription') {

		$arrReasonsFrameOnly[$curReason]['count'] = $curCount;

	};
	
};

////////////////////////////////////////////////////////////////////////////////// PRESCRIPTION TYPES BREAKDOWN

$arrPrescriptions = grabPrescriptions();

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
	
	if($curUpgrade == 'special_order') {

		$curVision = 'special_order';

	};

	// Push to main array
	$arrPRXTypes[$curVision]['lenses'][$curLensCode]['lens_code'] = $curLensCode;
	$arrPRXTypes[$curVision]['lenses'][$curLensCode]['item_name'] = $curItemName;
	$arrPRXTypes[$curVision]['lenses'][$curLensCode]['price'] = $curPrice;
	$arrPRXTypes[$curVision]['lenses'][$curLensCode]['count']++;
	
};

// Cycle through array to sort lenses inner array
for ($i=0; $i < sizeOf($arrPrescriptions); $i++) { 

	$curVision = $arrPrescriptions[$i]['prescription_vision'];
	asort($arrPRXTypes[$curVision]['lenses']);

};

////////////////////////////////////////////////////////////////////////////////// SHOW DATA

echo '<div class="loader" id="data-top-blocks" data-items-sold="'.( number_format($totalFramesSold, 0, '.', ',') ).'" data-items-sold-raw="'.$totalFramesSold.'" data-best-item="'.$bestItemSold.'"></div>';

echo '<div class="loader" id="data-top-blocks-prx" data-with-prx="'.$totalFramesWithPRX.'" data-without-prx="'.$totalFramesWithoutPRX.'"></div>';

echo '<div class="loader" id="data-top-blocks-prx-types" data-sv-stock-count="'.( $arrPRXTypes['single_vision_stock']['count'] ).'" data-sv-rx-count="'.( $arrPRXTypes['single_vision_rx']['count'] ).'" data-dv-stock-count="'.( $arrPRXTypes['double_vision_stock']['count'] ).'" data-dv-rx-count="'.( $arrPRXTypes['double_vision_rx']['count'] ).'" data-p-stock-count="'.( $arrPRXTypes['progressive_stock']['count'] ).'" data-p-rx-count="'.( $arrPRXTypes['progressive_rx']['count'] ).'" data-so-count="'.( $arrPRXTypes['special_order']['count'] ).'"></div>';

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
	if($arrItemsMerch['item_name'] != NULL && $arrItemsMerch['item_name'] != '') {

		$curItem = $arrItemsMerch['item_name'];

	}
	else {

		$curItem = $arrItemsMerch['item_name_sunnies'];

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

////////////////////////////////////////////////////////////////////////////////// FRAMES ADDITIONAL STATISTICS

// Grab additional data
$arrFrameData = grabFrameData();
$arrFrameStats = array(

	"collections" => array(),
	"group_categories" => array(),
	"finish" => array(),
	"general_colors" => array(),
	"materials" => array(),
	"product_seasonality" => array(),
	"shapes" => array(),
	"sizes" => array()

);

// Cycle through data
for ($i=0; $i < sizeOf($arrFrameData); $i++) { 

	// Push to the array - COLLECTIONS
	if($arrFrameData[$i]['collection_code'] != NULL) {

		$arrFrameStats['collections'][$arrFrameData[$i]['collection_code']]['code'] = $arrFrameData[$i]['collection_code'];
		$arrFrameStats['collections'][$arrFrameData[$i]['collection_code']]['name'] = $arrFrameData[$i]['collection_name'];
		$arrFrameStats['collections'][$arrFrameData[$i]['collection_code']]['count']++;

	};

	// Push to the array - GROUP CATEGORIES
	if($arrFrameData[$i]['group_category_code'] != NULL) {

		$arrFrameStats['group_categories'][$arrFrameData[$i]['group_category_code']]['code'] = $arrFrameData[$i]['group_category_code'];
		$arrFrameStats['group_categories'][$arrFrameData[$i]['group_category_code']]['name'] = $arrFrameData[$i]['group_category_name'];
		$arrFrameStats['group_categories'][$arrFrameData[$i]['group_category_code']]['count']++;

	};

	// Push to the array - FINISH
	if($arrFrameData[$i]['finish_code'] != NULL) {

		$arrFrameStats['finish'][$arrFrameData[$i]['finish_code']]['code'] = $arrFrameData[$i]['finish_code'];
		$arrFrameStats['finish'][$arrFrameData[$i]['finish_code']]['name'] = $arrFrameData[$i]['finish_name'];
		$arrFrameStats['finish'][$arrFrameData[$i]['finish_code']]['count']++;

	};

	// Push to the array - GENERAL COLORS
	if($arrFrameData[$i]['general_color_code'] != NULL) {

		$arrFrameStats['general_colors'][$arrFrameData[$i]['general_color_code']]['code'] = $arrFrameData[$i]['general_color_code'];
		$arrFrameStats['general_colors'][$arrFrameData[$i]['general_color_code']]['name'] = $arrFrameData[$i]['general_color_name'];
		$arrFrameStats['general_colors'][$arrFrameData[$i]['general_color_code']]['count']++;

	};

	// Push to the array - MATERIALS
	if($arrFrameData[$i]['material_code'] != NULL) {

		$arrFrameStats['materials'][$arrFrameData[$i]['material_code']]['code'] = $arrFrameData[$i]['material_code'];
		$arrFrameStats['materials'][$arrFrameData[$i]['material_code']]['name'] = $arrFrameData[$i]['material_name'];
		$arrFrameStats['materials'][$arrFrameData[$i]['material_code']]['count']++;

	};

	// Push to the array - PRODUCT SEASONALITY
	if($arrFrameData[$i]['product_seasonality_code'] != NULL) {

		$arrFrameStats['product_seasonality'][$arrFrameData[$i]['product_seasonality_code']]['code'] = $arrFrameData[$i]['product_seasonality_code'];
		$arrFrameStats['product_seasonality'][$arrFrameData[$i]['product_seasonality_code']]['name'] = $arrFrameData[$i]['product_seasonality_name'];
		$arrFrameStats['product_seasonality'][$arrFrameData[$i]['product_seasonality_code']]['count']++;

	};

	// Push to the array - SHAPES
	if($arrFrameData[$i]['shape_code'] != NULL) {

		$arrFrameStats['shapes'][$arrFrameData[$i]['shape_code']]['code'] = $arrFrameData[$i]['shape_code'];
		$arrFrameStats['shapes'][$arrFrameData[$i]['shape_code']]['name'] = $arrFrameData[$i]['shape_name'];
		$arrFrameStats['shapes'][$arrFrameData[$i]['shape_code']]['count']++;

	};

	// Push to the array - SIZES
	if($arrFrameData[$i]['size_code'] != NULL) {

		$arrFrameStats['sizes'][$arrFrameData[$i]['size_code']]['code'] = $arrFrameData[$i]['size_code'];
		$arrFrameStats['sizes'][$arrFrameData[$i]['size_code']]['name'] = $arrFrameData[$i]['size_name'];
		$arrFrameStats['sizes'][$arrFrameData[$i]['size_code']]['count']++;

	};
	
};

////////////////////////////////////////////////////////////////////////////////// SINGLE VISION STOCK

echo 	"<script type=\"text/javascript\">

			google.charts.load('current', {
			  packages: ['bar', 'line', 'corechart', 'table']
			});
			
		</script>";

echo 	'<script type="text/javascript">      	

			function drawChartSVS() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Lens");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPRXTypes['single_vision_stock']) && !empty($arrPRXTypes['single_vision_stock'])) {

	// Set specific array
	$arrSVS = array_values($arrPRXTypes['single_vision_stock']['lenses']);

	// Cycle through lenses array
	for ($i=0; $i < sizeOf($arrSVS); $i++) { 
	
		$curLC = $arrSVS[$i]['lens_code'];
		$curIN = str_replace("SVS-", "", $arrSVS[$i]['item_name']);
		$curCo = $arrSVS[$i]['count'];

		echo 		'["'.( $curLC.' - '.$curIN ).'", '.$curCo.']';

		if($i != (sizeOf($arrSVS) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_svs"));

				chart.draw(data, options);

	      	}

      		drawChartSVS();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// SINGLE VISION RX

echo 	'<script type="text/javascript">      	

			function drawChartSVRX() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Lens");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPRXTypes['single_vision_rx']) && !empty($arrPRXTypes['single_vision_rx'])) {

	// Set specific array
	$arrSVRx = array_values($arrPRXTypes['single_vision_rx']['lenses']);

	// Cycle through lenses array
	for ($i=0; $i < sizeOf($arrSVRx); $i++) { 
	
		$curLC = $arrSVRx[$i]['lens_code'];
		$curIN = str_replace("SVRX-", "", $arrSVRx[$i]['item_name']);
		$curCo = $arrSVRx[$i]['count'];

		echo 		'["'.( $curLC.' - '.$curIN ).'", '.$curCo.']';

		if($i != (sizeOf($arrSVRx) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_svrx"));

				chart.draw(data, options);

	      	}

      		drawChartSVRX();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// DOUBLE VISION STOCK

echo 	'<script type="text/javascript">      	

			function drawChartDVS() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Lens");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPRXTypes['double_vision_stock']) && !empty($arrPRXTypes['double_vision_stock'])) {

	// Set specific array
	$arrDVS = array_values($arrPRXTypes['double_vision_stock']['lenses']);

	// Cycle through lenses array
	for ($i=0; $i < sizeOf($arrDVS); $i++) { 
	
		$curLC = $arrDVS[$i]['lens_code'];
		$curIN = str_replace("DV-", "", $arrDVS[$i]['item_name']);
		$curCo = $arrDVS[$i]['count'];

		echo 		'["'.( $curLC.' - '.$curIN ).'", '.$curCo.']';

		if($i != (sizeOf($arrDVS) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_dvs"));

				chart.draw(data, options);

	      	}

      		drawChartDVS();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// DOUBLE VISION RX

echo 	'<script type="text/javascript">      	

			function drawChartDVRX() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Lens");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPRXTypes['double_vision_rx']) && !empty($arrPRXTypes['double_vision_rx'])) {

	// Set specific array
	$arrDVRx = array_values($arrPRXTypes['double_vision_rx']['lenses']);

	// Cycle through lenses array
	for ($i=0; $i < sizeOf($arrDVRx); $i++) { 
	
		$curLC = $arrDVRx[$i]['lens_code'];
		$curIN = str_replace("DVRX-", "", $arrDVRx[$i]['item_name']);
		$curCo = $arrDVRx[$i]['count'];

		echo 		'["'.( $curLC.' - '.$curIN ).'", '.$curCo.']';

		if($i != (sizeOf($arrDVRx) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_dvrx"));

				chart.draw(data, options);

	      	}

      		drawChartDVRX();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// PROGRESSIVE STOCK

echo 	'<script type="text/javascript">      	

			function drawChartPS() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Lens");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPRXTypes['progressive_stock']) && !empty($arrPRXTypes['progressive_stock'])) {

	// Set specific array
	$arrPS = array_values($arrPRXTypes['progressive_stock']['lenses']);

	// Cycle through lenses array
	for ($i=0; $i < sizeOf($arrPS); $i++) { 
	
		$curLC = $arrPS[$i]['lens_code'];
		$curIN = str_replace("PCS-", "", $arrPS[$i]['item_name']);
		$curCo = $arrPS[$i]['count'];

		echo 		'["'.( $curLC.' - '.$curIN ).'", '.$curCo.']';

		if($i != (sizeOf($arrPS) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_ps"));

				chart.draw(data, options);

	      	}

      		drawChartPS();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// PROGRESSIVE RX

echo 	'<script type="text/javascript">      	

			function drawChartPRX() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Lens");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPRXTypes['progressive_rx']) && !empty($arrPRXTypes['progressive_rx'])) {	

	// Set specific array
	$arrPRx = array_values($arrPRXTypes['progressive_rx']['lenses']);

	// Cycle through lenses array
	for ($i=0; $i < sizeOf($arrPRx); $i++) { 
	
		$curLC = $arrPRx[$i]['lens_code'];
		$curIN = str_replace("PRX-", "", $arrPRx[$i]['item_name']);
		$curCo = $arrPRx[$i]['count'];

		echo 		'["'.( $curLC.' - '.$curIN ).'", '.$curCo.']';

		if($i != (sizeOf($arrPRx) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_prx"));

				chart.draw(data, options);

	      	}

      		drawChartPRX();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// REASONS FRAME ONLY

echo 	'<script type="text/javascript">      	

			function drawChartReasonsFrameOnly() {

				// Create the data table
				var data = new google.visualization.arrayToDataTable([';

// Check if SVS is set
if(!empty($arrReasonsFrameOnly)) {

	echo 			'["Reason", "Count"],';

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrReasonsFrameOnly); $i++) { 
	
		// Set current data
		$curReason  = str_replace(")", "", str_replace("(", "", strtolower(array_values($arrReasonsFrameOnly)[$i]['reason'])));
		$curReasonB = array_values($arrReasonsFrameOnly)[$i]['reason'];
		$curCount  =  $arrReasonsFrameOnly[$curReason]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curReasonB.'", '.$curCount.']';

		if($i != (sizeOf($arrReasonsFrameOnly) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "40%", 
  						"height": "80%"
  					},
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.BarChart(document.getElementById("bar_chart_frame_only"));

				chart.draw(data, options);

	      	}

      		drawChartReasonsFrameOnly();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// REASONS LENS ONLY

echo 	'<script type="text/javascript">      	

			function drawChartReasonsLensOnly() {

				// Create the data table
				var data = new google.visualization.arrayToDataTable([';

// Check if SVS is set
if(!empty($arrReasonsLensOnly)) {

	echo 			'["Reason", "Count"],';

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrReasonsLensOnly); $i++) { 
	
		// Set current data
		$curReason  = str_replace(")", "", str_replace("(", "", strtolower(array_values($arrReasonsLensOnly)[$i]['reason'])));
		$curReasonB = array_values($arrReasonsLensOnly)[$i]['reason'];
		$curCount   =  $arrReasonsLensOnly[$curReason]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curReasonB.'", '.$curCount.']';

		if($i != (sizeOf($arrReasonsLensOnly) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "40%", 
  						"height": "80%"
  					},
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.BarChart(document.getElementById("bar_chart_lens_only"));

				chart.draw(data, options);

	      	}

      		drawChartReasonsLensOnly();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// COLLECTIONS

// Check if collections are set
$arrCollections = array_values($arrFrameStats['collections']);

$columns = array_column($arrCollections, 'count');
array_multisort($columns, SORT_DESC, $arrCollections);

echo 	'<script type="text/javascript">      	

			function drawChartCollections() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Collection");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrCollections)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrCollections); $i++) { 
	
		// Set current data
		$curCollection  = ucwords(str_replace("-", " ", str_replace("_", " ", strtolower($arrCollections[$i]['name']))));
		$curCount   	=  $arrCollections[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curCollection.'", '.$curCount.']';

		if($i != (sizeOf($arrCollections) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_collections"));

				chart.draw(data, options);

	      	}

      		drawChartCollections();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// GROUP / CATEGORIES

// Check if group / categories are set
$arrCategories = array_values($arrFrameStats['group_categories']);

$columns = array_column($arrCategories, 'count');
array_multisort($columns, SORT_DESC, $arrCategories);

echo 	'<script type="text/javascript">      	

			function drawChartCategories() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Group / Category");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrCategories)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrCategories); $i++) { 
	
		// Set current data
		$curCategory  = ucwords(str_replace("-", " ", str_replace("_", " ", strtolower($arrCategories[$i]['name']))));
		$curCount   	=  $arrCategories[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curCategory.'", '.$curCount.']';

		if($i != (sizeOf($arrCategories) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_category"));

				chart.draw(data, options);

	      	}

      		drawChartCategories();

      	</script>';      	

////////////////////////////////////////////////////////////////////////////////// FINISH

// Check if finish types are set
$arrFinish = array_values($arrFrameStats['finish']);

$columns = array_column($arrFinish, 'count');
array_multisort($columns, SORT_DESC, $arrFinish);

echo 	'<script type="text/javascript">      	

			function drawChartFinish() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Finish");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrFinish)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrFinish); $i++) { 
	
		// Set current data
		$curFinish  = ucwords(str_replace("-", " ", str_replace("_", " ", strtolower($arrFinish[$i]['name']))));
		$curCount   =  $arrFinish[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curFinish.'", '.$curCount.']';

		if($i != (sizeOf($arrFinish) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_finish"));

				chart.draw(data, options);

	      	}

      		drawChartFinish();

      	</script>';   

////////////////////////////////////////////////////////////////////////////////// GENERAL COLORS

// Check if generals colors are set
$arrGeneralColors = array_values($arrFrameStats['general_colors']);

$columns = array_column($arrGeneralColors, 'count');
array_multisort($columns, SORT_DESC, $arrGeneralColors);

echo 	'<script type="text/javascript">      	

			function drawChartGeneralColors() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Finish");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrGeneralColors)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrGeneralColors); $i++) { 
	
		// Set current data
		$curGeneralColor  = ucwords(str_replace("-", " ", str_replace("_", " ", strtolower($arrGeneralColors[$i]['name']))));
		$curCount   	  =  $arrGeneralColors[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curGeneralColor.'", '.$curCount.']';

		if($i != (sizeOf($arrGeneralColors) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_general_color"));

				chart.draw(data, options);

	      	}

      		drawChartGeneralColors();

      	</script>';   

////////////////////////////////////////////////////////////////////////////////// MATERIALS

// Check if materials are set
$arrMaterials = array_values($arrFrameStats['materials']);

$columns = array_column($arrMaterials, 'count');
array_multisort($columns, SORT_DESC, $arrMaterials);

echo 	'<script type="text/javascript">      	

			function drawChartMaterials() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Material");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrMaterials)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrMaterials); $i++) { 
	
		// Set current data
		$curMaterial  = ucwords(str_replace("-", " ", str_replace("_", " ", strtolower($arrMaterials[$i]['name']))));
		$curCount     =  $arrMaterials[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curMaterial.'", '.$curCount.']';

		if($i != (sizeOf($arrMaterials) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_material"));

				chart.draw(data, options);

	      	}

      		drawChartMaterials();

      	</script>'; 

////////////////////////////////////////////////////////////////////////////////// PRODUCT SEASONALITY

// Check if product seasonalities are set
$arrProductSeasonality = array_values($arrFrameStats['product_seasonality']);

$columns = array_column($arrProductSeasonality, 'count');
array_multisort($columns, SORT_DESC, $arrProductSeasonality);

echo 	'<script type="text/javascript">      	

			function drawChartProductSeasonality() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Product Seasonality");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrProductSeasonality)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrProductSeasonality); $i++) { 
	
		// Set current data
		$curSeasonality  = ucwords(str_replace("-", " ", str_replace("_", " ", strtolower($arrProductSeasonality[$i]['name']))));
		$curCount    	 =  $arrProductSeasonality[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curSeasonality.'", '.$curCount.']';

		if($i != (sizeOf($arrProductSeasonality) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_product_seasonality"));

				chart.draw(data, options);

	      	}

      		drawChartProductSeasonality();

      	</script>'; 

////////////////////////////////////////////////////////////////////////////////// SHAPES

// Check if shapes are set
$arrShapes = array_values($arrFrameStats['shapes']);

$columns = array_column($arrShapes, 'count');
array_multisort($columns, SORT_DESC, $arrShapes);

echo 	'<script type="text/javascript">      	

			function drawChartShapes() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Shape");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrShapes)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrShapes); $i++) { 
	
		// Set current data
		$curShapes  = ucwords(str_replace("-", " ", str_replace("_", " ", strtolower($arrShapes[$i]['name']))));
		$curCount   =  $arrShapes[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curShapes.'", '.$curCount.']';

		if($i != (sizeOf($arrShapes) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_shape"));

				chart.draw(data, options);

	      	}

      		drawChartShapes();

      	</script>'; 

////////////////////////////////////////////////////////////////////////////////// SIZES

// Check if sizes are set
$arrSizes = array_values($arrFrameStats['sizes']);

$columns = array_column($arrSizes, 'count');
array_multisort($columns, SORT_DESC, $arrSizes);

echo 	'<script type="text/javascript">      	

			function drawChartSizes() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Size");
				data.addColumn("number", "Count");

				data.addRows([';	

if(!empty($arrSizes)) {

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrSizes); $i++) { 
	
		// Set current data
		$curSize  = $arrSizes[$i]['name'];
		$curCount =  $arrSizes[$i]['count'];

		if($curCount == "") {

			$curCount = 0;

		};

		echo 		'["'.$curSize.'", '.$curCount.']';

		if($i != (sizeOf($arrSizes) - 1) ) {

			echo 		',';

		};

	};

}
else {

	echo 			"['No Data', 0]";

};
				
echo 			']);

				// Set chart options
				var options = {
					chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
  					pieHole: 0.4,
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		series: {
	            		0: { color: "#28a745" }
					}
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_size"));

				chart.draw(data, options);

	      	}

      		drawChartSizes();

      	</script>';       	

////////////////////////////////////////////////////////////////////////////////// CALLBACKS      	

echo 	"<script type=\"text/javascript\">

			google.charts.setOnLoadCallback(drawChartSVS);
			google.charts.setOnLoadCallback(drawChartSVRX);
			google.charts.setOnLoadCallback(drawChartDVS);
			google.charts.setOnLoadCallback(drawChartDVRX);
			google.charts.setOnLoadCallback(drawChartPS);
			google.charts.setOnLoadCallback(drawChartPRX);
			google.charts.setOnLoadCallback(drawChartReasonsFrameOnly);
			google.charts.setOnLoadCallback(drawChartReasonsLensOnly);
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