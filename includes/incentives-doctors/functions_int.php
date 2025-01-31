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

function getAreaPercentage($store_code,$payment_date){
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
					cs.store_code,
					cs.sr_area_manager,
					(SELECT CONCAT(CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),'|',et.designation) FROM emp_table et WHERE  et.emp_id = cs.sr_area_manager),
					cs.area_manager,
					(SELECT CONCAT(CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),'|',et.designation) FROM emp_table et WHERE  et.emp_id = cs.area_manager),
					cs.area_supervisor,
					(SELECT CONCAT(CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),'|',et.designation) FROM emp_table et WHERE  et.emp_id = cs.area_supervisor),
					cs.date_from,
					cs.date_to
					FROM commission_settings cs
					WHERE
						 store_code = '".$store_code."'
					ORDER BY id ASC;";

		$grabParams = array(
			'store_code',
			'sr_area_manager',
			'sram_name_designation',
			'area_manager',
			'am_name_designation',
			'area_supervisor',
			'asup_name_designation',
			'date_from',
			'date_to'
		);

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);
			$flagStartDate = false;
			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				//if($flagStartDate){
					$arrStoresManagers[$store_code][] = $tempArray;
				// }
				// elseif(strtotime($payment_date) >= strtotime($tempArray['date_from'])){
				// 	$arrStoresManagers[$store_code][] = $tempArray;
				// 	$flagStartDate = true;
				// }

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
					ORDER BY id ASC;";

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
			$flagStartDate = false;
			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				// if($flagStartDate){
					$arrThreshold[$store_code][] = $tempArray;
				// }
				// elseif(strtotime($payment_date) >= strtotime($tempArray['date_from'])){
				// 	$arrThreshold[$store_code][] = $tempArray;
				// 	$flagStartDate = true;
				// }
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
					ORDER BY id ASC;";

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
			$flagStartDate = false;
			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				// if($flagStartDate){
					$arrPercentageBelow[$store_code][] = $tempArray;
				// }
				// elseif(strtotime($payment_date) >= strtotime($tempArray['date_from'])){
				// 	$arrPercentageBelow[$store_code][] = $tempArray;
				// 	$flagStartDate = true;
				// }

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
					ORDER BY id ASC;";

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
			$flagStartDate = false;
			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParams); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};
				// if($flagStartDate){
					$arrPercentageAbove[$store_code][] = $tempArray;
				//}
				// elseif(strtotime($payment_date) >= strtotime($tempArray['date_from'])){
				// 	$arrPercentageAbove[$store_code][] = $tempArray;
				// 	$flagStartDate = true;
				// }

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
					LEFT JOIN stores_locations sl ON o.origin_branch = sl.store_id
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
	
	foreach ($arrIncentives as $key => $line) {		
		
		include 'get_percentage.php';

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

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small text-center">'.($i + 1).'</td>';					
		echo 		'<td nowrap class="cell100 small">'.cvdate3($line['payment_date']).'</td>';
		echo 		'<td nowrap class="cell100 small">'.$line['store_name'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$line['store_id'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$line['po_number'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.number_format($line['total'],2).'</td>';
		echo 		'<td nowrap class="cell100 small">1</td>';
		echo 		'<td nowrap class="cell100 small" '.(($line['excess'] < 0 || !is_numeric($valueThreshold)) ? 'style="color: red;"' : '').'>'.(is_numeric($valueThreshold) ? number_format($line['excess'],2) : 'N/A').'</td>';
		
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$line['sramBT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$line['amBT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$line['supareaBT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagBelow) ? 'style="color: red;"' : '').'>'.$line['staffBT'].'</td>';

		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$line['sramAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$line['amAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$line['supareaAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || !$flagAbove) ? 'style="color: red;"' : '').'>'.$line['staffAT'].'</td>';
		echo 		'<td nowrap class="cell100 small"  '.(($valueThreshold == 'n' || $line['total_bonus'] == 'N/A') ? 'style="color: red;"' : '').'>'.$line['total_bonus'].'</td>';
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
					LEFT JOIN stores_locations sl ON o.origin_branch = sl.store_id
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

    	include 'get_percentage.php';

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


function grabPoPayments($arrStoreCode, $arrDate){
	global $conn;
	
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
					LEFT JOIN stores_locations sl ON o.origin_branch = sl.store_id
					LEFT JOIN payments p ON os.po_number = p.po_number
					WHERE
						DATE(os.payment_date) IN ('".implode("','", $arrDate)."')
						AND o.origin_branch IN ('".implode("','", $arrStoreCode)."')
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

	return $arrIncentives;
}

function grabDailyLoginEmpCount($storeCode, $dailyDate){
	global $conn;
	
	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					COUNT(emp_id)
					FROM daily_login
					WHERE store_code = '".$storeCode."'
					AND daily_date = '".$dailyDate."';";

	$grabParams = array(
		'count_employee'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1);

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

	return $arrIncentives[0]['count_employee'];
}

function grabDataManagerSupervisor($per_date = false){
	global $conn;
	global $qDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;
	
	if(!$per_date){
		include 'managers_supervisors.php';

		$rowNum = 1;
		foreach ($arrDataManagersIncentives as $key => $value) {
			echo 	'<tr class="row100 body">';
			echo 		'<td nowrap class="cell100 small text-center">'.($rowNum).'</td>';

			$nameValue = explode('|', $value['name']);

			echo 		'<td nowrap class="cell100 small">'.strtoupper($nameValue[0]).'</td>';					
			echo 		'<td nowrap class="cell100 small">'.$key.'</td>';
			echo 		'<td nowrap class="cell100 small">'.$nameValue[1].'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($value['bonus_1'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($value['bonus_2'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($value['total_bonus'],5).'</td>';
			echo 	'</tr>';

			$rowNum++;
		}
		return $rowNum;
	}else{
		include 'managers_supervisors_perdate.php';

		$rowNum = 1;
		foreach ($arrDataManagersIncentives as $key => $arrEmp) {
			foreach ($arrEmp as $dateDay => $value) {
				echo 	'<tr class="row100 body">';
				echo 		'<td nowrap class="cell100 small text-center">'.($rowNum).'</td>';

				$nameValue = explode('|', $value['name']);

				echo 		'<td nowrap class="cell100 small">'.strtoupper($nameValue[0]).'</td>';					
				echo 		'<td nowrap class="cell100 small">'.$key.'</td>';
				echo 		'<td nowrap class="cell100 small">'.$nameValue[1].'</td>';
				echo 		'<td nowrap class="cell100 small">'.number_format($value['bonus_1'],5).'</td>';
				echo 		'<td nowrap class="cell100 small">'.number_format($value['bonus_2'],5).'</td>';
				echo 		'<td nowrap class="cell100 small">'.number_format($value['total_bonus'],5).'</td>';
				echo 		'<td nowrap class="cell100 small">'.cvdate3($value['date']).'</td>';
				echo 	'</tr>';

				$rowNum++;
			}
			
		}
		return $rowNum;
	}
}

function grabIncentivesCommissionTable() {

	global $conn;
	global $dlDate;
	global $arrFilterStores;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;

	// incentives data for  managers and supervisor
	$num = grabDataManagerSupervisor();

	// incentives data for  staff
	$arrIncentives = array();

	$query = 	"SELECT
					et.emp_id,
					CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),
					et.designation,
					GROUP_CONCAT(dl.store_code),
					GROUP_CONCAT(dl.daily_date)
					FROM daily_login dl
					LEFT JOIN emp_table et ON et.emp_id = dl.emp_id
					WHERE et.status = 'y' ".$dlDate."
					AND dl.store_code IN ('".implode("','", $arrFilterStores)."')
					GROUP BY  dl.emp_id
					ORDER BY daily_date ASC;";
	$grabParams = array(
		'emp_id',
		'name',
		'designation',
		'store_code',
		'daily_date'
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
			$tempArray['name'] = strtoupper($tempArray['name']);
			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	
	for($i = 0 ; $i < count($arrIncentives); $i++){

		$arrStoreCode = explode(',',$arrIncentives[$i]['store_code']);
		$arrStoreCode = array_unique($arrStoreCode);
		$arrStoreDate = explode(',',$arrIncentives[$i]['daily_date']);
		$arrStoreDate = array_unique($arrStoreDate);
		$arrIncentives[$i]['bonus_1'] = 0;
		$arrIncentives[$i]['bonus_2'] = 0;
		$arrIncentives[$i]['total_bonus'] = 0;
		$arrPoData = grabPoPayments($arrStoreCode, $arrStoreDate);

		foreach ($arrPoData as $key => $line) {

			include 'get_percentage.php';

			$line['staffBT'] =  0;
			$line['staffAT'] = 0;
			if($valueThreshold != 'n'){
				//to get and divide staffBT by count of employee of payment_date in daily login
				$empCount = grabDailyLoginEmpCount($line['store_id'],date('Y-m-d', strtotime($line['payment_date'])));
				if($staffBelow > 0){
					$multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];
					$line['staffBT'] = $multiplierValueBelow * $staffBelow;
					$line['staffBT'] = $line['staffBT'] / $empCount;
				}
				if($staffAbove > 0 && $line['excess'] >= 0){
					$line['staffAT'] = $line['excess'] * $staffAbove;
					$line['staffAT'] = $line['staffAT'] / $empCount;
				}
			}

			$arrIncentives[$i]['bonus_1'] += $line['staffBT'];
			$arrIncentives[$i]['bonus_2'] += $line['staffAT'];
			$arrIncentives[$i]['total_bonus'] += ($line['staffBT'] +  $line['staffAT']);
		}

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small text-center">'.($i + $num).'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['name'].'</td>';					
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['emp_id'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrIncentives[$i]['designation'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.number_format($arrIncentives[$i]['bonus_1'],5).'</td>';
		echo 		'<td nowrap class="cell100 small">'.number_format($arrIncentives[$i]['bonus_2'],5).'</td>';
		echo 		'<td nowrap class="cell100 small">'.number_format($arrIncentives[$i]['total_bonus'],5).'</td>';
		echo 	'</tr>';
	}

};

function grabIncentivesCommissionTableCSV() {

	global $conn;
	global $qDate;
	global $dlDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;

	header( 'Content-Type: application/csv' );
	$filename = "incentives-commission-".date('YmdHis').".csv";
	header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
     // clean output buffer
    ob_end_clean();
    
    $handle = fopen( 'php://output', 'w' );
   
    fputcsv($handle, ['Name','Employee ID','Designation', 'Bonus 1', 'Bonus 2', 'Total Bonus']);

	include 'managers_supervisors.php';


	foreach ($arrDataManagersIncentives as $key => $value) {

		$nameValue = explode('|', $value['name']);
		$arrDataDownload['name'] = strtoupper($nameValue[0]);
		$arrDataDownload['emp_id'] =$key;
		$arrDataDownload['designation'] = $nameValue[1];
		$arrDataDownload['bonus_1'] = number_format($value['bonus_1'], 5);
		$arrDataDownload['bonus_2'] = number_format($value['bonus_2'], 5);
		$arrDataDownload['total_bonus'] = number_format($value['total_bonus'], 5);

		fputcsv($handle, $arrDataDownload);
	}



	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					et.emp_id,
					CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),
					et.designation,
					GROUP_CONCAT(dl.store_code),
					GROUP_CONCAT(dl.daily_date)
					FROM daily_login dl
					LEFT JOIN emp_table et ON et.emp_id = dl.emp_id
					WHERE et.status = 'y' ".$dlDate."
					AND dl.store_code IN ('".implode("','", $arrFilterStores)."')
					GROUP BY  dl.emp_id
					ORDER BY daily_date ASC;";
	$grabParams = array(
		'emp_id',
		'name',
		'designation',
		'store_code',
		'daily_date'
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
			$tempArray['name'] = strtoupper($tempArray['name']);
			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	

    for($i = 0 ; $i < count($arrIncentives); $i++){

    	$arrStoreCode = explode(',',$arrIncentives[$i]['store_code']);
		$arrStoreCode = array_unique($arrStoreCode);
		$arrStoreDate = explode(',',$arrIncentives[$i]['daily_date']);
		$arrStoreDate = array_unique($arrStoreDate);
		$arrIncentives[$i]['bonus_1'] = 0;
		$arrIncentives[$i]['bonus_2'] = 0;
		$arrIncentives[$i]['total_bonus'] = 0;
		$arrPoData = grabPoPayments($arrStoreCode, $arrStoreDate);

		foreach ($arrPoData as $key => $line) {

			include 'get_percentage.php';

			$line['staffBT'] =  0;
			$line['staffAT'] = 0;
			if($valueThreshold != 'n'){
				//to get and divide staffBT by count of employee of payment_date in daily login
				$empCount = grabDailyLoginEmpCount($line['store_id'],date('Y-m-d', strtotime($line['payment_date'])));
				if($staffBelow > 0){
					$multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];
					$line['staffBT'] = $multiplierValueBelow * $staffBelow;
					$line['staffBT'] = $line['staffBT'] / $empCount;
				}
				if($staffAbove > 0 && $line['excess'] >= 0){
					$line['staffAT'] = $line['excess'] * $staffAbove;
					$line['staffAT'] = $line['staffAT'] / $empCount;
				}
			}

			$arrIncentives[$i]['bonus_1'] += $line['staffBT'];
			$arrIncentives[$i]['bonus_2'] += $line['staffAT'];
			$arrIncentives[$i]['total_bonus'] += ($line['staffBT'] +  $line['staffAT']);
		}

		$arrDataDownload['name'] = strtoupper($arrIncentives[$i]['name']);
		$arrDataDownload['emp_id'] = $arrIncentives[$i]['emp_id'];
		$arrDataDownload['designation'] = $arrIncentives[$i]['designation'];
		$arrDataDownload['bonus_1'] = number_format($arrIncentives[$i]['bonus_1'], 5);
		$arrDataDownload['bonus_2'] = number_format($arrIncentives[$i]['bonus_2'], 5);
		$arrDataDownload['total_bonus'] = number_format($arrIncentives[$i]['total_bonus'], 5);

    	fputcsv($handle, $arrDataDownload);
    }

    fclose( $handle );

    // flush buffer
    ob_flush();
    
    // use exit to get rid of unexpected output afterward
    exit();

};

function grabIncentivesCommissionBreakdownTable() {

	global $conn;
	global $dlDate;
	global $arrFilterStores;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;

	// incentives data for  managers and supervisor
	$num = grabDataManagerSupervisor(true);

	// incentives data for  staff
	$arrIncentives = array();

	$query = 	"SELECT
					et.emp_id,
					CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),
					et.designation,
					GROUP_CONCAT(dl.store_code),
					GROUP_CONCAT(dl.daily_date)
					FROM daily_login dl
					LEFT JOIN emp_table et ON et.emp_id = dl.emp_id
					WHERE et.status = 'y' ".$dlDate."
					AND dl.store_code IN ('".implode("','", $arrFilterStores)."')
					GROUP BY  dl.emp_id
					ORDER BY daily_date ASC;";
	$grabParams = array(
		'emp_id',
		'name',
		'designation',
		'store_code',
		'daily_date'
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
			$tempArray['name'] = strtoupper($tempArray['name']);
			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	$arrDataStaffIncentives = [];
	$arrDataStoresDateChecked = [];
	for($i = 0 ; $i < count($arrIncentives); $i++){

		$arrStoreCode = explode(',',$arrIncentives[$i]['store_code']);
		$arrStoreCode = array_unique($arrStoreCode);
		$arrStoreDate = explode(',',$arrIncentives[$i]['daily_date']);
		$arrStoreDate = array_unique($arrStoreDate);
		$arrIncentives[$i]['bonus_1'] = 0;
		$arrIncentives[$i]['bonus_2'] = 0;
		$arrIncentives[$i]['total_bonus'] = 0;
		$arrPoData = grabPoPayments($arrStoreCode, $arrStoreDate);

		foreach ($arrPoData as $key => $line) {

			include 'get_percentage.php';

			$line['staffBT'] =  0;
			$line['staffAT'] = 0;
			if($valueThreshold != 'n'){
				//to get and divide staffBT by count of employee of payment_date in daily login
				$empCount = grabDailyLoginEmpCount($line['store_id'],date('Y-m-d', strtotime($line['payment_date'])));
				if($staffBelow > 0){
					$multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];
					$line['staffBT'] = $multiplierValueBelow * $staffBelow;
					$line['staffBT'] = $line['staffBT'] / $empCount;
				}
				if($staffAbove > 0 && $line['excess'] >= 0){
					$line['staffAT'] = $line['excess'] * $staffAbove;
					$line['staffAT'] = $line['staffAT'] / $empCount;
				}
			}

			$payment_date = date('Y-m-d', strtotime($line['payment_date']));

			if(!in_array($payment_date, $arrDataStoresDateChecked[$arrIncentives[$i]['emp_id']])){

				$arrDataStoresDateChecked[$arrIncentives[$i]['emp_id']][] = $payment_date;
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date] = 
					[
						'name' => $arrIncentives[$i]['name'],
						'designation' => $arrIncentives[$i]['designation'],
						'bonus_1' => $line['staffBT'],
						'bonus_2' =>  $line['staffAT'],
						'total_bonus' => ($line['staffBT'] +  $line['staffAT']),
						'date' => $payment_date
					];
			}else{
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date]['bonus_1'] += $line['staffBT'];
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date]['bonus_2'] += $line['staffAT'];
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date]['total_bonus'] += ($line['staffBT'] +  $line['staffAT']);
			}

		}

		
	}

	$rowLine = $num;
	foreach ($arrDataStaffIncentives as $key => $arrEmp) {
		foreach ($arrEmp as $dateDay => $value) {
			echo 	'<tr class="row100 body">';
			echo 		'<td nowrap class="cell100 small text-center">'.($rowLine).'</td>';
			echo 		'<td nowrap class="cell100 small">'.strtoupper($value['name']).'</td>';					
			echo 		'<td nowrap class="cell100 small">'.$key.'</td>';
			echo 		'<td nowrap class="cell100 small">'.$value['designation'].'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($value['bonus_1'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($value['bonus_2'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($value['total_bonus'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.cvdate3($value['date']).'</td>';
			echo 	'</tr>';

			$rowLine++;
		}
		
	}

};

function grabIncentivesCommissionBreakdownTableCSV() {

	global $conn;
	global $qDate;
	global $dlDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $arrStoresManagers;
	global $arrThreshold;
	global $arrPercentageBelow;
	global $arrPercentageAbove;

	header( 'Content-Type: application/csv' );
	$filename = "incentives-commission-breakdown-".date('YmdHis').".csv";
	header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
     // clean output buffer
    ob_end_clean();
    
    $handle = fopen( 'php://output', 'w' );
   
    fputcsv($handle, ['Name','Employee ID','Designation', 'Bonus 1', 'Bonus 2', 'Total Bonus','Date']);

	include 'managers_supervisors_perdate.php';
	foreach ($arrDataManagersIncentives as $key => $arrEmp) {
		foreach ($arrEmp as $dateDay => $value) {

			$nameValue = explode('|', $value['name']);
			$arrDataDownload['name'] = strtoupper($nameValue[0]);
			$arrDataDownload['emp_id'] =$key;
			$arrDataDownload['designation'] = $nameValue[1];
			$arrDataDownload['bonus_1'] = number_format($value['bonus_1'], 5);
			$arrDataDownload['bonus_2'] = number_format($value['bonus_2'], 5);
			$arrDataDownload['total_bonus'] = number_format($value['total_bonus'], 5);
			$arrDataDownload['date'] =cvdate3($value['date']);

			fputcsv($handle, $arrDataDownload);
		}
	}



	// Set array
	$arrIncentives = array();

	$query = 	"SELECT
					et.emp_id,
					CONCAT_WS(' ',et.first_name, CONCAT(LEFT(et.middle_name,1),'.'), et.last_name),
					et.designation,
					GROUP_CONCAT(dl.store_code),
					GROUP_CONCAT(dl.daily_date)
					FROM daily_login dl
					LEFT JOIN emp_table et ON et.emp_id = dl.emp_id
					WHERE et.status = 'y' ".$dlDate."
					AND dl.store_code IN ('".implode("','", $arrFilterStores)."')
					GROUP BY  dl.emp_id
					ORDER BY daily_date ASC;";
	$grabParams = array(
		'emp_id',
		'name',
		'designation',
		'store_code',
		'daily_date'
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
			$tempArray['name'] = strtoupper($tempArray['name']);
			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	
	$arrDataStaffIncentives = [];
	$arrDataStoresDateChecked = [];
    for($i = 0 ; $i < count($arrIncentives); $i++){

    	$arrStoreCode = explode(',',$arrIncentives[$i]['store_code']);
		$arrStoreCode = array_unique($arrStoreCode);
		$arrStoreDate = explode(',',$arrIncentives[$i]['daily_date']);
		$arrStoreDate = array_unique($arrStoreDate);
		$arrIncentives[$i]['bonus_1'] = 0;
		$arrIncentives[$i]['bonus_2'] = 0;
		$arrIncentives[$i]['total_bonus'] = 0;
		$arrPoData = grabPoPayments($arrStoreCode, $arrStoreDate);

		foreach ($arrPoData as $key => $line) {

			include 'get_percentage.php';

			$line['staffBT'] =  0;
			$line['staffAT'] = 0;
			if($valueThreshold != 'n'){
				//to get and divide staffBT by count of employee of payment_date in daily login
				$empCount = grabDailyLoginEmpCount($line['store_id'],date('Y-m-d', strtotime($line['payment_date'])));
				if($staffBelow > 0){
					$multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];
					$line['staffBT'] = $multiplierValueBelow * $staffBelow;
					$line['staffBT'] = $line['staffBT'] / $empCount;
				}
				if($staffAbove > 0 && $line['excess'] >= 0){
					$line['staffAT'] = $line['excess'] * $staffAbove;
					$line['staffAT'] = $line['staffAT'] / $empCount;
				}
			}

			$payment_date = date('Y-m-d', strtotime($line['payment_date']));

			if(!in_array($payment_date, $arrDataStoresDateChecked[$arrIncentives[$i]['emp_id']])){

				$arrDataStoresDateChecked[$arrIncentives[$i]['emp_id']][] = $payment_date;
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date] = 
					[
						'name' => $arrIncentives[$i]['name'],
						'designation' => $arrIncentives[$i]['designation'],
						'date' => $payment_date,
						'bonus_1' => $line['staffBT'],
						'bonus_2' =>  $line['staffAT'],
						'total_bonus' => ($line['staffBT'] +  $line['staffAT'])
					];
			}else{
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date]['bonus_1'] += $line['staffBT'];
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date]['bonus_2'] += $line['staffAT'];
				$arrDataStaffIncentives[$arrIncentives[$i]['emp_id']][$payment_date]['total_bonus'] += ($line['staffBT'] +  $line['staffAT']);
			}
		}

		
    }
	foreach ($arrDataStaffIncentives as $key => $arrEmp) {
		foreach ($arrEmp as $dateDay => $value) {

			$arrDataDownload['name'] = strtoupper($value['name']);
			$arrDataDownload['emp_id'] = $key;
			$arrDataDownload['designation'] = $value['designation'];
			$arrDataDownload['bonus_1'] = number_format($value['bonus_1'], 5);
			$arrDataDownload['bonus_2'] = number_format($value['bonus_2'], 5);
			$arrDataDownload['total_bonus'] = number_format($value['total_bonus'], 5);
			$arrDataDownload['date'] = cvdate3($value['date']);

	    	fputcsv($handle, $arrDataDownload);
		}
		
	}


    fclose( $handle );

    // flush buffer
    ob_flush();
    
    // use exit to get rid of unexpected output afterward
    exit();

};

function grabIncentivesPercentage() {

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

	$query = 	"SELECT DISTINCT
					DATE(os.payment_date),
					sl.store_name,
					sl.store_id
					FROM orders o
					LEFT JOIN orders_specs os ON o.order_id = os.order_id
					LEFT JOIN stores_locations sl ON o.origin_branch = sl.store_id
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
					ORDER BY sl.store_name, os.payment_date ASC
						;";

	$grabParams = array(
		'payment_date',
		'store_name',
		'store_id'
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
			$tempArray['total'] = 0;

			$arrIncentives[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	$arrDataStoresIncentives = [];
	$arrDataStoresDateChecked =[];
	foreach ($arrIncentives as $key => $line) {		
		include 'get_percentage.php';
		$threshold = ($valueThreshold == 'n') ? 'N/A' : $valueThreshold;

		if(!array_key_exists($line['store_id'], $arrDataManagersIncentives)){
			if(!in_array($line['payment_date'], $arrDataStoresDateChecked[$line['store_id']])){

				$arrDataStoresDateChecked[$line['store_id']][] =$line['payment_date'];
				$arrDataStoresIncentives[$line['store_id']][] = 
					[
						'store_name' => $line['store_name'],
						'store_code' => $line['store_id'],
						'date' => $line['payment_date'],
						'threshold' => $threshold,
						'srambelow' => $sramDataBelow,
						'ambelow' => $amDataBelow,
						'supbelow' => $supareaDataBelow,
						'staffbelow' => $staffBelow,
						'sramabove' => $sramDataAbove,
						'amabove' => $amDataAbove,
						'supabove' => $supareaDataAbove,
						'staffabove' => $staffAbove
					];
			}
		}
	}

	$rowLine = 1;
	foreach ($arrDataStoresIncentives as $key => $value) {
		foreach ($value as $key => $rowData) {
			echo 	'<tr class="row100 body">';
			echo 		'<td nowrap class="cell100 small text-center">'.($rowLine).'</td>';
			echo 		'<td nowrap class="cell100 small">'.strtoupper($rowData['store_name']).'</td>';					
			echo 		'<td nowrap class="cell100 small">'.$rowData['store_code'].'</td>';
			echo 		'<td nowrap class="cell100 small">'.cvdate3($rowData['date']).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['threshold'],2).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['srambelow'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['ambelow'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['supbelow'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['staffbelow'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['sramabove'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['amabove'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['supabove'],5).'</td>';
			echo 		'<td nowrap class="cell100 small">'.number_format($rowData['staffabove'],5).'</td>';
			echo 	'</tr>';
			$rowLine++;
		}
	}
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
		case 'grabIncentivesCommissionTable':
			grabIncentivesCommissionTable();
			break;
		case 'grabIncentivesCommissionTableCSV':
			grabIncentivesCommissionTableCSV();
			break;
		case 'grabIncentivesCommissionBreakdownTable':
			grabIncentivesCommissionBreakdownTable();
			break;
		case 'grabIncentivesCommissionBreakdownTableCSV':
			grabIncentivesCommissionBreakdownTableCSV();
			break;
		case 'grabIncentivesPercentage':
			grabIncentivesPercentage();
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