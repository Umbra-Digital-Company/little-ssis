<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB DATA AND SET LIMITS

////////////////////////////// SEARCH

// Set search term
$querySearch = "";
// Set array
$arrOrders = array();
if(isset($_GET['search']) && !empty($_GET['search'])) {
	$arrSearch = $_GET['search']; 
	
		$querySearch .= " WHERE (
							os.po_number LIKE '%".$arrSearch."%'
	 					)";




	//////////////////////////////////////////////////////////////////////////////////// GRAB PAGE NUMBERS

	$totalNumberOfOrders = 0;

	$query = 	"SELECT					
					os.orders_specs_id
				FROM 
					orders_test o
				LEFT JOIN orders_specs_test os ON o.order_id =  os.order_id
				LEFT JOIN profiles_info pi ON pi.profile_id =o.profile_id
					".$querySearch;
	if ($stmt= mysqli_prepare($conn, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_store_result($stmt);
		$totalNumberOfOrders = mysqli_stmt_num_rows($stmt);
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

		$pageNum = $_GET['page'];
		$queryLimit = " LIMIT ".( ($pageNum - 1) * 100 ).", 100;";

	}
	else {

		$pageNum = 1;
		$queryLimit = " LIMIT 100;";

	};

	//////////////////////////////////////////////////////////////////////////////////// GRAB PURCHASE ORDER

	$grabParams = array(
		"first_name",
		"middle_name",
		"last_name",
		"po_number",
		"orders_specs_id",
		"status",
		"payment_date",
		"payment"
	);

	 $query  = 	"SELECT
				pi.first_name,
				pi.middle_name,
				pi.last_name,
				os.po_number,
				os.orders_specs_id,
				os.status,
				os.payment_date,
				os.payment
				FROM 
					orders_test o
				LEFT JOIN orders_specs_test os ON o.order_id =  os.order_id
				LEFT JOIN profiles_info pi ON pi.profile_id =o.profile_id
					".$querySearch."
				ORDER BY
				o.id DESC
				".$queryLimit;
				
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

	        };
	        $am_pm = explode(' ', $tempArray['payment_date']);
	        $am_pm = explode(":", $am_pm[1]);
	        $am_pm = ($am_pm[0] < 12) ? ' AM' : ' PM';
	        $tempArray['payment_date'] = date('m/d/Y h:i:s', strtotime($tempArray['payment_date'])).$am_pm;
	        $arrOrders[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};
$arrWarrantyType = [];
	$grabParams = array(
		"id",
		"description",
		"duration"
	);

	$query  = 	"SELECT
				id,
				description,
				duration
				FROM 
					warranty_type 
				ORDER BY
				id ASC
				";
				
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

	        };
	        $arrWarrantyType[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};
};
?>