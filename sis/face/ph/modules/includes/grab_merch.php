<?php

if(!isset($_SESSION)){

    session_start();

}
	
$arrMerch = array();

$queryMerch =   "SELECT
                    item_description,
                    product_code,
                    price 
                FROM 
                    poll_51 p
                WHERE 
                    product_code LIKE 'MC1016'
                        OR product_code LIKE 'MC1015'
                        OR product_code LIKE 'MGC%'
                        OR product_code LIKE 'MLBC%'
                        OR product_code LIKE 'MHC%' 
                        OR product_code LIKe 'MH1007'
                        OR product_code LIKe 'MH1008'
                        OR product_code LIKE 'MCK%' 
                        OR product_code LIKE 'MSPVHC%' 
                        OR product_code LIKE 'PL008-A'
                        OR product_code LIKE 'PL009-A'
                        AND price!='0'";

$grabMerch= array(
    "item_description",
    "product_code",
    "price"
);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryMerch)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1,$result2,$result3);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 3; $i++) { 

            $tempArray[$grabMerch[$i]] = ${'result' . ($i+1)};

        };

        $arrMerch[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

// Set sort array
$arrMerchSorted = array(

    "Agendas" => array(),
    "Care Kits" => array(),
    "Hard Cases" => array(),
    "Kids Hard Cases" => array(),
    "Velvet Hard Cases" => array(),
    "Cloths" => array(),
    "Gift Cards" => array()

);

// Sort through merch array and push to sorted array
for ($i=0; $i < sizeOf($arrMerch); $i++) { 

    // Set current data
    $curItemName = $arrMerch[$i]['item_description'];
    $curProductCode = $arrMerch[$i]['product_code'];
    $curProductPrice = $arrMerch[$i]['price'];
    
    if(strpos(strtolower($curItemName), 'agenda') !== false) {

        array_push($arrMerchSorted['Agendas'], $arrMerch[$i]);

    }
    elseif(strpos(strtolower($curItemName), 'care kit') !== false) {

        array_push($arrMerchSorted['Care Kits'], $arrMerch[$i]);

    }
    elseif(strpos(strtolower($curItemName), 'velvet hardcase') !== false) {

        array_push($arrMerchSorted['Velvet Hard Cases'], $arrMerch[$i]);

    }
    elseif(strpos(strtolower($curItemName), 'kids hard case') !== false) {

        array_push($arrMerchSorted['Kids Hard Cases'], $arrMerch[$i]);

    }
    elseif(strpos(strtolower($curItemName), 'hard case') !== false || strpos(strtolower($curItemName), 'hardcase') !== false) {

        array_push($arrMerchSorted['Hard Cases'], $arrMerch[$i]);

    }
    elseif(strpos(strtolower($curItemName), 'cloth') !== false) {

        array_push($arrMerchSorted['Cloths'], $arrMerch[$i]);

    }
    elseif(strpos(strtolower($curItemName), 'specs-gc') !== false) {

        array_push($arrMerchSorted['Gift Cards'], $arrMerch[$i]);

    }

};

?>
