<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";
if(!isset($_SESSION['user_login']['username'])) {
	header("Location: /");
    exit;
}

$msg = '';
$valid = true;

// Check Field if not empty
if (trim($_POST['user'])=='') {
	$valid=false;
	$msg = 'Sorry, the username or password is incorrect.';
}

// Check Field if not empty
if (trim($_POST['pass'])=='') {
	$valid=false;
	$msg = 'Sorry, the username or password is incorrect.';
}

// if valid
if($valid){	
	// Initialize Variable
	$username = strtolower($_POST['user']);	
	$password = $_POST['pass']; 

	// Select users
	$stmt = mysqli_stmt_init($conn);
	// Get User Details Query
  	$query="SELECT 
				LCASE(s.username),
				emp.first_name,										
				emp.middle_name,										
				emp.last_name,										
				s.id ,										
				s.isadmin,										
				s.position,										
				s.store_location,										
				s.store_code,												
				s.password,
				s.online
			FROM 									
				sunniess_specs.users2 s
			INNER JOIN 
				sunniess_specs.emp_table emp 
			ON 
			s.id = emp.emp_id
			WHERE 
				s.username=? 
					";

	// Run Query
	if (mysqli_stmt_prepare($stmt, 	$query)) {
		mysqli_stmt_bind_param($stmt, 's', $username);
		mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9,  $result10, $result11);
        mysqli_stmt_fetch($stmt);
		mysqli_stmt_store_result($stmt);

				$fullname 		= 	$result2."  ".$result4;
				$id				= 	$result5;
				$isadmin		=	$result6;
				$position 		=	$result7;
				$store_location	=	$result8;
				$store_code		=	$result9;
				$uPass 			= 	$result10;
				$online			= 	$result11;

		mysqli_stmt_close($stmt);
	}
	
	// Check if Password Entered Matched
	if(md5($password) == $result10) {

		if ( $online == '1' && $id == $_SESSION['id'] ) {
			// Error Message
			$msg = 'This account is already logged in.';
			echo $msg;
		} else {
			// Set User Active Query
			$queryLogActive = " UPDATE sunniess_specs.users2 SET
								online='1',
								date_log=now()
								where
								id='".$id."'
								";

			$stmt = mysqli_stmt_init($conn);
			// Run Query
			if (mysqli_stmt_prepare($stmt, $queryLogActive)) {
				mysqli_stmt_execute($stmt);		
			}

			// Set variables in Session
			$_SESSION['userlvl'] 		= $isadmin;
			$_SESSION['position'] 		= $position;
			$_SESSION['id'] 			= $id;
			$_SESSION['name'] 			= $fullname;
			//$_SESSION["store_code"]		= "103"; ///should be hardcoded per store
			$_SESSION['login'] 			= "YES";

			if($_SESSION['userlvl']=='1'){
				echo "<script>	window.location='./?page=doctor'; </script>";
			}elseif($_SESSION['userlvl']=='2'){
				echo "<script>	window.location='/sis/studios/".$_POST['path_loc']."/?page=store-home'; </script>";
			}
		}
		
	} else {			
		// Error Message
		$msg = 'Sorry, the username or password is incorrect.';
		echo $msg;
	}	
}
else{	
	// Return Message
	$msg = 'Login Successful';
	echo $msg;
}

?>