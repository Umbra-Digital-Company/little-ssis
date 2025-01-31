<?php 

//print_r($_GET);
?>
<div class="text-center small row no-gutters align-items-center justify-content-center">
	<p>Switch to this account?</p>
	<div class="row no-gutters align-items-center justify-content-center">
		<a href="./modules/process/switch_account.php?id=<?= $_GET['id'] ?>"><button type="button" class="btn ssis-btn-primary">YES</button></a>
		<span style="margin: 0 10px;">or</span>
		<button id="no_<?= $_GET['id'] ?>" type="button" class="btn ssis-btn-secondary">NO</button>	
	</div>
</div>
