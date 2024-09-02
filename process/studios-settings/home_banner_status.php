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

	$status = '';
	$echoStatus = '';
	if($_POST['action'] == 'video'){
		$status = ($_POST['status'] == 'active') ? 'video_status = 0' : 'video_status = 1';
		$echoStatus = ($_POST['status'] == 'active') ? 'inactive' : 'active';
	}elseif($_POST['action'] == 'image1'){
		$status = ($_POST['status'] == 'active') ? 'image_1_status = 0' : 'image_1_status = 1';
		$echoStatus = ($_POST['status'] == 'active') ? 'inactive' : 'active';
	}elseif($_POST['action'] == 'image2'){
		$status = ($_POST['status'] == 'active') ? 'image_2_status = 0' : 'image_2_status = 1';
		$echoStatus = ($_POST['status'] == 'active') ? 'inactive' : 'active';
	}
	

    	$query = 	'UPDATE
						studios_text_images_settings 
					SET '.$status.'
					WHERE active = 1
					;';

		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

		    mysqli_stmt_execute($stmt);		
		    mysqli_stmt_close($stmt);

		}
		else {

			echo mysqli_error($conn);
			exit;

		};

		echo ucwords($_POST['action']).' successfully '.$echoStatus;
  
?>