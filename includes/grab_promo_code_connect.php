<?php

$arrPromocodesConnect= array();


$query="SELECT `promo_code`,
            `type`,
            `expiration_date`,
            `number_uses`,
            `uses_per`,
            `case_sensitive`,
            `amount_ph`,
            `amount_us`,
            `percentage_ph`,
            `percentage_us`,
            `s_type_ph`, 
            `s_type_us`, 
            `c_type_ph`, 
            `c_type_us`, 
            `spec_promo`,
            `times_used`,
            `extra_info`, 
            `active` 
            FROM `promo_codes`
            where type NOT IN ('ecomm','archived')
            and active='y'
             ORDER BY `promo_code` ASC ";


$grabParams=array(
            'promo_code',
            'type',
            'expiration_date',
            'number_uses',
            'uses_per',
            'case_sensitive',
            'amount_ph',
            'amount_us',
            'percentage_ph',
            'percentage_us',
            's_type_ph', 
            's_type_us', 
            'c_type_ph', 
            'c_type_us', 
            'spec_promo',
            'times_used',
            'extra_info', 
            'active' );


            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {
            
                mysqli_stmt_execute($stmt);
                mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11,
                                 $result12, $result13, $result14, $result15, $result16, $result17, $result18);
            
                while (mysqli_stmt_fetch($stmt)) {
            
                    $tempArray = array();
            
                    for ($i=0; $i < sizeOf($grabParams); $i++) { 
            
                        $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
            
                    };
            
                    $arrPromocodesConnect[] = $tempArray;
            
                };
            
                mysqli_stmt_close($stmt);    
                                        
            }
            else {
            
                echo mysqli_error($conn);
            
            }; 
?>