<?php

if(!isset($_SESSION)){

    session_start();

}
	
$arrFrames= array();

// $query =   "SELECT 
//                 item_description 
//             FROM 
//                 products p 
//             WHERE 
//                 active='y' 
//             GROUP BY 
//                 item_description 
//             ORDER BY 
//                 item_description";

$querypn="";

$querypn .= "SELECT 
               LOWER(TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1))) AS 'grab_style'
            FROM 
                poll_51 p ";

if(isset($_GET['itempackage'])){

        if($_GET['itempackage']=='framelens' ){

            $querypn .= " WHERE    
                            product_code LIKE '%SS1%'  
                        GROUP BY 
                            grab_style 
                        ORDER BY  
                            grab_style ASC";

            }
            elseif($_GET['itempackage']=='lensonly' ){

                $querypn .= " WHERE product_code='x'  
                            GROUP BY 
                                grab_style  
                            ORDER BY 
                                grab_style ASC";

            }
            elseif($_GET['itempackage']=='frameonly' ){

                $querypn .=" WHERE   
                                product_code LIKE '%SS1%'  
                            GROUP BY 
                                grab_style  
                            ORDER BY 
                                grab_style ASC";

            }
            elseif($_GET['itempackage']=='merch' ){

                $querypn .= " WHERE    
                                product_code LIKE 'MC%' 
                                    OR product_code LIKE 'MGC%' 
                                    OR product_code LIKE 'MLBC%' 
                                    OR product_code LIKE 'MH%' 
                                    OR product_code LIKE 'MCK%' 
                                    OR product_code LIKE 'MSPVHC%'
                                    AND price!='0'   
                            ";
            }
        }     
else{

    $querypn .=" WHERE   
                    product_code LIKE '%SS1%' ";

}
                               
$query = $querypn;

$grabFrames = array("item_description");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 1; $i++) { 

            $tempArray[$grabFrames[$i]] = ${'result' . ($i+1)};

        };

        $arrFrames[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

};

echo '<pre>';
// print_r($arrFrames);
echo '</pre>';

if(isset($_GET)){
	
}

?>