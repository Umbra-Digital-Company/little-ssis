<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/dashboard_shopify_international_v2/functions.php";

////////////////////////////////////////////////////////////////////////////////// Grab Revenue

// Grab all the specs orders
$arrRevenue = grabRevenue();	

// Calculate grand total
$grandTotal = 0;

// Grab all the studios orders
$arrRevenueStudios = grabRevenueStudios();	

// Calculate grand total
$grandTotalStudios = 0;

// Grab all the merch orders
$arrRevenueMerch = grabRevenueMerch();	

// Calculate grand total
$grandTotalMerch = 0;

// Grab all the Anti Rad orders
$arrRevenueAntiRad = grabRevenueAntiRad();

// Calculate grand total
$grandTotalAntiRad= 0;

////////////////////////////////////////////////////////////////////////////////// REVENUE BREAKDOWN - SPECS

echo 	'<ul class="loader" id="data-revenue" style="display: none;">';

for ($i=0; $i < sizeOf($arrRevenue); $i++) { 

	// Set current data
	$curDay 	  = $arrRevenue[$i]['day'];
	$curOrderDate = $arrRevenue[$i]['order_date'];
	$curTotal 	  = $arrRevenue[$i]['total'];
	
	echo 		'<li data-day="'.$curDay.'" data-order-date="'.$curOrderDate.'" data-revenue-total="'.$curTotal.'"></li>';

	$grandTotal += $curTotal;

};

echo 		'<li id="revenue-grand-total" data-grand-total="'.( number_format($grandTotal, 2, '.', ',')  ).'" data-grand-total-raw="'.$grandTotal.'"></li>';
echo 	'</ul>';

////////////////////////////////////////////////////////////////////////////////// REVENUE BREAKDOWN - STUDIOS

for ($i=0; $i < sizeOf($arrRevenueStudios); $i++) { 

	// Set current data
	$curDay 	  = $arrRevenueStudios[$i]['day'];
	$curOrderDate = $arrRevenueStudios[$i]['order_date'];
	$curTotal 	  = $arrRevenueStudios[$i]['total'];	

	$grandTotalStudios += $curTotal;

};

echo '<p id="studios-revenue-grand-total" data-grand-total="'.( number_format($grandTotalStudios, 2, '.', ',')  ).'" data-grand-total-raw="'.$grandTotalStudios.'" style="display: none;"></p>';

////////////////////////////////////////////////////////////////////////////////// REVENUE BREAKDOWN - MERCH

for ($i=0; $i < sizeOf($arrRevenueMerch); $i++) { 

	// Set current data
	$curDay 	  = $arrRevenueMerch[$i]['day'];
	$curOrderDate = $arrRevenueMerch[$i]['order_date'];
	$curTotal 	  = $arrRevenueMerch[$i]['total'];	

	$grandTotalMerch += $curTotal;

};

echo '<p id="merch-revenue-grand-total" data-grand-total="'.( number_format($grandTotalMerch, 2, '.', ',')  ).'" data-grand-total-raw="'.$grandTotalMerch.'" style="display: none;"></p>';

////////////////////////////////////////////////////////////////////////////////// REVENUE BREAKDOWN - ANTI RAD

for ($i=0; $i < sizeOf($arrRevenueAntiRad); $i++) { 

	// Set current data
	$curDay 	  = $arrRevenueAntiRad[$i]['day'];
	$curOrderDate = $arrRevenueAntiRad[$i]['order_date'];
	$curTotal 	  = $arrRevenueAntiRad[$i]['total'];	

	$grandTotalAntiRad += $curTotal;

};

echo '<p id="antirad-revenue-grand-total" data-grand-total="'.( number_format($grandTotalAntiRad, 2, '.', ',')  ).'" data-grand-total-raw="'.$grandTotalAntiRad.'" style="display: none;"></p>';

////////////////////////////////////////////////////////////////////////////////// DAILY SALES

echo 	"<script type=\"text/javascript\">

			google.charts.load('current', {
	  			packages: ['bar', 'line', 'corechart', 'table']
			});
	
		</script>";
		
echo 	'<script type="text/javascript">

			function drawChartDailySales() {

				var data = new google.visualization.DataTable();
  				data.addColumn("string", "Day");
  				data.addColumn("number", "Sales");

  				data.addRows([';


if(isset($_GET['date']) && $_GET['date'] != 'custom') {

switch ($_GET['date']) {
	case 'pmonth':
		$curMonth = date('F', strtotime("-1 months"));
		break;
	case 'month':
		$curMonth 	= date("F");
		break;
	}
}else{
	$curMonth 	= date("F");
}

// Cycle through month days
for ($i=0; $i < $arrMonthDays[$curMonth]; $i++) { 

	$numDay = ($i + 1);
	$revDay = '0';
	foreach ($arrRevenue as $keyRow => $value) {
		if($value['day'] == $numDay){
			if(isset($value['total'])) {

				$revDay = $value['total'];

			}
			break;
		}
	}

	echo 			'["'.$numDay.'", '.$revDay.']';

	if($i != ($arrMonthDays[$curMonth] + 1) ) {

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

  				var chart = new google.visualization.ColumnChart(document.getElementById("bar_chart_rev_daily_sales"));

  				chart.draw(data, options);
  	
			};

			// LOAD GOOGLE CHART		
			drawChartDailySales();

		</script>';

////////////////////////////////////////////////////////////////////////////////// DAILY TRANSACTION

echo 	'<script type="text/javascript">
	
			function drawChartDailyTransactions() {

				var data = new google.visualization.DataTable();
  				data.addColumn("string", "Day");
  				data.addColumn("number", "Count");

  				data.addRows([';

// Cycle through month days
for ($i=0; $i < $arrMonthDays[$curMonth]; $i++) { 

	$numDay = ($i + 1);
	$countDay = '0';
	foreach ($arrRevenue as $keyRow => $value) {
		if($value['day'] == $numDay){
			if(isset($value['count'])) {

				$countDay = $value['count'];

			}
			break;
		}
	}

	echo 			'["'.$numDay.'", '.$countDay.']';

	if($i != ($arrMonthDays[$curMonth] - 1) ) {

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

  				var chart = new google.charts.Line(document.getElementById("line_chart_daily_transactions"));

			  	chart.draw(data, options);
  	
			};

			// LOAD GOOGLE CHARTS		
			drawChartDailyTransactions();

		</script>';

////////////////////////////////////////////////////////////////////////////////// WEEKLY SALES

// Grab number of days
$numDays  = $arrMonthDays[$curMonth];
$numWeeks = floor($numDays / 7);
$extraDays = $numDays - ($numWeeks * 7);

// Set weeks
$arrWeeks[0] = 0;
$arrWeeks[1] = 0;
$arrWeeks[2] = 0;
$arrWeeks[3] = 0;

// Cycle through revenue
for ($i=0; $i < sizeOf($arrRevenue); $i++) { 

	// Segment the revenue
	if($i < 7) {

		$arrWeeks[0] += $arrRevenue[$i]['total'];

	}
	elseif($i > 6 && $i < 14) {

		$arrWeeks[1] += $arrRevenue[$i]['total'];

	}
	elseif($i > 13 && $i < 21) {

		$arrWeeks[2] += $arrRevenue[$i]['total'];
		
	}
	else {

		$arrWeeks[3] += $arrRevenue[$i]['total'];

	};

};

echo 	'<script type="text/javascript">

			function drawChartWeeklySales() {

				var data = new google.visualization.DataTable();
	      		data.addColumn("string", "Week");
	      		data.addColumn("number", "Count");

	      		data.addRows([';	      	

// Cycle through month days
for ($i=0; $i < $numWeeks; $i++) { 

	$numWeek = ($i + 1);

	echo 				'["'.$numWeek.'", '.$arrWeeks[$i].']';

	if($i != ($numWeek + 1) ) {

		echo 			',';

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

	      		var chart = new google.charts.Line(document.getElementById("line_chart_weekly_sales"));

	      		chart.draw(data, options);
	      	
	    	};

	    	drawChartWeeklySales();

	    </script>';

echo 	"<script type=\"text/javascript\">

			google.charts.setOnLoadCallback(drawChartDailySales);
			google.charts.setOnLoadCallback(drawChartDailyTransactions);
			google.charts.setOnLoadCallback(drawChartWeeklySales);
			
		</script>";  

////////////////////////////////////////////////////////////////////////////////// HOURLY SALES	    

if(!isset($_GET['date']) || $_GET['date'] == 'day' || $_GET['date'] == 'yesterday') {

	// Grab revenue by hour
	$arrRev = grabRevenueHours();

	// Set hours array
	$arrRevenueHours = array(

		"10" => array(
			"hour" => "10",
			"total" => 0,
			"count" => 0
		),
		"11" => array(
			"hour" => "11",
			"total" => 0,
			"count" => 0
		),
		"12" => array(
			"hour" => "12",
			"total" => 0,
			"count" => 0
		),
		"13" => array(
			"hour" => "13",
			"total" => 0,
			"count" => 0
		),
		"14" => array(
			"hour" => "14",
			"total" => 0,
			"count" => 0
		),
		"15" => array(
			"hour" => "15",
			"total" => 0,
			"count" => 0
		),
		"16" => array(
			"hour" => "16",
			"total" => 0,
			"count" => 0
		),
		"17" => array(
			"hour" => "17",
			"total" => 0,
			"count" => 0
		),
		"18" => array(
			"hour" => "18",
			"total" => 0,
			"count" => 0
		),
		"19" => array(
			"hour" => "19",
			"total" => 0,
			"count" => 0
		),
		"20" => array(
			"hour" => "20",
			"total" => 0,
			"count" => 0
		),
		"21" => array(
			"hour" => "21",
			"total" => 0,
			"count" => 0
		),
		"22" => array(
			"hour" => "22",
			"total" => 0,
			"count" => 0
		),
		"23" => array(
			"hour" => "23",
			"total" => 0,
			"count" => 0
		)

	);

	// Cycle through revenue
	for ($i=0; $i < sizeOf($arrRev); $i++) { 
		
		// Set current data
		$curHour = $arrRev[$i]['hour'];
		$curTotal = $arrRev[$i]['total'];

		switch ($curHour) {

			case '1':
			case '2':
			case '3':
			case '4':
			case '5':
			case '6':
			case '7':
			case '8':
			case '9':
			case '10':
				$arrRevenueHours['10']['total'] += $curTotal;
				$arrRevenueHours['10']['count']++;
				break;

			case '11':
				$arrRevenueHours['11']['total'] += $curTotal;
				$arrRevenueHours['11']['count']++;
				break;

			case '12':
				$arrRevenueHours['12']['total'] += $curTotal;
				$arrRevenueHours['12']['count']++;
				break;

			case '13':
				$arrRevenueHours['13']['total'] += $curTotal;
				$arrRevenueHours['13']['count']++;
				break;

			case '14':
				$arrRevenueHours['14']['total'] += $curTotal;
				$arrRevenueHours['14']['count']++;
				break;

			case '15':
				$arrRevenueHours['15']['total'] += $curTotal;
				$arrRevenueHours['15']['count']++;
				break;

			case '16':
				$arrRevenueHours['16']['total'] += $curTotal;
				$arrRevenueHours['16']['count']++;
				break;

			case '17':
				$arrRevenueHours['17']['total'] += $curTotal;
				$arrRevenueHours['17']['count']++;
				break;

			case '18':
				$arrRevenueHours['18']['total'] += $curTotal;
				$arrRevenueHours['18']['count']++;
				break;

			case '19':
				$arrRevenueHours['19']['total'] += $curTotal;
				$arrRevenueHours['19']['count']++;
				break;

			case '20':
				$arrRevenueHours['20']['total'] += $curTotal;
				$arrRevenueHours['20']['count']++;
				break;

			case '21':
				$arrRevenueHours['21']['total'] += $curTotal;
				$arrRevenueHours['21']['count']++;
				break;

			case '22':
				$arrRevenueHours['22']['total'] += $curTotal;
				$arrRevenueHours['22']['count']++;
				break;

			case '23':
				$arrRevenueHours['23']['total'] += $curTotal;
				$arrRevenueHours['23']['count']++;
				break;
			
		};

	};

	// Reindex
	$arrRevenueHours = array_values($arrRevenueHours);

	// Echo out data
	echo 	'<script type="text/javascript">

				function drawChartHourly() {

					var data = new google.visualization.DataTable();
	  				data.addColumn("string", "Hour");
	  				data.addColumn("number", "Sales");

	  				data.addRows([';		      		
		
	for ($i=0; $i < sizeOf($arrRevenueHours); $i++) { 
	
		// Set current data
		$curHour  = $arrRevenueHours[$i]['hour'];
		$curTotal = $arrRevenueHours[$i]['total'];

		if($curHour < 12) {

			$apm = "a";

		}
		else {

			$apm = "p";

		};

		echo 			'["'.$curHour.$apm.'", '.$curTotal.']';

		if($i != (sizeOf($arrRevenueHours) - 1) ) {

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

	  				var chart = new google.visualization.ColumnChart(document.getElementById("bar_chart_rev_hourly_sales"));

	  				chart.draw(data, options);
	  	
				};

				// LOAD GOOGLE CHART		
				drawChartHourly();

			</script>';

echo 	"<script type=\"text/javascript\">

			google.charts.setOnLoadCallback(drawChartHourly);
			
		</script>";  

};

?>