<?php 
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";


	if(isset($_SESSION['customer_id'])){
	 $lastName = trim($_POST['lastname-guest']) == '' ? 'guest' : trim($_POST['lastname-guest']);

		$queryUpdateSalesPerson="UPDATE 
			profiles_info
		SET
			first_name ='".mysqli_real_escape_string($conn,$_POST['firstname-guest'])."',
			last_name ='".mysqli_real_escape_string($conn,$lastName)."',
			gender ='".mysqli_real_escape_string($conn,strtolower($_POST['gender-guest']))."',
			age = '".mysqli_real_escape_string($conn,$_POST['age_range-guest'])."'
		WHERE 
			profile_id='".$_SESSION['customer_id']."';";

		$stmt = mysqli_stmt_init($conn);
		if(mysqli_stmt_prepare($stmt, $queryUpdateSalesPerson)) {

			mysqli_stmt_execute($stmt);

		}else{

			echo mysqli_error($conn);

		}

		$queryOrder="UPDATE 
			orders_face
		SET
			first_name ='".mysqli_real_escape_string($conn,$_POST['firstname-guest'])."',
			last_name ='".mysqli_real_escape_string($conn,$lastName)."'
		WHERE 
			order_id='".$_SESSION['order_no']."';";

		$stmt = mysqli_stmt_init($conn);
		if(mysqli_stmt_prepare($stmt, $queryOrder)) {

			mysqli_stmt_execute($stmt);

		}else{

			echo mysqli_error($conn);

		}

		unset($_SESSION['guest_customer']);
		echo 'success';
	}else{
		echo 'inavalid';
	}
?>