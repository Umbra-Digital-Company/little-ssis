<?php 

	
function getpricePoll51($item_code){
    global $conn;
    $arrPOll51price=array();
    if($item_code=='fashion_lens'){
    $price = '0';
    }else{
                    

                        $queryItem=" Select price
                                    FROM
                                            poll_51
                                    WHERE
                                        product_code='".$item_code."' ";
                                        
                $grabParams = array("price");

                $stmt = mysqli_stmt_init($conn);
                if (mysqli_stmt_prepare($stmt, $queryItem)) {

                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1);

                while (mysqli_stmt_fetch($stmt)) {

                    $tempArray = array();

                    for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = $result1;

                    };

                    $arrPOll51price[] = $tempArray;

                };

                mysqli_stmt_close($stmt);    
                                            
                }
                $price=$arrPOll51price[0]["price"];

    }

    return $price;

}
function checkSpecsid($order_specs_id){
global $conn;

        $arrOrderspecsid= array();

        $query="select orders_specs_id from orders_specs
        where orders_specs_id='".$order_specs_id."' ";

        $grabParams = array('orders_specs_id');

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < 1; $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrOrderspecsid[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);

        }; 

if($arrOrderspecsid){
    $generate_id = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwyz';
$OrderSpecsId = "";

for ($i=0; $i < 23; $i++) { 

    $OrderSpecsId .=$generate_id[rand(0, (strlen($generate_id)-1))];

};

$OrderSpecsIdF = $_SESSION["store_code"].date('ymd').$OrderSpecsId;

}else{
    $OrderSpecsIdF=$order_specs_id;

}


return $OrderSpecsIdF;


}

function lensfunc($x,$y){
    global $conn;
    $prescriptionvisonArr=array();
        
$query="SELECT product_code FROM poll_51 where item_description='".$x."-".$y."' ";
        
$grabParams = array('product_code');
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 1; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $prescriptionvisonArr [] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 
    $lens_code_data=$prescriptionvisonArr[0]["product_code"];
    
return $lens_code_data;
}



function getSCname($scid,$name){
global $conn;


        $arrSC = array();

         $querySC = "SELECT first_name,last_name,middle_name FROM emp_table where emp_id='".$scid."'";

        $grabParams = array('first_name',
                           'last_name',
                           'middle_name');
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $querySC)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1,$result2,$result3);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < 3; $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };

            $arrSC [] = $tempArray;

        };

        mysqli_stmt_close($stmt);    

    }
    else {

        echo mysqli_error($conn);

    }; 

if($name=="f"){

$sc_name_order=  str_replace(" ","%20",$arrSC[0]["first_name"]);
}else{
$sc_name_order=  str_replace(" ","%20",$arrSC[0]["last_name"]);
}

return $sc_name_order;
}


function trimSpace($string){


$preg_string =preg_replace('/\s+/',' ',$string);

$final_string= str_replace(" ","%20",$preg_string);

return $final_string;

}


$labarray=array(
'102'=>'1',
'103' =>'2',
'104'=>	'3',
'105' =>'2',
'106' =>	'2',
'107' =>	'4',
'108' =>	'2',
'109' =>	'1',
'110' =>	'2',
'111' =>	'6',
'112' =>	'1',
'113' =>	'7',
'114' =>	'2',
'115' =>	'8',
'116' =>	'2',
'117' =>	'3',
'118' =>	'9',
'119' =>	'6',
'120' =>	'3',
'121' =>	'4',
'122' =>	'1',
'123' =>	'10',
'124' =>	'1',
'125' =>	'12',
'126' =>	'12',
'127' =>	'4',
'128' =>	'7',
'129' =>	'4',
'130' =>	'3',
'131' =>	'11',
'132' =>	'8',
'133' =>	'1',
'134' =>	'4',
'135' =>	'13',
'136' =>	'14',
'137' =>	'15',
'138' =>	'2',
'1000' => '888',
'140' => '12',
'142' => '18',
'141' => '14',
'143' => '11',
'144' => '3',
'145' => '20',
'139' => '10'

);


function GetLab($lab_id){
global $conn;
$arrLabname= array();
$query=" SELECT lab_name FROM labs_locations
                WHERE lab_id='".$lab_id."'
                ";
        $grabParams = array('lab_name');
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < 1; $i++) { 

                    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

                };

                $arrLabname [] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);

        }; 
            $lab_name=$arrLabname[0]["lab_name"];
            
        return $lab_name;		

}

?>