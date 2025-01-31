<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/face/includes/dashboard_face/functions.php";

////////////////////////////////////////////////////////////////////////////////// GRAB AGES

// Grab all the customers
$arrCustomersInfo = grabCustomersAges();

echo 	"<script type=\"text/javascript\">

			google.charts.load('current', {
			  packages: ['bar', 'line', 'corechart', 'table']
			});
			
		</script>";

echo 	'<script type="text/javascript">

	    	function drawChartAgeGroups() {

				// Create the data table.
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Age Group");
				data.addColumn("number", "Count");

				data.addRows([';

// Set age groups
$arrAgeGroups = array(

	"0-12"  => array(
		"group" => "0-12",
		"count" => 0
	),
	"13-17" => array(
		"group" => "13-17",
		"count" => 0
	),
	"18-24" => array(
		"group" => "18-24",
		"count" => 0
	),
	"25-34" => array(
		"group" => "25-34",
		"count" => 0
	),
	"35-44" => array(
		"group" => "35-44",
		"count" => 0
	),
	"45-54" => array(
		"group" => "45-54",
		"count" => 0
	),
	"55-64" => array(
		"group" => "55-64",
		"count" => 0
	),
	"65+" => array(
		"group" => "65+",
		"count" => 0
	),
	"guest" => array(
		"group" => "Guest",
		"count" => 0
	)

);

// Set Genders
$cMale   = 0;
$cFemale = 0;
$cNA = 0;
$genderGuest = 0;

// Set Customer Types
$cNew 		= 0;
$cRecurring = 0;
$cGuest = 0;

// Cycle through array
for ($i=0; $i < sizeOf($arrCustomersInfo); $i++) { 

	// Set current age
	$curAge 		 = $arrCustomersInfo[$i]['age_final'];
	$curGender 		 = $arrCustomersInfo[$i]['gender'];
	$curCustomerType = $arrCustomersInfo[$i]['customer_type'];

	if($curAge < 13) {

		$arrAgeGroups['0-12']['count']++;

	}
	elseif($curAge > 12 && $curAge < 18) {

		$arrAgeGroups['13-17']['count']++;

	}
	elseif($curAge > 17 && $curAge < 25) {

		$arrAgeGroups['18-24']['count']++;

	}
	elseif($curAge > 24 && $curAge < 35) {

		$arrAgeGroups['25-34']['count']++;

	}
	elseif($curAge > 34 && $curAge < 45) {

		$arrAgeGroups['35-44']['count']++;

	}
	elseif($curAge > 44 && $curAge < 55) {

		$arrAgeGroups['45-54']['count']++;

	}
	elseif($curAge > 54 && $curAge < 65) {

		$arrAgeGroups['55-64']['count']++;

	}
	elseif($curAge > 64 && $curAge < 101) {

		$arrAgeGroups['65+']['count']++;

	}
	elseif($curAge > 100) {

		$arrAgeGroups['guest']['count']++;

	};
	
	if($curGender == "male") {

		$cMale++;

	}
	elseif($curGender == "female") {

		$cFemale++;

	}
	elseif($curCustomerType == 'guest'){
		$genderGuest++;
	}
	elseif($curGender == "" || $curGender == "N/A") {

		$cNA++;

	}

	if($curCustomerType == 'new') {

		$cNew++;

	}
	elseif($curCustomerType == 'recurring') {

		$cRecurring++;

	}
	elseif($curCustomerType == 'guest') {

		$cGuest++;

	}
	
};

// Cycle through again
for ($i=0; $i < sizeOf($arrAgeGroups); $i++) { 
	
	// Set current data
	$curGroup = array_values($arrAgeGroups)[$i]['group'];
	$curCount = array_values($arrAgeGroups)[$i]['count'];

	echo 			'["'.$curGroup.'", '.$curCount.']';

	if($i != (sizeOf($arrAgeGroups) + 1) ) {

		echo 		',';

	};

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
        			colors: ["#7BDF9D", "#4ECD79", "#2EBE5F", "#09B543", "#038930", "#2A4D14", "#36482e", "#585858" ]
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("pie_chart_age_group"));

				chart.draw(data, options);

      		};

      		drawChartAgeGroups();

      	</script>';

////////////////////////////////////////////////

echo 	'<script type="text/javascript">

			function drawChartAge() {

				var data = new google.visualization.DataTable();
	      		data.addColumn("string", "Age");
	      		data.addColumn("number", "Count");

	      		data.addRows([';
	      			
// Set age breakdown array
$arrAgeBreakdown = array();

// Setup 100 years
for ($i=0; $i < 101; $i++) { 
 	
	$arrAgeBreakdown[$i]['age'] = $i;
	$arrAgeBreakdown[$i]['count'] = 0;

};

// Cycle through and sort ages
for ($i=0; $i < sizeOf($arrCustomersInfo); $i++) { 

	// Set current data
	$curAge = $arrCustomersInfo[$i]['age_final'];
	$arrAgeBreakdown[$curAge]['count']++;
	
};

// Cycle through revenue
for ($i=0; $i < 101; $i++) { 

	echo 			"['".$arrAgeBreakdown[$i]['age']."', ".$arrAgeBreakdown[$i]['count']."]";

	if($i != (sizeOf($arrAgeBreakdown) + 1) ) {

		echo 		',';

	};

};

echo 			']);

	      		var options = {
	      			chartArea: {
  						"width": "80%", 
  						"height": "80%"
  					},
	        		axes: {
	          			x: {
		            		0: {side: "bottom"}
	          			}
        			},
	        		legend: {
	        			position: "none"
	        		},
	        		backgroundColor: {
	        			fill: "#f7f7f7"
	        		},
	        		colors: ["#28a745"]
		      	};

	      		var chart = new google.charts.Line(document.getElementById("line_chart_age"));

	      		chart.draw(data, google.charts.Line.convertOptions(options));
	      	
	    	};

      		drawChartAge();

      	</script>';

////////////////////////////////////////////////

$arrLocationsBreakdown = array();
$arrLocationsBreakdownINT = array();

// Cycle through Customer Info
for ($i=0; $i < sizeOf($arrCustomersInfo); $i++) { 

	// Set current location
	$curCity 	= $arrCustomersInfo[$i]['city'];
	$curCountry = $arrCustomersInfo[$i]['country'];

	if($curCity != 'N/A' && $curCity != '' && $curCity != NULL) {		

		$arrLocationsBreakdown[$curCity]['city'] = $curCity;
		$arrLocationsBreakdown[$curCity]['count']++;

	}
	elseif($curCity == 'N/A' && $curCountry != 'philippines') {

		$arrLocationsBreakdownINT[$curCountry]['country'] = $arrCustomersInfo[$i]['country'];
		$arrLocationsBreakdownINT[$curCountry]['count']++;

	};

};

// Sort arrays
usort($arrLocationsBreakdown, function($a, $b) {

    if($a['count']==$b['count']) return 0;

    return $a['count'] < $b['count']?1:-1;
    
});	
usort($arrLocationsBreakdownINT, function($a, $b) {

    if($a['count']==$b['count']) return 0;

    return $a['count'] < $b['count']?1:-1;
    
});	

// Check if store filters are set
if(isset($_GET['filterStores'])) {

	$numLocations = sizeOf($arrLocationsBreakdown);
	$numLocationsInt = sizeOf($arrLocationsBreakdownINT);

}
else {

	if(sizeOf($arrLocationsBreakdown) < 10) {

		$numLocations = sizeOf($arrLocationsBreakdown);

	}
	else {

		$numLocations = 10;

	};

	if(sizeOf($arrLocationsBreakdownINT) < 10) {

		$numLocationsInt = sizeOf($arrLocationsBreakdownINT);

	}
	else {

		$numLocationsInt = 10;

	};	

};

// Philippines
echo 	'<script type="text/javascript">      	

			function drawChartLocations() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "City");
				data.addColumn("number", "Count");

				data.addRows([';		

for ($i=0; $i < $numLocations; $i++) { 

	$curCity = ucwords(str_replace("-", " ", array_values($arrLocationsBreakdown)[$i]['city']));

	echo 			"['".$curCity."', ".array_values($arrLocationsBreakdown)[$i]['count']."]";

	if($i != ($numLocations - 1) ) {

		echo 		',';

	};

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
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_locations"));

				chart.draw(data, options);

	      	}

      		drawChartLocations();

      	</script>';

// International
echo 	'<script type="text/javascript">      	

			function drawChartLocationsInternational() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Country");
				data.addColumn("number", "Count");

				data.addRows([';		

for ($i=0; $i < $numLocationsInt; $i++) { 

	$curCountry = ucwords(str_replace("-", " ", $arrLocationsBreakdownINT[$i]['country']));

	echo 			"['".$curCountry."', ".$arrLocationsBreakdownINT[$i]['count']."]";

	if($i != ($numLocationsInt - 1) ) {

		echo 		',';

	};

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
				var chart = new google.visualization.PieChart(document.getElementById("donut_chart_locations_international"));

				chart.draw(data, options);

	      	}

      		drawChartLocationsInternational();

      	</script>';

echo 	"<script type=\"text/javascript\">

			google.charts.setOnLoadCallback(drawChartAgeGroups);
			google.charts.setOnLoadCallback(drawChartAge);
			google.charts.setOnLoadCallback(drawChartLocations);
			google.charts.setOnLoadCallback(drawChartLocationsInternational);
			
		</script>";      	

////////////////////////////////////////////////////////////////////////////////// GRAB GENDERS

echo '<div class="loader" id="data-genders" data-male="'.$cMale.'" data-female="'.$cFemale.'" data-guest="'.$genderGuest.'" data-na="'.$cNA.'"></div>';
echo '<div class="loader" id="data-customer-types" data-new-customer="'.$cNew.'" data-recurring-customer="'.$cRecurring.'" data-guest-customer="'.$cGuest.'"></div>';

?>