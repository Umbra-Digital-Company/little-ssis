<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB DATA AND SET LIMITS

////////////////////////////// SEARCH

// Set search term
$arrSearch = explode("+", $_GET['search']);
$querySearch = "";

if(!empty($arrSearch)) {

	for ($i=0; $i < sizeOf($arrSearch); $i++) { 
	
		$querySearch .= " AND (
							pi.last_name like '%".$arrSearch[$i]."%' 
								OR pi.first_name like '%".$arrSearch[$i]."%' 
								OR pi.middle_name like  '%".$arrSearch[$i]."%'
	 					)";

	};	

};

////////////////////////////// STORES

// Set stores if specified
if(isset($_GET['filterStores'])) {

	// Set stores array
	$arrFilterStores = $_GET['filterStores'];

}
else {

	$arrFilterStores = array();

};

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

////////////////////////////// DATE

// Set timezone
date_default_timezone_set("Asia/Manila");

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

//////////////////////////////////////////////////////////////////////////////////// GRAB PATIENTS

// Set array
$arrPatients = array();

$grabParams = array(	
	"profile_id",
	"first_name",
	"middle_name",
	"last_name",
	"gender",
	"age_from_input",
	"age_from_birthdate",
	"last_frame_purchased",
	"last_lens_purchased",
	"vision",
	"payment_date",
	"last_payment_date"
);

$query  = 	"SELECT					
				pi.profile_id,
				pi.first_name,
				pi.middle_name,
				pi.last_name,	
				pi.gender,
				pi.age,
				DATE_FORMAT(FROM_DAYS(DATEDIFF(NOW(), pi.birthday)), '%Y')+0,
				CONCAT(
					LOWER(TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1))), 
					' (', 
					LOWER(REPLACE(p51.item_name,  TRIM(LEFT(p51.item_name , LOCATE(' ', p51.item_name) - 1)), '')),
					' )'
				) AS 'last_frame_purchased',
				LOWER(p51_b.item_name),
				os.prescription_vision,
				os.payment_date,
				DATEDIFF(NOW(), os.payment_date)
			FROM 
				profiles p
					LEFT JOIN profiles_info pi
						ON pi.profile_id = p.profile_id
					LEFT JOIN store_codes sc
						ON sc.location_code = pi.branch_applied
					LEFT JOIN orders o 
						ON o.profile_id = p.profile_id
					LEFT JOIN orders_specs os
						ON os.order_id = o.order_id
					LEFT JOIN poll_51 p51
						ON p51.product_code = os.product_code
					LEFT JOIN poll_51 p51_b
						ON p51_b.product_code = os.lens_code
			WHERE
				".$qDate."
				".$querySearch."
				".$specStore."
					AND os.payment = 'y'
					and pi.first_name NOT like '%Guest%'
					AND pi.branch_applied NOT IN ('142', '1000', '900', '991')
			GROUP BY
				pi.profile_id	
			ORDER BY
				os.date_created DESC, pi.joining_date DESC
			LIMIT 10;";				
			
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

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

    echo mysqli_error($conn);

}; 

// echo '<pre>';
// print_r($arrPatients);
// echo '</pre>';
// exit;

?>