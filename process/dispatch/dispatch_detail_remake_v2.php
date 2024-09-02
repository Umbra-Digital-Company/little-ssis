<?php 

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/grab_employees_list.php";

?>

<script type="text/javascript" src="../js/signature.js"></script>

<style>
	
	.canvas-holder {
		width: 100%;
		max-width: 525px;
		background: #fff;
		position: absolute;
		top: 50%;
		left: 50%;
		border-radius: 14px;
		-webkit-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		-o-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);	
	}
	/*.canvas-holder #thecanvas {
		border: 2px solid #e6e6e6;
		margin-top: 25px;
	}*/
	.canvas-holder button {
		margin-top: 20px;
		margin: 20px 5px;
	}
	.canvas-holder select,
	.canvas-holder textarea {
		max-width: 479px;
		margin: 20px auto 0;
	}
	.canvas-holder textarea {
		resize: none;
		height: 100px;
		padding: 15px;
	}
	/*.signature-preview {
		display: none;
	}*/
	.no-signature h4 {
		line-height: 30px;
		color: #808080;
		max-width: 400px;
		margin: 0 auto 20px;
	}
	.dispatch-staff-container {
		margin-top: 25px;
	}
	.dispatch-staff-container .select2-selection {
		text-align: left;
	}

</style>

<form class="dispatch-form" action="../process/dispatch/dispatch_process_remake_v2.php" method="POST" name="form-dispatch">

	<div class="text-right">
		<input type="hidden" name="order_id" value="<?php echo $_GET['cn']; ?>">
		<input type="hidden" name="order_specs_id" value="<?php echo $_GET['order_specs_id']; ?>">
		<input type="hidden" name="po_number" value="<?php echo $_GET['po_number']; ?>">
	</div>

	<div class="canvas-holder text-center">

		<select class="form-control" name="dispatch_type" id="dispatch_type">			
			<option value="remake">Remake</option>
		</select>

		<div class="form-group dispatch-staff-container">
			<select class="form-control text-left" name="dispatch_staff" id="dispatch_staff" required>
				<option selected disabled>Select Staff</option>
				
				<?php

					// Cycle through employees array
					for ($i=0; $i < sizeOf($arrEmployees); $i++) { 
						
						// Set current data
						$curEmployeeID 	 = $arrEmployees[$i]['emp_id'];
						$curEmployeeName = $arrEmployees[$i]['first_name']." ".$arrEmployees[$i]['middle_name']." ".$arrEmployees[$i]['last_name'];

						echo '<option value="'.$curEmployeeID.'">'.$curEmployeeName.'</option>';

					};

				?>

			</select>
		</div>

		<!-- <canvas id="thecanvas" width="475" height="148" border="0"></canvas> -->
		<textarea class="form-control" name="cancel_remark" id="cancel_remark" placeholder="Reason for Reorder/Remake" required></textarea>
		<button type="submit" class="btn ssis-btn-primary save">submit</button>
		<button type="button" class="btn ssis-btn-secondary close-signature" id="close-signature_<?php echo $_GET['cn'];?>">close</button>

	</div>

</form>

<script>

	jQuery(function($){ 

		// ========================= SELECT2

		$('#dispatch_staff').select2();

		// ========================= CLOSE SIGNATURE

	    $('.close-signature').click(function(e) {

	    	e.preventDefault();

			var id = $(this).attr("id").replace("close-signature_","");

	    	$('#overlay_'+ id).fadeOut();

	    });

	});

</script>