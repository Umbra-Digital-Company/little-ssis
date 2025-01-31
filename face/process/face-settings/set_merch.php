<?php
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";

	if(!isset($_SESSION['user_login']['id'])){
		echo '<script type="text/javascript"> window.location = "/"; </script>';
		exit;
	}

	$query = 	'UPDATE face_settings SET active = 0 WHERE category = "merch" AND active = 1;';

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);		

		}
		else {

			echo mysqli_error($conn);
			exit;

		};

	for ($i=0; $i < count($_POST['merch_list']); $i++) { 
		$query = 	'INSERT IGNORE INTO
						face_settings (
							product_code,
							category,
							active,
							created_by
						)
					VALUES (
						"'.mysqli_real_escape_string($conn,$_POST['merch_list'][$i]).'",
						"merch",
						"1",
						"'.$_SESSION['user_login']['id'].'"
					)
					ON DUPLICATE KEY UPDATE
					active = VALUES(active),
					created_by = VALUES(created_by)';

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);		

		}
		else {

			echo mysqli_error($conn);
			exit;

		};
	}
		
		echo '<script type="text/javascript"> alert("Merch products successfully set"); window.location = "/face/face-settings"; </script>';
	

?>