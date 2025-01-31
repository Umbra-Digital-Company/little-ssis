<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");

if(!isset($_SESSION)) {

    session_start();

};

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";



// echo "<pre>";
// print_r($_POST);

// exit;

function getLastInventory($lastDeliveryUnique){
    global $conn;
    $arrInventory = array();
        $query = "SELECT 
                        reference_number,
                        delivery_unique,
                        store_id,
                        product_code,
                        count,
                        status,
                        status_date,
                        stock_from,
                        admin_id,
                        admin_name,
                        type,
                        item_remark,
                        remarks,
                        requested,
                        transaction_reason,
                        request_date,
                        requestor,
                        request_edit,
                        variance_reason
                    FROM 
                        inventory_face
                    WHERE delivery_unique = '".$lastDeliveryUnique."';";

        $grabParams = array(
            'reference_number',
            'delivery_unique',
            'store_id',
            'product_code',
            'count',
            'status',
            'status_date',
            'stock_from',
            'admin_id',
            'admin_name',
            'type',
            'item_remark',
            'remarks',
            'requested',
            'transaction_reason',
            'request_date',
            'requestor',
            'request_edit',
            'variance_reason'
        );

        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $query)) {
            
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19);

            while (mysqli_stmt_fetch($stmt)) {

                $tempArray = array();

                for ($i=0; $i < sizeOf($grabParams); $i++) { 

                    $tempArray[$grabParams[$i]] = utf8_encode(${'result' . ($i+1)});

                };

                // $arrStoresStudios[] = $tempArray;
                $arrInventory[] = $tempArray;

            };

            mysqli_stmt_close($stmt);    
                                    
        }
        else {

            echo mysqli_error($conn);

        };

    return $arrInventory;
}

$curDeliveryID = '';
$lastDeliveryUnique = '';
for($i=0;$i<sizeof($_POST['frame_code']);$i++){
    //for increment delivery unique
    

    if(isset($_POST["off"][$i])){
        $query="UPDATE inventory_face SET    
                count='".$_POST["frame_count"][$i]."',
                    request_edit='y',
                item_remark = '".$_POST["item_remark"][$i]."',
                product_code='".$_POST["frame_code"][$i]."'

            WHERE reference_number='".$_POST["reference"]."' 
                and delivery_unique='".$_POST["off"][$i]."' 
                and requested='y'
             ";

             // echo $query.PHP_EOL;

            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $query)) {

                mysqli_stmt_execute($stmt);      
                mysqli_stmt_close($stmt);        

            }
            else {

                echo mysqli_error($conn);
                exit;

            };
         $lastDeliveryUnique = $_POST["off"][$i];
    }else{

        if(trim($_POST["frame_count"][$i]) == ''){
            continue;
        }

        $arrInventory = getLastInventory($lastDeliveryUnique);
        // print_r($arrInventory);

        $curDeliveryID = $_POST["reference"].($i+1);
        $status = 'for approval';
        $query =    "INSERT INTO 
                            inventory_face (
                                reference_number,
                                delivery_unique,
                                store_id,
                                product_code,
                                count,
                                status,
                                status_date,
                                stock_from,
                                admin_id,
                                admin_name,
                                type,
                                item_remark,
                                remarks,
                                requested,
                                transaction_reason,
                                request_date,
                                requestor,
                                request_edit,
                                variance_reason
                            )
                        VALUES(
                            '".mysqli_real_escape_string($conn,$_POST["reference"])."', 
                            '".mysqli_real_escape_string($conn,$curDeliveryID)."',  
                            '".mysqli_real_escape_string($conn,$arrInventory[0]['store_id'])."', 
                            '".mysqli_real_escape_string($conn,$_POST["frame_code"][$i])."', 
                            '".mysqli_real_escape_string($conn,$_POST["frame_count"][$i])."',   
                            '".mysqli_real_escape_string($conn,$arrInventory[0]['status'])."', 
                            NOW(),  
                            '".mysqli_real_escape_string($conn,$arrInventory[0]['stock_from'])."',
                            '".mysqli_real_escape_string($conn,$arrInventory[0]['admin_id'])."',
                            '".mysqli_real_escape_string($conn,$arrInventory[0]['admin_name'])."',
                            '".mysqli_real_escape_string($conn,$arrInventory[0]['type'])."',
                            '".mysqli_real_escape_string($conn,$_POST["item_remark"][$i])."',
                            '".mysqli_real_escape_string($conn,$arrInventory[0]['item_remark'])."',
                            'y',
                            '".$arrInventory[0]['transaction_reason']."',
                            NOW(),
                            '".$arrInventory[0]['requestor']."',
                            '".$arrInventory[0]['request_edit']."',
                            '".$arrInventory[0]['variance_reason']."'
                        )";

       // echo $query.PHP_EOL;
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

}

echo "<script> window.location='/face/inventory/admin/face-history/?ref_num=".$_POST["reference"]."'; </script>";
?>