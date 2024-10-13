

<?php


session_save_path($_SERVER["DOCUMENT_ROOT"] . "/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot . "/includes/connect.php";

if (!isset($_SESSION['user_login']['username'])) {
    header("Location: /");
    exit;
}

if (!isset($_SESSION)) {

    session_start();
}


// echo "<pre>";
// print_r($_POST);

if (isset($_POST['order_id']) && isset($_POST['po_number']) && isset($_POST['id'])) {
    // die(print_r($_POST['order_id']));
    // var_dump($_POST['order_id']);

    // $cancel_reason = $_POST['cancel_reason'] ?? "";

    $query = "UPDATE orders_face_details
          SET `status` = 'cancelled', synched = 'y'
          WHERE order_id = ? AND po_number = ? AND id = ?";

    $stmt = mysqli_stmt_init($conn);

    if (mysqli_stmt_prepare($stmt, $query)) {

        mysqli_stmt_bind_param($stmt, "ssi", $_POST['order_id'], $_POST['po_number'], $_POST['id']);

        if (mysqli_stmt_execute($stmt)) {
            echo json_encode(['status' => 'success', 'message' => 'Order cancelled successfully.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to cancel the order.']);
        }

        mysqli_stmt_close($stmt);
    } else {
        echo mysqli_error($conn);
        exit;
    };
}
?>
	

