<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////////////////////////////////////// GRAB DATA AND SET LIMITS

////////////////////////////// SEARCH

// Set search term
$arrSearch = explode("+", $_GET['search']);
$querySearch = "";

if(!empty($arrSearch)) {

	for ($i=0; $i < sizeOf($arrSearch); $i++) { 
	
		$querySearch .= " AND (
							emp.last_name LIKE '%".$arrSearch[$i]."%' 
								OR emp.first_name LIKE '%".$arrSearch[$i]."%' 
								OR emp.middle_name LIKE '%".$arrSearch[$i]."%'
								OR emp.emp_id LIKE '%".$arrSearch[$i]."%'
	 					)";

	};	

};

//////////////////////////////////////////////////////////////////////////////////// GRAB PAGE NUMBERS

$totalNumberOfEmployees = 0;

$query = 	"SELECT					
				COUNT(emp.emp_id)
			FROM 
				emp_table emp
			WHERE
				emp.department = 'RETAIL OPERATIONS'
				".$querySearch;

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

	mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);
    mysqli_stmt_fetch($stmt);
	mysqli_stmt_store_result($stmt);

	$totalNumberOfEmployees = $result1;

	mysqli_stmt_close($stmt);

}
else {

	echo mysqli_error($conn);
	exit;

};

// Calculate pages
$numberPages = ceil($totalNumberOfEmployees / 100);

// Set Limit
if(isset($_GET['page'])) {

	$pageNum = $_GET['page'];
	$queryLimit = " LIMIT ".( ($pageNum - 1) * 100 ).", 100;";

}
else {

	$pageNum = 1;
	$queryLimit = " LIMIT 100;";

};

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
				".$querySearch."
			ORDER BY
				emp.emp_id
			".$queryLimit;
			
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