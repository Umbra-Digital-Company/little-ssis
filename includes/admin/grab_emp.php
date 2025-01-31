<?php
if(!isset($_SESSION)){
        session_start();
    }

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];





if(isset($_GET['s'])){

$SearchItemWord = mysqli_real_escape_string($conn,strip_tags(str_replace("+", " ", str_replace('"','',$_GET['s']))));


	$searchBreakdown["searchword"] = explode(" ", $SearchItemWord);
}

$grabParams = array(
	"id", 
	"emp_no",
	"first_name", 
	"middle_name",
	"last_name",
	"gender",
	"civil_status",
	"birthdate", 
	"mobile_number", 
	"address",
	"email_address",
	"department",
	"brand",
	"location", 
	"designation",
	"employment_level",
	"sub_unit",
	"employment_status", 
	"start_date",
	"regularization_date",
	"sss",
	"tin", 
	"philhealth",
	"hdmf", 
	"prc", 
	"expiry_date",
	"educational_attainment",
	"last_school_attended",
	"year_graduated",
	"working_hours"
	);


$arrEmp= array();
$queryEmpN ="";
		$queryEmpN .="SELECT  `id`, 
							`emp_no`,
							`first_name`, 
							`middle_name`,
							`last_name`,
							`gender`,
							`civil_status`,
							`birthdate`, 
							`mobile_number`, 
							`address`,
							`email_address`,
							`department`,
							`brand`,
							`location`, 
							`designation`,
							`employment_level`,
							`sub_unit`,
							`employment_status`, 
							`start_date`,
							`regularization_date`,
							`sss`,
							`tin`, 
							`philhealth`,
							`hdmf`, 
							`prc`, 
							`expiry_date`,
							`educational_attainment`,
							`last_school_attended`,
							`year_graduated`,
							`working_hours`
			FROM `admin_employee` WHERE 
			emp_no !=''
			";
				if(isset($_GET['profile_id'])){

					$queryEmpN .=" and emp_no='".$_GET['profile_id']."' ";

				}
		if(isset($_GET['s'])){
			
			for($y=0 ;$y<sizeof($searchBreakdown["searchword"]);$y++){
					$queryEmpN .=" and (first_name LIKE '%".$searchBreakdown["searchword"][$y]."%' OR middle_name LIKE '%".$searchBreakdown["searchword"][$y]."%' OR last_name LIKE '%".$searchBreakdown["searchword"][$y]."%' )  ";
			}
		}

$queryEmp = $queryEmpN;
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryEmp)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 30; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrEmp[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>