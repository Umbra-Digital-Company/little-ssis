<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB DATA AND SET LIMITS

////////////////////////////// SEARCH
// Set stores if specified
if(isset($_GET['filterStores'])) {

	// Set stores array
	$arrFilterStores = $_GET['filterStores'];

}
else {

	$arrFilterStores = array();

};

/////////CHANGE BASIS

if(isset($_GET['basis'])){

    if($_GET['basis']=='origin_branch'){

		
    
            $numberedcount=" LEFT JOIN stores_locations sl
                                  ON sl.store_id = o.origin_branch";

            $querypatient="	LEFT JOIN store_codes sc
            ON sc.location_code = o.origin_branch";

            if(!empty($arrFilterStores)) {

                // Set WHERE query for stores
                $specStore = "AND o.origin_branch IN (";
            
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

			if(isset($_GET['date']) && $_GET['date'] != 'custom') {

				switch ($_GET['date']) {
			
					case 'yesterday':		
						$today = date('Y-m-d');
						$yesterdayinit = date('Y-m-d', strtotime($today . "-1 day"));
						$qGrabDateA = date('d', strtotime($yesterdayinit));
						$qGrabDateB = date('m', strtotime($yesterdayinit));
						$qGrabDateC = date('Y', strtotime($yesterdayinit));
						$qDate = 	"DATE_FORMAT(os.payment_date , '%d') = ".$qGrabDateA." 
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
			
				$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
				$qDateA = "DATE_FORMAT(os.payment_date, '%Y-%m-%d') >= '".$dateStart."'";
			
				if(isset($_GET['data_range_end_month'])) {
			
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
			
				$qGrabDateA = date("m");
				$qGrabDateB = date("Y");
				$qDate = 	"DATE_FORMAT(os.payment_date, '%m') = ".$qGrabDateA."
								AND DATE_FORMAT(os.payment_date, '%Y') = ".$qGrabDateB;
			
			};
			

    }else{
        $numberedcount=" LEFT JOIN stores_locations sl
        ON sl.store_id = pi.branch_applied";

        $querypatient="	LEFT JOIN store_codes sc
        ON sc.location_code = pi.branch_applied";

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
		if(isset($_GET['date']) && $_GET['date'] != 'custom') {

			switch ($_GET['date']) {
		
				case 'yesterday':		
					$today = date('Y-m-d');
					$yesterdayinit = date('Y-m-d', strtotime($today . "-1 day"));
					$qGrabDateA = date('d', strtotime($yesterdayinit));
					$qGrabDateB = date('m', strtotime($yesterdayinit));
					$qGrabDateC = date('Y', strtotime($yesterdayinit));
					$qDate = 	"DATE_FORMAT(pi.joining_date , '%d') = ".$qGrabDateA." 
									AND DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateB."
									AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateC;
					break;
		
				case 'day':
					$qGrabDateA = date("d");
					$qGrabDateB = date("m");
					$qGrabDateC = date("Y");
					$qDate = 	"DATE_FORMAT(pi.joining_date, '%d') = ".$qGrabDateA." 
									AND DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateB."
									AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateC;
					break;
		
				case 'week':
					$qGrabDateA = date("Y-m-d");
					$qDate = 	"YEARWEEK(DATE_FORMAT(pi.joining_date, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
					break;
		
				case 'month':
					$qGrabDateA = date("m");
					$qGrabDateB = date("Y");
					$qDate = 	"DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateA."
									AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateB;
					break;
				
				case 'year':
					$qGrabDate = date("Y");
					$qDate = "DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDate;
					break;
		
				case 'all-time':
					$qGrabDate = date("Y");
					$qDate = 	"DATE_FORMAT(pi.joining_date, '%Y') <= ".$qGrabDate;
					break;
		
			}	
		
		}
		elseif(isset($_GET['data_range_start_month'])) {
		
			$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
			$qDateA = "DATE_FORMAT(pi.joining_date, '%Y-%m-%d') >= '".$dateStart."'";
		
			if(isset($_GET['data_range_end_month'])) {
		
				$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
				$qDateB = " AND DATE_FORMAT(pi.joining_date, '%Y-%m-%d') <= '".$dateEnd."'";
		
			}
			else {
		
				$dateEnd = "";
				$qDateB = "";
		
			};
		
			$qDate = $qDateA.$qDateB;
		
		}
		else {
		
			$qGrabDateA = date("m");
			$qGrabDateB = date("Y");
			$qDate = 	"DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateA."
							AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateB;
		
		};

	}

	

}else{


    $numberedcount=" LEFT JOIN stores_locations sl
    ON sl.store_id = pi.branch_applied";

        $querypatient="	LEFT JOIN store_codes sc
        ON sc.location_code = pi.branch_applied";

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

	if(isset($_GET['date']) && $_GET['date'] != 'custom') {

		switch ($_GET['date']) {
	
			case 'yesterday':		
				$today = date('Y-m-d');
				$yesterdayinit = date('Y-m-d', strtotime($today . "-1 day"));
				$qGrabDateA = date('d', strtotime($yesterdayinit));
				$qGrabDateB = date('m', strtotime($yesterdayinit));
				$qGrabDateC = date('Y', strtotime($yesterdayinit));
				$qDate = 	"DATE_FORMAT(pi.joining_date , '%d') = ".$qGrabDateA." 
								AND DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateB."
								AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateC;
				break;
	
			case 'day':
				$qGrabDateA = date("d");
				$qGrabDateB = date("m");
				$qGrabDateC = date("Y");
				$qDate = 	"DATE_FORMAT(pi.joining_date, '%d') = ".$qGrabDateA." 
								AND DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateB."
								AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateC;
				break;
	
			case 'week':
				$qGrabDateA = date("Y-m-d");
				$qDate = 	"YEARWEEK(DATE_FORMAT(pi.joining_date, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
				break;
	
			case 'month':
				$qGrabDateA = date("m");
				$qGrabDateB = date("Y");
				$qDate = 	"DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateA."
								AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateB;
				break;
			
			case 'year':
				$qGrabDate = date("Y");
				$qDate = "DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDate;
				break;
	
			case 'all-time':
				$qGrabDate = date("Y");
				$qDate = 	"DATE_FORMAT(pi.joining_date, '%Y') <= ".$qGrabDate;
				break;
	
		}	
	
	}
	elseif(isset($_GET['data_range_start_month'])) {
	
		$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
		$qDateA = "DATE_FORMAT(pi.joining_date, '%Y-%m-%d') >= '".$dateStart."'";
	
		if(isset($_GET['data_range_end_month'])) {
	
			$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
			$qDateB = " AND DATE_FORMAT(pi.joining_date, '%Y-%m-%d') <= '".$dateEnd."'";
	
		}
		else {
	
			$dateEnd = "";
			$qDateB = "";
	
		};
	
		$qDate = $qDateA.$qDateB;
	
	}
	else {
	
		$qGrabDateA = date("m");
		$qGrabDateB = date("Y");
		$qDate = 	"DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateA."
						AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateB;
	
	};
	


}


//////////END CHANGE BASIS



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
// if(isset($_GET['filterStores'])) {

// 	// Set stores array
// 	$arrFilterStores = $_GET['filterStores'];

// }
// else {

// 	$arrFilterStores = array();

// };

// // Set Store ID if specified
// if(!empty($arrFilterStores)) {

// 	// Set WHERE query for stores
// 	$specStore = "AND pi.branch_applied IN (";

// 	for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 
	
// 		$specStore .= "'".$arrFilterStores[$i]."'";

// 		if($i < sizeOf($arrFilterStores) - 1) {

// 			$specStore .= ",";

// 		}

// 	};

// 	$specStore .= ")";		


// }
// else {

// 	$specStore = "";

// };

////////////////////////////// DATE

// Set timezone
date_default_timezone_set("Asia/Manila");

// Grab GET settings
// if(isset($_GET['date']) && $_GET['date'] != 'custom') {

// 	switch ($_GET['date']) {

// 		case 'yesterday':		
// 			$today = date('Y-m-d');
// 			$yesterdayinit = date('Y-m-d', strtotime($today . "-1 day"));
// 			$qGrabDateA = date('d', strtotime($yesterdayinit));
// 			$qGrabDateB = date('m', strtotime($yesterdayinit));
// 			$qGrabDateC = date('Y', strtotime($yesterdayinit));
// 			$qDate = 	"DATE_FORMAT(pi.joining_date , '%d') = ".$qGrabDateA." 
// 							AND DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateB."
// 							AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateC;
// 			break;

// 		case 'day':
// 			$qGrabDateA = date("d");
// 			$qGrabDateB = date("m");
// 			$qGrabDateC = date("Y");
// 			$qDate = 	"DATE_FORMAT(pi.joining_date, '%d') = ".$qGrabDateA." 
// 							AND DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateB."
// 							AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateC;
// 			break;

// 		case 'week':
// 			$qGrabDateA = date("Y-m-d");
// 			$qDate = 	"YEARWEEK(DATE_FORMAT(pi.joining_date, '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
// 			break;

// 		case 'month':
// 			$qGrabDateA = date("m");
// 			$qGrabDateB = date("Y");
// 			$qDate = 	"DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateA."
// 							AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateB;
// 			break;
		
// 		case 'year':
// 			$qGrabDate = date("Y");
// 			$qDate = "DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDate;
// 			break;

// 		case 'all-time':
// 			$qGrabDate = date("Y");
// 			$qDate = 	"DATE_FORMAT(pi.joining_date, '%Y') <= ".$qGrabDate;
// 			break;

// 	}	

// }
// elseif(isset($_GET['data_range_start_month'])) {

// 	$dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );
// 	$qDateA = "DATE_FORMAT(pi.joining_date, '%Y-%m-%d') >= '".$dateStart."'";

// 	if(isset($_GET['data_range_end_month'])) {

// 		$dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );
// 		$qDateB = " AND DATE_FORMAT(pi.joining_date, '%Y-%m-%d') <= '".$dateEnd."'";

// 	}
// 	else {

// 		$dateEnd = "";
// 		$qDateB = "";

// 	};

// 	$qDate = $qDateA.$qDateB;

// }
// else {

// 	$qGrabDateA = date("m");
// 	$qGrabDateB = date("Y");
// 	$qDate = 	"DATE_FORMAT(pi.joining_date, '%m') = ".$qGrabDateA."
// 					AND DATE_FORMAT(pi.joining_date, '%Y') = ".$qGrabDateB;

// };

//////////////////////////////////////////////////////////////////////////////////// GRAB ORDER NUMBERS

$totalNumberOfOrders = 0;

$query = 	"SELECT					
				COUNT(pi.profile_id)
			FROM 
				profiles p
					LEFT JOIN profiles_info pi
						ON pi.profile_id = p.profile_id
                    LEFT JOIN orders o
                         ON o.profile_id=pi.profile_id
					LEFT JOIN orders_specs os
							ON o.order_id=os.order_id
                         ".$numberedcount." 
			WHERE
				".$qDate."
				".$querySearch."
				".$specStore."
				and os.dispatch_type!='packaging' ";

// echo '<pre>';
// echo $query;
// echo '</pre>';
// exit;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);
    mysqli_stmt_fetch($stmt);
	mysqli_stmt_store_result($stmt);

	$totalNumberOfOrders = $result1;

	mysqli_stmt_close($stmt);

}
else {

	echo mysqli_error($conn);
	exit;

};

// Calculate pages
$numberPages = ceil($totalNumberOfOrders / 100);

// Set Limit
if(isset($_GET['page'])) {

	$page = $_GET['page'];
	$queryLimit = " LIMIT ".( ($page - 1) * 100 ).", 100;";

}
else {

	$page = 1;
	$queryLimit = " LIMIT 100;";

};

//////////////////////////////////////////////////////////////////////////////////// GRAB PATIENTS

// Set array
$arrCustomerP = array();

$grabParams = array(	
	"profile_id",
	"first_name",
	"middle_name",
	"last_name",
	"gender",
	"age_recorded",
	"age",
	"branch_applied_code",
	"branch_applied_name",	
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
				pi.branch_applied,
				sc.branch,				
				os.payment_date
			FROM 
				profiles p
					LEFT JOIN profiles_info pi
						ON pi.profile_id = p.profile_id
					
					LEFT JOIN orders o 
						ON o.profile_id = p.profile_id
					LEFT JOIN orders_specs os
						ON os.order_id = o.order_id
                         ".$querypatient." 
			WHERE
				".$qDate."
				".$querySearch."
				".$specStore."
					AND os.payment = 'y'
					and  pi.first_name NOT like '%Guest%'
					and pi.email_address NOT LIKE '%specsguest@sunniesspecsoptical.com%'
					and os.dispatch_type!='packaging'
			GROUP BY
				pi.profile_id	
			ORDER BY
				os.date_created DESC, pi.joining_date DESC			
			".$queryLimit;				
			
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerP[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

// echo '<pre>';
// print_r($arrCustomerP);
// echo '</pre>';
// exit;

?>