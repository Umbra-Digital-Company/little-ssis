<?php
if(!isset($_SESSION)){
        session_start();
    }

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

//Required files
//require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";

$arrCustomer = array();
$querypn="";

$querypn .=" SELECT  p.first_name, p.last_name,os.product_code,os.prescription_id,os.order_id,u.fullname,pr.color,p.profile_id,os.status,pr.item_description,os.lens_option,lab_name,
 os.lab_print,os.lab_production,os.lab_status,os.received_stat,os.store_dispatch,os.signature,os.payment,os.lab_print_date,os.lab_production_date,os.lab_status_date,os.received_stat_date,os.payment_date,os.id,os.status as osstatus
FROM profiles_info p
INNER JOIN orders_specs os on os.profile_id=p.profile_id
LEFT JOIN users u on u.id=p.sales_person
LEFT JOIN products pr on pr.product_code=os.product_code  
LEFT  JOIN orders o on o.order_id=os.order_id 
LEFT JOIN labs_locations ll on ll.lab_id=o.laboratory
where o.store_id!=''
";

if($_GET['page']=='mlist'){
	$querypn .=" group by p.profile_id order by status_date   ";
	
	
}


$grabParams = array(
    'first_name',
    'last_name',
    'product_code',
    'prescription_id',
   	'order_id',
    'fullname',
	'color',
	'profile_id',
	'status',
	'item_description',
	'lens_option',
	'lab_name',
	'lab_print',
	'lab_production',
	'lab_status',
	'received_stat',
	'store_dispatch',
	'signature',
	'payment',
	'lab_print_date',
	'lab_production_date',
	'lab_status_date',
	'received_stat_date',
	'payment_date',
	'id',
	'osstatus'
);



 $query =	$querypn;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 26; $i++) { 

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