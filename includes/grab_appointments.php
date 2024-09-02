<?php

if ( isset($_GET['date']) && $_GET['date'] != '' ) {
	$dateSet = $_GET['date'];
} else {
	$date = date('Y-m-d');
	$dateSet = date('Y-m-d', strtotime(str_replace('-', '/', $date)));
}

$store = $_SESSION['store_code'];
$arrAppointments = array();

if ( isset($_GET['search']) ) {

	$query =" SELECT
				soa.profile_id,
				soa.appointment_id,
				soa.store_appointment,
				LOWER(sl.store_name),
				soa.date_slot,
				soa.time_slot,
				soa.time_id,
				soa.first_name,
				soa.middle_initial,
				soa.last_name,
				soa.mobile_number
			FROM 
				so_appointment soa
			LEFT JOIN
				stores_locations sl
				ON sl.store_id = soa.store_appointment
			WHERE
				soa.store_appointment = '".$store."'
				AND soa.appointment_id =  '".$_GET['search']."'
				AND soa.status = 'pending'
			ORDER BY
				soa.time_id ASC
	";

} else {

	$query =" SELECT
				soa.profile_id,
				soa.appointment_id,
				soa.store_appointment,
				LOWER(sl.store_name),
				soa.date_slot,
				soa.time_slot,
				soa.time_id,
				soa.first_name,
				soa.middle_initial,
				soa.last_name,
				soa.mobile_number
			FROM 
				so_appointment soa
			LEFT JOIN
				stores_locations sl
				ON sl.store_id = soa.store_appointment
			WHERE
				soa.store_appointment = '".$store."'
				AND soa.status <> 'cancelled'
				AND soa.date_slot =  '".$dateSet."'
				AND soa.status = 'pending'
			ORDER BY
				soa.time_id ASC
	";

}

$grabparams= array(
	'profile',
	'appointment_id',
	'store_code',
	'store',
	'date',
	'time',
	'slot',
	'first_name',
	'middle_name',
	'last_name',
	'mobile'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

while (mysqli_stmt_fetch($stmt)) {

	$tempArray = array();

	for ($i=0; $i < sizeOf($grabparams); $i++) { 

		$tempArray[$grabparams[$i]] = ${'result' . ($i+1)};

	};

	$arrAppointments [] = $tempArray;

};

mysqli_stmt_close($stmt);    
							
}
else {

echo mysqli_error($conn);

}; 

?>