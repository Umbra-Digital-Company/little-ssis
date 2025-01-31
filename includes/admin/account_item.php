<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	
require $sDocRoot."/includes/connect.php";
require $sDocRoot."/includes/admin/grab_inventory.php";
	//  include("../includes/grab_inventory.php");
	?>

<div class="modal-header">
	<h4 class="modal-title">ADD ITEM</h4>
	<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
	<div class="inner-chat">
		
		<form action="../../process/admin/account_item.php" method="POST">
			
			
			<div class="col-sm-12 col-md-12">
				
						<div class="form-group">
							<label class="font-weight-bold" for="p_city">Select Item :</label>
							<input type="hidden" name="emp_id" value="<?= $_GET["id"] ?>">
							<select class="form-control text-center" name="item">
								<option></option>
							<?php 
							for($i=0;$i<sizeof($arrItems);$i++){
								?>
								<option value="<?=  $arrItems[$i]["item_id"] ?>"><?=  $arrItems[$i]["item_name"]."       - Product Code ".$arrItems[$i]["product_code"]; ?></option>
								<?php
								
							}
							
							?>
								</select>
						</div>
					</div>
					
		<div class="col-sm-12 col-md-12">
				
						<div class="form-group">
							<input type="submit" name="submit" value="Save" class="form-control">
						</div>
					</div>
		</form>
		
		
		
	</div>
</div>
<div class="modal-footer align-items-end justify-content-center col-12 no-gutters">
</div>