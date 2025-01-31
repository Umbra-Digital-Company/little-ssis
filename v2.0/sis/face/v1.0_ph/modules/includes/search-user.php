<?php 

include("../connect.php");
if(!isset($_SESSION)){
        session_start();
    }

$searchBreakdown["searchword"] = explode(" ", $_GET['s']);
//
//print_r($searchBreakdown);

$searchUser =array();
$querypn="";

 $querypn .="SELECT p.last_name,p.first_name,p.middle_name,p.email_address,profile_id FROM profiles_info p
where p.profile_id!='' ";
	
for($s=0;$s<sizeof($searchBreakdown["searchword"]);$s++){
$querypn .=" and	
 (p.last_name like '%".$searchBreakdown["searchword"][$s]."%' OR p.first_name like  '%".$searchBreakdown["searchword"][$s]."%' OR p.middle_name like  '%".$searchBreakdown["searchword"][$s]."%')";
}

 	$querySearch = $querypn;

$grabParamsUser = array( "last_name","first_name","middle_name","email_address","profile_id" );

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querySearch)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 5; $i++) { 

            $tempArray[$grabParamsUser[$i]] = ${'result' . ($i+1)};

        };

        $searchUser[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};



?>