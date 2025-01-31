<?php
	
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();


	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";


	$arrUsersId = json_decode($_POST['users_id'], true);
	$arrSalesColumns = json_decode($_POST['sales_columns'], true);
	$arrInventoryColumns = json_decode($_POST['inventory_columns'], true);
	
    $arrUsersId[] = $_SESSION['user_login']['id'];

    if(count($arrSalesColumns) == 0 && count($arrInventoryColumns) == 0){
    	echo json_encode('Please select columns fields.'); 
    	exit;
    }
  	$queryadded_count='UPDATE server_query
                        SET 
                        template_name = "'.mysqli_real_escape_string($conn,$_POST['template_name']).'", 
                        sales_columns = "'.implode(',',$arrSalesColumns).'",
                        inventory_columns = "'.implode(',',$arrInventoryColumns).'",
                        users_access = "'.implode(',',$arrUsersId).'"
                        WHERE id = '.mysqli_real_escape_string($conn,$_POST['id']) ;

    $stmt = mysqli_stmt_init($conn);
    if(mysqli_stmt_prepare($stmt, $queryadded_count)) {

        mysqli_stmt_execute($stmt);       
        mysqli_stmt_close($stmt);

        echo json_encode('Query successfully updated.'); 

    }
    else{

        echo mysqli_error($conn);
        exit;

    };
?>