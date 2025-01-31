<?php

$profile = $_GET['profile'];
$appointmentID = $_GET['id'];

$customerData = array();

$query =" SELECT
			soa.profile_id,
			soa.order_id,
			soa.appointment_id,
			soa.store_appointment,
			LOWER(sl.store_name),
			soa.date_slot,
			soa.time_slot,
			soa.time_id,
			soa.first_name,
			soa.middle_initial,
			soa.last_name,
			soa.mobile_number,
			pi.email_address,
			pi.gender,
			pi.birthday,
			pi.province,
			pi.city,
			pi.barangay,
			pi.age,
			pi.branch_applied,
			pi.joining_date,
			pi.address,
			os.po_number
		FROM 
			so_appointment soa
		LEFT JOIN
			stores_locations sl
			ON sl.store_id = soa.store_appointment
		LEFT JOIN
			profiles_info pi
			ON pi.profile_id = soa.profile_id
		LEFT JOIN
			orders_specs_test os
			ON os.order_id = soa.order_id
		WHERE
			soa.profile_id = '".$profile."'
			AND soa.appointment_id =  '".$appointmentID."'
		ORDER BY
			soa.time_id ASC
";

$grabparams= array(
	'profile',
	'orderID',
	'appointment_id',
	'store_code',
	'store',
	'date',
	'time',
	'slot',
	'first_name',
	'middle_name',
	'last_name',
	'mobile',
	'email',
	'gender',
	'birthday',
	'province',
	'city',
	'barangay',
	'age',
	'branch_applied',
	'joining_date',
	'address',
	'po_number'
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23);

while (mysqli_stmt_fetch($stmt)) {

	$tempArray = array();

	for ($i=0; $i < sizeOf($grabparams); $i++) { 

		$tempArray[$grabparams[$i]] = ${'result' . ($i+1)};

	};

	$customerData [] = $tempArray;

};

mysqli_stmt_close($stmt);    
							
}
else {

echo mysqli_error($conn);

}; 

?>