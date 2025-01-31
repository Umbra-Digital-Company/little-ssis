<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

////////////////////////////////////////////// GRAB POST DATA

// Generate Lab ID
$chars      = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
$lab_id 	= "";

for ($i=0; $i < 20; $i++) { 

	$lab_id .= $chars[rand(0, (strlen($chars)-1))];
	
};

$lab_name = str_replace(" ", "-", strtolower($_POST['lab_name']));
$lab_pos_code = $_POST['lab_pos_code'];
$lab_address = $_POST['address'];
$lab_province = str_replace(" ", "-", strtolower($_POST['province']));
$lab_city = str_replace(" ", "-", strtolower($_POST['city']));
$lab_barangay = str_replace(" ", "-", strtolower($_POST['barangay']));
$lab_warehouse_code = str_replace(" ", "-", strtolower($_POST['warehouse_code']));
$lab_zip_code = str_replace(" ", "-", strtolower($_POST['zip_code']));
$lab_email_address = $_POST['email_address'];
$lab_phone_number = $_POST['phone_number'];

////////////////////////////////////////////// INSERT INTO labs_locations

$query = 	'INSERT INTO
				labs_locations(
					lab_id,
					lab_pos_code,
					lab_name,
					address,
					province,
					city,
					barangay,
                    warehouse_code,
                    zip_code,
					email_address,
					phone_number
				)
			VALUES(
				"'.mysqli_real_escape_string($conn, $lab_id).'",
				"'.mysqli_real_escape_string($conn, $lab_pos_code).'",
				"'.mysqli_real_escape_string($conn, $lab_name).'",
				"'.mysqli_real_escape_string($conn, $lab_address).'",
				"'.mysqli_real_escape_string($conn, $lab_province).'",
				"'.mysqli_real_escape_string($conn, $lab_city).'",
				"'.mysqli_real_escape_string($conn, $lab_barangay).'",
                "'.mysqli_real_escape_string($conn, $lab_warehouse_code).'",
                "'.mysqli_real_escape_string($conn, $lab_zip_code).'",
				"'.mysqli_real_escape_string($conn, $lab_email_address).'",
				"'.mysqli_real_escape_string($conn, $lab_phone_number).'"
			)';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

echo '<script> window.location="/store-locations"; </script>';

?>
