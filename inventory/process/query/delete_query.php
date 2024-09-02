<?php
	
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();


	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";
	
    $arrUsersId[] = $_SESSION['user_login']['id'];

    if(trim($_POST['id']) == ''){
    	echo json_encode('Please select template.'); 
    	exit;
    }
  	$queryadded_count='UPDATE server_query
                        SET 
                        status = "deleted"
                        WHERE id = '.mysqli_real_escape_string($conn,$_POST['id']).' AND creator_id = '.$_SESSION['user_login']['id'].';';

    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $queryadded_count)) {

        mysqli_stmt_execute($stmt);       
        mysqli_stmt_close($stmt);

        echo json_encode('Query successfully deleted.');

    }
    else{

        echo mysqli_error($conn);
        exit;

    };
?>