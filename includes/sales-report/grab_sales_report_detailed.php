<?php

///////////////////////////////////////////////// DATE SETTINGS

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

	}elseif($_GET['date']=='day'){

		$dateStart = date('Y-m-d');
		$dateEnd= date('Y-m-d');

	}

}
else{

	$dateStart = date('Y-m').'-1';
	$dateEnd= date('Y-m-t');

};

///////////////////////////////////////////////// GRAB STUDIOS ORDERS







if(isset($_GET['filterStores']) && $_SESSION["store_code"]=='vn-admin'){
    $storefilter=" AND origin_branch IN (".implode(',', $_GET['filterStores']).")";
}elseif($_SESSION["store_code"]=='vn-admin'){
    $storefilter=" AND origin_branch IN (142,150,155)";
}else{
        $storefilter=" AND origin_branch ='".$_SESSION["store_code"]."' ";
    }





$arrStudios = array();

$grabParamsstudios = array(    
    "payment_date",
    "po_number_studios",
    "item_name",
    "product_code",    
    "status",
    "price",
    "item_name_merch",
    "product_code_merch",
    "product_upgrade"
);

 $queryStudios = "SELECT 
                    os2.payment_date,
                    os2.po_number,
                    ps.item_name,
                    os2.product_code,
                    os2.status,
                    os2.price,
                    ps2.item_name,
                    ps2.product_code,
                    os2.product_upgrade 
                FROM 
                    `orders_specs` os2 
                        LEFT JOIN poll_51_studios ps 
                            ON ps.product_code=os2.product_code 
                        LEFT JOIN poll_51_studios ps2 
                            ON  ps2.product_code=os2.product_upgrade
                        LEFT JOIN orders o2
                                On o2.order_id=os2.order_id

                WHERE                 
                (os2.`product_upgrade` LIKE 'sunnies_studios' OR os2.product_upgrade=ps2.product_code)         
                        AND DATE(os2.payment_date)>='".$dateStart."'
                        AND DATE(os2.payment_date)<='".$dateEnd."'
                        ".$storefilter." 
                ORDER BY 
                    `payment_date` DESC";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryStudios)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

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
    "price"
);

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
                    os2.price 
                FROM 
                      poll_51_new ps 
                        LEFT JOIN `orders_specs` os2
                            ON ps.product_code=os2.product_code and os2.product_code!='M100'
                        LEFT JOIN poll_51_new ps2 
                            ON ps2.product_code=os2.product_upgrade 
                            LEFT JOIN orders o2
                                On o2.order_id=os2.order_id
                WHERE 
                    os2.`product_upgrade` NOT LIKE 'sunnies_studios'      
                        AND DATE(os2.payment_date)>='".$dateStart."'
                        AND DATE(os2.payment_date)<='".$dateEnd."'
                        ".$storefilter." 
                ORDER BY 
                    `payment_date` DESC";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querySpecs)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

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