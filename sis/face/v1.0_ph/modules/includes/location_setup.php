<?php
if(!isset($_SESSION)){

    session_start();

}

if(!isset($conn)){

	include("../connect.php");

};

function checkDataLocation($x, $y = NULL) {

	if($x != "") {

		return true;

	}

	else {

		return false;	

	};

};

// Grab variables
$province;
$state;
$city;
$sC;
$grabParams;
$queryCheckout;

// Master courier variable
$mCourier = "all";
$get      = 'n';

// Set result count
$grabRes = 4;

if(isset($_GET["check_province"])) {

    $province = $_GET["check_province"];
    $province = $province;
    $sC = "city";
    $sO = "city";
    $grabParams = array("province", "city");

    $queryCheckoutA =   "SELECT DISTINCT
                            ccp.province, 
                            ccp.city
                        FROM 
                            countries_checkout_payo ccp
                        WHERE 
                            ccp.province = '".$province."'
                                AND ccp.province <> 'n/a' 
                        UNION ALL
                        SELECT DISTINCT
                            ccpb.province, 
                            ccpb.city
                        FROM 
                            countries_checkout_payo_b ccpb                            
                        WHERE 
                            ccpb.province = '".$province."'
                                AND ccpb.province <> 'n/a'
                        ORDER BY 
                            province ASC, city ASC"; 
                      

    $grabRes = 2;
    $get = 'p';

}
else if(isset($_GET["check_city"])) {

    $province = $_GET["check_province2"];
    $city = $_GET["check_city"];
    $city = $city;
    $sC = "barangay";
    $sO = "barangay";
    $grabParams = array("id", "city", "barangay", "courier");

	$queryCheckoutA =    "SELECT DISTINCT
                            ccp.id,
                            ccp.city, 
                            ccp.barangay,
                            ccp.courier
                        FROM 
                            countries_checkout_payo ccp
                        WHERE 
                            ccp.city = '".$city."' 
                                AND ccp.city <> 'n/a'
                        UNION ALL
                        SELECT DISTINCT
                            ccpb.id,
                            ccpb.city, 
                            ccpb.barangay,
                            ccpb.courier
                        FROM 
                            countries_checkout_payo_b ccpb                            
                        WHERE 
                            ccpb.city = '".$city."' 
                                AND ccpb.city <> 'n/a'
                        ORDER BY 
                            barangay ASC,
                            courier ASC";                           

    $get = 'c';

}
else {

    $grabParams = array("province");

    $queryCheckoutA =   "SELECT DISTINCT
                            ccp.province
                        FROM 
                            countries_checkout_payo ccp
                        WHERE 
                            ccp.province <> 'n/a' 
                        UNION ALL
                        SELECT DISTINCT
                            ccpb.province
                        FROM 
                            countries_checkout_payo_b ccpb                            
                        WHERE 
                            ccpb.province <> 'n/a'
                        ORDER BY 
                            province ASC";

    $grabRes = 1;

};

// Product array to hold all results
$arrCC = array();
$arrCCAll = array();
$arrCCAllSorted = array();

function sendQuery($x, $y) {

    global $conn;
    global $arrCCAll;
    global $grabParams;
    global $grabRes;
    global $province;

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $x)) {

        mysqli_stmt_execute($stmt);

        if($grabRes == 4) {

            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

        }
        else if($grabRes == 2) {

            mysqli_stmt_bind_result($stmt, $result1, $result2);

        }
        else {

            mysqli_stmt_bind_result($stmt, $result1);

        }    

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < $grabRes; $i++) { 

                $tempArray['courier']       = $y;
                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};                

            };

            $arrCCAll[] = $tempArray;

        };

        mysqli_stmt_close($stmt);

    }
    else {

        echo mysqli_error($conn);

    };

};

sendQuery($queryCheckoutA, "payo");

// Sort through the arrays
for ($i=0; $i < sizeOf($arrCCAll); $i++) { 

    switch ($get) {

        case 'p':

            $arrCCAllSorted[$arrCCAll[$i]["city"]]["province"] = $arrCCAll[$i]["province"];
            $arrCCAllSorted[$arrCCAll[$i]["city"]]["city"]     = $arrCCAll[$i]["city"];
            $arrCCAllSorted[$arrCCAll[$i]["city"]]["courier"]  = $arrCCAll[$i]["courier"];

            break;

        case 's':

            $arrCCAllSorted[$arrCCAll[$i]["city"]]["state"]    = $arrCCAll[$i]["state"];
            $arrCCAllSorted[$arrCCAll[$i]["city"]]["city"]     = $arrCCAll[$i]["city"];
            $arrCCAllSorted[$arrCCAll[$i]["city"]]["courier"]  = $arrCCAll[$i]["courier"];

            break;

        case 'c':            

            $arrCCAllSorted[$arrCCAll[$i]["barangay"]]["city"]     = $arrCCAll[$i]["city"];
            $arrCCAllSorted[$arrCCAll[$i]["barangay"]]["barangay"] = $arrCCAll[$i]["barangay"];
            $arrCCAllSorted[$arrCCAll[$i]["barangay"]]["courier"]  = $arrCCAll[$i]["courier"];

            break;

        

        default:

            $arrCCAllSorted[$arrCCAll[$i]["province"]]["province"] = $arrCCAll[$i]["province"];        
            $arrCCAllSorted[$arrCCAll[$i]["province"]]["courier"]  = $arrCCAll[$i]["courier"];
            array_multisort($arrCCAllSorted, SORT_ASC);

            break;

    };

};

$arrCCAllSorted = array_values($arrCCAllSorted);

$arrCC = $arrCCAllSorted;
if(isset($_GET["grab_select"])) {

        if($_GET["grab_select"] == "b") {

        // Echo select options
        echo    '<select class="col-md-12 text-left select s-a form-control" name="barangay" id="barangay">';
        echo        '<option value="n"';

        if(isset($_GET['selected_city']) && $_GET['selected_city'] == "") {

            echo    ' selected';

        };

        echo        '>-</option>';

        for ($i=0; $i < sizeOf($arrCCAllSorted); $i++) { 

            echo    '<option data-courier="'.array_values($arrCCAllSorted)[$i]["courier"].'" value="'.array_values($arrCCAllSorted)[$i][$sO].'"';

            if($_GET['selected_barangay'] == array_values($arrCCAllSorted)[$i][$sO]) {

                echo ' selected';

            };

            echo    '>'.ucwords(str_replace("-", " ", array_values($arrCCAllSorted)[$i][$sO])).'</option>';

        };

        echo    '</select>';
        echo '<label class="placeholder" for="barangay">Barangay</label>';

    }

    else {

        // Echo select options
        echo    '<select class="col-md-12 text-left select s-a form-control" name="city" id="city">';
        echo        '<option value="n"';

        if($_GET['selected_city'] == "") {

            echo    ' selected';

        };

        echo        ' disabled>-</option>';

        for ($i=0; $i < sizeOf($arrCC); $i++) {

            echo '<option value="'.$arrCC[$i][$sO].'"';

            if($_GET['selected_city'] == $arrCC[$i][$sO]) {

                echo ' selected';

            };

            echo '>'.ucwords(str_replace("-", " ", $arrCC[$i][$sO])).'</option>';

        };

        echo    '</select>';
        echo '<label class="placeholder" for="city">City</label>';

    };

   

};
?>