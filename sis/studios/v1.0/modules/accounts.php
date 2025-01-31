<?php

if ( !isset($_SESSION) ) {
	session_start();
}

include("connect.php");
include("functions.php");

// Set Query - Get List of online Accounts
$arrOnline = array();
$query="SELECT 
		s.username,
		LOWER(s.first_name),		
		LOWER(s.middle_name),		
		LOWER(s.last_name),		
		s.id ,		
		s.isadmin,		
		s.position,		
		s.store_location,		
		s.store_code,	
		s.password,		
		s.date_log
	FROM 	
		users s
	
	WHERE 
		s.position='".$_SESSION['position']."'";

// Initialize Array
$grabParams = array('username','first_name','middle_name','last_name','id' ,'isadmin','position','store_location','store_code','password','date_log');

// Start Connection
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $query)) {
    mysqli_stmt_execute($stmt);
    // Set Fields in each variable
    mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11);
    // loop through query
    while (mysqli_stmt_fetch($stmt)) {
        $tempArray = array();
        // loop
        for ($i=0; $i < 11; $i++) { 
            $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
        };
        // put inside array
        $arrOnline [] = $tempArray;
    };
    // Close connection
    mysqli_stmt_close($stmt);                            
}
else {

    echo mysqli_error($conn);

}

?>

<div class="switch-account-overlay">
	<div class="overlay-title">
		<div class="d-flex align-items-center">
			<span class="close-overlay" data-reload="no" data-sidebar="yes"><img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="close" class="img-fluid"></span>
			<p class="h2">Switch Account</p>
			
		</div>
	</div>

	<div class="overlay-body">
		<div class="accts-in">
			
			<?php for ( $i=0; $i < sizeof($arrOnline); $i++ ) : ?>

				<div class="acct-list list-item account d-flex no-gutters align-items-center justify-content-between">
					<?php $link = ( $_SESSION["id"] == $arrOnline[$i]["id"] ) ? '#' : 'modules/process/switch_account.php?id='.$arrOnline[$i]["id"] ?>
					<a href="<?= $link ?>" class="col">
						<div class="d-flex align-items-center">
							<span class="acct-icon mr-2">
								<img src="<?= get_url('images/icons') ?>/icon-account-<?= ($_SESSION['userlvl']=='1') ? 'doctor' : 'assistant' ?>.png" alt="account" class="img-fluid">
							</span>
							<div>
								<p class="font-weight-bold"><?= ucwords( $arrOnline[$i]["first_name"]." ".$arrOnline[$i]["last_name"] ); ?></p>
								<span class="small text-secondary"><?= ( $_SESSION["id"] == $arrOnline[$i]["id"] ) ? 'Signed in' : 'Last login: ' . cvdate(2,$arrOnline[$i]["date_log"]) ?></span>
							</div>
						</div>
					</a>
					<?php if ( $arrOnline[$i]['id'] == $_SESSION['id'] ) : ?>
					<a class="d-flex align-items-center" href="./?page=logout2">
							<span class="h3 ml-3">Logout</span>
					</a>
					<?php endif ?>

					<?php if ( $arrOnline[$i]['id'] != $_SESSION['id'] ) : ?>

						<a href="#remove_<?= $arrOnline[$i]["id"] ?>" class="btn_remove_account"><img src="<?= get_url('images/icons') ?>/icon-delete.png" alt="delete" class="img-fluid"></a>

					<?php endif ?>

				</div>
				<div class="text-center mt-5 form-group remove_account_confirmation" id="remove_<?= $arrOnline[$i]["id"] ?>">
					<p>Are you sure you want to remove<br/>this account?</p>
					<div class="mt-3 d-flex align-items-center justify-content-center">
						<a href="./?page=logout2&acct=<?= $arrOnline[$i]["id"] ?>" class="btn btn-link text-danger">yes</a>
						<a href="#" class="btn btn-link text-dark cancel_remove">no</a>
					</div>
				</div>

			<?php endfor ?>

			<div class="list-item account d-flex no-gutters align-items-center justify-content-between mb-4" id="add_account">
				<p class="font-bold">Use Different Account</p>
				<span class="btn_add_account">
					<img src="<?= get_url('images/icons') ?>/icon-add-<?= ($_SESSION['userlvl']=='1') ? 'theme-doctor' : 'primary' ?>.png" alt="delete" class="img-fluid">
				</span>
			</div>

		</div>

		<form method="POST" id="add_account_login" name="add_account_login" class="acct-new text-center mt-4" style="display:none">
			<div class="form-group">
				<input type="text"  class="form-control" id="UserName" name="user" required="required" />
				<label for="UserName" class="placeholder">Username</label>
			</div>
			<div class="form-group">
				<input type="password" class="form-control toggle-password" id="Password" name="pass" required="required" />
				<label for="Password" class="placeholder">Password</label>
				<input type="checkbox" class="sr-only" id="toggle_password">
				<label for="toggle_password"></label>
			</div>
			<div class="d-flex justify-content-end">
				<button type="button" class="btn text-secondary btn-link" id="cancelSign">cancel</button>
				<input type="button" name="btnsubmit" id="btnsubmit" value="Log In" class="btn btn-primary" />
			</div>
			<div id="msg" class="text-center mt-3 text-danger"></div> 
		</form>

	</div>
</div>