<?php

//////////////////////////////////////////////////////////////////////////////////// FINANCE FUNCTIONS

function grabRevenueMonths() {

	global $conn;
	global $qStore;

	// Set Store ID if specified
	if($qStore != "") {

		$specStore = "AND o.store_id = '".$qStore."'";

	}
	else {

		$specStore = "";

	};

	// Set array
	$arrRevenue = array();
	
	$query = 	"SELECT  
					DATE_FORMAT(os.payment_date, '%Y') AS 'year',	
					DATE_FORMAT(os.payment_date, '%m') AS 'month',	
					SUM(os.price) AS 'total'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
				WHERE
					os.order_id IS NOT NULL
						AND os.payment = 'y'					
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')
						".$specStore."
						AND DATE_FORMAT(os.payment_date, '%Y') = '2019'
				GROUP BY
					month";

	$grabParams = array(
		'year',
		'month',
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

// function grabCustomers() {

// 	global $conn;
// 	global $qDate;
// 	global $qStore;

// 	// Set Store ID if specified
// 	if($qStore != "") {

// 		$specStore = "AND o.store_id = '".$qStore."'";

// 	}
// 	else {

// 		$specStore = "";

// 	};

// 	// Set array
// 	$arrCustomerInfo = array();

// 	$query = 	"SELECT
// 					os.order_id,
// 					os.profile_id,
// 					pi.date_created,
// 					pi.date_updated,
// 					pi.first_name,
// 					pi.middle_name,
// 					pi.last_name,
// 					pi.email_address,
// 					pi.phone_number,
// 					pi.gender,
// 					pi.birthday,
// 					pi.email_updates,
// 					pi.country,
// 					pi.province,
// 					pi.city,
// 					pi.barangay,
// 					pi.age,
// 					pi.branch_applied,
// 					pi.joining_date,
// 					pi.sales_person,
// 					pi.address,
// 					pi.priority
// 				FROM
// 					orders o
// 						LEFT JOIN orders_specs os
// 							ON os.order_id = o.order_id
// 						LEFT JOIN profiles_info pi
// 							ON pi.profile_id = os.profile_id
// 				WHERE
// 					".$qDate."
// 					".$specStore; 

// 	$grabParams = array(
// 		'order_id',
// 		'profile_id',
// 		'date_created',
// 		'date_updated',
// 		'first_name',
// 		'middle_name',
// 		'last_name',
// 		'email_address',
// 		'phone_number',
// 		'gender',
// 		'birthday',
// 		'email_updates',
// 		'country',
// 		'province',
// 		'city',
// 		'barangay',
// 		'age',
// 		'branch_applied',
// 		'joining_date',
// 		'sales_person',
// 		'address',
// 		'priority'
// 	);

// 	$stmt = mysqli_stmt_init($conn);
// 	if (mysqli_stmt_prepare($stmt, $query)) {

// 		mysqli_stmt_execute($stmt);
// 		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22);

// 		while (mysqli_stmt_fetch($stmt)) {

// 			$tempArray = array();

// 			for ($i=0; $i < sizeOf($grabParams); $i++) { 

// 				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

// 			};

// 			$arrCustomerInfo[] = $tempArray;

// 		};

// 		mysqli_stmt_close($stmt);    

// 	}
// 	else {

// 		showMe(mysqli_error($conn));

// 	};

// 	return $arrCustomerInfo;

// };

// function grabStores() {

// 	global $conn;
// 	global $qStore;

// 	// Set array
// 	$arrStores = array();

// 	$query = 	"SELECT
// 					sl.id,
// 					sl.date_created,
// 					sl.date_updated,
// 					sl.store_id,
// 					sl.lab_id,
// 					sl.zone,
// 					sl.store_name,
// 					sl.address,
// 					sl.province,
// 					sl.city,
// 					sl.barangay,
// 					sl.phone_number,
// 					sl.email_address,
// 					sl.active
// 				FROM
// 					stores_locations sl					
// 				ORDER BY
// 					sl.store_name ASC";

// 	$grabParams = array(
// 		"id",
// 		"date_created",
// 		"date_updated",
// 		"store_id",
// 		"lab_id",
// 		"zone",
// 		"store_name",
// 		"address",
// 		"province",
// 		"city",
// 		"barangay",
// 		"phone_number",
// 		"email_address",
// 		"active"		
// 	);

// 	$stmt = mysqli_stmt_init($conn);
// 	if (mysqli_stmt_prepare($stmt, $query)) {

// 		mysqli_stmt_execute($stmt);
// 		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14);

// 		while (mysqli_stmt_fetch($stmt)) {

// 			$tempArray = array();

// 			for ($i=0; $i < sizeOf($grabParams); $i++) { 

// 				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

// 			};

// 			$arrStores[] = $tempArray;

// 		};

// 		mysqli_stmt_close($stmt);    

// 	}
// 	else {

// 		showMe(mysqli_error($conn));

// 	};

// 	return $arrStores;

// };

function grabTopMetrics($search_previous_year, $search_current_year, $search_month) {

	global $conn;	

	// Set array
	$arrTopMetrics = array();
	
	$query = 	"SELECT  
					DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') AS 'year',	
					DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') AS 'month',					
					o.store_id,
					sl.store_name,
					SUM(
						IF(
							os.lens_option = 'without prescription',
							1,
							0
						)
					) AS 'frame_only',
					SUM(
						IF(
							os.lens_option <> 'without prescription',
							1,
							0
						)
					) AS 'package',
					COUNT(o.store_id),
					SUM(os.price)
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN stores_locations sl
							ON sl.store_id = o.store_id
				WHERE
					os.payment = 'y'					
						AND os.status NOT IN ('cancelled', 'canceled', 'failed', 'return')						
						AND o.order_id NOT IN ('_remake', '-1100000001254')
						AND sl.store_id <> '1000'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = '".$search_month."'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') >= '".$search_previous_year."'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= '".$search_current_year."'
				GROUP BY
					o.store_id
				ORDER BY
					sl.store_name ASC";

	$grabParams = array(
		'year',
		'month',
		'store_id',
		'store_name',
		'frame_only',
		'package',
		'count',
		'total'
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

			$arrTopMetrics[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrTopMetrics;

};

function grabLensMetrics($search_previous_year, $search_current_year, $search_month) {

	global $conn;	

	// Set array
	$arrLensMetrics = array();

	$query = 	"SELECT  
					DATE_FORMAT(os.payment_date, '%Y') AS 'year',
					DATE_FORMAT(os.payment_date, '%m') AS 'month',
					os.product_code,
					TRIM(LEFT(item_name, LOCATE(' ', item_name) - 1)) AS 'product_style',
					TRIM(REPLACE(item_name, TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '')) AS 'product_color',
					os.product_upgrade,
					os.lens_code,
					os.prescription_vision,
					IF(
						os.prescription_vision = '',
						'frame_only',
						IF(
							os.lens_code = 'SO1001',
							'special_order',
							os.prescription_vision
						)
					) AS 'prescription_vision_type',
					os.lens_option,
					os.price
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_code	
				WHERE
					o.order_id IS NOT NULL 
						AND os.payment = 'y'					
						AND os.status IN ('complete', 'dispatched', 'paid', 'received')						
						AND DATE_FORMAT(os.payment_date, '%M') = '".$search_month."'
						AND DATE_FORMAT(os.payment_date, '%Y') >= '".$search_previous_year."'
						AND DATE_FORMAT(os.payment_date, '%Y') <= '".$search_current_year."'";

	$grabParams = array(
		'year',
		'month',
		'product_code',
		'style',
		'color',
		'product_upgrade',
		'lens_code',
		'prescription_vision',
		'prescription_vision_type',
		'lens_option',
		'price'
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

			$arrLensMetrics[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrLensMetrics;

};

function grabStylesMetrics($search_previous_year, $search_current_year, $search_month) {

	global $conn;	

	// Set array
	$arrFrameMetrics = array();

	$query = 	"SELECT  
					DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') AS 'year',	
					DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') AS 'month',						
					os.product_code,
					TRIM(LEFT(item_name, LOCATE(' ', item_name) - 1)) AS 'product_style',
					TRIM(REPLACE(item_name, TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '')) AS 'product_color',
					COUNT(os.product_code) AS 'frames_sold',
					SUM(os.price) AS 'frames_total'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_code	
				WHERE
					os.payment = 'y'					
						AND os.status NOT IN ('cancelled', 'canceled', 'failed', 'return')						
						AND o.order_id NOT IN ('_remake', '-1100000001254')
						AND o.store_id <> '999'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = '".$search_month."'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') >= '".$search_previous_year."'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= '".$search_current_year."'
				GROUP BY
					product_style
				ORDER BY
					frames_sold DESC";

	$grabParams = array(
		'year',
		'month',
		'product_code',
		'style',
		'color',
		'frames_sold',
		'frames_total'
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

			$arrFrameMetrics[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrFrameMetrics;

};

function grabSKUsMetrics($search_previous_year, $search_current_year, $search_month) {

	global $conn;	

	// Set array
	$arrFrameMetrics = array();

	$query = 	"SELECT  
					DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') AS 'year',	
					DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') AS 'month',						
					os.product_code,
					TRIM(LEFT(item_name, LOCATE(' ', item_name) - 1)) AS 'product_style',
					TRIM(REPLACE(item_name, TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '')) AS 'product_color',
					COUNT(os.product_code) AS 'frames_sold',
					SUM(os.price) AS 'frames_total'
				FROM 
					orders o
						LEFT JOIN orders_specs os
							ON os.order_id = o.order_id
						LEFT JOIN poll_51 p51
							ON p51.product_code = os.product_code	
				WHERE
					os.payment = 'y'					
						AND os.status NOT IN ('cancelled', 'canceled', 'failed', 'return')						
						AND o.order_id NOT IN ('_remake', '-1100000001254')
						AND o.store_id <> '999'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = '".$search_month."'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') >= '".$search_previous_year."'
						AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(o.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= '".$search_current_year."'
				GROUP BY
					os.product_code
				ORDER BY
					frames_sold DESC";

	$grabParams = array(
		'year',
		'month',
		'product_code',
		'style',
		'color',
		'frames_sold',
		'frames_total'
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

			$arrFrameMetrics[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrFrameMetrics;

};


?>