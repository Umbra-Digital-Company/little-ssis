<?php

?><Style>
.form-remarks2 {
	position: fixed;
	top: 50px;
	left: 50%;
	margin-left: -300px;
	width: 600px;
	z-index: 9999;
	padding: 20px;
	background: #fff;
}

.form-remarks2 .textarea-control {
	border: 1px solid #2d2d2d;
	padding: 15px;
	margin-top: 15px;
}

.form-remarks2 textarea {
	resize: none;
	border: 0;
	padding: 0;
	height: 100%;
	background: transparent !important;
}

.close-confirmation {
	position: absolute;
	top: 0;
	width: 30px;
	height: 30px;
	text-align: center;
	right: 0;
	cursor: pointer;
}

.close-confirmation span {
	font-size: 20px;
}

.form-remarks2 table {
	width: 100%;
}

.form-remarks2 table,
.form-remarks2 table tr td {
	border: 1px solid #2d2d2d;
	border-collapse: collapse;
	padding: 10px 15px;
}

.form-remarks2 table thead {
	background: #2d2d2d;
	color: #fff;
}

.form-remarks2 table thead tr th {
	padding: 10px 15px;
}
</Style>


<div class="form-remarks2">

	<?php 
    // echo "<pre>";
    // print_r($_POST);
    // echo "</pre>";
$marked="";
	$count ="0";
	$prod="0";
	$comp="0";
	
	for($i=0;$i<sizeof($_POST['off']);$i++){
		if(isset($_POST['production'][$_POST['off'][$i]])  || isset($_POST['complete'][$_POST['off'][$i]]) ){
			
			 if(isset($_POST['complete'][$_POST['off'][$i]])){ 
				$comp=sizeof($_POST['complete'][$_POST['off'][$i]])	;
				 $marked='COMPLETE';
			 }
			 if(isset($_POST['production'][$_POST['off'][$i]])){ 
				 $prod=sizeof($_POST['production'][$_POST['off'][$i]])	;
				 $marked='PRODUCED';
			 }
			
			$count = $prod + $comp;
			
			
	 if(isset($_POST['production'][$_POST['off'][$i]])  && $_POST['action_type']!='reject'){ 
		 $action_type='PRODUCED';
	
	 }elseif(isset($_POST['complete'][$_POST['off'][$i]]) && $_POST['action_type']!='reject'){ 
		  $action_type='COMPLETED';
		 
	 }elseif($_POST['action_type']=='reject'){
		 $action_type='REJECTED';
	 }
			
			
		}
		
		
		
		
		
		
		
	}
	

	?>

	<?php if ($count=='0') : ?>
		
		<h2 class="text-center" style="margin: 0;"> Please Check Order to <span class="<?= ( $_POST['action_type'] == 'reject' ) ? 'text-danger' : 'text-success'; ?>"><?= ucwords($_POST['action_type']); ?></span> </h2>
		
	<?php else : ?>
		
		<form action="../process/lab_updatev2.php" method="post">
			<h2>Confirmation </h2>
			<p>Are you sure you want to mark this <?= ($count > 1) ? "order's" : 'order'; ?> as  being  <span class="<?= ( $_POST['action_type'] == 'reject' ) ? 'text-danger' : 'text-success'; ?>">
				<?= strtoupper($action_type); ?></span>?</p>
			<table cellpadding="0" cellspacing="0" class="confirmation-list-of-orders">
				<thead>
					<tr>
						<th>NAME</th>
						<th>PO #</th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i=0;$i<sizeof($_POST['off']);$i++) : ?>
						<?php if(isset($_POST['production'][$_POST['off'][$i]])  || isset($_POST['complete'][$_POST['off'][$i]]) ) : ?>
							<tr>
								<td><?php echo $_POST['name'][$_POST['off'][$i]]; ?></td>
								<td><?php echo $_POST['po_number'][$_POST['off'][$i]]; ?></td>
							</tr>
						<?php endif ?>
					<?php endfor ?>
				</tbody>
			</table>
		
			<input type="hidden" value="<?= $_POST['action_type']; ?>" name="update_type">

			<?php for ($y=0; $y<sizeof($_POST['off']); $y++) : ?>
			
				<input type="hidden" name="off[]" value="<?php echo  $_POST['off'][$y]; ?>">
				<input type="hidden" name="order_id[<?php echo  $_POST['off'][$y]; ?>]" value="<?php echo $_POST['order_id'][$_POST['off'][$y]]; ?>" >
			
				<?php if(isset($_POST['complete'][$_POST['off'][$y]])) : ?>
			
					<input type="hidden" value="<?php echo $_POST['complete'][$_POST['off'][$y]]; ?>" name="complete[<?= $_POST['off'][$y] ?>]">
			
				<?php endif ?>

		 		<?php if (isset($_POST['production'][$_POST['off'][$y]])) : ?>

					<input type="hidden" value="<?php echo $_POST['production'][$_POST['off'][$y]]; ?>" name="production[<?= $_POST['off'][$y] ?>]">
			
				<?php endif ?>
			
			<?php endfor ?>
			
			<?php if (isset($_POST['production'])) : ?>
			
				<h2 style="margin-top: 30px;">Target Date & Time</h2>
				<div class="form-group">
					<div class="row">
						<div class="col-6">
							<label class="font-weight-bold" for="bdate">Date</label>
							<input type="date" name="Tdate" class="form-control required-name" id="bdate">
						</div>
						<div class="col-6">
							<label class="font-weight-bold" for="btime">Time</label>
							<input type="time" name="Ttime" class="form-control required-name" id="btime">
						</div>
					</div>
				</div>

			<?php endif ?>

			<h2 style="margin-top: 30px;">Remarks</h2>
			
			<div class="textarea-control">
				<textarea class="form-control" name="remarks" placeholder="<?= ( $_POST['action_type'] == 'reject' ) ? 'Reason for rejecting this order' : 'Other notes'; ?>" ></textarea>
			</div>

			<div class="text-center" style="margin-top: 30px;">
				<input class="btn btn-primary" type="submit" value="Submit">
			</div>
			
		</form>

	<?php endif ?>

	<div class="close-confirmation">
		<span class="text-danger">&times;</span>
	</div>

</div>
