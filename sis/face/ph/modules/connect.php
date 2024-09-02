<?php

if ( !isset($_SESSION) ) {
	session_start();
}



define('DB_SERVER', "localhost");
define('DB_USER', "root");
define('DB_PASSWORD', "2FC4587532A018D9816914D5EB05AA49E23A7808");
define('DB_TABLE', "ssolutio_ssis");

$conn 	= new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_TABLE);

// add another way of connect
$conn2 	= mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_TABLE);
mysqli_set_charset($conn2, 'utf8');

//define('DB_SERVER', "198.1.99.223:2083");
//
//define('DB_USER', "sunniesstudios");
//
//define('DB_PASSWORD', "58XDD7OPY5G3TWQSRXO");
//
//define('DB_TABLE', "sunniess_specs");
//
//$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_TABLE);

if (mysqli_ping($conn)){
}
else{
	
//	
//define('DB_SERVER', "localhost");
//
//define('DB_USER', "root");
//
//define('DB_PASSWORD', "");
//
//define('DB_TABLE', "sunniess_specs");
//
//$conn = new mysqli(DB_SERVER, DB_USER, DB_PASSWORD, DB_TABLE);

}


// $storeSetup =array();

// $querysetupstore="SELECT store_code,lab_id FROM store_setup";


// $grabParams = array(
// 	'store_code','lab_id' );
	
// $stmtstoresetup = mysqli_stmt_init($conn);
// if (mysqli_stmt_prepare($stmtstoresetup, $querysetupstore)) {
//     mysqli_stmt_execute($stmtstoresetup);
//     mysqli_stmt_bind_result($stmtstoresetup, $result1, $result2);

//     while (mysqli_stmt_fetch($stmtstoresetup)) {

//         $tempArray = array();

//         for ($i=0; $i < 2; $i++) { 

//             $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

//         };

//         $storeSetup[] = $tempArray;

//     };

//     mysqli_stmt_close($stmtstoresetup);    
                            
// }


// $_SESSION["store_code"]	= $storeSetup[0]["store_code"];
// $_SESSION["lab_code_pos"] = $storeSetup[0]["lab_id"];
// ?>