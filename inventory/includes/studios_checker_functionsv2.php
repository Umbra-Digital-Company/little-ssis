<?php 







function WarehouseChecker_auditor($product_Code,$date_start,$date_end) {

    global $conn;
                $datenow=date('Y-m-d');
                $dateFstart= date('Y-m-d',strtotime($date_start.'-1 days')) ;
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
                                            FROM `inventory_actual_count_studios`
                                            WHERE store_audited='warehouse' 
                                            and  date_end ='".$dateFstart."'
                                            and product_code='".$product_Code."'
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
                "number"
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
                        from inventory_studios   iib
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
                        ),0) from inventory_studios   iip
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
                        ),0) from inventory_studios  iid
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
                        ),0) from inventory_studios  iiso
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
                        ),0) from inventory_studios   iisi
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
                        ),0) from inventory_studios   iisoc
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
                        ),0) from inventory_studios   iiiboc
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
                        ),0) from inventory_studios  iinbic
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
                        ),0) from inventory_studios  ipc
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
                        ),0) from inventory_studios  iidc
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
                        ),0) from inventory_studios   ididc
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
                'none'
            FROM   inventory_studios ip 
            LEFT JOIN poll_51_studios p51 on p51.product_code=ip.product_code
            WHERE ip.product_code='".$product_Code."'
            AND (ip.stock_from='warehouse' OR ip.store_id='warehouse')
            group by ip.product_code";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16);
                
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

                if( $arrInvLook){
                    $arrInvLook2=$arrInvLook;

                }else{
                    $arrInvLook2[0]["store_name"] ="0";
                    $arrInvLook2[0]["item_name"]="0";
                    $arrInvLook2[0]["product_code"]="0";
                    $arrInvLook2[0]["beg_inventory"]="0";
                    $arrInvLook2[0]["pullout"]="0";
                    $arrInvLook2[0]["damage"]="0";
                    $arrInvLook2[0]["stock_transfer_out"]="0";
                    $arrInvLook2[0][ "stock_transfer_in_c"]="0";
                    $arrInvLook2[0]["stock_transfer_out_c"]="0";
                    $arrInvLook2[0]["interbranch_out_c"]="0";
                    $arrInvLook2[0]["interbranch_in_c"]="0";
                    $arrInvLook2[0]["pullout_c"]="0";
                    $arrInvLook2[0]["damage_c"]="0";
                    $arrInvLook2[0]["damage_i"]="0";
                    $arrInvLook2[0]["sales"]="0";
                    $arrInvLook2[0]["number"]="0";
                    $arrInvLook2[0]["transit_out"]="0";
                }


                $beg_inventory = $arrInvLook2[0]["beg_inventory"]-$arrInvLook2[0]["pullout"]-$arrInvLook2[0]["damage"]-$arrInvLook2[0]["stock_transfer_out"]-$arrInvLook2[0]["sales"];

                $runningtotal=  $beg_inventory +$arrInvLook2[0]["stock_transfer_in_c"]
                    +$arrInvLook2[0]["interbranch_in_c"]- $arrInvLook2[0]["stock_transfer_out_c"]-
                    $arrInvLook2[0]["interbranch_out_c"]-$arrInvLook2[0]["damage_c"]-$arrInvLook2[0]["pullout_c"]-$arrInvLook2[0]["sales"]; 


        return $runningtotal;

}
    


function StoreChecker_auditor($product_code,$store_id,$date_start,$date_end){
    
    global $conn;

   

                
            $dateFstart= date('Y-m-d',strtotime($date_start.'-1 days')) ;
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
                                        FROM `inventory_actual_count_studios`
                                        WHERE store_audited='".$store_id."' 
                                        and  date_end ='".$dateFstart."'
                                        and product_code='".$product_code."'
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
            // else {

            //     echo mysqli_error($conn);

            // };
                
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
                                                || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code)  || preg_match("/MRK/i",$product_code) 
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
                    p51.product_code,
                    coalesce(pactual.input_count,0) as beginventory,
                 (select coalesce(
                        sum(
                            if(iisi.variance_status='approve',
                            REPLACE(iisi.actual_count,',',''),
                            REPLACE( iisi.`count`,',','')
                            )
                    ),0) FROM inventory_studios  iisi
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
                    ),0) FROM inventory_studios  iisoc
                            WHERE
                            iisoc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios  iiiboc
                            WHERE
                            iiiboc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios iinbic
                            WHERE
                            iinbic.product_code =p51.product_code
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
                    ),0) FROM inventory_studios ipc
                            WHERE
                            ipc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios iidc
                            WHERE
                            iidc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios  ididc
                            WHERE
                            ididc.product_code =p51.product_code
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
            (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                                WHERE
                                                    ito.product_code =p51.product_code
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
                             FROM inventory_studios iir
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
                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                                WHERE
                                                    ito.product_code =p51.product_code
                                                                    AND
                                                                    ito.store_id='".$store_id."'
                                                        
                                                        AND 
                                                        ito.status ='in transit' 
                                                        
                                                    
                                                        
                                                        AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                
                                                ) AS transit_in,

                                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                                WHERE
                                                    ito.product_code =p51.product_code
                                                                    AND
                                                                    ito.stock_from='".$store_id."'
                                                        
                                                        AND 
                                                        ito.status ='in transit' 
                                                        
                                                    
                                                        
                                                     AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                
                                                ) AS transit_out_c
                            
                                
          

        FROM    poll_51_studios_new p51
      LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited='".$store_id."' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                            WHERE iacx.store_audited='".$store_id."'
                            AND iacx.product_code='".$product_code."'
                        AND iacx.date_end<'".$date_start."' 
                    ) AND pactual.product_code='".$product_code."'
                    LEFT JOIN store_codes_studios sls on sls.store_code='".$store_id."' 
        WHERE p51.product_code='".$product_code."'
    
        group by p51.product_code";
     

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
                    //     if($product_code=='60243-2'){
                        // echo "<pre>";
                        // print_r($arrInvLook);
                        // echo "</pre>";
                    // }
            
                    $arrActualX3=array();
                    for ($i=0;$i<sizeof($arrActualCount2);$i++) {
                        $arrActualX3[$i]=$arrActualCount2[$i]["product_code"];
                    }
                    $beg_inventoryx =$arrInvLook[0]["beg_inventory"];
                    $beg_inventory=$beg_inventoryx;
            
                        $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                            +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                            $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"] -$arrInvLook[0]["sales"]+$arrInvLook[0]["damage_in_c"]; 
        
   return $runningtotal;
}



function StoreChecker_auditor_VS($product_code,$store_id,$date_start,$date_end){
    
    global $conn;

  
                
            $dateFstart= date('Y-m-d',strtotime($date_start.'-1 days')) ;
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
                                        FROM `inventory_actual_count_studios`
                                        WHERE store_audited='".$store_id."' 
                                        and  date_end ='".$dateFstart."'
                                        and product_code='".$product_code."'
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
                                                  || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C1/i",$product_code) 
                                                  || preg_match("/MRK/i",$product_code) 
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
                    p51.product_code,
                    coalesce(pactual.input_count,0) as beginventory,
                 (select coalesce(
                        sum(
                            if(iisi.variance_status='approve',
                            REPLACE(iisi.actual_count,',',''),
                            REPLACE( iisi.`count`,',','')
                            )
                    ),0) FROM inventory_studios  iisi
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
                    ),0) FROM inventory_studios  iisoc
                            WHERE
                            iisoc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios  iiiboc
                            WHERE
                            iiiboc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios iinbic
                            WHERE
                            iinbic.product_code =p51.product_code
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
                    ),0) FROM inventory_studios ipc
                            WHERE
                            ipc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios iidc
                            WHERE
                            iidc.product_code =p51.product_code
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
                    ),0) FROM inventory_studios  ididc
                            WHERE
                            ididc.product_code =p51.product_code
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
            (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                                WHERE
                                                    ito.product_code =p51.product_code
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
                             FROM inventory_studios iir
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
                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                                WHERE
                                                    ito.product_code =p51.product_code
                                                                    AND
                                                                    ito.store_id='".$store_id."'
                                                        
                                                        AND 
                                                        ito.status ='in transit' 
                                                        
                                                    
                                                        
                                                        AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                
                                                ) AS transit_in,

                                                (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                                WHERE
                                                    ito.product_code =p51.product_code
                                                                    AND
                                                                    ito.stock_from='".$store_id."'
                                                        
                                                        AND 
                                                        ito.status ='in transit' 
                                                        
                                                    
                                                        
                                                     AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                                
                                                ) AS transit_out_c
                            
                                
          

        FROM    poll_51_studios_new p51
      LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited='".$store_id."' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                            WHERE iacx.store_audited='".$store_id."'
                            AND iacx.product_code='".$product_code."'
                        AND iacx.date_end<'".$date_start."' 
                    ) AND pactual.product_code='".$product_code."'
            LEFT JOIN store_codes_studios sls on sls.store_code='".$store_id."'
        WHERE p51.product_code='".$product_code."'
    
        group by p51.product_code";
     

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

                        // echo "<pre>";
                        // print_r($arrInvLook);
                        // echo "</pre>";
            
            

                        $arrActualX3=array();
                        for ($i=0;$i<sizeof($arrActualCount2);$i++) {
                            $arrActualX3[$i]=$arrActualCount2[$i]["product_code"];
                        }
                        $beg_inventoryx =$arrInvLook[0]["beg_inventory"];
                        $beg_inventory=$beg_inventoryx;
                
                            $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                                +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                                $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"] -$arrInvLook[0]["sales"]+$arrInvLook[0]["damage_in_c"]; 
            
   return $runningtotal;
}

function StoreChecker_auditor_MS($product_code,$store_id,$date_start,$date_end){
    
    global $conn;

  
                
            $dateFstart= date('Y-m-d',strtotime($date_start.'-1 days')) ;
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
                                        FROM `inventory_actual_count_studios`
                                        WHERE store_audited='".$store_id."' 
                                        and  date_end ='".$dateFstart."'
                                        and product_code='".$product_code."'
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
                                        || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C1/i",$product_code) 
                                        || preg_match("/MRK/i",$product_code) 
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

            sls.lab_name,
            p51.item_name,
            p51.product_code,
            coalesce(pactual.input_count,0) as beginventory,
         (select coalesce(
                sum(
                    if(iisi.variance_status='approve',
                    REPLACE(iisi.actual_count,',',''),
                    REPLACE( iisi.`count`,',','')
                    )
            ),0) FROM inventory_studios  iisi
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
            ),0) FROM inventory_studios  iisoc
                    WHERE
                    iisoc.product_code =p51.product_code
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
            ),0) FROM inventory_studios  iiiboc
                    WHERE
                    iiiboc.product_code =p51.product_code
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
            ),0) FROM inventory_studios iinbic
                    WHERE
                    iinbic.product_code =p51.product_code
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
            ),0) FROM inventory_studios ipc
                    WHERE
                    ipc.product_code =p51.product_code
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
            ),0) FROM inventory_studios iidc
                    WHERE
                    iidc.product_code =p51.product_code
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
            ),0) FROM inventory_studios  ididc
                    WHERE
                    ididc.product_code =p51.product_code
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
    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                        WHERE
                                            ito.product_code =p51.product_code
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
                     FROM inventory_studios iir
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
                        (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                        WHERE
                                            ito.product_code =p51.product_code
                                                            AND
                                                            ito.store_id='".$store_id."'
                                                
                                                AND 
                                                ito.status ='in transit' 
                                                
                                            
                                                
                                                AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                        
                                        ) AS transit_in,

                                        (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
                                        WHERE
                                            ito.product_code =p51.product_code
                                                            AND
                                                            ito.stock_from='".$store_id."'
                                                
                                                AND 
                                                ito.status ='in transit' 
                                                
                                            
                                                
                                             AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
                                        
                                        ) AS transit_out_c
                    
                        
  

FROM    poll_51_studios_new p51
LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited='".$store_id."' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                    WHERE iacx.store_audited='".$store_id."'
                    AND iacx.product_code='".$product_code."'
                AND iacx.date_end<'".$date_start."' 
            ) AND pactual.product_code='".$product_code."'
            LEFT JOIN labs_locations sls on sls.lab_id='".$store_id."'
WHERE p51.product_code='".$product_code."'
AND  sls.lab_id   ='".$store_id."'
group by p51.product_code";


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

                        // echo "<pre>";
                        // echo  $query;
                        // echo "</pre>";
            
            
                        $arrActualX3=array();
                        for ($i=0;$i<sizeof($arrActualCount2);$i++) {
                            $arrActualX3[$i]=$arrActualCount2[$i]["product_code"];
                        }
                        $beg_inventoryx =$arrInvLook[0]["beg_inventory"];
                        $beg_inventory=$beg_inventoryx;
                
                            $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                                +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                                $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"] -$arrInvLook[0]["sales"]+$arrInvLook[0]["damage_in_c"]; 
            


   return $runningtotal;
}

function WarehouseChecker_auditor_qa($product_Code,$date_start,$date_end) {

    global $conn;
                $datenow=date('Y-m-d');


                $dateFstart= date('Y-m-d',strtotime($date_start.'-1 days')) ;
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
                                            FROM `inventory_actual_count_studios`
                                            WHERE store_audited='warehouse_qa' 
                                            and  date_end ='".$dateFstart."'
                                            and product_code='".$product_Code."'
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
                "number"
            );


    $arrInvLook=array();
    $query=" SELECT 

                'warehouse_qa',
                        p51.item_name,
                        p51.product_code,(SELECT 
                            coalesce(
                                sum(
                                    if(iib.variance_status='approve',
                                    REPLACE(iib.actual_count,',',''),
                                    REPLACE(iib.`count`,',','')
                                    )
                            ),0)
                        from inventory_studios   iib
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
                        ),0) from inventory_studios   iip
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
                        ),0) from inventory_studios  iid
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
                        ),0) from inventory_studios  iiso
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
                        ),0) from inventory_studios   iisi
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
                        ),0) from inventory_studios   iisoc
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
                        ),0) from inventory_studios   iiiboc
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
                        ),0) from inventory_studios  iinbic
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
                        ),0) from inventory_studios  ipc
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
                        ),0) from inventory_studios  iidc
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
                        ),0) from inventory_studios   ididc
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
                'none'
            FROM   inventory_studios ip 
            LEFT JOIN poll_51_studios p51 on p51.product_code=ip.product_code
            WHERE ip.product_code='".$product_Code."'
            AND (ip.stock_from='warehouse_qa' OR ip.store_id='warehouse_qa')
            group by ip.product_code";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16);
                
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

                if( $arrInvLook){
                    $arrInvLook2=$arrInvLook;

                }else{
                    $arrInvLook2[0]["store_name"] ="0";
                    $arrInvLook2[0]["item_name"]="0";
                    $arrInvLook2[0]["product_code"]="0";
                    $arrInvLook2[0]["beg_inventory"]="0";
                    $arrInvLook2[0]["pullout"]="0";
                    $arrInvLook2[0]["damage"]="0";
                    $arrInvLook2[0]["stock_transfer_out"]="0";
                    $arrInvLook2[0][ "stock_transfer_in_c"]="0";
                    $arrInvLook2[0]["stock_transfer_out_c"]="0";
                    $arrInvLook2[0]["interbranch_out_c"]="0";
                    $arrInvLook2[0]["interbranch_in_c"]="0";
                    $arrInvLook2[0]["pullout_c"]="0";
                    $arrInvLook2[0]["damage_c"]="0";
                    $arrInvLook2[0]["damage_i"]="0";
                    $arrInvLook2[0]["sales"]="0";
                    $arrInvLook2[0]["number"]="0";
                    $arrInvLook2[0]["transit_out"]="0";
                }


                $beg_inventory = $arrInvLook2[0]["beg_inventory"]-$arrInvLook2[0]["pullout"]-$arrInvLook2[0]["damage"]-$arrInvLook2[0]["stock_transfer_out"]-$arrInvLook2[0]["sales"];

                $runningtotal=  $beg_inventory +$arrInvLook2[0]["stock_transfer_in_c"]
                    +$arrInvLook2[0]["interbranch_in_c"]- $arrInvLook2[0]["stock_transfer_out_c"]-
                    $arrInvLook2[0]["interbranch_out_c"]-$arrInvLook2[0]["damage_c"]-$arrInvLook2[0]["pullout_c"]-$arrInvLook2[0]["sales"]; 


        return $runningtotal;

}


function WarehouseChecker_auditor_damage($product_Code,$date_start,$date_end) {

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
                "number"
            );
            $dateFstart= date('Y-m-d',strtotime($date_start.'-1 days')) ;
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
                                        FROM `inventory_actual_count_studios`
                                        WHERE store_audited='warehouse_damage' 
                                        and  date_end ='".$dateFstart."'
                                        and product_code='".$product_Code."'
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
                        from inventory_studios   iib
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
                        ),0) from inventory_studios   iip
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
                        ),0) from inventory_studios  iid
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
                        ),0) from inventory_studios  iiso
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
                        ),0) from inventory_studios   iisi
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
                        ),0) from inventory_studios   iisoc
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
                        ),0) from inventory_studios   iiiboc
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
                        ),0) from inventory_studios  iinbic
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
                        ),0) from inventory_studios  ipc
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
                        ),0) from inventory_studios  iidc
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
                        ),0) from inventory_studios   ididc
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
                'none'
            FROM   inventory_studios ip 
            LEFT JOIN poll_51_studios p51 on p51.product_code=ip.product_code
            WHERE ip.product_code='".$product_Code."'
            AND (ip.stock_from='warehouse_damage' OR ip.store_id='warehouse_damage')
            group by ip.product_code";
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                    $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16);
                
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
    
                if( $arrInvLook){
                    $arrInvLook2=$arrInvLook;
    
                }else{
                    $arrInvLook2[0]["store_name"] ="0";
                    $arrInvLook2[0]["item_name"]="0";
                    $arrInvLook2[0]["product_code"]="0";
                    $arrInvLook2[0]["beg_inventory"]="0";
                    $arrInvLook2[0]["pullout"]="0";
                    $arrInvLook2[0]["damage"]="0";
                    $arrInvLook2[0]["stock_transfer_out"]="0";
                    $arrInvLook2[0][ "stock_transfer_in_c"]="0";
                    $arrInvLook2[0]["stock_transfer_out_c"]="0";
                    $arrInvLook2[0]["interbranch_out_c"]="0";
                    $arrInvLook2[0]["interbranch_in_c"]="0";
                    $arrInvLook2[0]["pullout_c"]="0";
                    $arrInvLook2[0]["damage_c"]="0";
                    $arrInvLook2[0]["damage_i"]="0";
                    $arrInvLook2[0]["sales"]="0";
                    $arrInvLook2[0]["number"]="0";
                    $arrInvLook2[0]["transit_out"]="0";
                }
    
    
                $beg_inventory = $arrInvLook2[0]["beg_inventory"]-$arrInvLook2[0]["pullout"]-$arrInvLook2[0]["damage"]-$arrInvLook2[0]["stock_transfer_out"]-$arrInvLook2[0]["sales"];
    
                $runningtotal=  $beg_inventory +$arrInvLook2[0]["stock_transfer_in_c"]
                    +$arrInvLook2[0]["interbranch_in_c"]- $arrInvLook2[0]["stock_transfer_out_c"]-
                    $arrInvLook2[0]["interbranch_out_c"]-$arrInvLook2[0]["damage_c"]-$arrInvLook2[0]["pullout_c"]-$arrInvLook2[0]["sales"]; 
    
    
        return $runningtotal;
    
 }
?>