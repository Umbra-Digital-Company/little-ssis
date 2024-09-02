<?php
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	//$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	// Required includes
	require $sDocRoot."/includes/connect.php";

	$arrHardCase = array();

	 $query='SELECT
	            item_name,
	            LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color",
	            product_code,
	            LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style" ,
	            p.price
	        FROM
	            poll_51_new p
	            
	            WHERE p.item_name LIKE "%HARDCASE%" ORDER BY p.item_name ASC;';

	$grabParams = array("description", "color", "product_code", "item_description" ,"price");

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < count($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

	        };

	        $arrHardCase[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};

	$arrPaperBag = array();

	 $query='SELECT
	            item_name,
	            LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color",
	            product_code,
	            LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style" ,
	            p.price
	        FROM
	            poll_51 p
	            WHERE p.item_name LIKE "%PAPER BAG%" ORDER BY p.item_name ASC;';

	$grabParams = array("description", "color", "product_code", "item_description" ,"price");

	$stmt = mysqli_stmt_init($conn);
	if (mysqli_stmt_prepare($stmt, $query)) {
	    mysqli_stmt_execute($stmt);
	    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5);

	    while (mysqli_stmt_fetch($stmt)) {

	        $tempArray = array();

	        for ($i=0; $i < count($grabParams); $i++) { 

	            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
	        };

	        $arrPaperBag[] = $tempArray;

	    };

	    mysqli_stmt_close($stmt);    
	                            
	}
	else {

	    echo mysqli_error($conn);

	};
	// print_r($arrPaperBag);
	// print_r($arrHardCase);
?>
<div style="padding-left: 23px; padding-right: 23px; padding-top: 20px; text-align: left;">
	<input type="hidden" name="order_id_value" value="<?= $_GET['order_id']?>">
	<input type="hidden" name="po_number" value="<?= $_GET['po_number']?>">
	<input type="hidden" name="orders_specs_id" value="<?= $_GET['orders_specs_id']?>">
	<div class="d-flex justify-content-between">
		<p>NAME: <?= $_GET['name']?> </p>
		<p>PO NUMBER: <?= $_GET['po_number']?> </p>
	</div>
	<div class="row">
		<div class="col-9" style="padding-right: 0px;">
			<select name="paper_bag" class="form-control">
				<option value="" selected disabled>Paper Bag</option>
				<option value="">None</option>
				<?php for($i = 0; $i < count($arrPaperBag); $i++){ ?>
						<option value="<?= $arrPaperBag[$i]['product_code'] ?>"><?= $arrPaperBag[$i]['color'] ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-3" style="padding-left: 0px; padding-top: 20px;">
			<input type="number" class="form-control" name="paper_bag_quantity" placeholder="Quantity" style="border-left-style: none; font-size: 14px;" min="0">
		</div>
	</div>
	<div class="row mt-3">
		<div class="col-9" style="padding-right: 0px;">
			<select name="hard_case" class="form-control" required>
				<option value="" selected disabled>Hardcase</option>
				<?php for($i = 0; $i < count($arrHardCase); $i++){ ?>
						<option value="<?= $arrHardCase[$i]['product_code'] ?>"><?= $arrHardCase[$i]['color'] ?></option>
				<?php } ?>
			</select>
		</div>
		<div class="col-3" style="padding-left: 0px;">
			<select name="hardcase_from" class="form-control" style="border-left-style: none;" required>
				<option value="" selected disabled>From</option>
				<option value="store">STORE</option>
				<option value="lab">LABORATORY</option>
			</select>
		</div>
	</div>
</div>