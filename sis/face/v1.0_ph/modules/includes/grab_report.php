<?php 
if(!isset($_SESSION)){
    session_start();
}
// echo "<pre>";
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

if(isset($_GET['date1'])){
$date1=$_GET['date1'];
}else{
    $date1=date('Y-m-d');
}

if(isset($_GET['date2'])){
    $date2=$_GET['date2'];
}
else{
    $date2=date('Y-m-d');
}
$arrReport=array();
  $queryReport = 	"SELECT 
 count(os.po_number),
 os.lens_option,
 os.product_code,
 sum(os.price),
 sum(p.total),
 p.payment_method,
 o.store_id
    FROM
        orders_specs os
    LEFT JOIN orders o on o.order_id=os.order_id
    LEFT JOIN payments p on p.po_number=os.po_number

WHERE 

    os.payment_date >='".$date1."' and os.payment_date<='".$date2."'
    and o.order_id like '".$_SESSION['store_code']."%'
    and lens_option='with prescription'
    and payment ='y'
UNION ALL 
SELECT 
 count(os.po_number),
 os.lens_option,
 os.product_code,
 sum(os.price),
 sum(p.total),
 p.payment_method,
 o.store_id
    FROM
        orders_specs os
    LEFT JOIN orders o on o.order_id=os.order_id
    LEFT JOIN payments p on p.po_number=os.po_number

WHERE 

    os.payment_date >='".$date1."' and os.payment_date<='".$date2."'
    and o.order_id like '".$_SESSION['store_code']."%'
    and lens_option='lens only'
    and payment ='y'
   
    UNION ALL 
SELECT 
 count(os.po_number),
 os.lens_option,
 os.product_code,
 sum(os.price),
 sum(p.total),
 p.payment_method,
 o.store_id
    FROM
        orders_specs os
    LEFT JOIN orders o on o.order_id=os.order_id
    LEFT JOIN payments p on p.po_number=os.po_number

WHERE 

    os.payment_date >='".$date1."' and os.payment_date<='".$date2."'
    and o.order_id like '".$_SESSION['store_code']."%'
    and lens_option='without prescription'
    and product_code!='M100'
    and product_code!='S100'
    and payment ='y'
    
  UNION ALL 
SELECT 
 count(os.po_number),
 os.lens_option,
 os.product_code,
 sum(os.price),
 sum(p.total),
 p.payment_method,
 o.store_id
    FROM
        orders_specs os
    LEFT JOIN orders o on o.order_id=os.order_id
    LEFT JOIN payments p on p.po_number=os.po_number

WHERE 

    os.payment_date >='".$date1."' and os.payment_date<='".$date2."'
    and o.order_id like '".$_SESSION['store_code']."%'
    and lens_option='without prescription'
    and product_code='M100'
    and payment ='y'
    UNION ALL 
SELECT 
 count(os.po_number),
 os.lens_option,
 os.product_code,
 sum(os.price),
 sum(p.total),
 p.payment_method,
 o.store_id
    FROM
        orders_specs os
    LEFT JOIN orders o on o.order_id=os.order_id
    LEFT JOIN payments p on p.po_number=os.po_number

WHERE 

    os.payment_date >='".$date1."' and os.payment_date<='".$date2."'
    and o.order_id like '".$_SESSION['store_code']."%'
    and lens_option='without prescription'
    and product_code='S100'
    and payment ='y'
    
    GROUP by p.payment_method
";



$grabParams = array(
    'po_count',
    'lens_option',
    'product_code',
    'price',
    'total',
    'payment_method',
    'store_id'

);

$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $queryReport)) {

mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7);

while (mysqli_stmt_fetch($stmt)) {

  $tempArray = array();

  for ($i=0; $i < sizeOf($grabParams); $i++) { 

    $tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

  };

  $arrReport [] = $tempArray;

};

mysqli_stmt_close($stmt);    
                          
}
else {

echo mysqli_error($conn);

}; 
?>