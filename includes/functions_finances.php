<?php

//////////////////////////////////////////////////////////////////////////////////// DATE SETTINGS

// Set timezone
date_default_timezone_set("Asia/Manila");

// Grab GET settings


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

function grabRevenue() {

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

	if(isset($_GET['date'])) {

		switch ($_GET['date']) {

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
							GROUP BY
								month
							ORDER BY
                                    FIELD(month, 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December')";
				break;
			
		}

	}
	elseif(isset($_GET['date_range_start'])) {

		$dateStart = $_GET['date_range_start'];
		$queryDateA = "	AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d') >= '".$dateStart."'";

		if(isset($_GET['date_range_end'])) {

			$dateEnd = $_GET['date_range_end'];
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
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%M')
							AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(os.date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = DATE_FORMAT(DATE_ADD(DATE_FORMAT(NOW(), '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y')
							".$specStore."
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

function grabCustomers() {

	global $conn;
	global $qDate;
	global $qStore;

	// Set Store ID if specified
	if($qStore != "") {

		$specStore = "AND o.store_id = '".$qStore."'";

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
					".$specStore; 

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
	global $qStore;

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
					sl.address,
					sl.province,
					sl.city,
					sl.barangay,
					sl.phone_number,
					sl.email_address,
					sl.active
				FROM
					stores_locations sl					
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
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14);

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

?>