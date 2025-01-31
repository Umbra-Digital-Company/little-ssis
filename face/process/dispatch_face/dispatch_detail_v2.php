<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if(!isset($_SESSION['user_login']['username'])) {
    header("Location: /");
    exit;
}


	// Required includes

	if(!defined('DB_SERVER')){
		require_once $sDocRoot."/includes/connect.php";
	}

// Required includes
require $sDocRoot."/includes/grab_employees_list.php";

?>

<script type="text/javascript" src="/js/signature.js"></script>

<style>
	
	.canvas-holder {
		width: 100%;
		max-width: 600px;
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
	.canvas-holder #thecanvas {
		border: 2px solid #e6e6e6;
		margin-top: 25px;
	}
	.canvas-holder button {
		margin-top: 20px;
		margin: 20px 5px;
	}
	.canvas-holder select,
	.canvas-holder textarea {
		max-width: 554px;
		margin: 20px auto 0;
	}
	.canvas-holder textarea {
		display: none;
		resize: none;
		height: 100px;
		padding: 15px;
	}
	.signature-preview {
		display: none;
	}
	.no-signature h4 {
		line-height: 30px;
		color: #808080;
		max-width: 475px;
		margin: 0 auto 20px;
	}
	.dispatch-staff-container {
		margin-top: 25px;
	}
	.dispatch-staff-container .select2-selection {
		text-align: left;
	}

</style>

<form class="dispatch-form" action="/face/process/dispatch_face/dispatch_process_v3.php" method="POST" name="form-dispatch">
	
	<input type="hidden" name="sig" class="signature64" value="">

	<div class="text-right">

		<input type="hidden" name="order_id" value="<?php echo $_GET['cn']; ?>">
		<input type="hidden" name="orders_specs_id" value="<?= $_GET['orders_specs_id']?>">
		<input type="hidden" name="payment_date" value="<?= $_GET['payment_date']?>">
	</div>

	<div class="canvas-holder text-center">
		<?php
		if($_SESSION['store_code'] != '150' &&  $_SESSION['store_code'] != '142'  &&  $_SESSION['store_code'] != '155' && strtotime($_GET['payment_date']) >= strtotime(date('2021-05-10')) ){
			$arrHardCase = array();

			 $query='SELECT
			            item_name,
			            LOWER(REPLACE(item_name,  TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1)), "")) AS "grab_color",
			            product_code,
			            LOWER(TRIM(LEFT(item_name , LOCATE(" ", item_name) - 1))) AS "grab_style" ,
			            p.price
			        FROM
			            poll_51_new p
			            
			            WHERE ( p.product_code LIKE "hc%" or p.product_code LIKE "SC%%" ) AND p.price="0" ORDER BY p.item_name ASC;';

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

		?>
		<div style="padding-left: 23px; padding-right: 23px; padding-top: 20px; text-align: left;">
			<input type="hidden" name="order_id_value" value="<?= $_GET['order_id']?>">
			<input type="hidden" name="po_number" value="<?= $_GET['po_number']?>">
		
			<div class="d-flex justify-content-between">
				<p>NAME: <?= $_GET['name']?> </p>
				<p>PO NUMBER: <?= $_GET['po_number']?> </p>
			</div>
			<?php
				$arrSow = ['147','148','149'];
				$column = (in_array($_SESSION['store_code'], $arrSow)) ? 6 : 9;

				$arrPaperBag = ['SF019-02' => 'SHIPPING BOX PH', 'SFP018-01' => 'BUBBLE MAILER (SMALL)', 'SFP018-02' => 'BUBBLE MAILER (BIG)'];
			?>
			<div class="row">
				<div class="col-<?=$column?>" style="padding-right: 0px;">
					<select name="paper_bag" class="form-control" style=" border-right-style: none;" required>
						<option value="" selected disabled>Paper Bag</option>
						<option value="">None</option>
						<?php foreach ($arrPaperBag as $key => $value) { ?>
								<option value="<?= $key ?>"><?= $value ?></option>
						<?php } ?>
					</select>
				</div>
				<?php if(in_array($_SESSION['store_code'], $arrSow)) { ?>
					<div class="col-3" style="padding-left: 0px; padding-right: 0px;">
						<select name="paper_bag_from" class="form-control" style="border-right-style: none;" required>
							<option value="" selected disabled>From</option>
							<option value="store">STORE</option>
							<option value="lab">LABORATORY</option>
						</select>
					</div>
			<?php } ?>
				<div class="col-3" style="padding-left: 0px; padding-top: 20px;">
					<input type="number" class="form-control" name="paper_bag_quantity" placeholder="Quantity" max="5" style="font-size: 14px;" min="0">
				</div>
			</div>
		</div>
	<?php } ?>
	
		<select class="form-control" name="dispatch_type" id="dispatch_type">
			<option value="dispatch" selected="">Dispatch</option>
			<!-- <option value="remake">Remake</option>			 -->
		</select>

		<div class="form-group dispatch-staff-container">
			<select class="form-control text-left" name="dispatch_staff" id="dispatch_staff" required>
				<option value="" selected disabled>Select Staff</option>
				
				<?php

					// Cycle through employees array
					for ($i=0; $i < sizeOf($arrEmployees); $i++) { 
						if($arrEmployees[$i]["designation"]!='OPTOMETRIST' && $arrEmployees[$i]['designation']!='AREA DOCTOR' && $arrEmployees[$i]['designation']!='HEAD CORPORATE DOCTOR'){
							// Set current data
							$curEmployeeID 	 = $arrEmployees[$i]['emp_id'];
							$curEmployeeName = $arrEmployees[$i]['first_name']." ".$arrEmployees[$i]['middle_name']." ".$arrEmployees[$i]['last_name'];

							echo '<option value="'.$curEmployeeID.'">'.$curEmployeeName.'</option>';
						}
					};

				?>

			</select>
		</div>
					
		<canvas id="thecanvas" width="550" height="148" border="0"></canvas>

	<div style="padding-left:30px;padding-right:30px">	
					<p>
							
							I acknowledge that I have received my Sunnies Face pair in good condition.
							 The staff have also discussed ways to care for my new item and what warranty is available to me.
							
						</p>
	</div>
		<textarea class="form-control" name="cancel_remark" id="cancel_remark" placeholder="Reason for Reorder/Remake"></textarea>
		<button type="submit" class="btn ssis-btn-primary save">dispatch</button>
		<button type="button" class="btn ssis-btn-secondary clear">clear</button>
		<button type="button" class="btn ssis-btn-secondary close-signature" id="close-signature_<?php echo $_GET['cn'];?>">close</button>

	</div>

</form>

<script>

$(document).ready(function() {

		// ========================= SELECT2

		console.log('ready');

		$('#dispatch_staff').select2();
		$('#dispatch_doctor').select2();
		
		// ========================= SELECT ACTION

		$('#dispatch_type').on('change', function() {

			if ( $(this).val() == 'dispatch' ) {

				$('#thecanvas').show();
				$('#cancel_remark').hide();
				$('.save').text('dispatch');

			} 
			else {

				$('#thecanvas').hide();
				$('#cancel_remark').show();
				$('.save').text('submit');

			}

		});
				
		var reader = new FileReader();

		reader = function() {

		    $(".link").attr("href",reader.result);
		    $(".link").text(reader.result);

		}

	    var canvas = document.getElementById('thecanvas');
	    var signaturePad = new SignaturePad(canvas);   

	    drawSignatureLine();
		
		var file =signaturePad.toDataURL("image/png");
	    
	    $('.save').click(function(){ 	

			$(".signature64").val(signaturePad.toDataURL("image/png"));

			//  window.open(signaturePad.toDataURL("image/png"));
			if ( $('#signature64').val() != '' ) {

				$('#overlay, .no-signature').fadeOut();
				$('.dispatch-form').fadeIn();

			}

	    });
	    
	    $('button.clear').click(function(){

			$(".signature64").val("");
	        signaturePad.clear();        
	        drawSignatureLine();

	    });

	    $('.close-signature').click(function(e) {

	    	e.preventDefault();

			var id = $(this).attr("id").replace("close-signature_","");

	    	$('#overlay_'+ id).fadeOut();

	    });

	    function drawSignatureLine(){  
			
			var context = canvas.getContext('2d');          
			context.lineWidth = .5;
			context.strokeStyle = '#333';
			context.beginPath();
			context.moveTo(0, 150);
			context.lineTo(500, 150);
			context.stroke();
			
	    }
		 
		function encodeImagetoBase64(element) {

			var file = element.files[0];
			var reader = new FileReader();

			reader.onloadend = function() {

				$(".link").attr("href",reader.result);
				$(".link").text(reader.result);

			}

			reader.readAsDataURL(file);

		}

});

</script>