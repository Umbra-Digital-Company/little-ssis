<?php

$arrPromoCode = array();
if(isset($_GET['email']) && trim($_GET['email']) != '' ){
	$arrOrderId = [];
	$query = 	"SELECT
					o.order_id,
	 				p.first_name,
					p.last_name,
					p.email_address,
					o.promo_code,
					(SELECT number_uses FROM promo_codes WHERE promo_code = o.promo_code) AS 'number_uses',
					COUNT(o.promo_code),
					sl.store_name,
					(SELECT os.payment_date FROM orders_specs os WHERE os.order_id = o.order_id LIMIT 1) AS 'Payment Date'
				FROM 
					profiles_info p
						LEFT JOIN orders o
							ON o.profile_id = p.profile_id
						LEFT JOIN stores_locations sl
							on sl.store_id = o.origin_branch
							LEFT JOIN orders_specs os
							ON o.order_id = os.order_id
						
				WHERE 
					p.email_address ='".mysqli_real_escape_string($conn, $_GET['email'])."' 
					AND promo_code !=''
					AND p.email_address !=''
					AND os.status NOT IN ('cancelled')
					and os.product_upgrade !='SOW-500DP'

				GROUP BY o.order_id, o.promo_code
	 			ORDER BY 
					o.date_created ";
	$grabParams = array(
		'order_id',
	    'first_name',
	    'last_name',
	    'email',
		'promo_code',
		'number_uses',
		'count_promocode',
		'store',
		'payment_date'

	);
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };
	        $arrOrderId[] = $tempArray['order_id'];
	        $tempArray['db'] = 'sunniessystems';
	        $arrPromoCode[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};
	$sunnies = new mysqli("178.128.19.253", "brother", "S7xKuZYlSlx3KCcK", "ssolutio_ssis");
	$query = 	"SELECT
					o.order_id,
	 				p.first_name,
					p.last_name,
					p.email_address,
					o.promo_code,
					(SELECT number_uses FROM promo_codes WHERE promo_code = o.promo_code) AS 'number_uses',
					COUNT(o.promo_code),
					sl.store_name,
					(SELECT os.payment_date FROM orders_specs_test os WHERE os.order_id = o.order_id LIMIT 1) AS 'Payment Date'
				FROM 
					profiles_info p
						LEFT JOIN orders_test o
							ON o.profile_id = p.profile_id
						LEFT JOIN stores_locations sl
							on sl.store_id = o.origin_branch
							LEFT JOIN orders_specs_test os
								ON o.order_id = os.order_id
						
				WHERE 
					p.email_address ='".mysqli_real_escape_string($sunnies, $_GET['email'])."' 
					AND promo_code !=''
					AND p.email_address !=''
					AND o.order_id NOT IN('".implode("','", $arrOrderId)."')
					AND os.status NOT IN ('cancelled')
					AND os.product_upgrade !='SOW-500DP'
					
				GROUP BY o.order_id, o.promo_code
	 			ORDER BY 
					o.date_created ";

	$stmt = mysqli_stmt_init($sunnies);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < sizeOf($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };
	        $tempArray['db'] = 'sunniesspecs';
	        $arrPromoCode[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($sunnies);

	};

	usort($arrPromoCode, function ($item1, $item2) {
	    return $item1['promo_code'] <=> $item2['promo_code'];
	});

	 // echo "normal query output<pre>";
	 // print_r($arrPromoCode);  echo "</pre>";

	$arrPromoCodeData = [];
	$arrPromoCodeExist = [];
	foreach ($arrPromoCode as $val) {
	   if(in_array($val['promo_code'], $arrPromoCodeExist)){
	   		if(is_numeric($arrPromoCodeData[count($arrPromoCodeData) - 1]['count_promocode'])){
	   			$arrPromoCodeData[count($arrPromoCodeData) - 1]['count_promocode'] += (is_numeric($val['count_promocode'])) ? $val['count_promocode'] : 0;
	   		}

	   		$arrPromoCodeData[count($arrPromoCodeData) - 1]['store'] = $arrPromoCodeData[count($arrPromoCodeData) - 1]['store'].', '.$val['store'];
	   }else{
	   		$arrPromoCodeData[] = $val;
	   		$arrPromoCodeExist[] = $val['promo_code'];
	   }
	}
	 // echo "arrange array sum of number uses<pre>";
	 // print_r($arrPromoCodeData);  echo "</pre>";
}
?>