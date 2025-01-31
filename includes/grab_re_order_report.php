<?php 
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

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

	}

}
else{

	$dateStart = date('Y-m').'-1';
	$dateEnd= date('Y-m-t');

};

///////////////////////////////////////////////// STORES SETTINGS

if(isset($_GET['filterStores']) &&  $_GET['filterStores']!=''){

    $store_code = $_GET['filterStores'];

}
else{

    $store_code = '';

};

///////////////////////////////////////////////// GRAB REORDERS

$arrReorder = array();

 $query = 	"SELECT 
				os.date_created,
				os.date_updated,
				os.po_number,
				IF(
					os.old_po_number IS NOT NULL,
					os.old_po_number,
					q.po_number
				),
				emp.emp_id,
				emp.first_name,
				emp.middle_name,
				emp.last_name,
				os.profile_id,
				os.price,
				o.origin_branch,
				os.`status`,
				os.dispatch_type 				
			FROM 
				orders_specs os
					LEFT JOIN orders o 
						ON o.order_id=os.order_id
					LEFT JOIN emp_table emp
						ON emp.emp_id = o.doctor
					LEFT JOIN (
						SELECT 
							a.date_created,
							a.date_updated,
							a.po_number,
							a.profile_id
						FROM 
							orders_specs a
						WHERE 
							a.status = 'return'
								AND a.order_id like '".$store_code."-%'		
						ORDER BY 
							a.date_created DESC
					) AS q
						ON q.profile_id = os.profile_id
							AND DATE_FORMAT(q.date_updated, '%Y-%m-%d') <= DATE_FORMAT(DATE_ADD(os.date_created, INTERVAL 3 HOUR), '%Y-%m-%d')
							AND DATE_FORMAT(q.date_updated, '%Y-%m-%d') >= DATE_FORMAT(DATE_ADD(os.date_created, INTERVAL -3 HOUR), '%Y-%m-%d')
			WHERE 
				(dispatch_type = 're-order' or os.status='return')
					AND date(payment_date) >= '".$dateStart."'
					AND date(payment_date) <= '".$dateEnd."'
					AND o.order_id like '".$store_code."-%'			
			ORDER BY 
				date_created DESC";

$grabParams = array(
	'date_created',
	'date_updated',
	'po_number',
	'original_po_number',
	'employee_id',
	'first_name',
	'middle_name',
	'last_name',
	'profile_id',
	'price',
	'origin_branch',
	'status',
	'dispatch_type'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrReorder[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

// echo '<pre>';
// echo $query;
// echo '</pre>';
// echo '<pre>';
// print_r($arrReorder);
// echo '</pre>';

?>