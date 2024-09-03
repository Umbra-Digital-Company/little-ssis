
<div class="d-flex justify-content-center">
	<div class="card">
		<div class="card-header">Order Confirmation</div>
	  <div class="card-body">
	  	<p>NAME : <?= $_GET['name'] ?></p><br>
	  	<p>ORDER ID : <?= $_GET['order_id'] ?></p><br>
	  	<div class="d-flex justify-content-between">
		  	<a href="/dispatch" style="color: #fff; margin-right: 30px;" target="_blank"><button type="button" class="btn btn-primary">Go to Order Management</button></a>
		  	<a href="/sis/studios/ph/?page=store-home" style="color: #fff"><button type="button" class="btn btn-primary">Go to Home Page</button></a>
		  </div>
	  </div>
	</div>
</div>