<?php
session_save_path("/var/www/html/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
include $sDocRoot.'/includes/connect.php';
include 'get_default_page.php';
$arrDefaultPage = grabDefaultPages($_SESSION['user_login']['username']);
$_SESSION['selected-brand'] = $_GET['brand'];

if($_SESSION['selected-brand'] == "specs"){
    echo '<script> window.location = "'.$arrDefaultPage['specs_link'].'"; </script>';
}
else if($_SESSION['selected-brand'] == "studios"){
    echo '<script> window.location = "'.$arrDefaultPage['studios_link'].'"; </script>';
}
elseif($_SESSION['selected-brand'] == 'face') {
    echo '<script> window.location = "'.$arrDefaultPage['face_link'].'"; </script>';
}
elseif($_SESSION['selected-brand'] == 'flask') {
    $_SESSION['selected-brand'] = 'flask';
    echo '<script> window.location = "'.$arrDefaultPage['flask_link'].'"; </script>';
}
elseif($_SESSION['selected-brand'] == 'general') {
    $_SESSION['selected-brand'] = 'general';
    echo '<script> window.location = "'.$arrDefaultPage['general_link'].'"; </script>';
}
?>