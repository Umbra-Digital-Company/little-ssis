<?php 

$count = "0";
$prod = "0";
$comp = "0";
	
for($i=0;$i<sizeof($_POST['off']);$i++){
			
	if(isset($_POST['receive'][$_POST['off'][$i]])){ 

		$comp++;

	}

	$count = $comp; 

} 

$arrActionType = array( 

	'reject' => "Reject",
	'save'  => "Receive"
		
);

?>

<style>

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

	.form-remarks2 h5 {
		font-weight: 500;
		font-size: 18px;
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
		margin-top: 20px;
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

</style>

<div class="form-remarks2">

<?php if ( $count == '0' ) : ?>
	
	<h5 class="text-center" style="margin: 0;"> Please Check Order to <span class="<?= ( $_POST['action_type'] == 'reject' ) ? 'text-danger' : 'text-success'; ?>"><?= ucwords($_POST['action_type']); ?></span> </h5>
	
<?php else : ?>

	<form action="../process/dispatch/receive.php" method="post">

		<h5>Confirmation </h5>
		<p>Are you sure you want to mark this <?= ($count > 1) ? "order's" : 'order'; ?> as <span class="<?= ( $_POST['action_type'] == 'reject' ) ? 'text-danger' : 'text-success'; ?>"><?= strtoupper($arrActionType[$_POST['action_type']]); ?></span>?</p>
	
		<table cellpadding="0" cellspacing="0" class="confirmation-list-of-orders">
			<thead>
				<tr>
					<th>NAME</th>
					<th>PO #</th>
				</tr>
			</thead>
			<tbody>

				<?php for ($i=0;$i<sizeof($_POST['off']);$i++) : ?>

					<?php if(isset($_POST['receive'][$_POST['off'][$i]])  || isset($_POST['complete'][$_POST['off'][$i]]) ) : ?>

						<tr>
							<td><?php echo $_POST['name'][$_POST['off'][$i]]; ?></td>
							<td><?php echo $_POST['order_id'][$_POST['off'][$i]]; ?></td>
						</tr>

					<?php endif ?>

				<?php endfor ?>

			</tbody>
		</table>
		
		<input type="hidden" value="save" name="update_type">

		<?php for ($y=0; $y<sizeof($_POST['off']); $y++) : ?>
		
			<input type="hidden" name="off[]" value="<?php echo  $_POST['off'][$y]; ?>">
			<input type="hidden" name="order_id[<?= $_POST['off'][$y] ?>]" value="<?php echo  $_POST['order_id'][$_POST['off'][$y]] ?>">
		
		<?php if(isset($_POST['complete'][$_POST['off'][$y]])) : ?>
		
			<input type="hidden" value="<?php echo $_POST['complete'][$_POST['off'][$y]]; ?>" name="complete[<?= $_POST['off'][$y] ?>]">

		<?php endif ?>

	 	<?php if (isset($_POST['receive'][$_POST['off'][$y]])) : ?>

			<input type="hidden" value="<?php echo $_POST['receive'][$_POST['off'][$y]]; ?>" name="receive[<?= $_POST['off'][$y] ?>]">
		
		<?php endif ?>
		
	<?php endfor ?>

	<br>

	<!-- <?php if($_POST['action_type']=='reject'){  ?>

		<select class="form-control" name="receive_type" id="dispatch_type">		
			<option value="remake">Remake</option>
			
		</select>

	<?php } ?> -->

		<h5 style="margin-top: 30px;">Remarks</h5>
		<div class="textarea-control">
			<textarea class="form-control" name="remarks" placeholder="<?= ( $_POST['action_type'] == 'reject' ) ? 'Reason for rejecting this order' : 'Other notes'; ?>"></textarea>
		</div>

		<div class="text-center" style="margin-top: 30px;">
			<input class="btn ssis-btn-primary" type="submit" value="Submit">
		</div>
			
	</form>

<?php endif ?>

	<div class="close-confirmation">
		<span class="text-danger">&times;</span>
	</div>

</div>