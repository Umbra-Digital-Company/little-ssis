<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	

// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/admin/grab_inventory.php";
	//  include("../includes/grab_inventory.php");
	?>

	<table class="table table-hover table-striped table-bordered">
		<thead>
			<tr class="head">
				<th>#</th>
				<th colspan="2">Product</th>
				
			</tr>
		</thead>
		<tbody>
			<?php for($i=0;$i<sizeof($arrItems);$i++){ ?>
					<tr class="body">
						<td class="cell100 column1"><?= $i+1  ?></td>
							<td >
							<label>Item :</label>	<?php echo $arrItems[$i]["item_name"]."<br>  <label> Details</label> :".$arrItems[$i]["item_description"] ?>
						<Br>
							<label>Serial/Product No.</label><?= $arrItems[$i]["product_code"] ?>		
						<Br>
							<label>Owned by </label>: <?= $arrItems[$i]["first_name"]." ".$arrItems[$i]["last_name"] ?>
							</td>
							<Td class="text-center"><a class="inventory-delete" href="../process/delete_item.php?item_code=<?php echo $arrItems[$i]["item_id"] ?>&delete=1">
						<i class="zmdi zmdi-close text-danger"></i></a></Td>
					</tr>
			<?php } ?>
		
		</tbody>
	</table>	