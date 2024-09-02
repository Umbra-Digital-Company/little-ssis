<?php
	function setPoNumberAdd($order_no_Cart){

    global $conn;

    $arrCartQF = array();

    $arrCartQuery = 'SELECT  
                        COUNT(pr.item_code) as count,
                        GROUP_CONCAT( os.orders_specs_id)  as group_orders_specs_id,
                        IF(
                            pr.item_code LIKE "AR%", 
                            RIGHT(item_name, LENGTH(item_name) - LOCATE(")", item_name)),
                            IF(
                                pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                "",
                                LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), ""))
                            )                            
                        ) AS "grab_color",                        
                        IF(
                            pr.item_code LIKE "AR%",
                            REPLACE(LEFT(pr.item_name, LOCATE(")", pr.item_name) - 1), "ANTI-RADIATION (", ""),
                            IF(
                                pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                pr.item_name,
                                LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)))
                            )                            
                        ) AS "grab_style",
                        os.orders_specs_id,
                        pr.item_name,
                        pr.barcode,
                        pr.item_code,';
                        if(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr'){
                            $arrCartQuery .=' pr.sr_srp,'; 
                        }elseif(isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs'){
                            $arrCartQuery .=' pr.vnd_srp,'; 
                        }else{
                            $arrCartQuery .='  pr.retail,';
                        }

    $arrCartQuery .=       ' pr.item_code,
                        os.product_upgrade,
                        os.product_code AS product_code_order,
                        IF(
                            s.image_url IS NOT NULL,
                            s.image_url,
                            img.image_url
                        ),
                        os.dispatch_type
                    FROM 
                        profiles_info p
                            LEFT JOIN orders_face_details os 
                                ON os.profile_id = p.profile_id
                            LEFT JOIN poll_51_face_new pr 
                                ON pr.item_code = os.product_code OR pr.item_code = os.product_upgrade
                            LEFT JOIN poll_51_shopify_data s
                                ON s.product_code = os.product_code OR s.product_code = os.product_upgrade
                            LEFT JOIN poll_51_image_studios img
                                ON img.product_code = pr.item_code
                            LEFT  JOIN orders_face o 
                                ON o.order_id = os.order_id 
                            LEFT JOIN emp_table u 
                                ON u.emp_id= o.doctor
                    WHERE 
                        o.order_id = "'.$order_no_Cart.'"
                            AND os.status != "cancelled"
                    GROUP BY 
                        pr.item_code, os.product_upgrade ORDER BY os.id ASC';


    $grabParamsQF = array(
        "count",
        "group_orders_specs_id",
        "color",
        "style",
        "orders_specs_id",
        "item_description",
        "product_number",
        "item_code",
        "price",
        "product_code",
        "product_upgrade",
        "product_code_order",
        "image_url",
        "dispatch_type"
    );
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i=0; $i < sizeOf($grabParamsQF); $i++) { 

                $tempArray[$grabParamsQF[$i]] = ${'result' . ($i+1)};

            };
            $tempArray['item_description'] = ucwords(strtolower($tempArray['item_description']));
            $arrCartQF [] = $tempArray;

        };

        mysqli_stmt_close($stmt);    
                                
    }

    return $arrCartQF;
}

$order_count = (isset($_SESSION['order_no'])) ? setPoNumberAdd($_SESSION['order_no']) : [];
$arrCart     = $order_count;
$order_count = (isset($_SESSION['order_no'])) ? count($order_count) : 0;
?>