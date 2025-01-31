<?php 

//////////////////////////////////////////////////////////////////////////////////// 
// DATA CALCULATIONS
//////////////////////////////////////////////////////////////////////////////////// 

//////////////////////////////////////////////////////////////////////////////////// DATE & TIME

$time = date('Y-m-d h:i:s');

if(isset($_GET['date']) && $_GET['date'] != 'custom') {

	switch ($_GET['date']) {

		case 'yesterday':
			$yA = date('Y-m-d');
			$date = date('F d, Y', strtotime($yA . "-1 day"));
			break;

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

//////////////////////////////////////////////////////////////////////////////////// TOP DATA | 4 blocks

// Grab all the specs orders
$arrAllFrames = grabFrames();

/////////////// frames sold
$totalFramesSold = 0;
$totalFramesDispatched = 0;
$totalFramesCancelled = 0;
$totalFramesPending = 0;

/////////////// dispatch on time
$totalFramesOnTime = 0;

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

/////////////// dispatch rate

$calc = 0;
if($totalFramesDispatched > 0 && $totalFramesSold > 0) {

	$calc = ($totalFramesDispatched / $totalFramesSold) * 100;

}
$dRate = number_format($calc, 0, '.', ',');
$dCount = $totalFramesDispatched;

if($dRate > 79) {

	$dRateStyle = "green";
	$dRateClass = "text-success";

}
else if($dRate > 30) {

	$dRateStyle = "orange";
	$dRateClass = "text-warning";

}
else {

	$dRateStyle = "red";
	$dRateClass = "text-danger";

};

/////////////// top selling frame
$arrTopFrames = grabBestFrames();	

/////////////// dispatch on time
$calcTime = 0;
if($totalFramesOnTime > 0 && $totalFramesDispatched > 0) {

	$calcTime = ($totalFramesOnTime / $totalFramesDispatched) * 100;

}
$timeRate = number_format($calcTime, 0, '.', ',');		

if($timeRate > 79) {

	$timeRateStyle = "green";
	$timeRateClass = "text-success";

}
else if($timeRate > 30) {

	$timeRateStyle = "orange";
	$timeRateClass = "text-warning";

}
else {

	$timeRateStyle = "red";
	$timeRateClass = "text-danger";

};

//////////////////////////////////////////////////////////////////////////////////// GOOGLE PIE CHARTS

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

/////////////// revenue

// Call grabRevenue function
$arrRevenue = grabRevenue();

/////////////// count orders

// Call countOrders function
$arrCountOrders = countOrders();

?>