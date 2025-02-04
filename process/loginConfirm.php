<?php 

session_save_path("/var/www/html/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";


// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

// Set variables
$msg = '';
$valid = true;

// Errors
if (trim($_POST['user'])=='') {

	$valid = false;
	$msg .= "* Enter username<br />";

};

if (trim($_POST['pass'])=='') {

	$valid = false;
	$msg .= "* Enter Password<br />";

};

// If POST is valid
if($valid){
		
	// Set username and password
	$username = $_POST['user'];
	$password = $_POST['pass']; 
	
	$query = 	"SELECT 
					s.username,
					s.id,
					s.isadmin,
					s.position,
					s.store_location,
					s.store_code,
					s.s_pass,
					s.password,
					s.user_type,
					s.store_type,
					uc.dispatch_studios0sunnies_studios
				FROM
					users s
					LEFT JOIN user_access uc ON s.id = uc.user_id
				WHERE
					s.username=?
						AND s.locked != 'y'";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 	$query)) {

		mysqli_stmt_bind_param($stmt, 's', $username);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);
        mysqli_stmt_fetch($stmt);
		mysqli_stmt_store_result($stmt);

		$username  		= $result1;
		$id				= $result2;
		$isadmin		= $result3;
		$position 		= $result4;
		$store_location	= $result5;
		$store_code		= $result6;
		$uSPass 		= $result7;
		$uPass 			= $result8;
		$user_type 		= $result9;
		$store_type 	= $result10;
		$dispatch_access= $result11;

		mysqli_stmt_close($stmt);

	}
	
	// Test password
	$tryP = $uSPass.$password;

	if(password_verify($tryP, $uPass)) {
			
		$_SESSION['userlvl'] 		 = $isadmin;
		$_SESSION['id'] 			 = $id;
		$_SESSION["store_code"]		 = $store_code;
		$_SESSION['login'] 			 = "YES";
		$_SESSION['dashboard_login'] = "YES";
		$_SESSION['user_type'] = $user_type;
		$_SESSION['store_type'] = $store_type;

		$_SESSION['user_login']['username'] 	   = $username;
		$_SESSION['user_login']['id'] 			   = $id;
		$_SESSION['user_login']['userlvl'] 		   = $isadmin;		
		$_SESSION['user_login']['position'] 	   = $position;
		$_SESSION['user_login']['store_location']  = $store_location;
		$_SESSION['user_login']['store_code'] 	   = $store_code;
		$_SESSION['user_login']['login'] 		   = "YES";
		$_SESSION['user_login']['dashboard_login'] = "YES";
		$_SESSION['dispatch_studios_no_access'] = ($dispatch_access == 1) ? false : true;

    	$query = "UPDATE
			users
		SET
			online = 1,
			date_log = now()
		WHERE
			username = '$username'";				

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {
		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);		
		}

		if($position == 'store' && $user_type == 1){
			$position = 'store-studios';
		}

		switch ($position) {

<<<<<<< HEAD
			case 'laboratory':
				echo '<script>	window.location.href="/list"; </script>';				
				break;
			
			case 'store':
				echo '<script>	window.location.href="/dispatch"; </script>';
				break;

			case 'store-studios':
				echo '<script>	window.location.href="/sis/studios/v1.0/?page=store-home"; </script>';
				break;

			case 'admin':
				echo '<script>	window.location.href="/dashboard/philippines"; </script>';				
				break;

			case 'vvm-admin':
				echo '<script>	window.location.href="/vvm/specs"; </script>';
				break;

			case 'vvm-studios':
				echo '<script>	window.location.href="/vvm/studios"; </script>';
				break;

			case 'vvm-specs':
				echo '<script>	window.location.href="/vvm/specs"; </script>';
				break;

			case 'vvm-user':
				echo '<script>	window.location.href="/vvm/specs"; </script>';
				break;

			case 'vvm-user-mixed':
				echo '<script>	window.location.href="/patient-profile"; </script>';
				break;

			case 'supervisor':
				echo '<script>	window.location.href="/dashboard/supervisor"; </script>';
				break;

			case 'profiler':
				echo '<script>	window.location.href="/patient-profile"; </script>';
				break;

			case 'aim-warehouse':
				echo '<script>	window.location.href="/inventory/warehouse/"; </script>';
				break;

			case 'aim-store':
				echo '<script>	window.location.href="/inventory/store/stock-movement/"; </script>';
				break;

			case 'aim-lab':
				echo '<script>	window.location.href="/inventory/lab/stock-movement/"; </script>';
				break;

			case 'aim-runner':
				echo '<script>	window.location.href="/inventory/runner/orders/"; </script>';
				break;

			case 'admin-distributor':
				echo '<script>	window.location.href="/distributors/dashboard/"; </script>';
				break;

			case 'aim-overseer':
				echo '<script>	window.location.href="/inventory/admin/"; </script>';
				break;

			case 'aim-auditor':
				echo '<script>	window.location.href="/inventory/audit/"; </script>';
				break;

			case 'aim-clerk':
				echo '<script>	window.location.href="/inventory/admin/history-all"; </script>';
				break;

			case 'dashboard':
			case 'dashboard-plus':
				echo '<script>	window.location.href="/dashboard/philippines/"; </script>';
				break;

			case 'accountant':
				echo '<script>	window.location.href="/lens-po-report/"; </script>';
				break;

			case 'vs-doctor':
				echo '<script>	window.location.href="/virtual-store/doctors/"; </script>';
				break;

			case 'vs-admin':
				echo '<script>	window.location.href="/products/"; </script>';
				break;

			case 'activator':
				echo '<script>	window.location.href="/system/poll-51/specs/"; </script>';
				break;

			case 'hr':
				echo '<script>	window.location.href="/employees/"; </script>';
				break;
			case 'vn-admin':
				echo '<script>	window.location.href="/dashboard/vietnam/"; </script>';
				break;
				case 'marketing':
					echo '<script>	window.location.href="/product-assortment/"; </script>';
					break;
					case 'sourcing':
						echo '<script>	window.location.href="/product-assortment/"; </script>';
						break;
						case 'merch':
							echo '<script>	window.location.href="/product-assortment/"; </script>';
							break;

		};
=======
				}
				else {

				    echo mysqli_error($conn);

				}

			// print_r($_SESSION);
		echo '<script>	window.location.href="/brand-select"; </script>';
>>>>>>> 455a2dc4... staging updates to live
	
	}
	else{
			
		echo '<p class="text-center text-danger">Invalid username or password</p>';

	}
	
}
else{
	
	echo '<p class="text-center text-danger">Invalid username or password</p>';

}

?>