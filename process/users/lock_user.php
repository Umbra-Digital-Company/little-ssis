<meta charset="UTF-8">

<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

if(!isset($_GET['user_id'])) {

	header('location: /');
	exit;

};

$user_id 	   = $_GET['user_id'];
$user_username = $_GET['user_username'];

$query = 	'UPDATE
				users
			SET
				locked = "y"
			WHERE
				id = '.$user_id.'
					AND username = "'.$user_username.'"';				

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}

// Send back
header('location: /user-management');
exit;

?>
