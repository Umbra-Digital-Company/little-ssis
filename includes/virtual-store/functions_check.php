<?php

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
			$qDate = 	"DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateC;
			break;

		case 'day':
			$qGrabDateA = date("d");
			$qGrabDateB = date("m");
			$qGrabDateC = date("Y");
			$qDate = 	"DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateC;
			break;

		case 'week':
			$qGrabDateA = date("Y-m-d");
			$qDate = 	"YEARWEEK(DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
			break;

		case 'month':
			$qGrabDateA = date("m");
			$qGrabDateB = date("Y");
			$qDate = 	"DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateB;
			break;
		
		case 'year':
			$qGrabDate = date("Y");
			$qDate = "DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDate;
			break;

		case 'all-time':
			$qGrabDate = date("Y");
			$qDate = 	"DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= ".$qGrabDate;
			break;

	}	

}
elseif(isset($_GET['data_range_start_month'])) {

	$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
	$qDateA = "DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') >= '".$dateStart."'";

	if(isset($_GET['data_range_end_month'])) {

		$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
		$qDateB = " AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') <= '".$dateEnd."'";

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
	$qDate = 	"DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = ".$qGrabDateA." 
					AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateB."
					AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateC;

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
		$specStore = "AND o.store_id IN (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
			$specStore .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {

				$specStore .= ",";

			}

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
					os.lens_code,
					os.prescription_vision,
					os.price,
					os.prescription_vision,
					os.lens_option,
					os.status,
					os.payment,
					os.target_date,
					os.store_dispatch_date,
				    IF(
				        os.store_dispatch_date < os.target_date,
				        'y',
				        'n'
				     ) AS 'on_time'
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
						AND os.status != 'cancelled'
				ORDER by 
					os.date_created
				".$limit; 

	$grabParams = array(
		'date_created',
		'date_updated',
		'order_po_id',
		'product_code',
		'style',
		'color',
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
		$specStore = "AND o.store_id IN (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
			$specStore .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {

				$specStore .= ",";

			}

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
						AND os.status != 'cancelled'
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

	return $arrBestFrame;

};

function grabRevenue() {

	global $conn;
	global $arrFilterStores;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND o.store_id IN (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
			$specStore .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {

				$specStore .= ",";

			}

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
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',	
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',
								SUM(os.price) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL -12 Hour), '%d')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
									".$specStore."
									AND os.status != 'cancelled'
							GROUP BY
								day";
				break;

			case 'day':
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',	
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',
								SUM(os.price) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
									".$specStore."
									AND os.status != 'cancelled'
							GROUP BY
								day";
				break;

			case 'week':
				$qGrabDateA = date("Y-m-d");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',	
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',
								SUM(os.price) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND YEARWEEK(DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)
									".$specStore."
									AND os.status != 'cancelled'
							GROUP BY
								day";
				break;

			case 'month':
				$qGrabDateA = date("m");
				$qGrabDateB = date("Y");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',	
								SUM(os.price) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') =".$qGrabDateA."
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') =".$qGrabDateB."
									".$specStore."
									AND os.status != 'cancelled'
							GROUP BY
								day";
				break;

			case 'year':
				$qGrabDateA = date("Y");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') AS 'month',
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',
								SUM(os.price) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') =".$qGrabDateA."
									".$specStore."
									AND os.status != 'cancelled'
							GROUP BY
								month
							ORDER BY
                                    FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;

			case 'all-time':
				$qGrabDateA = date("Y");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') AS 'month',	
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',
								SUM(os.price) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= ".$qGrabDateA."
									".$specStore."
									AND os.status != 'cancelled'
							GROUP BY
								month
							ORDER BY
                                    FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );	
		$queryDateA = "	AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') >= '".$dateStart."'";

		if(isset($_GET['data_range_end_month'])) {

			$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
			$queryDateB = " AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') <= '".$dateEnd."'";

		}
		else {

			$dateEnd = "";
			$queryDateB = "";

		};

		$queryDate = $queryDateA.$queryDateB;

		$query = 	"SELECT  
						CONCAT(
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%b'), 
							' - ',  
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
						) AS 'day',
						CONCAT(
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
						) AS 'order_date',
						SUM(os.price) AS 'total'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
					WHERE
						os.order_id IS NOT NULL
						".$queryDate."
						".$specStore."
						AND os.status != 'cancelled'
					GROUP BY
						day
					ORDER BY                        
                        order_date ASC";

	}
	else {

		$query = 	"SELECT  
						DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',	
						CONCAT(
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
						) AS 'order_date',
						SUM(os.price) AS 'total'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
					WHERE
						os.order_id IS NOT NULL
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
							".$specStore."
							AND os.status != 'cancelled'
					GROUP BY
						day";

	};	

	$grabParams = array(
		'day',
		'order_date',
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

	return $arrRevenue;

};

function grabRevenueBreakdown() {

	global $conn;
	global $arrFilterStores;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND o.store_id IN (";
		$specStoreB = "AND sl.store_id IN (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
			$specStore .= "'".$arrFilterStores[$i]."'";
			$specStoreB .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {

				$specStore .= ",";
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
						q.total
					FROM
						stores_locations sl
							LEFT JOIN 
							(
								SELECT  
									o.store_id,
									SUM(os.price) AS 'total'
								FROM 
									orders o
										LEFT JOIN orders_specs os
											ON os.order_id = o.order_id
								WHERE
									os.order_id IS NOT NULL";

		switch ($_GET['date']) {

			case 'yesterday':
				$query .= 	"		
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL -12 Hour), '%d')
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
										".$specStore;
				break;

			case 'day':
				$query .= 	"		
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
										".$specStore;
				break;

			case 'week':
				$qGrabDateA = date("Y-m-d");
				$query .= 	"			AND YEARWEEK(DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)
										".$specStore;
				break;

			case 'month':
				$qGrabDateA = date("m");
				$qGrabDateB = date("Y");
				$query .= 	"			AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') =".$qGrabDateA."
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') =".$qGrabDateB."
										".$specStore;
				break;

			case 'year':
				$qGrabDateA = date("Y");
				$query .= 	"			AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') =".$qGrabDateA." ".$specStore;
				break;

			case 'all-time':
				$qGrabDateA = date("Y");
				$query .= 	"			AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= ".$qGrabDateA."
										".$specStore;
				break;
			
		}

		$query .= 	"					AND os.status != 'cancelled'
								GROUP BY
									o.store_id
							) AS q
								ON q.store_id = sl.store_id
					WHERE
						sl.store_id <> '1000'
						".$specStoreB."
					ORDER BY
						sl.store_id";

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
		$queryDateA = "	AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') >= '".$dateStart."'";

		if(isset($_GET['data_range_end_month'])) {

			$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
			$queryDateB = " AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') <= '".$dateEnd."'";

		}
		else {

			$dateEnd = "";
			$queryDateB = "";

		};

		$queryDate = $queryDateA.$queryDateB;

		$query = 	"SELECT
						sl.store_id,
						sl.store_name,
						q.total
					FROM
						stores_locations sl
							LEFT JOIN 
							(
								SELECT 
									o.store_id, 
									SUM(os.price) AS 'total'
								FROM 
									orders o
										LEFT JOIN orders_specs os
											ON os.order_id = o.order_id
								WHERE
									os.order_id IS NOT NULL
									".$queryDate."
									".$specStore."
									AND os.status != 'cancelled'
								GROUP BY
									o.store_id
							) AS q
								ON q.store_id = sl.store_id
					WHERE
						sl.store_id <> '1000'
						".$specStoreB."
					ORDER BY
						sl.store_id";


	}
	else {

		$query = 	"SELECT
						sl.store_id,
						sl.store_name,
						q.total
					FROM
						stores_locations sl
							LEFT JOIN 
							(
								SELECT  
									o.store_id,
									SUM(os.price) AS 'total'
								FROM 
									orders o
										LEFT JOIN orders_specs os
											ON os.order_id = o.order_id
										LEFT JOIN stores_locations sl
											ON sl.store_id = o.store_id
								WHERE
									os.order_id IS NOT NULL		
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
										AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
										".$specStore."
										AND os.status != 'cancelled'
								GROUP BY
									o.store_id
							) AS q
								ON q.store_id = sl.store_id
					WHERE
						sl.store_id <> '1000'
					ORDER BY
						sl.store_id";

	};	

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

	return $arrRevenue;

};

function countOrders() {

	global $conn;
	global $arrFilterStores;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND o.store_id IN (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
			$specStore .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {

				$specStore .= ",";

			}

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrCountOrders = array();


	if(isset($_GET['date']) && $_GET['date'] != 'custom') {

		switch ($_GET['date']) {

			case 'yesterday':
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',	
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',
								COUNT(os.order_id) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL -12 Hour), '%d')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
									".$specStore."
							GROUP BY
								day";
				break;

			case 'day':
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',	
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',
								COUNT(os.order_id) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
									".$specStore."
							GROUP BY
								day";
				break;

			case 'week':
				$qGrabDateA = date("Y-m-d");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',	
								COUNT(os.order_id) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND YEARWEEK(DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)
									".$specStore."
							GROUP BY
								day";
				break;

			case 'month':
				$qGrabDateA = date("m");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',	
								COUNT(os.order_id) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') =".$qGrabDateA."
									".$specStore."
							GROUP BY
								day";
				break;

			case 'year':
				$qGrabDateA = date("Y");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') AS 'month',
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',	
								COUNT(os.order_id) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') =".$qGrabDateA."
									".$specStore."
							GROUP BY
								month
							ORDER BY
                                    FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;

			case 'all-time':
				$qGrabDateA = date("Y");
				$query = 	"SELECT  
								DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') AS 'month',
								CONCAT(
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
									DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
								) AS 'order_date',	
								COUNT(os.order_id) AS 'total'
							FROM 
								orders o
									LEFT JOIN orders_specs os
										ON os.order_id = o.order_id
							WHERE
								os.order_id IS NOT NULL
									AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= ".$qGrabDateA."
									".$specStore."
							GROUP BY
								month
							ORDER BY
                                    FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['data_range_start_month'])) {

		$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
		$queryDateA = "	AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') >= '".$dateStart."'";

		if(isset($_GET['data_range_end_month'])) {

			$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
			$queryDateB = " AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') <= '".$dateEnd."'";

		}
		else {

			$dateEnd = "";
			$queryDateB = "";

		};

		$queryDate = $queryDateA.$queryDateB;

		$query = 	"SELECT  
						CONCAT(
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%b'), 
							' - ',  
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
						) AS 'day',
						CONCAT(
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
						) AS 'order_date',
						COUNT(os.order_id) AS 'total'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
					WHERE
						os.order_id IS NOT NULL
						".$queryDate."
						".$specStore."
					GROUP BY
						day
					ORDER BY                        
                        order_date ASC";

	}
	else {

		$query = 	"SELECT  
						DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') AS 'day',	
						CONCAT(
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m'), 
							DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
						) AS 'order_date',
						COUNT(os.order_id) AS 'total'
					FROM 
						orders o
							LEFT JOIN orders_specs os
								ON os.order_id = o.order_id
					WHERE
						os.order_id IS NOT NULL
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d')
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
							".$specStore."
					GROUP BY
						day";

	};	

	$grabParams = array(
		'day',
		'order_date',
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

			$arrCountOrders[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrCountOrders;

};

function grabCustomers() {

	global $conn;
	global $qDate;
	global $arrFilterStores;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND o.store_id IN (";

		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
			$specStore .= "'".$arrFilterStores[$i]."'";

			if($i < sizeOf($arrFilterStores) - 1) {

				$specStore .= ",";

			}

		};

		$specStore .= ")";		

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrCustomerInfo = array();

	$query = 	"SELECT
					os.order_id,
					os.profile_id,
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

//////////////////////////////////////////////////////////////////////////////////// ORDER LIST FUNCTIONS

function cvdate2($d){

	$returner = '';
	$datae=date_parse($d); 
	$returner .= getMonth2($datae['month'])." ".$datae['day'].", ".$datae['year'];
	$suffix = "AM";
	$hour = $datae['hour'];

	if ($datae['hour']>'12') {

		$hour = $datae['hour']-12;

	};

	if ($datae['hour']>'11' && $datae['hour']<'24') {

		$suffix = "PM";

	};

	$returner .= " - ".AddZero2($hour).":".AddZero2($datae['minute'])." ".$suffix;	

	return $returner;

};

function getMonth2($mid){

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
		
	};

};

function AddZero2($num){

	if (strlen($num)=='1') {

		return "0".$num;

	} 
	else {

		return $num;

	};

};
	
function CheckMessage($order_id_m){
	
	global $conn;
	
	$arrMessage2 = array();

	$query333 = "SELECT  
					rc.date_created,
					rc.date_updated,
					rc.order_po_id,
					rc.profile_id,
					rc.message,
					rc.message_id,
					u.first_name,
					u.last_name
				FROM 
					remarks_comm rc
						LEFT JOIN users u 
							ON u.id = rc.profile_id
				WHERE
					rc.order_po_id='".$order_id_m."'					
				ORDER by 
					rc.date_created DESC 
				Limit 1"; 

	$grabParams = array(
		'date_created',
		'date_updated',
		'order_po_id',
		'profile_id',
		'message',
		'message_id',
		'first_name',
		'last_name'
	);

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query333)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < 8; $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrMessage2[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	};
					
	if($arrMessage2){

		if($arrMessage2[0]["profile_id"]!=''){

			$order_specs_id_m= $arrMessage2[0]["profile_id"];

		}
		else {

			$order_specs_id_m ="";

		};
				
	}
	else{

		$order_specs_id_m ="";

	};

	return $order_specs_id_m;

};

//////////////////////////////////////////////////////////////////////////////////// DISPATCH FUNCTIONS

function tabDetails( $label, $arrow ) {

	// get sort link
	$sort_link = '';

	if ( isset($_GET['sort']) && (!isset($_GET['sort2']) )) {

		( $_GET['sort'] == $label ) ? $sort_link = '&sort2='.$label.'2' : '';

	};

	switch ( $arrow ) {

		case 'true' :
			// toggle arrow style
			if ( isset($_GET['sort'] ) || ( isset($_GET['sort2']) ) ) {

				if ($_GET['sort'] == $label && ( !isset($_GET['sort2']) ) ) {
					$arrow_style = '<i class="zmdi zmdi-caret-down-circle text-success"></i>';
				}
				elseif ( $_GET['sort'] == $label && $_GET['sort2'] == $label . '2' ) {
					$arrow_style = '<i class="zmdi zmdi-caret-down-circle text-danger"></i>';
				}
				else {
					$arrow_style = '<i class="zmdi zmdi-caret-down-circle"></i>';
				}
			
			} else {

				$arrow_style = '<i class="zmdi zmdi-caret-down-circle"></i>';

			}
			break;

		case 'false' :
			// toggle arrow style
			$arrow_style = '';
			break;

	};

	echo '<a href="./?page=dispatch&sort='.$label . $sort_link .'">';
	echo 	$label;
	echo 	'<span class="sort-arrow">';
	echo 		$arrow_style;
	echo 	'</span>';
	echo '</a>';

};

?>