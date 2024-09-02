<?php 

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes
require $sDocRoot."/includes/connect.php";

$arrMessage = array();

$query = 	"SELECT 
				DATE_ADD(rc.date_created, INTERVAL 13 hour),
				DATE_ADD(rc.date_updated, INTERVAL 13 hour),
				#DATE_ADD(rc.date_created,INTERVAL 13 HOUR),
				#DATE_ADD(rc.date_updated,INTERVAL 13 HOUR),
				rc.order_po_id,
				rc.profile_id,
				rc.message,
				rc.message_id,
				u.first_name,
				u.last_name,
				s.first_name,
				s.last_name
			FROM 
				remarks_comm rc
					LEFT JOIN emp_table u 
						ON u.emp_id=rc.profile_id
			 		LEFT JOIN users s 
			 			ON s.id=rc.profile_id
			WHERE
				rc.order_po_id='".$_GET['id']."'
			ORDER BY 
				date_created ASC"; 

$grabParams = array(

	'date_created',
	'date_updated',
	'order_po_id',
	'profile_id',
	'message',
	'message_id',
	'first_name',
	'last_name',
	'l_first_name',
	'l_last_name'

);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {

    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrMessage[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 

function cvdate($d){

	$returner = '';
	$datae=date_parse($d); 
	$returner .= getMonth($datae['month'])." ".$datae['day'].", ".$datae['year'];
	$suffix = "AM";
	$hour = $datae['hour'];

	if ($datae['hour']>'12') {

		$hour = $datae['hour']-12;

	};

	if ($datae['hour']>'11' && $datae['hour']<'24') {

		$suffix = "PM";

	};

	$returner .= " at ".AddZero($hour).":".AddZero($datae['minute']).":".AddZero($datae['second'])." ".$suffix;	

	return $returner;

};

function getMonth($mid){

	switch($mid){

		case '1': return "Jan"; break;
		case '2': return "Feb"; break;
		case '3': return "Mar"; break;
		case '4': return "Apr"; break;
		case '5': return "May"; break;
		case '6': return "Jun"; break;
		case '7': return "Jul"; break;
		case '8': return "Aug"; break;
		case '9': return "Sep"; break;
		case '10': return "Oct"; break;
		case '11': return "Nov"; break;
		case '12': return "Dec"; break;
		
	};

};

function AddZero($num){

	if (strlen($num)=='1') {

		return "0".$num;

	} 
	else {

		return $num;

	};

};



?>

<style>

	#informationRemarks .modal-body {
		max-height: 300px;
		overflow-y: auto;
		overflow-x: hidden;
	}

	.remarks-messenger {
		margin-bottom: 25px;
		position: relative;
	}

	.remarks-messenger p {
		margin: 0;
	}

	.remarks-messenger .col-10 {
		padding: 15px;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}

	.lab-messenger .col-10::before {
		content: '';
		position: absolute;
		bottom: -5px;
		left: 15px;
		border-top: 8px solid #eee;
		border-right: 8px solid transparent;
		border-left: 8px solid transparent;
	}

	.store-messenger .col-10::before {
		content: '';
		position: absolute;
		bottom: -5px;
		right: 15px;
		border-top: 8px solid #36482e;
		border-right: 8px solid transparent;
		border-left: 8px solid transparent;
	}

	#messenger_input {
		border: 1px solid #e6e6e6;
		resize: none;
		width: 100%;
		padding: 10px 10px 0;
		min-height: 20px;
		overflow: hidden;
		-webkit-border-radius: 5px;
		-moz-border-radius: 5px;
		border-radius: 5px;
	}

	.send-messenger {
		font-size: 20px;
		background: transparent;
		border: 0;
		padding: 0 20px 0 30px;
		color: #36482E;
	}

</style>
										
<div class="modal-header">
    <div class="row">
        <div class="col-12">
			<div class="row">
				<div class="col-4">
					<span>PO# <?=$_GET['po_number']?></span>
				</div>

				<div class="col-8">
					<div class="input-group ml-1">
						<select class="form-control" id='information_selected' name='information_selected' selected_concern="<?=$_GET['concern']?>" style="width: 145px; " required>
							
							<option value="Doctor-refraction-process-related-issue">Doctor-refraction/process related issue</option>
							<option value="Doctor-behavior-related-issue">Doctor-behavior related issue</option>
							<option value="Staff-performance-process-related-issue">Staff-performance/process related issue</option>
							<option value="Staff-behavior-related-issue">Staff-behavior related issue</option>
							<option value="Inventory-lens-oos">Inventory-Lens OOS</option>
							<option value="Inventory-frame-oos">Inventory-Frame OOS</option>
							<option value="Inventory-ftc">Inventory-FTC</option>
							<option value="Wrong-item-delivered">Wrong item delivered</option>
							<option value="Damaged-lens-in-warranty">Damaged lens-In warranty</option>
							<option value="Damaged-lens-out-warranty">Damaged lens-Out warranty</option>
							<option value="Change-frame-manufacture-defect">Change frame-manufacture defect</option>
							<option value="Change-frame-customer-mishandling">Change frame-customer mishandling</option>
							<option value="Customer-change-of-mind">Customer change of mind</option>
							<option value="Quality-issue-upon-qc">Quality issue upon QC</option>
							<option value="Ssis-issue">SSIS issue</option>
							<option value="Delayed-delivery">Delayed delivery</option>
							<option value="Essilor-concern">Essilor concern</option>
							<option value="Allergic-reaction">Allergic reaction</option>

						</select>
						<div class="input-group-append">
							<button class="btn btn-success" type="button" id="btnSave">SAVE</button>
						</div>
					</div>
				</div>
			</div>

        </div>
		<!-- <div class="col-6">
			
		
        </div> -->
        <div class="col-12 mt-2">
            <?php
                $str = $_GET['concern'];
				
                $concern = (explode(",",$str)); 
				// print_r($concern);
				// exit;
            ?>

            <h4 class="modal-title title-concern">

                <?php
                $total = 0;
                foreach ($concern as $value) {
                    // $q = ($total > 0) ? ', ' : '';
                    echo "<br>".str_replace("-"," ",$value);
                    // $total++;

                  } ?>
                  
            </h4>
        </div>
    </div>
	<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>
<div class="modal-body">
	<div class="inner-chat">

	<?php

		for($i=0;$i<sizeof($arrMessage);$i++){
	
			if($arrMessage[$i]["profile_id"]!=$_SESSION['id']){ 

	?>
	
		<div class="lab-messenger remarks-messenger">
			
			<div class="row no-gutters align-items-start justify-content-start">
				<div class="col-10 ssis-bg-gray">
					<p><?= $arrMessage[$i]["message"]; ?></p>
				</div>
				<p class="font-weight-bold small" style="margin-top: 5px; opacity: .65;">
					<?= ucwords( $arrMessage[$i]["l_first_name"] ); ?> <?= ucwords( $arrMessage[$i]["l_last_name"] ); ?>
					<?=  cvdate($arrMessage[$i]["date_created"])?>
				</p>
			</div>
			
		</div>
	
	<?php 

			} 
			else{

	?>
	
		<div class="store-messenger remarks-messenger">
			<div class="row no-gutters align-items-end justify-content-end">
				<div class="col-10 text-white ssis-bg-primary">
					<p><?= $arrMessage[$i]["message"]; ?></p>
				</div>
				<p class="font-weight-bold text-right small" style="margin-top: 5px; opacity: .65;">
					<?= ucwords( $arrMessage[$i]["first_name"] ); ?> <?= ucwords( $arrMessage[$i]["last_name"] ); ?>
					<?=  cvdate($arrMessage[$i]["date_created"])?>
				</p>
			</div>
		</div>
		
	<?php 

			}

		} 

	?>

	</div>
</div>

<div class="modal-footer align-items-end justify-content-center col-12 no-gutters">
	<form  method="POST" id="messenger_form" class="row align-items-end col-12 flex-nowrap">
		<input type="hidden" value="<?= $_GET['id'] ?>" name="order_specsid" id="order_specsid">
		<input type="hidden" value="<?= $_SESSION['id'] ?>" name="sender" id="sender">
		<textarea name="messenger_input" id="messenger_input" class="messenger-area" placeholder="Reply"></textarea>
		<button type="button" class="send-messenger"><i class="zmdi zmdi-mail-send"></i></button>
	</form>
</div>
												
<script>

	$('.messenger-area').on('keyup', function() {

		$(this).css('height','auto');
		$(this).height(this.scrollHeight);

	});

	$('.send-messenger').click(function(){

		var order_specs = $('#order_specsid').val(),
			senderd = $('#sender').val(),
			messenger_inputd = $('#messenger_input').val();
	
		if ( $('.messenger-area').val() == '' ) {

			$('.messenger-area').css('border-color', 'tomato');

		} 
		else {

			$.ajax({

				url: '../process/dispatch/chat_process.php',
				method: 'POST',
				data: {order_specsid:order_specs,sender:senderd,messenger_input:messenger_inputd},
				success: function() {

					previewChat( messenger_inputd );
					$('#messenger_input').val('');

				}

			});

		}

	});
		
		var first_select = "<?= $_GET['concern'] ?>";
		var orders_id = "<?= $_GET['orders_specs_id'] ?>";
		// var data_selected =[];
		// alert(orders_id);
		
		// if(concern_data.length == 0){
			concern_data.push({"data":first_select,"orders_specs_id": orders_id});
		// }

		console.log(concern_data);
		// alert(concern[0].selected);

		$('#information_selected').val('');
		// alert($('#information_selected').attr('selected_concern')+' '+$('#information_selected').val());
		$('#information_selected option').each(function(){
			// alert($('#information_selected').attr('selected_concern'));
			// var selected = $(this).val()
			
			if($(this).val() == $('#information_selected').attr('selected_concern')){
				// alert($(this).val());
				$("#information_selected option[value=" + $(this).val() + "]"). hide();
			}
			// data_select = $(this).val();

			// console.log($(this).val());
		});


		$('#btnSave').click(function(){
			// alert(concern);	
			// alert($('#information_selected').val());
			var info = $('#information_selected').val();

			if($('#information_selected').val() == null || $('#information_selected').val() == ''){
				// alert("pls select");
				
			}else{
				var s = info.replaceAll("-"," ");
				$('.title-concern').append("<br>"+s);
				$('#information_selected option').each(function(){
				
					if($(this).val() == info){
						

						$.ajax({
							url: "../process/dispatch/concern.php",
							type: "POST",
							data: {selected:info, orders_specs_id: orders_id},
							// dataType: 'json',
							success: function(response){
								// console.log(response);
								// location.reload();
								// $('#informationRemarks').modal('show');
								// $('.modal-title').html(selected);
							
								// concern_data.push({"data":$(this).val(),"orders_specs_id":orders_id});
								// var id = orders_specs_id;

								// $('#informationRemarks .modal-content').load("../process/dispatch/remarks_chat_test.php?id="+ id+'&concern='+selected+'&po_number='+po_number+'&orders_specs_id='+orders_specs_id, function() {
								// 	scrollChat( 500 );
								// 	$('#informationRemarks').modal('show');
								// });

								// $("#concern_selected").val(null).trigger("change"); 

							},
							error: function(){
								// $('body').waitMe('hide');
							}
						});//END :: AJAX

						// alert(data_selected);
						$("#information_selected option[value=" + $(this).val() + "]"). hide();
					}
			
				});
				
			}

			// console.log(data_selected);
			$('#information_selected').val('');
		});
		// let data_select = [];
		// alert(data_selected.length);


		// if(concern_data.length != 0){
		// 	// console.log(concern_data.length);
		// 	for(var c=0; c<concern_data.length; c++){
		// 		$('.title-concern').append("<br>"+concern_data[c].data);
		// 	}

		// }

</script>