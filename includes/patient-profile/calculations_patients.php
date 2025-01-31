<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/patient-profile/functions.php";

////////////////////////////////////////////////////////////////////////////////// GRAB TOP DATA

// Load Google Chart packages
echo 	"<script type=\"text/javascript\">
					
			google.charts.load('current', {
			  packages: ['bar', 'line', 'corechart', 'table']
			});
			
			google.charts.setOnLoadCallback(drawTableAllOccupations);

		</script>";

// Grab all the customers
$arrPatientProfiles = grabPatients();

// Set variables
$numContactLensYes = 0;
$numContactLensNo  = 0;
$numMoreThan5 	   = 0;
$numLessThan5 	   = 0;
$numInsuranceYes   = 0;
$numInsuranceNo    = 0;

// Set array
$arrOccupations    = array();

// Cycle through array
for ($i=0; $i < sizeOf($arrPatientProfiles); $i++) { 

	// Set current occupation
	$curOccupation = str_replace(" ", "-", strtolower($arrPatientProfiles[$i]['occupation']));
	
	// Contact Lens
	if(strtolower($arrPatientProfiles[$i]['contact_lens']) == 'yes') {

		$numContactLensYes++;

	}
	elseif(strtolower($arrPatientProfiles[$i]['contact_lens']) == 'no') {

		$numContactLensNo++;

	}

	// Number of Sleep Hours
	if(strtolower($arrPatientProfiles[$i]['sleep_time']) == 'yes') {

		$numMoreThan5++;

	}
	elseif(strtolower($arrPatientProfiles[$i]['sleep_time']) == 'no') {

		$numLessThan5++;

	}

	// Number of Insurance
	if(strtolower($arrPatientProfiles[$i]['insurance']) == 'yes') {

		$numInsuranceYes++;

	}
	elseif(strtolower($arrPatientProfiles[$i]['insurance']) == 'no') {

		$numInsuranceNo++;

	}

	// Occupations
	if($curOccupation != 'n/a' && $curOccupation != '' && $curOccupation != 'na' && $curOccupation != 'none') {

		$arrOccupations[$curOccupation]['occupation'] = $curOccupation;
		$arrOccupations[$curOccupation]['count']++;

	}

};

// Reindex occupations
$arrOccupations = array_values($arrOccupations);

// Sort array DESCENDING
foreach ($arrOccupations as $key => $row) {

    $name[$key]  = strtolower($row['count']);

};

// Multisort the cart
array_multisort($name, SORT_DESC, $arrOccupations);

//////////////////////////// CONTACT LENS

echo 	'<script type="text/javascript">      	

			function drawChartContactLens() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Answer");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPatientProfiles) && !empty($arrPatientProfiles)) {

	echo 		'["Yes - '.$numContactLensYes.'", '.$numContactLensYes.'],';
	echo 		'["No - '.$numContactLensNo.'", '.$numContactLensNo.']';

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
	        		colors: ["#28a745", "#585858" ]
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("chart_contact_lens"));

				chart.draw(data, options);

	      	}

      		drawChartContactLens();

      	</script>';

//////////////////////////// SLEEP TIME

echo 	'<script type="text/javascript">      	

			function drawChartSleepTime() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Answer");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPatientProfiles) && !empty($arrPatientProfiles)) {

	echo 		'["More than 5 - '.$numMoreThan5.'", '.$numMoreThan5.'],';
	echo 		'["Less than 5 - '.$numLessThan5.'", '.$numLessThan5.']';

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
	        		colors: ["#28a745", "#585858" ]
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("chart_hours_of_sleep"));

				chart.draw(data, options);

	      	}

      		drawChartSleepTime();

      	</script>';      	

//////////////////////////// INSURANCE

echo 	'<script type="text/javascript">      	

			function drawChartInsurance() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Answer");
				data.addColumn("number", "Count");

				data.addRows([';	

// Check if SVS is set
if(isset($arrPatientProfiles) && !empty($arrPatientProfiles)) {

	echo 		'["Yes - '.$numInsuranceYes.'", '.$numInsuranceYes.'],';
	echo 		'["No - '.$numInsuranceNo.'", '.$numInsuranceNo.']';

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
	        		colors: ["#28a745", "#585858" ]
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("chart_insurance"));

				chart.draw(data, options);

	      	}

      		drawChartInsurance();

      	</script>';      	

//////////////////////////// TOP 10 OCCUPATIONS

echo 	'<script type="text/javascript">      	

			function drawChartTopTen() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Answer");
				data.addColumn("number", "Count");

				data.addRows([';	

// Cycle through occupation
for ($i=0; $i < 10; $i++) { 

	// Set current data
	$curOccupation = ucwords(str_replace("-", " ", $arrOccupations[$i]['occupation']));
	$curCount 	   = $arrOccupations[$i]['count'];

	echo 			'["'.$curOccupation.' - '.$curCount.'", '.$curCount.']';

	if($i != 9) {

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
	        		colors: ["#28a745", "#585858" ]
				};

				// Instantiate and draw our chart, passing in some options.
				var chart = new google.visualization.PieChart(document.getElementById("chart_top_ten_occupations"));

				chart.draw(data, options);

	      	}

      		drawChartTopTen();

      	</script>';  

//////////////////////////// ALL OCCUPATIONS

echo 	'<script type="text/javascript">      	

			google.charts.setOnLoadCallback(drawTableAllOccupations);

			function drawTableAllOccupations() {

				// Create the data table
				var data = new google.visualization.DataTable();
				data.addColumn("string", "Occupation");
				data.addColumn("number", "Count");

				data.addRows([';	

// Cycle through occupation
for ($i=0; $i < 100; $i++) { 

	// Set current data
	$curOccupation = ucwords(str_replace("-", " ", $arrOccupations[$i]['occupation']));
	$curCount 	   = $arrOccupations[$i]['count'];

	echo 			'["'.$curOccupation.'", '.$curCount.']';

	if($i != 99) {

		echo 		',';

	};

};
				
echo 			']);

				// Instantiate and draw our chart, passing in some options.				
				var table = new google.visualization.Table(document.getElementById("table_all_occupations"));

				table.draw(data, {showRowNumber: true, width: "100%", height: "100%"});

	      	}


      	</script>';      	

?>