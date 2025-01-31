<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set POST data
if($_POST['action'] == 'upload'){
	$arrData = [];
	$arrEmpId = [];
	$arrStoreLogs = [];
	foreach($_FILES as $file) {
	  $content = file($file['tmp_name']);
	  $row = 1;
	  foreach($content as $line){
	    if($row !=1){
	      $data_row = str_getcsv($line);
	      if(!array_filter($data_row)) break;
	       	$emp_id = trim(mysqli_real_escape_string($conn,$data_row[0]));
	        $arrEmpId ['emp_id'][] = $emp_id; // save to array to update employee not in csv file
	        $first_name = trim(mysqli_real_escape_string($conn,$data_row[1]));
	        $middle_name = trim(mysqli_real_escape_string($conn,$data_row[2]));
	        $last_name = trim(mysqli_real_escape_string($conn,$data_row[3]));
	        $gender = trim(mysqli_real_escape_string($conn,$data_row[4]));
	        $civil_status = trim(mysqli_real_escape_string($conn,$data_row[5]));
	        $birth_date = trim(mysqli_real_escape_string($conn,$data_row[6]));
	        $department = trim(mysqli_real_escape_string($conn,$data_row[7]));
	        $designation = trim(mysqli_real_escape_string($conn,$data_row[8]));
	        $brand = trim(mysqli_real_escape_string($conn,$data_row[9]));
	        $location = trim(mysqli_real_escape_string($conn,$data_row[10]));
	        $start_date= trim(mysqli_real_escape_string($conn,$data_row[11]));
	        $bank_name = trim(mysqli_real_escape_string($conn,$data_row[12]));
	        $bank_number = trim(mysqli_real_escape_string($conn,$data_row[13]));
	        $status = 'Y';
	        $first_name = utf8_decode(utf8_encode($first_name));
	        $middle_name = utf8_decode(utf8_encode($middle_name));
	        $last_name = utf8_decode(utf8_encode($last_name));
	        $department = utf8_decode(utf8_encode($department));
	        $designation = utf8_decode(utf8_encode($designation));
	        $brand = utf8_decode(utf8_encode($brand));
	        $location = utf8_decode(utf8_encode($location));
	        $bank_name = utf8_decode(utf8_encode($bank_name));
	        $bank_number = utf8_decode(utf8_encode($bank_number));
	        $arrData[] = "('$emp_id', '$first_name', '$middle_name', '$last_name', '$gender', '$civil_status', '$birth_date', '$department',
                        '$designation', '$brand', '$location', '$start_date','$status','$bank_name','$bank_number')";

            $arrStoreLogs[] = "('$emp_id','$location', 'uploaded_".$_SESSION['user_login']['id']."')";
	    }
	    $row++;
	  }
	}
	// echo '<pre>';
	// print_r($arrData);
	// echo '</pre>';
	// exit;
	//CHECK FROM emp_table_test
	$arrEmployees = array();

	$grabParams = array(	
		"emp_id"
	);

	$query  = 	"UPDATE emp_table
				SET status = 'N'
				WHERE
				emp_id NOT IN ('".implode("','", $arrEmpId['emp_id'])."')
				AND location NOT IN ('142','155','150');";
				
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);		
		mysqli_stmt_close($stmt);		

	}
	else {

		echo json_encode(mysqli_error($conn));
		exit;

	};
	$arrColumn = ['emp_id', 'first_name', 'middle_name','last_name', 'gender', 'civil_status', 'birth_date', 'department',
                        'designation', 'brand', 'location', 'start_date','status','bank_name','bank_number'];
    $query = "INSERT INTO emp_table (".implode(',', $arrColumn).")
            VALUES ";
   $query .= implode(',', $arrData);
   $query .= " ON DUPLICATE KEY UPDATE
   			first_name = VALUES(first_name),
   			middle_name = VALUES(middle_name),
   			last_name = VALUES(last_name),
   			gender = VALUES(gender),
   			civil_status = VALUES(civil_status),
   			birth_date = VALUES(birth_date),
   			department = VALUES(department),
   			designation = VALUES(designation),
   			brand = VALUES(brand),
   			location = VALUES(location),
   			start_date = VALUES(start_date),
   			status = VALUES(status),
   			bank_name = VALUES(bank_name),
   			bank_number = VALUES(bank_number)
   			";
      $stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);		
		mysqli_stmt_close($stmt);		

	}
	else {

		echo json_encode(mysqli_error($conn));
		exit;

	};

	$arrColumn = ['emp_id', 'store_code', 'created_by'];

	$query = "INSERT INTO emp_store_logs (".implode(',', $arrColumn).")
            VALUES ";
   	$query .= implode(',', $arrStoreLogs);

   	 $stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

		mysqli_stmt_execute($stmt);		
		mysqli_stmt_close($stmt);		

	}
	else {

		echo json_encode(mysqli_error($conn));
		exit;

	};


	echo json_encode('Employees Successfully Uploaded');
	exit;
}
$emp_employee_id  = trim($_POST['employee_id']);
$emp_first_name   = $_POST['employee_first_name'];
$emp_middle_name  = $_POST['employee_middle_name'];
$emp_last_name 	  = $_POST['employee_last_name'];
$emp_gender 	  = $_POST['employee_gender'];
$emp_civil_status = $_POST['employee_civil_status'];
$emp_birth_date = date('m/d/Y', strtotime($_POST['employee_birth_date']));
$emp_department   = $_POST['employee_department'];
$emp_designation  = $_POST['employee_designation'];
$emp_brand 		  = $_POST['employee_brand'];
$emp_location 	  = $_POST['employee_location'];
$emp_start_date = date('m/d/Y', strtotime($_POST['employee_start_date']));
$emp_status 	  = $_POST['employee_status'];
$bank_name 	  = $_POST['bank_name'];
$bank_number 	  = $_POST['bank_number'];

if($_POST['action'] == 'add'){
	$arrLab = [];
	$query = 'SELECT
		emp_id
		FROM 
		emp_table 
		WHERE
		emp_id = "'.$emp_employee_id.'";';
		$grabParams=array("emp_id");
	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1);
		while (mysqli_stmt_fetch($stmt)) {
	
			$tempArray = array();
	
			for ($i=0; $i < 1; $i++) { 
	
				$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
	
			};
	
			$arrLab[] = $tempArray;
	
		};
	
		mysqli_stmt_close($stmt);
		if(count($arrLab) > 0){
			echo json_encode('Employee ID already exist.');
		exit;
		}		
	}
	else {
	
		echo json_encode(mysqli_error($conn));
		exit;
	
	};
	$query = 	'INSERT INTO
				emp_table (
					emp_id,
					first_name,
					middle_name,
					last_name,
					gender,
					civil_status,
					birth_date,
					department,
					designation,
					brand,
					location,
					start_date,
					bank_name,
					bank_number
				)
			VALUES (
				"'.mysqli_real_escape_string($conn,$emp_employee_id).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_first_name)).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_middle_name)).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_last_name)).'",
				"'.mysqli_real_escape_string($conn,$emp_gender).'",
				"'.mysqli_real_escape_string($conn,$emp_civil_status).'",
				"'.mysqli_real_escape_string($conn,$emp_birth_date).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_department)).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_designation)).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_brand)).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_location)).'",
				"'.mysqli_real_escape_string($conn,$emp_start_date).'",
				"'.mysqli_real_escape_string($conn,$bank_name).'",
				"'.mysqli_real_escape_string($conn,$bank_number).'"
			)';
}
else{
	$query = 'UPDATE emp_table SET
			first_name = "'.mysqli_real_escape_string($conn,utf8_decode($emp_first_name)).'",
			middle_name = "'.mysqli_real_escape_string($conn,utf8_decode($emp_middle_name)).'",
			last_name = "'.mysqli_real_escape_string($conn,utf8_decode($emp_last_name)).'",
			gender = "'.mysqli_real_escape_string($conn,$emp_gender).'",
			civil_status = "'.mysqli_real_escape_string($conn,$emp_civil_status).'",
			birth_date = "'.mysqli_real_escape_string($conn,$emp_birth_date).'",
			department = "'.mysqli_real_escape_string($conn,utf8_decode($emp_department)).'",
			designation = "'.mysqli_real_escape_string($conn,utf8_decode($emp_designation)).'",
			brand = "'.mysqli_real_escape_string($conn,utf8_decode($emp_brand)).'",
			location = "'.mysqli_real_escape_string($conn,utf8_decode($emp_location)).'",
			start_date = "'.mysqli_real_escape_string($conn,$emp_start_date).'",
			status = "'.mysqli_real_escape_string($conn,$emp_status).'",
			bank_name = "'.mysqli_real_escape_string($conn,$bank_name).'",
			bank_number = "'.mysqli_real_escape_string($conn,$bank_number).'"
			WHERE emp_id = "'.mysqli_real_escape_string($conn,$emp_employee_id).'";';
			
}
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);		
	mysqli_stmt_close($stmt);		

}
else {

	echo json_encode(mysqli_error($conn));
	exit;

};

$query = 	'INSERT INTO
				emp_store_logs (
					emp_id,
					store_code,
					created_by
				)
			VALUES (
				"'.mysqli_real_escape_string($conn,$emp_employee_id).'",
				"'.mysqli_real_escape_string($conn,utf8_decode($emp_location)).'",
				"'.$_SESSION['user_login']['id'].'"
			)';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);		
	mysqli_stmt_close($stmt);		

}
else {

	echo json_encode(mysqli_error($conn));
	exit;

};

$action = ($_POST['action'] == 'add') ? 'New Employee Successfully Saved.' : 'Employee Successfully Updated.';
echo json_encode($action);
exit;

?>
