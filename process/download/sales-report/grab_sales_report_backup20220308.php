<?php

///////////////////////////////////////////////// ACCESS SETTINGS

if(!isset($_GET['verification']) || $_GET['verification'] == '') {

	header('location: http://www.sunniessystems.com');
	exit;

};

///////////////////////////////////////////////// SESSION SETTINGS

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

///////////////////////////////////////////////// BRAND SETTINGS

if(isset($_GET['brand']) && $_GET['brand'] != '') {

	$download_brand = $_GET['brand'];

}
else {

	header('location: /report');
	exit;

};

///////////////////////////////////////////////// DATE SETTINGS

date_default_timezone_set('Asia/Manila');
$downloadDate = date("Ymdhis");

if(isset($_GET['date']) && $_GET['date'] != ''){

	if($_GET['date']=='month'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}
	elseif($_GET['date']=='yesterday'){

	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-t');

	}
    elseif($_GET['date']=='week'){

		$dateStart = date( 'Y-m-d', strtotime( 'sunday this week' ) );
		$dateEnd = date( 'Y-m-d', strtotime( 'saturday this week' ) );

	}
	elseif($_GET['date']=='custom'){

		$dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		$dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];

	}
	elseif($_GET['date']=='all-time'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}

}
else{

	$dateStart = date('Y-m').'-1';
	$dateEnd= date('Y-m-t');

};

///////////////////////////////////////////////// SET EMPTY ARRAY

$arrOrders = array();

///////////////////////////////////////////////// GRAB STUDIOS ORDERS

if($download_brand == 'studios') {

	$grabParamsstudios = array(    
	    "payment_date",
	    "po_number_studios",
	    "item_name",
	    "product_code",    
	    "status",
	    "price"
	);

	$queryStudios = "SELECT 
	                    os2.payment_date,
	                    os2.po_number,
	                    ps.item_name,
	                    ps.product_code,
	                    os2.status,
	                    os2.price 
	                FROM 
	                    `orders_specs` os2 
	                        LEFT JOIN poll_51_studios ps 
	                            ON ps.product_code=os2.product_code 
	                WHERE                 
	                    os2.`product_upgrade` LIKE 'sunnies_studios'                  
	                        AND os2.payment_date>='".$dateStart."'
	                        AND os2.payment_date<='".$dateEnd."'
							AND os2.order_id LIKE '".$_SESSION["store_code"]."-%'
	                ORDER BY 
	                    `payment_date` DESC";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $queryStudios)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParamsstudios); $i++) { 

	            $tempArray[$grabParamsstudios[$i]] = ${'result' . ($i+1)};

	        };

	        $arrOrders[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

};

///////////////////////////////////////////////// GRAB SPECS ORDERS

if($download_brand == 'specs') {

	$grabParamsSpecs = array(
	    "payment_date",
	    "po_number_specs",
	    "item_name",
	    "item_name_merch",
	    "product_code",
	    "product_upgrade",
	    "lens_code",
	    "prescription_vision",
	    "status",
	    "price"
	);

	$querySpecs =   "SELECT 
	                    os2.payment_date,
	                    os2.po_number,
	                    ps.item_name,
	                    ps2.item_name,
	                    ps.product_code,
	                    os2.product_upgrade,
	                    os2.lens_code,
	                    os2.prescription_vision,
	                    os2.status,
	                    os2.price 
	                FROM 
	                    `orders_specs` os2 
	                        LEFT JOIN poll_51 ps 
	                            ON ps.product_code=os2.product_code 
	                        LEFT JOIN poll_51 ps2 
	                            ON ps2.product_code=os2.product_upgrade 
	                WHERE 
	                    os2.`product_upgrade` NOT LIKE 'sunnies_studios'      
	                        AND os2.payment_date>='".$dateStart."'
	                        AND os2.payment_date<='".$dateEnd."'
							AND os2.order_id LIKE '".$_SESSION["store_code"]."-%'
	                ORDER BY 
	                    `payment_date` DESC";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $querySpecs)) {

	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParamsSpecs); $i++) { 

	            $tempArray[$grabParamsSpecs[$i]] = ${'result' . ($i+1)};

	        };

	        $arrOrders[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

};

// echo '<pre>';
// print_r($arrOrders);
// echo '</pre>';
// exit;

///////////////////////////////////////////////// DOWNLOAD AS CSV

function array_to_csv_download($array, $filename, $delimiter=";") {

	header('Content-Type: text/csv; charset=utf-8');
	header('Content-Disposition: attachment; filename="'.$filename.'";');	

	global $arrOrders;

	$f 		 = fopen('php://output', 'w');
	$arrKeys = array_keys($array[0]);

	fputcsv($f, $arrKeys, $delimiter);

	foreach ($arrOrders as $row) {

		fputcsv($f, $row, $delimiter);

	};

	fclose($f);

};

array_to_csv_download($arrOrders, "ssis-".$download_brand."-orders-".$downloadDate.".csv");

?>