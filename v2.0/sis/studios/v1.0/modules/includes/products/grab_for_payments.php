<?php
$arrDate = [];
function getForPayments($offset = 0, $limit = 5)
{

    global $conn;
    global $arrDate;
    $filterDate = ' AND DATE(ADDTIME(os.date_created,"13:00:00")) = "' . date('Y-m-d') . '"';

    if (isset($_GET['date']) && trim($_GET['date']) != '') {
        $arrDate = explode('|', $_GET['date']);
        $filterDate = ' AND DATE(ADDTIME(os.date_created,"13:00:00")) BETWEEN "' . $arrDate[0] . '" AND "' . $arrDate[1] . '"';
    }

    $arrCartQF = array();

    $arrCartQuery = 'SELECT
                        p.first_name,
                        p.last_name,
                        os.order_id,
                       if(os.product_code="M100",
                            LOWER(REPLACE(pr2.item_name, TRIM(LEFT(pr2.item_name , LOCATE(" ", pr2.item_name) - 1)), "")) ,
                            LOWER(REPLACE(pr.item_name, TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1)), ""))) AS "grab_color", 
                            if(os.product_code="M100",
                            LOWER(TRIM(LEFT(pr2.item_name , LOCATE(" ", pr2.item_name) - 1))) ,
                            LOWER(TRIM(LEFT(pr.item_name , LOCATE(" ", pr.item_name) - 1))) )AS "grab_style",
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
                        os.profile_id,
                         ADDTIME(os.date_created,"12:00:00")
                   FROM orders_sunnies_studios os
                           LEFT JOIN profiles_info p ON os.profile_id = p.profile_id 
                            LEFT JOIN poll_51_studios_new pr ON pr.product_code = os.product_code 
                            LEFT JOIN poll_51_studios_new pr2 ON pr2.product_code = os.product_upgrade 
                            LEFT JOIN poll_51_shopify_data s ON s.product_code = os.product_code OR s.product_code = os.product_upgrade 
                            LEFT JOIN poll_51_image_studios img ON img.product_code = pr.product_code 
                            LEFT JOIN orders_studios o ON o.order_id = os.order_id 
                            LEFT JOIN emp_table u ON u.emp_id= o.doctor
                    WHERE 
                            os.status IN ("for payment","downloaded")
                            ' . $filterDate . '
                            AND o.origin_branch = "' . $_SESSION['user_login']['store_code'] . '"
                    ORDER BY os.id ASC
                    LIMIT ? OFFSET ?
                    ';

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
        "profile_id",
        "date_created"
    );

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $arrCartQuery)) {

        mysqli_stmt_bind_param($stmt, 'ii', $limit, $offset);

        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16);

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

function getTotalItems()
{
    global $conn;
    $filterDate = ' AND DATE(ADDTIME(os.date_created,"13:00:00")) = "' . date('Y-m-d') . '"';

    if (isset($_GET['date']) && trim($_GET['date']) != '') {
        $arrDate = explode('|', $_GET['date']);
        $filterDate = ' AND DATE(ADDTIME(os.date_created,"13:00:00")) BETWEEN "' . $arrDate[0] . '" AND "' . $arrDate[1] . '"';
    }

    $totalItemsQuery = 'SELECT COUNT(*) 
                        FROM orders_sunnies_studios os 
                        LEFT JOIN orders_studios o ON o.order_id = os.order_id 
                        WHERE 
                            os.status IN ("for payment","downloaded")
                            ' . $filterDate . '
                            AND o.origin_branch = "' . $_SESSION['user_login']['store_code'] . '"
                        ';

    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $totalItemsQuery)) {
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $totalItems);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);
        return $totalItems;
    }

    return 0; // Return 0 if the query fails
}
