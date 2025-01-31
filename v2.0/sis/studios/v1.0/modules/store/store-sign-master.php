<meta charset="UTF-8">
<?php include("../connect.php");
if ( !isset($_SESSION) ) {
	session_start();
}


$msg = '';
$valid = true;

if ( $_POST['username'] == '' ) {
	$valid = false;
}
if ( trim($_POST['month']) == '' || trim($_POST['day']) == '' || trim($_POST['year']) == '' ) {
	$valid=false;
}



if ( $valid ) {
	$username 		= $_POST['username'];
	$password 		= $_POST['month'].$_POST['day'].$_POST['year'];
	$phone_number 	= str_replace('+','',trim($username));
	$phone_number 	= str_replace('-','',$phone_number);
	$phone_number 	= str_replace(' ','',$phone_number);
	$phone_number 	= ltrim($phone_number,"0");
	$phone_number 	= trim($_POST['country_codes_login']).$phone_number;
	$qstmt = mysqli_stmt_init($conn);
	$query = "SELECT 
		s.email_address,
		s.s_pass,
		s.password,
		s.profile_id,
		s.id,
		pi.age
	FROM 
		profiles s
	LEFT JOIN
		profiles_info pi on pi.profile_id = s.profile_id
	WHERE 
		s.email_address = '".$username."' OR REPLACE(pi.phone_number,'-','') = '".$phone_number."'
	AND 
		s.password = '".$password."'
	";
	if (mysqli_stmt_prepare($qstmt, $query)) {
		//mysqli_stmt_bind_param($qstmt, 's', $email);
		mysqli_stmt_execute($qstmt);
        mysqli_stmt_bind_result($qstmt, $r1, $r2, $r3, $r4, $r5, $r6);
        mysqli_stmt_fetch($qstmt);
		mysqli_stmt_store_result($qstmt); 

		$email_add 		= $r1;
		$uSPass 		= $r2;
		$uPass 			= $r3;
		$customer_id	= $r4;
		$id				= $r5;
		$age			= $r6;

		mysqli_stmt_close($qstmt);
		//echo $uSPass;
		//	echo $uPass;
		// echo $query;
	}
	
	 

	

	// $tryP = $uSPass.$password;
	if ( $uPass == $password ) {
		$_SESSION["login_customer"]	= "YES";
		$_SESSION["customer_id"] 	= $customer_id;
		$_SESSION["cust_id"]	 	= $id;

		$queryUpdateSalesPerson="UPDATE 
			profiles_info
		SET
			sales_person ='".$_SESSION['id']."' 
		WHERE 
			profile_id='".$customer_id."';
		";

		$stmt = mysqli_stmt_init($conn);
		if(mysqli_stmt_prepare($stmt, $queryUpdateSalesPerson)) {

			mysqli_stmt_execute($stmt);

		} 

		$_SESSION['priority'] = ( $age > '55' ) ? '1' : '0'; 

		unset($_SESSION['email_taken']);
		
		echo '<script>window.location="./?page=health-declaration-form"</script>';
		
	} else {
		echo 'Invalid email or password.';
	}
	
} else {

	echo 'Invalid email or password.';

}

?>