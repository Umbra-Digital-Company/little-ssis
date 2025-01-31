<?php
if(!isset($_SESSION)){
        session_start();
    }

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

//Required files
//require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";

$arrCustomer = array();
$querypn="";

$querypn .=" SELECT  p.first_name,
p.middle_name,
p.last_name,
p.suffix_name,
p.gender
,os.product_code,
os.prescription_id,
os.order_id,
LOWER(u.first_name) as uf,
u.middle_name as um,
LOWER(u.last_name) as ul,

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
 os.lab_print_date,
 os.lab_production_date,
 os.lab_status_date,
 os.received_stat_date,
 os.payment_date,
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
 o.doctor,LOWER(d.first_name) as ofirst,
 LOWER(d.last_name) as olast
FROM profiles_info p
INNER JOIN orders_specs os on os.profile_id=p.profile_id
LEFT JOIN emp_table u on u.emp_id=p.sales_person

LEFT JOIN orders o on o.order_id=os.order_id 
LEFT JOIN emp_table d on d.emp_id=o.doctor
LEFT JOIN labs_locations ll on ll.lab_id=o.laboratory
where o.store_id!=''
";

if ($_GET['page']=='doctorque') {
	$querypn .= "	and ( o.origin_branch like '".$_SESSION["store_code"]."%'   or o.store_id like '".$_SESSION["store_code"]."%') and os.status = 'for exam' group by os.order_id  Order by o.date_created";
}
elseif($_GET['page']=='doctor'){
	$querypn .=" and (o.store_id like '".$_SESSION["store_code"]."%' OR o.origin_branch like '".$_SESSION["store_code"]."%')
	and date(os.date_created)  >='2019-07-01'
	and p.branch_applied!=''

	and (os.status='for exam' OR  (o.doctor='".$_SESSION['id']."' and os.status='for exam' )
	 OR os.status='change frame' or os.status='add frame' OR o.doctor = '' )
	and o.store_id!='' group by os.order_id  Order by os.status DESC,p.date_created ASC";
}
elseif($_GET['page']=='doctor-complete'){

	if(isset($_GET['search'])){
		$searchBreakdown["searchword"] = explode(" ", $_GET['search']);

				for($s=0;$s<sizeof($searchBreakdown["searchword"]);$s++){

					$querypn .=" and	
		(p.last_name like '%".$searchBreakdown["searchword"][$s]."%' OR p.first_name like  '%".$searchBreakdown["searchword"][$s]."%' OR p.middle_name like  '%".$searchBreakdown["searchword"][$s]."%'
		OR os.po_number  like '%".$searchBreakdown["searchword"][$s]."%'
		) ";}
	}

	$querypn .=" and os.lens_option!='without prescription' and (p.branch_applied='".$_SESSION["store_code"]."' OR  o.store_id like '".$_SESSION["store_code"]."%' )
	 and o.doctor='".$_SESSION['id']."' and os.status!='for exam' and status!='cancelled' 
	  ";

	 $querypn .=" ORDER BY o.date_updated DESC  limit 50 ";

}
elseif($_GET['page']=='dispatch'){	
	$querypn .="  and os.payment='y'  and store_id='".$_SESSION["store_code"]."'  and os.product_upgrade!='fashion_lens'  and status!='for exam'     ";
	
	if(isset($_GET['sort'])){
			if($_GET['sort']=='payment'){
				if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY payment   ";
				   }else{
				   
						$querypn .=" ORDER BY payment DESC  ";
					   }
				}
			elseif($_GET['sort']=='processed'){
				if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY lab_print   ";
				   }else{
				   
				$querypn .=" ORDER BY lab_print DESC  ";
				}
					}
			elseif($_GET['sort']=='production'){
				if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY lab_production   ";
				   }else{
				   
				$querypn .=" ORDER BY lab_production DESC  ";
				}
					}
			elseif($_GET['sort']=='completed'){
				if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY lab_status   ";
				   }else{
				$querypn .=" ORDER BY lab_status DESC  ";
				}
					}
				elseif($_GET['sort']=='received'){
					if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY received_stat   ";
				   }else{
				$querypn .=" ORDER BY received_stat DESC  ";
					}
				}
			elseif($_GET['sort']=='dispatched'){
				if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY store_dispatch   ";
				   }
				
				else{
				$querypn .=" ORDER BY store_dispatch DESC  ";
				}
					}
			elseif($_GET['sort']=='atoz'){
				if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY last_name   DESC  ";
				   }
				
				else{
				$querypn .=" ORDER BY last_name ";
				}
					}
		elseif($_GET['sort']=='lab'){
				if(isset($_GET['sort2']))
				   { 
					 	  	$querypn .=" ORDER BY lab_name   DESC  ";
				   }
				
				else{
				$querypn .=" ORDER BY lab_name ";
				}
					}
		
				}
	else{
			$querypn .=" ORDER BY o.date_updated DESC  ";
		
	}
	
	
	
}
elseif($_GET['page']=='mlist'){
	$querypn .="
	
	group by p.profile_id order by status_date   ";
	
	
}
elseif($_GET['page']=='profileinfo'){
	$querypn .="
	and p.profile_id='".$_GET['profile_id']."'
	 order by status_date   ";
	
	
}


$grabParams = array(
	'first_name',
	'middle_name',
    'last_name',
	'suffix_name',
	'gender',
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
	'target_date',
	'store_dispatch_date',
	'doctor',
	'ofirst',
	'olast'
);


$query =	$querypn;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, 
	$result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,
	$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34,$result35,$result36,$result37
	,$result38,$result39);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 39; $i++) { 

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