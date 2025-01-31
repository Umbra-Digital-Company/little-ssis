<?php 

if(!isset($_SESSION)){

    session_start();

};

function grab_frame_colors($style,$type) {
    global $conn;

    $arrcolor= array();
$querypn ="";

    $querypn .=    "SELECT     
                    LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)), '')) AS 'grab_color',
                    product_code,
                    LOWER(TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1))) AS 'grab_style' ,
                    p.price
                FROM 
                    poll_51 p 
                 
                ";

   if(isset($type)){
        if($type=='framelens' ){
            $querypn .=" WHERE   product_code LIKE '%SS1%'
                        AND TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) = '".$style."' ";
            }
            elseif($type=='lensonly' ){
                $querypn .=" ";
            }
            elseif($type=='frameonly' ){
                $querypn .=" WHERE  product_code LIKE '%SS1%'
                AND TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) = '".$style."'  ";
            }
            elseif($type=='merch' ){
                $querypn .=" WHERE  product_code LIKE 'MC%' 
                OR product_code LIKE 'MGC%' 
                OR product_code LIKE 'MLBC%' 
                OR product_code LIKE 'MH%' 
                OR product_code LIKE 'MCK%' 
                OR product_code LIKE 'MSPVHC%'
                and price!='0'
                 ";
            }
            else{
                $querypn .="  WHERE   product_code LIKE '%SS1%'
                AND TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) = '".$style."' ";
            }
        }     
    else{
        $querypn .="  WHERE   product_code LIKE '%SS1%'
        AND TRIM(LEFT(item_name , LOCATE(' ', item_name) - 1)) = '".$style."' ";
    }


$querypn .=" ORDER BY 
                    grab_color ASC";

            $query = $querypn;

    $grabcolor = array("color", "product_code", "item_description","price");
    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < 4; $i++) { 

                $tempArray[$grabcolor[$i]] = ${'result' . ($i+1)};

            };

            $arrcolor[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    

        // echo '<pre>';
        // print_r($arrcolor);
        // echo '</pre>';

        for ($i = 0; $i<sizeof($arrcolor); $i++) {

            $img_url = "";
            if ( file_exists('./images/specs/'.$arrcolor[$i]['item_description'].'/'.str_replace(" ", "-", trim($arrcolor[$i]['color'])).'/front.png') ) {
                $img_url = './images/specs/'.$arrcolor[$i]['item_description'].'/'.str_replace(" ", "-", trim($arrcolor[$i]['color'])).'/front.png';
            } else {
                $img_url = './images/specs/no-image/no_specs_frame_available_b.png';
            }

            if ( $type != 'merch' ) { 

                echo    '<div class="col-4 form-group">';
                echo        '<input type="radio" name="frame_style" class="sr-only frame-option" id="'.$style.'-'.str_replace(' ','-',$arrcolor[$i]['color']).'" value="'.$arrcolor[$i]['product_code'].'" />';
                echo        '<label for="'.$style.'-'.str_replace(' ','-',$arrcolor[$i]['color']).'" class="list-item frame-grid d-flex flex-column align-items-center justify-content-center">';
                echo            '<img src="'.$img_url.'" alt="'.$style.'-'.$arrcolor[$i]['color'].'" class="img-fluid" />';
                echo            '<section class="text-center mt-2">';
                echo                '<p class="font-bold style">'.ucwords($style).'</p>';
                echo                '<p class="text-center color">'.ucwords($arrcolor[$i]['color']).'</p>';
                echo            '</section>';
                echo        '</label>';
                echo    '</div>';

            } else {
                
                echo    '<div class="col-4 form-group">';
                echo        '<input type="radio" name="frame_style" class="sr-only frame-option" id="'.$style.'-'.str_replace(' ','-',$arrcolor[$i]['color']).'" value="'.$arrcolor[$i]['product_code'].'" />';
                echo        '<label for="'.$style.'-'.str_replace(' ','-',$arrcolor[$i]['color']).'" class="list-item frame-grid d-flex flex-column align-items-center justify-content-between">';
                echo            '<section class="text-center">';
                echo                '<p class="font-bold style">'.ucwords($style).'</p>';
                echo                '<p class="text-center color">'.ucwords($arrcolor[$i]['color']).'</p>';
                echo            '</section>';
                echo            '<p class="font-bold text-primary">P'.$arrcolor[$i]['price'].'</p>';
                echo        '</label>';
                echo    '</div>';

            }
        }
                                
    } else {

        echo mysqli_error($conn);

    };

}

?>