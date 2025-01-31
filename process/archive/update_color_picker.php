<?php   

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

//////////////////////////////////////////////////// GRAB POST

// Check if POST is present
if(!isset($_POST)) { exit; };

// Grab POST data
if(isset($_POST['brand']) && isset($_POST['sku']) && isset($_POST['hex_code'])) {

    $brand        = $_POST['brand'];
    $product_code = $_POST['sku'];
    $hex_code     = $_POST['hex_code'];

}
else {

    exit;

};

//////////////////////////////////////////////////// UPDATE DATABASE

// Set query
$query  =   "INSERT INTO 
				the_archive_".$brand." (
					product_code2,
					product_code,
					color_swatch_new
				) 
            VALUES (
            	'".mysqli_real_escape_string($conn, $product_code)."',
            	'".mysqli_real_escape_string($conn, $product_code)."',            	
            	'".mysqli_real_escape_string($conn, $hex_code)."'
        	)
        	ON DUPLICATE KEY UPDATE
        		product_code2 = VALUES(product_code2),
        		product_code = VALUES(product_code),
        		color_swatch_new = VALUES(color_swatch_new);";

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);	

    echo "Color successfully updated for ".$product_code;	

}
else {

	echo mysqli_error($conn);
	exit;
	
};

?>