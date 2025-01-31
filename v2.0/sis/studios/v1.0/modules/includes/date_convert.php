<?php
	function cvdate3($d){
      $returner = '';
      $datae=date_parse($d);
      $returner .= getMonth3($datae['month'])." ".$datae['day'].", ".$datae['year'];
      $suffix = "AM";
      $hour = $datae['hour'];
      if ($datae['hour']>'12') {
        $hour = $datae['hour']-12;
      }
      if ($datae['hour']>'11' && $datae['hour']<'24') {
        $suffix = "PM";
      }
      $returner .= " ".AddZero3($hour).":".AddZero3($datae['minute']).":".AddZero3($datae['second'])." ".$suffix;
      return $returner;
    }

    function dateConvert($d){
      $returner = '';
      $datae=date_parse($d);
      $returner .= getMonth3($datae['month'])." ".$datae['day'].", ".$datae['year'];
      return $returner;
    }

    function getMonth3($mid){
      switch($mid){
        case '1': return "Jan"; break;
        case '2': return "Feb"; break;
        case '3': return "Mar"; break;
        case '4': return "Apr"; break;
        case '5': return "May"; break;
        case '6': return "Jun"; break;
        case '7': return "Jul"; break;
        case '8': return "Aug"; break;
        case '9': return "Sep"; break;
        case '10': return "Oct"; break;
        case '11': return "Nov"; break;
        case '12': return "Dec"; break;

      }
    }

    function timeConvert($d){
      $returner = '';
      $time = explode(':', $d);
      $suffix = "AM";
      $hour = $time[0];
      if ($hour > 12) {
          $hour = $hour-12;
      }
      if ($time[0] > 11 && $time[0] < 24) {
          $suffix = "PM";
      }
      $returner .= " ".AddZero3($hour).":".AddZero3($time[1]).":".AddZero3($time[2])." ".$suffix;
      return $returner;
  	}

    function AddZero3($num){
      if (strlen($num)=='1') {
        return "0".$num;
      } else {
        return $num;
      }
    }
?>