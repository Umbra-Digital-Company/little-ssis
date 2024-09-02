<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
	
$stmt = mysqli_stmt_init($conn);


$queryStore = 	"INSERT IGNORE INTO 
					store_codes_face(
						warehouse_code,
						warehouse_name,
						business_unit,
						store_code,
						store_name_proper
					)
				VALUES(
					'".mysqli_real_escape_string($conn,$_POST['warehouse_code'])."',
					'".mysqli_real_escape_string($conn,$_POST['warehouse_name'])."',
					'FACE',
					'".mysqli_real_escape_string($conn,$_POST['store_code'])."',
					'".mysqli_real_escape_string($conn,$_POST['store_name_proper'])."'
				)";

// echo '<pre>';
// echo $queryStore;
// echo '</pre>';	exit;

if (mysqli_stmt_prepare($stmt, $queryStore)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}

echo "<script> window.alert('Succesfully Created');	window.location='/face/stores'; </script>";

?>
