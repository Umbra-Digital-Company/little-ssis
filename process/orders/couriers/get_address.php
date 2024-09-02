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
                    DISTINCT
                    email_address,
                    phone_number,
                    country,
                    province,
                    city,
                    barangay,
                    address1,
                    address2,
                    zip_code
                  FROM profiles_shipping_address WHERE profile_id = '".$profile_id."';";

    $grabParams = array(
      'email_address',
      'phone_number',
      'country',
      'province',
      'city',
      'barangay',
      'address1',
      'address2',
      'zip_code'
    );
      
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $query)) {

      mysqli_stmt_execute($stmt);
      mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

      $countValue = 0;
      while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

          $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };
        $tempArray['count'] = $countValue;
        $tempArray['phone_number'] = str_replace('-', '',$tempArray['phone_number']);
        $arrProfileInfo[] = $tempArray;
        $countValue++;
      };

      mysqli_stmt_close($stmt);    
                  
    }
    else {

      echo mysqli_error($conn); 

    };

    return $arrProfileInfo;
  }

  echo json_encode(getProfileInfo($_GET['profile_id']));

?>