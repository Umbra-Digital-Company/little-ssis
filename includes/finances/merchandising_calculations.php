<?php 

//////////////////////////////////////////////////////////////////////////////////// 
// DATA CALCULATIONS
//////////////////////////////////////////////////////////////////////////////////// 

//////////////////////////////////////////////////////////////////////////////////// DATE & TIME

$time = date('Y-m-d h:i:s');

if(isset($_GET['date']) && $_GET['date'] != 'custom') {

	switch ($_GET['date']) {

		case 'day':
			$date = date('F d, Y');
			break;

		case 'week':
			$w = date('w');
			$week_start = date('F d, Y', strtotime('-'.$w.' days'));
			$week_end = date('F d, Y', strtotime('+'.(6-$w).' days'));
			$date = $week_start." - ".$week_end;
			break;

		case 'month':
			$date = date('F, Y');
			break;

		case 'year':
			$date = date('Y');
			break;

		case 'all-time':
			$date = "All Time";
			break;

	}

}
elseif(isset($_GET['data_range_start_month'])) {
	
	$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
	$dateA = date('F d, Y', strtotime($dateStart));

	if(isset($_GET['data_range_end_month'])) {

		$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
		$dateB = date('F d, Y', strtotime($dateEnd));

	}
	else {

		$dateB = date('F d, Y', strtotime(now()));

	};

	$date = $dateA." - ".$dateB;

}
else {

	$date = date('F d, Y');

};

//////////////////////////////////////////////////////////////////////////////////// HISTORICAL DATA

/////////////// 3 Year Sales Table
$arrRevInit = grabRevenue();
$arrRev3Y = array();
$arrMonths = array(

	"01" => 0,
	"02" => 0,
	"03" => 0,
	"04" => 0,
	"05" => 0,
	"06" => 0,
	"07" => 0,
	"08" => 0,
	"09" => 0,
	"10" => 0,
	"11" => 0,
	"12" => 0

);

// Cycle through Revenue array
for ($i=0; $i < sizeOf($arrRevInit); $i++) { 

	// Insert data
	$curYear = $arrRevInit[$i]['year'];
	$arrRev3Y[$curYear]['year'] = $curYear;
	$arrRev3Y[$curYear]['totals'] = $arrMonths;
	
};

// Cycle through Revenue array
for ($i=0; $i < sizeOf($arrRevInit); $i++) { 

	// Insert data
	$curYear = $arrRevInit[$i]['year'];
	$curMonth = $arrRevInit[$i]['month'];
	$arrRev3Y[$curYear]['totals'][$curMonth] = $arrRevInit[$i]['total'];
	
};

//////////////////////////////////////////////////////////////////////////////////// TOP LINE

$grandTotalPrev = 0;
$grandCountPrev = 0;
$grandBasketSizePrev = 0;

$grandTotalCurr = 0;
$grandCountCurr = 0;
$grandBasketSizeCurr = 0;

$grandPackageCount = 0;
$grandFrameOnlyCount = 0;

// Grab Top Metrics
$arrTopMetrics = grabTopMetrics($search_previous_year, $search_current_year, $search_month);

for ($i=0; $i < sizeOf($arrTopMetrics); $i++) { 

	if($arrTopMetrics[$i]['year'] == $search_previous_year) {

		$grandTotalPrev += $arrTopMetrics[$i]['total'];
		$grandCountPrev += $arrTopMetrics[$i]['count'];

		$grandBasketSizePrev += ($arrTopMetrics[$i]['total'] / $arrTopMetrics[$i]['count']);

	}
	else {

		$grandTotalCurr += $arrTopMetrics[$i]['total'];
		$grandCountCurr += $arrTopMetrics[$i]['count'];

		$grandBasketSizeCurr += ($arrTopMetrics[$i]['total'] / $arrTopMetrics[$i]['count']);

	};

	$grandPackageCount += $arrTopMetrics[$i]['package'];
	$grandFrameOnlyCount += $arrTopMetrics[$i]['frame_only'];
	
};

?>