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


if(isset($_GET['filterStores'])){

	$store_id=$_GET['filterStores'];

}else{
	if($_SESSION['user_login']['userlvl'] == '13' || $_SESSION['user_login']['userlvl'] == '15'|| $_SESSION['user_login']['userlvl'] == '1'){
			$store_id='warehouse';

	}else{
			$store_id=$_SESSION['store_code'];
	}
}



if(isset($_GET['filterStores'])){

	$store_id_sales=" AND o.origin_branch='".$_GET['filterStores']."' ";
}else{
	if($_SESSION['user_login']['userlvl'] == '13'){
		$store_id_sales =" AND o.origin_branch='warehouse' ";
		//	$store_id='warehouse';

	}elseif($_SESSION['user_login']['userlvl'] !== '3' && $_SESSION['user_login']['position'] !== 'laboratory') {
		$store_id_sales =" AND o.laboratory='".$_SESSION['store_code']."'
						AND os.lens_option='with prescription' 
						AND os.lens_code!='SO1001'
					 ";
		}
		elseif($_SESSION['user_login']['userlvl'] == '3' && $_SESSION['user_login']['position'] == 'laboratory') {
			$store_id_sales =" AND o.laboratory='".$_SESSION['store_code']."'
							AND os.lens_option='with prescription' 
							AND os.lens_code!='SO1001'
						 ";
			}
		else{
			$store_id_sales =" AND o.origin_branch='".$_SESSION['store_code']."'
						AND (
							 os.lens_option='without prescription'
							OR
							 os.lens_code='SO1001'
						

							)
			
			 ";
			//$store_id=$_SESSION['store_code'];
	}
}



// $alphabet="";

// if(isset($_GET['alpha']) ){
// 	$alphabet =" AND p51.item_name like '".$_GET['alpha']."%' ";
// }else{

// 	$alphabet =" AND p51.item_name like 'A%'   ";
// }


	$arrFrames = array();
 $query =    "SELECT 
							TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) AS 'product_style',
							REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '') AS 'product_color',
							p51.product_code,
									(SELECT 
										coalesce(
												sum(
														if(iib.variance_status='approve',
														REPLACE(iib.actual_count,',',''),
														REPLACE(iib.`count`,',',''))
										),0)
								 FROM inventory  iib
							WHERE
							iib.product_code =p51.product_code
							AND
							iib.store_id='".$store_id."'
							
							AND 
							iib.status ='received'
							AND
								(
									iib.`type`='replenish'
									OR
									iib.`type`='stock_transfer'
									OR
									iib.`type`='interbranch'
								
								)
						
							AND
								DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$dateStart."'
							
									) as beginventory,
							

						(select coalesce(
												sum(
														if(iip.variance_status='approve',
														REPLACE(iip.actual_count,',',''),
														REPLACE(iip.`count`,',',''))
										),0) FROM inventory  iip
													WHERE
													iip.product_code =p51.product_code
														AND
														iip.stock_from='".$store_id."'
											
											AND 
											iip.status ='received' 
											AND iip.type='pullout'
											AND
											DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$dateStart."'
									) as pullout,

						(select coalesce(
												sum(
														if(iid.variance_status='approve',
														REPLACE(iid.actual_count,',',''),
														REPLACE(iid.`count`,',','')
														)
										),0) FROM inventory iid
													WHERE
													iid.product_code =p51.product_code
														AND
														iid.stock_from='".$store_id."'
											
											AND 
											iid.status ='received' 
											AND iid.type='damage'
											AND
											DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$dateStart."'
									) as damage,

							(select coalesce(
												sum(
														if( iiso.variance_status='approve',
														REPLACE(iiso.actual_count,',',''),
														REPLACE(iiso.`count`,',','')
														)
										),0) FROM inventory iiso
													WHERE
													iiso.product_code =p51.product_code
														AND
														iiso.stock_from='".$store_id."'
											
											AND 
											iiso.status ='received' 
											AND iiso.type='stock_transfer'
											AND
											DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$dateStart."'
									) as stock_transfer_out,
									

							(select coalesce(
												sum(
														if(iisi.variance_status='approve',
														REPLACE(iisi.actual_count,',',''),
														REPLACE(iisi.`count`,',','')
														)
										),0) FROM inventory  iisi
													WHERE
													iisi.product_code =p51.product_code
														AND
														iisi.store_id='".$store_id."'
											
											AND 
											iisi.status ='received' 
											AND (iisi.type='stock_transfer'
														OR
														iisi.type='replenish'
														)
											AND
											DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
									) as stock_transfer_in_c,


							(select coalesce(
												sum(
														if(iisoc.variance_status='approve',
														REPLACE(iisoc.actual_count,',',''),
														REPLACE(iisoc.`count`,',',''))
										),0) FROM inventory  iisoc
													WHERE
													iisoc.product_code =p51.product_code
														AND
														iisoc.stock_from='".$store_id."'
											
											AND 
											iisoc.status ='received' 
											AND iisoc.type='stock_transfer'
											AND
											DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
									) as stock_transfer_out_c,



							(select coalesce(
												sum(
														if(iiiboc.variance_status='approve',
														REPLACE(iiiboc.actual_count,',',''),
														REPLACE(iiiboc.`count`,',','')
														)
										),0) FROM inventory  iiiboc
													WHERE
													iiiboc.product_code =p51.product_code
														AND
														iiiboc.stock_from='".$store_id."'
											
											AND 
											iiiboc.status ='received' 
											AND iiiboc.type='interbranch'
											
											AND
											DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
									) as interbranch_out_c,
									
									(select coalesce(
												sum(
														if(iinbic.variance_status='approve',
														REPLACE(iinbic.actual_count,',',''),
														REPLACE(iinbic.`count`,',','')
														)
										),0) FROM inventory iinbic
													WHERE
													iinbic.product_code =p51.product_code
														AND
														iinbic.store_id='".$store_id."'
											
											AND 
											iinbic.status ='received' 
											AND iinbic.type='interbranch'
										
											AND
											DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
									) as interbranch_in_c,
							

							(select coalesce(
												sum(
														if(ipc.variance_status='approve',
														REPLACE(ipc.actual_count,',',''),
														REPLACE(ipc.`count`,',','')
														)
										),0) FROM inventory ipc
														WHERE
														ipc.product_code =p51.product_code
															AND
															ipc.stock_from='".$store_id."'
												
												AND 
												ipc.status ='received' 
												AND ipc.type='pullout'
												AND
												DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
										) as pullout_c,
	
							(select coalesce(
												sum(
														if(iidc.variance_status='approve',
														REPLACE(iidc.actual_count,',',''),
														REPLACE(iidc.`count`,',','')
														)
										),0) FROM inventory iidc
														WHERE
														iidc.product_code =p51.product_code
															AND
															iidc.stock_from='".$store_id."'
												
												AND 
												iidc.status ='received' 
												AND iidc.type='damage'
												AND
												DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
										) as damage_c,
										

										(SELECT 
										coalesce(
												sum(
														if(iir.variance_status='approve',
														REPLACE(iir.actual_count,',',''),
														REPLACE(iir.`count`,',','')
														)
										),0)
								 FROM inventory iir
							WHERE
							iir.product_code =p51.product_code
							AND iir.requested='y'
							
							AND 
							iir.status !='received'
							AND
								(
									iir.`type`='replenish'
									OR
									iir.`type`='stock_transfer'
									OR
									iir.`type`='interbranch'
								
								)
						
							AND
								DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
							AND
												DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
							
									) as requested,
						
									(select coalesce(
												sum(
														if(ididc.variance_status='approve',
														REPLACE(ididc.actual_count,',',''),
														REPLACE(ididc.`count`,',','')
														)
										),0) FROM inventory  ididc
														WHERE
														ididc.product_code =p51.product_code
															AND
															ididc.store_id='warehouse_damage'
												
												AND 
												ididc.status ='received' 
												AND ididc.type='damage'
												AND
												DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
										) as damage_i,

										(SELECT coalesce(sum( REPLACE(count,',','')),0) 
												FROM 	inventory_actual_count 
												WHERE  date_start>='".$dateStart."'
												AND		date_end<='".$dateEnd."'
												AND store_audited='".$store_id."'
												) AS actual_count,

												
										(SELECT coalesce(sum( REPLACE(count,',','')),0) FROM inventory itin
										WHERE
											itin.product_code =p51.product_code
															AND
															itin.store_id='".$store_id."'
												
												AND 
												itin.status ='in transit' 
												
												AND
												DATE(DATE_ADD(itin.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(itin.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
										
										) AS transit_in,
										(SELECT coalesce(sum( REPLACE(count,',','')),0) FROM inventory ito
										WHERE
											ito.product_code =p51.product_code
															AND
															ito.stock_from='".$store_id."'
												
												AND 
												ito.status ='in transit' 
												
												AND
												DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$dateStart."'
												AND
												DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$dateEnd."'
										
										) AS transit_out,
										(select coalesce(
												sum(
														if(iiiboc.variance_status='approve',
														REPLACE(iiiboc.actual_count,',',''),
														REPLACE(iiiboc.`count`,',','')
														)
										),0) FROM inventory  iiiboc
													WHERE
													iiiboc.product_code =p51.product_code
														AND
														iiiboc.stock_from='".$store_id."'
											
											AND 
											iiiboc.status ='received' 
											AND iiiboc.type='interbranch'
											
											
												AND
												DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$dateStart."'
									) as interbranch_out,
										(SELECT coalesce(sum( REPLACE(count,',','')),0) FROM inventory ito
										WHERE
											ito.product_code =p51.product_code
															AND
															ito.stock_from='".$store_id."'
												
												AND 
												ito.status ='in transit' 
												
												AND
												DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<'".$dateStart."'
										
										) AS transit_out_past,
										(
											SELECT COALESCE(sum( REPLACE(input_count,',','')),0) FROM inventory_actual_count 
												where date_end<'".$dateStart."' and product_code=p51.product_code and store_audited='".$store_id."'

										) as past_variance

							
					FROM 
							poll_51 p51
					LEFT JOIN 
							inventory ip
						ON
							ip.product_code=p51.product_code 	AND ( store_id ='".$store_id."' OR stock_from='".$store_id."' ) 
					
				
					WHERE
		p51.product_code NOT LIKE 'L0%'
		

	

		GROUP BY 
			p51.product_code
	ORDER BY 
	
		item_name ASC
		
	";

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
		'damage_i',
		
		'actual_count',
		'transit_in',
		'transit_out',
		'interbranch_out',
		'transit_out_past',
		'past_variance'
		
	);

	
	// if( p51.product_code='M100',
	// (
	// 			select count(po_number) FROM `orders_specs` os

	// 					LEFT JOIN orders o ON o.order_id=os.order_id

	// 					WHERE 
	// 						payment='y'
	// 							And (status!='return' OR
	// 								status!='cancelled' OR
	// 								status!='returned' 
	// 								)
	// 							AND (os.product_upgrade=p51.product_code )
								
	// 							AND  date(os.payment_date)>='2020-02-4'
	// 							AND  date(os.payment_date)<='".$dateStart."'
								
							
	// 							".$store_id_sales."



	// ),
	// 		(
	// 			select count(po_number) FROM `orders_specs` os

	// 					LEFT JOIN orders o ON o.order_id=os.order_id

	// 					WHERE 
	// 						payment='y'
	// 							And (status!='return' OR
	// 								status!='cancelled' OR
	// 								status!='returned' 
	// 								)
	// 							AND (product_code=p51.product_code )
								
	// 							AND  date(os.payment_date)>='2020-02-4'
	// 							AND  date(os.payment_date)<='".$dateStart."'
								
							
	// 							".$store_id_sales."



	// 		)
	// ) as sales,
		
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9
		, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21);

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

		$store_idac=$_GET['filterStores'];
	
	}else{
		if($_SESSION['user_login']['userlvl'] == '13' || $_SESSION['user_login']['userlvl'] == '15'|| $_SESSION['user_login']['userlvl'] == '1'){
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
							 and  date_end ='".$dateEnd."'
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

		$arrActualCount[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);

};









?>