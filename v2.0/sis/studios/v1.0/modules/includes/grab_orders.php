<?php 

if(!isset($_SESSION)){
        session_start();
    }

$arrOrders =array();

$queryOrder="SELECT total,store_id,os.product_code,prescription_id,product_upgrade,o.order_id,prescription_vision,pr.item_description,os.status,os.id,os.orders_specs_id
 FROM orders o
  LEFT JOIN orders_specs os on os.order_id=o.order_id
   LEFT JOIN poll_51 pr on pr.product_code=os.product_code
where o.profile_id='".$_GET['profile_id']."'
and os.status='dispatched'
";

$grabParamsOrder = array( "total","store_id","product_code","prescription_id","product_upgrade","order_id","prescription_vision","item_description","status", "id","orders_specs_id" );

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryOrder)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 11; $i++) { 

            $tempArray[$grabParamsOrder[$i]] = ${'result' . ($i+1)};

        };

        $arrOrders[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; ?>
