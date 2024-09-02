<?php 

session_save_path("/var/www/html/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

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
					ua.default_page
				FROM
					users s
				LEFT JOIN
					user_access ua ON s.id = ua.user_id
				WHERE
					s.username=?
				AND 
					s.locked != 'y'";

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, 	$query)) {

		mysqli_stmt_bind_param($stmt, 's', $username);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);
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
		$default_page   = $result10;

		mysqli_stmt_close($stmt);

	}
	
	// Test password
	$tryP = $uSPass.$password;

	if(password_verify($tryP, $uPass)) {

		//SET ONLINE TO 1
	    $query = 'UPDATE
			users
		SET
			online = "1",
		WHERE
			id = '.$id;				

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {
		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);		
		}

			
		$_SESSION['userlvl'] 		 = $isadmin;
		$_SESSION['id'] 			 = $id;
		$_SESSION["store_code"]		 = $store_code;
		$_SESSION['login'] 			 = "YES";
		$_SESSION['dashboard_login'] = "YES";
		$_SESSION['user_type'] = $user_type;

		$_SESSION['user_login']['username'] 	   = $username;
		$_SESSION['user_login']['id'] 			   = $id;
		$_SESSION['user_login']['userlvl'] 		   = $isadmin;		
		$_SESSION['user_login']['position'] 	   = $position;
		$_SESSION['user_login']['store_location']  = $store_location;
		$_SESSION['user_login']['store_code'] 	   = $store_code;
		$_SESSION['user_login']['login'] 		   = "YES";
		$_SESSION['user_login']['dashboard_login'] = "YES";

		switch ($default_page) {

			case 'laboratory|ssis':
				echo '<script>	window.location.href="/list"; </script>';				
				break;

			case 'affiliate|dashboard':
				echo '<script>	window.location.href="/dashboard/affiliate"; </script>';				
				break;

			case 'vietnam|dashboard':
				echo '<script>	window.location.href="/dashboard/vietnam"; </script>';				
				break;

			case 'virtual_store_ph|dashboard':
				echo '<script>	window.location.href="/dashboard/shopify-ph"; </script>';				
				break;

			case 'virtual_store_int|dashboard':
				echo '<script>	window.location.href="/dashboard/shopify-int"; </script>';				
				break;

			case 'dashboard|dashboard':
				echo '<script>	window.location.href="/dashboard/supervisor"; </script>';				
				break;

			case 'promo_code|dashboard':
				echo '<script>	window.location.href="/dashboard/promocode"; </script>';				
				break;

			case 'dispatch|ssis':
				echo '<script>	window.location.href="/dispatch"; </script>';
				break;

			case 'philippines|dashboard':
				echo '<script>	window.location.href="/dashboard/philippines"; </script>';				
				break;

			case 'sunnies_specs_vvm|vvm':
				echo '<script>	window.location.href="/vvm/specs"; </script>';
				break;

			case 'sunnies_studio|vvm':
				echo '<script>	window.location.href="/vvm/studios"; </script>';
				break;

			case 'sunnies_specs_vvm|vvm':
				echo '<script>	window.location.href="/vvm/specs"; </script>';
				break;

			case 'sunnies_specs_vvm|vvm':
				echo '<script>	window.location.href="/vvm/specs"; </script>';
				break;

			case 'patient_profile|maintenance':
				echo '<script>	window.location.href="/patient-profile"; </script>';
				break;

			case 'dashboard|dashboard':
				echo '<script>	window.location.href="/dashboard/supervisor"; </script>';
				break;

			case 'patient_profile|maintenance':
				echo '<script>	window.location.href="/patient-profile"; </script>';
				break;

			case 'stock_movement_warehouse|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/stock-movement/"; </script>';
				break;

			case 'stock_transfer_warehouse|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/stock-transfer/"; </script>';
				break;

			case 'good_issue_warehouse|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/pullout/"; </script>';
				break;

			case 'receive_warehouse|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/receive/"; </script>';
				break;

			case 'inventory_request_warehouse|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/request/"; </script>';
				break;

			case 'variance_report_warehouse|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/repoerts/"; </script>';
				break;

			case 'history_store|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/history/"; </script>';
				break;

			case 'history_all_warehouse|main_menu':
				echo '<script>	window.location.href="/inventory/warehouse/history-all/"; </script>';
				break;

			case 'stock_movement_store|main_menu':
				echo '<script>	window.location.href="/inventory/store/stock-movement/"; </script>';
				break;

			case 'stock_movement_lab|main_menu':
				echo '<script>	window.location.href="/inventory/lab/stock-movement/"; </script>';
				break;

			case 'stock_movement_runner|main_menu':
				echo '<script>	window.location.href="/inventory/runner/orders/"; </script>';
				break;

			case 'dashboard_dist|distributors':
				echo '<script>	window.location.href="/distributors/dashboard/"; </script>';
				break;

			case 'stock_transfer_admin|main_menu':
				echo '<script>	window.location.href="/inventory/admin/"; </script>';
				break;

			case 'stock_movement_auditor|main_menu':
				echo '<script>	window.location.href="/inventory/audit/"; </script>';
				break;

			case 'history_all_admin|main_menu':
				echo '<script>	window.location.href="/inventory/admin/history-all"; </script>';
				break;

			case 'philippines|dashboard':
			case 'philippines|dashboard':
				echo '<script>	window.location.href="/dashboard/philippines/"; </script>';
				break;

			case 'lens_po_report|ssis':
				echo '<script>	window.location.href="/lens-po-report/"; </script>';
				break;

			case 'patient_lookup|virtual_store':
				echo '<script>	window.location.href="/virtual-store/doctors/"; </script>';
				break;

			case 'products|products':
				echo '<script>	window.location.href="/products/"; </script>';
				break;

			case 'sunnies_specs_poll_51|poll_51':
				echo '<script>	window.location.href="/system/poll-51/specs/"; </script>';
				break;

			case 'employees|hr':
				echo '<script>	window.location.href="/employees/"; </script>';
				break;

		};
	
	}
	else{
			
		echo '<p class="text-center text-danger">Invalid username or password</p>';

	}
	
}
else{
	
	echo '<p class="text-center text-danger">Invalid username or password</p>';

}

?>