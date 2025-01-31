<?php

if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$dateStartpd = date('Y-m').'-1';
		$dateEndpd= date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
	 	$dateStartpd = date('Y-m-d',strtotime("-1 days"));
	 	$dateEndpd= date('Y-m-t');
	}elseif($_GET['date']=='week'){
		$dateStartpd = date( 'Y-m-d', strtotime( 'sunday this week' ) );
		 $dateEndpd = date( 'Y-m-d', strtotime( 'saturday this week' ) );
	}
	elseif($_GET['date']=='custom'){
		 $dateStartpd = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		 $dateEndpd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
	}
	elseif($_GET['date']=='all-time'){
		$dateStartpd = date('Y-m').'-1';
		$dateEndpd= date('Y-m-t');
	}

}
else{
	$dateStartpd = date('Y-m').'-1';
		$dateEndpd= date('Y-m-t');
}

if($_SESSION['store_code']=='warehouse'){
	$queStore="AND ( store_id ='".$_SESSION['store_code']."' OR stock_from='".$_SESSION['store_code']."' OR store_id='warehouse_damage' ) 
	group by date(status_date),product_code,type ";
}else{
$queStore="	AND ( store_id ='".$_SESSION['store_code']."' OR stock_from='".$_SESSION['store_code']."' ) 
	group by date(status_date),product_code,type";
}


	$arrIntPerday = array();
$querypn ="";
      $querypn .=    "SELECT TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style', 

		REPLACE(item_name, TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color', 
		p51.product_code,
		coalesce(
												sum(
														if(ip.variance_status='approve',
														ip.actual_count,
														ip.`count`)
										),0) as total,
		type,
		status,
		date(DATE_ADD(status_date, INTERVAL 13 HOUR)),
		stock_from,
		store_id		
		
		
		
		FROM poll_51 p51 
		LEFT JOIN inventory ip ON ip.product_code=p51.product_code 
		WHERE 
										p51.product_code NOT LIKE 'L0%' 
										AND
												status_date>='".$dateStartpd."'
										AND
												status_date<='".$dateEndpd."' 
										AND 
													ip.status ='received' 
													".$queStore;
										

			$querypn .=    " ORDER BY item_name ASC
							";

	$grabParamsperday = array(
		'product_style',
		'product_color',
		'product_code',
		'total',
		'type',
		'status',
		'status_date',
		'stock_from',
		'store_id'	
		
	);
		$query=$querypn;
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParamsperday); $i++) { 

				$tempArray[$grabParamsperday[$i]] = ${'result' . ($i+1)};

			};

			$arrIntPerday[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

	};

?>