<?php
	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
  	session_start();

  $sDocRoot = $_SERVER["DOCUMENT_ROOT"];

  // Required includes
  require $sDocRoot."/includes/connect.php";

  function getStoreInfo(){
    global $conn;
    $arrProfileInfo = array();

    $query =    "SELECT
                    store_name_proper,
                    email_address,
                    phone_number,
                    country,
                    province,
                    city,
                    barangay,
                    address
                  FROM stores_locations WHERE store_id = '".$_SESSION['user_login']['store_code']."';";

    $grabParams = array(
      'store_name',
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
      mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8);

      $countValue = 0;
      while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < sizeOf($grabParams); $i++) { 

          $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

        };
        $tempArray['phone_number'] = str_replace('-', '',$tempArray['phone_number']);
        $arrProfileInfo[] = $tempArray;
        $countValue++;
      };

      mysqli_stmt_close($stmt);    
                  
    }
    else {

      echo mysqli_error($conn); 

    };

    return $arrProfileInfo[0];
  }

  echo json_encode(getStoreInfo());

?>