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
			$qDate = 	"DATE_FORMAT(pi.date_created, '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(pi.date_created, '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(pi.date_created, '%Y') = ".$qGrabDateC;
			break;
			
		case 'day':
			$qGrabDateA = date("d");
			$qGrabDateB = date("m");
			$qGrabDateC = date("Y");
			$qDate = 	"DATE_FORMAT(pi.date_created, '%d') = ".$qGrabDateA." 
							AND DATE_FORMAT(pi.date_created, '%m') = ".$qGrabDateB."
							AND DATE_FORMAT(pi.date_created, '%Y') = ".$qGrabDateC;
			break;

		case 'week':
			$qGrabDateA = date("Y-m-d");
			$qDate = 	"YEARWEEK(DATE_FORMAT(pi.date_created, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
			break;

		case 'month':
			$qGrabDateA = date("m");
			$qGrabDateB = date("Y");
			$qDate = 	"DATE_FORMAT(pi.date_created, '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(pi.date_created, '%Y') = ".$qGrabDateB;
			break;
		
		case 'year':
			$qGrabDate = date("Y");
			$qDate = "DATE_FORMAT(pi.date_created, '%Y') = ".$qGrabDate;
			break;

		case 'all-time':
			$qGrabDate = date("Y");
			$qDate = 	"DATE_FORMAT(pi.date_created, '%Y') <= ".$qGrabDate;
			break;

	}	

}
elseif(isset($_GET['data_range_start_month'])) {

	// Set start date
	$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );

	$qDateA = 	"DATE_FORMAT(pi.date_created, '%Y-%m-%d') >= '".$dateStart."'";

	if(isset($_GET['data_range_end_month'])) {

		// Set end date
		$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );

		$qDateB = " AND DATE_FORMAT(pi.date_created, '%Y-%m-%d') <= '".$dateEnd."'";

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
	$qDate = 	"DATE_FORMAT(pi.date_created, '%d') = ".$qGrabDateA." 
					AND DATE_FORMAT(pi.date_created, '%m') = ".$qGrabDateB."
					AND DATE_FORMAT(pi.date_created, '%Y') = ".$qGrabDateC;

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

//////////////////////////////////////////////////////////////////////////////////// PATIENTS PROFILE FUNCTIONS

function grabPatients() {

	global $conn;
	global $qDate;
	global $arrFilterStores;

	// Set Store ID if specified
	if(!empty($arrFilterStores)) {

		// Set WHERE query for stores
		$specStore = "AND pi.branch_applied IN (";

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
	$arrPatients = array();

	$query = 	"SELECT  
					IF(
						pi.occupation <> '',
						pi.occupation,
						'n/a'
					) AS 'occupation',
					IF(
						pi.contact_lens <> '',
						pi.contact_lens,
						'n/a'
					) AS 'contact_lens',
					IF(
						pi.sleep_time <> '',
						pi.sleep_time,
						'n/a'
					) AS 'sleep_time',
					IF(
						pi.insurance <> '',
						pi.insurance,
						'n/a'
					) AS 'insurance'
				FROM 
					profiles_info pi
				WHERE
					".$qDate."
					".$specStore."
				ORDER by 
					pi.date_created"; 

	$grabParams = array(
		"occupation",
		"contact_lens",
		"sleep_time",
		"insurance"
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

			$arrPatients[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    

	}
	else {

		showMe(mysqli_error($conn));

	};

	return $arrPatients;

};

// function grabPatients() {

// 	global $conn;
// 	global $qDate;
// 	global $arrFilterStores;

// 	// Set Store ID if specified
// 	if(!empty($arrFilterStores)) {

// 		// Set WHERE query for stores
// 		$specStore = "AND o.store_id IN (";

// 		for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
		
// 			$specStore .= "'".$arrFilterStores[$i]."'";

// 			if($i < sizeOf($arrFilterStores) - 1) {

// 				$specStore .= ",";

// 			}

// 		};

// 		$specStore .= ")";		

// 	}
// 	else {

// 		$specStore = "";

// 	};

// 	// Set array
// 	$arrPatients = array();

// 	$query = 	"SELECT  
// 					pi.date_created,
// 					pi.profile_id,
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
// 					pi.priority,
// 					pi.profile_synched,
// 					pi.occupation,
// 					pi.contact_lens,
// 					pi.sleep_time,
// 					pi.insurance
// 				FROM 
// 					profiles_info pi
// 				WHERE
// 					".$qDate."
// 				ORDER by 
// 					pi.date_created"; 

// 	$grabParams = array(
// 		"date_created",
// 		"profile_id",
// 		"first_name",
// 		"middle_name",
// 		"last_name",
// 		"email_address",
// 		"phone_number",
// 		"gender",
// 		"birthday",
// 		"email_updates",
// 		"country",
// 		"province",
// 		"city",
// 		"barangay",
// 		"age",
// 		"branch_applied",
// 		"joining_date",
// 		"sales_person",
// 		"address",
// 		"priority",
// 		"profile_synched",
// 		"occupation",
// 		"contact_lens",
// 		"sleep_time",
// 		"insurance"
// 	);	

// 	$stmt = mysqli_stmt_init($conn);
// 	if (mysqli_stmt_prepare($stmt, $query)) {

// 		mysqli_stmt_execute($stmt);
// 		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25);

// 		while (mysqli_stmt_fetch($stmt)) {

// 			$tempArray = array();

// 			for ($i=0; $i < sizeOf($grabParams); $i++) { 

// 				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

// 			};

// 			$arrPatients[] = $tempArray;

// 		};

// 		mysqli_stmt_close($stmt);    

// 	}
// 	else {

// 		showMe(mysqli_error($conn));

// 	};

// 	return $arrPatients;

// };

// $arrTest = grabPatients();
// showMe($arrTest);
// exit;

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

?>