<?php

// ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

header('Acces-Control-Allow-Origin: *');
header('Content-Type: application/json');

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
// Required includes
require $sDocRoot."/includes/connect.php";

$dateApiFrom = ( isset($_REQUEST['date_from']) ) ? explode('-',$_REQUEST['date_from']) : "" ;
$dateApiTo = ( isset($_REQUEST['date_to']) ) ? explode('-',$_REQUEST['date_to']) : "" ;
$_GET['data_range_start_year'] = $dateApiFrom[0];
$_GET['data_range_start_month'] = $dateApiFrom[1];
$_GET['data_range_start_day'] = $dateApiFrom[2];
$_GET['data_range_end_year'] = $dateApiTo[0];
$_GET['data_range_end_month'] = $dateApiTo[1];
$_GET['data_range_end_day'] = $dateApiTo[2];
require "functions.php";

class Incentives
{
    private $username;
    private $password;

    private $dateFrom;
    private $dateTo;

    function __construct()
    {
      $this->username = ( isset($_SERVER['PHP_AUTH_USER']) ) ? $_SERVER['PHP_AUTH_USER'] : "" ;
      $this->password = ( isset($_SERVER['PHP_AUTH_PW']) ) ? $_SERVER['PHP_AUTH_PW'] : "" ;
      $this->dateFrom = ( isset($_REQUEST['date_from']) ) ? $_REQUEST['date_from'] : "" ;
      $this->dateTo = ( isset($_REQUEST['date_to']) ) ? $_REQUEST['date_to'] : "" ;

      //IF USERNAME AND PASSWORD IS INCORRECT WILL NOT CONTINUE ON FUNCTIONS
      if( $this->username!="sunnies" || $this->password!="TQ=g2G" ){
        echo json_encode(array('message' => 'Invalid username or password', 'error'=>1));
        exit();
      }

      $arrDateFrom = explode('-', $this->dateFrom);
      $arrDateTo = explode('-', $this->dateTo);

      if(count($arrDateFrom) != 3 || !checkdate($arrDateFrom[1], $arrDateFrom[2], $arrDateFrom[0])){
        echo json_encode(array('message' => 'Invalid date from', 'error'=>1));
        exit;
      }
      if(count($arrDateTo) != 3 || !checkdate($arrDateTo[1], $arrDateTo[2], $arrDateTo[0])){
        echo json_encode(array('message' => 'Invalid date to', 'error'=>1));
        exit;
      }
      else{
        $this->getIncentives();
      }

       
    }

    function getIncentives(){

      global $conn;
      global $qDate;
      global $dlDate;
      global $arrFilterStores;
      global $removeStoreIDs;
      global $arrStoresManagers;
      global $arrThreshold;
      global $arrPercentageBelow;
      global $arrPercentageAbove;

      $arrDataApiResult = [];

      include 'managers_supervisors.php';
      
      foreach ($arrDataManagersIncentives as $key => $value) {
        if($key == 'N/A') continue;
        $nameValue = explode('|', utf8_encode(utf8_decode($value['name'])));
        $arrDataDownload['name'] = strtoupper($nameValue[0]);
        $arrDataDownload['emp_id'] =$key;
        $arrDataDownload['designation'] = $nameValue[1];
        $arrDataDownload['bonus_1'] = $value['bonus_1'];
        $arrDataDownload['bonus_2'] = $value['bonus_2'];
        $arrDataDownload['total_bonus'] = $value['total_bonus'];
        $arrDataDownload['bank_name'] = $nameValue[2];
        $arrDataDownload['bank_number'] = $nameValue[3];

        $arrDataApiResult[] = $arrDataDownload;
      }

      if(!empty($arrFilterStores)) {

        // Set WHERE query for stores
        $specStore = "AND (";

        for ($i=0; $i < sizeOf($arrFilterStores); $i++) { 

          if($i > 0) {

            $specStore .= "OR ";

          }
              
          $specStore .= "o.origin_branch = '".$arrFilterStores[$i]."'";

        };

        $specStore .= ")";    

      }
      else {

        $specStore = "";

      };

      // Set array
      $arrIncentives = array();

      $query =  "SELECT
              if( os.status = 'return' AND os.payment='y',
                        (select payment_date from orders_specs 
                            where 
                            status != 'cancelled'
                            and old_po_number = os.po_number
                            LIMIT 1
                        ),''
                      )as order_checker,
              os.payment_date,
              sl.store_name,
              sl.store_id,
              if(os.old_po_number !='', os.old_po_number,'N/A') as old_po,
              os.status,
              os.po_number,
              REPLACE(p.total, '-','')
              FROM orders o
              LEFT JOIN orders_specs os ON o.order_id = os.order_id
              LEFT JOIN stores_locations sl ON o.origin_branch = sl.store_id
              LEFT JOIN payments p ON os.po_number = p.po_number
              WHERE
                ".$qDate."
                ".$specStore."
                ".$removeStoreIDs."
                AND os.payment = 'y'
                AND os.dispatch_type!='packaging'
                AND os.po_number!=''
                AND os.orders_specs_id!=''
              ORDER BY os.payment_date ASC
                ;";

      $grabParams = array(
        'checker_date',
        'payment_date',
        'store_name',
        'store_id',
        'old_po',
        'status',
        'po_number',
        'total'
      );

      $stmt = mysqli_stmt_init($conn);
      $arrOldPoNumber = [];
      if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

        while (mysqli_stmt_fetch($stmt)) {

          $tempArray = array();

          for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

          };
          //to check exist po number with return status has new reorder data upon filter
          if($tempArray['old_po'] != 'N/A'){
            $arrOldPoNumber[] = $tempArray['old_po'];
          }

          $arrIncentives[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    

      }
      else {

        showMe(mysqli_error($conn));

      };

      $arrStaffIncentives = [];
      $arrExistStaff = [];
      $noStaffIncentives = 0;
      foreach ($arrIncentives as $rowline => $line) {

        include 'get_staff_daily.php';
        $negative = '';
        if($line['status'] == 'return'){
          $monthyearCheckerPaymentDate = date("Y-m", strtotime($line['checker_date']));
          $monthyearPaymentDate = date("Y-m", strtotime($line['payment_date']));
          if(!strtotime($line['checker_date'])){
            $line['total'] = 0;
          }elseif($monthyearCheckerPaymentDate == $monthyearPaymentDate && in_array($line['po_number'], $arrOldPoNumber)){
              $line['total'] = 0;
          }elseif($monthyearCheckerPaymentDate != $monthyearPaymentDate && in_array($line['po_number'], $arrOldPoNumber)){
            $negative = '-';
          }
        }
        include 'get_percentage.php';

          $line['staffBT'] =  0;
          $line['staffAT'] = 0;
          if($valueThreshold != 'n'){
            //to get and divide staffBT by count of employee of payment_date in daily login
            $empCount = count($arrDailyStaff);
            if($staffBelow > 0){
              $multiplierValueBelow = ($line['excess'] >= 0) ? $valueThreshold : $line['total'];
              $line['staffBT'] = $negative.($multiplierValueBelow * $staffBelow);
              if($empCount > 0){
                $line['staffBT'] = $line['staffBT'] / $empCount;
              }else{
                $noStaffIncentives +=  $line['staffBT'];
                $line['staffBT'] = 0;
              }
            }
            if($staffAbove > 0 && $line['excess'] >= 0){
              $line['staffAT'] = $negative.($line['excess'] * $staffAbove);

              if($empCount > 0){
                $line['staffAT'] = $line['staffAT'] / $empCount;
              }else{
                $noStaffIncentives +=  $line['staffAT'];
                $line['staffAT'] = 0;
              }
            }
          }
        
        foreach ($arrDailyStaff as $staff) {
          if(!in_array($staff['emp_id'], $arrExistStaff)){
            $arrExistStaff[] = $staff['emp_id'];
            $arrStaffIncentives[$staff['emp_id']]['name'] = $staff['name'];
            $arrStaffIncentives[$staff['emp_id']]['bonus_1'] = $line['staffBT'];
            $arrStaffIncentives[$staff['emp_id']]['bonus_2'] = $line['staffAT'];
            $arrStaffIncentives[$staff['emp_id']]['total_bonus'] = ($line['staffBT'] +  $line['staffAT']);
          }else{
            $arrStaffIncentives[$staff['emp_id']]['bonus_1'] += $line['staffBT'];
            $arrStaffIncentives[$staff['emp_id']]['bonus_2'] += $line['staffAT'];
            $arrStaffIncentives[$staff['emp_id']]['total_bonus'] += ($line['staffBT'] + $line['staffAT']);
          }
        }
      
      }

     
      foreach ($arrStaffIncentives as $key => $value) {

        $nameValue = explode('|', utf8_encode(utf8_decode($value['name'])));

        $arrDataDownload['name'] = strtoupper($nameValue[0]);
        $arrDataDownload['emp_id'] = $key;
        $arrDataDownload['designation'] = $nameValue[1];
        $arrDataDownload['bonus_1'] = number_format($value['bonus_1'],5);
        $arrDataDownload['bonus_2'] = number_format($value['bonus_2'],5);
        $arrDataDownload['total_bonus'] = number_format($value['total_bonus'],5);
        $arrDataDownload['bank_name'] = $nameValue[2];
        $arrDataDownload['bank_number'] = $nameValue[3];
        $arrDataApiResult[] = $arrDataDownload;
      }

      echo json_encode($arrDataApiResult);
    }

}//END:: CLASS


$class = new Incentives();


?>
