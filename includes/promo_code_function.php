<?php 


function  PromoCodeUsed($promo_code,$order_id){

            global $conn;

            $arrPromoUsed=array();


                    $query ="Select o.order_id,
                                    pcu.code,
                                    pc.expiration_date,
                                    pc.amount_ph,
                                    pc.amount_us,
                                    pc.number_uses,
                                    pc.active,
                                    o.email_address,
                                    o.date_created,
                                    pc.c_type_ph,
                                    pc.percentage_ph
                                    
                    from  promo_codes_users  pcu
                    left join orders  o ON pcu.code=o.promo_code 
                    LEFT JOIN promo_codes pc on pc.promo_code = pcu.code
                    WHERE pcu.code='".$promo_code."' 
                    and o.order_id='".$order_id."' 
                    group by order_id";




                    $grabParams = array(
                        "order_id",
                        "code",
                        "expiration_date",
                        "amount_ph",
                        "amount_us",
                        "number_uses",
                        "active",
                        "email_address",
                        "date_created",
                        "c_type_ph",
                        "percentage_ph" 
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

                $arrPromoUsed[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);	

        }; 

       
        if($arrPromoUsed){
                if($arrPromoUsed[0]["expiration_date"]=='0000-00-00 00:00:00' || $arrPromoUsed[0]["expiration_date"]>=$arrPromoUsed[0]["date_created"]){
                    $arrUsed=$arrPromoUsed;
                    $arrUsed["message"] = "good";

                }else{
                    $arrUsed["message"] = "promo code expired";
                }
            }else{
                $arrUsed["message"] = "N/A";
            }

        return $arrUsed;
}




function PromoChecker($promo_code){
    global $conn;

    $arrPromoUsed=array();

    $datenow=date('Y-m-d H:i:s');

    $query="SELECT pc.promo_code,
                    pc.expiration_date,
                    pc.number_uses,
                    pc.amount_ph,
                    pcu.used_by,
                    pc.percentage_ph,
                    count(used_by) 
    
    
                    FROM promo_codes pc
                        LEFT JOIN `promo_codes_users` pcu      ON pc.promo_code=pcu.code
                        WHERE  pc.active='y'
                        AND binary(pc.promo_code) =binary('".$promo_code."')";



     $grabParams = array(
                 'promo_code',
                    'expiration_date',
                    'number_uses',
                    'amount_ph',
                    'used_by',
                    'percentage_ph',
                    'count_used' 
    );

    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParams); $i++) { 

                $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

            };

            $arrPromoUsed[] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }
    else {

        echo mysqli_error($conn);	

    }; 
   




    if($arrPromoUsed){

        if( ($arrPromoUsed[0]["expiration_date"]=='0000-00-00 00:00:00'  || $arrPromoUsed[0]["expiration_date"]>=$datenow )
                         &&  $arrPromoUsed[0]["used_by"]==''
                          && $arrPromoUsed[0]["number_uses"]>$arrPromoUsed[0]["count_used"] ){

            $arrUsed["message"] = "good";


        }
        elseif($arrPromoUsed[0]["used_by"]!='' ){
            $arrUsed["message"] = "Promo Code already been Used";

        }
        elseif( $arrPromoUsed[0]["number_uses"]<=$arrPromoUsed[0]["count_used"]){
            $arrUsed["message"] = "Promo Code has reach its limit";
        }
        elseif( $arrPromoUsed[0]["expiration_date"]<=$datenow){
            $arrUsed["message"] = "Promo Code is expired";
        }
        else{
            $arrUsed["message"] ="Promo Code doessnt exist";
        }


    }else{
        $arrUsed["message"] ="Promo Code doessnt exist";
    }


    return $arrUsed;

}
?>