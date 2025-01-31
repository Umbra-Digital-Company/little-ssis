<?php

// ======================================= ORDER MANAGEMENT - SEND TO CASHIER
function ShowArray($array){

	echo "<pre>";
		print_r($array);
	echo "</pre>";
};


function CheckTempoUrl($po_number){

	global $conn;

	$arrUrl=array();

	$query = "SELECT `url`, `synch` FROM temp_pos_url
        WHERE `url` like '%".$po_number."%'
	";
		
	$grabParams = array("url","synch");
	$stmt = mysqli_stmt_init($conn);
	
	if ( mysqli_stmt_prepare( $stmt, $query ) ) {

		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $result1, $result2 );

    	while (mysqli_stmt_fetch($stmt)) {

        	$tempArray = array();

        	for ( $i=0; $i < sizeOf($grabParams); $i++ ) { 
            	$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};
        	};
			$arrUrl[] = $tempArray;
    	};

    	mysqli_stmt_close($stmt);    
                            
	} else {

    	echo mysqli_error($conn);
	}; 

	return  $arrUrl;

}

// ======================================= DATE FORMAT

function cvdate($type, $d){
	$returner = '';
	$datae=date_parse($d);

	if ( $type == 1 ) {
		$dayFormat = ( strlen($datae['day'])=='1' ) ? "0".$datae['day'] : $datae['day'];
		$returner .= getMonth($type, $datae['month'])."/".$dayFormat."/".$datae['year'];
	} elseif ( $type == 2 ) {
		$returner .= getMonth($type, $datae['month'])." ".$datae['day'].", ".$datae['year'];
	} elseif ( $type == 2 ) {
		$returner .= getMonth($type, $datae['month'])." ".$datae['day'].", ".$datae['year'];
	}
	
	$suffix = "AM";
	$hour = $datae['hour'];

	if ($datae['hour']>'12') {
		$hour = $datae['hour']-12;
	}

	if ($datae['hour']>'11' && $datae['hour']<'24') {
		$suffix = "PM";
	}

	if ( $type == 1 ) {
		$returner .= " - ".AddZero($hour).":".AddZero($datae['minute'])." ".$suffix;
	} elseif ( $type == 2 ) {
		$returner .= " ";
	} elseif ( $type == 2 ) {
		$returner .= " at ".AddZero($hour).":".AddZero($datae['minute']).":".AddZero($datae['second'])." ".$suffix;
	}

	return $returner;
};

function getMonth($type, $mid){
	switch ( $type ) {
		case 1 : 
			$m1 = "01";$m2 = "02";$m3 = "03";$m4 = "04";$m5 = "05";$m6 = "06";$m7 = "07";$m8 = "08";$m9 = "09";$m10 = "10";$m11 = "11";$m12 = "12";
			break;
		case 2 :
			$m1 = "January";$m2 = "February";$m3 = "March";$m4 = "April";$m5 = "May";$m6 = "June";$m7 = "July";$m8 = "August";$m9 = "September";$m10 = "October";$m11 = "November";$m12 = "December";
			break;
		case 3 :
			$m1 = "Jan";$m2 = "Feb";$m3 = "Mar";$m4 = "Apr";$m5 = "May";$m6 = "Jun";$m7 = "Jul";$m8 = "Aug";$m9 = "Sep";$m10 = "Oct";$m11 = "Nov";$m12 = "Dec";
	}
	switch($mid){
		case '1': return $m1; break;
		case '2': return $m2; break;
		case '3': return $m3; break;
		case '4': return $m4; break;
		case '5': return $m5; break;
		case '6': return $m6; break;
		case '7': return $m7; break;
		case '8': return $m8; break;
		case '9': return $m9; break;
		case '10': return $m10; break;
		case '11': return $m11; break;
		case '12': return $m12; break;
	}
};

function AddZero($num){
	if (strlen($num)=='1') {
		return "0".$num;
	} else {
		return $num;
	}
}

// ======================================= GREETINGS

function greetings() {

	if ( date('G') < 11 ) {
		$greetings = 'Good Morning!';
	} elseif ( date('G') < 18 ) {
		$greetings = 'Good Afternoon!';
	} elseif ( date('G') < 24 ) {
		$greetings = 'Good Evening!';
	}

	return $greetings;

}

// ======================================= ASSETS PATH

function get_url($url) {
	return '/sis/studios/assets/' . $url;
}

// ======================================= FORMAT NUMBER

function trimTrailingZeroes($nbr) {
    return strpos($nbr,'.')!==false ? rtrim(rtrim($nbr,'0'),'.') : $nbr;
}


?>