<?php 
	set_time_limit(0);
	ini_set('memory_limit', '3G');
    
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

function WarehouseChecker_smr($product_Code,$date_start,$date_end) {

    global $conn;
                $datenow=date('Y-m-d');

                $grabInvParams= array(
                            "store_name",
                            "item_name",
                            "product_code",
                            "beg_inventory",
                           "stock_transfer_in_c",
                            "stock_transfer_out_c",
                            "interbranch_out_c",
                            "interbranch_in_c",
                            "pullout_c",
                            "damage_c",
                            "damage_i",
                            "sales",
                            "number",
                            "transit_out",
                            "requested",
                            "transit_in",
                            "transit_out_c"
                           
                         );


    $arrInvLook=array();
     $query=" SELECT 

                'warehouse',
                        p51.item_name,
                        p51.item_code,
                        coalesce(pactual.input_count,0) as beginventory,
                     (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisi
                                WHERE
                                iisi.product_code =p51.item_code
                                AND
                                iisi.store_id='warehouse'
                            
                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                                OR
                                iisi.type='replenish'
                                )
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_in_c,


                    (select coalesce(
                            sum(
                                if(iisoc.variance_status='approve',
                                REPLACE(iisoc.actual_count,',',''),
                                REPLACE( iisoc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisoc
                                WHERE
                                iisoc.product_code =p51.item_code
                                AND
                                iisoc.stock_from='warehouse'
                            
                            AND 
                            iisoc.status ='received' 
                            AND iisoc.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_out_c,

                          (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iiiboc
                                WHERE
                                iiiboc.product_code =p51.item_code
                                AND
                                iiiboc.stock_from='warehouse'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_out_c,
                        
                        (select coalesce(
                            sum(
                                if(iinbic.variance_status='approve',
                                REPLACE( iinbic.actual_count,',',''),
                                REPLACE( iinbic.`count`,',','')
                                )
                        ),0) FROM inventory_face iinbic
                                WHERE
                                iinbic.product_code =p51.item_code
                                AND
                                iinbic.store_id='warehouse'
                            
                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'
                        
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_in_c ,
                    

                    (select coalesce(
                            sum(
                                if(ipc.variance_status='approve',
                                REPLACE(  ipc.actual_count,',',''),
                                REPLACE(  ipc.`count`,',','')
                                )
                        ),0) FROM inventory_face ipc
                                WHERE
                                ipc.product_code =p51.item_code
                                    AND
                                    ipc.stock_from='warehouse'
                            
                            AND 
                            ipc.status ='received' 
                            AND ipc.type='pullout'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as pullout_c,

                    (select coalesce(
                            sum(
                                if(iidc.variance_status='approve',
                                REPLACE(  iidc.actual_count,',',''),
                                REPLACE(  iidc.`count`,',','')
                                )
                        ),0) FROM inventory_face iidc
                                WHERE
                                iidc.product_code =p51.item_code
                                    AND
                                    iidc.stock_from='warehouse'
                            
                            AND 
                            iidc.status ='received' 
                            AND iidc.type='damage'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_c,
                
                        (select coalesce(
                            sum(
                                if(ididc.variance_status='approve',
                                REPLACE(  ididc.actual_count,',',''),
                                REPLACE( ididc.`count`,',','')
                                )
                        ),0) FROM inventory_face  ididc
                                WHERE
                                ididc.product_code =p51.item_code
                                    AND
                                    ididc.store_id='warehouse'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        '0' as sales,
                'none' as number,
                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='warehouse'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                                    
                                                    ) AS transit_out,



                                                    (SELECT 
										coalesce(
												sum(
														if(iir.variance_status='approve',
														REPLACE(iir.actual_count,',',''),
														REPLACE(iir.`count`,',','')
														)
										),0)
								 FROM inventory_face iir
							WHERE
							iir.product_code =p51.item_code
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
								DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
							AND
												DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>=pactual.date_end
							
									) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.store_id='warehouse'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,

                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='warehouse'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c
								
                                    
              

            FROM    poll_51_face_new p51
            LEFT JOIN inventory_actual_count_face pactual ON pactual.store_audited='warehouse'AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_face iacx 
                                WHERE iacx.store_audited='warehouse'
                                AND iacx.product_code='".$product_Code."'
                            AND iacx.date_end<'".$date_start."' 
                        )   and pactual.product_code='".$product_Code."'
            WHERE p51.item_code='".$product_Code."'
        
            group by p51.item_code";
         

            $stmtBig = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {
        
                mysqli_stmt_execute($stmtBig);
                mysqli_stmt_close($stmtBig);
        
            }
            else {
        
                echo mysqli_error($conn);
        
            }
        

                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17 );
                
                    while (mysqli_stmt_fetch($stmt)) {
                
                        $tempArray = array();
                
                        for ($i=0; $i < sizeOf($grabInvParams); $i++) { 
                
                            $tempArray[$grabInvParams[$i]] = ${'result' . ($i+1)};
                
                        };
                
                        $arrInvLook[] = $tempArray;
                
                    };
                
                    mysqli_stmt_close($stmt);    
                                            
                }
                else {
                
                    echo mysqli_error($conn);	
                
                };


                // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

                // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
                // echo "<pre>";
                // print_r($arrInvLook);
                // echo "</pre>";
        return $arrInvLook;

}
    

    

function WarehouseChecker_smr_damage($product_Code,$date_start,$date_end) {

    global $conn;
                $datenow=date('Y-m-d');
    
                $grabInvParams= array(
                            "store_name",
                            "item_name",
                            "product_code",
                            "beg_inventory",
                           "stock_transfer_in_c",
                            "stock_transfer_out_c",
                            "interbranch_out_c",
                            "interbranch_in_c",
                            "pullout_c",
                            "damage_c",
                            "damage_i",
                            "sales",
                            "number",
                            "transit_out",
                            "requested",
                            "transit_in",
                            "transit_out_c"
                           
                         );
    
    
    $arrInvLook=array();
     $query=" SELECT 
    
                'warehouse_damage',
                        p51.item_name,
                        p51.item_code,
                        coalesce(pactual.input_count,0) as beginventory,
                     (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisi
                                WHERE
                                iisi.product_code =p51.item_code
                                AND
                                iisi.store_id='warehouse_damage'
                            
                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                                OR
                                iisi.type='replenish'
                                )
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_in_c,
    
    
                    (select coalesce(
                            sum(
                                if(iisoc.variance_status='approve',
                                REPLACE(iisoc.actual_count,',',''),
                                REPLACE( iisoc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisoc
                                WHERE
                                iisoc.product_code =p51.item_code
                                AND
                                iisoc.stock_from='warehouse_damage'
                            
                            AND 
                            iisoc.status ='received' 
                            AND iisoc.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_out_c,
    
                          (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iiiboc
                                WHERE
                                iiiboc.product_code =p51.item_code
                                AND
                                iiiboc.stock_from='warehouse_damage'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_out_c,
                        
                        (select coalesce(
                            sum(
                                if(iinbic.variance_status='approve',
                                REPLACE( iinbic.actual_count,',',''),
                                REPLACE( iinbic.`count`,',','')
                                )
                        ),0) FROM inventory_face iinbic
                                WHERE
                                iinbic.product_code =p51.item_code
                                AND
                                iinbic.store_id='warehouse_damage'
                            
                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'
                        
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_in_c ,
                    
    
                    (select coalesce(
                            sum(
                                if(ipc.variance_status='approve',
                                REPLACE(  ipc.actual_count,',',''),
                                REPLACE(  ipc.`count`,',','')
                                )
                        ),0) FROM inventory_face ipc
                                WHERE
                                ipc.product_code =p51.item_code
                                    AND
                                    ipc.stock_from='warehouse_damage'
                            
                            AND 
                            ipc.status ='received' 
                            AND ipc.type='pullout'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as pullout_c,
    
                    (select coalesce(
                            sum(
                                if(iidc.variance_status='approve',
                                REPLACE(  iidc.actual_count,',',''),
                                REPLACE(  iidc.`count`,',','')
                                )
                        ),0) FROM inventory_face iidc
                                WHERE
                                iidc.product_code =p51.item_code
                                    AND
                                    iidc.stock_from='warehouse_damage'
                            
                            AND 
                            iidc.status ='received' 
                            AND iidc.type='damage'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_c,
                
                        (select coalesce(
                            sum(
                                if(ididc.variance_status='approve',
                                REPLACE(  ididc.actual_count,',',''),
                                REPLACE( ididc.`count`,',','')
                                )
                        ),0) FROM inventory_face  ididc
                                WHERE
                                ididc.product_code =p51.item_code
                                    AND
                                    ididc.store_id='warehouse_damage'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        '0' as sales,
                'none' as number,
                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='warehouse_damage'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                                    
                                                    ) AS transit_out,
    
    
    
                                                    (SELECT 
                                        coalesce(
                                                sum(
                                                        if(iir.variance_status='approve',
                                                        REPLACE(iir.actual_count,',',''),
                                                        REPLACE(iir.`count`,',','')
                                                        )
                                        ),0)
                                 FROM inventory_face iir
                            WHERE
                            iir.product_code =p51.item_code
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
                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                            AND
                                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            
                                    ) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.store_id='warehouse_damage'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,
    
                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='warehouse_damage'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c
                                
                                    
              
    
            FROM    poll_51_face_new p51
            LEFT JOIN inventory_actual_count_face pactual ON pactual.store_audited='warehouse_damage'AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_face iacx 
                                WHERE iacx.store_audited='warehouse_damage'
                                AND iacx.product_code='".$product_Code."'
                            AND iacx.date_end<'".$date_start."' 
                        )  and pactual.product_code='".$product_Code."'
            WHERE p51.item_code='".$product_Code."'
          
            group by p51.item_code";
         
    
            $stmtBig = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {
        
                mysqli_stmt_execute($stmtBig);
                mysqli_stmt_close($stmtBig);
        
            }
            else {
        
                echo mysqli_error($conn);
        
            }
        
    
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17 );
                
                    while (mysqli_stmt_fetch($stmt)) {
                
                        $tempArray = array();
                
                        for ($i=0; $i < sizeOf($grabInvParams); $i++) { 
                
                            $tempArray[$grabInvParams[$i]] = ${'result' . ($i+1)};
                
                        };
                
                        $arrInvLook[] = $tempArray;
                
                    };
                
                    mysqli_stmt_close($stmt);    
                                            
                }
                else {
                
                    echo mysqli_error($conn);	
                
                };
    
    
                // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];
    
                // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
                // echo "<pre>";
                // print_r($arrInvLook);
                // echo "</pre>";
        return $arrInvLook;
    
  }

function WarehouseChecker_smr_qa($product_Code,$date_start,$date_end) {

    global $conn;
                $datenow=date('Y-m-d');
    
                $grabInvParams= array(
                            "store_name",
                            "item_name",
                            "product_code",
                            "beg_inventory",
                           "stock_transfer_in_c",
                            "stock_transfer_out_c",
                            "interbranch_out_c",
                            "interbranch_in_c",
                            "pullout_c",
                            "damage_c",
                            "damage_i",
                            "sales",
                            "number",
                            "transit_out",
                            "requested",
                            "transit_in",
                            "transit_out_c"
                           
                         );
    
    
    $arrInvLook=array();
     $query=" SELECT 
    
                'warehouse_qa',
                        p51.item_name,
                        p51.item_code,
                        coalesce(pactual.input_count,0) as beginventory,
                     (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisi
                                WHERE
                                iisi.product_code =p51.item_code
                                AND
                                iisi.store_id='warehouse_qa'
                            
                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                                OR
                                iisi.type='replenish'
                                )
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_in_c,
    
    
                    (select coalesce(
                            sum(
                                if(iisoc.variance_status='approve',
                                REPLACE(iisoc.actual_count,',',''),
                                REPLACE( iisoc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisoc
                                WHERE
                                iisoc.product_code =p51.item_code
                                AND
                                iisoc.stock_from='warehouse_qa'
                            
                            AND 
                            iisoc.status ='received' 
                            AND iisoc.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_out_c,
    
                          (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iiiboc
                                WHERE
                                iiiboc.product_code =p51.item_code
                                AND
                                iiiboc.stock_from='warehouse_qa'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_out_c,
                        
                        (select coalesce(
                            sum(
                                if(iinbic.variance_status='approve',
                                REPLACE( iinbic.actual_count,',',''),
                                REPLACE( iinbic.`count`,',','')
                                )
                        ),0) FROM inventory_face iinbic
                                WHERE
                                iinbic.product_code =p51.item_code
                                AND
                                iinbic.store_id='warehouse_qa'
                            
                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'
                        
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_in_c ,
                    
    
                    (select coalesce(
                            sum(
                                if(ipc.variance_status='approve',
                                REPLACE(  ipc.actual_count,',',''),
                                REPLACE(  ipc.`count`,',','')
                                )
                        ),0) FROM inventory_face ipc
                                WHERE
                                ipc.product_code =p51.item_code
                                    AND
                                    ipc.stock_from='warehouse_qa'
                            
                            AND 
                            ipc.status ='received' 
                            AND ipc.type='pullout'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as pullout_c,
    
                    (select coalesce(
                            sum(
                                if(iidc.variance_status='approve',
                                REPLACE(  iidc.actual_count,',',''),
                                REPLACE(  iidc.`count`,',','')
                                )
                        ),0) FROM inventory_face iidc
                                WHERE
                                iidc.product_code =p51.item_code
                                    AND
                                    iidc.stock_from='warehouse_qa'
                            
                            AND 
                            iidc.status ='received' 
                            AND iidc.type='damage'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_c,
                
                        (select coalesce(
                            sum(
                                if(ididc.variance_status='approve',
                                REPLACE(  ididc.actual_count,',',''),
                                REPLACE( ididc.`count`,',','')
                                )
                        ),0) FROM inventory_face  ididc
                                WHERE
                                ididc.product_code =p51.item_code
                                    AND
                                    ididc.store_id='warehouse_qa'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        '0' as sales,
                'none' as number,
                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='warehouse_qa'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                                    
                                                    ) AS transit_out,
    
    
    
                                                    (SELECT 
                                        coalesce(
                                                sum(
                                                        if(iir.variance_status='approve',
                                                        REPLACE(iir.actual_count,',',''),
                                                        REPLACE(iir.`count`,',','')
                                                        )
                                        ),0)
                                 FROM inventory_face iir
                            WHERE
                            iir.product_code =p51.item_code
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
                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                            AND
                                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>=pactual.date_end
                            
                                    ) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.store_id='warehouse_qa'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,
    
                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='warehouse_qa'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c
                                
                                    
              
    
            FROM    poll_51_face_new p51
            LEFT JOIN inventory_actual_count_face pactual ON pactual.store_audited='warehouse_qa'AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_face iacx 
                                WHERE iacx.store_audited='warehouse_qa'
                                AND iacx.product_code='".$product_Code."'
                            AND iacx.date_end<'".$date_start."' 
                        )  and pactual.product_code='".$product_Code."'
            WHERE p51.item_code='".$product_Code."'
          
            group by p51.item_code";
         
    
            $stmtBig = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {
        
                mysqli_stmt_execute($stmtBig);
                mysqli_stmt_close($stmtBig);
        
            }
            else {
        
                echo mysqli_error($conn);
        
            }
        
    
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17 );
                
                    while (mysqli_stmt_fetch($stmt)) {
                
                        $tempArray = array();
                
                        for ($i=0; $i < sizeOf($grabInvParams); $i++) { 
                
                            $tempArray[$grabInvParams[$i]] = ${'result' . ($i+1)};
                
                        };
                
                        $arrInvLook[] = $tempArray;
                
                    };
                
                    mysqli_stmt_close($stmt);    
                                            
                }
                else {
                
                    echo mysqli_error($conn);	
                
                };
    
    
                // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];
    
                // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
                // echo "<pre>";
                // print_r($arrInvLook);
                // echo "</pre>";
        return $arrInvLook;
    
 }






 function storeChecker_smr($product_code,$store_id,$date_start,$date_end){

    global $conn;
                $datenow=date('Y-m-d');

                $grabInvParams= array(
                            "store_name",
                            "item_name",
                            "product_code",
                            "beg_inventory",
                           "stock_transfer_in_c",
                            "stock_transfer_out_c",
                            "interbranch_out_c",
                            "interbranch_in_c",
                            "pullout_c",
                            "damage_c",
                            "damage_i",
                            "sales",
                            "number",
                            "transit_out",
                            "requested",
                            "transit_in",
                            "transit_out_c"
                           
                         );
                         $datenow=date('Y-m-d');


                         if($store_id=='787'  || $store_id=='788' || $store_id=='789' || $store_id=='1017' || $store_id=='1019' || $store_id=='1021'|| $store_id=='889'){
                                     $reRoute=" AND  date(os.payment_date)>='2020-06-25' 
                                      and  os.product_upgrade!='sunnies_studios' ";
                     
                                     // AND os.product_upgrade!='PL0010'
                             }
                             
                             else{
                                 $reRoute=" 
                               
                                             
                                      ";
                             }
                     
                     
                             // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
                             if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
                             || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                                                 || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code) 
                                                  || preg_match("/P1/i",$product_code) 
                                                 || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)  
                                                  || preg_match("/SC/i",$product_code)
                                                   || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                                                   || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) 
                                                   || preg_match("/SSGWPCB/i",$product_code)     || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code) 
                                                    || preg_match("/TB0/i",$product_code)     || preg_match("/SGC/i",$product_code)    || preg_match("/DS/i",$product_code)   
                                                     || preg_match("/ST/i",$product_code)      || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code) 
                                                      || preg_match("/CPV/i",$product_code)   || preg_match("/SWS/i",$product_code)    || preg_match("/SMHP/i",$product_code) 
                                                    || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code)   || preg_match("/SMZ/i",$product_code) 
                                                    || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code) || preg_match("/SS00/i",$product_code) 
                                                    || preg_match("/SPHC/i",$product_code) || preg_match("/NT0/i",$product_code)  || preg_match("/GSOM/i",$product_code) 
                                                    || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C100/i",$product_code) 
                                                   ){
                                                     
                                      $carekits=" AND os.product_upgrade ";
                                      $condition1="  ";
                     
                             }else{
                                $condition1=" and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
                                  $carekits=" AND os.product_code ";
                     
                             }
                            //  OR ( o.store_id='".$store_id."'  and dispatch_type='packaging')
               $que_sales="SELECT count(po_number)
                                                    
                                            
                FROM `orders_sunnies_studios` os
                
                LEFT JOIN orders_studios o ON o.order_id=os.order_id
                
                WHERE 
                payment='y'
                And os.status NOT IN ('return','cancelled','returned','failed' )
                AND date(os.payment_date)>='2020-02-4'
                ". $condition1." 
                AND  date(os.payment_date)>='".$date_start."'
                AND  date(os.payment_date)<='".$date_end."'
               ". $carekits."  ='".$product_code."'
              
                AND  origin_branch='".$store_id."'
                ";
                $grabInvParamssales=array("sales");
                $stmt2 = mysqli_stmt_init($conn);
									if (mysqli_stmt_prepare($stmt2, $que_sales)) {
							
										mysqli_stmt_execute($stmt2);
										mysqli_stmt_bind_result($stmt2, $result1);
							
										while (mysqli_stmt_fetch($stmt2)) {
							
											$tempArray = array();
							
											for ($i=0; $i < sizeOf($grabInvParamssales); $i++) { 
							
												$tempArray[$grabInvParamssales[$i]] = ${'result' . ($i+1)};
							
											};
							
											$arrSalesData[] = $tempArray;
							
										};
							
										mysqli_stmt_close($stmt2);    
																
									}
									else {
							
										echo mysqli_error($conn);	
							
									};


    $arrInvLook=array();
       $query=" SELECT 

                        sls.store_name_proper,
                        p51.item_name,
                        p51.item_code,
                        coalesce(pactual.input_count,0) as beginventory,
                     (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisi
                                WHERE
                                iisi.product_code =p51.item_code
                                AND
                                iisi.store_id='".$store_id."'
                            
                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                                OR
                                iisi.type='replenish'
                                )
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_in_c,


                    (select coalesce(
                            sum(
                                if(iisoc.variance_status='approve',
                                REPLACE(iisoc.actual_count,',',''),
                                REPLACE( iisoc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisoc
                                WHERE
                                iisoc.product_code =p51.item_code
                                AND
                                iisoc.stock_from='".$store_id."'
                            
                            AND 
                            iisoc.status ='received' 
                            AND iisoc.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_out_c,

                          (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iiiboc
                                WHERE
                                iiiboc.product_code =p51.item_code
                                AND
                                iiiboc.stock_from='".$store_id."'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_out_c,
                        
                        (select coalesce(
                            sum(
                                if(iinbic.variance_status='approve',
                                REPLACE( iinbic.actual_count,',',''),
                                REPLACE( iinbic.`count`,',','')
                                )
                        ),0) FROM inventory_face iinbic
                                WHERE
                                iinbic.product_code =p51.item_code
                                AND
                                iinbic.store_id='".$store_id."'
                            
                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'
                        
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_in_c ,
                    

                    (select coalesce(
                            sum(
                                if(ipc.variance_status='approve',
                                REPLACE(  ipc.actual_count,',',''),
                                REPLACE(  ipc.`count`,',','')
                                )
                        ),0) FROM inventory_face ipc
                                WHERE
                                ipc.product_code =p51.item_code
                                    AND
                                    ipc.stock_from='".$store_id."'
                            
                            AND 
                            ipc.status ='received' 
                            AND ipc.type='pullout'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as pullout_c,

                    (select coalesce(
                            sum(
                                if(iidc.variance_status='approve',
                                REPLACE(  iidc.actual_count,',',''),
                                REPLACE(  iidc.`count`,',','')
                                )
                        ),0) FROM inventory_face iidc
                                WHERE
                                iidc.product_code =p51.item_code
                                    AND
                                    iidc.stock_from='".$store_id."'
                            
                            AND 
                            iidc.status ='received' 
                            AND iidc.type='damage'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_c,
                
                        (select coalesce(
                            sum(
                                if(ididc.variance_status='approve',
                                REPLACE(  ididc.actual_count,',',''),
                                REPLACE( ididc.`count`,',','')
                                )
                        ),0) FROM inventory_face  ididc
                                WHERE
                                ididc.product_code =p51.item_code
                                    AND
                                    ididc.store_id='".$store_id."'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        coalesce('".$arrSalesData[0]['sales']."',0) as sales,
                'none' as number,
                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                                    
                                                    ) AS transit_out,



                                                    (SELECT 
										coalesce(
												sum(
														if(iir.variance_status='approve',
														REPLACE(iir.actual_count,',',''),
														REPLACE(iir.`count`,',','')
														)
										),0)
								 FROM inventory_face iir
							WHERE
							iir.product_code =p51.item_code
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
								DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
							AND
												DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>='".$date_start."'
							
									) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.store_id='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,

                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c
								
                                    
              

            FROM    poll_51_face_new p51
          LEFT JOIN inventory_actual_count_face pactual ON pactual.store_audited='".$store_id."' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_face iacx 
                                WHERE iacx.store_audited='".$store_id."'
                                AND iacx.product_code='".$product_code."'
                            AND iacx.date_end<'".$date_start."' 
                        ) AND pactual.product_code='".$product_code."'
                        LEFT JOIN store_codes_studios sls on sls.store_code='".$store_id."' 
            WHERE p51.item_code='".$product_code."'
        
            group by p51.item_code";
         

            $stmtBig = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {
        
                mysqli_stmt_execute($stmtBig);
                mysqli_stmt_close($stmtBig);
        
            }
            else {
        
                echo mysqli_error($conn);
        
            }
        

                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17 );
                
                    while (mysqli_stmt_fetch($stmt)) {
                
                        $tempArray = array();
                
                        for ($i=0; $i < sizeOf($grabInvParams); $i++) { 
                
                            $tempArray[$grabInvParams[$i]] = ${'result' . ($i+1)};
                
                        };
                
                        $arrInvLook[] = $tempArray;
                
                    };
                
                    mysqli_stmt_close($stmt);    
                                            
                }
                else {
                
                    echo mysqli_error($conn);	
                
                };


                // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

                // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
                // echo "<pre>";
                // print_r($arrInvLook);
                // echo "</pre>";
        return $arrInvLook;

}






function storeChecker_smr_VS($product_code,$store_id,$date_start,$date_end){

    global $conn;
                $datenow=date('Y-m-d');

                $grabInvParams= array(
                            "store_name",
                            "item_name",
                            "product_code",
                            "beg_inventory",
                           "stock_transfer_in_c",
                            "stock_transfer_out_c",
                            "interbranch_out_c",
                            "interbranch_in_c",
                            "pullout_c",
                            "damage_c",
                            "damage_i",
                            "sales",
                            "number",
                            "transit_out",
                            "requested",
                            "transit_in",
                            "transit_out_c"
                           
                         );
                         $datenow=date('Y-m-d');


                         if($store_id=='787'  || $store_id=='788' || $store_id=='789' || $store_id=='1017' || $store_id=='1019' || $store_id=='1021'|| $store_id=='889'){
                                     $reRoute=" AND  date(os.payment_date)>='2020-06-25' 
                                      and  os.product_upgrade!='sunnies_studios' ";
                     
                                     // AND os.product_upgrade!='PL0010'
                             }
                            
                             else{
                                 $reRoute=" 
                                
                                      ";
                             }
                     
                     
                             // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
                             if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
                             || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                                                 || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code) 
                                                  || preg_match("/P1/i",$product_code) 
                                                 || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)  
                                                  || preg_match("/SC/i",$product_code)
                                                   || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                                                   || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) 
                                                   || preg_match("/SSGWPCB/i",$product_code)     || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code) 
                                                    || preg_match("/TB0/i",$product_code)     || preg_match("/SGC/i",$product_code)    || preg_match("/DS/i",$product_code)   
                                                     || preg_match("/ST/i",$product_code)      || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code) 
                                                      || preg_match("/CPV/i",$product_code)   || preg_match("/SWS/i",$product_code)    || preg_match("/SMHP/i",$product_code) 
                                                    || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code)   || preg_match("/SMZ/i",$product_code) 
                                                    || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code) || preg_match("/SS00/i",$product_code) 
                                                    || preg_match("/SPHC/i",$product_code) || preg_match("/NT0/i",$product_code)  || preg_match("/GSOM/i",$product_code) 
                                                    || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C100/i",$product_code) 
                                                   ){
                                                     
                                      $carekits=" AND os.product_upgrade ";
                                      $condition1="  ";
                     
                             }else{
                                $condition1=" and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
                                  $carekits=" AND os.product_code ";
                     
                             }
                            //  OR ( o.store_id='".$store_id."'  and dispatch_type='packaging')
                $que_sales="SELECT count(po_number)
                                                    
                                            
                FROM `orders_specs` os
                
                LEFT JOIN orders o ON o.order_id=os.order_id
                
                WHERE 
                payment='y'
                And os.status NOT IN ('return','cancelled','returned','failed' )
                AND date(os.payment_date)>='2020-02-4'
                ". $condition1." 
                AND  date(os.payment_date)>='".$date_start."'
                AND  date(os.payment_date)<='".$date_end."'
               ". $carekits."  ='".$product_code."'
                ".$reRoute." 
                AND  origin_branch='".$store_id."'
                ";
                $grabInvParamssales=array("sales");
                $stmt2 = mysqli_stmt_init($conn);
									if (mysqli_stmt_prepare($stmt2, $que_sales)) {
							
										mysqli_stmt_execute($stmt2);
										mysqli_stmt_bind_result($stmt2, $result1);
							
										while (mysqli_stmt_fetch($stmt2)) {
							
											$tempArray = array();
							
											for ($i=0; $i < sizeOf($grabInvParamssales); $i++) { 
							
												$tempArray[$grabInvParamssales[$i]] = ${'result' . ($i+1)};
							
											};
							
											$arrSalesData[] = $tempArray;
							
										};
							
										mysqli_stmt_close($stmt2);    
																
									}
									else {
							
										echo mysqli_error($conn);	
							
									};


    $arrInvLook=array();
       $query=" SELECT 

                        sls.store_name_proper,
                        p51.item_name,
                        p51.item_code,
                        coalesce(pactual.input_count,0) as beginventory,
                     (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisi
                                WHERE
                                iisi.product_code =p51.item_code
                                AND
                                iisi.store_id='".$store_id."'
                            
                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                                OR
                                iisi.type='replenish'
                                )
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_in_c,


                    (select coalesce(
                            sum(
                                if(iisoc.variance_status='approve',
                                REPLACE(iisoc.actual_count,',',''),
                                REPLACE( iisoc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisoc
                                WHERE
                                iisoc.product_code =p51.item_code
                                AND
                                iisoc.stock_from='".$store_id."'
                            
                            AND 
                            iisoc.status ='received' 
                            AND iisoc.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_out_c,

                          (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iiiboc
                                WHERE
                                iiiboc.product_code =p51.item_code
                                AND
                                iiiboc.stock_from='".$store_id."'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_out_c,
                        
                        (select coalesce(
                            sum(
                                if(iinbic.variance_status='approve',
                                REPLACE( iinbic.actual_count,',',''),
                                REPLACE( iinbic.`count`,',','')
                                )
                        ),0) FROM inventory_face iinbic
                                WHERE
                                iinbic.product_code =p51.item_code
                                AND
                                iinbic.store_id='".$store_id."'
                            
                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'
                        
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_in_c ,
                    

                    (select coalesce(
                            sum(
                                if(ipc.variance_status='approve',
                                REPLACE(  ipc.actual_count,',',''),
                                REPLACE(  ipc.`count`,',','')
                                )
                        ),0) FROM inventory_face ipc
                                WHERE
                                ipc.product_code =p51.item_code
                                    AND
                                    ipc.stock_from='".$store_id."'
                            
                            AND 
                            ipc.status ='received' 
                            AND ipc.type='pullout'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as pullout_c,

                    (select coalesce(
                            sum(
                                if(iidc.variance_status='approve',
                                REPLACE(  iidc.actual_count,',',''),
                                REPLACE(  iidc.`count`,',','')
                                )
                        ),0) FROM inventory_face iidc
                                WHERE
                                iidc.product_code =p51.item_code
                                    AND
                                    iidc.stock_from='".$store_id."'
                            
                            AND 
                            iidc.status ='received' 
                            AND iidc.type='damage'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_c,
                
                        (select coalesce(
                            sum(
                                if(ididc.variance_status='approve',
                                REPLACE(  ididc.actual_count,',',''),
                                REPLACE( ididc.`count`,',','')
                                )
                        ),0) FROM inventory_face  ididc
                                WHERE
                                ididc.product_code =p51.item_code
                                    AND
                                    ididc.store_id='".$store_id."'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        coalesce('".$arrSalesData[0]['sales']."',0) as sales,
                'none' as number,
                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                                    
                                                    ) AS transit_out,



                                                    (SELECT 
										coalesce(
												sum(
														if(iir.variance_status='approve',
														REPLACE(iir.actual_count,',',''),
														REPLACE(iir.`count`,',','')
														)
										),0)
								 FROM inventory_face iir
							WHERE
							iir.product_code =p51.item_code
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
								DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
							AND
												DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>='".$date_start."'
							
									) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.store_id='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,

                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c
								
                                    
              

            FROM    poll_51_face_new p51
          LEFT JOIN inventory_actual_count_face pactual ON pactual.store_audited='".$store_id."' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_face iacx 
                                WHERE iacx.store_audited='".$store_id."'
                                AND iacx.product_code='".$product_code."'
                            AND iacx.date_end<'".$date_start."' 
                        ) AND pactual.product_code='".$product_code."'
                LEFT JOIN store_codes_studios sls on sls.store_code='".$store_id."'
            WHERE p51.item_code='".$product_code."'
        
            group by p51.item_code";
         

            $stmtBig = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {
        
                mysqli_stmt_execute($stmtBig);
                mysqli_stmt_close($stmtBig);
        
            }
            else {
        
                echo mysqli_error($conn);
        
            }
        

                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17 );
                
                    while (mysqli_stmt_fetch($stmt)) {
                
                        $tempArray = array();
                
                        for ($i=0; $i < sizeOf($grabInvParams); $i++) { 
                
                            $tempArray[$grabInvParams[$i]] = ${'result' . ($i+1)};
                
                        };
                
                        $arrInvLook[] = $tempArray;
                
                    };
                
                    mysqli_stmt_close($stmt);    
                                            
                }
                else {
                
                    echo mysqli_error($conn);	
                
                };


                // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

                // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
                // echo "<pre>";
                // print_r($arrInvLook);
                // echo "</pre>";
        return $arrInvLook;

}





function storeChecker_smr_MS($product_code,$store_id,$date_start,$date_end){

    global $conn;
                $datenow=date('Y-m-d');

                $grabInvParams= array(
                            "store_name",
                            "item_name",
                            "product_code",
                            "beg_inventory",
                           "stock_transfer_in_c",
                            "stock_transfer_out_c",
                            "interbranch_out_c",
                            "interbranch_in_c",
                            "pullout_c",
                            "damage_c",
                            "damage_i",
                            "sales",
                            "number",
                            "transit_out",
                            "requested",
                            "transit_in",
                            "transit_out_c"
                           
                         );
                         $datenow=date('Y-m-d');


                         if($store_id=='787'  || $store_id=='788' || $store_id=='789' || $store_id=='1017' || $store_id=='1019' || $store_id=='1021'|| $store_id=='889'){
                                     $reRoute=" AND  date(os.payment_date)>='2020-06-25' 
                                      and  os.product_upgrade!='sunnies_studios' ";
                     
                                     // AND os.product_upgrade!='PL0010'
                             }
                           
                             else{
                                 $reRoute=" 
                                
                                      ";
                             }
                     
                     
                             // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
                             if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
                             || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                                                 || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code) 
                                                  || preg_match("/P1/i",$product_code) 
                                                 || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)  
                                                  || preg_match("/SC/i",$product_code)
                                                   || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                                                   || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) 
                                                   || preg_match("/SSGWPCB/i",$product_code)     || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code) 
                                                    || preg_match("/TB0/i",$product_code)     || preg_match("/SGC/i",$product_code)    || preg_match("/DS/i",$product_code)   
                                                     || preg_match("/ST/i",$product_code)      || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code) 
                                                      || preg_match("/CPV/i",$product_code)   || preg_match("/SWS/i",$product_code)    || preg_match("/SMHP/i",$product_code) 
                                                    || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code)   || preg_match("/SMZ/i",$product_code) 
                                                    || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code) || preg_match("/SS00/i",$product_code) 
                                                    || preg_match("/SPHC/i",$product_code) || preg_match("/NT0/i",$product_code)  || preg_match("/GSOM/i",$product_code) 
                                                    || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C100/i",$product_code) 
                                                   ){
                                                     
                                      $carekits=" AND os.product_upgrade ";
                                      $condition1="  ";
                     
                             }else{
                                $condition1=" and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
                                  $carekits=" AND os.product_code ";
                     
                             }
                            //  OR ( o.store_id='".$store_id."'  and dispatch_type='packaging')
                $que_sales="SELECT count(po_number)
                                                    
                                            
                FROM `orders_sunnies_studios` os
                
                LEFT JOIN orders_studios o ON o.order_id=os.order_id
                
                WHERE 
                payment='y'
                And os.status NOT IN ('return','cancelled','returned','failed' )
                AND date(os.payment_date)>='2020-02-4'
                ". $condition1." 
                AND  date(os.payment_date)>='".$date_start."'
                AND  date(os.payment_date)<='".$date_end."'
               ". $carekits."  ='".$product_code."'
                ".$reRoute." 
                AND  stock_from='".$store_id."'
                ";
                $grabInvParamssales=array("sales");
                $stmt2 = mysqli_stmt_init($conn);
									if (mysqli_stmt_prepare($stmt2, $que_sales)) {
							
										mysqli_stmt_execute($stmt2);
										mysqli_stmt_bind_result($stmt2, $result1);
							
										while (mysqli_stmt_fetch($stmt2)) {
							
											$tempArray = array();
							
											for ($i=0; $i < sizeOf($grabInvParamssales); $i++) { 
							
												$tempArray[$grabInvParamssales[$i]] = ${'result' . ($i+1)};
							
											};
							
											$arrSalesData[] = $tempArray;
							
										};
							
										mysqli_stmt_close($stmt2);    
																
									}
									else {
							
										echo mysqli_error($conn);	
							
									};


    $arrInvLook=array();
       $query=" SELECT 

                        sls.lab_name,
                        p51.item_name,
                        p51.item_code,
                        coalesce(pactual.input_count,0) as beginventory,
                     (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisi
                                WHERE
                                iisi.product_code =p51.item_code
                                AND
                                iisi.store_id='".$store_id."'
                            
                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                                OR
                                iisi.type='replenish'
                                )
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_in_c,


                    (select coalesce(
                            sum(
                                if(iisoc.variance_status='approve',
                                REPLACE(iisoc.actual_count,',',''),
                                REPLACE( iisoc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iisoc
                                WHERE
                                iisoc.product_code =p51.item_code
                                AND
                                iisoc.stock_from='".$store_id."'
                            
                            AND 
                            iisoc.status ='received' 
                            AND iisoc.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as stock_transfer_out_c,

                          (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory_face  iiiboc
                                WHERE
                                iiiboc.product_code =p51.item_code
                                AND
                                iiiboc.stock_from='".$store_id."'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_out_c,
                        
                        (select coalesce(
                            sum(
                                if(iinbic.variance_status='approve',
                                REPLACE( iinbic.actual_count,',',''),
                                REPLACE( iinbic.`count`,',','')
                                )
                        ),0) FROM inventory_face iinbic
                                WHERE
                                iinbic.product_code =p51.item_code
                                AND
                                iinbic.store_id='".$store_id."'
                            
                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'
                        
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as interbranch_in_c ,
                    

                    (select coalesce(
                            sum(
                                if(ipc.variance_status='approve',
                                REPLACE(  ipc.actual_count,',',''),
                                REPLACE(  ipc.`count`,',','')
                                )
                        ),0) FROM inventory_face ipc
                                WHERE
                                ipc.product_code =p51.item_code
                                    AND
                                    ipc.stock_from='".$store_id."'
                            
                            AND 
                            ipc.status ='received' 
                            AND ipc.type='pullout'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as pullout_c,

                    (select coalesce(
                            sum(
                                if(iidc.variance_status='approve',
                                REPLACE(  iidc.actual_count,',',''),
                                REPLACE(  iidc.`count`,',','')
                                )
                        ),0) FROM inventory_face iidc
                                WHERE
                                iidc.product_code =p51.item_code
                                    AND
                                    iidc.stock_from='".$store_id."'
                            
                            AND 
                            iidc.status ='received' 
                            AND iidc.type='damage'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_c,
                
                        (select coalesce(
                            sum(
                                if(ididc.variance_status='approve',
                                REPLACE(  ididc.actual_count,',',''),
                                REPLACE( ididc.`count`,',','')
                                )
                        ),0) FROM inventory_face  ididc
                                WHERE
                                ididc.product_code =p51.item_code
                                    AND
                                    ididc.store_id='".$store_id."'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        coalesce('".$arrSalesData[0]['sales']."',0) as sales,
                'none' as number,
                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                                    
                                                    ) AS transit_out,



                                                    (SELECT 
										coalesce(
												sum(
														if(iir.variance_status='approve',
														REPLACE(iir.actual_count,',',''),
														REPLACE(iir.`count`,',','')
														)
										),0)
								 FROM inventory_face iir
							WHERE
							iir.product_code =p51.item_code
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
								DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
							AND
												DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>='".$date_start."'
							
									) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.store_id='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,

                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_face ito
                                                    WHERE
                                                        ito.product_code =p51.item_code
                                                                        AND
                                                                        ito.stock_from='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c
								
                                    
              

            FROM    poll_51_face_new p51
          LEFT JOIN inventory_actual_count_face pactual ON pactual.store_audited='".$store_id."' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_face iacx 
                                WHERE iacx.store_audited='".$store_id."'
                                AND iacx.product_code='".$product_code."'
                            AND iacx.date_end<'".$date_start."' 
                        ) AND pactual.product_code='".$product_code."'
                        LEFT JOIN labs_locations sls on sls.lab_id='".$store_id."'
            WHERE p51.item_code='".$product_code."'
            AND  sls.lab_id   ='".$store_id."'
            group by p51.item_code";
         

            $stmtBig = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmtBig, "SET SQL_BIG_SELECTS=1;")) {
        
                mysqli_stmt_execute($stmtBig);
                mysqli_stmt_close($stmtBig);
        
            }
            else {
        
                echo mysqli_error($conn);
        
            }
        

                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17 );
                
                    while (mysqli_stmt_fetch($stmt)) {
                
                        $tempArray = array();
                
                        for ($i=0; $i < sizeOf($grabInvParams); $i++) { 
                
                            $tempArray[$grabInvParams[$i]] = ${'result' . ($i+1)};
                
                        };
                
                        $arrInvLook[] = $tempArray;
                
                    };
                
                    mysqli_stmt_close($stmt);    
                                            
                }
                else {
                
                    echo mysqli_error($conn);	
                
                };


                // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

                // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
                // echo "<pre>";
                // print_r($arrInvLook);
                // echo "</pre>";
        return $arrInvLook;

}


///////////////////////////////////////////////////



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
							FROM `inventory_actual_count_face`
							 WHERE store_audited='".$store_id."' 
							 and  date_end =(SELECT max(iacx.date_end) from inventory_actual_count_face  iacx 
                                                                                                WHERE  iacx.store_audited='".$store_id."' 
                                                                                            AND iacx.date_end<'".$dateStart."' 
																					    )
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







$arrActualCount2= array();

$grabParamsACtual2= array(
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
 $queryActualCounts2="SELECT `count`,
							`actual_count_id`,
							`date_count`,
							`date_start`,
							`date_end`, 
							`store_audited`,
							`auditor`,
							`product_code`,
							`input_count` 
							FROM `inventory_actual_count_face`
							 WHERE store_audited='".$store_id."'
							 and  date_end ='".$dateEnd."'
							";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryActualCounts2)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParamsACtual); $i++) { 

			$tempArray[$grabParamsACtual2[$i]] = ${'result' . ($i+1)};

		};

		$arrActualCount2[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);

};

?>