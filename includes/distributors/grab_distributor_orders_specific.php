<?php 

//////////////////////////////////////////////////////////////////////////////////// GRAB DISTRIBUTOR ID

if(isset($_GET['profile_id']) && $_GET['profile_id'] != '') {

    $distributorID = $_GET['profile_id'];

}
else {

    $distributorID = '';

};

//////////////////////////////////////////////////////////////////////////////////// GRAB DISTRIBUTORS

$arrOrders = array();

$query  =   "SELECT 
                do.date_created,
                do.date_updated,
                do.order_id,
                do.name,
                do.company,
                do.email_address,
                do.percentage,
                do.phone_number,
                do.message,
                do.paper_bag,
                do.sub_total,
                do.total,
                do.currency,
                do.country,
                dop.branch_name,
                SUM(dop.price),
                SUM(dop.quantity)                
            FROM 
                distributors_orders do
                    LEFT JOIN distributors_orders_products dop
                        ON dop.order_id = do.order_id
            WHERE
                do.distributor_id = '".$distributorID."'
            GROUP BY
                do.order_id
            ORDER BY
                do.date_created DESC";

$grabParams = array(
    'date_created',
    'date_updated',
    'order_id',
    'name',
    'company',
    'email_address',
    'percentage',
    'phone_number',
    'message',
    'paper_bag',
    'sub_total',
    'total',
    'currency',
    'country',
    'branch_name',
    'price',
    'quantity'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrOrders[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>
