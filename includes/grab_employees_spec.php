<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB EMPLOYEE

// Set array
$arrEmployee = array();

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

$query  = 	"SELECT					
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
				emp.department = 'RETAIL OPERATIONS'
					AND emp.emp_id = '".( $_GET['emp_id'] )."'";
			
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrEmployee[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>