<?php

//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrVariance = array();

if ( isset($_SESSION) ) {
	$aimQueryID = '';
} else {
	$aimQueryID = "";
}

 $query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 13 HOUR),
				i.reference_number,
				i.count as 'total_items',
				i.stock_from,
               CASE
                    WHEN i.stock_from = 'warehouse' THEN 'warehouse'
					WHEN i.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN i.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sl.store_name_proper != '' THEN LOWER(sl.store_name_proper)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				i.sender,
				et.first_name,
				et.last_name,
				isig.signature,
				i.type,
                i.variance,
				IF(
					i.store_id = '".$aimQueryID."',
					'in',
					'out'
				) AS 'direction',
                CASE
                    WHEN i.store_id = 'warehouse' THEN 'warehouse'
					WHEN i.store_id = 'hq' THEN 'Sunnies HQ'
					WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN sls.store_name_proper != '' THEN LOWER(sls.store_name_proper)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE ''
				END AS store_name_to,
                i.product_code,
                p51.item_name,
                i.actual_count,
                i.runner_count,
                i.status_date,
                i.receiver,
				ae.first_name,
				ae.last_name,
				isig.store_signature,
                i.delivery_unique,
				i.variance_status,
				i.variance
			FROM 
				inventory_face i
					LEFT JOIN inventory_signature_face isig
						ON isig.delivery_id = i.reference_number
					LEFT JOIN store_codes_face sl
						ON sl.store_code = i.stock_from
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
					LEFT JOIN emp_table et
						ON et.emp_id = i.sender
					LEFT JOIN admin_employee ae
						ON ae.emp_no = i.receiver
                    LEFT JOIN store_codes_face sls
						ON sls.store_code = i.store_id
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
                    LEFT JOIN poll_51_face p51 
                        ON p51.item_code=i.product_code
			WHERE
				i.reference_number='".$_GET["ref_num"]."'	
									
			
			ORDER BY
				i.date_created ASC";

$grabParams = array(
	'date_created',
	'reference_number',
	'count',
	'stock_from_id',
	'stock_from_branch',
	'sender_id',
	'sender_first_name',
	'sender_last_name',
	'signature',
    'type',
    'variance',
    'direction',
    'store_to_name',
    'product_code',
    'item_name',
    'actual_count',
    'runner',
    'status_date',
	'receiver',
	'receiver_firstname',
	'receiver_lastname',
	'receiver_signature',
	'delivery_unique',
	'variance_status',
	'variance'
);
	
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, 
    $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17,
     $result18, $result19, $result20, $result21, $result22, $result23, $result24, $result25);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParams); $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrVariance[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);	

};

?>