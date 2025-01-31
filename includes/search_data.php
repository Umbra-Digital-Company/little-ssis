<?php
if(!isset($_SESSION)){
        session_start();
    }

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

//Required files
//require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";
$searchBreakdown["searchword"] = explode(" ", $_GET['s']);

$arrCustomerSearch = array();

$querypn="";

 $querypn .=" SELECT  p.first_name,
 p.last_name,
 os.product_code,
 os.prescription_id,
 os.order_id,

 pr.color,
 p.profile_id,
 os.status,
 pr.item_description,
 os.lens_option,
 sc.branch,
 lab_name,
 os.lab_print,
 os.lab_production,
 os.lab_status,
 os.received_stat,
 os.store_dispatch,
 os.signature,
 os.payment,
 os.lab_print_date,
 os.lab_production_date,
 os.lab_status_date,
 os.received_stat_date,
 os.payment_date,
 os.id,
 os.dispatch_type,
 os.po_number,
 os.orders_specs_id
 
FROM profiles_info p
LEFT JOIN orders_specs os on os.profile_id=p.profile_id
LEFT JOIN users u on u.id=p.sales_person
LEFT JOIN products pr on pr.product_code=os.product_code  
LEFT  JOIN orders o on o.order_id=os.order_id 
LEFT JOIN  store_codes sc on sc.location_code=o.store_id
LEFT JOIN labs_locations ll on ll.lab_id=o.laboratory
where 
os.payment='y'
 	and os.product_upgrade!='fashion_lens'
 	AND os.product_upgrade!='special_order'
	AND os.product_upgrade NOT LIKE '%adaptar%'
	AND os.product_upgrade NOT LIKE '%essilor%'
	AND os.product_upgrade NOT LIKE '%varilux%'
	AND os.product_upgrade NOT LIKE '%comfort%'

";

for($s=0;$s<sizeof($searchBreakdown["searchword"]);$s++){
$querypn .=" and	
 (p.last_name like '%".$searchBreakdown["searchword"][$s]."%' OR p.first_name like  '%".$searchBreakdown["searchword"][$s]."%' OR p.middle_name like  '%".$searchBreakdown["searchword"][$s]."%'
 		OR os.po_number  like '%".$searchBreakdown["searchword"][$s]."%' 
 )";
}

//if($_GET['page']=='doctor'){
//	$querypn .="and (o.status='for exam' OR  (o.doctor='".$_SESSION['id']."' and o.status='for exam' ) )";
//	
//	
//}
//elseif($_GET['page']=='doctor-complete'){
//	$querypn .=" and o.doctor='".$_SESSION['id']."' and status!='for exam' ";
//	
//	
//}
//elseif($_GET['page']=='dispatch'){
//	$querypn .=" and o.status='for payment' ";
//	
//	
//}
//elseif($_GET['page']=='mlist'){
//	$querypn .=" order by status_date ";
//	
//	
//}

$grabParams = array(
    'first_name',
    'last_name',
    'product_code',
    'prescription_id',
   
    'order_id',
   
	'color',
	'profile_id',
	'status',
	'item_description',
	'lens_option',
	'branch',
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
	'dispatch_type',
	'po_number',
	'orders_specs_id'
);


 $query =	$querypn;


$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
     mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 28; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrCustomerSearch[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

