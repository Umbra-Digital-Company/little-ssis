<?php 

//////////////////////////////////////////// FUNCTIONS

function checkTier($arr_tiers, $average_purchase_value) {

	// Set default
	$final_tier = 1;

	// Reformat APV
	$average_purchase_value = number_format($average_purchase_value, 2, '.', '');

	// Check tier
	for ($i=0; $i < sizeOf($arr_tiers); $i++) { 
	
		// Set current data
		$curTier = $arr_tiers[$i]['tier'];
		$curMin  = $arr_tiers[$i]['min'];
		$curMax  = $arr_tiers[$i]['max'];

		// Compare
		if($average_purchase_value >= $curMin && $average_purchase_value <= $curMax) {

			// Set tier
			$final_tier = $curTier;			

		};

	};

	// Return the Tier
	return $final_tier;

};

function format_interval(DateInterval $interval) {

    $result = 0;

    if ($interval->y) { $result += $interval->format("%y") * 12; }
    if ($interval->m) { $result += $interval->format("%m"); }
    // if ($interval->d) { $result .= $interval->format("%d"); }

    return $result;

};

//////////////////////////////////////////// VARIABLES AND ARRAYS

// Set Tiers
$arrAVTiers = array(
	array(
		"tier" => "1",
		"min" => 0,
		"max" => 1999
	),
	array(
		"tier" => "2",
		"min" => 2000,
		"max" => 5999
	),
	array(
		"tier" => "3",
		"min" => 6000,
		"max" => 9999
	),
	array(
		"tier" => "4",
		"min" => 10000,
		"max" => 11999
	),
	array(
		"tier" => "5",
		"min" => 11999,
		"max" => 100000000
	)
);

// Set variables
$total_purchase_value 	  	= 0;
$total_purchase_value_raw 	= 0;
$total_purchase_count 	  	= sizeOf($arrOrders);
$average_purchase_value   	= 0;
$average_purchase_value_raw = 0;

$arrFrequency     	= array();
$arrFrequencySorted = array();
$arrFrequencyMonths = array();
$total_frequency_months 	= 0;
$average_purchase_frequency = 0;
$average_frequency_count 	= 0;

$last_purchase_date = "";

//////////////////////////////////////////// CALCULATIONS

// Cycle through array
for ($i=0; $i < sizeOf($arrOrders); $i++) { 

	///////// PURCHASE VALUE

	// Set current data
	$curTotal = 0;
	$curCount = 0;
	$curOrder = $arrOrders[$i];

	for ($a=0; $a < sizeOf($curOrder); $a++) { 
		
		// Set current data
		$curPrice = $curOrder[$a]['price'];

		// Add to total
		$curTotal += $curPrice;

		// Add to count
		$curCount++;

	}

	// Add to PV
	$total_purchase_value += $curTotal;

	// Add to TC
	// $total_purchase_count += $curCount;

	///////// BUYING FREQUENCY

	// Set current data
	$curPaymentDate = date("Y-m-d", strtotime($arrOrders[$i][0]['payment_date']));

	// Push to Frequency array
	$arrFrequency[$i]['payment_date'] = $curPaymentDate;
	$arrFrequency[$i]['total'] 		  = $curTotal;

};

// Set raw data
$average_purchase_value_raw = $total_purchase_value / $total_purchase_count;
$total_purchase_value_raw 	= $total_purchase_value;

//////////////////////////////////////////// AVERAGE FREQUENCY

// Cycle through array
for ($i=0; $i < sizeOf($arrFrequency); $i++) { 

	// Set current data
	$curDate  = $arrFrequency[$i]['payment_date'];
	$curTotal = $arrFrequency[$i]['total'];

	// Push to sorted array
	$arrFrequencySorted[$curDate]['payment_date'] = $curDate;
	$arrFrequencySorted[$curDate]['total'] 		  += $curTotal;
	
};

// Reindex
$arrFrequencySorted = array_values($arrFrequencySorted);

// Cycle through array
for ($i=0; $i < sizeOf($arrFrequencySorted); $i++) { 

	if($i < sizeOf($arrFrequencySorted)) {

		// Set current data
		$curDate  = new DateTime($arrFrequency[$i]['payment_date']);
		$prevDate = new DateTime($arrFrequency[($i+1)]['payment_date']);
		
		// Compare to previous date
		$diff 		= $curDate -> diff($prevDate);
		$difference = format_interval($diff);
		array_push($arrFrequencyMonths, $difference);	

	};

};

// Cycle through array
for ($i=0; $i < sizeOf($arrFrequencyMonths); $i++) { 

	// add to total
	$total_frequency_months += $arrFrequencyMonths[$i];

};

// Calculate average frequence
$average_frequency_count 	= sizeOf($arrFrequencyMonths);
$average_purchase_frequency = $total_frequency_months / $average_frequency_count;

// Calculate Tier
$frequency_tier = floor(floor($average_purchase_frequency) / 3); 

//////////////////////////////////////////// FINAL TOUCHES

$average_purchase_value 	= number_format(($total_purchase_value / $total_purchase_count), 2, ".", ",");
$total_purchase_value   	= number_format($total_purchase_value, 2, ".", ",");
$average_purchase_tier  	= checkTier($arrAVTiers, $average_purchase_value_raw);
$last_purchase_date 		= $arrFrequency[0]['payment_date'];

?>