<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/supervisor/functions.php";

////////////////////////////////////////////////////////////////////////////////// TOP DATA BLOCKS

// Grab all the specs orders
$arrTopFrames = grabBestFrames();

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
	$totalFramesNone 	+= $arrTopFrames[$i]['count_none'];
	
};

// Grab Total Frames Special Order
$totalFramesSO = $totalFramesSold - ($totalFramesSVStock + $totalFramesSVRx + $totalFramesDVStock + $totalFramesDVRx + $totalFramesPStock + $totalFramesPRx + $totalFramesNone);

/////////////// top selling frame

if(!empty($arrTopFrames)) {

	if($arrTopFrames[0]['product_code'] == 'F100') {

		$fID = 1;

	}
	else {

		$fID = 0;

	};

	if($fID == 1 && $arrTopFrames[1]['product_code'] == 'M100') {

		$fID = 2;

	}

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
		$curIN = $arrSVS[$i]['item_name'];
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
		$curIN = $arrSVRx[$i]['item_name'];
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
		$curIN = $arrDVS[$i]['item_name'];
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
		$curIN = $arrDVRx[$i]['item_name'];
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
		$curIN = $arrPS[$i]['item_name'];
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
		$curIN = $arrPRx[$i]['item_name'];
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

echo 	"<script type=\"text/javascript\">

			google.charts.setOnLoadCallback(drawChartSVS);
			google.charts.setOnLoadCallback(drawChartSVRX);
			google.charts.setOnLoadCallback(drawChartDVS);
			google.charts.setOnLoadCallback(drawChartDVRX);
			google.charts.setOnLoadCallback(drawChartPS);
			google.charts.setOnLoadCallback(drawChartPRX);
			google.charts.setOnLoadCallback(drawChartReasonsFrameOnly);
			google.charts.setOnLoadCallback(drawChartReasonsLensOnly);
			
		</script>";  

?>