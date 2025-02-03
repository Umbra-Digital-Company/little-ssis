<?php

if(!isset($_SESSION)){

    session_start();

}

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

$arrCustomer = array();
$queOverlist ="";
$queOverlist .= 	"SELECT  
				p.first_name, 
				p.last_name,
				p.suffix_name,
				os.product_code,
				os.prescription_id,
				os.order_id,
				u.first_name as uf,
				u.middle_name as um,
				u.last_name as ul,
				
				p.profile_id,
				os.status,
				
				os.lens_option,
				lab_name,
 				os.lab_print,
 				os.lab_production,
 				os.lab_status,
 				os.received_stat,
 				os.store_dispatch,
 				
 				os.payment,
 				
 				os.id,
 				os.status as osstatus,
 				p.priority,
 				os.lab_remarks,
 				os.po_number,
 				os.dispatch_type,
 				os.orders_specs_id,
 				os.product_upgrade,
 				os.target_date,
 				os.store_dispatch_date,
 				os.synched,
				p.profile_synched,
				o.store_id,
				os.date_created
			FROM profiles_info p
				INNER JOIN orders_specs os 
					ON os.profile_id=p.profile_id
				LEFT JOIN emp_table u 
					ON u.emp_id=p.sales_person
				
				LEFT  JOIN orders o 
					ON o.order_id=os.order_id 
				LEFT JOIN labs_locations ll 
					ON ll.lab_id=o.laboratory
			WHERE 
				o.store_id != ''
					##AND os.payment = 'y'
					";
					##AND os.store_dispatch_date > DATE_ADD(NOW(), INTERVAL -3 DAY)
if(isset($_GET['search'])){
	
$searchBreakdown["searchword"] = explode(" ", $_GET['search']);
	if($_GET['search']==''){
	
			
		$queOverlist.= "AND store_id = '".$_SESSION["store_code"]."' 
		AND os.product_upgrade != 'fashion_lens'
		AND status != 'for exam'
		and status != 'cancelled'  ";
	
	}
	else{
		for($s=0;$s<sizeof($searchBreakdown["searchword"]);$s++){
			$queOverlist.=" and	
		(p.last_name like '%".$searchBreakdown["searchword"][$s]."%' OR p.first_name like  '%".$searchBreakdown["searchword"][$s]."%' OR p.middle_name like  '%".$searchBreakdown["searchword"][$s]."%'
		OR os.po_number  like '%".$searchBreakdown["searchword"][$s]."%'
		) ";
		}
	};

}else{
	$queOverlist.= "AND ( store_id = '".$_SESSION["store_code"]."'  OR  o.origin_branch='7747' OR  o.origin_branch='787' ) 
					AND os.product_upgrade != 'fashion_lens'
					AND status != 'for exam'
					and status != 'cancelled'  ";

}
	$queOverlist .= 	"	ORDER BY 
				o.date_created desc,o.date_updated ,lab_print,lab_production,lab_status,received_stat,store_dispatch DESC
				LIMIT  50
";
$querypn = $queOverlist;
$grabParams = array(

    'first_name',
	'last_name',
	'suffix_name',
    'product_code',
    'prescription_id',
   	'order_id',
    'uf',
	'um',
	'ul',
	
	'profile_id',
	'status',
	
	'lens_option',
	'lab_name',
	'lab_print',
	'lab_production',
	'lab_status',
	'received_stat',
	'store_dispatch',
	
	'payment',
	
	'id',
	'osstatus',
	'priority',
	'lab_remarks',
	'po_number',
	'dispatch_type',
	'orders_specs_id',
	'product_upgrade',
	'target_date',
	'store_dispatch_date',
	'synched',
	'profile_synched',
		'o_store_id',
		'os_date_created'

);

$query = $querypn;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15,
	 $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,
	 $result27,$result28,$result29,$result30,$result31,$result32,$result33);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

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