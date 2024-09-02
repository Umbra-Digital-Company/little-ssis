<?php   

session_save_path("/home/ssolutions/public_html/site/7747/cgi-bin/tmp");
session_start();

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if ( !isset($_SESSION) ) {
    
    session_start();

}
	
?>

	<!-- fonts link -->

	<!-- css files -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="css/select2.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/material-design-iconic-font.min.css">

	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

    <!--[if lt IE 9]>
        <script src="https://cdn.jsdelivr.net/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://cdn.jsdelivr.net/respond/1.4.2/respond.min.js"></script>
    <![endif]-->


<style>

.login-bg {
        background: url("images/store-banner.jpg");
        background-repeat: no-repeat;
       	background-size: cover;
       	background-position: center;
       	min-height: 100vh;
    }

	.admin-form {
		background: rgba(255,255,255,.8);
		position: fixed;
		top: 50%;
		left: 50%;
		width: 100%;
		max-width: 480px;
		-webkit-transform: translate(-50%, -50%);
		-moz-transform: translate(-50%, -50%);
		-ms-transform: translate(-50%, -50%);
		-o-transform: translate(-50%, -50%);
		transform: translate(-50%, -50%);
	}

	.admin-form form {
		margin-top: 30px;
	}

	.admin-form form .form-group {
		position: relative;
	}

	#msg {
		margin-bottom: 15px;
		color: tomato;
	}

</style>

<body id="ssis-admin-login"> 
<div class="login-bg">	
	
	<div class="admin-form jumbotron">
		<center>
			
			<img src="images/sunnies-specs-logo-b.png" class="img-responsive login-img" />
	 		
	 		<form method="post" id="login_form" name="login_form">

	     		<div class="form-group">
	     			<input type="text"  class="form-control" placeholder="Username " id="UserName" name="user"/>
					<i class="fa fa-user"></i>
	     		</div>

	     		<div class="form-group">
	       			<input type="password" class="form-control" placeholder="Password" id="Password" name="pass">
	       			<i class="fa fa-lock"></i>
	     		</div>
	     
	     		<div id="msg"></div> 
		        <input type="button" name="btnsubmit" id="btnsubmit" value="Login" class="log-btn btn btn-primary" />

	     	</form>
	  	</center>
	</div>

</div>

<script src="js/jquery-3.2.1.min.js"></script>
<script src="js/tether.min.js"></script>
<script src="js/bootstrap.min.js"></script>

<script type="text/javascript">
$(document).ready(function(){
	
	$(".form-control").on('keyup', function (e) {
    if (e.keyCode == 13) {
       
		
		
		$.post("process/loginConfirm.php",$("form#login_form").serialize(),function(d){
			
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	
				location.reload();
				} else {
						
					$("#msg").html(d);
					
				}
		
		});
    }
});

	$("#btnsubmit").click(function(){
		
	
		$.post("process/loginConfirm.php",$("form#login_form").serialize(),function(d){
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	
					location.reload();
					
				} else {
						
					$("#msg").html(d);
					
				}
			});
	});
	

});
</script>


</body>
</html>