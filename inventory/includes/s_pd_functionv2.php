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



function perdayStoreSalesVS($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;
           
           
           $arrPDque=array();
           if($branch_type=='store'){
                                                
                            $reRoute=" ";

                            // $carekits=" AND os.product_code ";

                // if($store_id=='787' ||  $store_id=='788'){
                if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
                || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code) 
                || preg_match("/P1/i",$product_code) 
                || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)  
                || preg_match("/SC/i",$product_code)
                || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code)
                || preg_match("/SSGWPCB/i",$product_code)   || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code) 
                || preg_match("/TB0/i",$product_code)    || preg_match("/SGC/i",$product_code)   || preg_match("/DS/i",$product_code) 
                || preg_match("/ST/i",$product_code)     || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code) 
                || preg_match("/CPV/i",$product_code)   || preg_match("/SWS/i",$product_code)  || preg_match("/SMHP/i",$product_code) 
                || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code) || preg_match("/SS00/i",$product_code) 
                || preg_match("/SMZ/i",$product_code)  || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code) 
                || preg_match("/SPHC/i",$product_code) || preg_match("/NT0/i",$product_code)  || preg_match("/GSOM/i",$product_code) 
                || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C100/i",$product_code) 
                || preg_match("/MRK/i",$product_code) 
                ){


                                        $carekits=" AND os.product_upgrade ";
                                        $condition1="  ";

                                }else{
                                    $condition1=" and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
                                    $carekits=" AND os.product_code ";

                                }
                            // }else{
                            //     $condition1=" and os.product_upgrade ='sunnies_studios' ";
                            //     $carekits=" AND os.product_code ";
                            // }
                                

         $query ="SELECT count(po_number),
                            os.payment_date,
                            os.po_number
                                                                        
                                                                
                    FROM `orders_specs` os

                    LEFT JOIN orders  o ON o.order_id=os.order_id

                    WHERE 
                    payment='y'
                    And os.status NOT IN ('return','cancelled','returned','failed' )
                    AND date(os.payment_date)>='2020-02-4'
                     ".$condition1."  
                    AND  date(os.payment_date)>='".$date_start."'
                    AND  date(os.payment_date)<='".$date_end."'
                    ". $carekits."  ='".$product_code."'
                    ".$reRoute." 
                    AND  origin_branch='".$store_id."' 
                    GROUP BY os.payment_date,orders_specs_id ";

           }elseif($branch_type=='lab'){

            if($date_start<'2020-06-26'){
                $filterVirtual= " ";
            }
            else{
                $filterVirtual= " AND o.origin_branch!='787' AND o.origin_branch!='788' ";

            }

                            $lensFilter="
                            AND (
                            (
                                (os.lens_option='with prescription' )
                                    AND
                                    os.lens_code NOT IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')
                                AND  o.laboratory='".$store_id."'
                            )
                            or (lens_code='L035' AND xsl.lab_id='".$store_id."')               
                            )
                            
                            ";

                    $query ="SELECT    coalesce(CASE
                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                            END,0) AS count,
                            os.payment_date,
                            os.po_number
                                                    

                            FROM `orders_specs` os

                            LEFT JOIN orders o ON o.order_id=os.order_id
                            LEFT JOIN store_codes_studios xsl ON xsl.store_code =o.origin_branch
                            WHERE 
                            payment='y'
                            And os.status NOT IN ('return','cancelled','returned','failed' )
                            AND os.product_code='".$product_code."'
                            AND date(os.payment_date)>='2020-02-4'
                            AND  date(os.payment_date)>='".$date_start."'
                            AND  date(os.payment_date)<='".$date_end."'
                            
                            ".$lensFilter."

                             ".$filterVirtual." 
                             GROUP BY os.payment_date,orders_specs_id 
                            
                    ";

           }

                    $grabParams=array("sales_count", "payment_date", "po_number");

                   
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrPDque[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);	

        };

        return $arrPDque;
}



function perdayStoreSalesMS($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;
           
           
           $arrPDque=array();
           if($branch_type=='store'){
                                                
                            $reRoute=" ";

                            // $carekits=" AND os.product_code ";

                            // if($store_id=='787' ||  $store_id=='788'){
                                if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
                                || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                                                    || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code)  || preg_match("/P1/i",$product_code) 
                                                    || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)   || preg_match("/SC/i",$product_code)
                                                      || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                                                      || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) || preg_match("/SSGWPCB/i",$product_code)   || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code)  
                                                      || preg_match("/TB0/i",$product_code)  || preg_match("/SGC/i",$product_code)   || preg_match("/DS/i",$product_code)   || preg_match("/ST/i",$product_code)  
                                                      || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code)  || preg_match("/CPV/i",$product_code)   || preg_match("/SWS/i",$product_code)  
                                                      || preg_match("/SMHP/i",$product_code) || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code) || preg_match("/SMZ/i",$product_code) 
                                                      || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) 
                                                      || preg_match("/VCP/i",$product_code)
                                                       || preg_match("/SS00/i",$product_code) || preg_match("/SPHC/i",$product_code) 
                                                       || preg_match("/NT0/i",$product_code)  || preg_match("/GSOM/i",$product_code) 
                                                       || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C100/i",$product_code) 
                                                       || preg_match("/MRK/i",$product_code) 
                                                      ){

                                        $carekits=" AND os.product_upgrade ";
                                        $condition1="  ";

                                }else{
                                    $condition1=" and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
                                    $carekits=" AND os.product_code ";

                                }
                            // }else{
                            //     $condition1=" and os.product_upgrade ='sunnies_studios' ";
                            //     $carekits=" AND os.product_code ";
                            // }
                            $stock_from ="  AND  os.stock_from='".$store_id."'  ";                  

         $query ="SELECT count(po_number),
                            os.payment_date,
                            os.po_number
                                                                        
                                                                
                    FROM `orders_sunnies_studios` os

                    LEFT JOIN orders_studios  o ON o.order_id=os.order_id

                    WHERE 
                    payment='y'
                    And os.status NOT IN ('return','cancelled','returned','failed' )
                    AND date(os.payment_date)>='2020-02-4'
                     ".$condition1."  
                    AND  date(os.payment_date)>='".$date_start."'
                    AND  date(os.payment_date)<='".$date_end."'
                    ". $carekits."  ='".$product_code."'
                    ".$reRoute." 
                   ".$stock_from."
                    GROUP BY os.payment_date,orders_specs_id 
          
                    ";

           }elseif($branch_type=='lab'){

            if($date_start<'2020-06-26'){
                $filterVirtual= " ";
            }
            else{
                $filterVirtual= " AND o.origin_branch!='787' AND o.origin_branch!='788' ";

            }

                            $lensFilter="
                            AND (
                            (
                                (os.lens_option='with prescription' )
                                    AND
                                    os.lens_code NOT IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')
                                AND  o.laboratory='".$store_id."'
                            )
                            or (lens_code='L035' AND xsl.lab_id='".$store_id."')               
                            )
                            
                            ";

                    $query ="SELECT    coalesce(CASE
                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                            END,0) AS count,
                            os.payment_date,
                            os.po_number
                                                    

                            FROM `orders_sunnies_studios` os

                            LEFT JOIN orders_studios  o ON o.order_id=os.order_id
                            LEFT JOIN store_codes_studios xsl ON xsl.store_code =o.origin_branch
                            WHERE 
                            payment='y'
                            And os.status NOT IN ('return','cancelled','returned','failed' )
                            AND os.product_code='".$product_code."'
                            AND date(os.payment_date)>='2020-02-4'
                            AND  date(os.payment_date)>='".$date_start."'
                            AND  date(os.payment_date)<='".$date_end."'
                            
                            ".$lensFilter."

                             ".$filterVirtual." 
                             GROUP BY os.payment_date,orders_specs_id 
                            
                    ";

           }

                    $grabParams=array("sales_count", "payment_date", "po_number");

                   
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrPDque[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);	

        };

        return $arrPDque;
}


function perdayStoreSales($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;
           
           
           $arrPDque=array();
           if($branch_type=='store'){
                                                
                            $reRoute=" ";

                            // $carekits=" AND os.product_code ";

                            // if($store_id=='787' ||  $store_id=='788'){
                                if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
                                || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                                                    || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code)  || preg_match("/AFC/i",$product_code)  || preg_match("/P1/i",$product_code) 
                                                    || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)  || preg_match("/SWB/i",$product_code)   || preg_match("/SC/i",$product_code)
                                                      || preg_match("/GRH/i",$product_code) || preg_match("/MSAC/i",$product_code)  || preg_match("/MSKC/i",$product_code)  || preg_match("/KLB/i",$product_code) 
                                                      || preg_match("/MSEP/i",$product_code)  || preg_match("/SPB0/i",$product_code)  || preg_match("/MSLS/i",$product_code) || preg_match("/MSHS/i",$product_code) || preg_match("/SSGWPCB/i",$product_code)   || preg_match("/SSP/i",$product_code)  || preg_match("/HT0/i",$product_code)  
                                                      || preg_match("/TB0/i",$product_code)  || preg_match("/SGC/i",$product_code)   || preg_match("/DS/i",$product_code)   || preg_match("/ST/i",$product_code)  
                                                      || preg_match("/SMS/i",$product_code)  || preg_match("/SML/i",$product_code)  || preg_match("/CPV/i",$product_code)   || preg_match("/SWS/i",$product_code)  
                                                      || preg_match("/SMHP/i",$product_code) || preg_match("/SFSP/i",$product_code) || preg_match("/MSTS/i",$product_code) || preg_match("/SMZ/i",$product_code) 
                                                      || preg_match("/MSSS/i",$product_code) || preg_match("/VS/i",$product_code) || preg_match("/VCP/i",$product_code) 
                                                      || preg_match("/SS00/i",$product_code) || preg_match("/SPHC/i",$product_code) 
                                                      || preg_match("/DM/i",$product_code)  || preg_match("/DTS/i",$product_code) || preg_match("/C100/i",$product_code) 
                                                      || preg_match("/MRK/i",$product_code) 
                                                      ){

                                        $carekits=" AND os.product_upgrade ";
                                        $condition1="  ";

                                }else{
                                    $condition1=" and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
                                    $carekits=" AND os.product_code ";

                                }
                            // }else{
                            //     $condition1=" and os.product_upgrade ='sunnies_studios' ";
                            //     $carekits=" AND os.product_code ";
                            // }
                                

         $query ="SELECT count(po_number),
                            os.payment_date,
                            os.po_number
                                                                        
                                                                
                    FROM `orders_sunnies_studios` os

                    LEFT JOIN orders_studios  o ON o.order_id=os.order_id

                    WHERE 
                    payment='y'
                    And os.status NOT IN ('return','cancelled','returned','failed' )
                    AND date(os.payment_date)>='2020-02-4'
                     ".$condition1."  
                    AND  date(os.payment_date)>='".$date_start."'
                    AND  date(os.payment_date)<='".$date_end."'
                    ". $carekits."  ='".$product_code."'
                    ".$reRoute." 
                    AND  origin_branch='".$store_id."' 
                    GROUP BY os.payment_date,orders_specs_id ";

           }elseif($branch_type=='lab'){

            if($date_start<'2020-06-26'){
                $filterVirtual= " ";
            }
            else{
                $filterVirtual= " AND o.origin_branch!='787' AND o.origin_branch!='788' ";

            }

                            $lensFilter="
                            AND (
                            (
                                (os.lens_option='with prescription' )
                                    AND
                                    os.lens_code NOT IN ('L013', 'L014', 'L015', 'L016', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001')
                                AND  o.laboratory='".$store_id."'
                            )
                            or (lens_code='L035' AND xsl.lab_id='".$store_id."')               
                            )
                            
                            ";

                    $query ="SELECT    coalesce(CASE
                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                            END,0) AS count,
                            os.payment_date,
                            os.po_number
                                                    

                            FROM `orders_sunnies_studios` os

                            LEFT JOIN orders_studios  o ON o.order_id=os.order_id
                            LEFT JOIN store_codes_studios xsl ON xsl.store_code =o.origin_branch
                            WHERE 
                            payment='y'
                            And os.status NOT IN ('return','cancelled','returned','failed' )
                            AND os.product_code='".$product_code."'
                            AND date(os.payment_date)>='2020-02-4'
                            AND  date(os.payment_date)>='".$date_start."'
                            AND  date(os.payment_date)<='".$date_end."'
                            
                            ".$lensFilter."

                             ".$filterVirtual." 
                             GROUP BY os.payment_date,orders_specs_id 
                            
                    ";

           }

                    $grabParams=array("sales_count", "payment_date", "po_number");

                   
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrPDque[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);	

        };

        return $arrPDque;
}





function  perdayWarehouseSales($store_id,$product_code,$date_start,$date_end){

    global $conn;
    $arrPDque=array();
   
    $arrPDque=0;

    return $arrPDque;


}





################## inventory tables

function StockTransferPlus($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;


    $arrStockIn=array();

    $query="select coalesce(
        sum(
            if(iisi.variance_status='approve',
            REPLACE(iisi.actual_count,',',''),
            REPLACE( iisi.`count`,',','')
            )
         ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),stock_from,reference_number 
    
    from inventory_studios  iisi
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
        AND
        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$date_start."'
        AND
        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
        GROUP BY iisi.status_date,stock_from
        ";



        $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,$result4);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrStockIn[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);	

        };


        return $arrStockIn;


}


function StockTransferMinus($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;


    $arrStockIn=array();

    $query="select coalesce(
        sum(
            if(iisi.variance_status='approve',
            REPLACE(iisi.actual_count,',',''),
            REPLACE( iisi.`count`,',','')
            )
         ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id ,reference_number
    
    from inventory_studios  iisi
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
        AND
        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='".$date_start."'
        AND
        DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='".$date_end."'
        GROUP BY iisi.status_date,stock_from
        ";



        $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,$result4);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrStockIn[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);	

        };


        return $arrStockIn;


}

function InterBranchPlus($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;


    $arrinterBranch=array();

     $query="select coalesce(
            sum(
                if(iinbic.variance_status='approve',
                REPLACE(iinbic.actual_count,',',''),
                REPLACE( iinbic.`count`,',',''))
                  ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),stock_from ,reference_number
                   from inventory_studios iinbic
              WHERE
              iinbic.product_code ='".$product_code."'
                AND
                iinbic.store_id='".$store_id."'
          
          AND 
          iinbic.status ='received' 
          AND iinbic.type='interbranch'
        
          AND
          DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$date_start."'
            AND
            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
            
            GROUP BY iinbic.status_date,store_id";




            $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {
    
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,$result4);
    
                while (mysqli_stmt_fetch($stmt)) {
    
                    $tempArray = array();
    
                    for ($i=0; $i < sizeOf($grabParams); $i++) { 
    
                        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
    
                    };
    
                    $arrinterBranch[] = $tempArray;
    
                };
    
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
    
                echo mysqli_error($conn);	
    
            };
    
    
            return $arrinterBranch;



}





function InterBranchMinus($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;


    $arrinterBranch=array();

       $query="select coalesce(
            sum(
                if(iinbic.variance_status='approve',
                REPLACE(iinbic.actual_count,',',''),
                REPLACE( iinbic.`count`,',',''))
                  ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id ,reference_number
                   from inventory_studios iinbic
              WHERE
              iinbic.product_code ='".$product_code."'
                AND
                iinbic.stock_from='".$store_id."'
          
          AND 
          iinbic.status ='received' 
          AND iinbic.type='interbranch'
        
          AND
          DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))>='".$date_start."'
            AND
            DATE(DATE_ADD(iinbic.status_date, INTERVAL 13 HOUR))<='".$date_end."'
            
            GROUP BY iinbic.status_date,store_id";




            $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {
    
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,$result4);
    
                while (mysqli_stmt_fetch($stmt)) {
    
                    $tempArray = array();
    
                    for ($i=0; $i < sizeOf($grabParams); $i++) { 
    
                        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
    
                    };
    
                    $arrinterBranch[] = $tempArray;
    
                };
    
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
    
                echo mysqli_error($conn);	
    
            };
    
    
            return $arrinterBranch;



}


function pullout($store_id,$product_code,$date_start,$date_end,$branch_type){
    global $conn;

    $arrPullout=array();

    $query="select coalesce(
        sum(
            if(ipc.variance_status='approve',
            REPLACE(ipc.actual_count,',',''),
            REPLACE( ipc.`count`,',',''))
             ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),Concat(store_id,'-',reason) as store ,reference_number
              from inventory_studios ipc
            WHERE
            ipc.product_code ='".$product_code."'
              AND
              ipc.stock_from='".$store_id."'
        
        AND 
        ipc.status ='received' 
        AND ipc.type='pullout'
        AND
        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
        AND
        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
        GROUP BY ipc.status_date,store_id 
        ";

    $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {
    
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,$result4);
    
                while (mysqli_stmt_fetch($stmt)) {
    
                    $tempArray = array();
    
                    for ($i=0; $i < sizeOf($grabParams); $i++) { 
    
                        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
    
                    };
    
                    $arrPullout[] = $tempArray;
    
                };
    
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
    
                echo mysqli_error($conn);	
    
            };
    
    
            return $arrPullout;

}



function Damage($store_id,$product_code,$date_start,$date_end,$branch_type){
    global $conn;

    $arrPullout=array();

    $query="select coalesce(
        sum(
            if(ipc.variance_status='approve',
            REPLACE(ipc.actual_count,',',''),
            REPLACE( ipc.`count`,',',''))
             ),0),
             DATE_ADD(status_date,INTERVAL 12 HOUR),
             store_id,
             reference_number
              from inventory_studios ipc
            WHERE
            ipc.product_code ='".$product_code."'
              AND
              ipc.stock_from='".$store_id."'
        
        AND 
        ipc.status ='received' 
        AND ipc.type='damage'
        AND
        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))>='".$date_start."'
        AND
        DATE(DATE_ADD(ipc.status_date, INTERVAL 13 HOUR))<='".$date_end."'
        GROUP BY ipc.status_date,store_id ";

    $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {
    
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);
    
                while (mysqli_stmt_fetch($stmt)) {
    
                    $tempArray = array();
    
                    for ($i=0; $i < sizeOf($grabParams); $i++) { 
    
                        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
    
                    };
    
                    $arrPullout[] = $tempArray;
    
                };
    
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
    
                echo mysqli_error($conn);	
    
            };
    
    
            return $arrPullout;

}


function inTransitIn($store_id,$product_code,$date_start,$date_end,$branch_type){
    global $conn;
    $arrTransit=array();


 $query="SELECT coalesce(sum(REPLACE(count,',','')),0),DATE_ADD(status_date,INTERVAL 12 HOUR),stock_from,reference_number from inventory_studios ito
    WHERE
        ito.product_code ='".$product_code."'
                        AND
                        ito.store_id='".$store_id."'
            
            AND 
            ito.status ='in transit' 
            
        
           
            AND
            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
            AND
            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."' 
        GROUP BY ito.status_date,stock_from
        ";


    $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {
    
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,$result4);
    
                while (mysqli_stmt_fetch($stmt)) {
    
                    $tempArray = array();
    
                    for ($i=0; $i < sizeOf($grabParams); $i++) { 
    
                        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
    
                    };
    
                    $arrTransit[] = $tempArray;
    
                };
    
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
    
                echo mysqli_error($conn);	
    
            };
    
    
            return $arrTransit;
}

function inTransitOut($store_id,$product_code,$date_start,$date_end,$branch_type){
    global $conn;
    global $conn;
    $arrTransit=array();



     $query="SELECT coalesce(sum(REPLACE(count,',','')),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id,reference_number
     from inventory_studios ito
    WHERE
        ito.product_code ='".$product_code."'
                        AND
                        ito.stock_from='".$store_id."'
            
            AND 
            ito.status ='in transit' 
            
        
            
            AND
            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))>='".$date_start."'
            AND
            DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."' 
        GROUP BY ito.status_date,store_id
        ";

    $grabParams=array("sales_count", "payment_date","stock_from","reference_number");

                   
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {
    
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1, $result2, $result3,$result4);
    
                while (mysqli_stmt_fetch($stmt)) {
    
                    $tempArray = array();
    
                    for ($i=0; $i < sizeOf($grabParams); $i++) { 
    
                        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
    
                    };
    
                    $arrTransit[] = $tempArray;
    
                };
    
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
    
                echo mysqli_error($conn);	
    
            };
    
    
            return $arrTransit;
}
?>