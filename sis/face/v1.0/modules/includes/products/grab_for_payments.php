<?php
$arrDate = [];
function getForPayments(){

    global $conn;
    global $arrDate;
    $filterDate = ' AND DATE(ADDTIME(os.date_created,"13:00:00")) = "'.date('Y-m-d').'"';

    if(isset($_GET['date']) && trim($_GET['date']) != ''){
        $arrDate = explode('|', $_GET['date']);
        $filterDate = ' AND DATE(ADDTIME(os.date_created,"13:00:00")) BETWEEN "'.$arrDate[0].'" AND "'.$arrDate[1].'"';
    }

    $arrCartQF = array();

    $arrCartQuery = 'SELECT
                        p.first_name,
                        p.last_name,
                        os.order_id,
                        LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), "")) AS "grab_color",
                        LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1))) AS "grab_style",
                        os.orders_specs_id,
                        pr.item_name,
                        pr.product_number,
                        pr.item_code,
                        pr.price,
                        pr.product_code,
                        os.product_upgrade,
                        IF(
                            s.image_url IS NOT NULL,
                            s.image_url,
                            img.image_url
                        ),
                        po_number,
                         ADDTIME(os.date_created,"12:00:00")
                    FROM 
                        profiles_info p
                            LEFT JOIN orders_sunnies_studios os 
                                ON os.profile_id = p.profile_id
                            LEFT JOIN poll_51_studios_new pr 
                                ON pr.product_code = os.product_code OR pr.product_code = os.product_upgrade
                            LEFT JOIN poll_51_shopify_data s
                                ON s.product_code = os.product_code OR s.product_code = os.product_upgrade
                            LEFT JOIN poll_51_image_studios img
                                ON img.product_code = pr.product_code
                            LEFT  JOIN orders_studios o 
                                ON o.order_id = os.order_id 
                            LEFT JOIN emp_table u 
                                ON u.emp_id= o.doctor
                    WHERE 
                            os.status = "for payment"
                            '.$filterDate.'
                            AND o.origin_branch = ?
                    ORDER BY os.id ASC';
                    // echo $arrCartQuery; exit;
    $grabParamsQF = array(
        "first_name",
        "last_name",
        "order_id",
        "color",
        "style",
        "orders_specs_id",
        "item_description",
        "product_number",
        "item_code",
        "price",
        "product_code",
        "product_upgrade",
        "image_url",
        "po_number",
        "date_created"
    );
    
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_bind_param($stmt, 's', $_SESSION['user_login']['store_code']);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15);

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
?>