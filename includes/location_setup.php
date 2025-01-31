<?php

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



function grabMaster($x) {



    global $conn;



    $provinceSearch = $x;



    $courier = "";



    // Check master
 $checkQuery =   "SELECT

                        ccm.province,

                        ccm.courier

                    FROM

                        countries_checkout_master ccm

                    WHERE

                        ccm.province = '".$provinceSearch."'

                    ORDER BY

                        ccm.province ASC;";



    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $checkQuery)) {



        mysqli_stmt_execute($stmt);

        mysqli_stmt_bind_result($stmt, $result1, $result2);

        mysqli_stmt_fetch($stmt);

        mysqli_stmt_store_result($stmt);



        $courier = $result2;



        mysqli_stmt_close($stmt);



    };



    $mCourier = $courier;



};



if(isset($_GET["check_province"])) {



    grabMaster($_GET["check_province"]);



    $province = $_GET["check_province"];

    $province = $province;

    $sC = "city";

    $sO = "city";

    $grabParams = array("province", "city");
	if($province=='metro-manila'){
		 $queryCheckoutA =   "SELECT DISTINCT

                            cc.province, 

                            cc.city

                        FROM 

                            countries_checkout_ninja_van cc 

                        WHERE 

                            cc.province = '".$province."' 

                                AND cc.province <> 'n/a' 

                        ORDER BY 

                            cc.province ASC, cc.city ASC"; 
		
	}else{

  $queryCheckoutA =   "SELECT DISTINCT

                            cc.province, 

                            cc.city

                        FROM 

                            countries_checkout_acommerce cc 

                        WHERE 

                            cc.province = '".$province."' 

                                AND cc.province <> 'n/a' 

                        ORDER BY 

                            cc.province ASC, cc.city ASC"; 
}
 $queryCheckoutB =   "SELECT DISTINCT

                            ccnv.province, 

                            ccnv.city

                        FROM 

                            countries_checkout_acommerce ccnv 

                        WHERE 

                            ccnv.province = '".$province."' 

                                AND ccnv.province <> 'n/a' 

                        ORDER BY 

                            ccnv.province ASC, ccnv.city ASC";                            

    $grabRes = 2;

    $get = 'p';



}

else if(isset($_GET["check_state"])) {



    $state = $_GET["check_state"];

    $state = $state;

    $sC = "city";

    $sO = "city";

    $grabParams = array("state", "city");

    $queryCheckoutA =   "SELECT DISTINCT 

                            cc.state,

                            cc.city

                        FROM 

                            countries_checkout cc 

                        WHERE 

                            cc.state = '".$state."' 

                                AND cc.state <> 'n/a' 

                        ORDER BY 

                            cc.city";

    $queryCheckoutB =   "SELECT DISTINCT 

                            ccnv.state,

                            ccnv.city

                        FROM 

                            countries_checkout_ninja_van ccnv 

                        WHERE 

                            ccnv.state = '".$state."' 

                                AND ccnv.state <> 'n/a' 

                        ORDER BY 

                            ccnv.city";

    $grabRes = 2;

    $get = 's';



}

else if(isset($_GET["check_city"])) {


  $province = $_GET["check_province2"];

    $city = $_GET["check_city"];

    $city = $city;

    $sC = "barangay";

    $sO = "barangay";

    $grabParams = array("id", "city", "barangay", "courier");
	if($province=='metro-manila'){
	 $queryCheckoutA =   "SELECT DISTINCT 

                            cc.id,

                            cc.city, 

                            cc.barangay,

                            cc.courier

                        FROM 

                             countries_checkout_ninja_van cc 

                        WHERE 

                            cc.city = '".$city."' 

                                AND cc.city <> 'n/a' 

                        ORDER BY 

                            cc.barangay ASC,

                            cc.courier ASC";
	}
	else{

    $queryCheckoutA =   "SELECT DISTINCT 

                            cc.id,

                            cc.city, 

                            cc.barangay,

                            cc.courier

                        FROM 

                             countries_checkout_acommerce cc 

                        WHERE 

                            cc.city = '".$city."' 

                                AND cc.city <> 'n/a' 

                        ORDER BY 

                            cc.barangay ASC,

                            cc.courier ASC";
}
	
    $queryCheckoutB =   "SELECT DISTINCT 

                            ccnv.id,

                            ccnv.city, 

                            ccnv.barangay,

                            ccnv.courier

                        FROM 

                            countries_checkout_acommerce ccnv

                        WHERE 

                            ccnv.city = '".$city."' 

                                AND ccnv.city <> 'n/a' 

                        ORDER BY 

                            ccnv.barangay ASC,

                            ccnv.courier ASC";                            

    $get = 'c';



}

else {



    $grabParams = array("province");

    $queryCheckoutA =   "SELECT DISTINCT

                            cc.province

                        FROM

                            countries_checkout_master cc

                        WHERE

                            cc.province <> 'n/a'

                        ORDER BY

                            cc.province ASC";

    $queryCheckoutB =   "SELECT DISTINCT

                            ccnv.province

                        FROM 

                            countries_checkout_ninja_van ccnv 

                        WHERE

                            ccnv.province <> 'n/a'

                        ORDER BY

                            ccnv.province ASC";

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



sendQuery($queryCheckoutA, "");

//sendQuery($queryCheckoutB, "ninja_van");



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

        echo    '<select class="col-md-12 text-left s-a form-control" name="barangay" id="barangay">';

                



        echo '<option value="n"';



        if($_GET['selected_city'] == "") {



            echo ' selected';



        };



        echo ' disabled>'.ucfirst($sO).'</option>';



        for ($i=0; $i < sizeOf($arrCCAllSorted); $i++) { 



            echo    '<option data-courier="'.array_values($arrCCAllSorted)[$i]["courier"].'" value="'.array_values($arrCCAllSorted)[$i][$sO].'"';



            if($_GET['selected_barangay'] == array_values($arrCCAllSorted)[$i][$sO]) {



                echo ' selected';



            };


            echo '>'.ucwords(str_replace("-", " ", array_values($arrCCAllSorted)[$i][$sO])).'</option>';



        };



        echo    '</select>';



    }

    else {



        // Echo select options

        echo    '<select class="col-md-12 text-left s-a form-control" name="city" id="city">';

        

        echo        '<option value="n"';



        if($_GET['selected_city'] == "") {



            echo        ' selected';



        };



        echo        ' disabled>'.ucfirst($sO).'</option>';



        for ($i=0; $i < sizeOf($arrCC); $i++) {



            echo '<option value="'.$arrCC[$i][$sO].'"';


			if($_GET['id']!='') {
				
				if($arrCC[$i][$sO] ==$arrStore[0]["city"] ){
					
						echo 'selected';
				}
				
			}

            if($_GET['selected_city'] == $arrCC[$i][$sO]) {



                echo ' selected';



            };



            echo '>'.ucwords(str_replace("-", " ", $arrCC[$i][$sO])).'</option>';



        };



        echo    '</select>';



    };

   



};


?>