<meta charset="UTF-8">

<?php   

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
if(!isset($_SESSION)) { session_start(); };

if(!isset($_SESSION['user_login']['username'])) {
    header("Location: /");
    exit;
}


// error_reporting(E_ALL);
// ini_set('display_errors', 1);

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

// Poll 51 array
$arrPoll51 = array_map('str_getcsv', file($_SERVER['DOCUMENT_ROOT'].'/face/system/poll-51/face/POLL51.csv'));
$numItems = sizeOf($arrPoll51);

// Remove special characters
$arrRemove = array(
    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E','Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U','Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c','è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o','ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y', '\'' => '', '"' => ''
);

// Cycle through Poll 51 array
for ($i=0; $i < $numItems; $i++) { 

	$query = 	'INSERT INTO 
					poll_51_face (
						item_description,
						item_name,
						discount,
						_class,
						style_name,
						color_name,
						uom,
						retail,
						cost,
						quantity,
						barcode,
						item_code,
						category,
						collection,
						correct_group_category,
						finish,
						shade_family,
						bundle_group,
						material,
						product_seasonality,
						shape,
						size,
						sub_category,
						color,
						sos_date,
						vs_ws_srp,
						vnd_srp,
						gender,
						usd_srp,
						segment,
						sub_segment,
						form_group,
						vision,
						house_brand,
						sr_srp,
						data_cgc,
						gl_account,
						product_classification,
						pricing_category,
						markdown_date,
						marketplace_srp
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
					"'.( strtr($arrPoll51[$i][33], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][34], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][35], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][36], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][37], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][38], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][39], $arrRemove) ).'",
					"'.( strtr($arrPoll51[$i][40], $arrRemove) ).'"
				)
				ON DUPLICATE KEY UPDATE
					item_description = VALUES(item_description),
					item_name = VALUES(item_name),
					discount = VALUES(discount),
					_class = VALUES(_class),
					style_name = VALUES(style_name),
					color_name = VALUES(color_name),
					uom = VALUES(uom),
					retail = VALUES(retail),
					cost = VALUES(cost),
					quantity = VALUES(quantity),
					barcode = VALUES(barcode),
					item_code = VALUES(item_code),
					category = VALUES(category),
					collection = VALUES(collection),
					correct_group_category = VALUES(correct_group_category),
					finish = VALUES(finish),
					shade_family = VALUES(shade_family),
					bundle_group = VALUES(bundle_group),
					material = VALUES(material),
					product_seasonality = VALUES(product_seasonality),
					shape = VALUES(shape),
					size = VALUES(size),
					sub_category= VALUES(sub_category),
					color = VALUES(color),
					sos_date = VALUES(sos_date),
					vnd_srp = VALUES(vnd_srp),
					vs_ws_srp = VALUES(vs_ws_srp),
					gender = VALUES(gender),
					usd_srp = VALUES(usd_srp),
					segment = VALUES(segment),
					sub_segment = VALUES(sub_segment),
					form_group = VALUES(form_group),
					vision = VALUES(vision),
					house_brand = VALUES(house_brand),
					sr_srp = VALUES(sr_srp),
					data_cgc=VALUES(data_cgc),
					gl_account=VALUES(gl_account),
					product_classification=VALUES(product_classification),
					pricing_category=VALUES(pricing_category),
					markdown_date=VALUES(markdown_date),
					marketplace_srp=VALUES(marketplace_srp);';


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

header('Location: /face/system/poll-51/face');
exit;

echo "<script> window.location='/face/system/poll-51/face'; </script>";

?>
