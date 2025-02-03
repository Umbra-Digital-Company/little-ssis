<?php 
// phpinfo();
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set variables
$msg = '';
$valid = true;

// Errors
if (empty(trim($_POST['user']))) {
    $valid = false;
    $msg .= "* Enter username<br />";
}

if (empty(trim($_POST['pass']))) {
    $valid = false;
    $msg .= "* Enter Password<br />";
}

// If POST is valid
if($valid){
    // Set username and password
    $username = trim($_POST['user']);
    $password = trim($_POST['pass']); 
    
    $query = "SELECT 
                s.username,
                s.id,
                s.isadmin,
                s.position,
                s.store_location,
                s.store_code,
                s.s_pass,
                s.password,
                s.user_type,
                s.store_type,
                uc.dispatch_studios0sunnies_studios,
                s.password2
              FROM
                users s
                LEFT JOIN user_access_v2 uc ON s.username = uc.username
              WHERE
                s.username=?
                AND s.locked != 'y'";

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_bind_param($stmt, 's', $username);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_store_result($stmt);

        $username       = $result1;
        $id             = $result2;
        $isadmin        = $result3;
        $position       = $result4;
        $store_location = $result5;
        $store_code     = $result6;
        $uSPass         = $result7;
        $uPass          = $result8;
        $user_type      = $result9;
        $store_type     = $result10;
        $dispatch_access= $result11;
        $password2		= $result12;

        mysqli_stmt_close($stmt);
    }
    
    // Test password
    $tryP = $uSPass.$password;

    if(password_verify($password, $password2)) {
        $_SESSION['userlvl']          = $isadmin;
        $_SESSION['id']               = $id;
        $_SESSION["store_code"]       = $store_code;
        $_SESSION['login']            = "YES";
        $_SESSION['dashboard_login']  = "YES";
        $_SESSION['user_type']        = $user_type;
        $_SESSION['store_type']       = $store_type;

        $_SESSION['user_login']['username']       = $username;
        $_SESSION['user_login']['id']             = $id;
        $_SESSION['user_login']['userlvl']        = $isadmin;        
        $_SESSION['user_login']['position']       = $position;
        $_SESSION['user_login']['store_location'] = $store_location;
        $_SESSION['user_login']['store_code']     = $store_code;
        $_SESSION['user_login']['login']          = "YES";
        $_SESSION['user_login']['dashboard_login']= "YES";
        $_SESSION['dispatch_studios_no_access']   = ($dispatch_access == 1) ? false : true;

        $querlog = "INSERT INTO users_access_logs(`username`,`action`,`application`) 
                    VALUES(?, 'login', 'sunniesstore')";

        $stmtBig2 = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmtBig2, $querlog)) {
            mysqli_stmt_bind_param($stmtBig2, 's', $_SESSION['user_login']['username']);
            mysqli_stmt_execute($stmtBig2);
            mysqli_stmt_close($stmtBig2);
        } else {
            echo mysqli_error($conn);
        }

        echo '<script>window.location.href="?page=store-home";</script>';
    } else {
        echo '<p class="text-center text-danger">Invalid username or password</p>';
    }
} else {
    echo '<p class="text-center text-danger">Invalid username or password</p>';
}

?>