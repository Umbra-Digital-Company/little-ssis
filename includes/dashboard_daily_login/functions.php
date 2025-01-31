<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();
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

	$arrFilterStores = ($_SESSION['user_login']['position'] == 'supervisor') ? explode(',',$_SESSION['user_login']['store_location']) : array();

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
$removeStoreIDs = "AND o.store_id NOT IN ('142', '1000', '900', '991', '126', '130','787', '788','150', '999','139','155')";
$removeStoreLocations = "sl.store_id NOT IN ('142', '1000', '900', '991', '126', '130','787', '788','150', '999','139','155')";

//////////////////////////////////////////////////////////////////////////////////// HELPER FUNCTIONS

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

//////////////////////////////////////////////////////////////////////////////////// DASHBOARD FUNCTIONS


function grabEmployeesDataLoginTable() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $dlDate;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			}
					
			$specStore .= "s.store_id = '".$arrFilterStores[$i]."'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrDataLogin = array();

	$query = 	"SELECT
					CONCAT_WS(' ', e.first_name, CONCAT(LEFT(e.middle_name, 1),'.'), e.last_name ) AS name,
					e.emp_id,
					s.store_name,
					dl.device,
					dl.ip_address,
                    dl.daily_date,
                    dl.latitude,
                    dl.longitude
                FROM
                    daily_login dl
                    LEFT JOIN emp_table e ON dl.emp_id = e.emp_id
                    LEFT JOIN stores_locations s  ON s.store_id=dl.store_code
                WHERE 
                    dl.daily_in = 1 ". $dlDate."
                    ".$specStore." ORDER BY s.store_name, name, dl.daily_date; ";

	$grabParams = array(
		'name',
		'emp_id',
		'store_name',
		'device',
		'ip_address',
		'daily_date',
		'latitude',
		'longitude'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrDataLogin[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	
	foreach ($arrDataLogin as $key => $value) {

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small">'.($key+1).'</td>';
		echo 		'<td nowrap class="cell100 small">'.$value['name'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$value['emp_id'].'</td>';			
		echo 		'<td nowrap class="cell100 small">'.$value['store_name'].'</td>';
		echo 	($value['latitude'] != '') ? '<td nowrap class="cell100 small"><a href="https://www.google.com/maps?q='.$value['latitude'].','.$value['longitude'].'&z=18" rel="noopener noreferrer" target="_blank">Location Link</a></td>' :  '<td nowrap class="cell100 small text-center" style="color:red;">N/A</td>';		
		echo 		'<td nowrap class="cell100 small">'.$value['ip_address'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$value['daily_date'].'</td>';
		echo 		'<td nowrap class="cell100 small "><div class="device-limit">'.$value['device'].'</div></td>';
		echo 	'</tr>';
	}

};

function grabEmployeesDataLoginCSV() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $dlDate;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			}
					
			$specStore .= "s.store_id = '".$arrFilterStores[$i]."'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrDataLogin = array();

	$query = 	"SELECT
					CONCAT_WS(' ', e.first_name, CONCAT(LEFT(e.middle_name, 1),'.'), e.last_name ) AS name,
					e.emp_id,
					s.store_name,
					dl.device,
					dl.ip_address,
                    dl.daily_date,
                    dl.latitude,
                    dl.longitude
                FROM
                    daily_login dl
                    LEFT JOIN emp_table e ON dl.emp_id = e.emp_id
                    LEFT JOIN stores_locations s  ON s.store_id=dl.store_code
                WHERE 
                    dl.daily_in = 1 ". $dlDate."
                    ".$specStore." ORDER BY s.store_name, name, dl.daily_date; ";

	$grabParams = array(
		'name',
		'emp_id',
		'store_name',
		'device',
		'ip_address',
		'daily_date',
		'latitude',
		'longitude'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrDataLogin[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	header( 'Content-Type: application/csv' );
	$filename = "employee-daily-logs".date('YmdHis').".csv";
	header( 'Content-Disposition: attachment; filename="' . $filename . '";' );
     // clean output buffer
    ob_end_clean();
    
    $handle = fopen( 'php://output', 'w' );
   
    fputcsv($handle, ['NAME','EMPLOYEE ID','STORE','LOCATION LINK','IP ADDRESS', 'DATE','DEVICE']);

    foreach ($arrDataLogin as $key => $line) {

    	$csvData['name'] = $line['name'];
    	$csvData['emp_id'] = $line['emp_id'];
    	$csvData['store_name'] = $line['store_name'];
    	$csvData['location'] = ($line['latitude'] != '') ? 'https://www.google.com/maps?q='.$line['latitude'].','.$line['longitude'].'&z=18' :  'N/A';
    	$csvData['ip_address'] = $line['ip_address'];
    	$csvData['daily_date'] = $line['daily_date'];
    	$csvData['device'] = $line['device'];
    	fputcsv($handle, $csvData);
    }

    fclose( $handle );

    // flush buffer
    ob_flush();
    
    // use exit to get rid of unexpected output afterward
    exit();

};

function grabEmployeesNoLoginTable() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $dlDate;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			}
					
			$specStore .= "s.store_id = '".$arrFilterStores[$i]."'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrDataLogin = array();

	$query = 	"SELECT
					CONCAT_WS(' ', e.first_name, CONCAT(LEFT(e.middle_name, 1),'.'), e.last_name ) AS name,
					e.emp_id,
					s.store_name,
					dl.device,
					dl.ip_address,
                    dl.daily_date,
                    dl.latitude,
                    dl.longitude
                FROM
                    daily_login dl
                    LEFT JOIN emp_table e ON dl.emp_id = e.emp_id
                    LEFT JOIN stores_locations s  ON s.store_id=dl.store_code
                WHERE 
                    dl.daily_in = 1 ". $dlDate."
                    ".$specStore." ORDER BY s.store_name, name, dl.daily_date; ";

	$grabParams = array(
		'name',
		'emp_id',
		'store_name',
		'device',
		'ip_address',
		'daily_date',
		'latitude',
		'longitude'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrDataLogin[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	
	foreach ($arrDataLogin as $key => $value) {

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small">'.($key+1).'</td>';
		echo 		'<td nowrap class="cell100 small">'.$value['name'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$value['emp_id'].'</td>';			
		echo 		'<td nowrap class="cell100 small">'.$value['store_name'].'</td>';
		echo 	($value['latitude'] != '') ? '<td nowrap class="cell100 small"><a href="https://www.google.com/maps?q='.$value['latitude'].','.$value['longitude'].'&z=18" rel="noopener noreferrer" target="_blank">Location Link</a></td>' :  '<td nowrap class="cell100 small text-center" style="color:red;">N/A</td>';		
		echo 		'<td nowrap class="cell100 small">'.$value['ip_address'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$value['daily_date'].'</td>';
		echo 		'<td nowrap class="cell100 small "><div class="device-limit">'.$value['device'].'</div></td>';
		echo 	'</tr>';
	}

};

function grabEmployeesCount() {

	global $conn;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $removeStoreLocations;
	global $revBreakdownSort;
	global $customMonth;
	global $dlDate;
	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";	

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			};		
		
			$specStore .= "sl.store_id = '".$arrFilterStores[$i]."'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	if($revBreakdownSort != "") {

		$querySort = 'total '.$revBreakdownSort;

	}
	else {

		$querySort = 'sl.store_name';

	};

	// Set array
	$arrRevenue = array();

		$query = 	"SELECT
						sl.store_id,
						sl.store_name,
						COUNT(dl.emp_id) AS total
					FROM
						stores_locations sl
							LEFT JOIN 
							daily_login dl ON dl.store_code = sl.store_id ".$dlDate."
								WHERE
									".$removeStoreLocations."
									".$specStore."
									GROUP BY sl.store_id ORDER BY
									".$querySort;
		// echo $query; exit;
	$grabParams = array(
		'store_id',
		'store_name',
		'total'
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

			$arrRevenue[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	// SET CURRENT GRAND TOTAL
	$curGrandTotalID = "";
	$curGrandTotal = 0;

	// ECHO DATA LIST
	for ($i=0; $i < sizeOf($arrRevenue); $i++) { 

		//
		$storenumber=$i+1;

		// Set current data
		$curStoreID    = $arrRevenue[$i]['store_id'];
		$curStoreName  = ucwords(str_replace("u.p.", "UP", str_replace("sm", "SM", str_replace("-", " ", $arrRevenue[$i]['store_name']))));
		$curStoreTotal = $arrRevenue[$i]['total'];

		// Check if highest total
		if($curStoreTotal > $curGrandTotal) {

			$curGrandTotal = $curStoreTotal;
			$curGrandTotalID = $curStoreID;

		};
	
		echo 	'<div class="store-row row no-gutters align-items-center" data-store-id="'.$curStoreID.'" data-store-total="'.$curStoreTotal.'">';
		echo 		'<div class="col-7 col-md-3 store-name">';
		echo 			'<div class="row">';
		echo 				'<p class="col-1 text-right">'.($i + 1).'</p>';
		echo 				'<p class="col-10 text-right">'.$curStoreName.'</p>';
		echo 			'</div>';
		echo 		'</div>';
		echo 		'<div class="col-5 col-md-9">';
		echo 			'<div class="row flex-column flex-md-row no-gutters align-items-start align-items-md-center pl-3 pr-3" style="border-left: 1px solid #000000;">';
		echo 				'<div class="col-12 col-lg-2 store-total">';
		echo 					'<p class="col-12 text-left p-0 pl-md-3 pr-md-3 pt-2 pb-2">'.$arrRevenue[$i]['total'].'</p>';
		echo 				'</div>';
		echo 				'<div class="col-9 col-lg-10 store-total-bar">';
		echo 					'<div class="data-bar" id="dataBar'.$curStoreID.'" data-store-total="'.$curStoreTotal.'"></div>';
		echo 				'</div>';
		echo 			'</div>';
		echo 		'</div>';
		echo 	'</div>';

	};	

	// ECHO HIGHEST TOTAL
	echo 	'<div id="revenue-breakdown-highest" data-store-id="'.$curGrandTotalID.'" data-total="'.$curGrandTotal.'"></div>';	

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

		
		case 'grabEmployeesDataLoginTable':
			grabEmployeesDataLoginTable();
			break;
		case 'grabEmployeesDataLoginCSV':
			grabEmployeesDataLoginCSV();
			break;
		case 'grabEmployeesNoLoginTable':
			grabEmployeesNoLoginTable();
			break;
		case 'grabEmployeesCount':
			grabEmployeesCount();
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