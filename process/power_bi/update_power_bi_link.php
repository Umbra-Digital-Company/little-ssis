<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

require $sDocRoot."/includes/connect.php";

//   print_r($_POST);
// echo "<pre>";
  $query ="Update finance_report SET
                    link='".mysqli_real_escape_string($conn,$_POST['link'])."',
                    link2='".mysqli_real_escape_string($conn,$_POST['link2'])."',
                    link3='".mysqli_real_escape_string($conn,$_POST['link3'])."',
                    link4='".mysqli_real_escape_string($conn,$_POST['link4'])."',
                    link5='".mysqli_real_escape_string($conn,$_POST['link5'])."',
                    link6='".mysqli_real_escape_string($conn,$_POST['link6'])."',
                    link7='".mysqli_real_escape_string($conn,$_POST['link7'])."',
                    link8='".mysqli_real_escape_string($conn,$_POST['link8'])."',
                    link_title_1='".mysqli_real_escape_string($conn,$_POST['link_title1'])."',
                    link_title_2='".mysqli_real_escape_string($conn,$_POST['link_title2'])."',
                    link_title_3='".mysqli_real_escape_string($conn,$_POST['link_title3'])."',
                    link_title_4='".mysqli_real_escape_string($conn,$_POST['link_title4'])."',
                    link_title_5='".mysqli_real_escape_string($conn,$_POST['link_title5'])."',
                    link_title_6='".mysqli_real_escape_string($conn,$_POST['link_title6'])."',
                    link_title_7='".mysqli_real_escape_string($conn,$_POST['link_title7'])."',
                    link_title_8='".mysqli_real_escape_string($conn,$_POST['link_title8'])."'

                where id='1' ";

                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $query)) {
                
                    mysqli_stmt_execute($stmt);		
                
                };

  // echo "</pre>";

   echo "<script> window.alert('Power BI  updated! ');	window.location='../../finance/power_bi/'; </script>";

?>