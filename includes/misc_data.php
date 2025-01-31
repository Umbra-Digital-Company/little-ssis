<?php

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

// Number of days in month
$arrMonthDays = array(

	"January" => 31,
	"February" => 28,
	"March" => 31,
	"April" => 30,
	"May" => 31,
	"June" => 30,
	"July" => 31,
	"August" => 31,
	"September" => 30,
	"October" => 31,
	"November" => 30,
	"December" => 31

);

?>