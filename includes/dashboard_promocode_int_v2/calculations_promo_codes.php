<?php 

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/dashboard_promocode_int_v2/functions.php";

//get Performing Promo Codes
$arrPerformingcodes = grabPerformingCodes();

////////////////////////////////////////////////////////////////////////////////// REASONS PROMO CODES

echo 	'<script type="text/javascript">      	

			function drawChartPerformingCodes() {

				// Create the data table
				var data = new google.visualization.arrayToDataTable([';

// Check if SVS is set
if(!empty($arrPerformingcodes)) {

	echo 			'["Promo Code", "Count"],';

	// Cycle through Frame Only Reasons array
	for ($i=0; $i < sizeOf($arrPerformingcodes); $i++) { 
	
		// Set current data

		echo 		'["'.$arrPerformingcodes[$i]['promo_code'].'", '.$arrPerformingcodes[$i]['count'].']';

		if($i != (sizeOf($arrPerformingcodes) - 1) ) {

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
				var chart = new google.visualization.BarChart(document.getElementById("bar_chart_performing_codes"));

				chart.draw(data, options);

	      	}

      		drawChartPerformingCodes();

      	</script>';

////////////////////////////////////////////////////////////////////////////////// CALLBACKS      	

echo 	"<script type=\"text/javascript\">

			google.charts.setOnLoadCallback(drawChartPerformingCodes);
			
		</script>";  

?>