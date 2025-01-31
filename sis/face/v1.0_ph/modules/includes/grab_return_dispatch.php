<?php 

if(!isset($_SESSION)){
        session_start();
    }


$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

$arrCustomerDetail = array();

 $queryDispatchDetail="SELECT  p.first_name, p.last_name,os.product_code,os.order_id,u.fullname,pr.color,p.profile_id,o.doctor,signature,os.status
FROM profiles_info p
INNER JOIN orders_specs os on os.profile_id=p.profile_id
LEFT JOIN users u on u.id=p.sales_person
LEFT JOIN products pr on pr.product_code=os.product_code
LEFT  JOIN orders o on o.order_id=os.order_id
where p.profile_id='".$_GET['profile_id']."'
;";


				$grabParams = array(
					'first_name',
					'last_name',
					'product_code',
					
					'order_id',
					'fullname',
					'color',
					'profile_id',
					'doctor',
					'signature',
					'status'
				);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryDispatchDetail)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 10; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerDetail[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 
					




?>