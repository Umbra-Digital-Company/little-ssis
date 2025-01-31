<?php
session_save_path("/var/www/html/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
include $sDocRoot.'/includes/connect.php';
include 'get_default_page.php';
$arrDefaultPage = grabDefaultPages($_SESSION['user_login']['username']);
$_SESSION['selected-brand'] = $_POST['brand'];

if($_SESSION['selected-brand'] == "specs"){
    echo $arrDefaultPage['specs_link'];
}
elseif($_SESSION['selected-brand'] == "studios"){
    echo $arrDefaultPage['studios_link'];
}
elseif($_SESSION['selected-brand'] == 'face') {
    echo $arrDefaultPage['face_link'];
}
elseif($_SESSION['selected-brand'] == 'flask') {
    $_SESSION['selected-brand'] = 'flask';
    echo $arrDefaultPage['flask_link'];
}
elseif($_SESSION['selected-brand'] == 'general') {
    $_SESSION['selected-brand'] = 'general';
    echo $arrDefaultPage['general_link'];
}
?>