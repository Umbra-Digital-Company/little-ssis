<?php
function getOrdersConfirmed($order_no_Cart)
{

    global $conn;

    $arrCartQF = array();

    $arrCartQuery = 'SELECT  
                            COUNT(pr.product_code) as count,
                            GROUP_CONCAT( os.orders_specs_id)  as group_orders_specs_id,
                            IF(
                                pr.product_code LIKE "AR%", 
                                RIGHT(item_name, LENGTH(item_name) - LOCATE(")", item_name)),
                                IF(
                                    pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                    "",
                                    LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), ""))
                                )                            
                            ) AS "grab_color",                        
                            IF(
                                pr.product_code LIKE "AR%",
                                REPLACE(LEFT(pr.item_name, LOCATE(")", pr.item_name) - 1), "ANTI-RADIATION (", ""),
                                IF(
                                    pr.item_name LIKE "%AGENDA%" OR pr.item_name LIKE "%HARDCASE%" OR pr.item_name LIKE "%ANTI FOG%" OR pr.item_name LIKE "%DAILY SHIELD%" OR pr.item_name LIKE "%DAILY MASK%" OR pr.item_name LIKE "%DAILY DUO%" OR pr.item_name LIKE "%CLEAN LENS%" OR pr.item_name LIKE "%CARE KIT%" OR pr.item_name LIKE "%ANTI-FOG%",
                                    pr.item_name,
                                    LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)))
                                )                            
                            ) AS "grab_style",
                            os.orders_specs_id,
                            pr.item_name,
                            pr.product_number,
                            pr.item_code,';

    if (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'sr') {
        $arrCartQuery .= ' pr.sr_price,';
    } elseif (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') {
        $arrCartQuery .= ' pr.vnd_srp,';
    } else {
        $arrCartQuery .= '  pr.price,';
    }

    $arrCartQuery .= '  pr.product_code,
                            os.product_upgrade,
                            IF(
                                s.image_url IS NOT NULL,
                                s.image_url,
                                img.image_url
                            ),
                            os.po_number,
                            os.dispatch_type,
                            o.promo_code,
                            o.promo_code_amount,
                            o.feedback
                        FROM 
                            profiles_info p
                                LEFT JOIN orders_sunnies_studios os 
                                    ON os.profile_id = p.profile_id
                                LEFT JOIN poll_51_studios_new pr 
                                 ON (pr.product_code = os.product_code OR pr.product_code = os.product_upgrade) AND pr.item_code!="LENS001"
                                LEFT JOIN poll_51_shopify_data s
                                    ON s.product_code = os.product_code OR s.product_code = os.product_upgrade
                                LEFT JOIN poll_51_image_studios img
                                    ON img.product_code = pr.product_code
                                LEFT  JOIN orders_studios o 
                                    ON o.order_id = os.order_id 
                                LEFT JOIN emp_table u 
                                    ON u.emp_id= o.doctor
                        WHERE 
                            o.order_id = "' . $order_no_Cart . '"
                                AND os.status != "cancelled"
                        GROUP BY os.product_code, os.product_upgrade
                        ORDER BY os.id ASC';

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
        "image_url",
        "po_number",
        "dispatch_type",
        "promo_code",
        "promo_code_amount",
        "feedback"
    );

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17);

        while (mysqli_stmt_fetch($stmt)) {

            $tempArray = array();

            for ($i = 0; $i < sizeOf($grabParamsQF); $i++) {

                $tempArray[$grabParamsQF[$i]] = ${'result' . ($i + 1)};
            };
            $tempArray['item_description'] = ucwords(strtolower($tempArray['item_description']));
            $arrCartQF[] = $tempArray;
        };

        mysqli_stmt_close($stmt);
    }

    return $arrCartQF;
}


function getProfile($order_id)
{
    global $conn;

    $profileQuery = 'SELECT 
                    p.age,
                    CONCAT(p.first_name, " ", p.last_name) AS fullname
                 FROM orders_studios o
                 LEFT JOIN profiles_info p ON p.profile_id = o.profile_id
                 WHERE o.order_id = ?';

    $age = null;
    $fullname = null; 
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $profileQuery)) {
        mysqli_stmt_bind_param($stmt, "s", $order_id);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $ageResult, $fullnameResult);

        if (mysqli_stmt_fetch($stmt)) {
            $age = $ageResult;
            $fullname = $fullnameResult;
        }

        mysqli_stmt_close($stmt);
    }

    // Return an array if you want both age and first name
    return array('age' => $age, 'full_name' => $fullname);
}


$profile = (getProfile($_GET['order_id']));
$arrOrdersConfirmed = (getOrdersConfirmed($_GET['order_id']));
