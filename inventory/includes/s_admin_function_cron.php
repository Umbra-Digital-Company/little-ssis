<?php
set_time_limit(0);
ini_set('memory_limit', '3G');

if (isset($_GET['date'])) {
    if ($_GET['date'] == 'month') {
        $dateStart = date('Y-m') . '-1';
        $dateEnd = date('Y-m-t');
    } elseif ($_GET['date'] == 'yesterday') {
        $dateStart = date('Y-m-d', strtotime("-1 days"));
        $dateEnd = date('Y-m-d', strtotime("-1 days"));
    } elseif ($_GET['date'] == 'week') {
        $dateStart = date('Y-m-d', strtotime('monday this week'));
        $dateEnd = date('Y-m-d', strtotime('sunday this week'));
    } elseif ($_GET['date'] == 'custom') {
        $dateStart = $_GET['data_range_start_year'] . "-" . $_GET['data_range_start_month'] . "-" . $_GET['data_range_start_day'];
        $dateEnd = $_GET['data_range_end_year'] . "-" . $_GET['data_range_end_month'] . "-" . $_GET['data_range_end_day'];
    } elseif ($_GET['date'] == 'all-time') {
        $dateStart = date('Y-m') . '-1';
        $dateEnd = date('Y-m-t');
    } else {
        $dateStart = date('Y-m-d');
        $dateEnd = date('Y-m-t');
    }

} else {
    $dateStart = date('Y-m-d');
    $dateEnd = date('Y-m-t');
}

// if(isset($_GET['filterStores'])){

// 	$store_id=$_GET['filterStores'];

// }else{
// 	if($_SESSION['user_login']['userlvl'] == '13' || $_SESSION['user_login']['userlvl'] == '15'|| $_SESSION['user_login']['userlvl'] == '1'){
// 			$store_id='warehouse';

// 	}else{
// 			$store_id=$_SESSION['store_code'];
// 	}
// }



function WarehouseChecker_smr($product_code, $date_start, $date_end)
{

    global $conn;
    $datenow = date('Y-m-d');

    $grabInvParams = array(
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







    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = 'none';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';

    $arrBegINV = array();
    $grabBegINVparams = array(
        "store_name",
        "item_name",
        "product_code",
        "beg_inventory",
        "date_end"
    );

    $query = " SELECT 

                'Warehouse',
               p51.item_name,
               p51.product_code,
               coalesce(pactual.input_count,0) as beginventory,
               pactual.date_end
               FROM    poll_51_studios_new p51
               LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited='warehouse' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                WHERE iacx.store_audited='warehouse'
                                AND iacx.product_code='" . $product_code . "'
                            AND iacx.date_end<'" . $date_start . "' 
                        ) AND pactual.product_code='" . $product_code . "'
            WHERE p51.product_code='" . $product_code . "'
             group by p51.product_code";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabBegINVparams); $i++) {

                $tempArray[$grabBegINVparams[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrBegINV[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;


    ################################################

    $arrIn = array();
    $grabIn = array(
        "In",
        'type'
    );
    $query = " select coalesce(
                   sum(
                   if(iisi.variance_status='approve',
                   REPLACE(iisi.actual_count,',',''),
                   REPLACE( iisi.`count`,',','')
                   )
                   ),0),type FROM inventory_studios  iisi
                   WHERE
                   iisi.product_code ='" . $product_code . "'
                   AND
                   iisi.store_id ='warehouse'
                   AND
                   iisi.status='received'
                   
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='" . $date_end . "'
                   group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabIn); $i++) {

                $tempArray[$grabIn[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrIn[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ################
    ##################### stock transfer out 
    $arrOut = array();
    $grabOut = array(
        "Out",
        "type"
    );
    $query = " select coalesce(
                sum(
                    if(iisoc.variance_status='approve',
                    REPLACE(iisoc.actual_count,',',''),
                    REPLACE( iisoc.`count`,',','')
                    )
            ),0),type FROM inventory_studios  iisoc
                    WHERE
                    iisoc.product_code ='" . $product_code . "'
                    AND
                    iisoc.stock_from ='warehouse'
                
                AND 
                iisoc.status ='received' 
            
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
                group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabOut); $i++) {

                $tempArray[$grabOut[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrOut[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ###############
    #############transit
    $arrTransit = array();
    $grabTransit = array(
        "transit_out",
        "transit_in"
    );
    $query = " select 
                        if( iisoc.stock_from  ='warehouse' ,
                    
                    coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                    REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_out, 

                    if( iisoc.store_id  ='warehouse' ,
                    coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                    REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_in
                    
                    FROM inventory_studios  iisoc
                    WHERE
                    iisoc.product_code ='" . $product_code . "'
                    AND
                ( iisoc.stock_from  ='warehouse'
                        OR
                        iisoc.store_id  ='warehouse'
                        )
                
                AND 
                iisoc.status ='in transit' 
            
                
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
                group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabTransit); $i++) {

                $tempArray[$grabTransit[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrTransit[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;


    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = '0';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';


    $arrInvLook[0]["store_name"] = $arrBegINV[0]["store_name"];
    $arrInvLook[0]["item_name"] = $arrBegINV[0]["item_name"];
    $arrInvLook[0]["product_code"] = $arrBegINV[0]["product_code"];
    $arrInvLook[0]["beg_inventory"] = $arrBegINV[0]["beg_inventory"];
    $arrInvLook[0]["sales"] = '0';

    for ($i = 0; $i < sizeof($arrIn); $i++) {
        if ($arrIn[$i]['type'] == 'stock_transfer' || $arrIn[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_in_c"] += $arrIn[$i]["In"];
        }
        if ($arrIn[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_in_c"] += $arrIn[$i]["In"];
        }

        if ($arrIn[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_i"] += $arrIn[$i]["In"];
        }

    }


    for ($i = 0; $i < sizeof($arrOut); $i++) {

        if ($arrOut[$i]['type'] == 'stock_transfer' || $arrOut[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'pullout') {
            $arrInvLook[0]["pullout_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_c"] += $arrOut[$i]["Out"];
        }

        // $arrInvLook[0]["stock_transfer_out_c"] =  $arrOut[0]["stock_transfer_out_c"];

    }

    for ($i = 0; $i < sizeof($arrTransit); $i++) {
        $arrInvLook[0]["transit_in"] += $arrTransit[$i]["transit_in"];
        $arrInvLook[0]["transit_out_c"] += $arrTransit[$i]["transit_out"];
        $arrInvLook[0]["transit_out"] += $arrTransit[$i]["transit_out"];
    }

    // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

    // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
    //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
    //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
    // echo "<pre>";
    // print_r($arrInvLook);
    // echo "</pre>";
    return $arrInvLook;

}


function WarehouseChecker_smr_damage($product_code, $date_start, $date_end)
{

    global $conn;
    $datenow = date('Y-m-d');

    $grabInvParams = array(
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







    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = 'none';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';

    $arrBegINV = array();
    $grabBegINVparams = array(
        "store_name",
        "item_name",
        "product_code",
        "beg_inventory",
        "date_start"
    );

    $query = " SELECT 

                'Warehouse Damage',
               p51.item_name,
               p51.product_code,
               coalesce(pactual.input_count,0) as beginventory,
               pactual.date_end
               FROM    poll_51_studios_new p51
               LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited='warehouse_damage' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                WHERE iacx.store_audited='warehouse_damage'
                                AND iacx.product_code='" . $product_code . "'
                            AND iacx.date_end<'" . $date_start . "' 
                        ) AND pactual.product_code='" . $product_code . "'
            WHERE p51.product_code='" . $product_code . "'
             group by p51.product_code";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabBegINVparams); $i++) {

                $tempArray[$grabBegINVparams[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrBegINV[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;


    ################################################

    $arrIn = array();
    $grabIn = array(
        "In",
        'type'
    );
    $query = " select coalesce(
                   sum(
                   if(iisi.variance_status='approve',
                   REPLACE(iisi.actual_count,',',''),
                   REPLACE( iisi.`count`,',','')
                   )
                   ),0),type FROM inventory_studios  iisi
                   WHERE
                   iisi.product_code ='" . $product_code . "'
                   AND
                   iisi.store_id ='warehouse_damage'
                   AND
                   iisi.status='received'
                   
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='" . $date_end . "'
                   group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabIn); $i++) {

                $tempArray[$grabIn[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrIn[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ################
    ##################### stock transfer out 
    $arrOut = array();
    $grabOut = array(
        "Out",
        "type"
    );
    $query = " select coalesce(
                sum(
                    if(iisoc.variance_status='approve',
                    REPLACE(iisoc.actual_count,',',''),
                    REPLACE( iisoc.`count`,',','')
                    )
            ),0),type FROM inventory_studios  iisoc
                    WHERE
                    iisoc.product_code ='" . $product_code . "'
                    AND
                    iisoc.stock_from ='warehouse_damage'
                
                AND 
                iisoc.status ='received' 
            
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
                group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabOut); $i++) {

                $tempArray[$grabOut[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrOut[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ###############
    #############transit
    $arrTransit = array();
    $grabTransit = array(
        "transit_out",
        "transit_in"
    );
    $query = " select 
                        if( iisoc.stock_from  ='warehouse_damage' ,
                    
                    coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                    REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_out, 

                    if( iisoc.store_id  ='warehouse_damage' ,
                    coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                    REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_in
                    
                    FROM inventory_studios  iisoc
                    WHERE
                    iisoc.product_code ='" . $product_code . "'
                    AND
                ( iisoc.stock_from  ='warehouse_damage'
                        OR
                        iisoc.store_id  ='warehouse_damage'
                        )
                
                AND 
                iisoc.status ='in transit' 
            
                
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
                group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabTransit); $i++) {

                $tempArray[$grabTransit[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrTransit[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;


    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = '0';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';


    $arrInvLook[0]["store_name"] = $arrBegINV[0]["store_name"];
    $arrInvLook[0]["item_name"] = $arrBegINV[0]["item_name"];
    $arrInvLook[0]["product_code"] = $arrBegINV[0]["product_code"];
    $arrInvLook[0]["beg_inventory"] = $arrBegINV[0]["beg_inventory"];
    $arrInvLook[0]["sales"] = '0';

    for ($i = 0; $i < sizeof($arrIn); $i++) {
        if ($arrIn[$i]['type'] == 'stock_transfer' || $arrIn[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_in_c"] += $arrIn[$i]["In"];
        }
        if ($arrIn[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_in_c"] += $arrIn[$i]["In"];
        }

        if ($arrIn[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_i"] += $arrIn[$i]["In"];
        }

    }


    for ($i = 0; $i < sizeof($arrOut); $i++) {

        if ($arrOut[$i]['type'] == 'stock_transfer' || $arrOut[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'pullout') {
            $arrInvLook[0]["pullout_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_c"] += $arrOut[$i]["Out"];
        }

        // $arrInvLook[0]["stock_transfer_out_c"] =  $arrOut[0]["stock_transfer_out_c"];

    }

    for ($i = 0; $i < sizeof($arrTransit); $i++) {
        $arrInvLook[0]["transit_in"] += $arrTransit[$i]["transit_in"];
        $arrInvLook[0]["transit_out_c"] += $arrTransit[$i]["transit_out"];
        $arrInvLook[0]["transit_out"] += $arrTransit[$i]["transit_out"];
    }

    // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

    // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
    //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
    //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
    // echo "<pre>";
    // print_r($arrInvLook);
    // echo "</pre>";
    return $arrInvLook;

}




function WarehouseChecker_smr_qa($product_code, $date_start, $date_end)
{

    global $conn;
    $datenow = date('Y-m-d');

    $grabInvParams = array(
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







    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = 'none';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';

    $arrBegINV = array();
    $grabBegINVparams = array(
        "store_name",
        "item_name",
        "product_code",
        "beg_inventory",
        "date_end"
    );

    $query = " SELECT 
    
                'Warehouse QA',
               p51.item_name,
               p51.product_code,
               coalesce(pactual.input_count,0) as beginventory,
               pactual.date_end
               FROM    poll_51_studios_new p51
               LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited='warehouse_qa' AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                WHERE iacx.store_audited='warehouse_qa'
                                AND iacx.product_code='" . $product_code . "'
                            AND iacx.date_end<'" . $date_start . "' 
                        ) AND pactual.product_code='" . $product_code . "'
            WHERE p51.product_code='" . $product_code . "'
             group by p51.product_code";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabBegINVparams); $i++) {

                $tempArray[$grabBegINVparams[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrBegINV[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;


    ################################################

    $arrIn = array();
    $grabIn = array(
        "In",
        'type'
    );
    $query = " select coalesce(
                   sum(
                   if(iisi.variance_status='approve',
                   REPLACE(iisi.actual_count,',',''),
                   REPLACE( iisi.`count`,',','')
                   )
                   ),0),type FROM inventory_studios  iisi
                   WHERE
                   iisi.product_code ='" . $product_code . "'
                   AND
                   iisi.store_id ='warehouse_qa'
                   AND
                   iisi.status='received'
                   
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='" . $date_end . "'
                   group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabIn); $i++) {

                $tempArray[$grabIn[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrIn[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ################
    ##################### stock transfer out 
    $arrOut = array();
    $grabOut = array(
        "Out",
        "type"
    );
    $query = " select coalesce(
                sum(
                    if(iisoc.variance_status='approve',
                    REPLACE(iisoc.actual_count,',',''),
                    REPLACE( iisoc.`count`,',','')
                    )
            ),0),type FROM inventory_studios  iisoc
                    WHERE
                    iisoc.product_code ='" . $product_code . "'
                    AND
                    iisoc.stock_from ='warehouse_qa'
                
                AND 
                iisoc.status ='received' 
            
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
                group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabOut); $i++) {

                $tempArray[$grabOut[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrOut[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ###############
    #############transit
    $arrTransit = array();
    $grabTransit = array(
        "transit_out",
        "transit_in"
    );
    $query = " select 
                        if( iisoc.stock_from  ='warehouse_qa' ,
                    
                    coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                    REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_out, 
    
                    if( iisoc.store_id  ='warehouse_qa' ,
                    coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                    REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_in
                    
                    FROM inventory_studios  iisoc
                    WHERE
                    iisoc.product_code ='" . $product_code . "'
                    AND
                ( iisoc.stock_from  ='warehouse_qa'
                        OR
                        iisoc.store_id  ='warehouse_qa'
                        )
                
                AND 
                iisoc.status ='in transit' 
            
               
                AND
                DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
                group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabTransit); $i++) {

                $tempArray[$grabTransit[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrTransit[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;


    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = '0';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';


    $arrInvLook[0]["store_name"] = $arrBegINV[0]["store_name"];
    $arrInvLook[0]["item_name"] = $arrBegINV[0]["item_name"];
    $arrInvLook[0]["product_code"] = $arrBegINV[0]["product_code"];
    $arrInvLook[0]["beg_inventory"] = $arrBegINV[0]["beg_inventory"];
    $arrInvLook[0]["sales"] = '0';

    for ($i = 0; $i < sizeof($arrIn); $i++) {
        if ($arrIn[$i]['type'] == 'stock_transfer' || $arrIn[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_in_c"] += $arrIn[$i]["In"];
        }
        if ($arrIn[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_in_c"] += $arrIn[$i]["In"];
        }

        if ($arrIn[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_i"] += $arrIn[$i]["In"];
        }

    }


    for ($i = 0; $i < sizeof($arrOut); $i++) {

        if ($arrOut[$i]['type'] == 'stock_transfer' || $arrOut[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'pullout') {
            $arrInvLook[0]["pullout_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_c"] += $arrOut[$i]["Out"];
        }

        // $arrInvLook[0]["stock_transfer_out_c"] =  $arrOut[0]["stock_transfer_out_c"];

    }

    for ($i = 0; $i < sizeof($arrTransit); $i++) {
        $arrInvLook[0]["transit_in"] += $arrTransit[$i]["transit_in"];
        $arrInvLook[0]["transit_out_c"] += $arrTransit[$i]["transit_out"];
        $arrInvLook[0]["transit_out"] += $arrTransit[$i]["transit_out"];
    }

    // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

    // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
    //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
    //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
    // echo "<pre>";
    // print_r($arrInvLook);
    // echo "</pre>";
    return $arrInvLook;

}





function storeChecker_smr($product_code, $store_id, $date_start, $date_end)
{

    global $conn;
    $datenow = date('Y-m-d');

    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = 'none';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';

    $arrBegINV = array();
    $grabBegINVparams = array(
        "store_name",
        "item_name",
        "product_code",
        "beg_inventory",
        "date_end"
    );
    $query = " SELECT 
            
                            sls.store_name_proper,
                           p51.item_name,
                           p51.product_code,
                           coalesce(pactual.input_count,0) as beginventory,
                           pactual.date_end
                        FROM    poll_51_studios_new p51
                        LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited ='" . $store_id . "'  AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                                        WHERE iacx.store_audited ='" . $store_id . "'
                                                        AND iacx.product_code='" . $product_code . "'
                                                        AND iacx.date_end<'" . $date_start . "' 
                                                    ) AND pactual.product_code='" . $product_code . "' 
                        AND pactual.product_code=p51.product_code
                        LEFT JOIN store_codes_studios sls on sls.store_code='" . $store_id . "'
                                   WHERE p51.product_code='" . $product_code . "'
                              
                                                group by p51.product_code";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabBegINVparams); $i++) {

                $tempArray[$grabBegINVparams[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrBegINV[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    $grabInvParams = array(
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
    $datenow = date('Y-m-d');


    if ($store_id == '787' || $store_id == '788' || $store_id == '789' || $store_id == '1017' || $store_id == '1019' || $store_id == '1021' || $store_id == '889') {
        $reRoute = " AND  date(os.payment_date)>='2020-06-25' 
                                      and  os.product_upgrade!='sunnies_studios' ";

        // AND os.product_upgrade!='PL0010'
    } else {
        $reRoute = " 
                               
                                             
                                      ";
    }


    // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
    if (
        preg_match("/MC/i", $product_code) || preg_match("/MH/i", $product_code) || preg_match("/MG/i", $product_code)
        || preg_match("/PL/i", $product_code) || preg_match("/60319/i", $product_code)
        || preg_match("/DMP/i", $product_code) || preg_match("/HC/i", $product_code) || preg_match("/DD/i", $product_code) || preg_match("/AFC/i", $product_code)
        || preg_match("/P1/i", $product_code)
        || preg_match("/MSCL/i", $product_code) || preg_match("/SDB/i", $product_code) || preg_match("/SSWP/i", $product_code) || preg_match("/SWB/i", $product_code)
        || preg_match("/SC/i", $product_code)
        || preg_match("/GRH/i", $product_code) || preg_match("/MSAC/i", $product_code) || preg_match("/MSKC/i", $product_code) || preg_match("/KLB/i", $product_code)
        || preg_match("/MSEP/i", $product_code) || preg_match("/SPB0/i", $product_code) || preg_match("/MSLS/i", $product_code) || preg_match("/MSHS/i", $product_code)
        || preg_match("/SSGWPCB/i", $product_code) || preg_match("/SSP/i", $product_code) || preg_match("/HT0/i", $product_code)
        || preg_match("/TB0/i", $product_code) || preg_match("/SGC/i", $product_code) || preg_match("/DS/i", $product_code)
        || preg_match("/ST/i", $product_code) || preg_match("/SMS/i", $product_code) || preg_match("/SML/i", $product_code)
        || preg_match("/CPV/i", $product_code) || preg_match("/SWS/i", $product_code) || preg_match("/SMHP/i", $product_code)
        || preg_match("/SFSP/i", $product_code) || preg_match("/MSTS/i", $product_code) || preg_match("/SMZ/i", $product_code)
        || preg_match("/MSSS/i", $product_code) || preg_match("/VS/i", $product_code) || preg_match("/VCP/i", $product_code) || preg_match("/SS00/i", $product_code)
        || preg_match("/SPHC/i", $product_code) || preg_match("/NT0/i", $product_code) || preg_match("/GSOM/i", $product_code)
        || preg_match("/DM/i", $product_code) || preg_match("/DTS/i", $product_code) || preg_match("/C100/i", $product_code)
        || preg_match("/MRK/i", $product_code)
    ) {

        $carekits = " AND os.product_upgrade ";
        $condition1 = "  ";

    } else {
        $condition1 = " and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
        $carekits = " AND os.product_code ";

    }
    //                  $arrMaxDate3=array();
    //                  $queryMaxDate2="SELECT Max(date_end),max(date_start) FROM inventory_actual_count_studios
    //             where  store_audited='".$store_id."'
    //             AND date_end< '".$date_end."'  ";

    // $grabInvParams22=array("max_date","min_date");
    //                         $stmt2 = mysqli_stmt_init($conn);
    //                         if (mysqli_stmt_prepare($stmt2, $queryMaxDate2)) {

    //                             mysqli_stmt_execute($stmt2);
    //                             mysqli_stmt_bind_result($stmt2, $result1, $result2);

    //                             while (mysqli_stmt_fetch($stmt2)) {

    //                                 $tempArray = array();

    //                                 for ($i=0; $i < sizeOf($grabInvParams22); $i++) { 

    //                                     $tempArray[$grabInvParams22[$i]] = ${'result' . ($i+1)};

    //                                 };

    //                                 $arrMaxDate3[] = $tempArray;

    //                             };

    //                             mysqli_stmt_close($stmt2);    

    //                         }
    //                         else {

    //                             echo mysqli_error($conn);	

    //                         };



    //  OR ( o.store_id='".$store_id."'  and dispatch_type='packaging')
    $que_sales = "SELECT count(po_number)
                                                    
                                            
                FROM `orders_sunnies_studios` os
                
                LEFT JOIN orders_studios o ON o.order_id=os.order_id
                
                WHERE 
                payment='y'
                And os.status NOT IN ('return','cancelled','returned','failed' )
                AND date(os.payment_date)>='2020-02-4'
                " . $condition1 . " 
                 AND  date(os.payment_date)>='" . $arrBegINV[0]["date_end"] . "'
                AND  date(os.payment_date)<='" . $date_end . "'
               " . $carekits . "  ='" . $product_code . "'
              
                AND  origin_branch='" . $store_id . "'
                ";

    // AND  date(os.payment_date)>='".date("Y-m-d", strtotime($arrMaxDate3[0]["max_date"] . " +1 day"))."'
    $grabInvParamssales = array("sales");
    $stmt2 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt2, $que_sales)) {

        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $result1);

        while (mysqli_stmt_fetch($stmt2)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabInvParamssales); $i++) {

                $tempArray[$grabInvParamssales[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrSalesData[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt2);

    } else {

        echo mysqli_error($conn);

    }
    ;





    ################################################

    $arrIn = array();
    $grabIn = array(
        "In",
        'type'
    );
    $query = " select coalesce(
                   sum(
                   if(iisi.variance_status='approve',
                   REPLACE(iisi.actual_count,',',''),
                   REPLACE( iisi.`count`,',','')
                   )
                   ),0),type FROM inventory_studios  iisi
                   WHERE
                   iisi.product_code ='" . $product_code . "'
                   AND
                   iisi.store_id ='" . $store_id . "'
                   AND
                   iisi.status='received'
                   
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='" . $date_end . "'
                   group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabIn); $i++) {

                $tempArray[$grabIn[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrIn[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ################
    ##################### stock transfer out 
    $arrOut = array();
    $grabOut = array(
        "Out",
        "type"
    );
    $query = " select coalesce(
            sum(
                if(iisoc.variance_status='approve',
                REPLACE(iisoc.actual_count,',',''),
                REPLACE( iisoc.`count`,',','')
                )
        ),0),type FROM inventory_studios  iisoc
                WHERE
                iisoc.product_code ='" . $product_code . "'
                AND
                iisoc.stock_from ='" . $store_id . "'
            
            AND 
            iisoc.status ='received' 
          
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
            group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabOut); $i++) {

                $tempArray[$grabOut[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrOut[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ###############
    #############transit
    $arrTransit = array();
    $grabTransit = array(
        "transit_out",
        "transit_in"
    );
    $queryT = " select 
                     if( iisoc.stock_from  ='" . $store_id . "' ,
                
                coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_out, 

                if( iisoc.store_id  ='" . $store_id . "' ,
                coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_in
                
                FROM inventory_studios  iisoc
                WHERE
                iisoc.product_code ='" . $product_code . "'
                AND
               ( iisoc.stock_from  ='" . $store_id . "' 
                    OR
                    iisoc.store_id  ='" . $store_id . "'
                    )
            
            AND 
            iisoc.status ='in transit' 
          
           
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
            group by reference_number";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $queryT)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabTransit); $i++) {

                $tempArray[$grabTransit[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrTransit[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;

    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = '0';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';


    $arrInvLook[0]["store_name"] = $arrBegINV[0]["store_name"];
    $arrInvLook[0]["item_name"] = $arrBegINV[0]["item_name"];
    $arrInvLook[0]["product_code"] = $arrBegINV[0]["product_code"];
    $arrInvLook[0]["beg_inventory"] = $arrBegINV[0]["beg_inventory"];
    $arrInvLook[0]["sales"] = $arrSalesData[0]["sales"];

    for ($i = 0; $i < sizeof($arrIn); $i++) {
        if ($arrIn[$i]['type'] == 'stock_transfer' || $arrIn[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_in_c"] += $arrIn[$i]["In"];
        }
        if ($arrIn[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_in_c"] += $arrIn[$i]["In"];
        }

        if ($arrIn[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_i"] += $arrIn[$i]["In"];
        }

    }


    for ($i = 0; $i < sizeof($arrOut); $i++) {

        if ($arrOut[$i]['type'] == 'stock_transfer' || $arrOut[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'pullout') {
            $arrInvLook[0]["pullout_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_c"] += $arrOut[$i]["Out"];
        }

        // $arrInvLook[0]["stock_transfer_out_c"] =  $arrOut[0]["stock_transfer_out_c"];

    }

    for ($i = 0; $i < sizeof($arrTransit); $i++) {
        $arrInvLook[0]["transit_in"] += $arrTransit[$i]["transit_in"];
        $arrInvLook[0]["transit_out_c"] += $arrTransit[$i]["transit_out"];
        $arrInvLook[0]["transit_out"] += $arrTransit[$i]["transit_out"];
    }
    // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

    // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
    //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
    //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
    // echo "<pre>";
    // print_r($arrInvLook);
    // echo "</pre>";
    return $arrInvLook;

}







function storeChecker_smr_VS($product_code, $store_id, $date_start, $date_end)
{

    global $conn;
    $datenow = date('Y-m-d');



    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = 'none';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';

    $arrBegINV = array();
    $grabBegINVparams = array(
        "store_name",
        "item_name",
        "product_code",
        "beg_inventory",
        "date_end"
    );
    $query = " SELECT 

                sls.store_name_proper,
               p51.item_name,
               p51.product_code,
               coalesce(pactual.input_count,0) as beginventory,
               pactual.date_end
            FROM    poll_51_studios_new p51
            LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited ='" . $store_id . "'    AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                            WHERE iacx.store_audited ='" . $store_id . "'
                                            AND iacx.product_code='" . $product_code . "'
                                            AND iacx.date_end<'" . $date_start . "' 
                                        ) AND pactual.product_code='" . $product_code . "' 
            AND pactual.product_code=p51.product_code
            LEFT JOIN store_codes_studios sls on sls.store_code='" . $store_id . "'
                       WHERE p51.product_code='" . $product_code . "'
                
                                    group by p51.product_code";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabBegINVparams); $i++) {

                $tempArray[$grabBegINVparams[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrBegINV[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;

    $grabInvParams = array(
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
    $datenow = date('Y-m-d');


    if ($store_id == '787' || $store_id == '788' || $store_id == '789' || $store_id == '1017' || $store_id == '1019' || $store_id == '1021' || $store_id == '889') {
        $reRoute = " AND  date(os.payment_date)>='2020-06-25' 
                                      and  os.product_upgrade!='sunnies_studios' ";

        // AND os.product_upgrade!='PL0010'
    } else {
        $reRoute = " 
                                
                                      ";
    }


    // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
    if (
        preg_match("/MC/i", $product_code) || preg_match("/MH/i", $product_code) || preg_match("/MG/i", $product_code)
        || preg_match("/PL/i", $product_code) || preg_match("/60319/i", $product_code)
        || preg_match("/DMP/i", $product_code) || preg_match("/HC/i", $product_code) || preg_match("/DD/i", $product_code) || preg_match("/AFC/i", $product_code)
        || preg_match("/P1/i", $product_code)
        || preg_match("/MSCL/i", $product_code) || preg_match("/SDB/i", $product_code) || preg_match("/SSWP/i", $product_code) || preg_match("/SWB/i", $product_code)
        || preg_match("/SC/i", $product_code)
        || preg_match("/GRH/i", $product_code) || preg_match("/MSAC/i", $product_code) || preg_match("/MSKC/i", $product_code) || preg_match("/KLB/i", $product_code)
        || preg_match("/MSEP/i", $product_code) || preg_match("/SPB0/i", $product_code) || preg_match("/MSLS/i", $product_code) || preg_match("/MSHS/i", $product_code)
        || preg_match("/SSGWPCB/i", $product_code) || preg_match("/SSP/i", $product_code) || preg_match("/HT0/i", $product_code)
        || preg_match("/TB0/i", $product_code) || preg_match("/SGC/i", $product_code) || preg_match("/DS/i", $product_code)
        || preg_match("/ST/i", $product_code) || preg_match("/SMS/i", $product_code) || preg_match("/SML/i", $product_code)
        || preg_match("/CPV/i", $product_code) || preg_match("/SWS/i", $product_code) || preg_match("/SMHP/i", $product_code)
        || preg_match("/SFSP/i", $product_code) || preg_match("/MSTS/i", $product_code) || preg_match("/SMZ/i", $product_code)
        || preg_match("/MSSS/i", $product_code) || preg_match("/VS/i", $product_code) || preg_match("/VCP/i", $product_code) || preg_match("/SS00/i", $product_code)
        || preg_match("/SPHC/i", $product_code) || preg_match("/NT0/i", $product_code) || preg_match("/GSOM/i", $product_code)
        || preg_match("/DM/i", $product_code) || preg_match("/DTS/i", $product_code) || preg_match("/C100/i", $product_code)
        || preg_match("/MRK/i", $product_code)
    ) {

        $carekits = " AND os.product_upgrade ";
        $condition1 = "  ";

    } else {
        $condition1 = " and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
        $carekits = " AND os.product_code ";

    }

    //                  $arrMaxDate3=array();
    //                  $queryMaxDate2="SELECT Max(date_end),max(date_start) FROM inventory_actual_count_studios
    // where  store_audited='".$store_id."'
    // AND date_end< '".$date_end."'  ";

    // $grabInvParams22=array("max_date","min_date");
    //                         $stmt2 = mysqli_stmt_init($conn);
    //                         if (mysqli_stmt_prepare($stmt2, $queryMaxDate2)) {

    //                             mysqli_stmt_execute($stmt2);
    //                             mysqli_stmt_bind_result($stmt2, $result1, $result2);

    //                             while (mysqli_stmt_fetch($stmt2)) {

    //                                 $tempArray = array();

    //                                 for ($i=0; $i < sizeOf($grabInvParams22); $i++) { 

    //                                     $tempArray[$grabInvParams22[$i]] = ${'result' . ($i+1)};

    //                                 };

    //                                 $arrMaxDate3[] = $tempArray;

    //                             };

    //                             mysqli_stmt_close($stmt2);    

    //                         }
    //                         else {

    //                             echo mysqli_error($conn);	

    //                         };
    //  OR ( o.store_id='".$store_id."'  and dispatch_type='packaging')
    $que_sales = "SELECT count(po_number)
                                                    
                                            
                FROM `orders_specs` os
                
                LEFT JOIN orders o ON o.order_id=os.order_id
                
                WHERE 
                payment='y'
                And os.status NOT IN ('return','cancelled','returned','failed' )
                AND date(os.payment_date)>='2020-02-4'
                " . $condition1 . " 
                 AND  date(os.payment_date)>='" . $arrBegINV[0]["date_end"] . "'
                AND  date(os.payment_date)<='" . $date_end . "'
               " . $carekits . "  ='" . $product_code . "'
                " . $reRoute . " 
                AND  origin_branch='" . $store_id . "'
                ";
    $grabInvParamssales = array("sales");
    $stmt2 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt2, $que_sales)) {

        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $result1);

        while (mysqli_stmt_fetch($stmt2)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabInvParamssales); $i++) {

                $tempArray[$grabInvParamssales[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrSalesData[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt2);

    } else {

        echo mysqli_error($conn);

    }
    ;




    ################################################

    $arrIn = array();
    $grabIn = array(
        "In",
        'type'
    );
    $query = " select coalesce(
                   sum(
                   if(iisi.variance_status='approve',
                   REPLACE(iisi.actual_count,',',''),
                   REPLACE( iisi.`count`,',','')
                   )
                   ),0),type FROM inventory_studios  iisi
                   WHERE
                   iisi.product_code ='" . $product_code . "'
                   AND
                   iisi.store_id ='" . $store_id . "'
                   AND
                   iisi.status='received'
                   
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='" . $date_end . "'
                   group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabIn); $i++) {

                $tempArray[$grabIn[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrIn[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ################
    ##################### stock transfer out 
    $arrOut = array();
    $grabOut = array(
        "Out",
        "type"
    );
    $query = " select coalesce(
            sum(
                if(iisoc.variance_status='approve',
                REPLACE(iisoc.actual_count,',',''),
                REPLACE( iisoc.`count`,',','')
                )
        ),0),type FROM inventory_studios  iisoc
                WHERE
                iisoc.product_code ='" . $product_code . "'
                AND
                iisoc.stock_from ='" . $store_id . "'
            
            AND 
            iisoc.status ='received' 
          
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
            group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabOut); $i++) {

                $tempArray[$grabOut[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrOut[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ###############
    #############transit
    $arrTransit = array();
    $grabTransit = array(
        "transit_out",
        "transit_in"
    );
    $query = " select 
                     if( iisoc.stock_from  ='" . $store_id . "' ,
                
                coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_out, 

                if( iisoc.store_id  ='" . $store_id . "' ,
                coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_in
                
                FROM inventory_studios  iisoc
                WHERE
                iisoc.product_code ='" . $product_code . "'
                AND
               ( iisoc.stock_from  ='" . $store_id . "' 
                    OR
                    iisoc.store_id  ='" . $store_id . "'
                    )
            
            AND 
            iisoc.status ='in transit' 
          
           
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
            group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabTransit); $i++) {

                $tempArray[$grabTransit[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrTransit[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;

    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = '0';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';


    $arrInvLook[0]["store_name"] = $arrBegINV[0]["store_name"];
    $arrInvLook[0]["item_name"] = $arrBegINV[0]["item_name"];
    $arrInvLook[0]["product_code"] = $arrBegINV[0]["product_code"];
    $arrInvLook[0]["beg_inventory"] = $arrBegINV[0]["beg_inventory"];
    $arrInvLook[0]["sales"] = $arrSalesData[0]["sales"];

    for ($i = 0; $i < sizeof($arrIn); $i++) {
        if ($arrIn[$i]['type'] == 'stock_transfer' || $arrIn[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_in_c"] += $arrIn[$i]["In"];
        }
        if ($arrIn[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_in_c"] += $arrIn[$i]["In"];
        }

        if ($arrIn[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_i"] += $arrIn[$i]["In"];
        }

    }


    for ($i = 0; $i < sizeof($arrOut); $i++) {

        if ($arrOut[$i]['type'] == 'stock_transfer' || $arrOut[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'pullout') {
            $arrInvLook[0]["pullout_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_c"] += $arrOut[$i]["Out"];
        }

        // $arrInvLook[0]["stock_transfer_out_c"] =  $arrOut[0]["stock_transfer_out_c"];

    }

    for ($i = 0; $i < sizeof($arrTransit); $i++) {
        $arrInvLook[0]["transit_in"] += $arrTransit[$i]["transit_in"];
        $arrInvLook[0]["transit_out_c"] += $arrTransit[$i]["transit_out"];
        $arrInvLook[0]["transit_out"] += $arrTransit[$i]["transit_out"];
    }
    // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

    // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
    //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
    //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
    // echo "<pre>";
    // print_r($arrInvLook);
    // echo "</pre>";
    return $arrInvLook;

}





function storeChecker_smr_MS($product_code, $store_id, $date_start, $date_end)
{

    global $conn;
    $datenow = date('Y-m-d');



    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = 'none';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';

    $arrBegINV = array();
    $grabBegINVparams = array(
        "store_name",
        "item_name",
        "product_code",
        "beg_inventory",
        "date_end"
    );
    $query = " SELECT 

                sls.store_name_proper,
               p51.item_name,
               p51.product_code,
               coalesce(pactual.input_count,0) as beginventory,
               pactual.date_end
            FROM    poll_51_studios_new p51
            LEFT JOIN inventory_actual_count_studios pactual ON pactual.store_audited ='" . $store_id . "'  AND pactual.date_end= (SELECT max(iacx.date_end) FROM inventory_actual_count_studios iacx 
                                            WHERE iacx.store_audited ='" . $store_id . "'
                                            AND iacx.product_code='" . $product_code . "'
                                            AND iacx.date_end<'" . $date_start . "' 
                                        ) AND pactual.product_code='" . $product_code . "' 
            AND pactual.product_code=p51.product_code
            LEFT JOIN store_codes_studios sls on sls.store_code='" . $store_id . "'
                       WHERE p51.product_code='" . $product_code . "'
                  
                                    group by p51.product_code";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabBegINVparams); $i++) {

                $tempArray[$grabBegINVparams[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrBegINV[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;

    $grabInvParams = array(
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
    $datenow = date('Y-m-d');


    if ($store_id == '787' || $store_id == '788' || $store_id == '789' || $store_id == '1017' || $store_id == '1019' || $store_id == '1021' || $store_id == '889') {
        $reRoute = " AND  date(os.payment_date)>='2020-06-25' 
                                      and  os.product_upgrade='sunnies_studios' ";

        // AND os.product_upgrade!='PL0010'
    } else {
        $reRoute = " 
                                
                                      ";
    }


    // AND if(os.product_code='M100',os.product_upgrade,os.product_code)
    if (
        preg_match("/MC/i", $product_code) || preg_match("/MH/i", $product_code) || preg_match("/MG/i", $product_code)
        || preg_match("/PL/i", $product_code) || preg_match("/60319/i", $product_code)
        || preg_match("/DMP/i", $product_code) || preg_match("/HC/i", $product_code) || preg_match("/DD/i", $product_code) || preg_match("/AFC/i", $product_code)
        || preg_match("/P1/i", $product_code)
        || preg_match("/MSCL/i", $product_code) || preg_match("/SDB/i", $product_code) || preg_match("/SSWP/i", $product_code) || preg_match("/SWB/i", $product_code)
        || preg_match("/SC/i", $product_code)
        || preg_match("/GRH/i", $product_code) || preg_match("/MSAC/i", $product_code) || preg_match("/MSKC/i", $product_code) || preg_match("/KLB/i", $product_code)
        || preg_match("/MSEP/i", $product_code) || preg_match("/SPB0/i", $product_code) || preg_match("/MSLS/i", $product_code) || preg_match("/MSHS/i", $product_code)
        || preg_match("/SSGWPCB/i", $product_code) || preg_match("/SSP/i", $product_code) || preg_match("/HT0/i", $product_code)
        || preg_match("/TB0/i", $product_code) || preg_match("/SGC/i", $product_code) || preg_match("/DS/i", $product_code)
        || preg_match("/ST/i", $product_code) || preg_match("/SMS/i", $product_code) || preg_match("/SML/i", $product_code)
        || preg_match("/CPV/i", $product_code) || preg_match("/SWS/i", $product_code) || preg_match("/SMHP/i", $product_code)
        || preg_match("/SFSP/i", $product_code) || preg_match("/MSTS/i", $product_code) || preg_match("/SMZ/i", $product_code)
        || preg_match("/MSSS/i", $product_code) || preg_match("/VS/i", $product_code) || preg_match("/VCP/i", $product_code) || preg_match("/SS00/i", $product_code)
        || preg_match("/SPHC/i", $product_code) || preg_match("/NT0/i", $product_code) || preg_match("/GSOM/i", $product_code)
        || preg_match("/DM/i", $product_code) || preg_match("/DTS/i", $product_code) || preg_match("/C100/i", $product_code)
        || preg_match("/MRK/i", $product_code)
    ) {

        $carekits = " AND os.product_upgrade ";
        $condition1 = "  ";

    } else {
        $condition1 = " and ( os.product_upgrade ='sunnies_studios'  OR   os.product_upgrade ='G100')";
        $carekits = " AND os.product_code ";

    }

    $arrMaxDate3 = array();
    $queryMaxDate2 = "SELECT Max(date_end),max(date_start) FROM inventory_actual_count_studios
            where  store_audited='" . $store_id . "'
            AND date_end< '" . $date_end . "'  ";

    $grabInvParams22 = array("max_date", "min_date");
    $stmt2 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt2, $queryMaxDate2)) {

        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $result1, $result2);

        while (mysqli_stmt_fetch($stmt2)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabInvParams22); $i++) {

                $tempArray[$grabInvParams22[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrMaxDate3[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt2);

    } else {

        echo mysqli_error($conn);

    }
    ;
    //  OR ( o.store_id='".$store_id."'  and dispatch_type='packaging')
    $que_sales = "SELECT count(po_number)
                                                    
                                            
                FROM `orders_sunnies_studios` os
                
                LEFT JOIN orders_studios o ON o.order_id=os.order_id
                
                WHERE 
                payment='y'
                And os.status NOT IN ('return','cancelled','returned','failed' )
                AND date(os.payment_date)>='2020-02-4'
                " . $condition1 . " 
                AND  date(os.payment_date)>='" . $date_start . "'
                AND  date(os.payment_date)<='" . $date_end . "'
               " . $carekits . "  ='" . $product_code . "'
                " . $reRoute . " 
                AND os.stock_from='SS-MPWHC'
                ";
    $grabInvParamssales = array("sales");
    $stmt2 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt2, $que_sales)) {

        mysqli_stmt_execute($stmt2);
        mysqli_stmt_bind_result($stmt2, $result1);

        while (mysqli_stmt_fetch($stmt2)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabInvParamssales); $i++) {

                $tempArray[$grabInvParamssales[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrSalesData[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt2);

    } else {

        echo mysqli_error($conn);

    }
    ;

    $que_sales2 = "SELECT if( os.packaging_for!='' && packaging_stock='lab',
                                    '0',
       
                                    COUNT(DISTINCT(po_number))
                                    )
       
       
                                    FROM `orders_specs` os
       
                                    LEFT JOIN orders o ON o.order_id=os.order_id
       
                                    WHERE 
                                    payment='y'
                                    And os.status NOT IN ('return','cancelled','returned','failed','void' )
                                    AND date(os.payment_date)>='2020-02-4'
                                    and coalesce(packaging_stock,'')!='lab'
                                    AND  date(os.payment_date)>='" . $arrBegINV[0]["date_end"] . "'
                                    AND  date(os.payment_date)<='" . $date_end . "'
                                    " . $carekits . "  ='" . $product_code . "'
                                    " . $reRoute . " 
                                   AND (
                                                         (o.origin_branch IN ('1017','1019','1021')  and dispatch_type!='packaging')
                                                              OR 
                                                         ( o.store_id IN ('1017','1019','1021')  and dispatch_type='packaging')
                                                      
                                                      )
                    ";




    $grabInvParamssales2 = array("sales");
    $arrSalesData2 = array();

    $stmt3 = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt3, $que_sales2)) {

        mysqli_stmt_execute($stmt3);
        mysqli_stmt_bind_result($stmt3, $result1);

        while (mysqli_stmt_fetch($stmt3)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabInvParamssales2); $i++) {

                $tempArray[$grabInvParamssales2[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrSalesData2[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt3);

    } else {

        echo mysqli_error($conn);

    }
    ;
    // $arrSalesData2[0]["sales"]+
    $total_sales = $arrSalesData[0]["sales"];




    ################################################

    $arrIn = array();
    $grabIn = array(
        "In",
        'type'
    );
    $query = " select coalesce(
                   sum(
                   if(iisi.variance_status='approve',
                   REPLACE(iisi.actual_count,',',''),
                   REPLACE( iisi.`count`,',','')
                   )
                   ),0),type FROM inventory_studios  iisi
                   WHERE
                   iisi.product_code ='" . $product_code . "'
                   AND
                   iisi.store_id ='" . $store_id . "'
                   AND
                   iisi.status='received'
                   
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
                   AND
                   DATE(DATE_ADD(iisi.status_date, INTERVAL 13 HOUR))<='" . $date_end . "'
                   group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabIn); $i++) {

                $tempArray[$grabIn[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrIn[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ################
    ##################### stock transfer out 
    $arrOut = array();
    $grabOut = array(
        "Out",
        "type"
    );
    $query = " select coalesce(
            sum(
                if(iisoc.variance_status='approve',
                REPLACE(iisoc.actual_count,',',''),
                REPLACE( iisoc.`count`,',','')
                )
        ),0),type FROM inventory_studios  iisoc
                WHERE
                iisoc.product_code ='" . $product_code . "'
                AND
                iisoc.stock_from ='" . $store_id . "'
            
            AND 
            iisoc.status ='received' 
          
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))>='" . $arrBegINV[0]["date_end"] . "'
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
            group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabOut); $i++) {

                $tempArray[$grabOut[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrOut[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;
    ###############
    #############transit
    $arrTransit = array();
    $grabTransit = array(
        "transit_out",
        "transit_in"
    );
    $query = " select 
                     if( iisoc.stock_from  ='" . $store_id . "' ,
                
                coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_out, 

                if( iisoc.store_id  ='" . $store_id . "' ,
                coalesce( sum( if(iisoc.variance_status='approve', REPLACE(iisoc.actual_count,',',''), 
                REPLACE( iisoc.`count`,',','') ) ),0),'0') as transit_in
                
                FROM inventory_studios  iisoc
                WHERE
                iisoc.product_code ='" . $product_code . "'
                AND
               ( iisoc.stock_from  ='" . $store_id . "' 
                    OR
                    iisoc.store_id  ='" . $store_id . "'
                    )
            
            AND 
            iisoc.status ='in transit' 
          
           
            AND
            DATE(DATE_ADD(iisoc.status_date, INTERVAL 13 HOUR))<='" . $date_end . "' 
            group by type";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabTransit); $i++) {

                $tempArray[$grabTransit[$i]] = ${'result' . ($i + 1)};

            }
            ;

            $arrTransit[] = $tempArray;

        }
        ;

        mysqli_stmt_close($stmt);

    } else {

        echo mysqli_error($conn);

    }
    ;

    $arrInvLook = array();
    $arrInvLook[0]["store_name"] = '0';
    $arrInvLook[0]["item_name"] = '0';
    $arrInvLook[0]["product_code"] = '0';
    $arrInvLook[0]["beg_inventory"] = '0';
    $arrInvLook[0]["sales"] = '0';
    $arrInvLook[0]["stock_transfer_in_c"] = '0';
    $arrInvLook[0]["stock_transfer_out_c"] = '0';
    $arrInvLook[0]["interbranch_out_c"] = '0';
    $arrInvLook[0]["interbranch_in_c"] = '0';
    $arrInvLook[0]["pullout_c"] = '0';
    $arrInvLook[0]["damage_c"] = '0';
    $arrInvLook[0]["damage_i"] = '0';
    $arrInvLook[0]["number"] = '0';
    $arrInvLook[0]["transit_out"] = '0';
    $arrInvLook[0]["requested"] = '0';
    $arrInvLook[0]["transit_in"] = '0';
    $arrInvLook[0]["transit_out_c"] = '0';


    $arrInvLook[0]["store_name"] = $arrBegINV[0]["store_name"];
    $arrInvLook[0]["item_name"] = $arrBegINV[0]["item_name"];
    $arrInvLook[0]["product_code"] = $arrBegINV[0]["product_code"];
    $arrInvLook[0]["beg_inventory"] = $arrBegINV[0]["beg_inventory"];
    $arrInvLook[0]["sales"] = $total_sales;


    for ($i = 0; $i < sizeof($arrIn); $i++) {
        if ($arrIn[$i]['type'] == 'stock_transfer' || $arrIn[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_in_c"] += $arrIn[$i]["In"];
        }
        if ($arrIn[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_in_c"] += $arrIn[$i]["In"];
        }

        if ($arrIn[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_i"] += $arrIn[$i]["In"];
        }

    }


    for ($i = 0; $i < sizeof($arrOut); $i++) {

        if ($arrOut[$i]['type'] == 'stock_transfer' || $arrOut[$i]['type'] == 'replenish') {
            $arrInvLook[0]["stock_transfer_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'interbranch') {
            $arrInvLook[0]["interbranch_out_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'pullout') {
            $arrInvLook[0]["pullout_c"] += $arrOut[$i]["Out"];
        }
        if ($arrOut[$i]['type'] == 'damage') {
            $arrInvLook[0]["damage_c"] += $arrOut[$i]["Out"];
        }

        // $arrInvLook[0]["stock_transfer_out_c"] =  $arrOut[0]["stock_transfer_out_c"];

    }

    for ($i = 0; $i < sizeof($arrTransit); $i++) {
        $arrInvLook[0]["transit_in"] += $arrTransit[$i]["transit_in"];
        $arrInvLook[0]["transit_out_c"] += $arrTransit[$i]["transit_out"];
        $arrInvLook[0]["transit_out"] += $arrTransit[$i]["transit_out"];
    }
    // $beg_inventory = $arrInvLook[0]["beg_inventory"]-$arrInvLook[0]["pullout"]-$arrInvLook[0]["damage"]-$arrInvLook[0]["stock_transfer_out"]-$arrInvLook[0]["sales"];

    // $runningtotal=  $beg_inventory +$arrInvLook[0]["stock_transfer_in_c"]
    //     +$arrInvLook[0]["interbranch_in_c"]- $arrInvLook[0]["stock_transfer_out_c"]-
    //     $arrInvLook[0]["interbranch_out_c"]-$arrInvLook[0]["damage_c"]-$arrInvLook[0]["pullout_c"]-$arrInvLook[0]["sales"]-$arrInvLook[0]["transit_out"]; 
    // echo "<pre>";
    // print_r($arrInvLook);
    // echo "</pre>";
    return $arrInvLook;

}
///////////////////////////////////////////////////



// $arrActualCount= array();

// $grabParamsACtual= array(
// 							'count',
// 							'actual_count_id',
// 							'date_count',
// 							'date_start',
// 							'date_end', 
// 							'store_audited',
// 							'auditor',
// 							'product_code',
// 							'input_count'
// );

//  $queryActualCounts="SELECT `count`,
// 							`actual_count_id`,
// 							`date_count`,
// 							`date_start`,
// 							`date_end`, 
// 							`store_audited`,
// 							`auditor`,
// 							`product_code`,
// 							`input_count` 
// 							FROM `inventory_actual_count_studios`
// 							 WHERE store_audited='".$store_id."' 
// 							 and  date_end =(SELECT max(iacx.date_end) from inventory_actual_count_studios  iacx 
//                                                                                                 WHERE  iacx.store_audited='".$store_id."' 
//                                                                                             AND iacx.date_end<'".$dateStart."' 
// 																					    )
// 							";
// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $queryActualCounts)) {

// 	mysqli_stmt_execute($stmt);
// 	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

// 	while (mysqli_stmt_fetch($stmt)) {

// 		$tempArray = array();

// 		for ($i=0; $i < sizeOf($grabParamsACtual); $i++) { 

// 			$tempArray[$grabParamsACtual[$i]] = ${'result' . ($i+1)};

// 		};

// 		$arrActualCount[] = $tempArray;

// 	};

// 	mysqli_stmt_close($stmt);    

// }
// else {

// 	echo mysqli_error($conn);

// };







// $arrActualCount2= array();

// $grabParamsACtual2= array(
// 							'count',
// 							'actual_count_id',
// 							'date_count',
// 							'date_start',
// 							'date_end', 
// 							'store_audited',
// 							'auditor',
// 							'product_code',
// 							'input_count'
// );
//  $queryActualCounts2="SELECT `count`,
// 							`actual_count_id`,
// 							`date_count`,
// 							`date_start`,
// 							`date_end`, 
// 							`store_audited`,
// 							`auditor`,
// 							`product_code`,
// 							`input_count` 
// 							FROM `inventory_actual_count_studios`
// 							 WHERE store_audited='".$store_id."'
// 							 and  date_end ='".$dateEnd."'
// 							";
// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $queryActualCounts2)) {

// 	mysqli_stmt_execute($stmt);
// 	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

// 	while (mysqli_stmt_fetch($stmt)) {

// 		$tempArray = array();

// 		for ($i=0; $i < sizeOf($grabParamsACtual); $i++) { 

// 			$tempArray[$grabParamsACtual2[$i]] = ${'result' . ($i+1)};

// 		};

// 		$arrActualCount2[] = $tempArray;

// 	};

// 	mysqli_stmt_close($stmt);    

// }
// else {

// 	echo mysqli_error($conn);

// };

?>