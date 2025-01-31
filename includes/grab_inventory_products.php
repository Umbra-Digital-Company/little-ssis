<?php

if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-t');
	}elseif($_GET['date']=='week'){
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
}


if(isset($_GET['filterStores'])){

	$store_id=$_GET['filterStores'][0];

}else{
	if($_SESSION['user_login']['userlvl'] == '13'){
			$store_id='warehouse';

	}else{
			$store_id=$_SESSION['store_code'];
	}
}

$alphabet="";

if(isset($_GET['alpha']) ){
	$alphabet =" AND p51.item_name like '".$_GET['alpha']."%' ";
}else{

	$alphabet =" AND p51.item_name like 'A%'   ";
}


	$arrFrames = array();

	  $query =    "SELECT 
							TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
							REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
							p51.product_code,
									(SELECT 
										coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0)
								 FROM inventory 
							WHERE
									product_code =p51.product_code
							AND
								store_id='".$store_id."'
							
							AND 
								status ='received'
							AND
								(
									`type`='replenish'
									OR
									`type`='stock_transfer'
									OR
									`type`='interbranch'
								
								)
						
							AND
								date(status_date)<='".$dateStart."'
							
									) as beginventory,
							

						(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
													WHERE
													product_code =p51.product_code
														AND
												stock_from='".$store_id."'
											
											AND 
												status ='received' 
											AND type='pullout'
											AND
											date(status_date)<='".$dateStart."'
									) as pullout,

						(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
													WHERE
													product_code =p51.product_code
														AND
												stock_from='".$store_id."'
											
											AND 
												status ='received' 
											AND type='damage'
											AND
											date(status_date)<='".$dateStart."'
									) as damage,

							(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
													WHERE
													product_code =p51.product_code
														AND
												stock_from='".$store_id."'
											
											AND 
												status ='received' 
											AND type='stock_transfer'
											AND
											date(status_date)<='".$dateStart."'
									) as stock_transfer_out,
									

							(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
													WHERE
													product_code =p51.product_code
														AND
												store_id='".$store_id."'
											
											AND 
												status ='received' 
											AND (type='stock_transfer'
														OR
												type='replenish'
														)
											AND
											date(status_date)>='".$dateStart."'
												AND
												date(status_date)<='".$dateEnd."'
									) as stock_transfer_in_c,


							(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
													WHERE
													product_code =p51.product_code
														AND
												stock_from='".$store_id."'
											
											AND 
												status ='received' 
											AND type='stock_transfer'
											AND
											date(status_date)>='".$dateStart."'
												AND
												date(status_date)<='".$dateEnd."'
									) as stock_transfer_out_c,



							(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
													WHERE
													product_code =p51.product_code
														AND
												stock_from='".$store_id."'
											
											AND 
												status ='received' 
											AND type='interbranch'
											
											AND
											date(status_date)>='".$dateStart."'
												AND
												date(status_date)<='".$dateEnd."'
									) as interbranch_out_c,
									
									(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
													WHERE
													product_code =p51.product_code
														AND
												store_id='".$store_id."'
											
											AND 
												status ='received' 
											AND type='interbranch'
										
											AND
											date(status_date)>='".$dateStart."'
												AND
												date(status_date)<='".$dateEnd."'
									) as interbranch_in_c	,
							

							(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
														WHERE
														product_code =p51.product_code
															AND
													stock_from='".$store_id."'
												
												AND 
													status ='received' 
												AND type='pullout'
												AND
												date(status_date)>='".$dateStart."'
												AND
												date(status_date)<='".$dateEnd."'
										) as pullout_c,
	
							(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
														WHERE
														product_code =p51.product_code
															AND
													stock_from='".$store_id."'
												
												AND 
													status ='received' 
												AND type='damage'
												AND
												date(status_date)>='".$dateStart."'
												AND
												date(status_date)<='".$dateEnd."'
										) as damage_c,
										

										(SELECT 
										coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0)
								 FROM inventory 
							WHERE
									product_code =p51.product_code
							AND requested='y'
							
							AND 
								status !='received'
							AND
								(
									`type`='replenish'
									OR
									`type`='stock_transfer'
									OR
									`type`='interbranch'
								
								)
						
							AND
								date(status_date)<='".$dateEnd."'
							AND
												date(status_date)>='".$dateStart."'
							
									) as requested,
						
									(select coalesce(
												sum(
														if(variance_status='approve',
														actual_count,
														`count`)
										),0) FROM inventory 
														WHERE
														product_code =p51.product_code
															AND
													store_id='warehouse_damage'
												
												AND 
													status ='received' 
												AND type='damage'
												AND
												date(status_date)>='".$dateStart."'
												AND
												date(status_date)<='".$dateEnd."'
										) as damage_i
							
					FROM 
							poll_51_new p51
					LEFT JOIN 
							inventory ip
						ON
							ip.product_code=p51.product_code
					
				
					WHERE
		p51.product_code NOT LIKE 'L0%'

	

		GROUP BY 
			p51.product_code
	ORDER BY 
	
		item_name ASC";

	$grabParams = array(
		'product_style',
		'product_color',
		'product_code',
		'beginning_inventory',
		'pullout',
		'damage',
		'stock_transfer_out',
		'stock_transfer_in_c',
		'stock_transfer_out_c',
		'interbranch_out_c',
		'interbranch_in_c',
		'pullout_c',
		'damage_c',
		'requested',
		'damage_i'
		
	);
		
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9
		, $result10, $result11, $result12, $result13, $result14, $result15);

		while (mysqli_stmt_fetch($stmt)) {

			$tempArray = array();

			for ($i=0; $i < sizeOf($grabParams); $i++) { 

				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

			};

			$arrFrames[] = $tempArray;

		};

		mysqli_stmt_close($stmt);    
								
	}
	else {

		echo mysqli_error($conn);

	};



	if(isset($_GET['filterStores'])){

		$store_idac=$_GET['filterStores'][0];
	
	}else{
		if($_SESSION['user_login']['userlvl'] == '13'){
				$store_idac='warehouse';
	
		}else{
				$store_idac=$_SESSION['store_code'];
		}
	}
	

$arrActualCount= array();

$grabParamsACtual= array(
							'count',
							'actual_count_id',
							'date_count',
							'date_start',
							'date_end', 
							'store_audited',
							'auditor',
							'product_code'
);
$queryActualCounts="SELECT `count`,
							`actual_count_id`,
							`date_count`,
							`date_start`,
							`date_end`, 
							`store_audited`,
							`auditor`,
							`product_code` 
							FROM `inventory_actual_count`
							 WHERE store_audited='".$store_idac."' 
							";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryActualCounts)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParamsACtual); $i++) { 

			$tempArray[$grabParamsACtual[$i]] = ${'result' . ($i+1)};

		};

		$arrActualCount[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);

};









?>