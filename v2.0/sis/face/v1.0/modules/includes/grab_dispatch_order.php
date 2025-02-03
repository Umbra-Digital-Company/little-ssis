<?php
// if(!isset($_SESSION)){
//         session_start();
//     }

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// //Required files
// //require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";

// $arrCustomer = array();

// $query="SELECT  p.first_name, p.last_name,os.product_code,os.order_id,u.fullname,pr.color,p.profile_id,o.doctor,o.status
// FROM profiles_info p
// INNER JOIN orders_specs os on os.profile_id=p.profile_id
// LEFT JOIN users u on u.id=p.sales_person
// LEFT JOIN products pr on pr.product_code=os.product_code
// LEFT  JOIN orders o on o.order_id=os.order_id
// where o.status='for payment'
// ORDER BY o.id";


// $grabParams = array(
//     'first_name',
//     'last_name',
//     'product_code',
//     'order_id',
//     'fullname',
// 	'color',
// 	'profile_id',
// 	'doctor',
// 	'status'
// );

// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $query)) {
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

//     while (mysqli_stmt_fetch($stmt)) {

//         $tempArray = array();

//         for ($i=0; $i < 9; $i++) { 

//             $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

//         };

//         $arrCustomer[] = $tempArray;

//     };


//     mysqli_stmt_close($stmt);    

// }
// else {

//     echo mysqli_error($conn);

// }; 

$itemsPerPage = isset($_GET['itemsPerPage']) && is_numeric($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 10;
$pagination = isset($_GET['dpage']) && is_numeric($_GET['dpage']) ? (int)$_GET['dpage'] : 1;
$offset = ($pagination - 1) * $itemsPerPage;
$store_id =$_SESSION["store_code"];


$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = '%' . $searchTerm . '%';
if( isset($_GET['search']) ){ 

    $date_filter="";
}else{
    $date_filter=" AND os.payment_date > DATE_ADD(NOW(), INTERVAL -1 WEEK)";
}

// Usage of the function
$totalResults = getTotalResults($conn, $searchTerm, $store_id, $date_filter);

// Calculate total pages based on total results
$totalPages = ceil($totalResults / $itemsPerPage);



$query = '
    SELECT 
        os.id,
        CONCAT(p.first_name, " ", p.last_name) AS fullname,
        os.status,
        os.status_date,
        os.order_id,
        ps.item_name,
        ps.data_cgc,
        os.po_number,
        os.profile_id,
        os.orders_specs_id
    FROM
        profiles_info p
    LEFT JOIN 
        orders_face_details os ON os.profile_id = p.profile_id
    LEFT JOIN 
        orders_face o ON o.order_id = os.order_id
    LEFT JOIN 
        poll_51_face_new ps ON ps.item_code = os.product_code
    WHERE
        (CONCAT(p.first_name, " ", p.last_name) LIKE ? 
        OR ps.item_name LIKE ? 
        OR os.order_id LIKE ?)
        AND os.status IN ("for payment", "paid", "cancelled", "returned")
    ORDER BY
        os.status_date DESC
    LIMIT ? OFFSET ?
';

// ! Testing:: Query for Dispatch
$query = '
    SELECT 
        os.id,
        CONCAT(p.first_name, " ", p.last_name) AS fullname,
        os.status,
        os.status_date,
        os.order_id,
        pr.item_name,
        pr.data_cgc,
        os.po_number,
        os.profile_id,
        os.orders_specs_id
    FROM
        profiles_info p
    INNER JOIN
        orders_face_details os ON os.profile_id = p.profile_id
    LEFT JOIN
        orders_face o ON o.order_id = os.order_id
    LEFT JOIN
        poll_51_face_new pr ON pr.item_code = os.product_code
    WHERE
      os.status IN ("for payment", "paid", "cancelled", "returned")
        AND o.origin_branch= ?
        ' . $date_filter . '
        AND  (CONCAT(p.first_name, " ", p.last_name) LIKE ? OR pr.item_name LIKE ? OR os.order_id LIKE ?)
    ORDER BY
        os.status_date DESC
    LIMIT ? OFFSET ?
';



$grabParams = array(
    'id',
    'fullname',
    'status',
    'status_date',
    'order_id',
    'item_name',
    'store_type',
    'po_number',
    'profile_id',
    'orders_specs_id'
);


$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_bind_param($stmt, 'sssii', $store_id ,$searchTerm, $searchTerm, $searchTerm, $itemsPerPage, $offset);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);
    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i = 0; $i < sizeOf($grabParams); $i++) {

            $tempArray[$grabParams[$i]] = ${'result' . ($i + 1)};
        };

        $arrCustomer[] = $tempArray;
    };

    mysqli_stmt_close($stmt);
} else {

    echo mysqli_error($conn);
};



function getTotalResults($conn, $searchTerm, $store_id, $date_filter)
{
    $countQuery = '
        SELECT COUNT(*) AS total
        FROM profiles_info p
        LEFT JOIN orders_face_details os ON os.profile_id = p.profile_id
        LEFT JOIN users u ON u.id = p.sales_person
        LEFT JOIN orders_face o ON o.order_id = os.order_id
        LEFT JOIN poll_51_face_new ps ON ps.item_code = os.product_code
        WHERE o.origin_branch = ?
        AND (CONCAT(p.first_name, " ", p.last_name) LIKE ? 
        OR ps.item_name LIKE ? 
        OR os.order_id LIKE ?)
        AND os.status IN ("for payment", "paid", "cancelled", "returned")
        ' . $date_filter . '
    ';

    
    // $countQuery = '
    // SELECT COUNT(*) AS total
    // FROM orders_face_details os
    // LEFT JOIN
    //     orders_face o ON o.order_id = os.order_id
    // LEFT JOIN
    //     profiles_info p ON os.profile_id = p.profile_id
    // LEFT JOIN
    //     poll_51_face pr ON pr.item_code = os.product_code
    // WHERE
    // o.origin_branch= ?
    //     AND  (CONCAT(p.first_name, " ", p.last_name) LIKE ? OR pr.item_name LIKE ? OR os.order_id LIKE ?)
    
    //     ' . $date_filter . '
    // ';


    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $countQuery)) {
        echo mysqli_error($conn);
        return 0;
    }

    mysqli_stmt_bind_param($stmt, 'sss', $store_id, $searchTerm, $searchTerm, $searchTerm);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $totalResults);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return (int)$totalResults;
}
