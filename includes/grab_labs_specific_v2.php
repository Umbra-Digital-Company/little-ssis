<?php 

// Set stores array
$arrLab = array();

$query =    "SELECT
                    ll.id,
                    ll.date_created,
                    ll.date_updated,
                    ll.lab_id,
                    ll.lab_pos_code,
                    ll.lab_name,
                    ll.address,
                    ll.province,
                    ll.city,
                    ll.barangay,
                    ll.zip_code,
                    ll.warehouse_code,
                    ll.email_address,
                    ll.phone_number,
                    sl.store_id,
                    sl.zone,
                    sl.store_name,
                    sl.address,
                    sl.province,
                    sl.city,
                    sl.barangay,
                    sl.phone_number,
                    sl.email_address,
                    sl.active
                FROM
                    labs_locations_test ll
                        LEFT JOIN stores_locations_test sl
                            ON sl.lab_id = ll.lab_id
                WHERE
                    ll.lab_id = '".$_GET['id']."'";

$grabParams = array(
    "id",
    "date_created",
    "date_updated",
    "lab_id",
    "lab_pos_code",
    "lab_name",
    "address",
    "province",
    "city",
    "barangay",
    "zip_code",
    "warehouse_code",
    "email_address",
    "phone_number", 
    "store_id",
    "store_zone",
    "store_name",
    "store_address",
    "store_province",
    "store_city",
    "store_barangay",
    "store_phone_number",    
    "store_email_address",    
    "store_active"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19, $result20, $result21, $result22, $result23, $result24);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrLab[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

?>
