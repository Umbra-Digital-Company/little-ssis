<?php 



$arrProfilesInfo = array();

$arrSearch = explode(" ", $_GET['search']);

$querySearch = "";

if(isset($_GET['date']) && $_GET['date'] != 'custom' && $_GET['date'] != '') {

    switch ($_GET['date']) {

        case 'yesterday':       
            $today = date('Y-m-d');
            $yesterdayinit = date('Y-m-d', strtotime($today . "-1 day"));
            $qGrabDateA = date('d', strtotime($yesterdayinit));
            $qGrabDateB = date('m', strtotime($yesterdayinit));
            $qGrabDateC = date('Y', strtotime($yesterdayinit));
            $qDate =    "DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = ".$qGrabDateA." 
                            AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateB."
                            AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateC;
            break;
            
        case 'day':
            $qGrabDateA = date("d");
            $qGrabDateB = date("m");
            $qGrabDateC = date("Y");
            $qDate =    "DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = ".$qGrabDateA." 
                            AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateB."
                            AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateC;
            break;

        case 'week':
            $qGrabDateA = date("Y-m-d");
            $qDate =    "YEARWEEK(DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y-%m-%d'), 1) = YEARWEEK('".$qGrabDateA."', 1)";
            break;

        case 'month':
            $qGrabDateA = date("m");
            $qGrabDateB = date("Y");
            $qDate =    "DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateA."
                            AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateB;
            break;
        
        case 'year':
            $qGrabDate = date("Y");
            $qDate = "DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDate;
            break;

        case 'all-time':
            $qGrabDate = date("Y");
            $qDate =    "DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') <= ".$qGrabDate;
            break;

    }   

}
elseif(isset($_GET['data_range_start_month'])) {

    // Set start date
    $dateStart = $_GET['data_range_start_year']."-".( sprintf("%02d", $_GET['data_range_start_month']) )."-".( sprintf("%02d", $_GET['data_range_start_day']) );

    $qDateA =   "DATE_ADD(date_created, INTERVAL 12 HOUR) >= '".$dateStart."'";

    if(isset($_GET['data_range_end_month'])) {

        // Set end date
        $dateEnd = $_GET['data_range_end_year']."-".( sprintf("%02d", $_GET['data_range_end_month']) )."-".( sprintf("%02d", $_GET['data_range_end_day']) );

        $qDateA = " DATE_ADD(date_created, INTERVAL 12 HOUR) >= '".$dateStart." 00:00:00' AND DATE_ADD(date_created, INTERVAL 12 HOUR) <=  '".$dateEnd." 23:59:59'";

    }
    else {

        $dateEnd = "";
        $qDateA = "";

    };

    $qDate = $qDateA;

}
else {

    $qGrabDateA = date("d");
    $qGrabDateB = date("m");
    $qGrabDateC = date("Y");
    $qDate =    "DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%d') = ".$qGrabDateA." 
                    AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%m') = ".$qGrabDateB."
                    AND DATE_FORMAT(DATE_ADD(DATE_FORMAT(date_created, '%Y-%m-%d %H:%i'), INTERVAL 12 Hour), '%Y') = ".$qGrabDateC;
    
    // $qDate = "date_created IS NOT NULL";

};
$query="SELECT  first_name,
                middle_name,
                last_name,
                phone_number,
                birthday,
                email_address,
                DATE_ADD(date_created, INTERVAL 12 HOUR)
            FROM
            profiles_info
            WHERE 
            ".$qDate;
if(isset($_GET['filterStores'])){
    $query .=" AND branch_applied IN (".implode(',', $_GET['filterStores']).")";
}else{
    $query .=" AND branch_applied IN (142,150)";
}
    $query .=" ORDER BY date_created";
if(!isset($_GET['date'])){
    $query .=" LIMIT 100";
}

                
        $grabParams = array(

            'first_name',
            'middle_name',
            'last_name',
            'phone_number',
            'birthday',
            'email_address',
            'date_created'
        
        );

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6,
     $result7);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrProfilesInfo[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

//print_r($arrProfilesInfo); exit;
?>