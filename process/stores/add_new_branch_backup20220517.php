<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/grab_stores.php";

////////////////////////////////////////////// GRAB POST DATA

$store_id = $_POST['store_id'];
$store_name = strtoupper($_POST['store_name']);
$store_lab_id = $_POST['lab_id'];
$store_address = $_POST['address'];
$store_province = str_replace(" ", "-", strtolower($_POST['province']));
$store_city = str_replace(" ", "-", strtolower($_POST['city']));
$store_barangay = str_replace(" ", "-", strtolower($_POST['barangay']));
$store_email_address = $_POST['email_address'];
$store_phone_number = $_POST['phone_number'];

////////////////////////////////////////////// CHECK IF STORE ID EXISTS

// Cycle through stores array
for ($i=0; $i < sizeOf($arrStore); $i++) { 

	$curStoreID = $arrStore[$i]['store_id'];

	if($curStoreID == $store_id) {

		$_SESSION['store']['error'] = 'Store ID already exists!';
		header("location: ".$_SERVER['HTTP_REFERER']);
		exit;

	}
	else {

		unset($_SESSION['store']['error']);

	};
	
};

////////////////////////////////////////////// INSERT INTO stores_locations

$query = 	'INSERT INTO
				stores_locations(
					store_id,
					lab_id,
					zone,
					store_name,
					country,
					address,
					province,
					city,
					barangay,
					phone_number,
					email_address,
					active
				)
			VALUES(
				"'.mysqli_real_escape_string($conn, $store_id).'",
				"'.mysqli_real_escape_string($conn, $store_lab_id).'",
				"",
				"'.mysqli_real_escape_string($conn, $store_name).'",
				"philippines",
				"'.mysqli_real_escape_string($conn, $store_address).'",
				"'.mysqli_real_escape_string($conn, $store_province).'",
				"'.mysqli_real_escape_string($conn, $store_city).'",
				"'.mysqli_real_escape_string($conn, $store_barangay).'",
				"'.mysqli_real_escape_string($conn, $store_phone_number).'",
				"'.mysqli_real_escape_string($conn, $store_email_address).'",				
				"y"
			)';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

};

echo '<script> window.location="/store-locations/"; </script>';

?>
