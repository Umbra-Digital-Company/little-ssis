<?php 

$arrMessageLatest = array();
	// #DATE_ADD(rc.date_created,INTERVAL 12 HOUR),
	// 			#DATE_ADD(rc.date_updated,INTERVAL 12 HOUR),
$queryLatest= 	"SELECT 
				DATE_ADD(rc.date_created, INTERVAL 12 hour),
				DATE_ADD(rc.date_updated, INTERVAL 12 hour),
			
				rc.order_po_id,
				rc.profile_id,
				rc.message,
				rc.message_id,
				u.first_name,
				u.last_name,
				s.first_name,
				s.last_name,
                os.po_number
			FROM 
				remarks_comm rc
					LEFT JOIN emp_table u 
						ON u.emp_id=rc.profile_id
			 		LEFT JOIN users s 
			 			ON s.id=rc.profile_id
                    LEFT JOIN 
                        orders_specs os  ON os.orders_specs_id=rc.order_po_id
                    LEFT JOIN
                        orders o ON o.order_id=os.order_id
                    WHERE
                            rc.profile_id!='".$_SESSION['id']."'
                    AND
                            rc.seen!='y'
                    AND 
                            os.status!='cancelled'
                    AND
                       ( o.origin_branch LIKE '".$_SESSION['store_code']."%'

                            OR 
                        os.stock_from ='".$_SESSION['store_code']."'
                        )
                        
			ORDER BY 
                rc.date_created DESC 
                LIMIT 40
                "; 
                


$grabParams = array(

	'date_created',
	'date_updated',
	'order_po_id',
	'profile_id',
	'message',
	'message_id',
	'first_name',
	'last_name',
	'l_first_name',
    'l_last_name',
    'po_number'

);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryLatest)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10,$result11);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrMessageLatest[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 
?>