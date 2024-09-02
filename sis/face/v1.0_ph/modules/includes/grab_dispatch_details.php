<?php 

if(!isset($_SESSION)){
        session_start();
    }


$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

$arrCustomerDetail = array();
$queryDispatchDetail="SELECT p.first_name, p.last_name,os.product_code,os.order_id,pr.color,p.profile_id,o.doctor,os.signature,status,p.address,ll.lab_name
 FROM profiles_info p
 LEFT  JOIN orders_specs os on os.profile_id=p.profile_id
 LEFT JOIN users u on u.id=p.sales_person
 LEFT JOIN products pr on pr.product_code=os.product_code
 LEFT JOIN orders o on o.order_id=os.order_id
LEFT JOIN labs_locations ll on ll.lab_id=o.laboratory
where p.profile_id='".$_GET['profile_id']."'
and os.orders_specs_id='".$_GET['orderspecsid']."';";


				$grabParams = array(
					'first_name',
					'last_name',
					'product_code',
					'order_id',
					
					'color',
					'profile_id',
					'doctor',
					'signature',
					'status',
					'address',
					'lab_name'
				);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryDispatchDetail)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 11; $i++) { 

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