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


function perdayStoreSales($store_id,$product_code,$date_start,$date_end,$branch_type){

    global $conn;
           
           
           $arrPDque=array();
           if($branch_type=='store'){
                                                
                                                    // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
                                                    if(preg_match("/MC/i", $product_code) || preg_match("/MH/i", $product_code) || preg_match("/MG/i", $product_code)  || preg_match("/PL/i", $product_code) || preg_match("/60319/i", $product_code)
                                    || preg_match("/DMP/i", $product_code)  || preg_match("/HC/i", $product_code) || preg_match("/DD/i", $product_code)     || preg_match("/AFC/i", $product_code)    || preg_match("/P1/i",$product_code) 
                                    || preg_match("/MSCL/i",$product_code) 
                                    || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code) || preg_match("/SWB/i",$product_code)
                                
                                    ){                


                                                        $carekits=" AND os.product_upgrade ";

                                                    }else{

                                                    
                                                            $carekits=" AND os.product_code ";

                                                    }




                                                        if($store_id=='787' || $store_id=='788' ){
                                                            $reRoute=" AND  date(os.payment_date)>='2020-06-25'
                                                            and  os.product_upgrade!='sunnies_studios' 
                                                            ";
                                                            // AND os.product_upgrade!='PL0010'
                                                    }
                                                    elseif($store_id=='151'){
                                                        $reRoute=" AND  os.lens_code!='L035' AND  os.lens_code!='ML0035'
                                                        AND (        
                                                                            os.lens_option='without prescription'
                                                                                OR
                                                                                os.lens_code IN (
                                                                                    'L001','L002','L045', 'L014', 'L015', 'L016', 'L017', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001',
                                                                                    'L049', 'L050', 'L051', 'L052', 'L053','SO001' ,'SO002','SO003',
                                                                                    'ML0001','ML0002','ML0045', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML024', 'ML0029', 'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001',
                                                                                    'ML0049', 'ML0050', 'ML0051', 'ML0052', 'ML0053','SO001' ,'SO002','SO003'  , 'ML0013'                                                                                
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
                                                        AND  os.lens_code!='L035' AND  os.lens_code!='ML0035'
                                                        AND (  
                                                                            os.lens_option='without prescription'
                                                                                OR
                                                                                os.lens_code IN ('L013', 'L014', 'L015', 'L016', 'L017', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 
                                                                                'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053','SO001' ,'SO002','SO003' ,
                                                                                'ML0013', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML0024', 'ML0029', 
                                                                                'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001', 'ML0049', 'ML0050', 'ML0051', 'ML0052', 'ML0053','SO001' ,'SO002','SO003'
                                                                                )
                                                                                OR product_upgrade='special_order'
                                                                    ) 
                                                                    and  os.product_code!='F100' 
                                                                    and  os.product_code!='S100' 
                                                                    and  os.product_upgrade!='sunnies_studios' 
                                                            ";
                                                    }



                                    $query ="SELECT if( os.packaging_for!='' && packaging_stock='lab',
                                                        '0',
                                                        
                                                    count(po_number)),
                                                        os.payment_date,
                                                        o.origin_branch,
                                                        os.po_number
                                                                                                    
                                                                                            
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
                                                GROUP BY os.payment_date,orders_specs_id ";

           }elseif($branch_type=='lab'){

                                            if($date_start<'2020-06-26'){
                                                $filterVirtual= " ";
                                            }
                                            else{
                                                $filterVirtual= " AND o.origin_branch!='787' AND o.origin_branch!='788' ";

                                            }

                                            if($store_id=='6981YR1OBFVPK2V40HY8'){
                                                $kidsfilter ="   (    (    (lens_code='L035' OR lens_code='ML0035' )  AND xsl.lab_id='".$store_id."'  and o.store_id!='102' )
                                                
                                                                        )           ";
                                        
                                            }elseif($store_id=='0075YR1OBABCD2V40335'){
                                        
                                                $kidsfilter ="   (   (lens_code='L035' OR lens_code='ML0035' )  AND     o.store_id='102')   ";
                                            }
                                            
                                            else{
                                                $kidsfilter ="   (   (lens_code='L035' OR lens_code='ML0035' )  AND xsl.lab_id='".$store_id."')    ";
                                            }

                                            if(preg_match("/MC/i",$product_code) || preg_match("/MH/i",$product_code) || preg_match("/MG/i",$product_code)  
                                            || preg_match("/PL/i", $product_code) || preg_match("/60319/i",$product_code)
                                                                || preg_match("/DMP/i",$product_code)  || preg_match("/HC/i",$product_code)  || preg_match("/DD/i",$product_code) || preg_match("/P1/i",$product_code) 
                                                                || preg_match("/MSCL/i",$product_code)  || preg_match("/SDB/i",$product_code)  || preg_match("/SSWP/i",$product_code)    || preg_match("/SWB/i",$product_code)   ){
                                        
                                                                    $packaging_for= " (packaging_for!='' AND packaging_stock='lab' AND o.laboratory='".$store_id."' ) OR ";
                                                                
                                                                    
                                                    $carekits=" AND os.product_upgrade ";
                                        
                                            }else{
                                                $packaging_for= " ";
                                                $carekits=" AND os.product_code ";
                                        
                                            }
                                        
                                            $KCC= "";

                                            if($store_id=='6981YR1OBFVPK2V40HY8'){
                                                // $KCC= " AND ( 
                                                //             o.origin_branch='151' AND os.lens_code NOT IN ('L001','L002','L045','L013', 'L014', 'L015', 'L016', 'L018', 
                                                //         'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053') 
                                                // ) ";
                                                $KCC="   AND   '1'= CASE WHEN o.store_id='151' AND os.lens_code  NOT IN ('L001','L002','L045','L013', 'L014', 'L015', 'L017', 'L016', 'L018', 
                                                'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032', 'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053','SO001' ,'SO002','SO003',
                                                             'ML0001','ML0002','ML0045', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML024', 'ML0029', 'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001',
                                                                                    'ML0049', 'ML0050', 'ML0051', 'ML0052', 'ML0053'   , 'ML0013'    
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
                                                AND ( ".$packaging_for."
                                                            (
                                                                (os.lens_option='with prescription' )
                                                                    AND
                                                                    os.lens_code NOT IN ('L013', 'L014', 'L015', 'L016', 'L017', 'L018', 'L020', 'L021', 'L022', 'L023', 'L024', 'L029', 'L030', 'L031', 'L032',
                                                                     'L033','SO1001', 'L049', 'L050', 'L051', 'L052', 'L053','SO001' ,'SO002','SO003',
                                                                    'ML0013', 'ML0014', 'ML0015', 'ML0016', 'ML0018', 'ML0020', 'ML0021', 'ML0022', 'ML0023', 'ML0024', 'ML0029', 
                                                                    'ML0030', 'ML0031', 'ML0032', 'ML0033','SO1001', 'ML0049', 'ML0050', 'ML0051', 'ML0052' )

                                                                
                                                                AND  o.laboratory='".$store_id."'
                                                               
                                                                ".$KCC." 
                                                            )


                                                    or ".$kidsfilter."         
                                                    )
                                                    
                                                ";
                                                    $query ="SELECT    coalesce(CASE
                                                            WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                                                            WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                                                            WHEN o.origin_branch!='787' THEN count(os.po_number)
                                                            END,0) AS count,
                                                            os.payment_date,
                                                            o.origin_branch,
                                                            os.po_number
                                                                                    

                                                            FROM `orders_specs` os

                                                            LEFT JOIN orders o ON o.order_id=os.order_id
                                                            LEFT JOIN stores_locations xsl ON xsl.store_id =o.store_id
                                                            WHERE 
                                                            payment='y'
                                                            And os.status NOT IN ('return','cancelled','returned','failed' )
                                                            and coalesce(packaging_stock,'')!='store'
                                                            ". $carekits."  ='".$product_code."'
                                                            AND date(os.payment_date)>='2020-02-4'
                                                            AND  date(os.payment_date)>='".$date_start."'
                                                            AND  date(os.payment_date)<='".$date_end."'
                                                            AND  os.product_upgrade!='special_order'
                                                            AND os.lens_code NOT LIKE 'EL%'

                                                            ".$lensFilter."

                                                            ".$filterVirtual." 
                                                            GROUP BY os.payment_date,orders_specs_id
                                                            
                                                    ";

                        }


        $grabParams=array("sales_count", "payment_date", "branch",'po_number');

                
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {

            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

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
         ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),stock_from,iisi.reference_number 
    
    FROM inventory  iisi
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
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

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
         ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id,iisi.reference_number 
    
    FROM inventory  iisi
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
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

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
                  ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),stock_from,reference_number 
                   FROM inventory iinbic
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
                  ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id,reference_number 
                   FROM inventory iinbic
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
             ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id,reference_number 
              FROM inventory ipc
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



function Damage($store_id,$product_code,$date_start,$date_end,$branch_type){
    global $conn;

    $arrPullout=array();

    $query="select coalesce(
        sum(
            if(ipc.variance_status='approve',
            REPLACE(ipc.actual_count,',',''),
            REPLACE( ipc.`count`,',',''))
             ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id,reference_number
              FROM inventory ipc
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



    $query="SELECT coalesce(sum(REPLACE(count,',','')),0),DATE_ADD(status_date,INTERVAL 12 HOUR),stock_from,ito.reference_number FROM inventory ito
    WHERE
        ito.product_code ='".$product_code."'
                        AND
                        ito.store_id='".$store_id."'
            
            AND 
            ito.status ='in transit' 
            
        
           
        AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
        GROUP BY ito.status_date,stock_from
        ";


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



    $query="SELECT coalesce(sum(REPLACE(count,',','')),0),DATE_ADD(status_date,INTERVAL 12 HOUR),store_id,ito.reference_number FROM inventory ito
    WHERE
        ito.product_code ='".$product_code."'
                        AND
                        ito.stock_from='".$store_id."'
            
            AND 
            ito.status ='in transit' 
            
        
            
        AND    DATE(DATE_ADD(ito.status_date, INTERVAL 13 HOUR))<='".$date_end."'
        GROUP BY ito.status_date,store_id
        ";

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
    
                    $arrTransit[] = $tempArray;
    
                };
    
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
    
                echo mysqli_error($conn);	
    
            };
    
    
            return $arrTransit;
}



function DamageIN($store_id,$product_code,$date_start,$date_end,$branch_type){
    global $conn;

    $arrPullout=array();

    $query="select coalesce(
        sum(
            if(ipc.variance_status='approve',
            REPLACE(ipc.actual_count,',',''),
            REPLACE( ipc.`count`,',',''))
             ),0),DATE_ADD(status_date,INTERVAL 12 HOUR),stock_from,reference_number
              FROM inventory ipc
            WHERE
            ipc.product_code ='".$product_code."'
              AND
              ipc.store_id='".$store_id."'
        
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
?>