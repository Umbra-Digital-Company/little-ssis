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
if((isset($_GET['date']) && !empty($_GET['date'])) || (isset($_GET['orders_specs_id']) && trim($_GET['orders_specs_id']) != '')){
	$querySearch .= (isset($_GET['orders_specs_id']) && trim($_GET['orders_specs_id']) != '') ? 
	" WHERE os.orders_specs_id ='".$_GET['orders_specs_id']."' " :  
	" WHERE (DATE(os.payment_date) = '".date('Y-m-d',strtotime($_GET['date']))."' AND os.courier != '' AND os.courier_no != '')";
	




	//////////////////////////////////////////////////////////////////////////////////// GRAB PAGE NUMBERS

	$totalNumberOfOrders = 0;

	$query = 	"SELECT					
					os.orders_specs_id
				FROM 
					orders o
				LEFT JOIN orders_specs os ON o.order_id =  os.order_id
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

	//////////////////////////////////////////////////////////////////////////////////// GRAB PURCHASE ORDER

	$grabParams = array(
		"currency",
		"first_name",
		"middle_name",
		"last_name",
		"country",
		"province",
		"city",
		"barangay",
		"quantity",
		"po_number",
		"amount",
		"nv_status",
		"pos_status",
		"payment_date",
		"courier"
	);

	$query  = 	"SELECT
				o.currency,
				pi.first_name,
				pi.middle_name,
				pi.last_name,
				psa.country,
				psa.province,
				psa.city,
				psa.barangay,
				COUNT(o.order_id),
				os.po_number,
				SUM(os.price),
				nvos.status,
				pos.status,
				os.payment_date,
				os.courier
				FROM 
					orders o
				LEFT JOIN orders_specs os ON o.order_id =  os.order_id
				LEFT JOIN profiles_info pi ON pi.profile_id =o.profile_id
				LEFT JOIN profiles_shipping_address psa ON os.order_id = psa.order_id
				LEFT JOIN ninja_van_order_status nvos ON o.order_id =  nvos.order_id
				LEFT JOIN payo_order_status pos ON os.orders_specs_id =  pos.order_specs_id
					".$querySearch."
				GROUP BY
				o.id, os.po_number
				".$queryLimit;
				//echo $query;
				
	$stmt = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});
	            $tempArray['payment_date'] = date('Y-m-d', strtotime($tempArray['payment_date']));

	        };
	        $arrOrders[] = $tempArray;

	    };
	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};
	if(isset($_GET['print']) || isset($_GET['orders_specs_id'])){
		$arrOrdersData = [];
		$grabParams = array(
			"currency",
			"count",
			"po_number",
			"item_description",
			"amount",
			"prescription_vision"
		);

		$query  = 	"SELECT
					o.currency,
					COUNT(os.product_code),
					os.po_number,
					pr.item_description,
					os.price,
					os.prescription_vision
					FROM 
						orders o
					LEFT JOIN orders_specs os ON o.order_id =  os.order_id
					LEFT JOIN poll_51 pr ON pr.product_code = os.product_code
						".$querySearch."
					GROUP BY
					o.id, os.po_number, os.product_code
					".$queryLimit;
					//echo $query;
					
		$stmt = mysqli_stmt_init($conn);

		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);
		    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

		    while (mysqli_stmt_fetch($stmt)) {

		        $tempArray = array();

		        for ($i=0; $i < sizeOf($grabParams); $i++) { 

		            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

		        };
		        $arrOrdersData[] = $tempArray;

		    };
		    mysqli_stmt_close($stmt);    
		                            
		}
		else {

		    echo mysqli_error($conn);

		};
		//  var_dump($arrOrdersData);

		// exit;
	}
}
?>