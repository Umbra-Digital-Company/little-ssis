<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";



echo "<pre>";
// print_r($_POST);



for($i=0;$i<sizeof($_POST['off']);$i++){


    $query="UPDATE inventory_face SET    
                count='".$_POST["count"][$_POST["off"][$i]]."',
                    request_edit='y'

            WHERE reference_number='".$_POST["reference"]."' 
                and product_code='".$_POST["off"][$i]."' 
                and requested='y'
             ";



            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {

                mysqli_stmt_execute($stmt);		
                mysqli_stmt_close($stmt);		

            }
            else {

                echo mysqli_error($conn);
                exit;

            };

}

echo "</pre>";


echo "<script> window.location='/face/inventory/admin/face-history/?ref_num=".$_POST["reference"]."'; </script>";
?>