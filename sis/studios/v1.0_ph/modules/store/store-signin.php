<style>
	
	.store-signin .btn {
		min-width: 100px;
	}

	.store-signin {
		position: absolute;
		width: 100%;
		top: 50%;
		left: 0;
		-webkit-transform: translateY(-50%);
		-moz-transform: translateY(-50%);
		-ms-transform: translateY(-50%);
		-o-transform: translateY(-50%);
		transform: translateY(-50%);
	}

</style>


	<form method="post" id="login_form" name="login_form">


	<div class="row">
		<div class="col-12 col-sm-6 push-sm-3">
			<div class="form-group">
				<label class="font-weight-bold" for="email">Email address:</label>
				<input type="email" name="email" class="form-control" id="email" required>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="password">Password:</label>
				<input type="password" class="form-control" name="password" id="password" required>
			</div>
		</div>
	</div>

	<div class="row justify-content-center align-items-center">
		
		  <input type="button" name="btnsubmit" id="btnsubmit" value="Login" class="log-btn btn btn-primary" />
	</div>
	<div id="msg"></div> 
</form> 
<script type="text/javascript">
$(document).ready(function(){
	
	$(".form-control").on('keyup', function (e) {
    if (e.keyCode == 13) {
      
		
		
		$.post("modules/store/store-sign-master.php",$("form#login_form").serialize(),function(d){
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	
					window.location="./?page=home";
					
				} else {
						
					$("#msg").html(d);
					
				}
		
		});
    }
});

	$("#btnsubmit").click(function(){
		
	
		$.post("modules/store/store-sign-master.php",$("form#login_form").serialize(),function(d){
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	 
					window.location="./?page=home";
					
				} else {
						
					$("#msg").html(d);
					
				}
			});
	});
	

});
</script>