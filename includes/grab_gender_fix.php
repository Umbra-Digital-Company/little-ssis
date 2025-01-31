<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Set array
$arrProfiles = [];

$grabParams = array(	
	"profile_id",
	"branch_applied",
	"first_name",
	"middle_name",
	"last_name",
	"gender",
	"date_created",
	'branch'
);
$search = (isset($_GET['search'])) ? $_GET['search'] : date('Y-m-d');

$query  = 	"SELECT 
			pi.profile_id,
			pi.branch_applied,
			pi.first_name,
			pi.middle_name,
			pi.last_name,
			pi.gender,
			pi.date_created,
			sc.branch
			FROM profiles_info pi
				LEFT JOIN orders_specs os ON os.profile_id=pi.profile_id
				LEFT JOIN store_codes sc ON pi.branch_applied = sc.location_code
 				WHERE pi.email_address NOT LIKE 'specsguest%'
 					AND (pi.gender LIKE 'N/Ax' OR pi.gender='')
  					AND pi.profile_id LIKE '%-%'
					AND date(os.payment_date) >= '".$search."'
					and os.dispatch_type!='packaging'
					ORDER BY os.`date_created` DESC";
			
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrProfiles[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

?>