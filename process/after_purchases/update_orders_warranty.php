<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
if(!isset($_SESSION)){
    session_start();
}
$arrWarranty = array();
$grabParams = array(
 "warranty",
 "warranty_date",
    "status",
    "payment"
);
$json = [];
$json['error'] = '';
$query  =    'SELECT
         warranty,
         warranty_date,
            status,
            payment
         FROM
         orders_specs_test
         WHERE orders_specs_id = "'.$_POST['orders_specs_id'].'";';
            
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

        };
        $arrWarranty[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    $json['error'] = mysqli_error($conn);
    echo json_encode($json);
    exit;

};
if($arrWarranty[0]['payment'] != "y" || $arrWarranty[0]['status'] == "cancelled" || $arrWarranty[0]['status'] == "for payment"){
    $json['error'] = "Warranty claiming not applicable.";
    echo json_encode($json);
    exit;
}
require $sDocRoot."/process/after_purchases/warranty_duration.php";

$po_number = mysqli_real_escape_string($conn,$_POST['po_number']);
$arrTime = ['12'=>'12','13'=>'01','14'=>'02','15'=>'03','16'=>'04','17'=>'05','18'=>'06','19'=>'07','20'=>'08','21'=>'09','22'=>'10','23'=>'11'];
$claiming_date = mysqli_real_escape_string($conn,$_POST['claiming_date']);
$date = explode(' ', $claiming_date);
$time = explode(':', $date[1]);
if($date[2] == 'PM'){
    foreach ($arrTime as $key => $value) {
       if($value == $time[0]){
        $claiming_date = date('Y-m-d H:i:s', strtotime($date[0].' '.$key.':'.$time[1].':'.$time[2]));
        break;
       }
    }
}
else{
    $claiming_date = $date[0].' '.$date[1];
}
$json['error'] = '';
$claiming_date = date('Y-m-d H:i:s', strtotime($claiming_date));
$warranty_type = mysqli_real_escape_string($conn,$_POST['warranty_type']);
$warranty_store_claim = mysqli_real_escape_string($conn,$_POST['warranty_store_claim']);
$orders_specs_id = mysqli_real_escape_string($conn,$_POST['orders_specs_id']);

$query ='INSERT INTO 
warranty_logs(orders_specs_id, claimed_date, warranty_store_claim, warranty_type_id )
VALUES("'.$orders_specs_id.'","'.$claiming_date.'","'.$warranty_store_claim.'","'.$warranty_type.'");';

//$json['query'] = $query;
$stmt = mysqli_stmt_init($conn);
if(mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
	mysqli_stmt_close($stmt);	

} 
else {

	$json['error'] = mysqli_error($conn);
 echo json_encode($json);
	exit;
}

$arrLogs = [];
$grabParams = array(
    "warranty_type",
    "duration",
    "store_name",
    "claimed_date"
);

$query  =   "SELECT
                wt.description,
                wt.duration,
                sl.store_name,
                wl.claimed_date
                FROM warranty_logs wl 
                LEFT JOIN stores_locations sl ON wl.warranty_store_claim =  sl.store_id
                LEFT JOIN warranty_type wt ON wl.warranty_type_id = wt.id
                WHERE wl.orders_specs_id = '".$_POST['orders_specs_id']."'";
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);
    $logs = "";
    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

        };
        $am_pm = explode(' ', $tempArray['claimed_date']);
        $am_pm = explode(":", $am_pm[1]);
        $am_pm = ($am_pm[0] < 12) ? ' AM' : ' PM';
        $tempArray['claimed_date'] = date('m/d/Y h:i:s', strtotime($tempArray['claimed_date'])).$am_pm;
        $logs .= "<tr><td>".$tempArray['warranty_type']." - ".$tempArray['duration']."</td><td>".$tempArray['store_name']."</td><td>".$tempArray['claimed_date']."</td></tr>";

    };
}

mysqli_stmt_close($stmt);
$json = [];
$json['warranty_expired'] =false;
$json['error'] = '';
$json['warranty_logs'] = ($logs != '') ? $logs : "<tr><td style='text-align:center;' colspan='3'>No Warranty Claiming Record</td>"; 
$json['message'] = "Warranty for ".$tempArray['warranty_type']." was successfuly claimed.";
echo json_encode($json);

?>