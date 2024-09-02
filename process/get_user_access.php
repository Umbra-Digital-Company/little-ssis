<?php 

function setUserAccess($username,$conn){

	//Get UserACess
	$user_access = getUserAccess($username,$conn);

	if($user_access['rows']<1){
		// INSERT data in user_access if current user does not have data yet

		$sql = "INSERT INTO user_access_v2 (
									username,
									philippines0dashboard
								) 
							VALUES(?,?)
							";

							if(!$stmt = $conn->prepare($sql)){
							  echo $conn->error;
							}

							$insert = [];
							$insert[] = $username;
							$insert[] = 1;

							$stmt->bind_param("ss", ...$insert);

							if(!$stmt->execute()){
							  printf("Error: %s.\n", $stmt->error);
							}

		$user_access = getUserAccess($username,$conn);

	}//END:: IF


	if(session_id() == ''){
	    //session has not started
	    session_start();
	}

	$_SESSION['user_access'] = $user_access['data'];
	// print_r($_SESSION);
}//END:: getUserAccess

function getUserAccess($username,$conn){

	//Update users table
	$sql = "SELECT * FROM user_access_v2 WHERE username = ? LIMIT 1";

	if(!$stmt = $conn->prepare($sql)){
		echo $conn->error;
	}

	$stmt->bind_param('s',$username);
	
	if(!$stmt->execute()){
		printf("Error: %s.\n", $stmt->error);
	}

	$result = $stmt->get_result();

  	$data = [];
  	while($row = $result->fetch_array(MYSQLI_ASSOC)){
	    $temp = [];
	    foreach ($row as $key => $value) {
	       $temp[$key] = htmlentities($value);
	    }
	    $data[] = $temp;
	}

	$user_access=[];
	foreach ($data as $value) {
		foreach ($value as $key => $value2) {
			if($key!='id' AND $key!='username' AND $key!='default_page' AND $key!='default_page_studios' AND $key!='default_page_face' AND $key!='default_page_cup_point'){
				$temp_key = explode('0', $key);
				if($value2!=0){
					$user_access[$temp_key[1]][$temp_key[0]] = $value2;
				}
			}
		}
	}

	$return=[];
	$return['rows'] = count($data);
	$return['data'] = $user_access;

	return $return;

}

// getUserAccess($user_id=179, $conn);

?>