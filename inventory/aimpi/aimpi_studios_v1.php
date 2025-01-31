<?php 


$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// require $sDocRoot."/includes/connect.php";

// $dateStart = date('Y-m-d');
// $dateEnd= date('Y-m-t');

// $FrameData= array();
// $FrameData= storeChecker_smr($arrProduct[$i]['product_code'],$_SESSION['user_login']['store_code'],$dateStart,$dateEnd);


// $store_id = $_SESSION['user_login']['store_code'];
// $frame_code = $arrProduct[$i]['product_code'];





function storeChecker_smr($product_code,$store_id,$date_start,$date_end){
    global $conn;
    $datenow=date('Y-m-d');
    

            if($store_id=='787' ||  $store_id=='788'){
                $reRoute=" AND  date(os.payment_date)>='2020-06-25' 
                 and  os.product_upgrade!='sunnies_studios' ";

                // AND os.product_upgrade!='PL0010'
        } elseif($store_id=='151'){
            $reRoute=" AND  os.lens_code!='L035' AND os.lens_code!='ML0035'
            AND (
                                  os.lens_option='without prescription'
                                    OR
                                    os.lens_code IN (
                                        'L001','L002','L045', 'L014', 'L015', 'L016', 'L017', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024',
                                         'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053',
                                         
                                         'SO001' ,'SO002','SO003',

                                         'ML0001','ML0002','ML0045', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML024', 'ML0029', 'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001',
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
            AND  os.lens_code!='L035'  AND os.lens_code!='ML0035'
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
                            || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)   ){
                                
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
        sls.store_name_proper,
                p51.item_name,
                p51.product_code,(SELECT 
                    coalesce(
                        sum(
                            if(iib.variance_status='approve',
                            REPLACE(iib.actual_count,',',''),
                            REPLACE( iib.`count`,',',''))
                    ),0)
                 FROM inventory_studios  iib
              WHERE
              iib.product_code =p51.product_code
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
                  ),0) FROM inventory_studios  iip
                        WHERE
                        iip.product_code =p51.product_code
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
                  ),0) FROM inventory_studios iid
                        WHERE
                        iid.product_code =p51.product_code
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
                  ),0) FROM inventory_studios iiso
                        WHERE
                        iiso.product_code =p51.product_code
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
                  ),0) FROM inventory_studios  iisi
                        WHERE
                        iisi.product_code =p51.product_code
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
                  ),0) FROM inventory_studios  iisoc
                        WHERE
                        iisoc.product_code =p51.product_code
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
                  ),0) FROM inventory_studios  iiiboc
                        WHERE
                        iiiboc.product_code =p51.product_code
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
                  ),0) FROM inventory_studios iinbic
                        WHERE
                        iinbic.product_code =p51.product_code
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
                  ),0) FROM inventory_studios ipc
                          WHERE
                          ipc.product_code =p51.product_code
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
                  ),0) FROM inventory_studios iidc
                          WHERE
                          iidc.product_code =p51.product_code
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
                  ),0) FROM inventory_studios  ididc
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
                                                    
                                            
                                            FROM `orders_sunnies_studios` os
                                            
                                            LEFT JOIN orders_studios o ON o.order_id=os.order_id
                                            
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
                    'none',
                    (SELECT coalesce(sum(REPLACE(count,',','')),0) FROM inventory_studios ito
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

                                ) AS transit_out_c,

                                (
                                            
                                    SELECT COALESCE(sum(iaccc.input_count),0)- COALESCE(sum(iaccc.running),0)
                                                FROM inventory_actual_count_studios iaccc 
                                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                and iaccc.product_code='".$product_code."'
                                                and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                                                            WHERE iacx.product_code='".$product_code."'
                                                                        AND iacx.store_audited=iaccc.store_audited
                                                                        AND iacx.date_end<'".$date_start."' 
                                                                    )
                                        ) as past_variance,

                                            ( SELECT if( os.packaging_for!='' && packaging_stock!='store',
                                                            '0',
                                                            
                                                        count(po_number))
                                    
                                                    

                                                    FROM `orders_sunnies_studios` os
                                        
                                                    LEFT JOIN orders_studios o ON o.order_id=os.order_id
                                        
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
                                                            FROM inventory_actual_count_studios iaccc 
                                                            where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                            and iaccc.product_code='".$product_code."'
                                                            and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                                                                        WHERE iacx.product_code='".$product_code."'
                                                                                    AND iacx.store_audited=iaccc.store_audited
                                                                                    AND iacx.date_end<'".$date_start."' 
                                                                                )
                                                    ) as past_variance_2,

                                (
                                            
                                    SELECT COALESCE( max(iaccc.date_end),0)
                                                FROM inventory_actual_count_studios iaccc 
                                                where iaccc.date_end<'".$date_start."'  and iaccc.store_audited='".$store_id."'
                                                and iaccc.product_code='".$product_code."'
                                                and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                                                            WHERE iacx.product_code='".$product_code."'
                                                                        AND iacx.store_audited=iaccc.store_audited
                                                                        AND iacx.date_end<'".$date_start."' 
                                                                    )
                                        ) as audit_date,

                                            (
                                                        
                                                SELECT 
                                                DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR))
                                                FROM inventory_studios  iib
                                            WHERE
                                            iib.product_code =p51.product_code
                                            AND
                                            (iib.store_id='".$store_id."')

                                            AND 
                                            iib.status ='received'
                                            AND
                                                
                                                iib.`type`='interbranch'
                                                
                                                

                                            AND
                                                DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory_studios iacx 
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
                                            FROM inventory_studios  iib
                                            WHERE
                                            iib.product_code =p51.product_code
                                            AND
                                            (iib.store_id='".$store_id."')

                                            AND 
                                            iib.status ='received'
                                            AND
                                            iib.type='stock_transfer'

                                            AND
                                            DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory_studios iacx 
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
                                            ),0) FROM inventory_studios iinbic
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
                                                                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>audit_date
                                            AND DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))< '".$date_start."' 
                                            ) as stock_transfer_in_past,


                                            ( SELECT if( os.packaging_for!='' && packaging_stock!='store',
                                            '0',
                                            
                                        count(po_number))

                                                    

                                            FROM `orders_sunnies_studios` os

                                            LEFT JOIN orders_studios o ON o.order_id=os.order_id

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

        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory_studios iid
                WHERE
                iid.product_code =p51.product_code
                AND
                iid.stock_from='".$store_id."'
            
            AND 
            iid.status ='received' 
            AND iid.type='damage'
            AND
            DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory_studios iacx 
                                                                                    WHERE iacx.product_code='".$product_code."'
                                                                                AND iacx.stock_from='".$store_id."'
                                                                                AND  DATE(DATE_ADD(iacx.status_date, INTERVAL 13 HOUR))<'".$date_start."' 
                                                                                AND count !='0'
                                                                                and status='received'
                                                                                and iacx.type='damage'
                                                                            )
        ) as damage_date,

        (select  DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory_studios  iip
                            WHERE
                            iip.product_code =p51.product_code
                            AND
                            iip.stock_from='".$store_id."'
                        
                        AND 
                        iip.status ='received' 
                        AND iip.type='pullout'
                        AND
                        DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory_studios iacx 
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
                                    FROM inventory_studios  iib
                                    WHERE
                                    iib.product_code =p51.product_code
                                    AND
                                    (iib.stock_from='".$store_id."')

                                    AND 
                                    iib.status ='received'
                                    AND
                                    iib.type='stock_transfer'

                                    AND
                                    DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))=(SELECT  DATE(DATE_ADD(max(iacx.status_date), INTERVAL 13 HOUR)) FROM inventory_studios iacx 
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
                                    ),0) FROM inventory_studios  iisi
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
                                    ),0) FROM inventory_studios  iiiboc
                                          WHERE
                                          iiiboc.product_code =p51.product_code
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

                                 ( select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory_studios  iiiboc
                                      WHERE
                                      iiiboc.product_code =p51.product_code
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
                                 FROM inventory_studios iinbic
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
                             
                           (select DATE(DATE_ADD(  max(status_date), INTERVAL 13 HOUR)) FROM inventory_studios iinbicd
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
                                


                            FROM poll_51_studios p51
                            LEFT JOin inventory_studios ip on ip.product_code=p51.product_code
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







function GetStock($Frame){
    global $store_code;
    global $FrameData;
    global  $frame_code;
    $runningtotal = 0;
                                                              
      if(empty($FrameData)){
  
                  $FrameData[0]['beg_inventory'] ='0';
                  $FrameData[0]['pullout'] ='0';
                  $FrameData[0]['damage'] ='0';
                  $FrameData[0]['stock_transfer_out'] ='0';
                  $FrameData[0]['stock_transfer_in_c'] ='0';
                  $FrameData[0]['stock_transfer_out_c'] ='0';
                  $FrameData[0]['interbranch_out_c'] ='0';
                  $FrameData[0]['interbranch_in_c'] ='0';
                  $FrameData[0]['pullout_c'] ='0';
                  $FrameData[0]['damage_c'] ='0';
                  $FrameData[0]['damage_i'] ='0';
                  $FrameData[0]['sales'] ='0';
                  $FrameData[0]['number'] ='0';
                  $FrameData[0]['transit_out'] ='0';
                  $FrameData[0]['requested'] ='0';
                  $FrameData[0]['transit_in'] ='0';
                  $FrameData[0]['transit_out_c'] ='0';
                  $FrameData[0]['past_variance']  ='0';
                  $FrameData[0]['sales_past']  ='0';
                  
                  
                  }else{
                  
                  
                  
                  if(($FrameData[0]['beg_inventory'] =='0'  ||$FrameData[0]['beg_inventory'] ==''  ) &&
                              $FrameData[0]['pullout'] =='0' &&
                              $FrameData[0]['damage'] =='0' &&
                              $FrameData[0]['stock_transfer_out'] =='0' &&
                              $FrameData[0]['stock_transfer_in_c'] =='0' &&
                              $FrameData[0]['stock_transfer_out_c'] =='0' &&
                              $FrameData[0]['interbranch_out_c'] =='0' &&
                              $FrameData[0]['interbranch_in_c'] =='0' &&
                              $FrameData[0]['pullout_c'] =='0' &&
                              $FrameData[0]['damage_c'] =='0' &&
                              $FrameData[0]['damage_i'] =='0' &&
                              $FrameData[0]['sales'] =='0' &&
                              $FrameData[0]['number'] =='0' &&
                              $FrameData[0]['transit_out'] =='0' &&
                              $FrameData[0]['requested'] =='0' &&
                              $FrameData[0]['transit_in'] =='0' &&
                              $FrameData[0]['transit_out_c'] =='0' 
                  
                  ){
              
                          $beg_inventory=$FrameData[0]["past_variance_2"];
                  
                  }
                  else{
                  
                  
                                              $beg_inventoryx =$FrameData[0]["beg_inventory"]
                                              -$FrameData[0]["pullout"]
                                              -$FrameData[0]["damage"]
                                              -$FrameData[0]["stock_transfer_out"]
                                            	-$FrameData[0]["sales_past"]
                                              -$FrameData[0]["transit_out"];
                                              
                                          
                                  
                                  if($store_code=='warehouse'){
                                  
                                          
                                              if(strpos($FrameData[0]["past_variance"],"-")){
                                                  $beg_inventory=$beg_inventoryx-$FrameData[0]["past_variance"];
                                          
                                              }else{
                                                      $beg_inventory=$beg_inventoryx+$FrameData[0]["past_variance"];
                                              }
                                  }else{
                                  
                                  
                                  
                                          
                                          if(strpos($FrameData[0]["past_variance"],"-")){
                                          
                                                  $beg_inventory=$beg_inventoryx-$FrameData[0]["past_variance"];
                                              
                                              }else{
                                              
                                          
                                          
                                          
                                          
                                                  if(  ($FrameData[0]["audit_date"] >=$FrameData[0]["stock_transfer_status_date"] 
                                                          &&  !empty($FrameData[0]["stock_transfer_status_date"] )  && empty($FrameData[0]["interbranch_out_past_date"]  )  )
                                                      
                                                  ){
                                                      
                                              
                                                      $beg_inventory=$FrameData[0]["past_variance_2"] -$FrameData[0]["sales_deduct_physical"];
                                                      
                                          
                                                  }
                                                  elseif($FrameData[0]["audit_date"] >=$FrameData[0]["interbranch_out_past_date"] 
                                                  &&  !empty($FrameData[0]["interbranch_out_past_date"]) && empty($FrameData[0]["stock_transfer_status_date"] ) ){
                                          
                                          
                                                      $beg_inventory=$FrameData[0]["past_variance_2"] -$FrameData[0]["sales_deduct_physical"];
                                                  }
                                                  
                                                  elseif( ($FrameData[0]["audit_date"] <=$FrameData[0]["stock_transfer_status_date"]) 
                                                          || ($FrameData[0]["audit_date"] <=$FrameData[0]["damage_past_date"]) 
                                                          || ($FrameData[0]["audit_date"]!=''  &&  empty($FrameData[0]["stock_transfer_status_date"]))
                                                          || ($FrameData[0]["audit_date"] <=$FrameData[0]["stock_transfer_minus_date"]) 
                                                          || ($FrameData[0]["audit_date"] <=$FrameData[0]["interbranch_out_past_date"])
                                                          ||  ($FrameData[0]["audit_date"] <=$FrameData[0]["interbranch_in_past_date"])
                                                          ){
                                                          
                                                              if($FrameData[0]["audit_date"] <=$FrameData[0]["stock_transfer_status_date"] || 
                                                              ($FrameData[0]["audit_date"]!=''  &&  empty($FrameData[0]["stock_transfer_status_date"])) ) {
                                                                  
                                                              $stok_transfer_beg=$FrameData[0]["stock_transfer_in_past"];
                                                              }else{
                                                                  $stok_transfer_beg="0";
                                          
                                                              }
                                                              
                                          
                                          
                                                              if($FrameData[0]["audit_date"] <=$FrameData[0]["interbranch_in_past_date"] || 
                                                              ($FrameData[0]["audit_date"]!=''  &&  empty($FrameData[0]["interbranch_in_past_date"])) ) {
                                                                  
                                                                  $interbranch_in_past=$FrameData[0]["interbranch_in_past"];
                                                              }else{
                                                                  $interbranch_in_past="0";
                                          
                                                              }
                                                              
                                          
                                          
                                                              if($FrameData[0]["audit_date"] <=$FrameData[0]["damage_past_date"]) {
                                                                      $damage_beg =$FrameData[0]["damage"];
                                                              }else{
                                                                  $damage_beg ="0";
                                                              }
                                          
                                                              if($FrameData[0]["audit_date"] <=$FrameData[0]["interbranch_out_past_date"]) {
                                                                  $past_interbranch =$FrameData[0]["interbranch_out_past"];
                                                          }else{
                                                              $past_interbranch ="0";
                                                          }
                                                          
                                                              
                                                              if(($FrameData[0]["audit_date"] <=$FrameData[0]["stock_transfer_minus_date"]) ){
                                                              $stock_transfer_beg_minus =$FrameData[0]["stock_transfer_minus"];
                                                          
                                          
                                                              }else{
                                                                  $stock_transfer_beg_minus = "0";
                                                              }
                                          
                                                          
                                          
                                          
                                                              
                                                      // echo "bbb";
                                                      $beg_inventory= $FrameData[0]["past_variance_2"]+ $stok_transfer_beg
                                                      +$interbranch_in_past
                                                      -$FrameData[0]["sales_deduct_physical"]
                                                      - $damage_beg
                                                      -$stock_transfer_beg_minus
                                                      -$past_interbranch;
                                                  
                                                  }else{
                                                      
                                                          $beg_inventory=$beg_inventoryx;
                                                      }
                                              }
                                      // }	
                                  }		
                                  
                                  $runningtotal=  $beg_inventory +$FrameData[0]["stock_transfer_in_c"]
                                  +$FrameData[0]["interbranch_in_c"]-$FrameData[0]["stock_transfer_out_c"]-
                                  $FrameData[0]["interbranch_out_c"]-$FrameData[0]["damage_c"]-$FrameData[0]["pullout_c"]-$FrameData[0]['sales']; 
                                  // -$sale_frame;
                                                                                  
                      }
                  }
                  
      $arrJsonlookupframe=json_encode($runningtotal);
      
      // showStatus($FrameData);
  
      
            //   if ($arrJsonlookupframe){
            //   print_r($arrJsonlookupframe);
            //   }
            //   else{
            //   echo json_last_error_msg();
            //   }

            return $runningtotal;
  
};




// $current_total_stock = GetStock($arrProduct[$i]['product_code']);



?>