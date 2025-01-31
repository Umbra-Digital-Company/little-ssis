<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

///////////////////////////////////////////////////////////////////////////////////////////// GRAB GET DATA

$glToken = $_POST['access_token'];

$query = 	"INSERT INTO 
					iou_tokens(
						access_token
					)
				VALUES(
					'".mysqli_real_escape_string($conn,$glToken)."'
				)";

if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}

?>
