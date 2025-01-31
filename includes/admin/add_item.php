<div class="modal-header">
	<h4 class="modal-title">ADD ITEM</h4>
	<button type="button" class="close" data-dismiss="modal">&times;</button>
</div>

<div class="modal-body">
	<div class="inner-chat">
		
		<form action="../../process/admin/add_item.php" method="POST">
			<div class="row">
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label class="font-weight-bold" for="p_city">Serial No./Product Code</label>
						<input type="text" name="snumber" class="text-center form-control" required>
					</div>
				</div>
				<div class="col-sm-6 col-md-6">
					<div class="form-group">
						<label class="font-weight-bold" for="p_city">Item</label>
						<input type="text" name="item" class="text-center form-control" required>
					</div>
				</div>
				<div class="col-sm-12 col-md-12">
					<div class="form-group">
						<label class="font-weight-bold" for="p_city">Description</label>
						<textarea name="description" class="text-center form-control" required> </textarea>
					</div>
				</div>
				<div class="col-sm-6 offset-sm-3 col-md-6 offset-md-3">
					<div class="form-group form-btn">
						<input type="submit" name="submit" class="form-control text-center btn-primary">
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<div class="modal-footer align-items-end justify-content-center col-12 no-gutters">
</div>