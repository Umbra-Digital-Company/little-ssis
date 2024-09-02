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
if((isset($_GET['order_id']) && trim($_GET['order_id'])) || (isset($_GET['orders_specs_id']) && trim($_GET['orders_specs_id']) != '')){
	$querySearch .=  " WHERE os.order_id ='".trim($_GET['order_id'])."' ";
	$querySearch .= (isset($_GET['orders_specs_id']) && trim($_GET['orders_specs_id']) != '') ? "AND os.orders_specs_id ='".trim($_GET['orders_specs_id'])."' " : "";
	$querySearch .="AND os.status != 'cancelled'";
	$querySearch .= (isset($_GET['invoice_no']) && trim($_GET['invoice_no']) != '') ? " AND os.courier_no='".trim($_GET['invoice_no'])."' " : "";

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
		"phone_number",
		"po_number",
		"order_id",
		"amount",
		"pos_status",
		"date",
		"pos_invoice_no",
		"pos_tracking_no",
		"shipper",
		"address1",
		"address2",
		"product_code",
		"comments",
		"payment_date",
		"courier",
		"courier_no",
		"item_description",
		"sum_price",
		"total_count",
        "product_upgrade",
		"prescription_vision",
		"payment_method",
		"zip_code",
		"promo_code",
		"o_email_address",
		"item_description_2"
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
				psa.phone_number,
				os.po_number,
				os.order_id,
				os.price,
				pos.status,
				pos.date_updated,
				pos.invoice_no,
				pos.tracking_no,
				pos.sub_courier,
				psa.address1,
				psa.address2,
				os.product_code,
				os.remarks,
				os.payment_date,
				os.courier,
				os.courier_no,
				pr.item_description,
				SUM(os.price),
				COUNT(os.id),
				os.product_upgrade,
				os.prescription_vision,
               IF(
                ppic.order_specs_id IS NOT NULL &&  ppic.status='paid',
            'credit',
            o.payment_method
            ) as payment_type,
				psa.zip_code,
				o.promo_code,
                o.email_address,
				LOWER(p51b.item_description)
				FROM 
					orders o
				LEFT JOIN orders_specs os ON o.order_id =  os.order_id
				LEFT JOIN poll_51 pr ON pr.product_code = os.product_code
				LEFT JOIN profiles_info pi ON pi.profile_id =o.profile_id
				LEFT JOIN profiles_shipping_address psa ON os.order_id = psa.order_id
				LEFT JOIN payo_order_status pos ON os.orders_specs_id =  pos.order_specs_id
				LEFT JOIN poll_51 p51b
                        ON p51b.product_code = os.product_upgrade
				LEFT JOIN paymongo_payment_intents_completed ppic 
                        ON ppic.order_specs_id = o.order_id 
					".$querySearch."
					 AND os.product_upgrade NOT IN ('SP-DGC500','SP-DGC1000','SP-DGC2000')
				GROUP BY os.product_code,os.product_upgrade,os.prescription_vision;";
				
	$stmt = mysqli_stmt_init($conn);

	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, 
		$result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20,
		 $result21, $result22, $result23, $result24,$result25, $result26,  $result27,  $result28,  $result29,  $result30,  $result31, $result32,  $result33,  $result34);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});
	            

	        };
	        $tempArray['date'] = ($tempArray['date'] != '') ? date('m/d/Y', strtotime($tempArray['date'])) : '';
	        $arrOrders[] = $tempArray;

	    };
	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};
	//var_dump($arrOrders);
// 	if(isset($_GET['print']) || isset($_GET['orders_specs_id'])){
// 		$arrOrdersData = [];
// 		$grabParams = array(
// 			"currency",
// 			"count",
// 			"po_number",
// 			"item_description",
// 			"amount",
// 			"prescription_vision"
// 		);

// 		$query  = 	"SELECT
// 					o.currency,
// 					COUNT(os.product_code),
// 					os.po_number,
// 					pr.item_description,
// 					os.price,
// 					os.prescription_vision
// 					FROM 
// 						orders o
// 					LEFT JOIN orders_specs os ON o.order_id =  os.order_id
// 					LEFT JOIN poll_51 pr ON pr.product_code = os.product_code
// 						".$querySearch."
// 					GROUP BY
// 					o.id, os.po_number, os.product_code
// 					".$queryLimit;
// 					//echo $query;
					
// 		$stmt = mysqli_stmt_init($conn);

// 		if (mysqli_stmt_prepare($stmt, $query)) {

// 		    mysqli_stmt_execute($stmt);
// 		    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6);

// 		    while (mysqli_stmt_fetch($stmt)) {

// 		        $tempArray = array();

// 		        for ($i=0; $i < sizeOf($grabParams); $i++) { 

// 		            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

// 		        };
// 		        $arrOrdersData[] = $tempArray;

// 		    };
// 		    mysqli_stmt_close($stmt);    
		                            
// 		}
// 		else {

// 		    echo mysqli_error($conn);

// 		};
// 		//  var_dump($arrOrdersData);

// 		// exit;
// 	}
}
?>