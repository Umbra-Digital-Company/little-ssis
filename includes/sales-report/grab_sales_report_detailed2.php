<?php

///////////////////////////////////////////////// DATE SETTINGS
// print_r($_GET);

if(isset($_GET['date'])){

	if($_GET['date']=='month'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}
	elseif($_GET['date']=='yesterday'){

	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-t');

	}
    elseif($_GET['date']=='week'){

		$dateStart = date( 'Y-m-d', strtotime( 'monday this week' ) );
		$dateEnd = date( 'Y-m-d', strtotime( 'sunday next week' ) );

	}
	elseif($_GET['date']=='custom'){

		$dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		$dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];

	}
	elseif($_GET['date']=='all-time'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}

}
else{

	$dateStart = date('Y-m').'-1';
	$dateEnd= date('Y-m-t');

};

///////////////////////////////////////////////// GRAB STUDIOS ORDERS

$arrStudios = array();

$grabParamsstudios = array(    
    "payment_date",
    "po_number_studios",
    "item_name",
    "product_code",    
    "status",
    "price",
    "order_id"
);

$queryStudios = "SELECT 
                    os2.payment_date,
                    os2.po_number,
                    ps.item_name,
                    ps.product_code,
                    os2.status,
                    os2.price,
                    os2.order_id 
                FROM 
                    `orders_specs` os2 
                        LEFT JOIN poll_51_studios ps 
                            ON ps.product_code=os2.product_code 
                WHERE                 
                    os2.`product_upgrade` LIKE 'sunnies_studios'                  
                        AND DATE(os2.payment_date)>='".$dateStart."'
                        AND DATE(os2.payment_date)<='".$dateEnd."'
                        AND os2.order_id LIKE'".$_GET['filterStores']."-%'
                ORDER BY 
                    `payment_date` DESC";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryStudios)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParamsstudios); $i++) { 

            $tempArray[$grabParamsstudios[$i]] = ${'result' . ($i+1)};

        };

        $arrStudios[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

///////////////////////////////////////////////// GRAB SPECS ORDERS

$arrSpecs = array();

$grabParamsSpecs = array(
    "payment_date",
    "po_number_specs",
    "item_name",
    "item_name_merch",
    "product_code",
    "product_upgrade",
    "lens_code",
    "prescription_vision",
    "status",
    "price",
    "order_id",
    "paymen_price"
);


// AND (
//     os2.lens_option='without prescription'
//     OR
//     os2.lens_code='SO1001')
//     AND os2.product_code!='M100'

// AND os2.order_id LIKE '".$_SESSION['store_code']."-%'

$storelength=strlen($_GET['filterStores']);
if($storelength>'5'){
  $queryFilt="  AND os2.lens_option='with prescription' 
                AND os2.lens_code!='SO1001'
                AND  o.laboratory= '".$_GET['filterStores']."'  
                ";


}else{
 $queryFilt=" AND (
         os2.lens_option='without prescription'
        OR
     os2.lens_code='SO1001')
         AND os2.product_code!='M100'
    
    AND o.origin_branch = '".$_GET['filterStores']."'
    
     ";

}


 $querySpecs =   "SELECT 
                    os2.payment_date,
                    os2.po_number,
                    ps.item_name,
                    ps2.item_name,
                    ps.product_code,
                    os2.product_upgrade,
                    os2.lens_code,
                    os2.prescription_vision,
                    os2.status,
                    os2.price,
                    os2.order_id,
                    pay.total 
                FROM 
                    `orders_specs` os2 
                        LEFT JOIN poll_51 ps 
                            ON ps.product_code=os2.product_code 
                        LEFT JOIN poll_51 ps2 
                            ON ps2.product_code=os2.product_upgrade 
                        LEFT JOIN payments
                            pay ON pay.po_number=os2.po_number
                        LEFT JOIN orders o 
                                ON o.order_id=os2.order_id

                WHERE 
                    os2.`product_upgrade` NOT LIKE 'sunnies_studios'      
                        AND DATE(os2.payment_date)>='".$dateStart."'
                        AND DATE(os2.payment_date)<='".$dateEnd."'
                         ".$queryFilt." 
                     
                          
                ORDER BY 
                pay.total ,`payment_date` DESC";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querySpecs)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParamsSpecs); $i++) { 

            $tempArray[$grabParamsSpecs[$i]] = ${'result' . ($i+1)};

        };

        $arrSpecs   [] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

?>