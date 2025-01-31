<?php 







function WarehouseChecker_auditor($product_Code,$date_start,$date_end) {

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

    if($store_id=='787'  || $store_id=='788' || $store_id=='789'){
        $reRoute=" AND  date(os.payment_date)>='2020-06-25' ";
          // AND os.product_upgrade!='PL0010'
        }else{
        $reRoute=" 
        AND  os.lens_code!='L035'
        AND (
                        os.lens_option='without prescription'
                        OR
                        os.lens_code IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')
            ) 
            and  os.product_code!='F100' 
            and  os.product_code!='S100' 
            
        ";
        }


        // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
        if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
        || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                            || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code)  || preg_match("/P1/i",$product_code) 
                            || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)   || preg_match("/SC/i",$product_code)
                              || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                              || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) || preg_match("/SSGWPCB/i",$product_code) 
                              || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code)  || preg_match("/TB0/i",$product_code)  || preg_match("/SGC/i",$product_code)  
                              || preg_match("/DS/i",$product_code)   || preg_match("/ST/i",$product_code)   || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code)  || preg_match("/CPV/i",$product_code)  
                              || preg_match("/SWS/i",$product_code)   || preg_match("/SMHP/i",$product_code) || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code)
                               || preg_match("/SMZ/i",$product_code)  || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code) 
                               || preg_match("/SS00/i",$product_code) || preg_match("/SPHC/i",$product_code) || preg_match("/C1/i",$product_code) 
                               ){


        $carekits=" AND os.product_upgrade ";

        }else{
        $carekits=" AND os.product_code ";

        }

                
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
                       
                        "requested",
                     
                       
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
                        "interbranch_in_past_date"

                        );
                       

                        $arrInvLook=array();
     $query ="SELECT 
                        sls.store_name_proper,
                        p51.item_name,
                        p51.product_code,(SELECT 
                        coalesce(
                        sum(
                        if(iib.variance_status='approve',
                        REPLACE(iib.actual_count,',',''),
                        REPLACE( iib.`count`,',',''))
                        ),0)
                        from inventory_studios   iib
                        WHERE
                        iib.product_code ='".$product_code."'
                        AND
                        (iib.store_id=sls.store_code)

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
                        ),0) from inventory_studios   iip
                        WHERE
                        iip.product_code ='".$product_code."'
                        AND
                        iip.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iid
                        WHERE
                        iid.product_code ='".$product_code."'
                        AND
                        iid.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iiso
                        WHERE
                        iiso.product_code ='".$product_code."'
                        AND
                        iiso.stock_from=sls.store_code

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
                        ),0) from inventory_studios   iisi
                        WHERE
                        iisi.product_code ='".$product_code."'
                        AND
                        iisi.store_id=sls.store_code

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
                        ),0) from inventory_studios   iisoc
                        WHERE
                        iisoc.product_code ='".$product_code."'
                        AND
                        iisoc.stock_from=sls.store_code

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
                        ),0) from inventory_studios   iiiboc
                        WHERE
                        iiiboc.product_code ='".$product_code."'
                        AND
                        iiiboc.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iinbic
                        WHERE
                        iinbic.product_code ='".$product_code."'
                        AND
                        iinbic.store_id=sls.store_code

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
                        ),0) from inventory_studios  ipc
                        WHERE
                        ipc.product_code ='".$product_code."'
                        AND
                        ipc.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iidc
                        WHERE
                        iidc.product_code ='".$product_code."'
                        AND
                        iidc.stock_from=sls.store_code

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
                        ),0) from inventory_studios   ididc
                        WHERE
                        ididc.product_code ='".$product_code."'
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
                                                    
                                            
                                                    FROM `orders_sunnies_studios` os
                                                    
                                                    LEFT JOIN orders_studios o ON o.order_id=os.order_id
                                                    
                                                    WHERE 
                                                    payment='y'
                                                    And os.status NOT IN ('return','cancelled','returned','failed' )
                                                    AND date(os.payment_date)>='2020-02-4'
                                                    AND  date(os.payment_date)>='".$date_start."'
                                                    AND  date(os.payment_date)<='".$date_end."'
                                                   ". $carekits."  ='".$product_code."'
                                                    ".$reRoute." 
                                                    AND  origin_branch=sls.store_code   
                                                )  as sales,
                       'none',
                    



                        '0',


                        


                        (
                            
                        SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                from inventory_actual_count_studios  iaccc 
                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                and iaccc.product_code='".$product_code."'
                                and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                            WHERE iacx.product_code='".$product_code."'
                                                        AND iacx.store_audited=iaccc.store_audited
                                                        AND iacx.date_end<'".$date_start."' 
                                                    )
                        ) as past_variance,

                        ( SELECT count(po_number)
                                    
                                                    

                        FROM `orders_sunnies_studios` os
            
                        LEFT JOIN orders_studios o ON o.order_id=os.order_id
            
                        WHERE 
                        payment='y'
                        And os.status NOT IN ('return','cancelled','returned','failed' )
                        AND date(os.payment_date)>='2020-02-4'
                      
                        AND  date(os.payment_date)<'".$date_start."'
                        ". $carekits."  ='".$product_code."'
                         ".$reRoute."  
                        AND  origin_branch=sls.store_code    
                         ) as sales_past,

                            (
                                        
                                SELECT COALESCE(sum(iaccc.input_count),0)
                                            from inventory_actual_count_studios  iaccc 
                                            where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                            and iaccc.product_code='".$product_code."'
                                            and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_audited=iaccc.store_audited
                                                                    AND iacx.date_end<'".$date_start."' 
                                                                )
                                    ) as past_variance_2,

                        (
                            
                        SELECT COALESCE( max(iaccc.date_end),0)
                                from inventory_actual_count_studios  iaccc 
                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                and iaccc.product_code='".$product_code."'
                                and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                            WHERE iacx.product_code='".$product_code."'
                                                        AND iacx.store_audited=iaccc.store_audited
                                                        AND iacx.date_end<'".$date_start."' 
                                                    )
                        ) as audit_date,

                            (
                                        
                                SELECT 
                                DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                from inventory_studios   iib
                            WHERE
                            iib.product_code ='".$product_code."'
                            AND
                            (iib.store_id='".$store_id."')

                            AND 
                            iib.status ='received'
                            AND
                                
                                iib.`type`='interbranch'
                                
                                

                            AND
                                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_id='".$store_id."'
                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                    AND count !='0'
                                                                    and status='received'
                                                                    AND  iacx.type='stock_transfer'
                                                                )

                                            ) as interbranch_status_date,

                            (

                            SELECT 
                            DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                            from inventory_studios   iib
                            WHERE
                            iib.product_code ='".$product_code."'
                            AND
                            (iib.store_id='".$store_id."')

                            AND 
                            iib.status ='received'
                            AND
                            iib.type='stock_transfer'

                            AND
                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx2.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx2 
                                                        WHERE iacx2.product_code='".$product_code."'
                                                    AND iacx2.store_id='".$store_id."'
                                                    AND  DATE(DATE_ADD(iacx2.status_date, INTERVAL 13 HOUR))>audit_date
                                                    AND  iacx2.count !='0'
                                                    and  iacx2.status='received'
                                                    AND  iacx2.type='stock_transfer'
                                                )


                            ) as stock_transfer_status_date,


                            (select coalesce(
                            sum(
                            if(iinbic.variance_status='approve',
                            REPLACE( iinbic.actual_count,',',''),
                            REPLACE( iinbic.`count`,',',''))
                            ),0) from inventory_studios  iinbic
                            WHERE
                            iinbic.product_code ='".$product_code."'
                            AND
                            iinbic.store_id='".$store_id."'

                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'


                            
                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                            ) as interbranch_in_past ,


                            (select coalesce(
                            sum(
                            if(iisi.variance_status='approve',
                            REPLACE( iisi.actual_count,',',''),
                            REPLACE( iisi.`count`,',',''))
                            ),0) from inventory_studios   iisi
                            WHERE
                            iisi.product_code ='".$product_code."'
                            AND
                            iisi.store_id='".$store_id."'

                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                            OR
                            iisi.type='replenish'
                            )
                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                            ) as stock_transfer_in_past,


                            ( SELECT count(po_number)

                                                    

                            FROM `orders_sunnies_studios` os

                            LEFT JOIN orders_studios o ON o.order_id=os.order_id

                            WHERE 
                            payment='y'
                            And os.status NOT IN ('return','cancelled','returned','failed' )
                            ". $carekits."   ='".$product_code."'
                            AND date(os.payment_date)>='2020-02-4'
                            AND  date(os.payment_date)>audit_date
                            AND  date(os.payment_date)<'".$date_start."' 
                                            ".$reRoute." 
                                    AND  origin_branch=sls.store_code    
                            ) as sales_deduct_physical,

                        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios  iid
                        WHERE
                        iid.product_code ='".$product_code."'
                        AND
                        iid.stock_from='".$store_id."'

                        AND 
                        iid.status ='received' 
                        AND iid.type='damage'
                        AND
                        DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
                                                                    WHERE iacx.product_code='".$product_code."'
                                                                AND iacx.stock_from='".$store_id."'
                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                AND count !='0'
                                                                and status='received'
                                                                and iacx.type='damage'
                                                            )
                        ) as damage_date,

                        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios   iip
                        WHERE
                        iip.product_code ='".$product_code."'
                        AND
                        iip.stock_from='".$store_id."'

                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
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
                        from inventory_studios   iib
                        WHERE
                        iib.product_code ='".$product_code."'
                        AND
                        (iib.stock_from='".$store_id."')

                        AND 
                        iib.status ='received'
                        AND
                        iib.type='stock_transfer'

                        AND
                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx3.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx3 
                                                WHERE iacx3.product_code='".$product_code."'
                                            AND iacx3.stock_from='".$store_id."'
                                            AND  DATE(DATE_ADD(iacx3.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND iacx3.count !='0'
                                            and iacx3.status='received'
                                            AND  iacx3.type='stock_transfer'
                                        )


                        ) as stock_transfer_minus_date,


                        (select coalesce(
                        sum(
                        if(iisi.variance_status='approve',
                        REPLACE( iisi.actual_count,',',''),
                        REPLACE( iisi.`count`,',',''))
                        ),0) from inventory_studios   iisi
                        WHERE
                        iisi.product_code ='".$product_code."'
                        AND
                        iisi.stock_from='".$store_id."'

                        AND 
                        iisi.status ='received' 
                        AND (iisi.type='stock_transfer'
                        OR
                        iisi.type='replenish'
                        )
                        AND  DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                        AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                        ) as stock_transfer_minus_past,

                        ( select coalesce(
                        sum(
                            if(iiiboc.variance_status='approve',
                            REPLACE(iiiboc.actual_count,',',''),
                            REPLACE( iiiboc.`count`,',',''))
                        ),0) from inventory_studios   iiiboc
                            WHERE
                            iiiboc.product_code ='".$product_code."'
                            AND
                            iiiboc.stock_from=sls.store_code

                        AND 
                        iiiboc.status ='received' 
                        AND iiiboc.type='interbranch'

                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past,

                        ( select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios   iiiboc
                        WHERE
                        iiiboc.product_code ='".$product_code."'
                        AND
                        iiiboc.stock_from=sls.store_code

                        AND 
                        iiiboc.status ='received' 
                        AND iiiboc.type='interbranch'

                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past_date,

                        ( select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                        from inventory_studios  iinbic
                        WHERE
                        iinbic.product_code ='".$product_code."'
                        AND
                        iinbic.store_id='".$store_id."'

                        AND 
                        iinbic.status ='received' 
                        AND iinbic.type='interbranch'



                        AND
                                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                        AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                        ) as interbranch_in_past_date


                        FROM poll_51_studios p51
                        LEFT JOin inventory_studios ip on ip.product_code='".$product_code."'
                        LEFT JOIN store_codes_studios sls on sls.store_code='".$store_id."'

                        WHERE p51.product_code='".$product_code."'
                        AND  sls.store_code   ='".$store_id."'
                        group by sls.store_code,p51.product_code
                        ";




                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $query)) {

                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                        $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
                        $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33);

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
    
            if(in_array($arrInvLook[0]['product_code'],$arrActualX3)){
                $beg_inventory =$arrInvLook[0]["past_variance_2"];
                
            }
            else{
                if(  ($arrInvLook[0]["audit_date"] >=$arrInvLook[0]["stock_transfer_status_date"] 
																 &&  !empty($arrInvLook[0]["stock_transfer_status_date"] )  && empty($arrInvLook[0]["interbranch_out_past_date"]  )  )
															
														){
															
													
                                                            $beg_inventory=$arrInvLook[0]["past_variance_2"] -$arrInvLook[0]["sales_deduct_physical"]+$arrInvLook[0]["stock_transfer_in_past"]+$arrInvLook[0]["interbranch_in_past"];
                                                            

														}
					elseif($arrInvLook[0]["audit_date"] >=$arrInvLook[0]["interbranch_out_past_date"] 
														&&  !empty($arrInvLook[0]["interbranch_out_past_date"]) && empty($arrInvLook[0]["stock_transfer_status_date"] ) ){


															$beg_inventory=$arrInvLook[0]["past_variance_2"] -$arrInvLook[0]["sales_deduct_physical"]+$arrInvLook[0]["stock_transfer_in_past"]+$arrInvLook[0]["interbranch_in_past"];
														}
                               elseif( ( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_status_date"]) 
                                            || ( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["damage_past_date"]) 
                                        || ($arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["stock_transfer_status_date"]))
                                        || ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["stock_transfer_minus_date"]) 
                                        || ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_out_past_date"])
                                        ||  ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_in_past_date"])
                                        ){
                                // echo "cccc";
                                                    if( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_status_date"] || 
                                                    ( $arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["stock_transfer_status_date"])) ) {
                                                        
                                                        $stok_transfer_beg=$arrInvLook[0]["stock_transfer_in_past"];
                                                    }else{
                                                        $stok_transfer_beg="0";

                                                    }
                                                    


                                                    if( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["interbranch_in_past_date"] || 
                                                    ( $arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["interbranch_in_past_date"])) ) {
                                                        ;
                                                        $interbranch_in_past=$arrInvLook[0]["interbranch_in_past"];
                                                    }else{
                                                        $interbranch_in_past="0";

                                                    }
                                                    


                                                    if( $arrInvLook[0]["audit_date"] <$arrInvLook[0]["damage_past_date"]) {
                                                            $damage_beg =$arrInvLook[0]["damage"];
                                                    }else{
                                                        $damage_beg ="0";
                                                    }

                                                    if( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_out_past_date"]) {
                                                        $past_interbranch =$arrInvLook[0]["interbranch_out_past"];
                                                }else{
                                                    $past_interbranch ="0";
                                                }
                                                
                                                    
                                                    if(( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_minus_date"]) ){
                                                        $stock_transfer_beg_minus =$arrInvLook[0]["stock_transfer_minus"];

                                                    }else{
                                                        $stock_transfer_beg_minus = "0";
                                                    }

                                                


                                                    
                                            // echo "bbb";
                                            $beg_inventory= $arrInvLook[0]["past_variance_2"]+ $stok_transfer_beg
                                            +$interbranch_in_past
                                            - $arrInvLook[0]["sales_deduct_physical"]
                                            - $damage_beg
                                            -$stock_transfer_beg_minus
                                            -$past_interbranch;
                                        
                                        }else{
                                            // echo "aaa";
                                            $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]
                                            -$arrInvLook[0]["sales_past"]-$arrInvLook[0]["transit_out"];
                                            }
            }
    
    
                $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                    +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                    $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"] -$arrInvLook[0]["sales"]; 


   return $runningtotal;
}



function StoreChecker_auditor_VS($product_code,$store_id,$date_start,$date_end){
    
    global $conn;

    if($store_id=='787'  || $store_id=='788'){
        $reRoute=" AND  date(os.payment_date)>='2020-06-25' ";
          // AND os.product_upgrade!='PL0010'
        }else{
        $reRoute=" 
        AND  os.lens_code!='L035'
        AND (
                        os.lens_option='without prescription'
                        OR
                        os.lens_code IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')
            ) 
            and  os.product_code!='F100' 
            and  os.product_code!='S100' 
            
        ";
        }


        // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
        if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
        || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                            || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code)  || preg_match("/P1/i",$product_code) 
                            || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)   || preg_match("/SC/i",$product_code)
                              || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                              || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) || preg_match("/SSGWPCB/i",$product_code) 
                              || preg_match("/SSP/i",$product_code)    || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code)  || preg_match("/TB0/i",$product_code)  || preg_match("/SGC/i",$product_code)  
                              || preg_match("/DS/i",$product_code)   || preg_match("/ST/i",$product_code)   || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code)  || preg_match("/CPV/i",$product_code)  
                              || preg_match("/SWS/i",$product_code)   || preg_match("/SMHP/i",$product_code) || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code) || preg_match("/SMZ/i",$product_code) 
                              || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code)
                              || preg_match("/SS00/i",$product_code) || preg_match("/SPHC/i",$product_code) || preg_match("/C1/i",$product_code) 
                              ){

        $carekits=" AND os.product_upgrade ";

        }else{
        $carekits=" AND os.product_code ";

        }

                
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
                       
                        "requested",
                     
                       
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
                        "interbranch_in_past_date"

                        );
                       

                        $arrInvLook=array();
     $query ="SELECT 
                        sls.store_name_proper,
                        p51.item_name,
                        p51.product_code,(SELECT 
                        coalesce(
                        sum(
                        if(iib.variance_status='approve',
                        REPLACE(iib.actual_count,',',''),
                        REPLACE( iib.`count`,',',''))
                        ),0)
                        from inventory_studios   iib
                        WHERE
                        iib.product_code ='".$product_code."'
                        AND
                        (iib.store_id=sls.store_code)

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
                        ),0) from inventory_studios   iip
                        WHERE
                        iip.product_code ='".$product_code."'
                        AND
                        iip.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iid
                        WHERE
                        iid.product_code ='".$product_code."'
                        AND
                        iid.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iiso
                        WHERE
                        iiso.product_code ='".$product_code."'
                        AND
                        iiso.stock_from=sls.store_code

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
                        ),0) from inventory_studios   iisi
                        WHERE
                        iisi.product_code ='".$product_code."'
                        AND
                        iisi.store_id=sls.store_code

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
                        ),0) from inventory_studios   iisoc
                        WHERE
                        iisoc.product_code ='".$product_code."'
                        AND
                        iisoc.stock_from=sls.store_code

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
                        ),0) from inventory_studios   iiiboc
                        WHERE
                        iiiboc.product_code ='".$product_code."'
                        AND
                        iiiboc.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iinbic
                        WHERE
                        iinbic.product_code ='".$product_code."'
                        AND
                        iinbic.store_id=sls.store_code

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
                        ),0) from inventory_studios  ipc
                        WHERE
                        ipc.product_code ='".$product_code."'
                        AND
                        ipc.stock_from=sls.store_code

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
                        ),0) from inventory_studios  iidc
                        WHERE
                        iidc.product_code ='".$product_code."'
                        AND
                        iidc.stock_from=sls.store_code

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
                        ),0) from inventory_studios   ididc
                        WHERE
                        ididc.product_code ='".$product_code."'
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
                                                    
                                                    LEFT JOIN orders_studios o ON o.order_id=os.order_id
                                                    
                                                    WHERE 
                                                    payment='y'
                                                    And os.status NOT IN ('return','cancelled','returned','failed' )
                                                    AND date(os.payment_date)>='2020-02-4'
                                                    AND  date(os.payment_date)>='".$date_start."'
                                                    AND  date(os.payment_date)<='".$date_end."'
                                                   ". $carekits."  ='".$product_code."'
                                                    ".$reRoute." 
                                                    AND  origin_branch=sls.store_code   
                                                )  as sales,
                       'none',
                    



                        '0',


                        


                        (
                            
                        SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                from inventory_actual_count_studios  iaccc 
                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                and iaccc.product_code='".$product_code."'
                                and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                            WHERE iacx.product_code='".$product_code."'
                                                        AND iacx.store_audited=iaccc.store_audited
                                                        AND iacx.date_end<'".$date_start."' 
                                                    )
                        ) as past_variance,

                        ( SELECT count(po_number)
                                    
                                                    

                        FROM `orders_specs` os
            
                        LEFT JOIN orders_studios o ON o.order_id=os.order_id
            
                        WHERE 
                        payment='y'
                        And os.status NOT IN ('return','cancelled','returned','failed' )
                        AND date(os.payment_date)>='2020-02-4'
                      
                        AND  date(os.payment_date)<'".$date_start."'
                        ". $carekits."  ='".$product_code."'
                         ".$reRoute."  
                        AND  origin_branch=sls.store_code    
                         ) as sales_past,

                            (
                                        
                                SELECT COALESCE(sum(iaccc.input_count),0)
                                            from inventory_actual_count_studios  iaccc 
                                            where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                            and iaccc.product_code='".$product_code."'
                                            and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_audited=iaccc.store_audited
                                                                    AND iacx.date_end<'".$date_start."' 
                                                                )
                                    ) as past_variance_2,

                        (
                            
                        SELECT COALESCE( max(iaccc.date_end),0)
                                from inventory_actual_count_studios  iaccc 
                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                and iaccc.product_code='".$product_code."'
                                and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                            WHERE iacx.product_code='".$product_code."'
                                                        AND iacx.store_audited=iaccc.store_audited
                                                        AND iacx.date_end<'".$date_start."' 
                                                    )
                        ) as audit_date,

                            (
                                        
                                SELECT 
                                DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                from inventory_studios   iib
                            WHERE
                            iib.product_code ='".$product_code."'
                            AND
                            (iib.store_id='".$store_id."')

                            AND 
                            iib.status ='received'
                            AND
                                
                                iib.`type`='interbranch'
                                
                                

                            AND
                                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_id='".$store_id."'
                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                    AND count !='0'
                                                                    and status='received'
                                                                    AND  iacx.type='stock_transfer'
                                                                )

                                            ) as interbranch_status_date,

                            (

                            SELECT 
                            DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                            from inventory_studios   iib
                            WHERE
                            iib.product_code ='".$product_code."'
                            AND
                            (iib.store_id='".$store_id."')

                            AND 
                            iib.status ='received'
                            AND
                            iib.type='stock_transfer'

                            AND
                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx2.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx2 
                                                        WHERE iacx2.product_code='".$product_code."'
                                                    AND iacx2.store_id='".$store_id."'
                                                    AND  DATE(DATE_ADD(iacx2.status_date, INTERVAL 13 HOUR))>audit_date
                                                    AND  iacx2.count !='0'
                                                    and  iacx2.status='received'
                                                    AND  iacx2.type='stock_transfer'
                                                )


                            ) as stock_transfer_status_date,


                            (select coalesce(
                            sum(
                            if(iinbic.variance_status='approve',
                            REPLACE( iinbic.actual_count,',',''),
                            REPLACE( iinbic.`count`,',',''))
                            ),0) from inventory_studios  iinbic
                            WHERE
                            iinbic.product_code ='".$product_code."'
                            AND
                            iinbic.store_id='".$store_id."'

                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'


                            
                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                            ) as interbranch_in_past ,


                            (select coalesce(
                            sum(
                            if(iisi.variance_status='approve',
                            REPLACE( iisi.actual_count,',',''),
                            REPLACE( iisi.`count`,',',''))
                            ),0) from inventory_studios   iisi
                            WHERE
                            iisi.product_code ='".$product_code."'
                            AND
                            iisi.store_id='".$store_id."'

                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                            OR
                            iisi.type='replenish'
                            )
                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                            ) as stock_transfer_in_past,


                            ( SELECT count(po_number)

                                                    

                            FROM `orders_specs` os

                            LEFT JOIN orders o ON o.order_id=os.order_id

                            WHERE 
                            payment='y'
                            And os.status NOT IN ('return','cancelled','returned','failed' )
                            ". $carekits."   ='".$product_code."'
                            AND date(os.payment_date)>='2020-02-4'
                            AND  date(os.payment_date)>audit_date
                            AND  date(os.payment_date)<'".$date_start."' 
                                            ".$reRoute." 
                                    AND  origin_branch=sls.store_code    
                            ) as sales_deduct_physical,

                        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios  iid
                        WHERE
                        iid.product_code ='".$product_code."'
                        AND
                        iid.stock_from='".$store_id."'

                        AND 
                        iid.status ='received' 
                        AND iid.type='damage'
                        AND
                        DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
                                                                    WHERE iacx.product_code='".$product_code."'
                                                                AND iacx.stock_from='".$store_id."'
                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                AND count !='0'
                                                                and status='received'
                                                                and iacx.type='damage'
                                                            )
                        ) as damage_date,

                        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios   iip
                        WHERE
                        iip.product_code ='".$product_code."'
                        AND
                        iip.stock_from='".$store_id."'

                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
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
                        from inventory_studios   iib
                        WHERE
                        iib.product_code ='".$product_code."'
                        AND
                        (iib.stock_from='".$store_id."')

                        AND 
                        iib.status ='received'
                        AND
                        iib.type='stock_transfer'

                        AND
                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx3.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx3 
                                                WHERE iacx3.product_code='".$product_code."'
                                            AND iacx3.stock_from='".$store_id."'
                                            AND  DATE(DATE_ADD(iacx3.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND iacx3.count !='0'
                                            and iacx3.status='received'
                                            AND  iacx3.type='stock_transfer'
                                        )


                        ) as stock_transfer_minus_date,


                        (select coalesce(
                        sum(
                        if(iisi.variance_status='approve',
                        REPLACE( iisi.actual_count,',',''),
                        REPLACE( iisi.`count`,',',''))
                        ),0) from inventory_studios   iisi
                        WHERE
                        iisi.product_code ='".$product_code."'
                        AND
                        iisi.stock_from='".$store_id."'

                        AND 
                        iisi.status ='received' 
                        AND (iisi.type='stock_transfer'
                        OR
                        iisi.type='replenish'
                        )
                        AND  DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                        AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                        ) as stock_transfer_minus_past,

                        ( select coalesce(
                        sum(
                            if(iiiboc.variance_status='approve',
                            REPLACE(iiiboc.actual_count,',',''),
                            REPLACE( iiiboc.`count`,',',''))
                        ),0) from inventory_studios   iiiboc
                            WHERE
                            iiiboc.product_code ='".$product_code."'
                            AND
                            iiiboc.stock_from=sls.store_code

                        AND 
                        iiiboc.status ='received' 
                        AND iiiboc.type='interbranch'

                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past,

                        ( select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios   iiiboc
                        WHERE
                        iiiboc.product_code ='".$product_code."'
                        AND
                        iiiboc.stock_from=sls.store_code

                        AND 
                        iiiboc.status ='received' 
                        AND iiiboc.type='interbranch'

                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past_date,

                        ( select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                        from inventory_studios  iinbic
                        WHERE
                        iinbic.product_code ='".$product_code."'
                        AND
                        iinbic.store_id='".$store_id."'

                        AND 
                        iinbic.status ='received' 
                        AND iinbic.type='interbranch'



                        AND
                                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                        AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                        ) as interbranch_in_past_date


                        FROM poll_51_studios p51
                        LEFT JOin inventory_studios ip on ip.product_code='".$product_code."'
                        LEFT JOIN store_codes_studios sls on sls.store_code='".$store_id."'

                        WHERE p51.product_code='".$product_code."'
                        AND  sls.store_code   ='".$store_id."'
                        group by sls.store_code,p51.product_code
                        ";




                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $query)) {

                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                        $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
                        $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33);

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
    
            if(in_array($arrInvLook[0]['product_code'],$arrActualX3)){
                $beg_inventory =$arrInvLook[0]["past_variance_2"];
                
            }
            else{
                if(  ($arrInvLook[0]["audit_date"] >=$arrInvLook[0]["stock_transfer_status_date"] 
																 &&  !empty($arrInvLook[0]["stock_transfer_status_date"] )  && empty($arrInvLook[0]["interbranch_out_past_date"]  )  )
															
														){
															
													
                                                            $beg_inventory=$arrInvLook[0]["past_variance_2"] -$arrInvLook[0]["sales_deduct_physical"]+$arrInvLook[0]["stock_transfer_in_past"]+$arrInvLook[0]["interbranch_in_past"];
                                                            

														}
					elseif($arrInvLook[0]["audit_date"] >=$arrInvLook[0]["interbranch_out_past_date"] 
														&&  !empty($arrInvLook[0]["interbranch_out_past_date"]) && empty($arrInvLook[0]["stock_transfer_status_date"] ) ){


															$beg_inventory=$arrInvLook[0]["past_variance_2"] -$arrInvLook[0]["sales_deduct_physical"]+$arrInvLook[0]["stock_transfer_in_past"]+$arrInvLook[0]["interbranch_in_past"];
														}
                               elseif( ( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_status_date"]) 
                                            || ( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["damage_past_date"]) 
                                        || ($arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["stock_transfer_status_date"]))
                                        || ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["stock_transfer_minus_date"]) 
                                        || ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_out_past_date"])
                                        ||  ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_in_past_date"])
                                        ){
                                // echo "cccc";
                                                    if( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_status_date"] || 
                                                    ( $arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["stock_transfer_status_date"])) ) {
                                                        
                                                        $stok_transfer_beg=$arrInvLook[0]["stock_transfer_in_past"];
                                                    }else{
                                                        $stok_transfer_beg="0";

                                                    }
                                                    


                                                    if( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["interbranch_in_past_date"] || 
                                                    ( $arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["interbranch_in_past_date"])) ) {
                                                        ;
                                                        $interbranch_in_past=$arrInvLook[0]["interbranch_in_past"];
                                                    }else{
                                                        $interbranch_in_past="0";

                                                    }
                                                    


                                                    if( $arrInvLook[0]["audit_date"] <$arrInvLook[0]["damage_past_date"]) {
                                                            $damage_beg =$arrInvLook[0]["damage"];
                                                    }else{
                                                        $damage_beg ="0";
                                                    }

                                                    if( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_out_past_date"]) {
                                                        $past_interbranch =$arrInvLook[0]["interbranch_out_past"];
                                                }else{
                                                    $past_interbranch ="0";
                                                }
                                                
                                                    
                                                    if(( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_minus_date"]) ){
                                                        $stock_transfer_beg_minus =$arrInvLook[0]["stock_transfer_minus"];

                                                    }else{
                                                        $stock_transfer_beg_minus = "0";
                                                    }

                                                


                                                    
                                            // echo "bbb";
                                            $beg_inventory= $arrInvLook[0]["past_variance_2"]+ $stok_transfer_beg
                                            +$interbranch_in_past
                                            - $arrInvLook[0]["sales_deduct_physical"]
                                            - $damage_beg
                                            -$stock_transfer_beg_minus
                                            -$past_interbranch;
                                        
                                        }else{
                                            // echo "aaa";
                                            $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]
                                            -$arrInvLook[0]["sales_past"]-$arrInvLook[0]["transit_out"];
                                            }
            }
    
    
                $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                    +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                    $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"] -$arrInvLook[0]["sales"]; 


   return $runningtotal;
}




function StoreChecker_auditor_MS($product_code,$store_id,$date_start,$date_end){
    
    global $conn;

    if($store_id=='787'  || $store_id=='788' || $store_id=='789'){
        $reRoute=" AND  date(os.payment_date)>='2020-06-25' ";
          // AND os.product_upgrade!='PL0010'
        }else{
        $reRoute=" 
        AND  os.lens_code!='L035'
        AND (
                        os.lens_option='without prescription'
                        OR
                        os.lens_code IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')
            ) 
            and  os.product_code!='F100' 
            and  os.product_code!='S100' 
            
        ";
        }
        $stock_from = "  AND os.stock_from='SS-MPWHC'  ";

        // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
        if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
        || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                            || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code)  || preg_match("/P1/i",$product_code) 
                            || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)   || preg_match("/SC/i",$product_code)
                              || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                              || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) || preg_match("/SSGWPCB/i",$product_code) 
                              || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code)  || preg_match("/TB0/i",$product_code)  || preg_match("/SGC/i",$product_code)  
                              || preg_match("/DS/i",$product_code)   || preg_match("/ST/i",$product_code)   || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code)  || preg_match("/CPV/i",$product_code)  
                              || preg_match("/SWS/i",$product_code)   || preg_match("/SMHP/i",$product_code) || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code) || preg_match("/SMZ/i",$product_code) 
                              || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code) 
                              || preg_match("/SS00/i",$product_code) || preg_match("/SPHC/i",$product_code) || preg_match("/C1/i",$product_code) 
                              ){


        $carekits=" AND os.product_upgrade ";

        }else{
        $carekits=" AND os.product_code ";

        }

                
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
                       
                        "requested",
                     
                       
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
                        "interbranch_in_past_date"

                        );
                       

                        $arrInvLook=array();

         $query ="SELECT 
                        sls.lab_name,
                        p51.item_name,
                        p51.product_code,(SELECT 
                        coalesce(
                        sum(
                        if(iib.variance_status='approve',
                        REPLACE(iib.actual_count,',',''),
                        REPLACE( iib.`count`,',',''))
                        ),0)
                        from inventory_studios   iib
                        WHERE
                        iib.product_code ='".$product_code."'
                        AND
                        (iib.store_id=sls.lab_id)

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
                        ),0) from inventory_studios   iip
                        WHERE
                        iip.product_code ='".$product_code."'
                        AND
                        iip.stock_from=sls.lab_id

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
                        ),0) from inventory_studios  iid
                        WHERE
                        iid.product_code ='".$product_code."'
                        AND
                        iid.stock_from=sls.lab_id

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
                        ),0) from inventory_studios  iiso
                        WHERE
                        iiso.product_code ='".$product_code."'
                        AND
                        iiso.stock_from=sls.lab_id

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
                        ),0) from inventory_studios   iisi
                        WHERE
                        iisi.product_code ='".$product_code."'
                        AND
                        iisi.store_id=sls.lab_id

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
                        ),0) from inventory_studios   iisoc
                        WHERE
                        iisoc.product_code ='".$product_code."'
                        AND
                        iisoc.stock_from=sls.lab_id

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
                        ),0) from inventory_studios   iiiboc
                        WHERE
                        iiiboc.product_code ='".$product_code."'
                        AND
                        iiiboc.stock_from=sls.lab_id

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
                        ),0) from inventory_studios  iinbic
                        WHERE
                        iinbic.product_code ='".$product_code."'
                        AND
                        iinbic.store_id=sls.lab_id

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
                        ),0) from inventory_studios  ipc
                        WHERE
                        ipc.product_code ='".$product_code."'
                        AND
                        ipc.stock_from=sls.lab_id

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
                        ),0) from inventory_studios  iidc
                        WHERE
                        iidc.product_code ='".$product_code."'
                        AND
                        iidc.stock_from=sls.lab_id

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
                        ),0) from inventory_studios   ididc
                        WHERE
                        ididc.product_code ='".$product_code."'
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
                                                    
                                            
                                                    FROM `orders_sunnies_studios` os
                                                    
                                                    LEFT JOIN orders_studios o ON o.order_id=os.order_id
                                                    
                                                    WHERE 
                                                    payment='y'
                                                    And os.status NOT IN ('return','cancelled','returned','failed' )
                                                    AND date(os.payment_date)>='2020-02-4'
                                                    AND  date(os.payment_date)>='".$date_start."'
                                                    AND  date(os.payment_date)<='".$date_end."'
                                                   ". $carekits."  ='".$product_code."'
                                                    ".$reRoute." 
                                                    ".$stock_from."
                                                )  as sales,
                       'none',
                    



                        '0',


                        


                        (
                            
                        SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                from inventory_actual_count_studios  iaccc 
                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited=sls.lab_id
                                and iaccc.product_code='".$product_code."'
                                and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                            WHERE iacx.product_code='".$product_code."'
                                                        AND iacx.store_audited=iaccc.store_audited
                                                        AND iacx.date_end<'".$date_start."' 
                                                    )
                        ) as past_variance,

                        ( SELECT count(po_number)
                                    
                                                    

                        FROM `orders_sunnies_studios` os
            
                        LEFT JOIN orders_studios o ON o.order_id=os.order_id
            
                        WHERE 
                        payment='y'
                        And os.status NOT IN ('return','cancelled','returned','failed' )
                        AND date(os.payment_date)>='2020-02-4'
                      
                        AND  date(os.payment_date)<'".$date_start."'
                        ". $carekits."  ='".$product_code."'
                         ".$reRoute."  
                         ".$stock_from."
                         ) as sales_past,

                            (
                                        
                                SELECT COALESCE(sum(iaccc.input_count),0)
                                            from inventory_actual_count_studios  iaccc 
                                            where iaccc.date_end<'".$date_start."'  and iaccc.store_audited=sls.lab_id
                                            and iaccc.product_code='".$product_code."'
                                            and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_audited=iaccc.store_audited
                                                                    AND iacx.date_end<'".$date_start."' 
                                                                )
                                    ) as past_variance_2,

                        (
                            
                        SELECT COALESCE( max(iaccc.date_end),0)
                                from inventory_actual_count_studios  iaccc 
                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited=sls.lab_id
                                and iaccc.product_code='".$product_code."'
                                and iaccc.date_end=(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
                                                            WHERE iacx.product_code='".$product_code."'
                                                        AND iacx.store_audited=iaccc.store_audited
                                                        AND iacx.date_end<'".$date_start."' 
                                                    )
                        ) as audit_date,

                            (
                                        
                                SELECT 
                                DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                from inventory_studios   iib
                            WHERE
                            iib.product_code ='".$product_code."'
                            AND
                            (iib.store_id=sls.lab_id)

                            AND 
                            iib.status ='received'
                            AND
                                
                                iib.`type`='interbranch'
                                
                                

                            AND
                                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
                                                                        WHERE iacx.product_code='".$product_code."'
                                                                    AND iacx.store_id=sls.lab_id
                                                                    AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))>'".$date_start."' 
                                                                    AND count !='0'
                                                                    and status='received'
                                                                    AND  iacx.type='stock_transfer'
                                                                )

                                            ) as interbranch_status_date,

                            (

                            SELECT 
                            DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                            from inventory_studios   iib
                            WHERE
                            iib.product_code ='".$product_code."'
                            AND
                            (iib.store_id=sls.lab_id)

                            AND 
                            iib.status ='received'
                            AND
                            iib.type='stock_transfer'

                            AND
                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx2.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx2 
                                                        WHERE iacx2.product_code='".$product_code."'
                                                    AND iacx2.store_id=sls.lab_id
                                                    AND  DATE(DATE_ADD(iacx2.status_date, INTERVAL 13 HOUR))>audit_date
                                                    AND  iacx2.count !='0'
                                                    and  iacx2.status='received'
                                                    AND  iacx2.type='stock_transfer'
                                                )


                            ) as stock_transfer_status_date,


                            (select coalesce(
                            sum(
                            if(iinbic.variance_status='approve',
                            REPLACE( iinbic.actual_count,',',''),
                            REPLACE( iinbic.`count`,',',''))
                            ),0) from inventory_studios  iinbic
                            WHERE
                            iinbic.product_code ='".$product_code."'
                            AND
                            iinbic.store_id=sls.lab_id

                            AND 
                            iinbic.status ='received' 
                            AND iinbic.type='interbranch'


                            
                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                            AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                            ) as interbranch_in_past ,


                            (select coalesce(
                            sum(
                            if(iisi.variance_status='approve',
                            REPLACE( iisi.actual_count,',',''),
                            REPLACE( iisi.`count`,',',''))
                            ),0) from inventory_studios   iisi
                            WHERE
                            iisi.product_code ='".$product_code."'
                            AND
                            iisi.store_id=sls.lab_id

                            AND 
                            iisi.status ='received' 
                            AND (iisi.type='stock_transfer'
                            OR
                            iisi.type='replenish'
                            )
                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                            ) as stock_transfer_in_past,


                            ( SELECT count(po_number)

                                                    

                            FROM `orders_sunnies_studios` os

                            LEFT JOIN orders_studios o ON o.order_id=os.order_id

                            WHERE 
                            payment='y'
                            And os.status NOT IN ('return','cancelled','returned','failed' )
                            ". $carekits."   ='".$product_code."'
                            AND date(os.payment_date)>='2020-02-4'
                            AND  date(os.payment_date)>audit_date
                            AND  date(os.payment_date)<'".$date_start."' 
                                            ".$reRoute." 
                                            ".$stock_from."
                            ) as sales_deduct_physical,

                        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios  iid
                        WHERE
                        iid.product_code ='".$product_code."'
                        AND
                        iid.stock_from=sls.lab_id

                        AND 
                        iid.status ='received' 
                        AND iid.type='damage'
                        AND
                        DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
                                                                    WHERE iacx.product_code='".$product_code."'
                                                                AND iacx.stock_from=sls.lab_id
                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                AND count !='0'
                                                                and status='received'
                                                                and iacx.type='damage'
                                                            )
                        ) as damage_date,

                        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios   iip
                        WHERE
                        iip.product_code ='".$product_code."'
                        AND
                        iip.stock_from=sls.lab_id

                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx 
                                                                    WHERE iacx.product_code='".$product_code."'
                                                                AND iacx.stock_from=sls.lab_id
                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                AND count !='0'
                                                                and status='received'
                                                                and iacx.type='pullout'
                                                            )
                        ) as pullout_date,

                        (

                        SELECT 
                        DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                        from inventory_studios   iib
                        WHERE
                        iib.product_code ='".$product_code."'
                        AND
                        (iib.stock_from=sls.lab_id)

                        AND 
                        iib.status ='received'
                        AND
                        iib.type='stock_transfer'

                        AND
                        DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx3.status_date), INTERVAL 13 HOUR)) from inventory_studios  iacx3 
                                                WHERE iacx3.product_code='".$product_code."'
                                            AND iacx3.stock_from=sls.lab_id
                                            AND  DATE(DATE_ADD(iacx3.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND iacx3.count !='0'
                                            and iacx3.status='received'
                                            AND  iacx3.type='stock_transfer'
                                        )


                        ) as stock_transfer_minus_date,


                        (select coalesce(
                        sum(
                        if(iisi.variance_status='approve',
                        REPLACE( iisi.actual_count,',',''),
                        REPLACE( iisi.`count`,',',''))
                        ),0) from inventory_studios   iisi
                        WHERE
                        iisi.product_code ='".$product_code."'
                        AND
                        iisi.stock_from=sls.lab_id

                        AND 
                        iisi.status ='received' 
                        AND (iisi.type='stock_transfer'
                        OR
                        iisi.type='replenish'
                        )
                        AND  DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                        AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                        ) as stock_transfer_minus_past,

                        ( select coalesce(
                        sum(
                            if(iiiboc.variance_status='approve',
                            REPLACE(iiiboc.actual_count,',',''),
                            REPLACE( iiiboc.`count`,',',''))
                        ),0) from inventory_studios   iiiboc
                            WHERE
                            iiiboc.product_code ='".$product_code."'
                            AND
                            iiiboc.stock_from=sls.lab_id

                        AND 
                        iiiboc.status ='received' 
                        AND iiiboc.type='interbranch'

                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past,

                        ( select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) from inventory_studios   iiiboc
                        WHERE
                        iiiboc.product_code ='".$product_code."'
                        AND
                        iiiboc.stock_from=sls.lab_id

                        AND 
                        iiiboc.status ='received' 
                        AND iiiboc.type='interbranch'

                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>audit_date
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<'".$date_start."'
                        ) as interbranch_out_past_date,

                        ( select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                        from inventory_studios  iinbic
                        WHERE
                        iinbic.product_code ='".$product_code."'
                        AND
                        iinbic.store_id=sls.lab_id

                        AND 
                        iinbic.status ='received' 
                        AND iinbic.type='interbranch'



                        AND
                                            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>audit_date
                        AND DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                        ) as interbranch_in_past_date


                        FROM poll_51_studios p51
                        LEFT JOin inventory_studios ip on ip.product_code='".$product_code."'
                        LEFT JOIN labs_locations sls on sls.lab_id='".$store_id."'
                                
                        WHERE p51.product_code='".$product_code."'
                        AND  sls.lab_id   ='".$store_id."'
                       
                        group by sls.lab_id,p51.product_code
                        ";




                        $stmt = mysqli_stmt_init($conn);
                        if (mysqli_stmt_prepare($stmt, $query)) {

                        mysqli_stmt_execute($stmt);
                        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
                        $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16,$result17 ,
                        $result18,$result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33);

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
    
            if(in_array($arrInvLook[0]['product_code'],$arrActualX3)){
                $beg_inventory =$arrInvLook[0]["past_variance_2"];
                
            }
            else{
                if(  ($arrInvLook[0]["audit_date"] >=$arrInvLook[0]["stock_transfer_status_date"] 
																 &&  !empty($arrInvLook[0]["stock_transfer_status_date"] )  && empty($arrInvLook[0]["interbranch_out_past_date"]  )  )
															
														){
															
													
                                                            $beg_inventory=$arrInvLook[0]["past_variance_2"] -$arrInvLook[0]["sales_deduct_physical"]+$arrInvLook[0]["stock_transfer_in_past"]+$arrInvLook[0]["interbranch_in_past"];
                                                            

														}
					elseif($arrInvLook[0]["audit_date"] >=$arrInvLook[0]["interbranch_out_past_date"] 
														&&  !empty($arrInvLook[0]["interbranch_out_past_date"]) && empty($arrInvLook[0]["stock_transfer_status_date"] ) ){


															$beg_inventory=$arrInvLook[0]["past_variance_2"] -$arrInvLook[0]["sales_deduct_physical"]+$arrInvLook[0]["stock_transfer_in_past"]+$arrInvLook[0]["interbranch_in_past"];
														}
                               elseif( ( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_status_date"]) 
                                            || ( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["damage_past_date"]) 
                                        || ($arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["stock_transfer_status_date"]))
                                        || ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["stock_transfer_minus_date"]) 
                                        || ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_out_past_date"])
                                        ||  ( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_in_past_date"])
                                        ){
                                // echo "cccc";
                                                    if( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_status_date"] || 
                                                    ( $arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["stock_transfer_status_date"])) ) {
                                                        
                                                        $stok_transfer_beg=$arrInvLook[0]["stock_transfer_in_past"];
                                                    }else{
                                                        $stok_transfer_beg="0";

                                                    }
                                                    


                                                    if( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["interbranch_in_past_date"] || 
                                                    ( $arrInvLook[0]["audit_date"]!=''  &&  empty($arrInvLook[0]["interbranch_in_past_date"])) ) {
                                                        ;
                                                        $interbranch_in_past=$arrInvLook[0]["interbranch_in_past"];
                                                    }else{
                                                        $interbranch_in_past="0";

                                                    }
                                                    


                                                    if( $arrInvLook[0]["audit_date"] <$arrInvLook[0]["damage_past_date"]) {
                                                            $damage_beg =$arrInvLook[0]["damage"];
                                                    }else{
                                                        $damage_beg ="0";
                                                    }

                                                    if( $arrInvLook[0]["audit_date"] <= $arrInvLook[0]["interbranch_out_past_date"]) {
                                                        $past_interbranch =$arrInvLook[0]["interbranch_out_past"];
                                                }else{
                                                    $past_interbranch ="0";
                                                }
                                                
                                                    
                                                    if(( $arrInvLook[0]["audit_date"] <=$arrInvLook[0]["stock_transfer_minus_date"]) ){
                                                        $stock_transfer_beg_minus =$arrInvLook[0]["stock_transfer_minus"];

                                                    }else{
                                                        $stock_transfer_beg_minus = "0";
                                                    }

                                                


                                                    
                                            // echo "bbb";
                                            $beg_inventory= $arrInvLook[0]["past_variance_2"]+ $stok_transfer_beg
                                            +$interbranch_in_past
                                            - $arrInvLook[0]["sales_deduct_physical"]
                                            - $damage_beg
                                            -$stock_transfer_beg_minus
                                            -$past_interbranch;
                                        
                                        }else{
                                            // echo "aaa";
                                            $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]
                                            -$arrInvLook[0]["sales_past"]-$arrInvLook[0]["transit_out"];
                                            }
            }
    
    
                $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
                    +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
                    $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"] -$arrInvLook[0]["sales"]; 


   return $runningtotal;
}

function WarehouseChecker_auditor_qa($product_Code,$date_start,$date_end) {

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