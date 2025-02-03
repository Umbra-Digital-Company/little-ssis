<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
///////////////////////////////////////////////////// CREDENTIALS

// PayMongo

// $paymongo_public_key = "pk_test_cneb24u2RWHDbXPUakK4FzYd"; // Sandbox
// $paymongo_secret_key = "sk_test_r8cV6GMv4WjXSBFG5cRWnf87"; // Sandbox

$paymongo_public_key = "pk_test_GdFTR4f26WAvVfZNQZMGzVDh"; // Sandbox
$paymongo_secret_key = "sk_test_8ziuyrY1wTexijUqQWCh9qCj"; // Sandbox

// $paymongo_public_key = ""; // Production
// $paymongo_secret_key = ""; // Production

//////////////////////////////////////////////////// Ninja Van

// Test
$ninja_van_client_id     = "af0c009b338f4ed1b40fc9257395f32a";
$ninja_van_client_secret = "175055c2cf924afe89bfd1207e14acf4";
$endpoint                = "https://api-sandbox.ninjavan.co" ;

// Production
// $ninja_van_client_id     = "";
// $ninja_van_client_secret = "";
// $endpoint                = "" ;

///////////////////////////////////////////////////// DATABASE

define('DB_SERVER', "165.232.164.207");
define('DB_USER', "root");
define('DB_PASSWORD', 'QU$I^Vty$3Jh5s8ZhVMYCABy%@YeKNAvx3GfXbaNYsNDtFf3zr1v$^');
define('DB_TABLE', "sunniess_specs");
//QU$I^Vty$3Jh5s8ZhVMYCABy%@YeKNAvx3GfXbaNYsNDtFf3zr1v$^
$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_TABLE);
if (mysqli_ping($conn)){} else{}

///////////////////////////////////////////////////// CHECK FOR LOCKED USERS

$locked = false;

if(isset($_SESSION['id'])) {

	$query = 	'SELECT
					locked,
					online
				FROM
					users
				WHERE
					id = "'.$_SESSION['id'].'"';

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 	$query)) {

		// mysqli_stmt_bind_param($stmt, 's', $locked);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);
        mysqli_stmt_fetch($stmt);
		mysqli_stmt_store_result($stmt);

		$lock_check = $result1;
		$online = $result2;

		mysqli_stmt_close($stmt);

	}

	if($lock_check == 'y') {

		$locked = true;

	};

};

if($locked) {

	header('location: /process/logout.php');
	exit;

};


if(isset($_SESSION['id'])) {

	$query = 	'SELECT
					locked,
					online
				FROM
					users
				WHERE
					id = "'.$_SESSION['id'].'"';

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 	$query)) {

		// mysqli_stmt_bind_param($stmt, 's', $locked);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);
        mysqli_stmt_fetch($stmt);
		mysqli_stmt_store_result($stmt);

		$lock_check = $result1;
		$online = $result2;

		mysqli_stmt_close($stmt);

	}

if($online=='0') {

	header('location: /process/logout.php');
	exit;

};

};
?>