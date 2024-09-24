<?php
if (!isset($_SESSION)) {
    session_start();
}

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

//Required files
//require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";

$arrCustomer = array();



// * Important:: Original Query
// $query = "SELECT  p.first_name, p.last_name,os.product_code,os.order_id,u.fullname,pr.color,p.profile_id,o.doctor,o.status
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
//     'color',
//     'profile_id',
//     'doctor',
//     'status'
// );


// $stmt = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmt, $query)) {
//     mysqli_stmt_bind_param($stmt, 'sss', $searchTerm, $searchTerm, $searchTerm);
//     mysqli_stmt_execute($stmt);
//     mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5,);

//     while (mysqli_stmt_fetch($stmt)) {

//         $tempArray = array();

//         for ($i = 0; $i < 6; $i++) {

//             $tempArray[$grabParams[$i]] = ${'result' . ($i + 1)};
//         };

//         $arrCustomer[] = $tempArray;
//     };

//     mysqli_stmt_close($stmt);
// } else {

//     echo mysqli_error($conn);
// };






$itemsPerPage = isset($_GET['itemsPerPage']) && is_numeric($_GET['itemsPerPage']) ? (int)$_GET['itemsPerPage'] : 10;
$pagination = isset($_GET['pagination']) && is_numeric($_GET['pagination']) ? (int)$_GET['pagination'] : 1;
$offset = ($pagination - 1) * $itemsPerPage;

// echo 'Current page: ' . $pagination;
// echo 'Items per page: ' . $itemsPerPage;

// ! Testing:: Get the search term from the request
$searchTerm = isset($_GET['search']) ? $_GET['search'] : '';
$searchTerm = '%' . $searchTerm . '%';



$countQuery = '
    SELECT COUNT(*) AS total
    FROM profiles_info p
    INNER JOIN
        orders_sunnies_studios os ON os.profile_id = p.profile_id
    LEFT JOIN
        poll_51_studios_new pr ON pr.product_code = os.product_code
    WHERE
        CONCAT(p.first_name, " ", p.last_name) LIKE ? OR pr.item_name LIKE ? OR os.order_id LIKE ?
';


$stmt = mysqli_stmt_init($conn);
mysqli_stmt_prepare($stmt, $countQuery);
mysqli_stmt_bind_param($stmt, 'sss', $searchTerm, $searchTerm, $searchTerm);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $totalResults);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);


$totalPages = ceil($totalResults / $itemsPerPage);




// ! Testing:: Query for Dispatch
$query = '
    SELECT 
        CONCAT(p.first_name, " ", p.last_name) AS fullname,
        os.status,
        os.status_date,
        os.order_id,
        pr.item_name
    FROM
        profiles_info p
    INNER JOIN
        orders_sunnies_studios os ON os.profile_id = p.profile_id
    LEFT JOIN
        poll_51_studios_new pr ON pr.product_code = os.product_code
    WHERE
        CONCAT(p.first_name, " ", p.last_name) LIKE ? OR pr.item_name LIKE ? OR os.order_id LIKE ?
    ORDER BY
        os.status_date ASC
    LIMIT ? OFFSET ?
';

// ! Testing:: Parameters for Customer Data Retrieval
$grabParams = array(
    'fullname',
    'status',
    'status_date',
    'order_id',
    'item_name'
);


$stmt = mysqli_stmt_init($conn);

if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_bind_param($stmt, 'sssii', $searchTerm, $searchTerm, $searchTerm, $itemsPerPage, $offset);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);
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
