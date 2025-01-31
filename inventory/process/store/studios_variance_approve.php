<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

	session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";?>


<pre><?php 

for($i=0;$i<sizeof($_POST["off"]);$i++){
    $query="UPDATE  inventory_studios SET 
                variance_status='".$_POST['variance_report'][$_POST["off"][$i]]."'
                WHERE
                    delivery_unique='".$_POST["off"][$i]."' 
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
echo "<script> window.location='/studios/inventory/store/studios-history/'; </script>";

// print_r($_POST)

?>
</pre>