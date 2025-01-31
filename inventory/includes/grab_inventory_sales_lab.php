<?php 

if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$dateStartpdsales = date('Y-m').'-1';
		$dateEndpdsales= date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
	 	$dateStartpdsales = date('Y-m-d',strtotime("-1 days"));
	 	$dateEndpdsales= date('Y-m-d',strtotime("-1 days"));
	}elseif($_GET['date']=='week'){
		$dateStartpdsales = date( 'Y-m-d', strtotime( 'monday this week' ) );
		 $dateEndpdsales = date( 'Y-m-d', strtotime( 'sunday this week' ) );
	}
	elseif($_GET['date']=='custom'){
		 $dateStartpdsales = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		 $dateEndpdsales = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
	}
	elseif($_GET['date']=='all-time'){
		$dateStartpdsales = date('Y-m').'-1';
		$dateEndpdsales= date('Y-m-t');
	}
	else{
		$dateStartpdsales = date('Y-m-d');
			$dateEndpdsales= date('Y-m-t');
	}
}
else{
	$dateStartpdsales = date('Y-m-d');
		$dateEndpdsales= date('Y-m-t');
}




if($date_start<'2020-06-26'){
    $filterVirtual= " ";
}
else{
    $filterVirtual= " AND o.origin_branch!='787' ";

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

    $arrDailySales = array();

      $query="
                 SELECT    coalesce(CASE
                WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                WHEN o.origin_branch!='787' THEN count(os.po_number)
                END,0),
                if(
                    os.product_code='M100',
                    os.product_upgrade,
                    os.product_code
                ) as product_code_os,
               
                date(os.payment_date),
                o.origin_branch,
                o.store_id 
                                        

                FROM `orders_specs` os

                LEFT JOIN orders o ON o.order_id=os.order_id
                LEFT JOIN stores_locations xsl ON xsl.store_id =o.origin_branch
                WHERE 
                payment='y'
                And os.status NOT IN ('return','cancelled','returned','failed' )
               
                AND date(os.payment_date)>='2020-02-4'
                AND  date(os.payment_date)>='".$dateStartpdsales."'
                AND  date(os.payment_date)<='".$dateEndpdsales."'

                ".$lensFilter."



                ".$filterVirtual." 
                group by os.product_code
                ORDER BY product_code ";

                $grabParamsSale = array(
			
                    'sales',
                    'product_code',
                    'payment_date',
                    'origin_branch',
                    'store_id'
                
                );
                    
                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);
                
                    while (mysqli_stmt_fetch($stmt)) {
                
                        $tempArray = array();
                
                        for ($i=0; $i < sizeOf($grabParamsSale); $i++) { 
                
                            $tempArray[$grabParamsSale[$i]] = ${'result' . ($i+1)};
                
                        };
                
                        $arrDailySales [] = $tempArray;
                
                    };
                
                    mysqli_stmt_close($stmt);    
                                            
                }
                else {
                
                    echo mysqli_error($conn);
                
                };
                

                $arrDailySalespast = array();
              $query2="SELECT   coalesce(CASE
                    WHEN o.origin_branch='787'AND date(os.payment_date)>='2020-06-26' THEN '0'
                    WHEN o.origin_branch='787'AND date(os.payment_date)<'2020-06-26' THEN count(os.po_number)
                    WHEN o.origin_branch!='787' THEN count(os.po_number)
                    END,0),if(
								os.product_code='M100',
								os.product_upgrade,
								os.product_code
							) as product_code_os,
                           
                            date(os.payment_date),
							o.origin_branch,
							o.store_id 
                
                                
    
                                FROM `orders_specs` os
                    
                                LEFT JOIN orders o ON o.order_id=os.order_id
                                LEFT JOIN stores_locations xsl ON xsl.store_id =o.origin_branch
                            WHERE 
                            payment='y'
                            And os.status NOT IN ('return','cancelled','returned','failed' )
                               
                                AND date(os.payment_date)>='2020-02-4'
                                
                                AND  date(os.payment_date)<'".$dateStartpdsales."'
                                
                                ".$lensFilter." 

                                ".$filterVirtual." 

                                group by os.product_code
                                ORDER BY product_code
        ";
$grabParamsSale2 = array(
			
    'sales',
    'product_code',
	'payment_date',
	'origin_branch',
	'store_id'

);
	
$stmt2 = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt2, $query2)) {

	mysqli_stmt_execute($stmt2);
	mysqli_stmt_bind_result($stmt2, $result1, $result2, $result3, $result4, $result5);

	while (mysqli_stmt_fetch($stmt2)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParamsSale2); $i++) { 

			$tempArray[$grabParamsSale2[$i]] = ${'result' . ($i+1)};

		};

		$arrDailySalespast [] = $tempArray;

	};

	mysqli_stmt_close($stmt2);    
							
}
else {

	echo mysqli_error($conn);
}

?>