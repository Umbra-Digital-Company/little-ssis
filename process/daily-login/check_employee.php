<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/date_convert.php";

if($_GET['action'] == 'daily_search'){
    $arrEmpId = array();

    $query  =   "SELECT
                   emp_id,
                   store_code
                FROM
                    daily_login
                WHERE daily_date = '".mysqli_real_escape_string($conn,$_GET['date'])."'
                AND daily_out = 0;";

    //echo $query;
    $grabParams = array(
        'emp_id',
        'store_code'
    );

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };
            $tempArray['check_store_code'] = ($tempArray['store_code'] == $_SESSION['user_login']['store_code']) ? true : false;
            $arrEmpId[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    }; 
    echo json_encode($arrEmpId);
}elseif($_GET['action'] == 'search_employee'){
    $arrEmpId = array();

    $query  =   "SELECT
                   emp_id,
                   CONCAT_WS(' ', first_name, CONCAT(LEFT(middle_name, 1),'.'), last_name ),
                   designation
                FROM
                    emp_table
                WHERE emp_id = '".mysqli_real_escape_string($conn,$_GET['employee_id'])."'
                    AND status = 'Y';";

    //echo $query;
    $grabParams = array(
        'emp_id',
        'name',
        'designation'
    );

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };
            $tempArray['name'] = utf8_encode($tempArray['name']);
            $tempArray['submit'] = ( strstr(strtolower($tempArray['designation']), 'manager') || strstr(strtolower($tempArray['designation']), 'supervisor') || strstr(strtolower($tempArray['designation']), 'optometrist') || strstr(strtolower($tempArray['designation']), 'doctor') ) ? false : true;
            $arrEmpId[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    };

    $query  =   "SELECT
                       COUNT(emp_id)
                    FROM
                        daily_login
                    WHERE
                        emp_id = '".mysqli_real_escape_string($conn,$_GET['employee_id'])."'
                        AND daily_in = 1 AND daily_out = 1 AND daily_date = '".date('Y-m-d',strtotime(date('Y-m-d H:i:s').' +12  hours'))."';";
        $grabParams = array(
            'emp_id'
        );

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrEmpId[0]['employee_log'] = $tempArray['emp_id'];

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);

        };

    echo json_encode($arrEmpId);
}elseif($_GET['action'] == 'search_login'){
    $arrEmpId = array();
    $arrAccessPosition = ['admin', 'supervisor'];
    if(in_array($_SESSION['user_login']['position'], $arrAccessPosition)){
        $month = mysqli_real_escape_string($conn,($_GET['month']+1));
        $year = mysqli_real_escape_string($conn,$_GET['year']);

        $query  =   "SELECT
                       COUNT(emp_id),
                       daily_date
                    FROM
                        daily_login
                    WHERE 
                        MONTH(daily_date) IN ('".$month."','".($month-1)."','".($month+1)."')
                        AND YEAR(daily_date) = '".$year."'
                        ";
                    $query .=($_SESSION['user_login']['position'] =='supervisor') ? " AND store_code IN (".$_SESSION['user_login']['store_location'].") OR  store_code = '".$_SESSION['user_login']['store_code']."'" : '';
                    $query .=" GROUP BY daily_date;";
    }else{
        $month = mysqli_real_escape_string($conn,($_GET['month']+1));
        $year = mysqli_real_escape_string($conn,$_GET['year']);
        $query  =   "SELECT
                       COUNT(emp_id),
                       daily_date
                    FROM
                        daily_login
                    WHERE 
                        MONTH(daily_date) IN ('".$month."','".($month-1)."','".($month+1)."')
                        AND YEAR(daily_date) = '".$year."'
                        AND store_code = '".$_SESSION['user_login']['store_code']."' GROUP BY daily_date;";
                    
                    
    }

        // echo $query;
        $grabParams = array(
            'count_emp_id',
            'date'
        );

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };
                $tempArray['start'] = $tempArray['date'];
                $tempArray['end'] = $tempArray['date'];
                $arrEmpId[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);

        };
    echo json_encode($arrEmpId);
}elseif($_GET['action'] == 'get_employee'){
    $arrEmployee = array();

    if($_SESSION['user_login']['position']=='admin'){
        $queryfilter ="";

    }elseif($_SESSION['user_login']['position']=='supervisor'){
        $queryfilter=" AND s.store_id IN (".$_SESSION['user_login']['store_location'].") OR store_code = '".$_SESSION['user_login']['store_code']."'";
    }else{
        $queryfilter=" AND dl.store_code = '".$_SESSION['user_login']['store_code']."'";
    }

    $query  =   "SELECT
                    dl.date_created,
                    dl.device,
                    e.emp_id,
                    CONCAT_WS(' ', e.first_name, CONCAT(LEFT(e.middle_name, 1),'.'), e.last_name ),
                    e.gender,
                    e.department,
                    e.designation,
                    e.location,
                    s.store_name,
                    dl.latitude,
                    dl.longitude
                FROM
                    daily_login dl
                    LEFT JOIN emp_table e ON dl.emp_id = e.emp_id
                    LEFT JOIN stores_locations s  ON s.store_id=dl.store_code
                WHERE 
                    dl.daily_date = '". mysqli_real_escape_string($conn,$_GET['date'])."' 
                    ".$queryfilter." ORDER BY dl.date_created, s.store_name
                      ";

    // echo $query;
    $grabParams = array(
        'date_created',
        'device',
        'emp_id',
        'name',
        'gender',
        'department',
        'designation',
        'location',
        'store_name',
        'latitude',
        'longitude'
    );

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {
        
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
            };

            $tempArray['name'] = utf8_encode($tempArray['name']);
            $tempArray['date_created'] = dateTimeConvert(date('Y-m-d H:i:s', strtotime($tempArray['date_created']. ' +12 hour')));
        
            $arrEmployee[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);

    }; 
    echo json_encode($arrEmployee);
}
?>
