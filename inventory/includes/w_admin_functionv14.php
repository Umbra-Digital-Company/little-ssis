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

function WarehouseChecker_smr($product_Code,$date_start,$date_end) {

    global $conn;
                $datenow=date('Y-m-d');

                $grabInvParams= array("store_name",
                "item_name",
                "product_code",
                "beg_inventory",
                "pullout",
                "damage",
                "stock_transfer_out",
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
                "transit_out_c",
                "past_variance",
                "sales_past",
                "past_variance_2",
                "audit_date",
                "Interbranch_status_date",
                "stock_transfer_status_date",
                "interbranch_in_past",
                'stock_transfer_in_past',
                "sales_deduct_physical",
                "damage_past_date",
                "pullout_past_date",
                "stock_transfer_minus_date",
                "stock_transfer_minus",
                "interbranch_out_past",
                "interbranch_out_past_date",
                "interbranch_in_past_date",
                "damage_in_c",
                "damage_in_past",
                "damage_in_past_date"

            );


    $arrInvLook=array();


             $query=" SELECT 

                'warehouse',
                        p51.item_name,
                        p51.product_code,(SELECT 
                            coalesce(
                                sum(
                                    if(iib.variance_status='approve',
                                    REPLACE(iib.actual_count,',',''),
                                    REPLACE(iib.`count`,',','')
                                    )
                            ),0)
                        FROM inventory  iib
                    WHERE
                    iib.product_code =p51.product_code
                    AND
                    (iib.store_id='warehouse')
                    
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
                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                    
                        ) as beginventory,
                        
                (select coalesce(
                            sum(
                                if(iip.variance_status='approve',
                                REPLACE(iip.actual_count,',',''),
                                REPLACE(iip.`count`,',','')
                                )
                        ),0) FROM inventory  iip
                                WHERE
                                iip.product_code =p51.product_code
                                AND
                                iip.stock_from='warehouse'
                            
                            AND 
                            iip.status ='received' 
                            AND iip.type='pullout'
                            AND
                            DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as pullout,

                (select coalesce(
                            sum(
                                if(iid.variance_status='approve',
                                REPLACE(  iid.actual_count,',',''),
                                REPLACE( iid.`count`,',','')
                                )
                        ),0) FROM inventory iid
                                WHERE
                                iid.product_code =p51.product_code
                                AND
                                iid.stock_from='warehouse'
                            
                            AND 
                            iid.status ='received' 
                            AND iid.type='damage'
                            AND
                            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as damage,

                    (select coalesce(
                            sum(
                                if( iiso.variance_status='approve',
                                REPLACE(   iiso.actual_count,',',''),
                                REPLACE(  iiso.`count`,',','')
                                )
                        ),0) FROM inventory iiso
                                WHERE
                                iiso.product_code =p51.product_code
                                AND
                                iiso.stock_from='warehouse'
                            
                            AND 
                            iiso.status ='received' 
                            AND iiso.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as stock_transfer_out,
                        

                    (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory  iisi
                                WHERE
                                iisi.product_code =p51.product_code
                                AND
                                iisi.store_id='warehouse'
                            
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
                        ),0) FROM inventory  iisoc
                                WHERE
                                iisoc.product_code =p51.product_code
                                AND
                                iisoc.stock_from='warehouse'
                            
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
                        ),0) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse'
                            
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
                        ),0) FROM inventory iinbic
                                WHERE
                                iinbic.product_code =p51.product_code
                                AND
                                iinbic.store_id='warehouse'
                            
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
                        ),0) FROM inventory ipc
                                WHERE
                                ipc.product_code =p51.product_code
                                    AND
                                    ipc.stock_from='warehouse'
                            
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
                        ),0) FROM inventory iidc
                                WHERE
                                iidc.product_code =p51.product_code
                                    AND
                                    iidc.stock_from='warehouse'
                            
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
                        ),0) FROM inventory  ididc
                                WHERE
                                ididc.product_code =p51.product_code
                                    AND
                                    ididc.store_id='warehouse'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        '0',
                'none',(SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
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
								DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
							AND
												DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>='".$date_start."'
							
									) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.store_id='warehouse'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,

                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.stock_from='warehouse'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c,
									(
																

                                        SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
																	FROM inventory_actual_count iaccc 
																	where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse'
																	and iaccc.product_code='".$product_Code."'
																	and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
																					    )
															) as past_variance,
                                                                    '0',
									(
																

                                        SELECT COALESCE(sum(iaccc.input_count),0)
																	FROM inventory_actual_count iaccc 
																	where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse'
																	and iaccc.product_code='".$product_Code."'
																	and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
																					    )
															) as past_variance_2,
									(
																

                                        SELECT COALESCE( max(iaccc.date_end),0)
																	FROM inventory_actual_count iaccc 
																	where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse'
																	and iaccc.product_code='".$product_Code."'
																	and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
																					    )
															) as audit_date


                                                            ,

                                                                    (
                                                                                
                                                                        SELECT 
                                                                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                                        FROM inventory  iib
                                                                    WHERE
                                                                    iib.product_code =p51.product_code
                                                                    AND
                                                                    (iib.store_id='warehouse')
                                                                    
                                                                    AND 
                                                                    iib.status ='received'
                                                                    AND
                                                                        
                                                                        iib.`type`='interbranch'
                                                                        
                                                                        
                                                                    
                                                                    AND
                                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                                            AND iacx.store_id='warehouse'
                                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                                                            AND count !='0'
                                                                                                            and status='received'
                                                                                                        )

                                                                                     ) as interbranch_status_date,

                                                    (
                                                                
                                                        SELECT 
                                                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                        FROM inventory  iib
                                                    WHERE
                                                    iib.product_code =p51.product_code
                                                    AND
                                                    (iib.store_id='warehouse')

                                                    AND 
                                                    iib.status ='received'
                                                    AND
                                                        iib.type='stock_transfer'

                                                    AND
                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_id='warehouse'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                                            AND count !='0'
                                                                                            and status='received'
                                                                                        )

                                                        
                                                            ) as stock_transfer_status_date,
                               
                                
                                (select coalesce(
                                    sum(
                                        if(iinbic.variance_status='approve',
                                        REPLACE( iinbic.actual_count,',',''),
                                        REPLACE( iinbic.`count`,',',''))
                                ),0) FROM inventory iinbic
                                        WHERE
                                        iinbic.product_code =p51.product_code
                                        AND
                                        iinbic.store_id='warehouse'
                                    
                                    AND 
                                    iinbic.status ='received' 
                                    AND iinbic.type='interbranch'
                                
                                   
                                  


                                                                        AND
                                                                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                                ) as interbranch_in_past ,
                                

                                (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                            WHERE
                                            iisi.product_code =p51.product_code
                                            AND
                                            iisi.store_id='warehouse'
                                        
                                        AND 
                                        iisi.status ='received' 
                                        AND (iisi.type='stock_transfer'
                                            OR
                                            iisi.type='replenish'
                                            )
                                      
                                            AND
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                                    ) as stock_transfer_in_past,
                                    ( SELECT count(po_number)
                                                            
                                                                            
                                                
                                                            FROM `orders_specs` os
                                                
                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                
                                                        WHERE 
                                                        payment='y'
                                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                                            AND os.product_code='".$product_Code."'
                                                            AND date(os.payment_date)>='2020-02-4'
                                                            
                                                            AND  date(os.payment_date)>audit_date
                                                            
                                                            AND os.lens_option='with prescription' 
                                                             AND os.lens_code NOT IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')

                                                
                                                            AND   os.stock_from='warehouse'
                                            ) as sales_deduct_physical,

        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iid
                WHERE
                iid.product_code =p51.product_code
                AND
                iid.stock_from='warehouse'
            
            AND 
            iid.status ='received' 
            AND iid.type='damage'
            AND
            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                                AND iacx.stock_from='warehouse'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='damage'
                                                                            )
        ) as damage_date,

        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iip
                            WHERE
                            iip.product_code =p51.product_code
                            AND
                            iip.stock_from='warehouse'
                        
                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                                AND iacx.stock_from='warehouse'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='pullout'
                                                                            )
                    ) as pullout_date,

                        (
                                    
                            SELECT 
                            DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                            FROM inventory  iib
                        WHERE
                        iib.product_code =p51.product_code
                        AND
                        (iib.stock_from='warehouse')

                        AND 
                        iib.status ='received'
                        AND
                            iib.type='stock_transfer'

                        AND
                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                AND iacx.stock_from='warehouse'
                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                AND count !='0'
                                                                and status='received'
                                                            )

                            
                                ) as stock_transfer_minus_date,
                                

                                (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                            WHERE
                                            iisi.product_code =p51.product_code
                                            AND
                                            iisi.stock_from='warehouse'
                                        
                                        AND 
                                        iisi.status ='received' 
                                        AND (iisi.type='stock_transfer'
                                            OR
                                            iisi.type='replenish'
                                            )
                                      
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                                    ) as stock_transfer_minus,
                                    (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past,
                        (select   DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past_date,
                               
                                
                               (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbic
                                       WHERE
                                       iinbic.product_code =p51.product_code
                                       AND
                                       iinbic.store_id='warehouse'
                                   
                                   AND 
                                   iinbic.status ='received' 
                                   AND iinbic.type='interbranch'
                               
                                  
                                 


                                                                       AND
                                                                       DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                           AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                               ) as interbranch_in_past_date,
                               '0',
                               '0',
                                 
                               (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbic
                                       WHERE
                                       iinbic.product_code =p51.product_code
                                       AND
                                       iinbic.store_id='warehouse'
                                   
                                   AND 
                                   iinbic.status ='received' 
                                   AND iinbic.type='damage'
                               
                                  
                                 


                                                                       AND
                                                                       DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                           AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                               ) as damage_in_past_date
                                    
              

            FROM    poll_51 p51
            LEFT JOIN inventory ip on p51.product_code=ip.product_code
            WHERE p51.product_code='".$product_Code."'
           
            group by p51.product_code";
         



                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
                    $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34,
                    $result35,$result36,$result37,$result38,$result39);
                
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
    


function labChecker_smr($product_code,$store_id,$date_start,$date_end){


    global $conn;
    $datenow=date('Y-m-d');

    if($date_start<'2020-06-26'){
        $filterVirtual= " ";
    }
    else{
        $filterVirtual= " AND o.origin_branch!='787'
                            AND o.origin_branch!='788' ";

    }
        //////////////// if kids plano redirect to  special lab   3set of $lensfilter 1 specifically getting mega//glo for L035
    if($store_id=='6981YR1OBFVPK2V40HY8'){
        $kidsfilter ="   (    ( (lens_code='L035' OR lens_code='ML0035' )  AND  os.stock_from='".$store_id."'  and o.store_id!='102' )
        
                                )           ";

    }elseif($store_id=='0075YR1OBABCD2V40335'){

        $kidsfilter ="   (   (lens_code='L035' OR lens_code='ML0035' )  AND     o.store_id='102')           ";
    }
    
    else{
        $kidsfilter ="   (  (lens_code='L035' OR lens_code='ML0035' )  AND  os.stock_from='".$store_id."')           ";
    }

    if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
    || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                        || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)   || preg_match("/P1/i",$product_code)  
                        || preg_match("/AFC/i",$product_code) || preg_match("/MSCL/i",$product_code) || preg_match("/SDB/i",$product_code)   || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)  || preg_match("/SC/i",$product_code)  ){

                            $packaging_for= " (packaging_for!='' AND packaging_stock='lab' AND  os.stock_from='".$store_id."' ) OR ";
                           
                            
             $carekits=" AND os.product_upgrade ";

    }else{
        $packaging_for= " ";
         $carekits=" AND os.product_code ";

    }


   
    if($store_id=='6981YR1OBFVPK2V40HY8'){
        // $KCC= " AND ( 
        //             o.origin_branch='151' AND os.lens_code NOT IN ('L001','L002','L045','L013', 'L014', 'L015', 'L016', 'L018', 
        //         'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053') 
        // ) ";
        $KCC="   AND   '1'= CASE WHEN o.store_id='151' AND os.lens_code  IN ('L001','L002','L045','L013', 'L014', 'L015', 'L016', 'L017', 'L018', 
                  'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053','SO001' ,'SO002','SO003',

                  'ML0001','ML0002','ML0013','ML0045', 'ML0013', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML024', 'ML0029', 'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001',
                                                                                    'ML0049', 'ML0050', 'ML0051', 'ML0052', 'ML0053'      
        
        
         ) 

        THEN   '0'
        else '1'        
        END ";


    }elseif($store_id=='KCCSTWE2QAM55LA11P6Q'){
        $KCC= "  AND o.order_id='x' ";

    }else{
        $KCC= "  ";

    }


    $lensFilter="
         AND  product_upgrade!='special_order'
         AND os.lens_code NOT LIKE 'EL%'
         AND ( ".$packaging_for."
                            (
                                (os.lens_option='with prescription' )
                                    AND
                                    os.lens_code NOT IN ('L013', 'L014', 'L015', 'L016', 'L017', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053','SO001' ,'SO002','SO003',
                                    'ML0013', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML0024', 'ML0029', 
                                    'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001', 'ML0049', 'ML0050', 'ML0051', 'ML0052'
                                    )
                                AND   os.stock_from='".$store_id."'

                                ".$KCC." 
                            )

        or ".$kidsfilter."         
        )
        
        ";




                $grabInvParams= array("store_name",
                "item_name",
                "product_code",
                "beg_inventory",
                "pullout",
                "damage",
                "stock_transfer_out",
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
                "transit_out_c",
                "past_variance",
                "sales_past",
                "past_variance_2",
                "audit_date",
                "Interbranch_status_date",
                "stock_transfer_status_date",
                "interbranch_in_past",
                'stock_transfer_in_past',
                "sales_deduct_physical",
                "damage_past_date",
                "pullout_past_date",
                "stock_transfer_minus_date",
                "stock_transfer_minus",
                "interbranch_out_past",
                "interbranch_out_past_date",
                "interbranch_in_past_date",
                "damage_in_c",
                "damage_in_past",
                "damage_in_past_date"
                

            );

        $arrInvLook=array();


        $query =" SELECT 

                        ll.lab_name,
                                p51.item_name,
                                p51.product_code,(SELECT 
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
                            (iib.store_id=ll.lab_id)
                            
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
                                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                            
                                ) as beginventory,
                                
                        (select coalesce(
                                    sum(
                                        if(iip.variance_status='approve',
                                        REPLACE( iip.actual_count,',',''),
                                        REPLACE( iip.`count`,',',''))
                                ),0) FROM inventory  iip
                                        WHERE
                                        iip.product_code =p51.product_code
                                        AND
                                        iip.stock_from=ll.lab_id
                                    
                                    AND 
                                    iip.status ='received' 
                                    AND iip.type='pullout'
                                    AND
                                    DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                ) as pullout,

                        (select coalesce(
                                    sum(
                                        if(iid.variance_status='approve',
                                        REPLACE(iid.actual_count,',',''),
                                        REPLACE( iid.`count`,',',''))
                                ),0) FROM inventory iid
                                        WHERE
                                        iid.product_code =p51.product_code
                                        AND
                                        iid.stock_from=ll.lab_id
                                    
                                    AND 
                                    iid.status ='received' 
                                    AND iid.type='damage'
                                    AND
                                    DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                ) as damage,

                            (select coalesce(
                                    sum(
                                        if( iiso.variance_status='approve',
                                        REPLACE( iiso.actual_count,',',''),
                                        REPLACE( iiso.`count`,',',''))
                                ),0) FROM inventory iiso
                                        WHERE
                                        iiso.product_code =p51.product_code
                                        AND
                                        iiso.stock_from=ll.lab_id
                                    
                                    AND 
                                    iiso.status ='received' 
                                    AND iiso.type='stock_transfer'
                                    AND
                                    DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                ) as stock_transfer_out,
                                

                            (select coalesce(
                                    sum(
                                        if(iisi.variance_status='approve',
                                        REPLACE( iisi.actual_count,',',''),
                                        REPLACE( iisi.`count`,',',''))
                                ),0) FROM inventory  iisi
                                        WHERE
                                        iisi.product_code =p51.product_code
                                        AND
                                        iisi.store_id=ll.lab_id
                                    
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
                                        REPLACE( iisoc.actual_count,',',''),
                                        REPLACE( iisoc.`count`,',',''))
                                ),0) FROM inventory  iisoc
                                        WHERE
                                        iisoc.product_code =p51.product_code
                                        AND
                                        iisoc.stock_from=ll.lab_id
                                    
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
                                        REPLACE(  iiiboc.actual_count,',',''),
                                        REPLACE( iiiboc.`count`,',',''))
                                ),0) FROM inventory  iiiboc
                                        WHERE
                                        iiiboc.product_code =p51.product_code
                                        AND
                                        iiiboc.stock_from=ll.lab_id
                                    
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
                                        REPLACE( iinbic.`count`,',',''))
                                ),0) FROM inventory iinbic
                                        WHERE
                                        iinbic.product_code =p51.product_code
                                        AND
                                        iinbic.store_id=ll.lab_id
                                    
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
                                        REPLACE( ipc.actual_count,',',''),
                                        REPLACE( ipc.`count`,',',''))
                                ),0) FROM inventory ipc
                                        WHERE
                                        ipc.product_code =p51.product_code
                                            AND
                                            ipc.stock_from=ll.lab_id
                                    
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
                                        REPLACE( iidc.actual_count,',',''),
                                        REPLACE( iidc.`count`,',',''))
                                ),0) FROM inventory iidc
                                        WHERE
                                        iidc.product_code =p51.product_code
                                            AND
                                            iidc.stock_from=ll.lab_id
                                    
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
                                        REPLACE(ididc.actual_count,',',''),
                                        REPLACE( ididc.`count`,',',''))
                                ),0) FROM inventory  ididc
                                        WHERE
                                        ididc.product_code =p51.product_code
                                            AND
                                            ididc.store_id='warehouse_damage'
                                    
                                    AND 
                                    ididc.status ='received' 
                                    AND ididc.type='damage'
                                    AND
                                    DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                                    AND
                                    DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                ) as damage_i,
                                ( SELECT    coalesce(CASE
                                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                                            END,0)
                                                                    

                                            FROM `orders_specs` os

                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                            LEFT JOIN stores_locations xsl ON xsl.store_id=o.store_id
                                        WHERE 
                                        payment='y'
                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                        and coalesce(packaging_stock,'')!='store'
                                              ". $carekits."  ='".$product_code."'
                                            AND date(os.payment_date)>='2020-02-4'
                                            AND  date(os.payment_date)>='".$date_start."'
                                            AND  date(os.payment_date)<='".$date_end."'
                                            
                                          ".$lensFilter."

                                         
                                          
                                             ".$filterVirtual." 
                                            ) as sales,
                                            ll.phone_number,
                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.stock_from='".$store_id."'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_start."'
                                                    
                                                    ) AS transit_out,



                                                        '0',


                                                        (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                        WHERE
                                                            ito.product_code =p51.product_code
                                                                            AND
                                                                            ito.store_id='".$store_id."'
                                                                
                                                                AND 
                                                                ito.status ='in transit' 
                                                                
                                                            
                                                               
                                                                AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'

                                                        ) AS transit_in,

                                                        (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                        WHERE
                                                            ito.product_code =p51.product_code
                                                                            AND
                                                                            ito.stock_from='".$store_id."'
                                                                
                                                                AND 
                                                                ito.status ='in transit' 
                                                                
                                                            
                                                               
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'

                                                        ) AS transit_out_c,

                                                        (
                                                                    

                                                                 SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                                                        FROM inventory_actual_count iaccc 
                                                                        where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                                        and iaccc.product_code='".$product_code."'
                                                                        and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                    WHERE iacx.product_code='".$product_code."'
                                                                                                AND iacx.store_audited=iaccc.store_audited
                                                                                                AND iacx.date_end<'".$date_start."' 
                                                                                            )
                                                                ) as past_variance,

                                                                    ( SELECT   coalesce(CASE
                                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                                            END,0)
                                                            
                                                                            
                                                
                                                                            FROM `orders_specs` os
                                                                
                                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                                            LEFT JOIN stores_locations xsl ON xsl.store_id =o.store_id
                                                                        WHERE 
                                                                        payment='y'
                                                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                                                        ". $carekits."  ='".$product_code."'
                                                                            AND date(os.payment_date)>='2020-02-4'
                                                                            and coalesce(packaging_stock,'')!='store'
                                                                            AND  date(os.payment_date)<'".$date_start."'
                                                                            
                                                                            ".$lensFilter." 

                                                                            ".$filterVirtual." 
                                                            ) as sales_past,

                                                                    (
                                                                                

                                                                            SELECT COALESCE(sum(iaccc.input_count),0)
                                                                                    FROM inventory_actual_count iaccc 
                                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                                                    and iaccc.product_code='".$product_code."'
                                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                                        )
                                                                            ) as past_variance_2,

                                                                    (
                                                                                

                                                                            SELECT COALESCE( max(iaccc.date_end),0)
                                                                                    FROM inventory_actual_count iaccc 
                                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                                                    and iaccc.product_code='".$product_code."'
                                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                                        )
                                                                            ) as audit_date,

                                                                    (
                                                                                
                                                                        SELECT 
                                                                        DATE(DATE_ADD( max(status_date), INTERVAL 13 HOUR))
                                                                        FROM inventory  iib
                                                                    WHERE
                                                                    iib.product_code =p51.product_code
                                                                    AND
                                                                    (iib.store_id='".$store_id."')
                                                                    
                                                                    AND 
                                                                    iib.status ='received'
                                                                    AND
                                                                        
                                                                        iib.`type`='interbranch'
                                                                        
                                                                        
                                                                    
                                                                    AND
                                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                                            AND iacx.store_id='".$store_id."'
                                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                                                            AND count !='0'
                                                                                                            and status='received'
                                                                                                            AND iacx.`type`='interbranch'
                                                                                                        )

                                                                                     ) as interbranch_status_date,

                                                    (
                                                                
                                                        SELECT 
                                                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                        FROM inventory  iib
                                                    WHERE
                                                    iib.product_code =p51.product_code
                                                    AND
                                                    (iib.store_id='".$store_id."')

                                                    AND 
                                                    iib.status ='received'
                                                    AND
                                                        iib.type='stock_transfer'

                                                    AND
                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                            AND iacx.store_id='".$store_id."'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                                            AND count !='0'
                                                                                            and status='received'
                                                                                            and iacx.type='stock_transfer'
                                                                                        )

                                                        
                                                            ) as stock_transfer_status_date,
                               
                                
                                (select coalesce(
                                    sum(
                                        if(iinbic.variance_status='approve',
                                        REPLACE( iinbic.actual_count,',',''),
                                        REPLACE( iinbic.`count`,',',''))
                                ),0) FROM inventory iinbic
                                        WHERE
                                        iinbic.product_code =p51.product_code
                                        AND
                                        iinbic.store_id='".$store_id."'
                                    
                                    AND 
                                    iinbic.status ='received' 
                                    AND iinbic.type='interbranch'
                                
                                   
                                    AND
                                                                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                                                        AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                                ) as interbranch_in_past ,
                                

                                (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
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
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 

                                    ) as stock_transfer_in_past,


                                    ( SELECT  coalesce(CASE
                                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                                            END,0)
                                                            
                                                                            
                                                
                                                            FROM `orders_specs` os
                                                
                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                            LEFT JOIN stores_locations xsl ON xsl.store_id =o.store_id
                                                        WHERE 
                                                        payment='y'
                                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                                        ". $carekits."  ='".$product_code."'
                                                            AND date(os.payment_date)>='2020-02-4'
                                                            and coalesce(packaging_stock,'')!='store'
                                                            AND  date(os.payment_date)>audit_date
                                                            AND  date(os.payment_date)<'".$date_start."' 
                                                          
                                                            ".$lensFilter."
                                                         
                                                       
                                                            ".$filterVirtual." 
                                            ) as sales_deduct_physical,

            (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iid
                            WHERE
                            iid.product_code =p51.product_code
                            AND
                            iid.stock_from=ll.lab_id
                        
                        AND 
                        iid.status ='received' 
                        AND iid.type='damage'
                        AND
                        DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                            AND iacx.stock_from='".$store_id."'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                            AND count !='0'
                                                                                            and status='received'
                                                                                            and iacx.type='damage'
                                                                                        )
                    ) as damage_date,

                    (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iip
                                        WHERE
                                        iip.product_code =p51.product_code
                                        AND
                                        iip.stock_from=ll.lab_id
                                    
                                    AND 
                                    iip.status ='received' 
                                    AND iip.type='pullout'
                                    AND
                                    DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                            AND iacx.stock_from='".$store_id."'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                            AND count !='0'
                                                                                            and status='received'
                                                                                            and iacx.type='pullout'
                                                                                        )
                                ) as pullout_date,
                                (
                                                                
                                                                SELECT 
                                                                DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                                FROM inventory  iib
                                                            WHERE
                                                            iib.product_code =p51.product_code
                                                            AND
                                                            (iib.stock_from='".$store_id."')
        
                                                            AND 
                                                            iib.status ='received'
                                                            AND
                                                                iib.type='stock_transfer'
        
                                                            AND
                                                                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                        WHERE iacx.product_code='".$product_code."'
                                                                                                    AND iacx.stock_from='".$store_id."'
                                                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                                                    AND count !='0'
                                                                                                    and status='received'
                                                                                                    and iacx.type='stock_transfer'
                                                                                                )
        
                                                                
                                                                    ) as stock_transfer_minus_date,
                                                                    (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                            WHERE
                                            iisi.product_code =p51.product_code
                                            AND
                                            iisi.stock_from='".$store_id."'
                                        
                                        AND 
                                        iisi.status ='received' 
                                        AND (iisi.type='stock_transfer'
                                            OR
                                            iisi.type='replenish'
                                            )
                                      
                                            AND
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<'".$date_start."' 

                                    ) as stock_transfer_minus_past,
                                     (select coalesce(
                                    sum(
                                        if(iiiboc.variance_status='approve',
                                        REPLACE(  iiiboc.actual_count,',',''),
                                        REPLACE( iiiboc.`count`,',',''))
                                ),0) FROM inventory  iiiboc
                                        WHERE
                                        iiiboc.product_code =p51.product_code
                                        AND
                                        iiiboc.stock_from=ll.lab_id
                                    
                                    AND 
                                    iiiboc.status ='received' 
                                    AND iiiboc.type='interbranch'
                                    
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                ) as interbranch_out_past,

                                (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                 FROM inventory  iiiboc
                                        WHERE
                                        iiiboc.product_code =p51.product_code
                                        AND
                                        iiiboc.stock_from=ll.lab_id
                                    
                                    AND 
                                    iiiboc.status ='received' 
                                    AND iiiboc.type='interbranch'
                                    
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                ) as interbranch_out_past_date,
                               
                                
                               (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbic
                                       WHERE
                                       iinbic.product_code =p51.product_code
                                       AND
                                       iinbic.store_id='".$store_id."'
                                   
                                   AND 
                                   iinbic.status ='received' 
                                   AND iinbic.type='interbranch'
                               
                                  
                                   AND
                                                                       DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                                                       AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                               ) as interbranch_in_past_date ,
                               '0',
                               '0',
                                 
                               (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbicd
                                       WHERE
                                       iinbicd.product_code =p51.product_code
                                       AND
                                       iinbicd.store_id='warehouse'
                                   
                                   AND 
                                   iinbicd.status ='received' 
                                 AND  iinbicd.store_id='".$store_id."'
                               
                                  
                                 


                                                                       AND
                                                                       DATE(DATE_ADD(iinbicd.status_date, INTERVAL 13 HOUR))>audit_date
                                           AND DATE(DATE_ADD(iinbicd.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                               ) as damage_in_past_date
                                    


                                FROM poll_51 p51
                                LEFT JOin inventory ip on ip.product_code=p51.product_code
                                LEFT JOIN labs_locations ll on ll.lab_id='".$store_id."'
                                WHERE p51.product_code='".$product_code."'
                                AND ll.lab_id='".$store_id."'
                                group by ll.lab_id,p51.product_code
            ";



        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
            $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
            $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34,
            $result35,$result36,$result37,$result38,$result39);
        

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

        // echo"<Br>";
        // print_r($arrInvLook);
        // echo"</br>";
        //////////////////// need sales past

       

        return $arrInvLook;


        


}








function storeChecker_smr($product_code,$store_id,$date_start,$date_end){
    global $conn;
    $datenow=date('Y-m-d');


            if($store_id=='787' ||  $store_id=='788'){
                $reRoute=" AND  date(os.payment_date)>='2020-06-25' 
                 and  os.product_upgrade!='sunnies_studios' ";

                // AND os.product_upgrade!='PL0010'
        } elseif($store_id=='151'){
            $reRoute=" AND  os.lens_code!='L035' AND  os.lens_code!='ML0035'
            AND (
                                  os.lens_option='without prescription'
                                    OR
                                    os.lens_code IN (
                                        'L001','L002','L045', 'L014', 'L015', 'L016', 'L017', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024',
                                         'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053',
                                         
                                         'SO001' ,'SO002','SO003',

                                         'ML0001','ML0002','ML0045', 'ML0013', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML024', 'ML0029', 'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001',
                                                                                    'ML0049', 'ML0050', 'ML0051', 'ML0052', 'ML0053'         
                                    )
                                    OR product_upgrade='special_order'
                        ) 
                        and  os.product_code!='F100' 
                        and  os.product_code!='S100' 
                        and  os.product_upgrade!='sunnies_studios' 
                        
                 ";

        }
        else{
            $reRoute=" 
            AND  os.lens_code!='L035'  AND  os.lens_code!='ML0035'
            AND (
                                  os.lens_option='without prescription'
                                    OR
                                    os.lens_code IN ('L013', 'L014', 'L015', 'L016', 'L017', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 
                                    'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053','SO001' ,'SO002','SO003' ,
                                    'ML0013', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML0024', 'ML0029', 
                                    'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001', 'ML0049', 'ML0050', 'ML0051', 'ML0052', 'ML0053')
                                    OR product_upgrade='special_order'
                        ) 
                        and  os.product_code!='F100' 
                        and  os.product_code!='S100' 
                        and  os.product_upgrade!='sunnies_studios' 
                        
                 ";
        }


        // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
        if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
        || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                            || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code)  || preg_match("/P1/i",$product_code) 
                            || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)   || preg_match("/SC/i",$product_code)  ){
                                
                 $carekits=" AND os.product_upgrade ";

        }else{
             $carekits=" AND os.product_code ";

        }
                            
                $grabInvParams= array("store_name",
                                    "item_name",
                                    "product_code",
                                    "beg_inventory",
                                    "pullout",
                                    "damage",
                                    "stock_transfer_out",
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
                                    "transit_out_c",
                                    "past_variance",
                                    "sales_past",
                                    "past_variance_2",
                                    "audit_date",
                                    "Interbranch_status_date",
                                    "stock_transfer_status_date",
                                    "interbranch_in_past",
                                    'stock_transfer_in_past',
                                    "sales_deduct_physical",
                                    "damage_past_date",
                                    "pullout_past_date",
                                    "stock_transfer_minus_date",
                                    "stock_transfer_minus",
                                    "interbranch_out_past",
                                    "interbranch_out_past_date",
                                    "interbranch_in_past_date",
                                    "damage_in_c",
                                    "damage_in_past",
                                    "damage_in_past_date"
                                    
            );


        $arrInvLook=array();
          
          
          $query ="SELECT 
        sls.store_name,
                p51.item_name,
                p51.product_code,(SELECT 
                    coalesce(
                        sum(
                            if(iib.variance_status='approve',
                            REPLACE(iib.actual_count,',',''),
                            REPLACE( iib.`count`,',',''))
                    ),0)
                 FROM inventory  iib
              WHERE
              iib.product_code =p51.product_code
              AND
              (iib.store_id=sls.store_id)
              
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
                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$date_start."'
              
                  ) as beginventory,

          (select coalesce(
                      sum(
                          if(iip.variance_status='approve',
                          REPLACE( iip.actual_count,',',''),
                          REPLACE( iip.`count`,',',''))
                  ),0) FROM inventory  iip
                        WHERE
                        iip.product_code =p51.product_code
                          AND
                          iip.stock_from=sls.store_id
                    
                    AND 
                    iip.status ='received' 
                    AND iip.type='pullout'
                    AND
                    DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                ) as pullout,

          (select coalesce(
                      sum(
                          if(iid.variance_status='approve',
                          REPLACE( iid.actual_count,',',''),
                          REPLACE( iid.`count`,',',''))
                  ),0) FROM inventory iid
                        WHERE
                        iid.product_code =p51.product_code
                          AND
                          iid.stock_from=sls.store_id
                    
                    AND 
                    iid.status ='received' 
                    AND iid.type='damage'
                    AND
                    DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                ) as damage,

            (select coalesce(
                      sum(
                          if( iiso.variance_status='approve',
                          REPLACE( iiso.actual_count,',',''),
                          REPLACE( iiso.`count`,',',''))
                  ),0) FROM inventory iiso
                        WHERE
                        iiso.product_code =p51.product_code
                          AND
                          iiso.stock_from=sls.store_id
                    
                    AND 
                    iiso.status ='received' 
                    AND iiso.type='stock_transfer'
                    AND
                    DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                ) as stock_transfer_out,
                

            (select coalesce(
                      sum(
                          if(iisi.variance_status='approve',
                          REPLACE( iisi.actual_count,',',''),
                          REPLACE( iisi.`count`,',',''))
                  ),0) FROM inventory  iisi
                        WHERE
                        iisi.product_code =p51.product_code
                          AND
                          iisi.store_id=sls.store_id
                    
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
                          REPLACE( iisoc.actual_count,',',''),
                          REPLACE( iisoc.`count`,',',''))
                  ),0) FROM inventory  iisoc
                        WHERE
                        iisoc.product_code =p51.product_code
                          AND
                          iisoc.stock_from=sls.store_id
                    
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
                          REPLACE(iiiboc.actual_count,',',''),
                          REPLACE( iiiboc.`count`,',',''))
                  ),0) FROM inventory  iiiboc
                        WHERE
                        iiiboc.product_code =p51.product_code
                          AND
                          iiiboc.stock_from=sls.store_id
                    
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
                          REPLACE(iinbic.actual_count,',',''),
                          REPLACE( iinbic.`count`,',',''))
                  ),0) FROM inventory iinbic
                        WHERE
                        iinbic.product_code =p51.product_code
                          AND
                          iinbic.store_id=sls.store_id
                    
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
                          REPLACE(ipc.actual_count,',',''),
                          REPLACE( ipc.`count`,',',''))
                  ),0) FROM inventory ipc
                          WHERE
                          ipc.product_code =p51.product_code
                            AND
                            ipc.stock_from=sls.store_id
                      
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
                          REPLACE(iidc.actual_count,',',''),
                          REPLACE( iidc.`count`,',',''))
                  ),0) FROM inventory iidc
                          WHERE
                          iidc.product_code =p51.product_code
                            AND
                            iidc.stock_from=sls.store_id
                      
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
                          REPLACE( ididc.actual_count,',',''),
                          REPLACE( ididc.`count`,',',''))
                  ),0) FROM inventory  ididc
                          WHERE
                          ididc.product_code =p51.product_code
                            AND
                            ididc.store_id='warehouse_damage'
                      
                      AND 
                      ididc.status ='received' 
                      AND ididc.type='damage'
                      AND
                      DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                      AND
                      DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                  ) as damage_i,
                                            
                                            ( SELECT if( os.packaging_for!='' && packaging_stock='lab',
                                                                        '0',
                                                                        
                                                                    count(po_number))
                                                    
                                            
                                            FROM `orders_specs` os
                                            
                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                            
                                            WHERE 
                                            payment='y'
                                            And os.status NOT IN ('return','cancelled','returned','failed' )
                                            AND date(os.payment_date)>='2020-02-4'
                                            and coalesce(packaging_stock,'')!='lab'
                                            AND  date(os.payment_date)>='".$date_start."'
                                            AND  date(os.payment_date)<='".$date_end."'
                                           ". $carekits."  ='".$product_code."'
                                            ".$reRoute." 
                                            AND ( (o.origin_branch='".$store_id."'  and dispatch_type!='packaging') OR ( o.store_id='".$store_id."'  and dispatch_type='packaging') )
                                        ) as sales,
                    sls.phone_number,
                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                    WHERE
                                        ito.product_code =p51.product_code
                                                        AND
                                                        ito.stock_from='".$store_id."'
                                            
                                            AND 
                                            ito.status ='in transit' 
                                            
                                        
                                            AND
                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_start."'
                                    
                                    ) AS transit_out,



                                '0',


                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                WHERE
                                    ito.product_code =p51.product_code
                                                    AND
                                                    ito.store_id='".$store_id."'
                                        
                                        AND 
                                        ito.status ='in transit' 
                                        
                                    
                                       
                                        AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'

                                ) AS transit_in,

                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                WHERE
                                    ito.product_code =p51.product_code
                                                    AND
                                                    ito.stock_from='".$store_id."'
                                        
                                        AND 
                                        ito.status ='in transit' 
                                        
                                    
                                      
                                    AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'

                                ) AS transit_out_c,

                                (
                                            
                                    SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                                FROM inventory_actual_count iaccc 
                                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                and iaccc.product_code='".$product_code."'
                                                and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                            WHERE iacx.product_code='".$product_code."'
                                                                        AND iacx.store_audited=iaccc.store_audited
                                                                        AND iacx.date_end<'".$date_start."' 
                                                                    )
                                        ) as past_variance,

                                            ( SELECT if( os.packaging_for!='' && packaging_stock!='store',
                                                            '0',
                                                            
                                                        count(po_number))
                                    
                                                    

                                                    FROM `orders_specs` os
                                        
                                                    LEFT JOIN orders o ON o.order_id=os.order_id
                                        
                                                    WHERE 
                                                    payment='y'
                                                    And os.status NOT IN ('return','cancelled','returned','failed' )
                                                    AND date(os.payment_date)>='2020-02-4'
                                                    and coalesce(packaging_stock,'')!='lab'
                                                    AND  date(os.payment_date)<'".$date_start."'
                                                    ". $carekits."  ='".$product_code."'
                                                     ".$reRoute."  
                                                     AND ( (o.origin_branch='".$store_id."'  and dispatch_type!='packaging') OR ( o.store_id='".$store_id."'  and dispatch_type='packaging') )
                                    ) as sales_past,

                                            (
                                                        
                                                SELECT COALESCE(sum(iaccc.input_count),0)
                                                            FROM inventory_actual_count iaccc 
                                                            where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                            and iaccc.product_code='".$product_code."'
                                                            and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                        WHERE iacx.product_code='".$product_code."'
                                                                                    AND iacx.store_audited=iaccc.store_audited
                                                                                    AND iacx.date_end<'".$date_start."' 
                                                                                )
                                                    ) as past_variance_2,

                                (
                                            
                                    SELECT COALESCE( max(iaccc.date_end),0)
                                                FROM inventory_actual_count iaccc 
                                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                and iaccc.product_code='".$product_code."'
                                                and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                            WHERE iacx.product_code='".$product_code."'
                                                                        AND iacx.store_audited=iaccc.store_audited
                                                                        AND iacx.date_end<'".$date_start."' 
                                                                    )
                                        ) as audit_date,

                                            (
                                                        
                                                SELECT 
                                                DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                FROM inventory  iib
                                            WHERE
                                            iib.product_code =p51.product_code
                                            AND
                                            (iib.store_id='".$store_id."')

                                            AND 
                                            iib.status ='received'
                                            AND
                                                
                                                iib.`type`='interbranch'
                                                
                                                

                                            AND
                                                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                        WHERE iacx.product_code='".$product_code."'
                                                                                    AND iacx.store_id='".$store_id."'
                                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                                    AND count !='0'
                                                                                    and status='received'
                                                                                    AND  iacx.type='interbranch'
                                                                                )

                                                            ) as interbranch_status_date,

                                            (

                                            SELECT 
                                            DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                            FROM inventory  iib
                                            WHERE
                                            iib.product_code =p51.product_code
                                            AND
                                            (iib.store_id='".$store_id."')

                                            AND 
                                            iib.status ='received'
                                            AND
                                            iib.type='stock_transfer'

                                            AND
                                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_id='".$store_id."'
                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                    AND count !='0'
                                                                    and status='received'
                                                                    AND  iacx.type='stock_transfer'
                                                                )


                                            ) as stock_transfer_status_date,


                                            (select coalesce(
                                            sum(
                                            if(iinbic.variance_status='approve',
                                            REPLACE( iinbic.actual_count,',',''),
                                            REPLACE( iinbic.`count`,',',''))
                                            ),0) FROM inventory iinbic
                                            WHERE
                                            iinbic.product_code =p51.product_code
                                            AND
                                            iinbic.store_id='".$store_id."'

                                            AND 
                                            iinbic.status ='received' 
                                            AND iinbic.type='interbranch'


                                            
                                            AND
                                                                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                            ) as interbranch_in_past ,


                                            (select coalesce(
                                            sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
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
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                                            ) as stock_transfer_in_past,


                                            ( SELECT if( os.packaging_for!='' && packaging_stock!='store',
                                            '0',
                                            
                                        count(po_number))

                                                    

                                            FROM `orders_specs` os

                                            LEFT JOIN orders o ON o.order_id=os.order_id

                                            WHERE 
                                            payment='y'
                                            And os.status NOT IN ('return','cancelled','returned','failed' )
                                            ". $carekits."   ='".$product_code."'
                                            and coalesce(packaging_stock,'')!='lab'
                                            AND date(os.payment_date)>='2020-02-4'
                                            AND  date(os.payment_date)>audit_date
                                            AND  date(os.payment_date)<'".$date_start."' 
                                                            ".$reRoute." 
                                                            AND( (o.origin_branch='".$store_id."'  and dispatch_type!='packaging') OR ( o.store_id='".$store_id."'  and dispatch_type='packaging') )
                                                            
                                            ) as sales_deduct_physical,

        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iid
                WHERE
                iid.product_code =p51.product_code
                AND
                iid.stock_from='".$store_id."'
            
            AND 
            iid.status ='received' 
            AND iid.type='damage'
            AND
            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_code."'
                                                                                AND iacx.stock_from='".$store_id."'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='damage'
                                                                            )
        ) as damage_date,

        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iip
                            WHERE
                            iip.product_code =p51.product_code
                            AND
                            iip.stock_from='".$store_id."'
                        
                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_code."'
                                                                                AND iacx.stock_from='".$store_id."'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='pullout'
                                                                            )
                    ) as pullout_date,

                            (

                                    SELECT 
                                    DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                    FROM inventory  iib
                                    WHERE
                                    iib.product_code =p51.product_code
                                    AND
                                    (iib.stock_from='".$store_id."')

                                    AND 
                                    iib.status ='received'
                                    AND
                                    iib.type='stock_transfer'

                                    AND
                                    DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                WHERE iacx.product_code='".$product_code."'
                                                            AND iacx.stock_from='".$store_id."'
                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date 
                                                            AND count !='0'
                                                            and status='received'
                                                            AND  iacx.type='stock_transfer'
                                                        )


                            ) as stock_transfer_minus_date,


                                    (select coalesce(
                                    sum(
                                    if(iisi.variance_status='approve',
                                    REPLACE( iisi.actual_count,',',''),
                                    REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                    WHERE
                                    iisi.product_code =p51.product_code
                                    AND
                                    iisi.stock_from='".$store_id."'

                                    AND 
                                    iisi.status ='received' 
                                    AND (iisi.type='stock_transfer'
                                    OR
                                    iisi.type='replenish'
                                    )
                                    AND
                                                                DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                    AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                    ) as stock_transfer_minus_past,

                                   ( select coalesce(
                                        sum(
                                            if(iiiboc.variance_status='approve',
                                            REPLACE(iiiboc.actual_count,',',''),
                                            REPLACE( iiiboc.`count`,',',''))
                                    ),0) FROM inventory  iiiboc
                                          WHERE
                                          iiiboc.product_code =p51.product_code
                                            AND
                                            iiiboc.stock_from=sls.store_id
                                      
                                      AND 
                                      iiiboc.status ='received' 
                                      AND iiiboc.type='interbranch'
                                      
                                      AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                                  ) as interbranch_out_past,

                                 ( select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iiiboc
                                      WHERE
                                      iiiboc.product_code =p51.product_code
                                        AND
                                        iiiboc.stock_from=sls.store_id
                                  
                                  AND 
                                  iiiboc.status ='received' 
                                  AND iiiboc.type='interbranch'
                                  
                                  AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                              ) as interbranch_out_past_date,

                              ( select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                 FROM inventory iinbic
                                WHERE
                                iinbic.product_code =p51.product_code
                                AND
                                iinbic.store_id='".$store_id."'

                                AND 
                                iinbic.status ='received' 
                                AND iinbic.type='interbranch'


                                
                                AND
                                                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                           ) as interbranch_in_past_date ,
                           '0',
                           '0',
                             
                           (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbicd
                                   WHERE
                                   iinbicd.product_code =p51.product_code
                                   AND
                                   iinbicd.store_id='warehouse'
                               
                               AND 
                               iinbicd.status ='received' 
                              AND iinbicd.store_id='".$store_id."'
                           
                              
                             


                                                                   AND
                                                                   DATE(DATE_ADD(iinbicd.status_date, INTERVAL 13 HOUR))>audit_date
                                       AND DATE(DATE_ADD(iinbicd.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                           ) as damage_in_past_date
                                


                            FROM poll_51 p51
                            LEFT JOin inventory ip on ip.product_code=p51.product_code
                            LEFT JOIN stores_locations sls on sls.store_id='".$store_id."'
                                
                                WHERE p51.product_code='".$product_code."'
                                AND  sls.store_id   ='".$store_id."'
                                group by sls.store_id,p51.product_code
                                ";




        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
            $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
            $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34,
            $result35,$result36,$result37,$result38,$result39);
        

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

        // echo"<Br>";
        // print_r($arrInvLook);
        // echo"</br>";
        //////////////////// need sales past

       

        return $arrInvLook;


        


}





function WarehouseChecker_smr_damage($product_Code,$date_start,$date_end) {

    global $conn;
                $datenow=date('Y-m-d');
    
                $grabInvParams= array("store_name",
                "item_name",
                "product_code",
                "beg_inventory",
                "pullout",
                "damage",
                "stock_transfer_out",
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
                "transit_out_c",
                "past_variance",
                "sales_past",
                "past_variance_2",
                "audit_date",
                "Interbranch_status_date",
                "stock_transfer_status_date",
                "interbranch_in_past",
                'stock_transfer_in_past',
                "sales_deduct_physical",
                "damage_past_date",
                "pullout_past_date",
                "stock_transfer_minus_date",
                "stock_transfer_minus",
                "interbranch_out_past",
                "interbranch_out_past_date",
                "interbranch_in_past_date",
                "damage_in_c",
                "damage_in_past",
                "damage_in_past_date"
            );
    
    
    $arrInvLook=array();
    
     $query=" SELECT 
    
                'warehouse_damage',
                        p51.item_name,
                        p51.product_code,(SELECT 
                            coalesce(
                                sum(
                                    if(iib.variance_status='approve',
                                    REPLACE(iib.actual_count,',',''),
                                    REPLACE(iib.`count`,',','')
                                    )
                            ),0)
                        FROM inventory  iib
                    WHERE
                    iib.product_code =p51.product_code
                    AND
                    (iib.store_id='warehouse_damage')
                    
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
                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                    
                        ) as beginventory,
                        
                (select coalesce(
                            sum(
                                if(iip.variance_status='approve',
                                REPLACE(iip.actual_count,',',''),
                                REPLACE(iip.`count`,',','')
                                )
                        ),0) FROM inventory  iip
                                WHERE
                                iip.product_code =p51.product_code
                                AND
                                iip.stock_from='warehouse_damage'
                            
                            AND 
                            iip.status ='received' 
                            AND iip.type='pullout'
                            AND
                            DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as pullout,
    
                (select coalesce(
                            sum(
                                if(iid.variance_status='approve',
                                REPLACE(  iid.actual_count,',',''),
                                REPLACE( iid.`count`,',','')
                                )
                        ),0) FROM inventory iid
                                WHERE
                                iid.product_code =p51.product_code
                                AND
                                iid.stock_from='warehouse_damage'
                            
                            AND 
                            iid.status ='received' 
                            AND iid.type='damage'
                            AND
                            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as damage,
    
                    (select coalesce(
                            sum(
                                if( iiso.variance_status='approve',
                                REPLACE(   iiso.actual_count,',',''),
                                REPLACE(  iiso.`count`,',','')
                                )
                        ),0) FROM inventory iiso
                                WHERE
                                iiso.product_code =p51.product_code
                                AND
                                iiso.stock_from='warehouse_damage'
                            
                            AND 
                            iiso.status ='received' 
                            AND iiso.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as stock_transfer_out,
                        
    
                    (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory  iisi
                                WHERE
                                iisi.product_code =p51.product_code
                                AND
                                iisi.store_id='warehouse_damage'
                            
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
                        ),0) FROM inventory  iisoc
                                WHERE
                                iisoc.product_code =p51.product_code
                                AND
                                iisoc.stock_from='warehouse_damage'
                            
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
                        ),0) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse_damage'
                            
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
                        ),0) FROM inventory iinbic
                                WHERE
                                iinbic.product_code =p51.product_code
                                AND
                                iinbic.store_id='warehouse_damage'
                            
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
                        ),0) FROM inventory ipc
                                WHERE
                                ipc.product_code =p51.product_code
                                    AND
                                    ipc.stock_from='warehouse_damage'
                            
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
                        ),0) FROM inventory iidc
                                WHERE
                                iidc.product_code =p51.product_code
                                    AND
                                    iidc.stock_from='warehouse_damage'
                            
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
                        ),0) FROM inventory  ididc
                                WHERE
                                ididc.product_code =p51.product_code
                                    AND
                                    ididc.store_id='warehouse_damage'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        '0',
                'none',(SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
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
                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                            AND
                                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            
                                    ) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.store_id='warehouse_damage'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,
    
                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.stock_from='warehouse_damage'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c,
                                    (
                                                                
    
                                        SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                                                    FROM inventory_actual_count iaccc 
                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse_damage'
                                                                    and iaccc.product_code='".$product_Code."'
                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                        )
                                                            ) as past_variance,
                                                                    '0',
                                    (
                                                                
    
                                        SELECT COALESCE(sum(iaccc.input_count),0)
                                                                    FROM inventory_actual_count iaccc 
                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse_damage'
                                                                    and iaccc.product_code='".$product_Code."'
                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                        )
                                                            ) as past_variance_2,
                                    (
                                                                
    
                                        SELECT COALESCE( max(iaccc.date_end),0)
                                                                    FROM inventory_actual_count iaccc 
                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse_damage'
                                                                    and iaccc.product_code='".$product_Code."'
                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                        )
                                                            ) as audit_date
    
    
                                                            ,
    
                                                                    (
                                                                                
                                                                        SELECT 
                                                                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                                        FROM inventory  iib
                                                                    WHERE
                                                                    iib.product_code =p51.product_code
                                                                    AND
                                                                    (iib.store_id='warehouse_damage')
                                                                    
                                                                    AND 
                                                                    iib.status ='received'
                                                                    AND
                                                                        
                                                                        iib.`type`='interbranch'
                                                                        
                                                                        
                                                                    
                                                                    AND
                                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                                            AND iacx.store_id='warehouse_damage'
                                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                                                            AND count !='0'
                                                                                                            and status='received'
                                                                                                        )
    
                                                                                     ) as interbranch_status_date,
    
                                                    (
                                                                
                                                        SELECT 
                                                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                        FROM inventory  iib
                                                    WHERE
                                                    iib.product_code =p51.product_code
                                                    AND
                                                    (iib.store_id='warehouse_damage')
    
                                                    AND 
                                                    iib.status ='received'
                                                    AND
                                                        iib.type='stock_transfer'
    
                                                    AND
                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_id='warehouse_damage'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                                            AND count !='0'
                                                                                            and status='received'
                                                                                        )
    
                                                        
                                                            ) as stock_transfer_status_date,
                               
                                
                                (select coalesce(
                                    sum(
                                        if(iinbic.variance_status='approve',
                                        REPLACE( iinbic.actual_count,',',''),
                                        REPLACE( iinbic.`count`,',',''))
                                ),0) FROM inventory iinbic
                                        WHERE
                                        iinbic.product_code =p51.product_code
                                        AND
                                        iinbic.store_id='warehouse_damage'
                                    
                                    AND 
                                    iinbic.status ='received' 
                                    AND iinbic.type='interbranch'
                                
                                   
                                  
    
    
                                                                        AND
                                                                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                                ) as interbranch_in_past ,
                                
    
                                (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                            WHERE
                                            iisi.product_code =p51.product_code
                                            AND
                                            iisi.store_id='warehouse_damage'
                                        
                                        AND 
                                        iisi.status ='received' 
                                        AND (iisi.type='stock_transfer'
                                            OR
                                            iisi.type='replenish'
                                            )
                                      
                                            AND
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                                    ) as stock_transfer_in_past,
                                    
                                    '0' as sales_deduct_physical,
    
        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iid
                WHERE
                iid.product_code =p51.product_code
                AND
                iid.stock_from='warehouse_damage'
            
            AND 
            iid.status ='received' 
            AND iid.type='damage'
            AND
            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                                AND iacx.stock_from='warehouse_damage'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='damage'
                                                                            )
        ) as damage_date,
    
        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iip
                            WHERE
                            iip.product_code =p51.product_code
                            AND
                            iip.stock_from='warehouse_damage'
                        
                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                                AND iacx.stock_from='warehouse_damage'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='pullout'
                                                                            )
                    ) as pullout_date,
    
                        (
                                    
                            SELECT 
                            DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                            FROM inventory  iib
                        WHERE
                        iib.product_code =p51.product_code
                        AND
                        (iib.stock_from='warehouse_damage')
    
                        AND 
                        iib.status ='received'
                        AND
                            iib.type='stock_transfer'
    
                        AND
                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                AND iacx.stock_from='warehouse_damage'
                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                AND count !='0'
                                                                and status='received'
                                                            )
    
                            
                                ) as stock_transfer_minus_date,
                                
    
                                (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                            WHERE
                                            iisi.product_code =p51.product_code
                                            AND
                                            iisi.stock_from='warehouse_damage'
                                        
                                        AND 
                                        iisi.status ='received' 
                                        AND (iisi.type='stock_transfer'
                                            OR
                                            iisi.type='replenish'
                                            )
                                      
                                            AND
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>=audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                                    ) as stock_transfer_minus,
                                    (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse_damage'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past,
                        (select   DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse_damage'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past_date,
                               
                                
                               (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbic
                                       WHERE
                                       iinbic.product_code =p51.product_code
                                       AND
                                       iinbic.store_id='warehouse_damage'
                                   
                                   AND 
                                   iinbic.status ='received' 
                                   AND iinbic.type='interbranch'
                               
                                  
                                 
    
    
                                                                       AND
                                                                       DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                           AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                               ) as interbranch_in_past_date,




                               (select coalesce(
                        sum(
                            if(iidcic.variance_status='approve',
                            REPLACE(  iidcic.actual_count,',',''),
                            REPLACE(  iidcic.`count`,',','')
                            )
                    ),0) FROM inventory iidcic
                            WHERE
                            iidcic.product_code =p51.product_code
                                AND
                                iidcic.store_id='warehouse_damage'
                        
                        AND 
                        iidcic.status ='received' 
                        AND iidcic.type='damage'
                        AND
                        DATE(DATE_ADD(iidcic.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                        AND
                        DATE(DATE_ADD(iidcic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                    ) as damage_in_c,



                    (select coalesce(
                        sum(
                            if(ididcpp.variance_status='approve',
                            REPLACE(  ididcpp.actual_count,',',''),
                            REPLACE( ididcpp.`count`,',','')
                            )
                    ),0) FROM inventory  ididcpp
                            WHERE
                            ididcpp.product_code =p51.product_code
                                AND
                                ididcpp.store_id='warehouse_damage'
                        
                        AND 
                        ididcpp.status ='received' 
                        AND ididcpp.type='damage'
                        AND
                                                                        DATE(DATE_ADD(ididcpp.status_date, INTERVAL 13 HOUR))>audit_date
                        AND
                        DATE(DATE_ADD(ididcpp.status_date, INTERVAL 13 HOUR))<='".$date_start."'
                    ) as damage_in_past,

                    
                    (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbicdp
                            WHERE
                            iinbicdp.product_code =p51.product_code
                                AND
                                iinbicdp.store_id='warehouse_damage'
                        
                        AND 
                        iinbicdp.status ='received' 
                        AND iinbicdp.type='damage'
                        AND
                                                                       DATE(DATE_ADD(iinbicdp.status_date, INTERVAL 13 HOUR))>audit_date
                                           AND DATE(DATE_ADD(iinbicdp.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                    ) as damage_in_past_date
                                    
              
    
            FROM    poll_51 p51
            LEFT JOIN inventory ip on p51.product_code=ip.product_code
            WHERE p51.product_code='".$product_Code."'
           
            group by p51.product_code";
         
    
    
    
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
                    $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34,$result35,$result36
                    ,$result37,$result38,$result39);
                
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
    
                $grabInvParams= array("store_name",
                "item_name",
                "product_code",
                "beg_inventory",
                "pullout",
                "damage",
                "stock_transfer_out",
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
                "transit_out_c",
                "past_variance",
                "sales_past",
                "past_variance_2",
                "audit_date",
                "Interbranch_status_date",
                "stock_transfer_status_date",
                "interbranch_in_past",
                'stock_transfer_in_past',
                "sales_deduct_physical",
                "damage_past_date",
                "pullout_past_date",
                "stock_transfer_minus_date",
                "stock_transfer_minus",
                "interbranch_out_past",
                "interbranch_out_past_date",
                "interbranch_in_past_date",
                "damage_in_c",
                "damage_in_past",
                "damage_in_past_date"
            );
    
    
    $arrInvLook=array();
    
    
             $query=" SELECT 
    
                'warehouse',
                        p51.item_name,
                        p51.product_code,(SELECT 
                            coalesce(
                                sum(
                                    if(iib.variance_status='approve',
                                    REPLACE(iib.actual_count,',',''),
                                    REPLACE(iib.`count`,',','')
                                    )
                            ),0)
                        FROM inventory  iib
                    WHERE
                    iib.product_code =p51.product_code
                    AND
                    (iib.store_id='warehouse_qa')
                    
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
                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                    
                        ) as beginventory,
                        
                (select coalesce(
                            sum(
                                if(iip.variance_status='approve',
                                REPLACE(iip.actual_count,',',''),
                                REPLACE(iip.`count`,',','')
                                )
                        ),0) FROM inventory  iip
                                WHERE
                                iip.product_code =p51.product_code
                                AND
                                iip.stock_from='warehouse_qa'
                            
                            AND 
                            iip.status ='received' 
                            AND iip.type='pullout'
                            AND
                            DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as pullout,
    
                (select coalesce(
                            sum(
                                if(iid.variance_status='approve',
                                REPLACE(  iid.actual_count,',',''),
                                REPLACE( iid.`count`,',','')
                                )
                        ),0) FROM inventory iid
                                WHERE
                                iid.product_code =p51.product_code
                                AND
                                iid.stock_from='warehouse_qa'
                            
                            AND 
                            iid.status ='received' 
                            AND iid.type='damage'
                            AND
                            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as damage,
    
                    (select coalesce(
                            sum(
                                if( iiso.variance_status='approve',
                                REPLACE(   iiso.actual_count,',',''),
                                REPLACE(  iiso.`count`,',','')
                                )
                        ),0) FROM inventory iiso
                                WHERE
                                iiso.product_code =p51.product_code
                                AND
                                iiso.stock_from='warehouse_qa'
                            
                            AND 
                            iiso.status ='received' 
                            AND iiso.type='stock_transfer'
                            AND
                            DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as stock_transfer_out,
                        
    
                    (select coalesce(
                            sum(
                                if(iisi.variance_status='approve',
                                REPLACE(iisi.actual_count,',',''),
                                REPLACE( iisi.`count`,',','')
                                )
                        ),0) FROM inventory  iisi
                                WHERE
                                iisi.product_code =p51.product_code
                                AND
                                iisi.store_id='warehouse_qa'
                            
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
                        ),0) FROM inventory  iisoc
                                WHERE
                                iisoc.product_code =p51.product_code
                                AND
                                iisoc.stock_from='warehouse_qa'
                            
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
                        ),0) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse_qa'
                            
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
                        ),0) FROM inventory iinbic
                                WHERE
                                iinbic.product_code =p51.product_code
                                AND
                                iinbic.store_id='warehouse_qa'
                            
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
                        ),0) FROM inventory ipc
                                WHERE
                                ipc.product_code =p51.product_code
                                    AND
                                    ipc.stock_from='warehouse_qa'
                            
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
                        ),0) FROM inventory iidc
                                WHERE
                                iidc.product_code =p51.product_code
                                    AND
                                    iidc.stock_from='warehouse_qa'
                            
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
                        ),0) FROM inventory  ididc
                                WHERE
                                ididc.product_code =p51.product_code
                                    AND
                                    ididc.store_id='warehouse_damage'
                            
                            AND 
                            ididc.status ='received' 
                            AND ididc.type='damage'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            AND
                            DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                        ) as damage_i,
                        '0',
                'none',(SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
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
                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                            AND
                                                DATE(DATE_ADD(iir.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                            
                                    ) as requested,
                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.store_id='warehouse_qa'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,
    
                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.stock_from='warehouse_qa'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            
                                                         AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_out_c,
                                    (
                                                                
    
                                        SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                                                    FROM inventory_actual_count iaccc 
                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse_qa'
                                                                    and iaccc.product_code='".$product_Code."'
                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                        )
                                                            ) as past_variance,
                                                                    '0',
                                    (
                                                                
    
                                        SELECT COALESCE(sum(iaccc.input_count),0)
                                                                    FROM inventory_actual_count iaccc 
                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse_qa'
                                                                    and iaccc.product_code='".$product_Code."'
                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                        )
                                                            ) as past_variance_2,
                                    (
                                                                
    
                                        SELECT COALESCE( max(iaccc.date_end),0)
                                                                    FROM inventory_actual_count iaccc 
                                                                    where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='warehouse_qa'
                                                                    and iaccc.product_code='".$product_Code."'
                                                                    and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_audited=iaccc.store_audited
                                                                                            AND iacx.date_end<'".$date_start."' 
                                                                                        )
                                                            ) as audit_date
    
    
                                                            ,
    
                                                                    (
                                                                                
                                                                        SELECT 
                                                                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                                        FROM inventory  iib
                                                                    WHERE
                                                                    iib.product_code =p51.product_code
                                                                    AND
                                                                    (iib.store_id='warehouse_qa')
                                                                    
                                                                    AND 
                                                                    iib.status ='received'
                                                                    AND
                                                                        
                                                                        iib.`type`='interbranch'
                                                                        
                                                                        
                                                                    
                                                                    AND
                                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                                            AND iacx.store_id='warehouse_qa'
                                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                                                            AND count !='0'
                                                                                                            and status='received'
                                                                                                        )
    
                                                                                     ) as interbranch_status_date,
    
                                                    (
                                                                
                                                        SELECT 
                                                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                        FROM inventory  iib
                                                    WHERE
                                                    iib.product_code =p51.product_code
                                                    AND
                                                    (iib.store_id='warehouse_qa')
    
                                                    AND 
                                                    iib.status ='received'
                                                    AND
                                                        iib.type='stock_transfer'
    
                                                    AND
                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                WHERE iacx.product_code='".$product_Code."'
                                                                                            AND iacx.store_id='warehouse_qa'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                                            AND count !='0'
                                                                                            and status='received'
                                                                                        )
    
                                                        
                                                            ) as stock_transfer_status_date,
                               
                                
                                (select coalesce(
                                    sum(
                                        if(iinbic.variance_status='approve',
                                        REPLACE( iinbic.actual_count,',',''),
                                        REPLACE( iinbic.`count`,',',''))
                                ),0) FROM inventory iinbic
                                        WHERE
                                        iinbic.product_code =p51.product_code
                                        AND
                                        iinbic.store_id='warehouse_qa'
                                    
                                    AND 
                                    iinbic.status ='received' 
                                    AND iinbic.type='interbranch'
                                
                                   
                                  
    
    
                                                                        AND
                                                                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                                ) as interbranch_in_past ,
                                
    
                                (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                            WHERE
                                            iisi.product_code =p51.product_code
                                            AND
                                            iisi.store_id='warehouse_qa'
                                        
                                        AND 
                                        iisi.status ='received' 
                                        AND (iisi.type='stock_transfer'
                                            OR
                                            iisi.type='replenish'
                                            )
                                      
                                            AND
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                                    ) as stock_transfer_in_past,
                                    ( SELECT count(po_number)
                                                            
                                                                            
                                                
                                                            FROM `orders_specs` os
                                                
                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                
                                                        WHERE 
                                                        payment='y'
                                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                                            AND os.product_code='".$product_Code."'
                                                            AND date(os.payment_date)>='2020-02-4'
                                                            
                                                            AND  date(os.payment_date)>audit_date
                                                            
                                                            AND os.lens_option='with prescription' 
                                                             AND os.lens_code NOT IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')
    
                                                
                                                            AND   os.stock_from='warehouse_qa'
                                            ) as sales_deduct_physical,
    
        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iid
                WHERE
                iid.product_code =p51.product_code
                AND
                iid.stock_from='warehouse_qa'
            
            AND 
            iid.status ='received' 
            AND iid.type='damage'
            AND
            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                                AND iacx.stock_from='warehouse_qa'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='damage'
                                                                            )
        ) as damage_date,
    
        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iip
                            WHERE
                            iip.product_code =p51.product_code
                            AND
                            iip.stock_from='warehouse_qa'
                        
                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                                AND iacx.stock_from='warehouse_qa'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='pullout'
                                                                            )
                    ) as pullout_date,
    
                        (
                                    
                            SELECT 
                            DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                            FROM inventory  iib
                        WHERE
                        iib.product_code =p51.product_code
                        AND
                        (iib.stock_from='warehouse_qa')
    
                        AND 
                        iib.status ='received'
                        AND
                            iib.type='stock_transfer'
    
                        AND
                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                    WHERE iacx.product_code='".$product_Code."'
                                                                AND iacx.stock_from='warehouse_qa'
                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>audit_date
                                                                AND count !='0'
                                                                and status='received'
                                                            )
    
                            
                                ) as stock_transfer_minus_date,
                                
    
                                (select coalesce(
                                        sum(
                                            if(iisi.variance_status='approve',
                                            REPLACE( iisi.actual_count,',',''),
                                            REPLACE( iisi.`count`,',',''))
                                    ),0) FROM inventory  iisi
                                            WHERE
                                            iisi.product_code =p51.product_code
                                            AND
                                            iisi.stock_from='warehouse_qa'
                                        
                                        AND 
                                        iisi.status ='received' 
                                        AND (iisi.type='stock_transfer'
                                            OR
                                            iisi.type='replenish'
                                            )
                                      
                                            AND
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>=audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                                    ) as stock_transfer_minus,
                                    (select coalesce(
                            sum(
                                if(iiiboc.variance_status='approve',
                                REPLACE( iiiboc.actual_count,',',''),
                                REPLACE(  iiiboc.`count`,',','')
                                )
                        ),0) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse_qa'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past,
                        (select   DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory  iiiboc
                                WHERE
                                iiiboc.product_code =p51.product_code
                                AND
                                iiiboc.stock_from='warehouse_qa'
                            
                            AND 
                            iiiboc.status ='received' 
                            AND iiiboc.type='interbranch'
                            
                            AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>=audit_date
                                    AND
                                    DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past_date,
                               
                                
                               (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbic
                                       WHERE
                                       iinbic.product_code =p51.product_code
                                       AND
                                       iinbic.store_id='warehouse_qa'
                                   
                                   AND 
                                   iinbic.status ='received' 
                                   AND iinbic.type='interbranch'
                               
                                  
                                 
    
    
                                                                       AND
                                                                       DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                           AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                               ) as interbranch_in_past_date,
                               '0',
                               '0',
                                 
                               (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory iinbic
                                       WHERE
                                       iinbic.product_code =p51.product_code
                                       AND
                                       iinbic.store_id='warehouse_qa'
                                   
                                   AND 
                                   iinbic.status ='received' 
                                   AND iinbic.type='damage'
                               
                                  
                                 
    
    
                                                                       AND
                                                                       DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                                           AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<= '".$date_start."' 
                               ) as damage_in_past_date
                                    
                                    
              
    
            FROM    poll_51 p51
            LEFT JOIN inventory ip on p51.product_code=ip.product_code
            WHERE p51.product_code='".$product_Code."'
           
            group by p51.product_code";
         
    
    
    
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
                    $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34,
                    $result35,$result36,$result37,$result38,$result39);
                
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
							FROM `inventory_actual_count`
							 WHERE store_audited='".$store_id."' 
							 and  date_end =(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
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
							FROM `inventory_actual_count`
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