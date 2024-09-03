<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Included files
require $sDocRoot."/includes/connect.php";
    
if(isset($_SESSION['customer_id']) && trim($_SESSION['customer_id']) != ''){
    $set = ($_POST['club_member'] == 'yes') ? 'y' : 'n';

    $query = 'UPDATE profiles_info SET club_member="'.$set.'" WHERE profile_id = "'.$_SESSION['customer_id'].'";';

    // echo $query; exit;
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_execute($stmt);		
    };
}else{
    echo 'Invalid club member';
}
?>