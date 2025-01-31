<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
if(!isset($_SESSION)) { session_start(); };

// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Poll 51 array
$arrPoll51 = array_map('str_getcsv', file($_SERVER['DOCUMENT_ROOT'].'/system/poll-51/specs/POLL51.csv'));
$numItems = sizeOf($arrPoll51);

// Remove special characters
$arrRemove = array(
    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', '\'' => '', '"' => ''
);

// Truncate poll_51_new
$queryTruncate = 'TRUNCATE poll_51_new;';

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryTruncate)) {

    mysqli_stmt_execute($stmt);		
    mysqli_stmt_close($stmt);		

}
else {

	echo mysqli_error($conn);		
	exit;

};

// Cycle through Poll 51 array
for ($i=0; $i < $numItems; $i++) { 

	$query = 	'INSERT INTO 
					poll_51_new (
						item_description,
						item_name,
						count,
						item_code,
						stock,
						product_number,
						PIECE,
						price,
						zero1,
						zero2,
						product_code2,
						product_code,
						category,
						collection,
						correct_group_category,
						finish,
						general_color,
						grouping,
						material,
						product_seasonality,
						shape,
						size,
						sub_category,
						sub_color,
						sos_date,
						vnd_srp,
						udf3,
						udf4,
						usd_srp,
						 segment,
						  sub_segment,
						   form_group,
						    vision,
							house_brand
					)
				VALUES(
					"'.( strtr($arrPoll51[$i][0], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][1], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][2], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][3], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][4], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][5], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][6], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][7], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][8], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][9], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][10], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][11], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][12], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][13], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][14], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][15], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][16], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][17], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][18], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][19], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][20], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][21], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][22], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][23], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][24], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][25], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][26], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][27], $arrRemove) ).'",

					"'.( strtr($arrPoll51[$i][28], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][29], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][30], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][31], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][32], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][33], $arrRemove) ).'"
				);';

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {

	    mysqli_stmt_execute($stmt);		
	    mysqli_stmt_close($stmt);		

	}
	else {

		echo mysqli_error($conn);		
		exit;

	};
	
};

header('Location: /system/poll-51/specs/');
exit;

echo "<script> window.location='/system/poll-51/specs/'; </script>";

?>
