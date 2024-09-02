<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
if(!isset($_SESSION)){
    session_start();
}
$warranty_duration = "";
$grabParams = array(
	"description",
    "warranty_duration"
);

$query  =   "SELECT
				description,
				duration
                FROM warranty_type
                WHERE id = '".$_POST['warranty_type']."'";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2);
    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

        };
       	$warranty_duration = $tempArray['warranty_duration'];
       	$json['message'] = $tempArray['description']." - ".$warranty_duration." warranty was expired";
    };
}
mysqli_stmt_close($stmt);
$duration = "";
$warranty_expired = false;

$payment_date = explode(' ', $_POST['payment_date']);
$claiming_date = explode(' ', $_POST['claiming_date']);
$payment_date = date("Y-m-d", strtotime($payment_date[0]));
$claiming_date = date("Y-m-d", strtotime($claiming_date[0]));
if(strstr($warranty_duration, "1 Month")){
	$p_month = strtotime($payment_date);
	$duration = strtotime("+1 months", strtotime($payment_date));
	$c_date = strtotime($claiming_date);
	$warranty_expired = ($c_date >= $p_month && $c_date <= $duration) ? false : true;
}elseif(strstr($warranty_duration, "6 Months")){
	$p_month = strtotime($payment_date);
	$duration = strtotime("+6 months", strtotime($payment_date));
	$c_date = strtotime($claiming_date);
	$warranty_expired = ($c_date >= $p_month && $c_date <= $duration) ? false : true;
}elseif(strstr($warranty_duration, "1 Year")){
	$p_month = strtotime($payment_date);
	$duration = strtotime("+1 year", strtotime($payment_date));
	$c_date = strtotime($claiming_date);
	$warranty_expired = ($c_date >= $p_month && $c_date <= $duration) ? false : true;
}

$json['warranty_expired'] = $warranty_expired;
if(isset($_POST['on_change']) || $warranty_expired){
	echo json_encode($json);
	exit;
}
?>