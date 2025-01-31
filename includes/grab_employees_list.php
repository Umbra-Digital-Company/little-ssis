<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
if(!defined('DB_SERVER')){
	require_once $sDocRoot."/includes/connect.php";
}
// session_save_path($sDocRoot ."/cgi-bin/tmp");
// session_start();

//////////////////////////////////////////////////////////////////////////////////// SET NAMES

$query = 'SET NAMES utf8';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);    
                            
}

//////////////////////////////////////////////////////////////////////////////////// GRAB EMPLOYEES

// Set array
$arrEmployees = array();

$grabParams = array(	
	"emp_id",
	"first_name",
	"middle_name",
	"last_name",
	"gender",
	"civil_status",
	"birth_date",
	"department",
	"designation",
	"brand",
	"location",
	"start_date"
);
$querypn ="";
$querypn  .= 	"SELECT					
				emp.emp_id,
				emp.first_name,
				emp.middle_name,
				emp.last_name,
				emp.gender,
				emp.civil_status,
				emp.birth_date,
				emp.department,
				emp.designation,
				emp.brand,
				emp.location,
				emp.start_date
			FROM 
				emp_table emp
			WHERE
				(emp.department = 'RETAIL OPERATIONS' OR designation='SALES REPRESENTATIVE' OR designation='OFFICER IN CHARGE'  OR designation='FULFILLMENT TEAM SUPERVISOR'  OR designation='FULFILLMENT STAFF: INTERNATIONAL CASHIER' 
							 OR designation='FULFILLMENT STAFF - PARCEL PROCESS'  OR designation='FULFILLMENT STAFF - STOCKMAN'  OR designation='FULFILLMENT STAFF - LOCAL CASHIER'
					OR emp.department = 'OPTOMETRIST' OR emp.designation = 'AREA DOCTOR' OR emp.designation = 'HEAD CORPORATE DOCTOR' OR emp.designation = 'OPTOMETRIST')
					and status='Y'

		";


if($_SESSION["store_code"]=='142' ||  $_SESSION["store_code"]=='150'  ){

	$querypn .=" AND emp.location='142' ";
}
		$querypn .="	ORDER BY
				emp.first_name ASC";

	$query=$querypn;
			
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrEmployees[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>