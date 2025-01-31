<?php




function GetTotalOnTransit($product_code,$store,$dateStart,$dateEnd){

	global $conn;
	
	
	$arrBegInventory= array();
	
	$query =    "SELECT 
								
								coalesce(sum(ip.count),0),
								date(ip.status_date)
								
								
						FROM 
								poll_51_new p51
						
						LEFT JOIN 
								inventory ip
							ON
								ip.product_code=p51.product_code
						WHERE
								p51.product_code ='".$product_code."'
						AND
							ip.store_id='".$store."'
						
						AND 
							status ='in transit'
						AND
								status_date<='".$dateEnd."'
						
						
							";
	
		$grabParams = array(
		
			'pulloutcount',
			'pulloutdate'
		);
			
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {
	
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2);
	
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
		
	   return $arrFrames[0]["pulloutcount"];
	
	
	}

// 	function GetDailySalesInventory($product_code,$store,$dateStart,$dateEnd){
// 			global $conn;

// $arrDailySales = array();
// 			$query="SELECT count(os.product_code) FROM `orders_specs` os

// 			WHERE 
// 					os.product_code='".$product_code."'
// 					And payment='y'
// 					And (status!='return' OR
// 						status!='cancelled' OR
// 						 status!='returned' 
// 						)
// 					  AND (os.payment_date)>='".$dateStart."'
// 					 AND (os.payment_date)<='".$dateEnd."' ";

// $grabParams = array(
			
// 	'sales'

// );
	
// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $query)) {

// 	mysqli_stmt_execute($stmt);
// 	mysqli_stmt_bind_result($stmt, $result1);

// 	while (mysqli_stmt_fetch($stmt)) {

// 		$tempArray = array();

// 		for ($i=0; $i < sizeOf($grabParams); $i++) { 

// 			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

// 		};

// 		$arrDailySales [] = $tempArray;

// 	};

// 	mysqli_stmt_close($stmt);    
							
// }
// else {

// 	echo mysqli_error($conn);

// };

// return $arrDailySales[0]["sales"];

// 	}


// 	function GetTotalBegInventory($product_code,$store,$dateStart,$dateEnd){

// 		global $conn;
		
		
// 		$arrBegInventory= array();
		
// 	 $query =    "SELECT 
									
// 									coalesce(sum(ip.count),0),
// 									date(ip.status_date),

// 									(select coalesce(sum(`count`),0) FROM inventory 
// 													WHERE
// 													product_code ='".$product_code."'
// 														AND
// 												stock_from='".$store."'
											
// 											AND 
// 												status ='received' 
// 											AND type='pullout'
// 											AND
// 												status_date<='".$dateStart."'
// 									) as pullout,

// 									(select coalesce(sum(`count`),0) FROM inventory 
// 													WHERE
// 													product_code ='".$product_code."'
// 														AND
// 												stock_from='".$store."'
											
// 											AND 
// 												status ='received' 
// 											AND type='damage'
// 											AND
// 												status_date<='".$dateStart."'
// 									) as damage,

// 									(select coalesce(sum(`count`),0) FROM inventory 
// 													WHERE
// 													product_code ='".$product_code."'
// 														AND
// 												stock_from='".$store."'
											
// 											AND 
// 												status ='received' 
// 											AND type='stock_transfer'
// 											AND
// 												status_date<='".$dateStart."'
// 									) as stock_transfer
									
									
// 							FROM 
// 									poll_51_new p51
							
// 							LEFT JOIN 
// 									inventory ip
// 								ON
// 									ip.product_code=p51.product_code
// 							WHERE
// 									p51.product_code ='".$product_code."'
// 							AND
// 								ip.store_id='".$store."'
							
// 							AND 
// 								status ='received'
// 							AND
// 								(
// 									ip.type='replenish'
// 									OR
// 									ip.type='stock_transfer'
// 									OR
// 									ip.type='interbranch'
								
// 								)
						
// 							AND
// 								status_date<='".$dateEnd."'
							
// 								";
		
// 			$grabParams = array(
			
// 				'stock',
// 				'pulloutdate',
// 				'pullout',
// 				'damage',
// 				'stock_transfer'

// 			);
				
// 			$stmt = mysqli_stmt_init($conn);
// 			if (mysqli_stmt_prepare($stmt, $query)) {
		
// 				mysqli_stmt_execute($stmt);
// 				mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);
		
// 				while (mysqli_stmt_fetch($stmt)) {
		
// 					$tempArray = array();
		
// 					for ($i=0; $i < sizeOf($grabParams); $i++) { 
		
// 						$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
		
// 					};
		
// 					$arrBegInventory[] = $tempArray;
		
// 				};
		
// 				mysqli_stmt_close($stmt);    
										
// 			}
// 			else {
		
// 				echo mysqli_error($conn);
		
// 			};



// $beginning_total= $arrBegInventory[0]["stock"]-$arrBegInventory[0]["pullout"]-$arrBegInventory[0]["damage"]-$arrBegInventory[0]["stock_transfer"];


			
// 		   return $beginning_total;
		
		
// 		}


		
function GetTotalOnTransit2($product_code,$store,$dateStart,$dateEnd){

	global $conn;
	
	
	$arrBegInventory= array();
	
	$query =    "SELECT 
								
								coalesce(sum(ip.count),0),
								date(ip.status_date)
								
								
						FROM 
								poll_51_new p51
						
						LEFT JOIN 
								inventory ip
							ON
								ip.product_code=p51.product_code
						WHERE
								p51.product_code ='".$product_code."'
						AND
							ip.stock_from='".$store."'
						
						AND 
							status ='in transit'
						AND
								status_date<='".$dateEnd."'
						
						
							";
	
		$grabParams = array(
		
			'pulloutcount',
			'pulloutdate'
		);
			
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {
	
			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2);
	
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
		
	   return $arrFrames[0]["pulloutcount"];
	
	
	}



	function stockChecker($product_code,$store_id){

		global $conn;



		$query=$arrFrames = array();
		$query =    "SELECT 
					sum(ip.`count`) 
,
				CASE
                    WHEN ip.store_id = 'warehouse' THEN 'warehouse'
					WHEN ip.store_id = 'hq' THEN 'Sunnies HQ'
					WHEN ip.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN ip.store_id = 'warehouse_qa' THEN 'warehouse QA'
					WHEN ip.store_id = 'manufacturer' THEN 'manufacturer'
					WHEN sls.store_name != '' THEN LOWER(sls.store_name)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE ''
				END AS store_name_to,
				ip.stock_from,
				CASE
					WHEN ip.stock_from = 'warehouse' THEN 'warehouse'
					WHEN ip.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN ip.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN ip.stock_from = 'warehouse_qa' THEN 'warehouse QA'
					WHEN ip.stock_from = 'manufacturer' THEN 'manufacturer'
					WHEN sl.store_name != '' THEN LOWER(sl.store_name)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
                p51.item_name,
                p51.product_code,
											(SELECT 
												coalesce(
														sum(
																if(iib.variance_status='approve',
																iib.actual_count,
																iib.`count`)
												),0)
										 FROM inventory  iib
									WHERE
									iib.product_code =p51.product_code
									AND
									iib.store_id=store_name_to
									
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
										date(iib.status_date)<'".$dateStart."'
									
											) as beginventory,
									
		
								(select coalesce(
														sum(
																if(iip.variance_status='approve',
																iip.actual_count,
																iip.`count`)
												),0) FROM inventory  iip
															WHERE
															iip.product_code =p51.product_code
																AND
																iip.store_id=store_name_from
													
													AND 
													iip.status ='received' 
													AND iip.type='pullout'
													AND
													date(iip.status_date)<'".$dateStart."'
											) as pullout,
		
								(select coalesce(
														sum(
																if(iid.variance_status='approve',
																iid.actual_count,
																iid.`count`)
												),0) FROM inventory iid
															WHERE
															iid.product_code =p51.product_code
																AND
																iid.store_id=store_name_from
													
													AND 
													iid.status ='received' 
													AND iid.type='damage'
													AND
													date(iid.status_date)<'".$dateStart."'
											) as damage,
		
									(select coalesce(
														sum(
																if( iiso.variance_status='approve',
																iiso.actual_count,
																iiso.`count`)
												),0) FROM inventory iiso
															WHERE
															iiso.product_code =p51.product_code
																AND
																iiso.store_id=store_name_from
													
													AND 
													iiso.status ='received' 
													AND iiso.type='stock_transfer'
													AND
													date(iiso.status_date)<'".$dateStart."'
											) as stock_transfer_out,
											
		
									(select coalesce(
														sum(
																if(iisi.variance_status='approve',
																iisi.actual_count,
																iisi.`count`)
												),0) FROM inventory  iisi
															WHERE
															iisi.product_code =p51.product_code
																AND
																iisi.store_id=store_name_to
													
													AND 
													iisi.status ='received' 
													AND (iisi.type='stock_transfer'
																OR
																iisi.type='replenish'
																)
													AND
													date(iisi.status_date)>='".$dateStart."'
														AND
														date(iisi.status_date)<='".$dateEnd."'
											) as stock_transfer_in_c,
		
		
									(select coalesce(
														sum(
																if(iisoc.variance_status='approve',
																iisoc.actual_count,
																iisoc.`count`)
												),0) FROM inventory  iisoc
															WHERE
															iisoc.product_code =p51.product_code
																AND
																iisoc.store_id=store_name_from
													
													AND 
													iisoc.status ='received' 
													AND iisoc.type='stock_transfer'
													AND
													date(iisoc.status_date)>='".$dateStart."'
														AND
														date(iisoc.status_date)<='".$dateEnd."'
											) as stock_transfer_out_c,
		
		
		
									(select coalesce(
														sum(
																if(iiiboc.variance_status='approve',
																iiiboc.actual_count,
																iiiboc.`count`)
												),0) FROM inventory  iiiboc
															WHERE
															iiiboc.product_code =p51.product_code
																AND
																iiiboc.store_id=store_name_from
													
													AND 
													iiiboc.status ='received' 
													AND iiiboc.type='interbranch'
													
													AND
													date(iiiboc.status_date)>='".$dateStart."'
														AND
														date(iiiboc.status_date)<='".$dateEnd."'
											) as interbranch_out_c,
											
											(select coalesce(
														sum(
																if(iinbic.variance_status='approve',
																iinbic.actual_count,
																iinbic.`count`)
												),0) FROM inventory iinbic
															WHERE
															iinbic.product_code =p51.product_code
																AND
																iinbic.store_id=store_name_to
													
													AND 
													iinbic.status ='received' 
													AND iinbic.type='interbranch'
												
													AND
													date(iinbic.status_date)>='".$dateStart."'
														AND
														date(iinbic.status_date)<='".$dateEnd."'
											) as interbranch_in_c	,
									
		
									(select coalesce(
														sum(
																if(ipc.variance_status='approve',
																ipc.actual_count,
																ipc.`count`)
												),0) FROM inventory ipc
																WHERE
																ipc.product_code =p51.product_code
																	AND
																	ipc.store_id=store_name_from
														
														AND 
														ipc.status ='received' 
														AND ipc.type='pullout'
														AND
														date(ipc.status_date)>='".$dateStart."'
														AND
														date(ipc.status_date)<='".$dateEnd."'
												) as pullout_c,
			
									(select coalesce(
														sum(
																if(iidc.variance_status='approve',
																iidc.actual_count,
																iidc.`count`)
												),0) FROM inventory iidc
																WHERE
																iidc.product_code =p51.product_code
																	AND
																	iidc.store_id=store_name_from
														
														AND 
														iidc.status ='received' 
														AND iidc.type='damage'
														AND
														date(iidc.status_date)>='".$dateStart."'
														AND
														date(iidc.status_date)<='".$dateEnd."'
												) as damage_c,
												
		
												(SELECT 
												coalesce(
														sum(
																if(iir.variance_status='approve',
																iir.actual_count,
																iir.`count`)
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
										date(iir.status_date)<='".$dateEnd."'
									AND
														date(iir.status_date)>='".$dateStart."'
									
											) as requested,
								
											(select coalesce(
														sum(
																if(ididc.variance_status='approve',
																ididc.actual_count,
																ididc.`count`)
												),0) FROM inventory  ididc
																WHERE
																ididc.product_code =p51.product_code
																	AND
																	ididc.store_id='warehouse_damage'
														
														AND 
														ididc.status ='received' 
														AND ididc.type='damage'
														AND
														date(ididc.status_date)>='".$dateStart."'
														AND
														date(ididc.status_date)<='".$dateEnd."'
												) as damage_i,
		
												(SELECT coalesce(sum(count),0) 
														FROM 	inventory_actual_count 
														WHERE  date_start>='".$dateStart."'
														AND		date_end<='".$dateEnd."'
														AND store_audited='".$store_id."'
														) AS actual_count
		
						
from inventory ip

LEFT JOIN stores_locations sl
								ON sl.store_id = ip.stock_from
							LEFT JOIN stores_locations sls
								ON sls.store_id = ip.store_id
							LEFT JOIN labs_locations ll
								ON ll.lab_id = ip.stock_from
							LEFT JOIN labs_locations lls
								ON lls.lab_id = ip.store_id
								   LEFT JOIN poll_51 p51 
								ON p51.product_code=ip.product_code
								
							 WHERE p51.product_code ='".$product_code."'
								GROUP by ip.product_code,stock_from,ip.store_id
				
			";



// ,
		
														
// 												(SELECT coalesce(sum(count),0) FROM inventory itin
// 												WHERE
// 													itin.product_code =p51.product_code
// 																	AND
// 																	itin.store_id=store_name_to
														
// 														AND 
// 														itin.status ='in transit' 
														
// 														AND
// 														date(itin.status_date)>='".$dateStart."'
// 														AND
// 														date(itin.status_date)<='".$dateEnd."'
												
// 												) AS transit_in,
// 												(SELECT coalesce(sum(count),0) FROM inventory ito
// 												WHERE
// 													ito.product_code =p51.product_code
// 																	AND
// 																	ito.store_id=store_name_from
														
// 														AND 
// 														ito.status ='in transit' 
														
// 														AND
// 														date(ito.status_date)>='".$dateStart."'
// 														AND
// 														date(ito.status_date)<='".$dateEnd."'
												
// 												) AS transit_out
	}
?>