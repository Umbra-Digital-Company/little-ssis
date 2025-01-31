<?php 



$arrCustomer = array();


$arrSearch = explode(" ", $_GET['search']);

$querySearch = "";

if(isset($_GET['search']) && $_GET['search'] != '') {

	for ($i=0; $i < sizeOf($arrSearch); $i++) { 
	
		$querySearch .= " AND (
							p.last_name like '%".$arrSearch[$i]."%' 
								OR p.first_name like '%".$arrSearch[$i]."%' 
								OR p.middle_name like  '%".$arrSearch[$i]."%'
	 							OR os.po_number like '%".$arrSearch[$i]."%' 
	 					)";

	};	

}
else {

	$querySearch .= " AND os.date_created > DATE_ADD(NOW(), INTERVAL -3 MONTH)";

};

$query="SELECT  
            p.first_name,
            p.last_name,
            os.product_code,
            os.prescription_id,
            os.order_id,
            p.profile_id,
            os.status,
            os.lens_option,
            sc.branch,
            ll.lab_name,
            os.lab_print,
            os.lab_production,
            os.lab_status,
            os.received_stat,
            os.store_dispatch,

            os.payment,
            DATE_ADD(os.lab_print_date,INTERVAL 12 HOUR),
            DATE_ADD(os.lab_production_date,INTERVAL 12 HOUR),
            DATE_ADD(os.lab_status_date,INTERVAL 12 HOUR),
            DATE_ADD(os.received_stat_date,INTERVAL 12 HOUR),
            os.payment_date,
            os.id,
            os.dispatch_type,
            os.po_number,
            os.orders_specs_id,
            os.target_date,
            DATE_ADD(os.store_dispatch_date,INTERVAL 12 HOUR),
            (
                SELECT 
                    prescription_id 
                FROM 
                    profiles_prescription 
                WHERE 
                    id = os.prescription_id 
                    AND profile_id=os.profile_id
            ) AS prescription_check,
            DATE_ADD( os.date_created,INTERVAL 12 HOUR)
            FROM 
            profiles_info p
                LEFT JOIN orders_specs_test os 
                    ON os.profile_id=p.profile_id
                LEFT JOIN users u 
                    ON u.id=p.sales_person
                LEFT  JOIN orders_test o 
                    ON o.order_id=os.order_id 
                LEFT JOIN  store_codes sc 
                    ON sc.location_code=o.store_id
                LEFT JOIN stores_locations sl
                    ON sl.store_id = sc.location_code
                LEFT JOIN labs_locations ll 
                    ON ll.lab_id=sl.lab_id
            WHERE 

            (status='for confirmation'
                OR
                (
                status='downloaded'
                OR DATE_ADD(os.status_date, INTERVAL 12 HOUR) > DATE_ADD(NOW(), INTERVAL -1 HOUR) 
                )
            )
            AND os.status!='for exam'
            AND os.status!='for payment'
            AND os.status!='cancelled'
            AND   ( o.origin_branch='7747' OR o.origin_branch='787')
            AND os.po_number!=''
            AND os.orders_specs_id!=''
            AND payment='n'
                ".$querySearch."
             ORDER BY  os.`date_created` DESC
                ";

                
        $grabParams = array(

            'first_name',
            'last_name',
            'product_code',
            'prescription_id',   
            'order_id',   
            'profile_id',
            'status',
            'lens_option',
            'branch',
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
            'dispatch_type',
            'po_number',
            'orders_specs_id',
            'target_date',
            'store_dispatch_date',
            'prescription_check',
            'date_created'
        
        );

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6,
     $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15,
      $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29);

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