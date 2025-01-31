
<?php
if ( !isset($_SESSION) ) { session_start(); }
include("connect.php");
$arrOnline = array();



$query="SELECT 

										s.username,

										s.first_name,
										
										s.middle_name,
										
										s.last_name,
										
										s.id ,
										
										s.isadmin,
										
										s.position,
										
										s.store_location,
										
										s.store_code,
										
										s.s_pass,
										
										s.password,
										
										date_log

									FROM 
									
										sunniess_specs.users s

									WHERE online='1'
											and position='".$_SESSION['position']."'
									 ";

$grabParams = array('username','first_name','middle_name','last_name','id' ,'isadmin','position','store_location','store_code','s_pass','password','date_log');


$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 12; $i++) { 

            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $arrOnline [] = $tempArray;

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
	}
	if ($datae['hour']>'11' && $datae['hour']<'24') {
		$suffix = "PM";
	}
	$returner .= " at ".AddZero($hour).":".AddZero($datae['minute']).":".AddZero($datae['second'])." ".$suffix."<br>";	
	return $returner;
}

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
		
	}
}

function AddZero($num){
	if (strlen($num)=='1') {
		return "0".$num;
	} else {
		return $num;
	}
}
?>

<div class="modal-header">
	<h5 class="modal-title">Active Account</h5>
	<a class="text-danger text-uppercase font-weight-bold" href="./?page=logout">Logout</a>
</div>

<div class="modal-body" style="padding: 0">
	<div class="accts-in">

		<?php 
		//$new_time = date("Y-m-d H:i:s", strtotime('+5 hours')).
		for($i=0;$i<sizeof($arrOnline);$i++){
	
		if(date('Y-m-d H:i:s') >	date('Y-m-d H:i:s', strtotime('+8 hours',strtotime($arrOnline[$i]["date_log"])))    ){
			
			  $queryLogInactive="UPDATE sunniess_specs.users SET
						online='0',
						date_log=now()
					where
						id='".$arrOnline[$i]["id"]."'
					";
		
			$stmt = mysqli_stmt_init($conn);

			if (mysqli_stmt_prepare($stmt, $queryLogInactive)) {
				mysqli_stmt_execute($stmt);		
			}
			
		}
		
		?>
			<div class="acct-list">
				<div class="acct-details" data-user-id="<?= $arrOnline[$i]["id"] ?>">
					<div class="row no-gutters align-items-center justify-content-between">
						<div class="row no-gutters align-items-center">
							<div class="acct-icon <?php if($_SESSION["id"]==$arrOnline[$i]["id"]){ ?>signed-in<?php } ?>">
								<i class="zmdi zmdi-account zmdi-hc-2x"></i>
							</div>
							<div>
								<p class="font-weight-bold"><?= ucwords( $arrOnline[$i]["first_name"]." ".$arrOnline[$i]["last_name"] ); ?></p>
								<span class="small"><?php echo  ucwords($arrOnline[$i]["position"]); ?> Signed in</span>
							</div>
						</div>
						<span class="small" style="display: block;"><?= cvdate($arrOnline[$i]["date_log"]) ?></span>
					</div>
				</div>
				<div class="switchAcc" id="switch_account_<?= $arrOnline[$i]["id"] ?>">
					<!-- load switch account confirmation here -->
				</div>
			</div>

		<?php } ?>


	</div>
	<div class="acct-new text-center">
		<form method="post" id="login_form" name="login_form">

				<div class="form-group">
					<input type="text"  class="form-control" placeholder="Username " id="UserName" name="user"/>
				</div>
				<div class="form-group">
					<input type="password" class="form-control" placeholder="Password" id="Password" name="pass">
				</div>
				<input type="button" name="btnsubmit" id="btnsubmit" value="signin" class="log-btn btn ssis-btn-primary" />
				<button type="button" class="btn ssis-btn-secondary" id="cancelSign">cancel</button>
		</form>
		<div id="msg"></div> 
	</div>
</div>

<div class="modal-footer">
	<button type="button" class="btn ssis-btn-primary" id="signInNew">Sign  - In</button>
	<button type="button" class="btn ssis-btn-secondary" data-dismiss="modal">close</button>
</div>

<script type="text/javascript">
$(document).ready(function(){
	
	
	$('.acct-details').click(function(){
		var id=	$(this).attr('data-user-id');
		$('#switch_account_' + id).load('./modules/includes/switch_account.php?id='+ id, function() {
			// remove other tabs
			$('#no_' + id).on('click', function() {
				$('#switch_account_' + id).removeClass('show');
			});
		}).addClass('show');
		$(this).parent().siblings().find('.switchAcc').removeClass('show');
	});
	
	
	
	$(".form-control").on('keyup', function (e) {
    if (e.keyCode == 13) {
       
		
		
		$.post("./modules/process/add_user.php",$("form#login_form").serialize(),function(d){
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	
					window.location.reload();
					
				} else {
						
					$("#msg").html(d);
					
				}
		
		});
    }
});

	$("#btnsubmit").click(function(){
		
	
		$.post("./modules/process/add_user.php",$("form#login_form").serialize(),function(d){
			
				$('#msg').html("");
					
				if (d.substring(0,7)=='success') {
	
					window.location.reload();
					
				} else {
						
					$("#msg").html(d);
					
				}
			});
	});
	

});
</script>
