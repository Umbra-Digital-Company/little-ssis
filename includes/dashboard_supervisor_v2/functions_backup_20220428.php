<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if (session_status() == PHP_SESSION_NONE) {

	session_start();

};

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

// Grab GET settings
if(isset($_GET['date']) && $_GET['date'] != 'custom') {

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
		case 'monthYear':
					
				$qGrabDateA = (strlen($_GET['data_range_month']) == 1) ? '0'.$_GET['data_range_month'] : $_GET['data_range_month'];
				$qGrabDateB = $_GET['data_range_year'];
					
					$qDate = 	" DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateA."
									AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateB;

					$dlDate = 	" AND DATE_FORMAT(dl.daily_date, '%m') = ".$qGrabDateA."
									AND DATE_FORMAT(dl.daily_date, '%Y') = ".$qGrabDateB;
			break;
		case 'year':
			$qGrabDate = date("Y");
			$qDate = "DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDate;
			break;

		case 'all-time':
			$qGrabDate = date("Y");
			$qDate = 	"DATE_FORMAT(os.payment_date, '%Y') <= ".$qGrabDate;
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

// Grab Session Stores
$arrSessionStores = explode(',', $_SESSION['user_login']['store_location']);

// Set stores array
if(isset($_GET['filterStores'])) {

	$arrFilterStores = $_GET['filterStores'];

}
else {

	$arrFilterStores = $arrSessionStores;

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

function grabFrames($number_of_frames = NULL) {

	global $conn;
	global $qDate;
	global $arrFilterStores;	

	// Set number of frames to grab
	if($number_of_frames == NULL) {

		$limit = "";

	}
	else {

		$limit = "LIMIT ".$number_of_frames;

	};

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
	$arrFrames = array();

	$query = 	"SELECT  
					os.date_created,
					os.date_updated,
					os.order_id,
					os.product_code,					
                    TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
                    REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
                    p51.item_name,
					os.lens_code,
					os.prescription_vision,
					os.price,
					os.prescription_vision,
					os.lens_option,
					os.status,
					os.payment,
					os.target_date,
					os.store_dispatch_date
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_code	
				WHERE					
					".$qDate."
					".$specStore."
					
					AND os.status IN ('dispatched', 'received', 'paid', 'complete')		
					AND os.dispatch_type!='packaging'			
				ORDER by 
					o.date_created
				".$limit; 

	$grabParams = array(
		'date_created',
		'date_updated',
		'order_po_id',
		'product_code',
		'style',
		'color',
		'item_name',
		'lens_code',
		'vision',
		'price',
		'prescription_vision',
		'lens_option',
		'status',
		'payment',
		'target_date',
		'store_dispatch_date',
		'on_time'
	);	

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrFrames[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrFrames;

};

function grabItemsSoldSpecs() {

	global $conn;
	global $qDate;
	global $arrFilterStores;

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
	$arrBestFrame = array();

	$query = 	"SELECT  
					os.product_code AS 'product_code',
				    p51.item_name AS 'item_name',
				    COUNT(os.product_code) AS 'count_frames',
				    COUNT(
				    	case when os.status = 'cancelled' then 1 end
				    ) AS 'count_cancelled',
				    COUNT(
				    	case when os.status = 'complete' then 1 end
				    ) AS 'count_complete',
				    COUNT(
				    	case when os.status = 'dispatched' then 1 end
				    ) AS 'count_dispatched',
				    COUNT(
				    	case when os.status = 'failed' then 1 end
				    ) AS 'count_failed',
				    COUNT(
				    	case when os.status = 'for exam' then 1 end
				    ) AS 'count_for_exam',
				    COUNT(
				    	case when os.status = 'for payment' then 1 end
				    ) AS 'count_for_payment',
				    COUNT(
				    	case when os.status = 'paid' then 1 end
				    ) AS 'count_paid',
				    COUNT(
				    	case when os.status = 'received' then 1 end
				    ) AS 'count_received',
				    COUNT(
				    	case when os.status = 'return' then 1 end
				    ) AS 'count_return',
				    COUNT(
				    	case when os.status = 'returned' then 1 end
				    ) AS 'count_returned',
					COUNT(
				    	case when (os.lens_option = 'with prescription' OR (os.lens_option='without prescription' AND lens_code IN ('L035' ,'ML0035')  ) ) AND os.payment = 'y' And os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' ) then 1 end
				    ) AS 'count_with_prescription',
				    COUNT(
				    	case when os.product_upgrade = 'fashion_lens'  AND os.payment = 'y' And os.status  NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_without_prescription',
				    COUNT(
				    	case when os.lens_option = 'lens only' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_lens_only',
				    COUNT(
				    	case when os.prescription_vision = 'single_vision_stock' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_single_vision_stock',
				    COUNT(
				    	case when os.prescription_vision = 'single_vision_rx' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_single_vision_rx',
				    COUNT(
				    	case when os.prescription_vision = 'double_vision_stock' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_double_vision_stock',
				    COUNT(
				    	case when os.prescription_vision = 'double_vision_rx' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_double_vision_rx',
				    COUNT(
				    	case when os.prescription_vision = 'progressive_stock' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_progressive_stock',
				    COUNT(
				    	case when os.prescription_vision = 'progressive_rx' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_progressive_rx',
				    COUNT(
				    	case when os.product_upgrade = 'special_order' AND os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )   AND os.lens_code NOT IN ('SO003','SO002','SO001') then 1 end
				    ) AS 'count_special_order',
				    COUNT(
				    	case when os.prescription_vision = '' then 1 end
				    ) AS 'count_none',
					COUNT(
				    	case when os.lens_code='SO001' OR (p51d.vision='VIS0001' AND  p51d.house_brand='HBR0002') then 1 end
				    ) AS 'count_special_sv',

					COUNT(
						case when os.lens_code='SO002' OR (p51d.vision='VIS0003' AND  p51d.house_brand='HBR0002')  then 1 end
				    ) AS 'count_special_dv',
					COUNT(
						case when os.lens_code='SO003' OR (p51d.vision='VIS0002' AND  p51d.house_brand='HBR0002')  then 1 end
				    ) AS 'count_special_px',  
				    COUNT( case when  ( os.lens_code IN ('L017','L016','L018','L020','L023','L021','L022','L024','L049','L050','L051','L052','L053','SO001' ,'SO002','SO003')  OR product_upgrade='special_order' ) AND product_upgrade!='fashion_lens' AND os.payment = 'y' And os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )   then 1 end
				    ) AS 'count_essilor',
				    COUNT( case when os.lens_code NOT IN ('L017','L016','L018','L020','L023','L021','L022','L024','L049','L050','L051','L052','L053','SO001' ,'SO002','SO003' ) AND product_upgrade!='special_order'  AND product_upgrade!='fashion_lens'  AND os.payment = 'y' And os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )  then 1 end
				    ) AS 'count_housebrand',
				     SUM( case when ( os.lens_code IN ('L017','L016','L018','L020','L023','L021','L022','L024','L049','L050','L051','L052','L053','SO001' ,'SO002','SO003')  OR product_upgrade='special_order' )  AND product_upgrade!='fashion_lens' AND os.payment = 'y' And os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' )   then p.total end
				    ) AS 'sum_essilor',
					SUM( case when os.lens_code NOT IN ('L017','L016','L018','L020','L023','L021','L022','L024','L049','L050','L051','L052','L053','SO001' ,'SO002','SO003' ) AND product_upgrade!='special_order'  AND product_upgrade!='fashion_lens'  AND os.payment = 'y' And os.status NOT IN ('return','cancelled','returned','failed','for exam', 'for payment', '' ) then p.total end
				    ) AS 'sum_housebrand'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN payments p
							ON os.po_number = p.po_number
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_code
						LEFT JOIN poll_51_development p51d
							ON p51d.product_code = os.lens_code
				WHERE
					".$qDate."
						".$specStore."
						
						AND os.product_upgrade <> 'sunnies_studios'
						AND os.product_code <> 'M100'
						AND os.product_code <> 'S100'
						AND os.product_upgrade <> 'G100'
						AND os.payment = 'y'
						AND os.po_number IS NOT NULL
						AND os.po_number != ''
				GROUP BY
					p51.item_name
				ORDER BY
					count_frames DESC"; 

	$grabParams = array(
		'product_code',
		'item_name',
		'count_frames',
		'count_cancelled',
		'count_complete',
		'count_dispatched',
		'count_failed',
		'count_for_exam',
		'count_for_payment',
		'count_paid',
		'count_received',
		'count_return',
		'count_returned',
		'count_with_prescription',
		'count_without_prescription',
		'count_lens_only',
		'count_single_vision_stock',
		'count_single_vision_rx',
		'count_double_vision_stock',
		'count_double_vision_rx',
		'count_progressive_stock',
		'count_progressive_rx',
		'count_special_order',
		'count_none',
		'count_special_sv',
		'count_special_dv',
		'count_special_px',
		'count_essilor',
		'count_housebrand',
		'sum_essilor',
		'sum_housebrand'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15,
		 $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25, $result26, $result27, $result28, $result29, $result30, $result31);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrBestFrame[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrBestFrame;

};

function grabItemsSoldStudios() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrStudios = array();

	$query = 	"SELECT  
					os.product_code AS 'product_code',
				    p51.item_name AS 'item_name',
				    COUNT(os.product_code) AS 'count_frames'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51_studios p51
							ON p51.product_code = os.product_code
				WHERE
					".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.product_upgrade = 'sunnies_studios'
						AND os.payment = 'y'
				GROUP BY
					p51.item_name
				ORDER BY
					count_frames DESC"; 

	$grabParams = array(
		'product_code',
		'item_name',
		'count_frames'
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

			$arrStudios[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrStudios;

};

function grabItemsSoldMerch() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrMerch = array();

	$query = 	"SELECT  
					os.product_code AS 'product_code',
				    p51.item_name AS 'item_name',
				    p51_studios.item_name AS 'item_name_studios',
				    COUNT(os.product_upgrade) AS 'count_merch'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_upgrade
						LEFT JOIN poll_51_studios p51_studios
							ON p51_studios.product_code = os.product_upgrade
				WHERE
					".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.product_code = 'M100'						
						AND os.payment = 'y'
						AND os.dispatch_type!='packaging'
				GROUP BY
					os.product_upgrade						
				ORDER BY
					os.product_code ASC"; 

	$grabParams = array(
		'product_code',
		'item_name',
		'item_name_studios',
		'count_merch'
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

			$arrMerch[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));
		exit;

	};

	return $arrMerch;

};

function grabItemsSoldAntiRad() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrAntiRad = array();

	$query = 	"SELECT  
					os.product_code AS 'product_code',
				    p51.item_name AS 'item_name',
				    p51_studios.item_name AS 'item_name_studios',
				    COUNT(os.product_upgrade) AS 'count_antirad'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_upgrade
						LEFT JOIN poll_51_studios p51_studios
							ON p51_studios.product_code = os.product_upgrade
				WHERE
					".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.product_upgrade = 'G100'							
						AND os.payment = 'y'
						AND os.dispatch_type!='packaging'
				GROUP BY
					os.product_code						
				ORDER BY
					count_antirad ASC"; 

	$grabParams = array(
		'product_code',
		'item_name',
		'item_name_studios',
		'count_antirad'
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

			$arrAntiRad[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));
		exit;

	};

	return $arrAntiRad;

};

function grabBestFramesTable() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrBestFrame = array();

	$query = 	"SELECT  
					os.product_code,
				    p51.item_name,
				    COUNT(os.product_code) AS 'count'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_code
				WHERE
					".$qDate."
					".$specStore."
					
						AND os.payment = 'y'					
						AND os.status IN ('dispatched', 'received', 'paid', 'complete')
						AND os.product_upgrade <> 'sunnies_studios'
						AND os.product_code <> 'M100'
						AND os.product_code <> 'S100'
						AND os.product_code <> 'F100'
						AND os.product_upgrade <> 'G100'
				GROUP BY
					p51.item_name
				ORDER BY
					count DESC"; 

	$grabParams = array(
		'product_code',
		'item_name',
		'count'
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

			$arrBestFrame[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	// ECHO OUT ROWS FOR TABLE
	for ($i=0; $i < sizeOf($arrBestFrame); $i++) { 

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small">'.($i + 1).'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['item_name'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['product_code'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['count'].'</td>';
		echo 	'</tr>';

	};

};

function grabBestFramesTableStudios() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrBestFrame = array();

	$query = 	"SELECT  
					os.product_code,
				    p51.item_name,
				    COUNT(os.product_code) AS 'count'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51_studios p51
							ON p51.product_code = os.product_code
				WHERE
					".$qDate."
					".$specStore."
					
						AND os.payment = 'y'					
						AND os.status IN ('dispatched', 'received', 'paid', 'complete')
						AND os.product_upgrade = 'sunnies_studios'
						AND (os.product_code LIKE '6%' OR  os.product_code LIKE 'AR%' )
						AND os.product_code <> 'M100'
						AND os.product_code <> 'S100'
						AND os.product_code <> 'F100'
				GROUP BY
					p51.item_name
				ORDER BY
					count DESC"; 

	$grabParams = array(
		'product_code',
		'item_name',
		'count'
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

			$arrBestFrame[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	// ECHO OUT ROWS FOR TABLE
	for ($i=0; $i < sizeOf($arrBestFrame); $i++) { 

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small">'.($i + 1).'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['item_name'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['product_code'].'</td>';
		echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['count'].'</td>';
		echo 	'</tr>';

	};

};

function grabBestFramesTableMerch() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrBestFrame = array();

	$query = 	"SELECT  
					os.product_code,
					os.product_upgrade,
				    p51.item_name,
				    p512.item_name,
				    COUNT(os.product_code) AS 'count'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_upgrade
						LEFT JOIN poll_51_studios p512
							ON p512.product_code = os.product_code
				WHERE
					".$qDate."
					".$specStore."
					
						AND os.payment = 'y'	
						AND os.dispatch_type!='packaging'				
						AND os.status IN ('dispatched', 'received', 'paid', 'complete')
						AND (
							os.product_code = 'M100'
								OR (
									os.product_upgrade = 'sunnies_studios'
										AND os.product_code LIKE 'HC%'
								)
						)
				GROUP BY
					p51.item_name
				ORDER BY
					count DESC"; 

	$grabParams = array(
		'product_code',
		'product_upgrade',
		'item_name',
		'item_name_studios',
		'count'
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

			$arrBestFrame[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	// ECHO OUT ROWS FOR TABLE
	for ($i=0; $i < sizeOf($arrBestFrame); $i++) { 

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small">'.($i + 1).'</td>';

		if($arrBestFrame[$i]['product_upgrade'] == 'sunnies_studios') {

			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['item_name_studios'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['product_code'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['count'].'</td>';

		}
		else {

			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['item_name'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['product_upgrade'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['count'].'</td>';

		};
		
		echo 	'</tr>';

	};

};

function grabBestFramesTableAntiRad() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrBestFrame = array();

	$query = 	"SELECT  
						os.product_code,
						os.product_upgrade,
						if(p51.item_name is NULL,
						p513.item_name,
						p51.item_name),
						if(p512.item_name is NULL,
						p513.item_name,
						p512.item_name),
						COUNT(os.product_code) AS 'count'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_upgrade
						LEFT JOIN poll_51_studios p512
							ON p512.product_code = os.product_code
							LEFT JOIN poll_51_studios p513
							ON p513.product_code = os.product_upgrade
				WHERE
					".$qDate."
					".$specStore."
					".$removeStoreIDs."
						AND os.payment = 'y'					
						AND os.status IN ('dispatched', 'received', 'paid', 'complete')
						AND os.product_upgrade = 'G100'
						AND os.dispatch_type!='packaging'	
						GROUP BY
						p51.item_name,p512.item_name,p513.item_name
				ORDER BY
					count DESC";

	$grabParams = array(
		'product_code',
		'product_upgrade',
		'item_name',
		'item_name_studios',
		'count'
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

			$arrBestFrame[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	// ECHO OUT ROWS FOR TABLE
	for ($i=0; $i < sizeOf($arrBestFrame); $i++) { 

		echo 	'<tr class="row100 body">';
		echo 		'<td nowrap class="cell100 small">'.($i + 1).'</td>';

		if($arrBestFrame[$i]['product_upgrade'] == 'sunnies_studios') {

			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['item_name_studios'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['product_code'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['count'].'</td>';

		}
		else {

			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['item_name_studios'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['product_upgrade'].'</td>';
			echo 	'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['count'].'</td>';

		};
		
		echo 	'</tr>';

	};

};


function grabReasons() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrReasons = array();

	$query = 	"SELECT  
					LOWER(os.reason) AS 'reason',
					os.lens_option,
					COUNT(os.po_number) AS 'count'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
				WHERE	
					".$qDate."
					".$specStore."
					
						AND os.payment = 'y'
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.reason IS NOT NULL
						AND os.reason <> ''
				GROUP BY
					reason
				ORDER BY
					reason ASC";

	$grabParams = array(
		'reason',
		'lens_option',
		'count'
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

			$arrReasons[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrReasons;

};

function grabFrameData() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrFrameData = array();

	$query = 	"SELECT  					
				    os.po_number,
					os.product_code,
				    p51c.code AS 'collection_code',
				    p51c.name AS 'collection_name',
				    p51cc.code AS 'group_category_code',
				    p51cc.name AS 'group_category_name',
				    p51f.code AS 'finish_code',
				    p51f.name AS 'finish_name',
				    p51gc.code AS 'general_color_code',
				    p51gc.name AS 'general_color_name',
				    p51m.code AS 'material_code',
				    p51m.name AS 'material_name',
				    p51ps.code AS 'product_seasonality_code',
				    p51ps.name AS 'product_seasonality_name',
				    p51s.code AS 'shape_code',
				    p51s.name AS 'shape_name',
				    p51size.code AS 'size_code',
				    p51size.name AS 'size_name'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_code
						LEFT JOIN poll_51_collections p51c
							ON p51c.code = p51.collection
						LEFT JOIN poll_51_correct_categories p51cc
							ON p51cc.code = p51.correct_group_category
						LEFT JOIN poll_51_finish p51f
							ON p51f.code = p51.finish
						LEFT JOIN poll_51_general_colors p51gc
							ON p51gc.code = p51.general_color
						LEFT JOIN poll_51_materials p51m
							ON p51m.code = p51.material
						LEFT JOIN poll_51_product_seasonality p51ps
							ON p51ps.code = p51.product_seasonality
						LEFT JOIN poll_51_shapes p51s
							ON p51s.code = p51.shape
						LEFT JOIN poll_51_sizes p51size
							ON p51size.code = p51.size
				WHERE
					".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.product_upgrade <> 'sunnies_studios'
						AND os.product_code <> 'M100'
						AND os.product_code <> 'S100'
						AND os.payment = 'y'"; 

	$grabParams = array(		
		'po_number',
		'product_code',
	    'collection_code',
	    'collection_name',
	    'group_category_code',
	    'group_category_name',
	    'finish_code',
	    'finish_name',
	    'general_color_code',
	    'general_color_name',
	    'material_code',
	    'material_name',
	    'product_seasonality_code',
	    'product_seasonality_name',
	    'shape_code',
	    'shape_name',
	    'size_code',
	    'size_name'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrFrameData[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrFrameData;

};

function grabPrescriptions() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrPrescriptions = array();

	$query = 	"SELECT  
					os.product_code,
					os.lens_option,

					case WHEN os.lens_code='SO001' THEN 'special_order_single_vision'
						WHEN os.lens_code='SO002' THEN 'special_order_double_vision_order'
						WHEN os.lens_code='SO003' THEN 'special_order_progressive'

					ELSE os.prescription_vision
					END AS prescription_vision,
					os.product_upgrade,
                    p51.item_name,
					os.lens_code,					
					p51.price
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.lens_code
				WHERE
					".$qDate."
					".$specStore."
					
						AND os.payment = 'y'
						AND os.lens_option IN ('with prescription', 'lens only')
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
				ORDER by 
					o.date_created
				".$limit; 

	$grabParams = array(		
		'product_code',
		'lens_option',
		'prescription_vision',	
		'product_upgrade',	
		'item_name',
		'lens_code',
		'price'
	);	

	// BIG QUERY
	$stmtBig = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	    mysqli_stmt_execute($stmtBig);
	    mysqli_stmt_close($stmtBig);

	}
	else {

	    echo mysqli_error($conn);

	}

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrPrescriptions[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrPrescriptions;

};

function grabRevenue($option_return = NULL) {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrRevenue = array();
	if(isset($_GET['date']) && $_GET['date'] == 'custom'){
		$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
		$queryB =  	"GROUP BY day ORDER BY DATE(os.payment_date)";
	}
	elseif(isset($_GET['date']) && $_GET['date'] != 'custom') {

		switch ($_GET['date']) {

			case 'yesterday':
			case 'day':
			case 'week':
			case 'month':
			case 'monthYear':
			case 'pmonth':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
				$queryB =  	"GROUP BY
								day";
				break;

			case 'year':
			case 'all-time':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%M') AS 'month',";
				$queryB = 	"GROUP BY
								month
							ORDER BY
                             	FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$queryA = 	"CONCAT(
						DATE_FORMAT(os.payment_date, '%b'), 
						' - ',  
						DATE_FORMAT(os.payment_date, '%d')
					) AS 'day',";
		$queryB =  	"GROUP BY
						day";

	}
	else {

		$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
		$queryB =  	"GROUP BY
						day";

	};	

	$query = 	"SELECT  
						".$queryA."
						CONCAT(
							DATE_FORMAT(os.payment_date, '%Y'), 
							DATE_FORMAT(os.payment_date, '%m'), 
							DATE_FORMAT(os.payment_date, '%d')
						) AS 'order_date',	
						SUM(os.price) AS 'sub_total',
						SUM(
							IF(
								DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
								os.price,
								pay.total
							)
						) AS 'total',
						COUNT(o.order_id) AS 'count'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
							LEFT JOIN payments pay
								ON pay.po_number = os.po_number
					WHERE						
						".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.dispatch_type!='packaging'
						AND os.order_id IS NOT NULL
					".$queryB;

	$grabParams = array(
		'day',
		'order_date',
		'subtotal',
		'total',
		'count'
	);	

	// BIG QUERY
	$stmtBig = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	    mysqli_stmt_execute($stmtBig);
	    mysqli_stmt_close($stmtBig);

	}
	else {

	    echo mysqli_error($conn);

	}

	// echo $query;
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

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

	return $arrRevenue;					

};

function grabRevenueStudios($option_return = NULL) {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrRevenue = array();

	if(isset($_GET['date']) && $_GET['date'] != 'custom') {

		switch ($_GET['date']) {

			case 'yesterday':
			case 'day':
			case 'week':
			case 'month':
			case 'monthYear':
			case 'pmonth':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
				$queryB =  	"GROUP BY
								day";
				break;

			case 'year':
			case 'all-time':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%M') AS 'month',";
				$queryB = 	"GROUP BY
								month
							ORDER BY
                             	FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$queryA = 	"CONCAT(
						DATE_FORMAT(os.payment_date, '%b'), 
						' - ',  
						DATE_FORMAT(os.payment_date, '%d')
					) AS 'day',";
		$queryB =  	"GROUP BY
						day";

	}
	else {

		$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
		$queryB =  	"GROUP BY
						day";

	};	

	$query = 	"SELECT  
						".$queryA."
						CONCAT(
							DATE_FORMAT(os.payment_date, '%Y'), 
							DATE_FORMAT(os.payment_date, '%m'), 
							DATE_FORMAT(os.payment_date, '%d')
						) AS 'order_date',	
						SUM(os.price) AS 'sub_total',
						SUM(
							IF(
								DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
								os.price,
								pay.total
							)
						) AS 'total',
						COUNT(o.order_id) AS 'count'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
							LEFT JOIN payments pay
								ON pay.po_number = os.po_number
					WHERE						
						".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.payment = 'y'
						AND os.product_upgrade = 'sunnies_studios'
					".$queryB;

	$grabParams = array(
		'day',
		'order_date',
		'subtotal',
		'total',
		'count'
	);	

	// BIG QUERY
	$stmtBig = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	    mysqli_stmt_execute($stmtBig);
	    mysqli_stmt_close($stmtBig);

	}
	else {

	    echo mysqli_error($conn);

	}

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

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

	return $arrRevenue;					

};

function grabRevenueMerch($option_return = NULL) {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrRevenue = array();

	if(isset($_GET['date']) && $_GET['date'] != 'custom') {

		switch ($_GET['date']) {

			case 'yesterday':
			case 'day':
			case 'week':
			case 'month':
			case 'monthYear':
			case 'pmonth':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
				$queryB =  	"GROUP BY
								day";
				break;

			case 'year':
			case 'all-time':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%M') AS 'month',";
				$queryB = 	"GROUP BY
								month
							ORDER BY
                             	FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$queryA = 	"CONCAT(
						DATE_FORMAT(os.payment_date, '%b'), 
						' - ',  
						DATE_FORMAT(os.payment_date, '%d')
					) AS 'day',";
		$queryB =  	"GROUP BY
						day";

	}
	else {

		$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
		$queryB =  	"GROUP BY
						day";

	};	

	$query = 	"SELECT  
						".$queryA."
						CONCAT(
							DATE_FORMAT(os.payment_date, '%Y'), 
							DATE_FORMAT(os.payment_date, '%m'), 
							DATE_FORMAT(os.payment_date, '%d')
						) AS 'order_date',	
						SUM(os.price) AS 'sub_total',
						SUM(
							IF(
								DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
								os.price,
								pay.total
							)
						) AS 'total',
						COUNT(o.order_id) AS 'count'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
							LEFT JOIN payments pay
								ON pay.po_number = os.po_number
					WHERE						
						".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.payment = 'y'
						AND os.dispatch_type!='packaging'
						AND os.product_code = 'M100'
					".$queryB;

	$grabParams = array(
		'day',
		'order_date',
		'subtotal',
		'total',
		'count'
	);	

	// BIG QUERY
	$stmtBig = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	    mysqli_stmt_execute($stmtBig);
	    mysqli_stmt_close($stmtBig);

	}
	else {

	    echo mysqli_error($conn);

	}

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

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

	return $arrRevenue;					

};

function grabRevenueAntiRad($option_return = NULL) {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrRevenue = array();

	if(isset($_GET['date']) && $_GET['date'] != 'custom') {

		switch ($_GET['date']) {

			case 'yesterday':
			case 'day':
			case 'week':
			case 'month':
			case 'monthYear':
			case 'pmonth':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
				$queryB =  	"GROUP BY
								day";
				break;

			case 'year':
			case 'all-time':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%M') AS 'month',";
				$queryB = 	"GROUP BY
								month
							ORDER BY
                             	FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$queryA = 	"CONCAT(
						DATE_FORMAT(os.payment_date, '%b'), 
						' - ',  
						DATE_FORMAT(os.payment_date, '%d')
					) AS 'day',";
		$queryB =  	"GROUP BY
						day";

	}
	else {

		$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
		$queryB =  	"GROUP BY
						day";

	};	

	$query = 	"SELECT  
						".$queryA."
						CONCAT(
							DATE_FORMAT(os.payment_date, '%Y'), 
							DATE_FORMAT(os.payment_date, '%m'), 
							DATE_FORMAT(os.payment_date, '%d')
						) AS 'order_date',	
						SUM(os.price) AS 'sub_total',
						SUM(
							IF(
								DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
								os.price,
								pay.total
							)
						) AS 'total',
						COUNT(o.order_id) AS 'count'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
							LEFT JOIN payments pay
								ON pay.po_number = os.po_number
					WHERE						
						".$qDate."
						".$specStore."
						
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						AND os.payment = 'y'
						AND os.dispatch_type!='packaging'
						AND os.product_upgrade = 'G100'
					".$queryB;

	$grabParams = array(
		'day',
		'order_date',
		'subtotal',
		'total',
		'count'
	);	

	// BIG QUERY
	$stmtBig = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	    mysqli_stmt_execute($stmtBig);
	    mysqli_stmt_close($stmtBig);

	}
	else {

	    echo mysqli_error($conn);

	}

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

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

	return $arrRevenue;					

};

function grabRevenueHours() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrRevenue = array();

	$query = 	"SELECT  
					DATE_FORMAT(os.payment_date, '%d') AS 'day',
					DATE_FORMAT(os.payment_date, '%H') AS 'hour',
					DATE_FORMAT(os.payment_date, '%i') AS 'minute',
					CONCAT(
						DATE_FORMAT(os.payment_date, '%Y'), 
						DATE_FORMAT(os.payment_date, '%m'), 
						DATE_FORMAT(os.payment_date, '%d'),
						DATE_FORMAT(os.payment_date, '%H'),
						DATE_FORMAT(os.payment_date, '%i'),
						DATE_FORMAT(os.payment_date, '%s')
					) AS 'order_date',	
					os.payment_date,
					os.price,
					IF(
						DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
						os.price,
						pay.total
					) AS 'total',
					o.order_id
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN payments pay
							ON pay.po_number = os.po_number
				WHERE						
					".$qDate."
					".$specStore."
					
					AND os.status IN ('complete', 'dispatched', 'paid', 'received')
					AND os.order_id IS NOT NULL				
				ORDER BY
					order_date ASC";

	$grabParams = array(
		'day',
		'hour',
		'minute',
		'order_date',
		'payment_date',
		'subtotal',
		'total',
		'order_id'
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

			$arrRevenue[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrRevenue;					

};

function grabRevenueBreakdown() {

	global $conn;
	global $arrFilterStores;
	global $revBreakdownSort;
	
	

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";
		$specStoreB = "AND sl.store_id IN (";		

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			};		
		
			$specStore .= "o.origin_branch = '".$arrFilterStores[$i]."'";
			$specStoreB .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {
				
				$specStoreB .= ",";

			}

		};

		$specStore .= ")";		
		$specStoreB .= ")";

	}
	else {

		$specStore = "";
		$specStoreB = "";

	};

	if($revBreakdownSort != "") {

		$querySort = 'q.total '.$revBreakdownSort;

	}
	else {

		$querySort = 'sl.store_id';

	};

	// Set array
	$arrRevenue = array();

	if(isset($_GET['date']) && $_GET['date'] != 'custom') {

		$query = 	"SELECT
						sl.store_id,
						sl.store_name,
						q.subtotal,
						q.total
					FROM
						stores_locations sl
							LEFT JOIN 
							(
								SELECT  									
									IF(
										LEFT(o.order_id, 3) <> o.origin_branch,
										o.origin_branch,
										LEFT(o.order_id, 3)
									) AS 'store_id_order',
									SUM(os.price) AS 'subtotal',
									SUM(
										IF(
											DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
											os.price,
											pay.total
										)
									) AS 'total'
								FROM 
									orders o
										LEFT JOIN orders_specs os
											ON os.order_id = o.order_id
										LEFT JOIN payments pay
											ON pay.po_number = os.po_number
								WHERE
									os.order_id IS NOT NULL";

		switch ($_GET['date']) {

			case 'yesterday':
				$query .= 	"		
										AND DATE_FORMAT(os.payment_date, '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL -12 Hour), '%d')
										AND DATE_FORMAT(os.payment_date, '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL -12 Hour), '%M')
										AND DATE_FORMAT(os.payment_date, '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL -12 Hour), '%Y')
										".$specStore;
				break;

			case 'day':
				$query .= 	"		
										AND DATE_FORMAT(os.payment_date, '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
										AND DATE_FORMAT(os.payment_date, '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
										AND DATE_FORMAT(os.payment_date, '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
										".$specStore;
				break;

			case 'week':
				$qGrabDateA = date("Y-m-d");
				$query .= 	"			AND YEARWEEK(DATE_FORMAT(os.payment_date, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)
										".$specStore;
				break;

			case 'month':
				$qGrabDateA = date("m");
				$qGrabDateB = date("Y");
				$query .= 	"			AND DATE_FORMAT(os.payment_date, '%m') =".$qGrabDateA."
										AND DATE_FORMAT(os.payment_date, '%Y') =".$qGrabDateB."
										".$specStore;
				break;
			case 'pmonth':
				$qGrabDateO= date("m");
				$qGrabDateA = date('m', strtotime("-1 months"));
				if($qGrabDateO=='01' || $qGrabDateO=='1'){

				$qGrabDateB = date("Y",strtotime("-1 year"));
				}else{
				$qGrabDateB = date("Y");
				}
				$query .= 	"			AND DATE_FORMAT(os.payment_date, '%m') =".$qGrabDateA."
										AND DATE_FORMAT(os.payment_date, '%Y') =".$qGrabDateB."
										".$specStore."
										".$setInternational;;
				break;
			case 'monthYear':
					
				$qGrabDateA = (strlen($_GET['data_range_month']) == 1) ? '0'.$_GET['data_range_month'] : $_GET['data_range_month'];
				$qGrabDateB = $_GET['data_range_year'];
					
					$query .= 	"	AND DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateA."
									AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateB;
				break;
			case 'year':
				$qGrabDateA = date("Y");
				$query .= 	"			AND DATE_FORMAT(os.payment_date, '%Y') =".$qGrabDateA." ".$specStore;
				break;

			case 'all-time':
				$qGrabDateA = date("Y");
				$query .= 	"			AND DATE_FORMAT(os.payment_date, '%Y') <= ".$qGrabDateA."
										".$specStore;
				break;
			
		}

		$query .= 	"					AND os.status IN ('dispatched', 'received', 'paid', 'complete')
								GROUP BY
									store_id_order
							) AS q
								ON q.store_id_order = sl.store_id
					WHERE	
						sl.store_id IS NOT NULL	
						".$specStoreB."
					ORDER BY
						".$querySort;

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
		$queryDateA = "	AND DATE_FORMAT(os.payment_date, '%Y-%m-%d') >= '".$dateStart."'";

		if(isset($_GET['data_range_end_month'])) {

			$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
			$queryDateB = " AND DATE_FORMAT(os.payment_date, '%Y-%m-%d') <= '".$dateEnd."'";

		}
		else {

			$dateEnd = "";
			$queryDateB = "";

		};

		$queryDate = $queryDateA.$queryDateB;

		$query = 	"SELECT
						sl.store_id,
						sl.store_name,
						q.subtotal,
						q.total
					FROM
						stores_locations sl
							LEFT JOIN 
							(
								SELECT 
									IF(
										LEFT(o.order_id, 3) <> o.origin_branch,
										o.origin_branch,
										LEFT(o.order_id, 3)
									) AS 'store_id_order',
									SUM(os.price) AS 'subtotal',
									SUM(
										IF(
											DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
											os.price,
											pay.total
										)
									) AS 'total'
								FROM 
									orders o
										LEFT JOIN orders_specs os
											ON os.order_id = o.order_id
										LEFT JOIN payments pay
											ON pay.po_number = os.po_number
								WHERE
									os.order_id IS NOT NULL
									".$queryDate."
									".$specStore."
									
									AND os.status IN ('dispatched', 'received', 'paid', 'complete')
								GROUP BY
									store_id_order
							) AS q
								ON q.store_id_order = sl.store_id
					WHERE
						sl.store_id IS NOT NULL
						".$specStoreB."
					ORDER BY
						".$querySort;


	}
	else {

		$query = 	"SELECT
						sl.store_id,
						sl.store_name,
						q.subtotal,
						q.total
					FROM
						stores_locations sl
							LEFT JOIN 
							(
								SELECT  
									IF(
										LEFT(o.order_id, 3) <> o.origin_branch,
										o.origin_branch,
										LEFT(o.order_id, 3)
									) AS 'store_id_order',
									SUM(os.price) AS 'subtotal',
									SUM(
										IF(
											DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
											os.price,
											pay.total
										)
									) AS 'total'
								FROM 
									orders o
										LEFT JOIN orders_specs os
											ON os.order_id = o.order_id
										LEFT JOIN stores_locations sl
											ON sl.store_id = o.store_id
										LEFT JOIN payments pay
											ON pay.po_number = os.po_number
								WHERE
									os.order_id IS NOT NULL		
										AND DATE_FORMAT(os.payment_date, '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
										AND DATE_FORMAT(os.payment_date, '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
										AND DATE_FORMAT(os.payment_date, '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
										".$specStore."
										
										AND os.status IN ('dispatched', 'received', 'paid', 'complete')
								GROUP BY
									store_id_order
							) AS q
								ON q.store_id_order = sl.store_id
					WHERE	
						sl.store_id IS NOT NULL	
						".$specStoreB."
					ORDER BY
						".$querySort;

	};

	$grabParams = array(
		'store_id',
		'store_name',
		'subtotal',
		'total'
	);

	// echo $query;

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

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
	echo '<div class="row">
			<div class="col-7 col-md-3 mb-2">
				<div class="row">
					<p class="col-1 text-right"></p>
					<p class="col-7 col-lg-9 text-center">Store</p>
					<p class="col-1 text-right">Staff</p>
				</div>
			</div>
			<div class="col-5 col-md-9 mb-2">
				<div class="row">
					<p class="col-12 text-left margin-revenue">Revenue</p>
				</div>
			</div>
		</div>
		';
	for ($i=0; $i < sizeOf($arrRevenue); $i++) {

		$empCount = grabEmployeesCount($arrRevenue[$i]['store_id']);

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
		echo 				'<a class="col-7 col-lg-9" style="text-decoration: underline !important;" target="_BLANK" href="/dashboard/philippines/?filterStores%5B%5D='.$curStoreID.'&'.$date_filter.'">';
		echo 					'<p class="text-right">'.$curStoreName.'</p>';
		echo 				'</a>';
		echo 				'<p class="col-1 text-right mr-1">'.$empCount.'</p>';
		echo 			'</div>';
		echo 		'</div>';
		echo 		'<div class="col-5 col-md-9">';
		echo 			'<div class="row flex-column flex-md-row no-gutters align-items-start align-items-md-center pl-3 pr-3" style="border-left: 1px solid #000000;">';
		echo 				'<div class="col-12 col-lg-2 store-total">';
		echo 					'<p class="col-12 text-left p-0 pl-md-3 pr-md-3 pt-2 pb-2">₱'.( number_format($arrRevenue[$i]['total'], 2, '.', ',') ).'</p>';
		echo 				'</div>';
		echo 				'<div class="col-12 col-lg-9 store-total-bar">';
		echo 					'<div class="data-bar" id="dataBar'.$curStoreID.'" data-store-total="'.$curStoreTotal.'"></div>';
		echo 				'</div>';
		echo 			'</div>';
		echo 		'</div>';
		echo 	'</div>';

	};	

	// ECHO HIGHEST TOTAL
	echo 	'<div id="revenue-breakdown-highest" data-store-id="'.$curGrandTotalID.'" data-total="'.$curGrandTotal.'"></div>';

};

function grabEmployeesCount($storeCode) {

	global $conn;
	global $dlDate;

	// Set array
	$arrTotal = array();

		$query = 	"SELECT
						COUNT(dl.emp_id) AS total
					FROM
							daily_login dl
								WHERE
								dl.store_code = '".$storeCode."'
								 ".$dlDate."
								 GROUP BY dl.daily_date;";
		// echo $query; exit;
	$grabParams = array(
		'total'
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

			$arrTotal[] = $tempArray['total'];

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	$count = count($arrTotal);
	return ($count > 0) ? round(array_sum($arrTotal)/$count) : 0;
};

function grabCustomers() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrCustomerInfo = array();

	$query = 	"SELECT
					o.order_id,
					o.profile_id,
					pi.date_created,
					pi.date_updated,
					pi.first_name,
					pi.middle_name,
					pi.last_name,
					pi.email_address,
					pi.phone_number,
					pi.gender,
					pi.birthday,
					pi.email_updates,
					pi.country,
					pi.province,
					pi.city,
					pi.barangay,
					pi.age,
					pi.branch_applied,
					pi.joining_date,
					pi.sales_person,
					pi.address,
					pi.priority
				FROM
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN profiles_info pi
							ON pi.profile_id = os.profile_id
				WHERE
					".$qDate."
					".$specStore."
					
					AND os.status != 'cancelled'"; 

	$grabParams = array(
		'order_id',
		'profile_id',
		'date_created',
		'date_updated',
		'first_name',
		'middle_name',
		'last_name',
		'email_address',
		'phone_number',
		'gender',
		'birthday',
		'email_updates',
		'country',
		'province',
		'city',
		'barangay',
		'age',
		'branch_applied',
		'joining_date',
		'sales_person',
		'address',
		'priority'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrCustomerInfo[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrCustomerInfo;

};

function grabCustomersAges() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrCustomerInfo = array();

	// $query = 	"SELECT
	// 				pi.profile_id,
	// 				pi.birthday,
	// 				pi.age AS 'age_init',
	// 				DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0 AS 'age_check',
	// 				IF(
	// 					pi.age >= DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0,
	// 					pi.age,
	// 					DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0
	// 				) AS 'age_final',
	// 				pi.gender,
	// 				REPLACE(pi.city,'-city',''),
	// 				pi.country
	// 			FROM
	// 				orders o
	// 					LEFT JOIN orders_specs os	
	// 						ON os.order_id = o.order_id
	// 					LEFT JOIN profiles_info pi
	// 						ON pi.profile_id = os.profile_id
	// 			WHERE
	// 				".$qDate."
	// 				".$specStore."
					
	// 				AND os.status IN ('complete', 'dispatched', 'paid', 'received')
	// 				AND os.order_id IS NOT NULL
	// 				AND pi.birthday IS NOT NULL
	// 				AND pi.birthday <> ''
	// 			GROUP BY
	// 				pi.profile_id
	// 			ORDER BY
	// 				age_final ASC";

	$query = 	"SELECT
					pi.profile_id,
					pi.birthday,
					pi.age AS 'age_init',
					DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0 AS 'age_check',
					IF(
						pi.age >= DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0,
						pi.age,
						DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0
					) AS 'age_final',
					pi.gender,
					REPLACE(pi.city,'-city',''),
					pi.country,
					CASE when pi.email_address 
							like 'specsguest@sunniesspecsoptical.com%'  
								then 'guest'
					    when pi.email_address NOT LIKE 'specsguest@sunniesspecsoptical.com%' AND q.order_id <> os.order_id then 'recurring'
						when 
						pi.email_address 
						NOT  LIKE 'specsguest@sunniesspecsoptical.com%' AND q.order_id = os.order_id  then 'new'
						ELSE 'new'
					END
				FROM
					orders o
						LEFT JOIN orders_specs os	
							ON os.order_id = o.order_id
						LEFT JOIN profiles_info pi
							ON pi.profile_id = os.profile_id
						LEFT JOIN (
							SELECT
								os.profile_id,
								os.order_id
							FROM
								orders_specs os
							WHERE
								os.status IN ('complete', 'dispatched', 'paid', 'received')
							ORDER BY
								os.date_created ASC
						) AS q
							ON q.profile_id = pi.profile_id

				WHERE
					".$qDate."
					".$specStore."
					AND os.order_id IS NOT NULL
					AND pi.birthday IS NOT NULL
					AND pi.birthday <> ''
					AND dispatch_type!='packaging'
					AND os.orders_specs_id != ''
					AND pi.profile_id != ''
				GROUP BY
					pi.profile_id
				ORDER BY
					age_final ASC";

	$grabParams = array(		
		'profile_id',
		'birthday',
		'age_init',
		'age_check',
		'age_final',
		'gender',
		'city',
		'country',
		'customer_type'
	);

	// BIG QUERY
	$stmtBig = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {

	    mysqli_stmt_execute($stmtBig);
	    mysqli_stmt_close($stmtBig);

	}
	else {

	    echo mysqli_error($conn);

	}

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrCustomerInfo[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrCustomerInfo;	

};

function grabCustomersGenders() {

	global $conn;
	global $qDate;
	global $arrFilterStores;
	

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
	$arrCustomerInfo = array();

	$query = 	"SELECT
					pi.gender,
					COUNT(pi.gender)
				FROM
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN profiles_info pi
							ON pi.profile_id = os.profile_id
				WHERE
					".$qDate."
					".$specStore."
					
					AND os.status != 'cancelled'
					AND pi.gender IS NOT NULL
					AND pi.gender != ''
				GROUP BY
					pi.gender ASC"; 

	$grabParams = array(		
		'gender',
		'count'
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

			$arrCustomerInfo[$result1] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrCustomerInfo;

};

function grabStores() {

	global $conn;
	global $arrSessionStores;

	// Set WHERE query for stores
	$specStore = " sl.store_id IN (";

	for ($i=0; $i < sizeOf($arrSessionStores); $i++) { 
	
		$specStore .= "'".$arrSessionStores[$i]."'";

		if($i < sizeOf($arrSessionStores) - 1) {

			$specStore .= ",";

		}

	};

	$specStore .= " ) ";		

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
					".$specStore."
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
	$arrLabs = array();;

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

function grabTransactionCount(){
	global $conn;
	global $qDate;
	global $arrFilterStores;
	global $removeStoreIDs;
	global $customMonth;

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

	if((isset($_GET['date']) && $_GET['date'] != 'custom') || $customMonth) {

		switch ($_GET['date']) {

			case 'yesterday':
			case 'day':
			case 'week':
			case 'month':
			case 'pmonth':
			case 'monthYear':
			case 'custom':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
				$queryB =  	"GROUP BY
								day";
				break;

			case 'year':
			case 'all-time':
				$queryA = 	"DATE_FORMAT(os.payment_date, '%M') AS 'month',";
				$queryB = 	"GROUP BY
								month
							ORDER BY
                             	FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$queryA = 	"CONCAT(
						DATE_FORMAT(os.payment_date, '%b'), 
						' - ',  
						DATE_FORMAT(os.payment_date, '%d')
					) AS 'day',";
		$queryB =  	"GROUP BY
						day";

	}
	else {

		$queryA = 	"DATE_FORMAT(os.payment_date, '%d') AS 'day',";
		$queryB =  	"GROUP BY
						day";

	};	


	$query=" SELECT count(o.order_id)
			FROM 
			orders o
				LEFT JOIN orders_specs os
					ON os.order_id = o.order_id
				LEFT JOIN payments pay
					ON pay.po_number = os.po_number
		WHERE						
			".$qDate."
			".$specStore."
			AND os.status IN ('complete', 'dispatched', 'paid', 'received')
			AND os.order_id IS NOT NULL
			AND os.dispatch_type!='packaging'	
			GROUP BY o.order_id;
		";
	$grabParams = array(
		"count"
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

			$arrCount[] = $tempArray;
		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};
	echo count($arrCount);	
}

//////////////////////////////////////////////////////////////////////////////////// FIRE FUNCTIONS ON GET

if($_GET['function']) {

	switch ($_GET['function']) {

		case 'grabFrames':
			grabFrames();
			break;

		case 'grabBestFrames':
			grabBestFrames();
			break;

		case 'grabBestFramesTable':
			grabBestFramesTable();
			break;

		case 'grabBestFramesTableStudios':
			grabBestFramesTableStudios();
			break;

		case 'grabBestFramesTableMerch':
			grabBestFramesTableMerch();
			break;

		case 'grabBestFramesTableAntiRad':
			grabBestFramesTableAntiRad();
			break;

		case 'grabRevenue':
			grabRevenue();
			break;

		case 'grabRevenueBreakdown':
			grabRevenueBreakdown();
			break;

		case 'countOrders':
			grabFcountOrdersrames();
			break;

		case 'grabCustomers':
			grabCustomers();
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
		case 'grabTransactionCount':
			grabTransactionCount();
			break;
		
	};

};

?>