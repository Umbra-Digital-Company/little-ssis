<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

if(!isset($_POST['user_username'])) {

	header('/dashboard/');
	exit;

};

// Grab POST data
$id 	  = $_POST['user_id'];
$username = $_POST['user_username'];

$query = 	'DELETE FROM
				users
			WHERE
				id = '.$id.'
					AND username = "'.$username.'"';	

// echo '<pre>';
// echo $query;
// echo '</pre>';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}
else {

	echo mysqli_error($conn);

};

?>
