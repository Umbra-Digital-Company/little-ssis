<?php 

//////////////////////////////////////////////////////////////////////////////////// HISTORICAL DATA

/////////////// 3 Year Sales Table
$arrRevInit = grabRevenueMonths();
$arrRev3Y   = array();
$arrMonths  = array(

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

	if($curYear > 0000) {

		$arrRev3Y[$curYear]['year'] = $curYear;
		$arrRev3Y[$curYear]['totals'] = $arrMonths;

	};
	
};

// Cycle through Revenue array
for ($i=0; $i < sizeOf($arrRevInit); $i++) { 

	// Insert data
	$curYear = $arrRevInit[$i]['year'];

	if($curYear > 0000) {

		$curMonth = $arrRevInit[$i]['month'];
		$arrRev3Y[$curYear]['totals'][$curMonth] = $arrRevInit[$i]['total'];

	};
	
};

?>