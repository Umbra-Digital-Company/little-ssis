<?php 
if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-d',strtotime("-1 days"));
	}elseif($_GET['date']=='week'){
		$dateStart = date( 'Y-m-d', strtotime( 'monday this week' ) );
		 $dateEnd = date( 'Y-m-d', strtotime( 'sunday this week' ) );
	}
	elseif($_GET['date']=='custom'){
		 $dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		 $dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
	}
	elseif($_GET['date']=='all-time'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	else{
		$dateStart = date('Y-m-d');
			$dateEnd= date('Y-m-t');
	}
	
}
else{
	$dateStart = date('Y-m-d');
		$dateEnd= date('Y-m-t');
}


$dateFstart= date('Y-m-d',strtotime($dateStart.'-1 days')) ;
$arrActualCount2= array();

$grabParamsACtual= array(
							'count',
							'actual_count_id',
							'date_count',
							'date_start',
							'date_end', 
							'store_audited',
							'auditor',
							'product_code',
							'input_count'
);
$queryActualCounts="SELECT `count`,
							`actual_count_id`,
							`date_count`,
							`date_start`,
							`date_end`, 
							`store_audited`,
							`auditor`,
							`product_code`,
							`input_count` 
							FROM `inventory_actual_count`
							 WHERE store_audited='".$store_idac."' 
							 and  date_end ='".$dateFstart."'
                             
                             order by date_end DESC
							";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryActualCounts)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParamsACtual); $i++) { 

			$tempArray[$grabParamsACtual[$i]] = ${'result' . ($i+1)};

		};

		$arrActualCount2[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);

};
?> 