<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

if(!isset($_SESSION)){
        session_start();
    }
include("connect.php");
//Required files
//require $sDocRoot."/aaSunnies_Specs_shop/modules/connect.php";

$arrNoti = array();


 $querypno =" SELECT  
 

 count(os.order_id) count

 
FROM profiles_info p
LEFT JOIN orders_specs os on os.profile_id=p.profile_id
LEFT JOIN users u on u.id=p.sales_person
LEFT JOIN products pr on pr.product_code=os.product_code  
LEFT  JOIN orders o on o.order_id=os.order_id 
LEFT JOIN  store_codes sc on sc.location_code=o.store_id
LEFT JOIN labs_locations ll on ll.lab_id=o.laboratory
where 
os.payment='y' and os.store_dispatch='n'
 and os.product_upgrade!='fashion_lens'
 and os.status!='for exam'
 and os.status!='cancelled'
 and os.product_upgrade!='special_order'
and os.lab_print!='y'
AND o.laboratory = '".$_SESSION['store_code']."'
ORDER  BY o.date_created
";


 //$querypn .=" o.laboratory='".$_SESSION['store_code']."' ";

$grabParams2 = array(
    
   
   'count'
);



$stmt = mysqli_stmt_init($conn);
if (mysqli_stmt_prepare($stmt, $querypno)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $result1);

    while (mysqli_stmt_fetch($stmt)) {

        $tempArray = array();

        for ($i=0; $i < 1; $i++) { 

            $tempArray[$grabParams2[$i]] = ${'result' . ($i+1)};

        };

        $arrNoti[] = $tempArray;

    };

    mysqli_stmt_close($stmt);    
                            
}
else {

    echo mysqli_error($conn);

}; 



?>
<form name="form" id="Notific">
<input type="hidden" name="notify" id="notify" value="<?=  $arrNoti[0]["count"];?>">
</form>
<script type="text/javascript">

	$(document).ready(notifyMe);	
	checknotif();
	
function checknotif(){
	
	setTimeout(checknotif,890000000);
	var	d = $('#notify').val();	
	//$.post("../includes/notification_count.php",$("form#Notific").serialize(),function(d){


		if (d>0){
			
			notifyMe(d);
		}	
	
}
		
function notifyMe(d) {
	
  // Let's check if the browser supports notifications
  if (!("Notification" in window)) {
    alert("This browser does not support desktop notification");
  }

  // Let's check if the user is okay to get some notification
  else if (Notification.permission === "granted") {
	// If it's okay let's create a notification
	var notification = new Notification("Sunnies Specs Optical : ", {body: "You have a " + d + "  New Order/s", icon:"http://www.sunniesspecs.com/images/logos/logo-specs-optical-flat.png"});
   
  }

  // Otherwise, we need to ask the user for permission
  // Note, Chrome does not implement the permission static property
  // So we have to check for NOT 'denied' instead of 'default'
  else if (Notification.permission !== 'denied') {
    Notification.requestPermission(function (permission) {
		// Whatever the user answers, we make sure we store the information
      if(!('permission' in Notification)) {
        Notification.permission = permission;
      }

      // If the user is okay, let's create a notification
      if (permission === "granted") {
        var notification = new Notification("Sunnies Specs Optical : ", {body: "You have a " + d + "  New Order/s", icon:"http://www.sunniesspecs.com/images/logos/logo-specs-optical-flat.png"});
      }
    });
  }

  // At last, if the user already denied any notification, and you 
  // want to be respectful there is no need to bother him any more.
}
</script>