<?php 
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
                        "past_variance", "interbranch_out",
                  );


$arrInvLook=array();
 $query="SELECT 
          sls.store_name,
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
                  DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                
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
                            iip.stock_from=sls.store_id
                      
                      AND 
                      iip.status ='received' 
                      AND iip.type='pullout'
                      AND
                      DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$datenow."'
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
                            iid.stock_from=sls.store_id
                      
                      AND 
                      iid.status ='received' 
                      AND iid.type='damage'
                      AND
                      DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                  ) as damage,

              (select coalesce(
                        sum(
                            if( iiso.variance_status='approve',
                            REPLACE(  iiso.actual_count  ,',',''),
                            REPLACE(  iiso.actual_count  ,',','') )
                    ),0) FROM inventory iiso
                          WHERE
                          iiso.product_code =p51.product_code
                            AND
                            iiso.stock_from=sls.store_id
                      
                      AND 
                      iiso.status ='received' 
                      AND iiso.type='stock_transfer'
                      AND
                      DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                  ) as stock_transfer_out,
                  

              (select coalesce(
                        sum(
                            if(iisi.variance_status='approve',
                            REPLACE(  iisi.actual_count  ,',','') ,
                            REPLACE(  iisi.`count`  ,',','') )
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
                      DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as stock_transfer_in_c,


              (select coalesce(
                        sum(
                            if(iisoc.variance_status='approve',
                            REPLACE(  iisoc.actual_count  ,',','') ,
                            REPLACE(  iisoc.`count`  ,',','') )
                    ),0) FROM inventory  iisoc
                          WHERE
                          iisoc.product_code =p51.product_code
                            AND
                            iisoc.stock_from=sls.store_id
                      
                      AND 
                      iisoc.status ='received' 
                      AND iisoc.type='stock_transfer'
                      AND
                      DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as stock_transfer_out_c,



              (select coalesce(
                        sum(
                            if(iiiboc.variance_status='approve',
                            REPLACE(  iiiboc.actual_count  ,',','') ,
                            REPLACE( iiiboc.`count`  ,',','') )
                    ),0) FROM inventory  iiiboc
                          WHERE
                          iiiboc.product_code =p51.product_code
                            AND
                            iiiboc.stock_from=sls.store_id
                      
                      AND 
                      iiiboc.status ='received' 
                      AND iiiboc.type='interbranch'
                      
                      AND
                      DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as interbranch_out_c,
                  
                  (select coalesce(
                        sum(
                            if(iinbic.variance_status='approve',
                            REPLACE( iinbic.actual_count  ,',','') ,
                            REPLACE( iinbic.`count`  ,',','') )
                    ),0) FROM inventory iinbic
                          WHERE
                          iinbic.product_code =p51.product_code
                            AND
                            iinbic.store_id=sls.store_id
                      
                      AND 
                      iinbic.status ='received' 
                      AND iinbic.type='interbranch'
                    
                      AND
                      DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as interbranch_in_c ,
              

              (select coalesce(
                        sum(
                            if(ipc.variance_status='approve',
                            REPLACE( ipc.actual_count ,',','') ,
                            REPLACE( ipc.`count` ,',','') )
                    ),0) FROM inventory ipc
                            WHERE
                            ipc.product_code =p51.product_code
                              AND
                              ipc.stock_from=sls.store_id
                        
                        AND 
                        ipc.status ='received' 
                        AND ipc.type='pullout'
                        AND
                        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as pullout_c,
  
              (select coalesce(
                        sum(
                            if(iidc.variance_status='approve',
                            REPLACE( iidc.actual_count  ,',','') ,
                            REPLACE(  iidc.`count`  ,',','') )
                    ),0) FROM inventory iidc
                            WHERE
                            iidc.product_code =p51.product_code
                              AND
                              iidc.stock_from=sls.store_id
                        
                        AND 
                        iidc.status ='received' 
                        AND iidc.type='damage'
                        AND
                        DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as damage_c,
            
                  (select coalesce(
                        sum(
                            if(ididc.variance_status='approve',
                            REPLACE(  ididc.actual_count  ,',','') ,
                            REPLACE(  ididc.`count`  ,',','') )
                    ),0) FROM inventory  ididc
                            WHERE
                            ididc.product_code =p51.product_code
                              AND
                              ididc.store_id='warehouse_damage'
                        
                        AND 
                        ididc.status ='received' 
                        AND ididc.type='damage'
                        AND
                        DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as damage_i,

                        ( SELECT count(po_number)
                              

                        FROM `orders_specs` os

                        LEFT JOIN orders o ON o.order_id=os.order_id

                        WHERE 
                        payment='y'
                        And (status!='return' OR
                        status!='cancelled' OR
                        status!='returned' 
                        )
                        AND date(os.payment_date)>='2020-02-4'
                        AND  date(os.payment_date)<'".$datenow."'
                        AND os.product_code='".$_GET['frame_code']."'
                        AND (
                        os.lens_option='without prescription'
                        OR
                        os.lens_code='SO1001')
                        AND  origin_branch=sls.store_id   
                        ) as sales,
                        sls.phone_number,
                        
                        

(
            

        SELECT COALESCE(sum(iaccc.input_count),0)
                FROM inventory_actual_count iaccc 
                where iaccc.date_end<'".$datenow."'  and iaccc.store_audited=sls.store_id  
                and iaccc.product_code='".$_GET['frame_code']."'
                and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                            WHERE iacx.product_code='".$_GET['frame_code']."'
                                        AND iacx.store_audited=sls.store_id  
                                        AND iacx.date_end<'".$datenow."' 
                                    )
        ) as past_variance_2,



(select coalesce(
          sum(
              if(iiiboc.variance_status='approve',
              REPLACE(  iiiboc.actual_count  ,',','') ,
              REPLACE( iiiboc.`count`  ,',','') )
      ),0) FROM inventory  iiiboc
            WHERE
            iiiboc.product_code =p51.product_code
              AND
              iiiboc.stock_from=sls.store_id
        
        AND 
        iiiboc.status ='received' 
        AND iiiboc.type='interbranch'
        
      
          AND
          DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
    ) as interbranch_out

   FROM stores_locations sls
   LEFT JOin inventory ip on ip.store_id=sls.store_id
   LEFT JOIN poll_51 p51 on p51.product_code=ip.product_code
     WHERE ip.product_code='".$_GET['frame_code']."'
     group by sls.store_id,ip.product_code
      UNION ALL 
   SELECT 

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
                  DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                
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
                            iip.stock_from=ll.lab_id
                      
                      AND 
                      iip.status ='received' 
                      AND iip.type='pullout'
                      AND
                      DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$datenow."'
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
                      DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                  ) as damage,

              (select coalesce(
                        sum(
                            if( iiso.variance_status='approve',
                            REPLACE(  iiso.actual_count  ,',',''),
                            REPLACE(  iiso.actual_count  ,',','') )
                    ),0) FROM inventory iiso
                          WHERE
                          iiso.product_code =p51.product_code
                            AND
                            iiso.stock_from=ll.lab_id
                      
                      AND 
                      iiso.status ='received' 
                      AND iiso.type='stock_transfer'
                      AND
                      DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                  ) as stock_transfer_out,
                  

              (select coalesce(
                        sum(
                            if(iisi.variance_status='approve',
                            REPLACE(  iisi.actual_count  ,',','') ,
                            REPLACE(  iisi.`count`  ,',','') )
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
                      DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as stock_transfer_in_c,


              (select coalesce(
                        sum(
                            if(iisoc.variance_status='approve',
                            REPLACE(  iisoc.actual_count  ,',','') ,
                            REPLACE(  iisoc.`count`  ,',','') )
                    ),0) FROM inventory  iisoc
                          WHERE
                          iisoc.product_code =p51.product_code
                            AND
                            iisoc.stock_from=ll.lab_id
                      
                      AND 
                      iisoc.status ='received' 
                      AND iisoc.type='stock_transfer'
                      AND
                      DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as stock_transfer_out_c,



              (select coalesce(
                        sum(
                            if(iiiboc.variance_status='approve',
                            REPLACE(  iiiboc.actual_count  ,',','') ,
                            REPLACE( iiiboc.`count`  ,',','') )
                    ),0) FROM inventory  iiiboc
                          WHERE
                          iiiboc.product_code =p51.product_code
                            AND
                            iiiboc.stock_from=ll.lab_id
                      
                      AND 
                      iiiboc.status ='received' 
                      AND iiiboc.type='interbranch'
                      
                      AND
                      DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as interbranch_out_c,
                  
                  (select coalesce(
                        sum(
                            if(iinbic.variance_status='approve',
                            REPLACE( iinbic.actual_count  ,',','') ,
                            REPLACE( iinbic.`count`  ,',','') )
                    ),0) FROM inventory iinbic
                          WHERE
                          iinbic.product_code =p51.product_code
                            AND
                            iinbic.store_id=ll.lab_id
                      
                      AND 
                      iinbic.status ='received' 
                      AND iinbic.type='interbranch'
                    
                      AND
                      DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as interbranch_in_c ,
              

              (select coalesce(
                        sum(
                            if(ipc.variance_status='approve',
                            REPLACE( ipc.actual_count ,',','') ,
                            REPLACE( ipc.`count` ,',','') )
                    ),0) FROM inventory ipc
                            WHERE
                            ipc.product_code =p51.product_code
                              AND
                              ipc.stock_from=ll.lab_id
                        
                        AND 
                        ipc.status ='received' 
                        AND ipc.type='pullout'
                        AND
                        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as pullout_c,
  
              (select coalesce(
                        sum(
                            if(iidc.variance_status='approve',
                            REPLACE( iidc.actual_count  ,',','') ,
                            REPLACE(  iidc.`count`  ,',','') )
                    ),0) FROM inventory iidc
                            WHERE
                            iidc.product_code =p51.product_code
                              AND
                              iidc.stock_from=ll.lab_id
                        
                        AND 
                        iidc.status ='received' 
                        AND iidc.type='damage'
                        AND
                        DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as damage_c,
            
                  (select coalesce(
                        sum(
                            if(ididc.variance_status='approve',
                            REPLACE(  ididc.actual_count  ,',','') ,
                            REPLACE(  ididc.`count`  ,',','') )
                    ),0) FROM inventory  ididc
                            WHERE
                            ididc.product_code =p51.product_code
                              AND
                              ididc.store_id='warehouse_damage'
                        
                        AND 
                        ididc.status ='received' 
                        AND ididc.type='damage'
                        AND
                        DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as damage_i,
                    ( SELECT coalesce(CASE
                                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                                            END,0)
            FROM `orders_specs` os

            LEFT JOIN orders o ON o.order_id=os.order_id

      WHERE 
         payment='y'
          And (status!='return' OR
            status!='cancelled' OR
             status!='returned' 
            )
            AND os.product_code='".$_GET['frame_code']."'
            AND date(os.payment_date)>='2020-02-4'
           AND  date(os.payment_date)<'".$datenow."'
             AND os.lens_option='with prescription' 
            AND os.lens_code!='SO1001'

          AND  o.laboratory=ll.lab_id
           ) as sales,
           ll.phone_number,
                        
                        

                        (
                                    
                        
                                SELECT COALESCE(sum(iaccc.input_count),0)
                                        FROM inventory_actual_count iaccc 
                                        where iaccc.date_end<'".$datenow."'  and iaccc.store_audited=ll.lab_id 
                                        and iaccc.product_code='".$_GET['frame_code']."'
                                        and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                    WHERE iacx.product_code='".$_GET['frame_code']."'
                                                                AND iacx.store_audited=ll.lab_id  
                                                                AND iacx.date_end<'".$datenow."' 
                                                            )
                                ) as past_variance_2,



(select coalesce(
          sum(
              if(iiiboc.variance_status='approve',
              REPLACE(  iiiboc.actual_count  ,',','') ,
              REPLACE( iiiboc.`count`  ,',','') )
      ),0) FROM inventory  iiiboc
            WHERE
            iiiboc.product_code =p51.product_code
              AND
              iiiboc.stock_from=ll.lab_id
        
        AND 
        iiiboc.status ='received' 
        AND iiiboc.type='interbranch'
        
      
          AND
          DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
    ) as interbranch_out
   FROM labs_locations ll
   LEFT JOIN inventory ip ON ip.store_id=ll.lab_id
  LEFT JOIN poll_51 p51 on p51.product_code=ip.product_code
    WHERE ip.product_code='".$_GET['frame_code']."'
     group by ll.lab_id,ip.product_code
     UNION ALL 
   SELECT 

         'warehouse',
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
                  DATE(DATE_ADD(iib.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                
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
                            iip.stock_from='warehouse'
                      
                      AND 
                      iip.status ='received' 
                      AND iip.type='pullout'
                      AND
                      DATE(DATE_ADD(iip.status_date, INTERVAL 13 HOUR))<'".$datenow."'
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
                            iid.stock_from='warehouse'
                      
                      AND 
                      iid.status ='received' 
                      AND iid.type='damage'
                      AND
                      DATE(DATE_ADD(iid.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                  ) as damage,

              (select coalesce(
                        sum(
                            if( iiso.variance_status='approve',
                            REPLACE(  iiso.actual_count  ,',',''),
                            REPLACE(  iiso.actual_count  ,',','') )
                    ),0) FROM inventory iiso
                          WHERE
                          iiso.product_code =p51.product_code
                            AND
                            iiso.stock_from='warehouse'
                      
                      AND 
                      iiso.status ='received' 
                      AND iiso.type='stock_transfer'
                      AND
                      DATE(DATE_ADD(iiso.status_date, INTERVAL 13 HOUR))<'".$datenow."'
                  ) as stock_transfer_out,
                  

              (select coalesce(
                        sum(
                            if(iisi.variance_status='approve',
                            REPLACE(  iisi.actual_count  ,',','') ,
                            REPLACE(  iisi.`count`  ,',','') )
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
                      DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as stock_transfer_in_c,


              (select coalesce(
                        sum(
                            if(iisoc.variance_status='approve',
                            REPLACE(  iisoc.actual_count  ,',','') ,
                            REPLACE(  iisoc.`count`  ,',','') )
                    ),0) FROM inventory  iisoc
                          WHERE
                          iisoc.product_code =p51.product_code
                            AND
                            iisoc.stock_from='warehouse'
                      
                      AND 
                      iisoc.status ='received' 
                      AND iisoc.type='stock_transfer'
                      AND
                      DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as stock_transfer_out_c,



              (select coalesce(
                        sum(
                            if(iiiboc.variance_status='approve',
                            REPLACE(  iiiboc.actual_count  ,',','') ,
                            REPLACE( iiiboc.`count`  ,',','') )
                    ),0) FROM inventory  iiiboc
                          WHERE
                          iiiboc.product_code =p51.product_code
                            AND
                            iiiboc.stock_from='warehouse'
                      
                      AND 
                      iiiboc.status ='received' 
                      AND iiiboc.type='interbranch'
                      
                      AND
                      DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as interbranch_out_c,
                  
                  (select coalesce(
                        sum(
                            if(iinbic.variance_status='approve',
                            REPLACE( iinbic.actual_count  ,',','') ,
                            REPLACE( iinbic.`count`  ,',','') )
                    ),0) FROM inventory iinbic
                          WHERE
                          iinbic.product_code =p51.product_code
                            AND
                            iinbic.store_id='warehouse'
                      
                      AND 
                      iinbic.status ='received' 
                      AND iinbic.type='interbranch'
                    
                      AND
                      DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                  ) as interbranch_in_c ,
              

              (select coalesce(
                        sum(
                            if(ipc.variance_status='approve',
                            REPLACE( ipc.actual_count ,',','') ,
                            REPLACE( ipc.`count` ,',','') )
                    ),0) FROM inventory ipc
                            WHERE
                            ipc.product_code =p51.product_code
                              AND
                              ipc.stock_from='warehouse'
                        
                        AND 
                        ipc.status ='received' 
                        AND ipc.type='pullout'
                        AND
                        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as pullout_c,
  
              (select coalesce(
                        sum(
                            if(iidc.variance_status='approve',
                            REPLACE( iidc.actual_count  ,',','') ,
                            REPLACE(  iidc.`count`  ,',','') )
                    ),0) FROM inventory iidc
                            WHERE
                            iidc.product_code =p51.product_code
                              AND
                              iidc.stock_from='warehouse'
                        
                        AND 
                        iidc.status ='received' 
                        AND iidc.type='damage'
                        AND
                        DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(iidc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as damage_c,
            
                  (select coalesce(
                        sum(
                            if(ididc.variance_status='approve',
                            REPLACE(  ididc.actual_count  ,',','') ,
                            REPLACE(  ididc.`count`  ,',','') )
                    ),0) FROM inventory  ididc
                            WHERE
                            ididc.product_code =p51.product_code
                              AND
                              ididc.store_id='warehouse_damage'
                        
                        AND 
                        ididc.status ='received' 
                        AND ididc.type='damage'
                        AND
                        DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))>='".$datenow."'
                        AND
                        DATE(DATE_ADD(ididc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
                    ) as damage_i,
                    '0',
           'none',
                        
                        

                        (
                                    
                        
                                SELECT COALESCE(sum(iaccc.input_count),0)
                                        FROM inventory_actual_count iaccc 
                                        where iaccc.date_end<'".$datenow."'  and iaccc.store_audited='warehouse'
                                        and iaccc.product_code='".$_GET['frame_code']."'
                                        and iaccc.date_end=(SELECT max(iacx.date_end) FROM inventory_actual_count iacx 
                                                                    WHERE iacx.product_code='".$_GET['frame_code']."'
                                                                AND iacx.store_audited='warehouse'
                                                                AND iacx.date_end<'".$datenow."' 
                                                            )
                                ) as past_variance_2,



(select coalesce(
          sum(
              if(iiiboc.variance_status='approve',
              REPLACE(  iiiboc.actual_count  ,',','') ,
              REPLACE( iiiboc.`count`  ,',','') )
      ),0) FROM inventory  iiiboc
            WHERE
            iiiboc.product_code =p51.product_code
              AND
              iiiboc.stock_from='warehouse'
        
        AND 
        iiiboc.status ='received' 
        AND iiiboc.type='interbranch'
        
       
          AND
          DATE(DATE_ADD(iiiboc.status_date, INTERVAL 13 HOUR))<='".$datenow."'
    ) as interbranch_out

   FROM   inventory ip 
  LEFT JOIN poll_51 p51 on p51.product_code=ip.product_code
    WHERE ip.product_code='".$_GET['frame_code']."'
    AND (ip.stock_from='warehouse' OR ip.store_id='warehouse')
     group by ip.product_code";

	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,
       $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18);

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








     ?>