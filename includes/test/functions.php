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
			break;
			
		case 'day':
			$qGrabDateA = date("d");
			$qGrabDateB = date("m");
			$qGrabDateC = date("Y");
			$qDate = 	"DATE_FORMAT(os.payment_date, '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateC;
			break;

		case 'week':
			$qGrabDateA = date("Y-m-d");
			$qDate = 	"YEARWEEK(DATE_FORMAT(os.payment_date, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
			break;

		case 'month':
			$qGrabDateA = date("m");
			$qGrabDateB = date("Y");
			$qDate = 	"DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateB;
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

	if(isset($_GET['data_range_end_month'])) {

		// Set end date
		$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );

		$qDateB = " AND DATE_FORMAT(os.payment_date, '%Y-%m-%d') <= '".$dateEnd."'";

	}
	else {

		$dateEnd = "";
		$qDateB = "";

	};

	$qDate = $qDateA.$qDateB;

}
else {

	$qGrabDateA = date("d");
	$qGrabDateB = date("m");
	$qGrabDateC = date("Y");
	$qDate = 	"DATE_FORMAT(os.payment_date, '%d') = ".$qGrabDateA." 
					AND DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateB."
					AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateC;

};


// Set stores if specified
if(isset($_GET['filterStores'])) {

	// Set stores array
	$arrFilterStores = $_GET['filterStores'];

}
else {

	$arrFilterStores = array();

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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

function grabBestFrames() {

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
				    	case when os.lens_option = 'with prescription' then 1 end
				    ) AS 'count_with_prescription',
				    COUNT(
				    	case when os.lens_option = 'without prescription' then 1 end
				    ) AS 'count_without_prescription',
				    COUNT(
				    	case when os.lens_option = 'lens only' then 1 end
				    ) AS 'count_lens_only',
				    COUNT(
				    	case when os.prescription_vision = 'single_vision_stock' then 1 end
				    ) AS 'count_single_vision_stock',
				    COUNT(
				    	case when os.prescription_vision = 'single_vision_rx' then 1 end
				    ) AS 'count_single_vision_rx',
				    COUNT(
				    	case when os.prescription_vision = 'double_vision_stock' then 1 end
				    ) AS 'count_double_vision_stock',
				    COUNT(
				    	case when os.prescription_vision = 'double_vision_rx' then 1 end
				    ) AS 'count_double_vision_rx',
				    COUNT(
				    	case when os.prescription_vision = 'progressive_stock' then 1 end
				    ) AS 'count_progressive_stock',
				    COUNT(
				    	case when os.prescription_vision = 'progressive_rx' then 1 end
				    ) AS 'count_progressive_rx',
				    COUNT(
				    	case when os.prescription_vision = '' then 1 end
				    ) AS 'count_none'
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
		'count_none'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23);

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
	
		if($arrBestFrame[$i]['product_code'] != 'F100' && $arrBestFrame[$i]['product_code'] != 'M100'){

			echo 	'<tr class="row100 body">';
			echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['item_name'].'</td>';
			echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['product_code'].'</td>';
			echo 		'<td nowrap class="cell100 small">'.$arrBestFrame[$i]['count'].'</td>';
			echo 	'</tr>';

		};

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
					os.prescription_vision,
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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
					SUM(
						IF(
							DATE_FORMAT(os.payment_date, '%Y-%m-%d') < '2019-09-24',
							os.price,
							pay.total
						)
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

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND (";
		$specStoreB = "AND sl.store_id IN (";		

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

			if($i > 0) {

				$specStore .= "OR ";

			};		
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";
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
									LEFT(o.order_id, 3) AS 'store_id_order',
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
										AND DATE_FORMAT(os.payment_date, '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
										AND DATE_FORMAT(os.payment_date, '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
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
						sl.store_id NOT IN ('1000', '900')
						".$specStoreB."
					ORDER BY
						sl.store_id";

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
									LEFT(o.order_id, 3) AS 'store_id_order',
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
						sl.store_id NOT IN ('1000', '900')
						".$specStoreB."
					ORDER BY
						sl.store_id";


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
									LEFT(o.order_id, 3) AS 'store_id_order',
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
						sl.store_id NOT IN ('1000', '900')
					ORDER BY
						sl.store_id";

	};	

	$grabParams = array(
		'store_id',
		'store_name',
		'subtotal',
		'total'
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
		echo 		'<div class="col-5 col-md-3 store-name">';
		echo 			'<p class="col-12 pl-0 text-right">'.$curStoreName.'</p>';
		echo 		'</div>';
		echo 		'<div class="col-7 col-md-9">';
		echo 			'<div class="row flex-column flex-md-row no-gutters align-items-start align-items-md-center pl-3 pr-3" style="border-left: 1px solid #000000;">';
		echo 				'<div class="col-3 col-lg-2 store-total">';
		echo 					'<p class="col-12 text-left p-0 pl-md-3 pr-md-3 pt-2 pb-2">₱'.( number_format($arrRevenue[$i]['total'], 2, '.', ',') ).'</p>';
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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrCustomerInfo = array();

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
					pi.city,
					pi.country
				FROM
					orders o
						LEFT JOIN orders_specs os	
							ON os.order_id = o.order_id
						LEFT JOIN profiles_info pi
							ON pi.profile_id = os.profile_id
				WHERE
					".$qDate."
					".$specStore."
					AND os.status IN ('complete', 'dispatched', 'paid', 'received')
					AND os.order_id IS NOT NULL
					AND pi.birthday IS NOT NULL
					AND pi.birthday <> ''
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
		'country'
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
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

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
		
			$specStore .= "o.order_id LIKE '".$arrFilterStores[$i]."-%'";

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
		
	};

};

?>