<?php
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
  	session_start();

  $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

  // Required includes
  require $sDocRoot."/includes/connect.php";

  function getProfileInfo($profile_id){
    global $conn;
    $arrProfileInfo = array();

    $query =    "SELECT
                    email_address,
                    phone_number,
                    country,
                    province,
                    city,
                    barangay,
                    address
                  FROM profiles_info WHERE profile_id = '".$profile_id."';";

    $grabParams = array(
      'email_address',
      'phone_number',
      'country',
      'province',
      'city',
      'barangay',
      'address'
    );
      
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

      while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

          $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };

        $tempArray['phone_number'] = str_replace('-', '',$tempArray['phone_number']);
        $arrProfileInfo[] = $tempArray;

      };

      mysqli_stmt_close($stmt);    
                  
    }
    else {

      echo mysqli_error($conn); 

    };

    return $arrProfileInfo[0];
  }

   echo json_encode(getProfileInfo($_GET['profile_id']));
	
?>