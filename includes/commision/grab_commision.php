<?php 
// session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
// session_start();

///////////////////////////////////////////////// DATE SETTINGS

if(isset($_GET['date'])){

	if($_GET['date']=='month'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}
	elseif($_GET['date']=='yesterday'){

	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-t');

	}
    elseif($_GET['date']=='week'){

		$dateStart = date( 'Y-m-d', strtotime( 'sunday this week' ) );
		$dateEnd = date( 'Y-m-d', strtotime( 'saturday this week' ) );

	}
	elseif($_GET['date']=='custom'){

		$dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		$dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];

	}
	elseif($_GET['date']=='all-time'){

		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');

	}elseif($_GET['date']=='day'){

		$dateStart = date('Y-m-d');
		$dateEnd= date('Y-m-t');

	}

}
else{

	$dateStart = date('Y-m').'-1';
	$dateEnd= date('Y-m-t');

};

///////////////////////////////////////////////// STORES SETTINGS

if(isset($_GET['filterStores'])){

    if(  $_GET['filterStores']!=''){

        if($_GET['filterStores']=='787'){
                $docfilter = " ";
        }
        else{
            $docfilter = "   AND doctor!='' ";
        }
    $store_code = "  AND o.origin_branch= '".$_GET['filterStores']."'	";

    }
    else{
        $docfilter = "   AND doctor!='' ";
        $store_code = " 	";
    }

}
else{

    $store_code = " AND o.origin_branch= 'x'	";

};



if(isset($_GET['filterDoctor']) ){

    if(   $_GET['filterDoctor']!=''){
    $doctor = "  AND o.doctor='".$_GET['filterDoctor']."'	";
    }else{
        $doctor = " ";
    }

}
else{

    $store_code = "   AND o.doctor='x'	";

};




$arrComms = array();

 $query="SELECT os.po_number,
                
                         pay.total-1200,
                  
                emp.first_name,
                emp.middle_name,
                emp.last_name,
                emp.emp_id,
                o.origin_branch,
                sl.store_name,
                p51.item_name,
                os.po_number,
                os.lens_code,
                os.payment_date,
                os.status,
                os.remarks,
                os.received_stat,
                os.lab_status,
                os.dispatch_type,
                (SELECT os2.remarks from orders_specs os2 WHERE os2.po_number=os.old_po_number LIMIT 1),
                p51.price
                
                FROM orders_specs os 
                LEFT JOIN orders o ON o.order_id=os.order_id
                LEFT JOIN emp_table emp ON emp.emp_id = o.doctor 
                LEFT JOIN stores_locations sl ON sl.store_id=o.origin_branch
                LEFT JOIN poll_51 p51 ON p51.product_code = os.lens_code
                LEFT JOIN payments pay ON pay.po_number= os.po_number
                            WHERE 	 date(payment_date) >= '".$dateStart."'
                            AND date(payment_date) <= '".$dateEnd."'
                    AND os.status NOT IN ('failed','','return')
                      ".$docfilter. " 
                    AND os.lens_option='with prescription'

                  ".$store_code." 

                  ".$doctor."
                  AND o.order_id NOT LIKE '%_remake%'
                    	 
                    ORDER BY emp.last_name	
        ";
        // 'cancelled'
        // GROUP by os.lens_code,o.origin_branch,emp.emp_id
        // count(os.po_number),
$grabParams=array(
    'po_number',
   'price',
    'first_name',
    'middle_name',
    'last_name',
    'emp_id',
    'origin_branch',
    'store_name',
    'item_name',
   'count',
   'lens_code',
   'payment_date',
   'status',
   'os_remarks',
   'received_stat',
   'lab_status',
   'dispatch_type',
   'remarks_2',
   'p51_price'
    
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrComms[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};


?>