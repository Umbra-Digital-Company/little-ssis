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

      include 'doctors.php';


      foreach ($arrDataManagersIncentives as $key => $value) {
        if($key == '' || $key == 'N/A') continue;
        $nameValue = explode('|', utf8_encode(utf8_decode($value['name'])));
        $arrDataDownload['name'] = strtoupper($nameValue[0]);
        $arrDataDownload['emp_id'] =$key;
        $arrDataDownload['designation'] = $nameValue[1];
        $arrDataDownload['bonus_1'] = number_format($value['bonus_1'], 5);
        $arrDataDownload['bonus_2'] = number_format($value['bonus_2'], 5);
        $arrDataDownload['total_bonus'] = number_format($value['total_bonus'], 5);
        $arrDataDownload['bank_name'] = $nameValue[2];
        $arrDataDownload['bank_number'] = $nameValue[3];

        $arrDataApiResult[] = $arrDataDownload;
      }

      echo json_encode($arrDataApiResult);
    }

}//END:: CLASS


$class = new Incentives();


?>
