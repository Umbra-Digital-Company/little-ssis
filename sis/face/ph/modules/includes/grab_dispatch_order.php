<?php
if(!isset($_SESSION)){
        session_start();
    }

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

//Required files
//require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";

$arrCustomer = array();

$query="SELECT  p.first_name, p.last_name,os.product_code,os.order_id,u.fullname,pr.color,p.profile_id,o.doctor,o.status
FROM profiles_info p
INNER JOIN orders_specs os on os.profile_id=p.profile_id
LEFT JOIN users u on u.id=p.sales_person
LEFT JOIN products pr on pr.product_code=os.product_code
LEFT  JOIN orders o on o.order_id=os.order_id
where o.status='for payment'
ORDER BY o.id";


$grabParams = array(
    'first_name',
    'last_name',
    'product_code',
    'order_id',
    'fullname',
	'color',
	'profile_id',
	'doctor',
	'status'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 9; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomer[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 


?>