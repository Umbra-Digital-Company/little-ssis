<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
function cvdate2($date){
	$arrDate = explode(' ', $date);

	$AMPM = explode(':', $arrDate[1]);
	$hour = $AMPM[0];
	$min = $AMPM[1];
	$AMPM = ($hour <= 12) ? 'AM' : 'PM';
	$hour = ($hour > 12) ? hourSet($hour) : $hour;
	$time = $hour.':'.$min.' '.$AMPM;

	$date = explode('-', $arrDate[0]);

	$year = $date[0];
	$month = getMonth2($date[1]);
	$day = $date[2];

	return $month.' '.$day.', '.$year.' - '.$time;
}
function hourSet($hour){
	switch($hour){
		case '13': return "01"; break;
		case '14': return "02"; break;
		case '15': return "03"; break;
		case '16': return "04"; break;
		case '17': return "05"; break;
		case '18': return "06"; break;
		case '19': return "07"; break;
		case '20': return "08"; break;
		case '21': return "09"; break;
		case '22': return "10"; break;
		case '23': return "11"; break;
		case '24': return "12"; break;
		
	}
}
function getMonth2($mid){
	switch($mid){
		case '01': return "Jan"; break;
		case '02': return "Feb"; break;
		case '03': return "Mar"; break;
		case '04': return "Apr"; break;
		case '05': return "May"; break;
		case '06': return "Jun"; break;
		case '07': return "Jul"; break;
		case '08': return "Aug"; break;
		case '09': return "Sep"; break;
		case '10': return "Oct"; break;
		case '11': return "Nov"; break;
		case '12': return "Dec"; break;
		
	}
}

if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-t');
	}elseif($_GET['date']=='week'){
		$dateStart = date( 'Y-m-d', strtotime( 'monday this week' ) );
		 $dateEnd = date( 'Y-m-d', strtotime( 'sunday this week' ) );
	}
	elseif($_GET['date']=='custom'){
		 $dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		 $dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
	}
	elseif($_GET['date']=='all-time'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	else{
		$dateStart = date('Y-m-d');
			$dateEnd= date('Y-m-t');
	}
	
}
else{
	$dateStart = date('Y-m-d');
		$dateEnd= date('Y-m-t');
}

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrReceivable = [];

$query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 12 HOUR),
				i.reference_number,
				i.type,
				SUM(i.count) AS 'total_items',
				SUM(i.actual_count) AS 'total_items_received',
				i.status,
				i.store_id,
				CASE
                    WHEN i.store_id = 'warehouse' THEN 'warehouse'
					WHEN i.store_id = 'hq' THEN 'Sunnies HQ'
					WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN i.store_id = 'warehouse_qa' THEN 'warehouse QA'
					WHEN i.store_id = 'manufacturer' THEN 'manufacturer'
					WHEN sls.store_name != '' THEN LOWER(sls.store_name)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE ''
				END AS store_name_to,
				i.stock_from,
				CASE
					WHEN i.stock_from = 'warehouse' THEN 'warehouse'
					WHEN i.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN i.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN i.stock_from = 'warehouse_qa' THEN 'warehouse QA'
					WHEN i.stock_from = 'manufacturer' THEN 'manufacturer'
					WHEN sl.store_name != '' THEN LOWER(sl.store_name)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				i.admin_id,
				i.admin_name,
				i.sender,
				ae.first_name,
				ae.last_name
			FROM 
				inventory i
					LEFT JOIN stores_locations sl
						ON sl.store_id = i.stock_from
                    LEFT JOIN stores_locations sls
						ON sls.store_id = i.store_id
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
					LEFT JOIN admin_employee ae
						ON ae.emp_no = i.sender";

			if (isset($_GET['ref_num'])) {
				$query .= " WHERE i.reference_number = '".$_GET['ref_num']."' ";
			} elseif (isset($_GET['date'])) {
				$query .= " WHERE DATE(DATE_ADD(i.date_created, INTERVAL 13 HOUR)) >= '".$dateStart."' AND DATE(DATE_ADD(i.date_created, INTERVAL 13 HOUR)) <= '".$dateEnd."' ";
				if ($_GET['filterReceiver']!='') {
					$query .= " AND i.store_id = '".$_GET['filterReceiver']."' ";
				}
				if ($_GET['filterSender']!='') {
					$query .= " AND i.stock_from = '".$_GET['filterSender']."' ";
				}
				if (isset($_GET['filterType']) && $_GET['filterType']!='all') {
					$query .= " AND i.type = '".$_GET['filterType']."' ";
				}
				if (isset($_GET['filterStatus']) && $_GET['filterStatus']!='all') {
					$query .= " AND i.status = '".$_GET['filterStatus']."' ";
				}
			} else {
				$query .= " WHERE DATE(DATE_ADD(i.date_created, INTERVAL 13 HOUR)) = '".date('Y-m-d')."' ";
			}
			$query .= " GROUP BY
				i.reference_number
			ORDER BY				
				i.date_created DESC,
				i.delivery_unique ASC";

$grabParams = array(
	'date_created',
	'reference_number',
	'type',
	'total_items',
	'total_items_received',
	'status',
	'stock_to_id',
	'stock_to_name',
	'stock_from_id',
	'stock_from_branch',
	'admin_id',
	'admin_name',
	'sender_id',
	'sender_first_name',
	'sender_last_name',
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
		$arrData = [];
		$arrData['type'] = ucwords(str_replace("_", " ", $tempArray['type']));
		$arrData['from'] =  ucwords(str_replace(['mw','ali','mtc','sm','hq','qa','-'],['MW','ALI','MTC','SM','HQ','QA',' '],strtolower($tempArray['stock_from_branch'])));
		$arrData['to'] =  ucwords(str_replace(['mw','ali','mtc','sm','hq','qa','-'],['MW','ALI','MTC','SM','HQ','QA',' '],strtolower($tempArray['stock_to_name'])));
		$arrData['total_sent'] = $tempArray['total_items'];
		$arrData['total_received'] = $tempArray['total_items_received'];
		$arrData['status'] = ucwords($tempArray['status']);
		$arrData['reference_number'] = $tempArray['reference_number'];
		$arrData['data_sent'] = cvdate2($tempArray['date_created']);

		$arrReceivable[] = $arrData;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);	

};

include 'history_specific.php';


array_to_csv_download(itemSpecific($arrReceivable));
function array_to_csv_download($array, $delimiter=",") {
    header('Content-Type: application/csv');
    header('Content-Disposition: attachment; filename="history_all_'.date('YmdHis').'.csv";');

    // open the "output" stream
    // see http://www.php.net/manual/en/wrappers.php.php#refsect2-wrappers.php-unknown-unknown-unknown-descriptioq
    $f = fopen('php://output', 'w');

    foreach ($array as $line) {
        fputcsv($f, $line, $delimiter);
    }
}
//print_r($arrReceivable); exit;

?>