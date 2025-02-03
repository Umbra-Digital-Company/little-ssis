<?php
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();
if(!isset($_SESSION['user_login']['username'])) {
	header("Location: /");
    exit;
}
// SET GET
if(isset($_GET['language_setting'])) {

	$_SESSION['language_setting'] = $_GET['language_setting'];

}
else {

	$_SESSION['language_setting'] = 'us';

};

echo "<script>window.history.back()</script>";
// echo $_SESSION['language_setting'] ;

?>