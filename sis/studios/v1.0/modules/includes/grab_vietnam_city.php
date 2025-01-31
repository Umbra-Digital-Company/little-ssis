<?php 
$conn->set_charset( "utf8" );
$arrVietnam = [];
  $queryCart = 	"SELECT  country, country_code, city
  					FROM 
					 countries_checkout_vietnam
					 ORDER BY city
					";

$grabParams = array(
	'country',
	'country_code',
	'city'

);
 
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryCart)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1, $result2, $result3);

  while (mysqli_stmt_fetch($stmt)) {

    $tempArray = array();

    for ($i=0; $i < sizeOf($grabParams); $i++) { 

      $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

    };

    $arrVietnam[] = $tempArray;

  };

  mysqli_stmt_close($stmt);    
                            
}
else {

  echo mysqli_error($conn);

}; 


//print_r($arrVietnam); exit;

?>