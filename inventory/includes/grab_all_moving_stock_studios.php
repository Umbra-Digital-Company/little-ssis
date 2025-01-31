<?php

if(isset($_GET['date'])){
	if($_GET['date']=='month'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	elseif($_GET['date']=='yesterday'){
	 	$dateStart = date('Y-m-d',strtotime("-1 days"));
	 	$dateEnd= date('Y-m-t');
	}elseif($_GET['date']=='week'){
		$dateStart = date( 'Y-m-d', strtotime( 'monday this week' ) );
		 $dateEnd = date( 'Y-m-d', strtotime( 'sunday this week' ) );
	}
	elseif($_GET['date']=='custom'){
		 $dateStart = $_GET['data_range_start_year']."-".$_GET['data_range_start_month']."-".$_GET['data_range_start_day'];
		 $dateEnd = $_GET['data_range_end_year']."-".$_GET['data_range_end_month']."-".$_GET['data_range_end_day'];
	}
	elseif($_GET['date']=='all-time'){
		$dateStart = date('Y-m').'-1';
		$dateEnd= date('Y-m-t');
	}
	else{
		$dateStart = date('Y-m-d');
			$dateEnd= date('Y-m-t');
	}
	
}
else{
	$dateStart = date('Y-m-1');
		$dateEnd= date('Y-m-t');
}
//////////////////////////////////////////////////////////////////////////////////// GRAB RECEIVABLES

$arrReceivable = array();

if ( isset($_SESSION) ) {
	$aimQueryID = $_SESSION['store_code'];
} else {
	$aimQueryID = "";
}

 $query =    "SELECT 
				DATE_ADD(i.date_created, INTERVAL 12 HOUR),
				i.reference_number,
				IF(
					i.store_id = '".$aimQueryID."',
					'in',
					'out'
				) AS 'direction',
				i.type,
				SUM(i.count) AS 'total_items',
				SUM(i.actual_count) AS 'total_items_received',
				i.status,
				i.store_id,
				CASE
                    WHEN i.store_id = 'warehouse' THEN 'warehouse'
					WHEN i.store_id = 'hq' THEN 'Sunnies HQ'
					WHEN i.store_id = 'warehouse_damage' THEN 'warehouse damage'
					WHEN i.store_id = 'warehouse_qa' THEN 'warehouse QA'
					WHEN i.store_id = 'manufacturer' THEN 'manufacturer'
					WHEN sls.store_name_proper != '' THEN LOWER(sls.store_name_proper)
					WHEN lls.lab_name != '' THEN LOWER(lls.lab_name)
					ELSE i.store_id
				END AS store_name_to,
				i.stock_from,
				CASE
					WHEN i.stock_from = 'warehouse' THEN 'warehouse'
					WHEN i.stock_from = 'hq' THEN 'Sunnies HQ'
					WHEN i.stock_from = 'warehouse_damage' THEN 'warehouse damage'
					WHEN i.stock_from = 'warehouse_qa' THEN 'warehouse QA'
					WHEN i.stock_from = 'manufacturer' THEN 'manufacturer'
					WHEN sl.store_name_proper != '' THEN LOWER(sl.store_name_proper)
					WHEN ll.lab_name != '' THEN LOWER(ll.lab_name)
					ELSE ''
				END AS store_name_from,
				i.admin_id,
				i.admin_name,
				i.sender,
				ae.first_name,
				ae.last_name
			FROM 
				inventory_studios i
					LEFT JOIN store_codes_studios sl
						ON sl.store_code = i.stock_from
                    LEFT JOIN store_codes_studios sls
						ON sls.store_code = i.store_id
					LEFT JOIN labs_locations ll
						ON ll.lab_id = i.stock_from
					LEFT JOIN labs_locations lls
						ON lls.lab_id = i.store_id
					LEFT JOIN admin_employee ae
						ON ae.emp_no = i.sender";
				if ($aimQueryID=='overseer') {
					$query .= " WHERE i.requested='y' ";
				} elseif (isset($_GET['date'])) {

					// filter date
					$query .= " WHERE DATE(DATE_ADD(i.date_created, INTERVAL 13 HOUR)) >= '".$dateStart."' AND DATE(DATE_ADD(i.date_created, INTERVAL 13 HOUR)) <= '".$dateEnd."' ";
					
					// filter process type
					if (isset($_GET['filterProcess']) && $_GET['filterProcess']!='all') {

						// get all incoming stock
						if ($_GET['filterProcess']=='in') {

							// get all specific sender
							if (isset($_GET['filterSender']) && $_GET['filterSender']!='') {
								$query .= " AND i.stock_from = '".$_GET['filterSender']."' AND i.store_id = '".$aimQueryID."' ";
							} else { // else get all sender
								$query .= " AND i.store_id = '".$aimQueryID."' ";
							}
						}

						// get all outgoing stock
						elseif ($_GET['filterProcess']=='out') {

							// get all specific receiver
							if (isset($_GET['filterReceiver']) && $_GET['filterReceiver']!='') {
								$query .= " AND i.store_id = '".$_GET['filterReceiver']."' AND i.stock_from = '".$aimQueryID."'";
							} else { // else get all receiver
								$query .= " AND i.stock_from = '".$aimQueryID."' ";
							}
						}
					} else { // get all incoming and outgoing stock
						$query .= " AND ( i.store_id = '".$aimQueryID."' OR i.stock_from = '".$aimQueryID."' ) ";
					}

					// filter transaction type
					if (isset($_GET['filterType']) && $_GET['filterType']!='all') {
						$query .= " AND i.type = '".$_GET['filterType']."' ";
					}

					// filter status
					if (isset($_GET['filterStatus']) && $_GET['filterStatus']!='all') {
						$query .= " AND i.status = '".$_GET['filterStatus']."' ";
					}
				} else {
					$query .= " WHERE (i.store_id = '".$aimQueryID."' OR i.stock_from = '".$aimQueryID."' )
					 AND DATE(DATE_ADD(i.date_created, INTERVAL 13 HOUR)) >= '".$dateStart."' AND DATE(DATE_ADD(i.date_created, INTERVAL 13 HOUR)) <= '".$dateEnd."'
					
					
					
					";
				}
			$query .= "
				AND i.status!='cancelled'
			GROUP BY
				i.reference_number
			ORDER BY				
				i.date_created DESC,
				i.delivery_unique ASC";

$grabParams = array(
	'date_created',
	'reference_number',
	'direction',
	'type',
	'total_items',
	'total_items_received',
	'status',
	'stock_to_id',
	'stock_to_name',
	'stock_from_id',
	'stock_from_branch',
	'admin_id',
	'admin_name',
	'sender_id',
	'sender_first_name',
	'sender_last_name',
);
	
$stmt = mysqli_stmt_init($conn);

$querylast =$query;
if (mysqli_stmt_prepare($stmt, $querylast)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16);

	while (mysqli_stmt_fetch($stmt)) {

		$tempArray = array();

		for ($i=0; $i < sizeOf($grabParams); $i++) { 

			$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

		};

		$arrReceivable[] = $tempArray;

	};

	mysqli_stmt_close($stmt);    
							
}
else {

	echo mysqli_error($conn);	

};

?>