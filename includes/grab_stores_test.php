<?php 

// Check position
$userPosition = $_SESSION['user_login']['position'];

// Set stores array
$arrStore = array();

$querypn = "";
$querypn .= "SELECT 
                ll.lab_name,
                sl.store_id,
                sl.store_name,
                sl.address,
                sl.city,
                sl.province, 
                sl.barangay,
                sl.phone_number,
                sl.email_address,
                sl.id,
                sl.lab_id
            FROM 
                `stores_locations` sl
                    LEFT JOIN labs_locations ll 
                        ON ll.lab_id = sl.lab_id
            WHERE
                sl.active = 'y'";

// Laboratory
if($userPosition == 'laboratory'){
    
    $querypn .= " AND sl.lab_id = '".$_SESSION['store_code']."'";

};

$querypn .= " ORDER BY
                sl.store_name ASC";

$grabParams = array(

    'lab_name',
	'store_id',
	'store_name',
	'address',
	'city',
	'province',
	'barangay',
	'phone_number',
	'email_address',
	'id',
	'lab_id'

);

$query = $querypn;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrStore[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>
