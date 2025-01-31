<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// DATE SETTINGS

// Set timezone
date_default_timezone_set("Asia/Manila");

// Number of days in month
$arrMonthDays = array(

	"January" => 31,
	"February" => 29,
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

// Check if custom range is the same as MONTH filter
$customMonth = false;

if(isset($_GET["date"]) && $_GET["date"] == "custom") {

	$startD = $_GET["data_range_start_day"];
	$startM = $_GET["data_range_start_month"];
	$startY = $_GET["data_range_start_year"];
	$endD 	= $_GET["data_range_end_day"];
	$endM 	= $_GET["data_range_end_month"];
	$endY 	= $_GET["data_range_end_year"];	

	if($startM == $endM && $startY == $endY) {

		if($startD == 1 && $endD == array_values($arrMonthDays)[$startM - 1]) {

			$customMonth = true;

		};

	};

};

// Grab GET settings
if((isset($_GET["date"]) && $_GET['date'] != "custom") || $customMonth) {

	switch ($_GET['date']) {

		case 'yesterday':		
			$today = date('Y-m-d');
			$yesterdayinit = date('Y-m-d', strtotime($today . "-1 day"));
			$qGrabDateA = date('d', strtotime($yesterdayinit));
			$qGrabDateB = date('m', strtotime($yesterdayinit));
			$qGrabDateC = date('Y', strtotime($yesterdayinit));
			$qDate = 	"DATE_FORMAT(os.payment_date, '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateC;

			$dlDate = " AND DATE_FORMAT(dl.daily_date, '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(dl.daily_date, '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDateC;
			break;
			
		case 'day':
			$qGrabDateA = date("d");
			$qGrabDateB = date("m");
			$qGrabDateC = date("Y");
			$qDate = 	"DATE_FORMAT(os.payment_date, '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateC;

			$dlDate = " AND DATE_FORMAT(dl.daily_date, '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(dl.daily_date, '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDateC;
			break;

		case 'week':
			$qGrabDateA = date("Y-m-d");
			$qDate = 	"YEARWEEK(DATE_FORMAT(os.payment_date, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";

			$dlDate = 	" AND YEARWEEK(DATE_FORMAT(dl.daily_date, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
			break;

		case 'month':
			$qGrabDateA = date("m");
			$qGrabDateB = date("Y");
			$qDate = 	"DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateB;

			$dlDate = 	" AND DATE_FORMAT(dl.daily_date, '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDateB;
			break;
		case 'pmonth':
				$qGrabDateO = date("m");
				$qGrabDateA = date('m', strtotime("-1 months"));
				if($qGrabDateO =='01' || $qGrabDateO =='1' ){
					$qGrabDateB = date("Y")-1;
				}else{
					$qGrabDateB = date("Y");
				}
			
					// $qGrabDateB = date("Y");
					$qDate = 	"DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateA."
									AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateB;

					$dlDate = 	" AND DATE_FORMAT(dl.daily_date, '%m') = ".$qGrabDateA."
									AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDateB;
			break;
		case 'custom':
			$qGrabDateA = $_GET["data_range_start_month"];
			$qGrabDateB = $_GET["data_range_start_year"];
			$qDate = 	"DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateB;

			$dlDate = 	" AND DATE_FORMAT(dl.daily_date, '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDateB;
			break;
		
		case 'year':
			$qGrabDate = date("Y");
			$qDate = "DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDate;

			$dlDate = " AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDate;
			break;

		case 'all-time':
			$qGrabDate = date("Y");
			$qDate = 	"DATE_FORMAT(os.payment_date, '%Y') <= ".$qGrabDate;

			$dlDate = 	" AND DATE_FORMAT(dl.daily_date, '%Y') <= ".$qGrabDate;
			break;

	}	

}
elseif(isset($_GET['data_range_start_month'])) {

	// Set start date
	$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );

	$qDateA = 	"DATE_FORMAT(os.payment_date, '%Y-%m-%d') >= '".$dateStart."'";

	$dlDateA = 	" AND DATE_FORMAT(dl.daily_date, '%Y-%m-%d') >= '".$dateStart."'";

	if(isset($_GET['data_range_end_month'])) {

		// Set end date
		$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );

		$qDateB = " AND DATE_FORMAT(os.payment_date, '%Y-%m-%d') <= '".$dateEnd."'";

		$dlDateB = " AND DATE_FORMAT(dl.daily_date, '%Y-%m-%d') <= '".$dateEnd."'";

	}
	else {

		$dateEnd = "";
		$qDateB = "";

		$dlDateB = "";

	};

	$qDate = $qDateA.$qDateB;

	$dlDate = $dlDateA.$dlDateB;

}
else {

	$qGrabDateA = date("d");
	$qGrabDateB = date("m");
	$qGrabDateC = date("Y");
	$qDate = 	"DATE_FORMAT(os.payment_date, '%d') = ".$qGrabDateA." 
					AND DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateB."
					AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateC;

	$dlDate = 	" AND DATE_FORMAT(dl.daily_date, '%d') = ".$qGrabDateA." 
					AND DATE_FORMAT(dl.daily_date, '%m') = ".$qGrabDateB."
					AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDateC;

};


// Set stores if specified
if(isset($_GET['filterStores'])) {

	// Set stores array
	$arrFilterStores = $_GET['filterStores'];

}
else {

	$arrFilterStores = array('788');

};

// Set if sort parameter is present
if(isset($_GET['sort'])) {

	// Grab sort
	$checkSort = $_GET['sort'];

	if($checkSort == 'highest-to-lowest') {

		$revBreakdownSort = 'DESC';

	}
	else {

		$revBreakdownSort = 'ASC';

	};	

}
else {

	$revBreakdownSort = "";

};

// Remove international stores
$removeStoreIDs = "AND o.store_id NOT IN ('142', '1000', '900', '991', '126', '147', '148', '149', '130', '787','150', '999','139','155')";
$removeStoreLocations = "sl.store_id NOT IN ('142', '1000', '900', '991', '126', '130','150', '999','139','155')";

$arrSelectedStores = [];
$arrStoresManagers = [];
$arrThreshold = [];
$arrPercentageBelow = [];
$arrPercentageAbove = [];

//////////////////////////////////////////////////////////////////////////////////// HELPER FUNCTIONS
function cvdate3($d){
    $returner = '';
    $datae=date_parse($d);
    $returner .= getMonth3($datae['month'])." ".$datae['day'].", ".$datae['year'];
    $suffix = "AM";
    $hour = $datae['hour'];
    if ($datae['hour']>'12') {
        $hour = $datae['hour']-12;
    }
    if ($datae['hour']>'11' && $datae['hour']<'24') {
        $suffix = "PM";
    }
    return $returner;
}

function getMonth3($mid){
    switch($mid){
        case '1': return "Jan"; break;
        case '2': return "Feb"; break;
        case '3': return "Mar"; break;
        case '4': return "Apr"; break;
        case '5': return "May"; break;
        case '6': return "Jun"; break;
        case '7': return "Jul"; break;
        case '8': return "Aug"; break;
        case '9': return "Sep"; break;
        case '10': return "Oct"; break;
        case '11': return "Nov"; break;
        case '12': return "Dec"; break;

    }
}

function AddZero3($num){
    if (strlen($num)=='1') {
        return "0".$num;
    } else {
        return $num;
    }
}

function showMe($input) {

	if(is_array($input)) {

		echo '<pre>';
		print_r($input);
		echo '</pre>';

	}
	else {

		echo '<pre>';
		echo $input;
		echo '</pre>';

	};

};

function getAreaPercentage($store_code){
	global $conn;
	global $qDate;
	global $arrSelectedStores;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;

	if(!in_array($store_code, $arrSelectedStores)){
		$arrSelectedStores[] = $store_code;
		$query = 	"SELECT
					store_code,
					sr_area_manager,
					area_manager,
					area_supervisor,
					date_from,
					date_to
					FROM commission_settings
					WHERE
						 store_code = '".$store_code."'
					ORDER BY id DESC;";

		$grabParams = array(
			'store_code',
			'sr_area_manager',
			'area_manager',
			'area_supervisor',
			'date_from',
			'date_to'
		);

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				$arrStoresManagers[$store_code][] = $tempArray;

			};

			mysqli_stmt_close($stmt);    

		}
		else {

			showMe(mysqli_error($conn));

		};

		$query = 	"SELECT
					store_code,
					threshold,
					date_from,
					date_to
					FROM commission_threshold
					WHERE
						 store_code = '".$store_code."'
					ORDER BY id DESC;";

		$grabParams = array(
			'store_code',
			'threshold',
			'date_from',
			'date_to'
		);

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				$arrThreshold[$store_code][] = $tempArray;

			};

			mysqli_stmt_close($stmt);    

		}
		else {

			showMe(mysqli_error($conn));

		};

		$query = 	"SELECT
					store_code,
					sr_area_manager,
					area_manager,
					area_supervisor,
					staff,
					date_from,
					date_to
					FROM commission_percentage_below
					WHERE
						 store_code = '".$store_code."'
					ORDER BY id DESC;";

		$grabParams = array(
			'store_code',
			'sr_area_manager',
			'area_manager',
			'area_supervisor',
			'staff',
			'date_from',
			'date_to'
		);

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				$arrPercentageBelow[$store_code][] = $tempArray;

			};

			mysqli_stmt_close($stmt);    

		}
		else {

			showMe(mysqli_error($conn));

		};

		$query = 	"SELECT
					store_code,
					sr_area_manager,
					area_manager,
					area_supervisor,
					staff,
					date_from,
					date_to
					FROM commission_percentage_above
					WHERE
						 store_code = '".$store_code."'
					ORDER BY id DESC;";

		$grabParams = array(
			'store_code',
			'sr_area_manager',
			'area_manager',
			'area_supervisor',
			'staff',
			'date_from',
			'date_to'
		);

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				$arrPercentageAbove[$store_code][] = $tempArray;

			};

			mysqli_stmt_close($stmt);    

		}
		else {

			showMe(mysqli_error($conn));

		};
	}
	
}

function grabIncentivesTable() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			}
					
			$specStore .= "o.origin_branch = '".$arrFilterStores[$i]."'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					os.payment_date,
					sl.store_name,
					sl.store_id,
					os.po_number,
					p.total
					FROM orders o
					LEFT JOIN orders_specs os ON o.order_id = os.order_id
					LEFT JOIN stores_locations sl ON o.store_id = sl.store_id
					LEFT JOIN payments p ON os.po_number = p.po_number
					WHERE
						".$qDate."
						".$specStore."
						".$removeStoreIDs."
						AND os.payment = 'y'
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.dispatch_type!='packaging'
						AND os.po_number!=''
						AND os.orders_specs_id!=''
					ORDER BY os.payment_date ASC
						;";

	$grabParams = array(
		'payment_date',
		'store_name',
		'store_id',
		'po_number',
		'total'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	
	for($i = 0 ; $i < count($arrIncentives); $i++){

		getAreaPercentage($arrIncentives[$i]['store_id']);

		$arrThresholdData = $arrThreshold[$arrIncentives[$i]['store_id']];
		$valueThreshold = 'n';
		foreach ($arrThresholdData as $key => $value) {
			if(strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$valueThreshold = $value['threshold'];
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) <= strtotime($value['date_to'])  ){

				$valueThreshold = $value['threshold'];
				break;

			}
		}
		$arrIncentives[$i]['excess'] = ($valueThreshold != 'n') ? $arrIncentives[$i]['total'] - $valueThreshold : 0;

		//below and above threshold percentage
		$sramDataBelow = 0;
		$amDataBelow = 0;
		$supareaDataBelow = 0;
		$staffBelow = 0;

		$sramDataAbove = 0;
		$amDataAbove = 0;
		$supareaDataAbove = 0;
		$staffAbove = 0;
		if($valueThreshold != 'n'){
			$arrPercentageBelowData = $arrPercentageBelow[$arrIncentives[$i]['store_id']];
			foreach ($arrPercentageBelowData as $key => $value) {
				if(strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

					$sramDataBelow = $value['sr_area_manager'];
					$amDataBelow = $value['area_manager'];
					$supareaDataBelow = $value['area_supervisor'];
					$staffBelow = $value['staff'];

					break;

				}
				elseif(strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) <= strtotime($value['date_to'])  ){

					$sramDataBelow = $value['sr_area_manager'];
					$amDataBelow = $value['area_manager'];
					$supareaDataBelow = $value['area_supervisor'];
					$staffBelow = $value['staff'];
					break;

				}
			}

			$arrPercentageAboveData = $arrPercentageAbove[$arrIncentives[$i]['store_id']];
			foreach ($arrPercentageAboveData as $key => $value) {
				if(strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

					$sramDataAbove = $value['sr_area_manager'];
					$amDataAbove = $value['area_manager'];
					$supareaDataAbove = $value['area_supervisor'];
					$staffAbove = $value['staff'];

					break;

				}
				elseif(strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($arrIncentives[$i]['payment_date']))) <= strtotime($value['date_to'])  ){

					$sramDataAbove = $value['sr_area_manager'];
					$amDataAbove = $value['area_manager'];
					$supareaDataAbove = $value['area_supervisor'];
					$staffAbove = $value['staff'];
					break;

				}
			}
		}

		$arrIncentives[$i]['sramBT'] =  'N/A';
		$arrIncentives[$i]['amBT'] =  'N/A';
		$arrIncentives[$i]['supareaBT'] =  'N/A';
		$arrIncentives[$i]['staffBT'] =  'N/A';

		$arrIncentives[$i]['sramAT'] =  'N/A';
		$arrIncentives[$i]['amAT'] =  'N/A';
		$arrIncentives[$i]['supareaAT'] =  'N/A';
		$arrIncentives[$i]['staffAT'] =  'N/A';

		$arrIncentives[$i]['total_bonus'] = 'N/A'; 

		$flagBelow = false;
		$flagAbove = false;
		if($valueThreshold != 'n'){
			if($sramDataBelow > 0){

				$multiplierValueBelow = ($arrIncentives[$i]['excess'] >= 0) ? $valueThreshold : $arrIncentives[$i]['total'];

				$arrIncentives[$i]['sramBT'] = $multiplierValueBelow * $sramDataBelow;
				$arrIncentives[$i]['amBT'] = $multiplierValueBelow * $amDataBelow;
				$arrIncentives[$i]['supareaBT'] = $multiplierValueBelow * $supareaDataBelow;
				$arrIncentives[$i]['staffBT'] = $multiplierValueBelow * $staffBelow;
				$flagBelow = true;
				$arrIncentives[$i]['total_bonus'] = $arrIncentives[$i]['sramBT'] + $arrIncentives[$i]['amBT'] + $arrIncentives[$i]['supareaBT'] +$arrIncentives[$i]['staffBT'];
			}
			if($sramDataAbove > 0 && $arrIncentives[$i]['excess'] >= 0){
				$arrIncentives[$i]['sramAT'] = $arrIncentives[$i]['excess'] * $sramDataAbove;
				$arrIncentives[$i]['amAT'] = $arrIncentives[$i]['excess'] * $amDataAbove;
				$arrIncentives[$i]['supareaAT'] = $arrIncentives[$i]['excess'] * $supareaDataAbove;
				$arrIncentives[$i]['staffAT'] = $arrIncentives[$i]['excess'] * $staffAbove;
				$flagAbove = true;

				$totalAbove = $arrIncentives[$i]['sramAT'] + $arrIncentives[$i]['amAT'] + $arrIncentives[$i]['supareaAT'] + $arrIncentives[$i]['staffAT'];

				$arrIncentives[$i]['total_bonus'] = ($arrIncentives[$i]['total_bonus'] != 'N/A') ? $arrIncentives[$i]['total_bonus'] + $totalAbove : $totalAbove;
			}
		}

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small text-center">'.($i + 1).'</td>';					
		echo 		'<td nowrap class="cell100 small">'.cvdate3($arrIncentives[$i]['payment_date']).'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['store_name'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['store_id'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['po_number'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.number_format($arrIncentives[$i]['total'],2).'</td>';
		echo 		'<td nowrap class="cell100 small">1</td>';
		echo 		'<td nowrap class="cell100 small" '.(($arrIncentives[$i]['excess'] < 0 || !is_numeric($valueThreshold)) ? 'style="color: red;"' : '').'>'.(is_numeric($valueThreshold) ? number_format($arrIncentives[$i]['excess'],2) : 'N/A').'</td>';
		
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['sramBT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['amBT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['supareaBT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['staffBT'].'</td>';

		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['sramAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['amAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['supareaAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['staffAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || $arrIncentives[$i]['total_bonus'] == 'N/A') ? 'style="color: red;"' : '').'>'.$arrIncentives[$i]['total_bonus'].'</td>';
		echo 	'</tr>';
	}

};

function grabIncentivesTableCSV() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			}
					
			$specStore .= "o.origin_branch = '".$arrFilterStores[$i]."'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};
	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					os.payment_date,
					sl.store_name,
					sl.store_id,
					os.po_number,
					p.total
					FROM orders o
					LEFT JOIN orders_specs os ON o.order_id = os.order_id
					LEFT JOIN stores_locations sl ON o.store_id = sl.store_id
					LEFT JOIN payments p ON os.po_number = p.po_number
					WHERE
						".$qDate."
						".$specStore."
						".$removeStoreIDs."
						AND os.payment = 'y'
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.dispatch_type!='packaging'
						AND os.po_number!=''
						AND os.orders_specs_id!=''
					ORDER BY os.payment_date ASC
						;";

	$grabParams = array(
		'payment_date',
		'store_name',
		'store_id',
		'po_number',
		'total'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	header( 'Content-Type: application/csv' );
	$filename = "incentives-details-".date('YmdHis').".csv";
	header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
     // clean output buffer
    ob_end_clean();
    
    $handle = fopen( 'php://output', 'w' );
    fputcsv($handle, ['Sales Date','Warehouse Name','Warehouse Code','PO Number','Value','Quantity','Excess','Sr Area Manager Below Threshold %','Area Manager Below Threshold %','Area Supervisor Below Threshold %','Staff Below Threshold % ','Sr Area Manager Above Threshold %','Area Manager Above Threshold %','Area Supervisor Above Threshold %','Staff Above Threshold % ','Total Bonus']);

    foreach ($arrIncentives as $key => $line) {

    	getAreaPercentage($line['store_id']);

		$arrThresholdData = $arrThreshold[$line['store_id']];
		$valueThreshold = 'n';
		foreach ($arrThresholdData as $key => $value) {
			if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

				$valueThreshold = $value['threshold'];
				break;

			}
			elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

				$valueThreshold = $value['threshold'];
				break;

			}
		}
		$line['quantity'] = 1;
		$line['excess'] = ($valueThreshold != 'n') ? $line['total'] - $valueThreshold : 0;

		//below and above threshold percentage
		$sramDataBelow = 0;
		$amDataBelow = 0;
		$supareaDataBelow = 0;
		$staffBelow = 0;

		$sramDataAbove = 0;
		$amDataAbove = 0;
		$supareaDataAbove = 0;
		$staffAbove = 0;
		if($valueThreshold != 'n'){
			$arrPercentageBelowData = $arrPercentageBelow[$line['store_id']];
			foreach ($arrPercentageBelowData as $key => $value) {
				if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

					$sramDataBelow = $value['sr_area_manager'];
					$amDataBelow = $value['area_manager'];
					$supareaDataBelow = $value['area_supervisor'];
					$staffBelow = $value['staff'];

					break;

				}
				elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

					$sramDataBelow = $value['sr_area_manager'];
					$amDataBelow = $value['area_manager'];
					$supareaDataBelow = $value['area_supervisor'];
					$staffBelow = $value['staff'];
					break;

				}
			}

			$arrPercentageAboveData = $arrPercentageAbove[$line['store_id']];
			foreach ($arrPercentageAboveData as $key => $value) {
				if(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && $value['date_to'] == '0000-00-00'){

					$sramDataAbove = $value['sr_area_manager'];
					$amDataAbove = $value['area_manager'];
					$supareaDataAbove = $value['area_supervisor'];
					$staffAbove = $value['staff'];

					break;

				}
				elseif(strtotime(date('Y-m-d', strtotime($line['payment_date']))) >= strtotime($value['date_from']) && strtotime(date('Y-m-d', strtotime($line['payment_date']))) <= strtotime($value['date_to'])  ){

					$sramDataAbove = $value['sr_area_manager'];
					$amDataAbove = $value['area_manager'];
					$supareaDataAbove = $value['area_supervisor'];
					$staffAbove = $value['staff'];
					break;

				}
			}
		}

		$line['sramBT'] =  'N/A';
		$line['amBT'] =  'N/A';
		$line['supareaBT'] =  'N/A';
		$line['staffBT'] =  'N/A';

		$line['sramAT'] =  'N/A';
		$line['amAT'] =  'N/A';
		$line['supareaAT'] =  'N/A';
		$line['staffAT'] =  'N/A';

		$line['total_bonus'] = 'N/A'; 

		$flagBelow = false;
		$flagAbove = false;
		if($valueThreshold != 'n'){
			if($sramDataBelow > 0){

				$multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];

				$line['sramBT'] = $multiplierValueBelow * $sramDataBelow;
				$line['amBT'] = $multiplierValueBelow * $amDataBelow;
				$line['supareaBT'] = $multiplierValueBelow * $supareaDataBelow;
				$line['staffBT'] = $multiplierValueBelow * $staffBelow;
				$flagBelow = true;
				$line['total_bonus'] = $line['sramBT'] + $line['amBT'] + $line['supareaBT'] + $line['staffBT'];
			}
			if($sramDataAbove > 0 && $line['excess'] >= 0){
				$line['sramAT'] = $line['excess'] * $sramDataAbove;
				$line['amAT'] = $line['excess'] * $amDataAbove;
				$line['supareaAT'] = $line['excess'] * $supareaDataAbove;
				$line['staffAT'] = $line['excess'] * $staffAbove;
				$flagAbove = true;

				$totalAbove = $line['sramAT'] + $line['amAT'] + $line['supareaAT'] + $line['staffAT'];

				$line['total_bonus'] = ($line['total_bonus'] != 'N/A') ? $line['total_bonus'] + $totalAbove : $totalAbove;
			}
		}

		$line['payment_date'] = cvdate3($line['payment_date']);
		$line['po_number'] = '="'.$line['po_number'].'"';
		$line['total'] = number_format($line['total'],2);
		$line['excess'] = number_format($line['excess'],2);


    	fputcsv($handle, $line);
    }

    fclose( $handle );

    // flush buffer
    ob_flush();
    
    // use exit to get rid of unexpected output afterward
    exit();

};

function grabIncentivesHcTable() {

	global $conn;
	global $dlDate;
	global $arrFilterStores;

	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					sl.store_name,
					sl.store_id,
					COUNT(dl.emp_id)
					FROM stores_locations sl 
					LEFT JOIN daily_login dl ON dl.store_code = sl.store_id ".$dlDate."
					WHERE sl.store_id IN ('".implode("','", $arrFilterStores)."')
					GROUP BY sl.store_id
					ORDER BY sl.store_name ASC;";
	$grabParams = array(
		'store_name',
		'store_id',
		'count_emp'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};
			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	
	for($i = 0 ; $i < count($arrIncentives); $i++){
		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small text-center">'.($i + 1).'</td>';					
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['store_name'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['count_emp'].'</td>';
		echo 	'</tr>';
	}

};

function grabIncentivesHcTableCSV() {

	global $conn;
	global $dlDate;
	global $arrFilterStores;

	

	// Set array
	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					sl.store_name,
					COUNT(dl.emp_id)
					FROM stores_locations sl 
					LEFT JOIN daily_login dl ON dl.store_code = sl.store_id ".$dlDate."
					WHERE sl.store_id IN ('".implode("','", $arrFilterStores)."')
					GROUP BY sl.store_id
					ORDER BY sl.store_name ASC;";

	$grabParams = array(
		'store_name',
		'count_emp'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};
			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	header( 'Content-Type: application/csv' );
	$filename = "incentives-hc-".date('YmdHis').".csv";
	header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
     // clean output buffer
    ob_end_clean();
    
    $handle = fopen( 'php://output', 'w' );
   
    fputcsv($handle, ['Stores','# of staff who received incentives in '.$_GET['date_title']]);

    foreach ($arrIncentives as $key => $line) {
    	fputcsv($handle, $line);
    }

    fclose( $handle );

    // flush buffer
    ob_flush();
    
    // use exit to get rid of unexpected output afterward
    exit();

};

function grabStores() {

	global $conn;
	global $removeStoreLocations;

	// Set array
	$arrStores = array();

	$query = 	"SELECT
					sl.id,
					sl.date_created,
					sl.date_updated,
					sl.store_id,
					sl.lab_id,
					sl.zone,
					sl.store_name,
					ll.lab_name,
					sl.address,
					sl.province,
					sl.city,
					sl.barangay,
					sl.phone_number,
					sl.email_address,
					sl.active
				FROM
					stores_locations sl
						LEFT JOIN labs_locations ll
							ON ll.lab_id = sl.lab_id		
				WHERE
					".$removeStoreLocations."	
				ORDER BY
					sl.store_name ASC";

	$grabParams = array(
		"id",
		"date_created",
		"date_updated",
		"store_id",
		"lab_id",
		"zone",
		"store_name",
		"lab_name",
		"address",
		"province",
		"city",
		"barangay",
		"phone_number",
		"email_address",
		"active"		
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrStores[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrStores;

};

function grabLabs() {

	global $conn;

	// Set array
	$arrLabs = array();

	$query = 	"SELECT 
					ll.id,
					ll.date_created,
					ll.date_updated,
					ll.lab_id,
					ll.lab_name,
					ll.address,
					ll.province,
					ll.city,
					ll.barangay,
					ll.zip_code,
					ll.phone_number
	            FROM 
	                labs_locations ll
	            ORDER BY
	                ll.lab_name ASC";

	$grabParams = array(
		"id",
		"date_created",
		"date_updated",
		"lab_id",
		"lab_name",
		"address",
		"province",
		"city",
		"barangay",
		"zip_code",
		"phone_number"
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrLabs[] = $tempArray;
		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	// Cycle through labs and add in stores
	$arrStores = grabStores();

	for ($i=0; $i < sizeOf($arrLabs); $i++) { 
	
		$curLabID = $arrLabs[$i]['lab_id'];
		$arrLabs[$i]['stores'] = array();

		// Cycle through stores
		for ($a=0; $a < sizeOf($arrStores); $a++) { 

			$curStoreLabID = $arrStores[$a]['lab_id'];
		
			if($curStoreLabID == $curLabID) {

				array_push($arrLabs[$i]['stores'], $arrStores[$a]['store_name']);

			};

		};

	};

	return $arrLabs;

};

function checkFilter($store_id) {

	global $arrFilterStores;

	if(in_array($store_id, $arrFilterStores)) {

		return 'checked="checked"';

	}
	else {

		return "";

	};

};

//////////////////////////////////////////////////////////////////////////////////// FIRE FUNCTIONS ON GET

if(isset($_GET['function'])) {

	switch ($_GET['function']) {

		case 'grabIncentivesTable':
			grabIncentivesTable();
			break;
		case 'grabIncentivesTableCSV':
			grabIncentivesTableCSV();
			break;

		case 'grabIncentivesHcTable':
			grabIncentivesHcTable();
			break;
		case 'grabIncentivesHcTableCSV':
			grabIncentivesHcTableCSV();
			break;
		case 'grabStores':
			grabStores();
			break;

		case 'grabLabs':
			grabLabs();
			break;

		case 'checkFilter':
			checkFilter();
			break;
	};

};

?>