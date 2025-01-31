<?php 

include("../connect.php");
if(!isset($_SESSION)){
        session_start();
    }

$searchBreakdown["searchword"] = explode(" ", $_GET['s']);
//
//print_r($searchBreakdown);

$searchUser =array();

$querypn="";

 $querypn .="SELECT  p.first_name, p.last_name,p.suffix_name,os.product_code,os.prescription_id,os.order_id,u.first_name as uf,u.middle_name as um,u.last_name as ul,pr.color,p.profile_id,os.status,pr.item_description,os.lens_option,lab_name,
 os.lab_print,os.lab_production,os.lab_status,os.received_stat,os.store_dispatch,os.signature,os.payment,os.lab_print_date,os.lab_production_date,os.lab_status_date,os.received_stat_date,os.payment_date,os.id,os.status as osstatus,p.priority,os.lab_remarks,os.po_number,os.dispatch_type,os.orders_specs_id,os.product_upgrade,
 os.synched,p.profile_synched
FROM profiles_info p
INNER JOIN orders_specs os on os.profile_id=p.profile_id
LEFT JOIN users u on u.id=p.sales_person
LEFT JOIN products pr on pr.product_code=os.product_code  
LEFT  JOIN orders o on o.order_id=os.order_id 
LEFT JOIN labs_locations ll on ll.lab_id=o.laboratory
where o.store_id != ''
";
	if($_GET['s']==''){
		$querypn .="
			
			
			AND branch_applied = '".$_SESSION["store_code"]."' 
			AND os.product_upgrade != 'fashion_lens'
			AND status != 'for exam'
			and status != 'cancelled'
		";

	}
	else{
		for($s=0;$s<sizeof($searchBreakdown["searchword"]);$s++){
		$querypn .=" and	
		(p.last_name like '%".$searchBreakdown["searchword"][$s]."%' OR p.first_name like  '%".$searchBreakdown["searchword"][$s]."%' OR p.middle_name like  '%".$searchBreakdown["searchword"][$s]."%'
		OR os.po_number  like '%".$searchBreakdown["searchword"][$s]."%'
		) ";
		}
	}
 $querySearch = $querypn;

$grabParamsUser = array(
   'first_name',
	'last_name',
	'suffix_name',
    'product_code',
    'prescription_id',
   	'order_id',
    'uf',
	'um',
	'ul',
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
	'osstatus',
	'priority',
	'lab_remarks',
	'po_number',
	'dispatch_type',
	'orders_specs_id',
	'product_upgrade',
	'synched',
	'profile_synched'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querySearch)) {
    mysqli_stmt_execute($stmt);
       mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29
							,$result30,$result31,$result32,$result33,$result34,$result35,$result36,$result37);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 37; $i++) { 

            $tempArray[$grabParamsUser[$i]] = ${'result' . ($i+1)};

        };

        $searchUser[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};



?>