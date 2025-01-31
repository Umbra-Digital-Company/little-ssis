<?php

//////////////////////////////////////////////////////////////////////////////////// DATE & TIME

$time = date('Y-m-d h:i:s');

if(isset($_GET['date']) && $_GET['date'] != 'custom') {

	switch ($_GET['date']) {

		case 'yesterday':
			$yA = date('Y-m-d');
			$date_title = date('F d, Y', strtotime($yA . "-1 day"));
			break;

		case 'day':
			$date_title = date('F d, Y');
			break;

		case 'week':
			$w = date('w');
			$week_start = date('F d, Y', strtotime('-'.$w.' days'));
			$week_end = date('F d, Y', strtotime('+'.(6-$w).' days'));
			$date_title = $week_start." - ".$week_end;
			break;

		case 'month':
			$date_title = date('F, Y');
			break;

		case 'year':
			$date_title = date('Y');
			break;

		case 'all-time':
			$date_title = "All Time";
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

	$date_title = $dateA." - ".$dateB;

}
else {

	$date_title = date('F, Y');

};

?>