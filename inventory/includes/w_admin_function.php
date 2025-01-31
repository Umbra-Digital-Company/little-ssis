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
                "sales_deduct_physical"
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
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                                                            AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                    
                                                    ) AS transit_in,

                                                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                    WHERE
                                                        ito.product_code =p51.product_code
                                                                        AND
                                                                        ito.stock_from='warehouse'
                                                            
                                                            AND 
                                                            ito.status ='in transit' 
                                                            
                                                        
                                                            AND
                                                            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
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
                                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                                            AND iacx.store_id='warehouse'
                                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
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
                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                            AND iacx.store_id='warehouse'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
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
                                                                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))=interbranch_status_date
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
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))=stock_transfer_status_date
                                    ) as stock_transfer_in_past,
                                    ( SELECT count(po_number)
                                                            
                                                                            
                                                
                                                            FROM `orders_specs` os
                                                
                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                
                                                        WHERE 
                                                        payment='y'
                                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                                            AND os.product_code='".$product_code."'
                                                            AND date(os.payment_date)>='2020-02-4'
                                                            
                                                            AND  date(os.payment_date)>=audit_date
                                                            
                                                            AND os.lens_option='with prescription' 
                                                            AND os.lens_code!='SO1001'
                                                
                                                            AND  o.laboratory=warehouse
                                            ) as sales_deduct_physical
            FROM    poll_51 p51
            LEFT JOIN inventory ip on p51.product_code=ip.product_code
            WHERE p51.product_code='".$product_Code."'
           
            group by p51.product_code";
         



                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
                    $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29);
                
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


        return $arrInvLook;

}
    


function labChecker_smr($product_code,$store_id,$date_start,$date_end){


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
                "sales_deduct_physical"
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
                                ( SELECT count(po_number)
                        
                                        

                                            FROM `orders_specs` os

                                            LEFT JOIN orders o ON o.order_id=os.order_id

                                        WHERE 
                                        payment='y'
                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                            AND os.product_code='".$product_code."'
                                            AND date(os.payment_date)>='2020-02-4'
                                            AND  date(os.payment_date)>='".$date_start."'
                                            AND  date(os.payment_date)<='".$date_end."'
                                            
                                            AND os.lens_option='with prescription' 
                                            AND os.lens_code!='SO1001'

                                            AND  o.laboratory=ll.lab_id
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
                                                                
                                                            
                                                                AND
                                                                DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                                                                AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'

                                                        ) AS transit_in,

                                                        (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                                        WHERE
                                                            ito.product_code =p51.product_code
                                                                            AND
                                                                            ito.stock_from='".$store_id."'
                                                                
                                                                AND 
                                                                ito.status ='in transit' 
                                                                
                                                            
                                                                AND
                                                                DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
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

                                                                    ( SELECT count(po_number)
                                                            
                                                                            
                                                
                                                                            FROM `orders_specs` os
                                                                
                                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                                
                                                                        WHERE 
                                                                        payment='y'
                                                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                                                            AND os.product_code='".$product_code."'
                                                                            AND date(os.payment_date)>='2020-02-4'
                                                                            
                                                                            AND  date(os.payment_date)<='".$date_start."'
                                                                            
                                                                            AND os.lens_option='with prescription' 
                                                                            AND os.lens_code!='SO1001'
                                                                
                                                                            AND  o.laboratory='".$store_id."'
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
                                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
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
                                                    (iib.store_id='".$store_id."')

                                                    AND 
                                                    iib.status ='received'
                                                    AND
                                                        iib.type='stock_transfer'

                                                    AND
                                                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                                                WHERE iacx.product_code='".$product_code."'
                                                                                            AND iacx.store_id='".$store_id."'
                                                                                            AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
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
                                        iinbic.store_id='".$store_id."'
                                    
                                    AND 
                                    iinbic.status ='received' 
                                    AND iinbic.type='interbranch'
                                
                                   
                                    AND
                                                                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))=interbranch_status_date
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
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))=stock_transfer_status_date
                                    ) as stock_transfer_in_past,
                                    ( SELECT count(po_number)
                                                            
                                                                            
                                                
                                                            FROM `orders_specs` os
                                                
                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                
                                                        WHERE 
                                                        payment='y'
                                                        And os.status NOT IN ('return','cancelled','returned','failed' )
                                                            AND os.product_code='".$product_code."'
                                                            AND date(os.payment_date)>='2020-02-4'
                                                            
                                                            AND  date(os.payment_date)>=audit_date
                                                            AND  date(os.payment_date)<='".$date_start."' 
                                                            AND os.lens_option='with prescription' 
                                                            AND os.lens_code!='SO1001'
                                                
                                                            AND  o.laboratory='".$store_id."'
                                            ) as sales_deduct_physical


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
            $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29);

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
            "sales_deduct_physical"
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
                                            
                                            ( SELECT count(po_number)
                                                    
                                            
                                            FROM `orders_specs` os
                                            
                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                            
                                            WHERE 
                                            payment='y'
                                            And os.status NOT IN ('return','cancelled','returned','failed' )
                                            AND date(os.payment_date)>='2020-02-4'
                                            AND  date(os.payment_date)>='".$date_start."'
                                            AND  date(os.payment_date)<='".$date_end."'
                                            AND os.product_code='".$product_code."'
                                            AND (
                                            os.lens_option='without prescription'
                                            OR
                                            os.lens_code='SO1001')
                                            AND  origin_branch=sls.store_id   
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
                                        
                                    
                                        AND
                                        DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
                                        AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'

                                ) AS transit_in,

                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory ito
                                WHERE
                                    ito.product_code =p51.product_code
                                                    AND
                                                    ito.stock_from='".$store_id."'
                                        
                                        AND 
                                        ito.status ='in transit' 
                                        
                                    
                                        AND
                                        DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
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

                                            ( SELECT count(po_number)
                                    
                                                    

                                                    FROM `orders_specs` os
                                        
                                                    LEFT JOIN orders o ON o.order_id=os.order_id
                                        
                                                    WHERE 
                                                    payment='y'
                                                    And os.status NOT IN ('return','cancelled','returned','failed' )
                                                    AND date(os.payment_date)>='2020-02-4'
                                                  
                                                    AND  date(os.payment_date)<='".$date_start."'
                                                    AND os.product_code='".$product_code."'
                                                    AND (
                                                    os.lens_option='without prescription'
                                                    OR
                                                    os.lens_code='SO1001')
                                                    AND  origin_branch=sls.store_id    
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
                                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
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
                                            (iib.store_id='".$store_id."')

                                            AND 
                                            iib.status ='received'
                                            AND
                                            iib.type='stock_transfer'

                                            AND
                                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_id='".$store_id."'
                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
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
                                            iinbic.store_id='".$store_id."'

                                            AND 
                                            iinbic.status ='received' 
                                            AND iinbic.type='interbranch'


                                            AND
                                                DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))=interbranch_status_date
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
                                                DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))=stock_transfer_status_date
                                            ) as stock_transfer_in_past,
                                            ( SELECT count(po_number)

                                                    

                                            FROM `orders_specs` os

                                            LEFT JOIN orders o ON o.order_id=os.order_id

                                            WHERE 
                                            payment='y'
                                            And os.status NOT IN ('return','cancelled','returned','failed' )
                                            AND os.product_code='".$product_code."'
                                            AND date(os.payment_date)>='2020-02-4'

                                            AND  date(os.payment_date)>=audit_date
                                                            AND  date(os.payment_date)<='".$date_start."' 
                                            AND (
                                                    os.lens_option='without prescription'
                                                    OR
                                                    os.lens_code='SO1001')
                                                    AND  origin_branch=sls.store_id    
                                            ) as sales_deduct_physical


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
            $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29);

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