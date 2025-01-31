<?php $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	

	// $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();
	
	require $sDocRoot."/includes/connect.php";
	require $sDocRoot."/includes/admin/grab_emp.php";
?>

<table class="table table-hover table-striped table-bordered">
	<thead>
		<tr class="head">
			<th>NAME</th>
		</tr>
	</thead>
	<tbody>
		<?php for($i=0;$i<sizeof($arrEmp);$i++){ ?>
				<tr class="body">
						<td nowrap class="cell100 column1">
							<a href="details/?profile_id=<?= $arrEmp[$i]["emp_no"] ?>"><?php echo $arrEmp[$i]["last_name"]." ".$arrEmp[$i]["first_name"] ?></a> </td>
				</tr>
		<?php } ?>
	</tbody>
</table>