<?php 

if(!isset($_SESSION)){

  session_start();

};

$arrCart = array();
$order_no_Cart = "";



if(isset($_GET['orderNo'])){
	//echo "a";
	$order_no_Cart =$_GET["orderNo"];
}
elseif(isset($_POST['order_id'])){
	//echo "k";
	$order_no_Cart =$_POST['order_id'];
}
elseif(isset($_SESSION["order_no"])){
	//echo "b";
	$order_no_Cart=$_SESSION["order_no"];

}
elseif(isset($_GET['load'])){
	//echo "c";
	$order_no_Cart=$grabOrderNo;

}
elseif(isset($_POST['orderNo'])){
	//echo "d";
	$order_no_Cart =$_POST['orderNo'];
	
}
else{

	$order_no_Cart="x";

};
  $queryCart = 	"SELECT  
									p.first_name, 
									p.last_name,
									os.product_code,
									os.prescription_id,
									os.order_id,
									u.first_name AS uf,
									u.middle_name AS um,
									u.last_name AS ul,
									LOWER(REPLACE(pr.item_name,  TRIM(LEFT(pr.item_name , LOCATE(' ', pr.item_name) - 1)), '')),
									p.profile_id,
									os.status,
									LOWER(TRIM(LEFT(pr.item_name , LOCATE(' ', pr.item_name) - 1))),
									os.lens_option,
									os.reason,
									lab_name,
									os.lab_print,
									os.lab_production,
									os.lab_status,
									os.received_stat,
									os.store_dispatch,
									os.signature,
									os.payment,
									os.lab_print_date,
									os.lab_production_date,
									os.lab_status_date,
									os.received_stat_date,
									os.payment_date,
									IF(
										pr.price IS NOT NULL,
										pr.price,
										0
									),
									os.id,
									os.product_upgrade,
									os.prescription_vision,
									os.prescription_id,
									o.doctor,
									o.sales_person,
									store_id,
									p.phone_number,
									p.middle_name,
									os.po_number,
									o.laboratory,
									os.lens_code,
									os.tints,
									os.price as osprice,
									os.orders_specs_id
								FROM 
									profiles_info p
										LEFT JOIN orders_specs os 
											ON os.profile_id = p.profile_id
										LEFT JOIN poll_51 pr 
											ON pr.product_code = os.product_code  
										LEFT  JOIN orders o 
											ON o.order_id = os.order_id 
										LEFT JOIN emp_table u 
											ON u.emp_id= o.doctor
										LEFT JOIN labs_locations ll 
											ON ll.lab_id = o.laboratory
								WHERE   
									o.order_id = '".$order_no_Cart."'
									and os.status!='cancelled'
								ORDER by os.status_date ASC";

$grabParams = array(

  	'first_name',
  	'last_name',
  	'product_code',
  	'prescription_id',
 	'order_id',
  	'uf',
	'um',
	'ul',
	'color',
	'profile_id',
	'status',
	'item_description',
	'lens_option',
	'reason',
	'lab_name',
	'lab_print',
	'lab_production',
	'lab_status',
	'received_stat',
	'store_dispatch',
	'signature',
	'payment',
	'lab_print_date',
	'lab_production_date',
	'lab_status_date',
	'received_stat_date',
	'payment_date',
	'price',
	'id',
	"product_upgrade",
	"prescription_vision",
	"prescription_id",
	"doctor",
	"sales_person",
	"store_id",
	"phone_number",
	"middle_name",
	"po_number",
	"laboratory",
	"lens_code",
	"tints",
	"osprice",
	"orders_specs_id"

);
 
$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryCart)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9, $result10, $result11, $result12, $result13, $result14, $result15, $result16, $result17, $result18, $result19,$result20,$result21,$result22,$result23,$result24,$result25,$result26,$result27,$result28,$result29,$result30,$result31,$result32,$result33,$result34,$result35,$result36,$result37,$result38,$result39,$result40,$result41,$result42,$result43 );

  while (mysqli_stmt_fetch($stmt)) {

    $tempArray = array();

    for ($i=0; $i < sizeOf($grabParams); $i++) { 

      $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

    };

    $arrCart [] = $tempArray;

  };

  mysqli_stmt_close($stmt);    
                            
}
else {

  echo mysqli_error($conn);

}; 

$arrTotal = array();

$queLabLoc = 	"SELECT 
					sum(price) AS total 
				FROM 
					orders_specs o 
				WHERE 
					order_id = '".$order_no_Cart."' and o.status!='cancelled'
				";

$grabParams = array("total");

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queLabLoc)) {

  mysqli_stmt_execute($stmt);
  mysqli_stmt_bind_result($stmt, $result1);

  while (mysqli_stmt_fetch($stmt)) {

    $tempArray = array();

    for ($i=0; $i < sizeOf($grabParams); $i++) { 

      $tempArray[$grabParams[$i]] = $result1;

    };

    $arrTotal[] = $tempArray;

  };

  mysqli_stmt_close($stmt);    
                            
}
else {

  echo mysqli_error($conn);

}; 


?>