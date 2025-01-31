<?php   

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();
// Required includes
require $sDocRoot."/includes/connect.php";

if(!isset($_SESSION['dashboard_login'])){
	echo 'Invalid session';
	exit;
}
// Set POST DATA

$arrData = $_POST['data'];


for($i = 0; $i < count($arrData); $i++){
	if( $arrData[$i]['gender'] == 'male' ||  $arrData[$i]['gender'] == 'female'){
		$query = 'UPDATE
					profiles_info
					SET
						gender = "'.mysqli_real_escape_string($conn, $arrData[$i]['gender']).'"
					WHERE
						profile_id = "'.mysqli_real_escape_string($conn, $arrData[$i]['profile_id']).'";';

		// echo $query.PHP_EOL;
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);		

		};
	}
}

echo 'Successfully updated.';

?>
