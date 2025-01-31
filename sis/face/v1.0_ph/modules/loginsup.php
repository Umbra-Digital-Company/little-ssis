<?php

include("connect.php");
if(!isset($_SESSION)){
    session_start();
}
$msg = '';
$valid = true;

// if valid
if($valid){	
	// Initialize Variable
	$username = strtolower($_POST['user']);	
	$password = $_POST['pass']; 

	// Select users
	$stmt = mysqli_stmt_init($conn);
	// Get User Details Query
	$arrLogin=array();
	$grabParams= array(
		'username',
		'first_name',										
		'middle_name',										
		'last_name',										
		'id' ,										
		'isadmin',										
		'position',										
		'store_location',										
		'store_code',
		's_pass',												
		'password',
		'user_type'

	);

  	$query="SELECT 
				LCASE(s.username),
				LOWER(s.first_name),										
				LOWER(s.middle_name),										
				LOWER(s.last_name),										
				s.id,						
				s.isadmin,										
				s.position,										
				s.store_location,										
				s.store_code,
				s.s_pass,												
				s.password,
				s.user_type
			FROM 									
				users s
			WHERE 
				s.username='".$username."';";

	// Run Query
	
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);
	
		while (mysqli_stmt_fetch($stmt)) {
	
			$tempArray = array();
	
			for ($i=0; $i < sizeOf($grabParams); $i++) { 
	
				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
	
			};
	
			$arrLogin[] = $tempArray;
	
		};
	
		mysqli_stmt_close($stmt);    
								
	}
	else {
	
		echo mysqli_error($conn);
	
	}; 
	 //print_r($arrLogin);exit;
	// Test password
	$tryP = $arrLogin[0]["s_pass"].$password;
	
	if(password_verify($tryP,$arrLogin[0]["password"])) {
		// Set User Active Query
		$queryLogActive = " UPDATE users SET
							online='1',
							date_log=now()
							where
							id='".$arrLogin[0]['id']."'
							";

		$stmt = mysqli_stmt_init($conn);
		// Run Query
		if (mysqli_stmt_prepare($stmt, $queryLogActive)) {
			mysqli_stmt_execute($stmt);		
		}



		$querylogs = 	'INSERT INTO  
		order_status(order_id,status,status_date,branch,updatee)
		VALUES("'.$arrLogin[0]['id'].'", "Login"
		, now(),"'.$_SESSION['store_code'].'","'.$arrLogin[0]['id'].'")';

		$stmt = mysqli_stmt_init($conn);
		if(mysqli_stmt_prepare($stmt, $querylogs)) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_close($stmt);

		} 


		// Set variables in Session
		$_SESSION['userlvl'] 		= $arrLogin[0]["isadmin"];
		$_SESSION['position'] 		=  $arrLogin[0]["position"];
		$_SESSION['id'] 			= $arrLogin[0]['id'];
		$_SESSION['name'] 			= strtolower($arrLogin[0]['first_name']." ".$arrLogin[0]['last_name']);
		$_SESSION["store_code"]	= $arrLogin[0]["store_code"];
		$_SESSION["user_type"]	= $arrLogin[0]["user_type"];
		$_SESSION['login'] 			= "YES";

		// echo 'success';
		
		// Redirect upon Login 
		//if($_SESSION['userlvl'] == '1'){
			// Redirect to Page
			//echo "<script>	window.location='./?page=doctor'; </script>";
		//} elseif($_SESSION['userlvl'] == '2') {
			// Redirect to Page
			echo "<script>	window.location='./?page=store-home'; </script>";
		//}
	} else {			
		// Error Message
		echo "<p class='text-danger text-center mt-3'>Sorry, the username or password is incorrect.</p>";
	}	
}
else{	
	// Return Message
	echo $msg;
}

?>