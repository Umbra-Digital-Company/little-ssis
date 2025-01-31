<?php
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

	require $sDocRoot."/includes/connect.php";

	$arrFrames = array();

	$query = 	"SHOW FULL PROCESSLIST;"; 

	$result = $conn->query($query);

	if ($result->num_rows > 0) {
	  // output data of each row
		
	  while($row = $result->fetch_assoc()) {
	  echo'<pre>';
	   print_r($row);
	  }
	} else {
	  echo "0 results";
	}

?>