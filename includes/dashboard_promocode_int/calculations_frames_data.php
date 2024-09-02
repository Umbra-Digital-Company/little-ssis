<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/dashboard_promocode_int/functions.php";

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