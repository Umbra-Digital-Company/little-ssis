<?php
	$data = [];
	$invalid = false;
	$invalid_message = '';
	$arrEmpId = [];
	foreach($_FILES as $file) {
	  $content = file($file['tmp_name']);
	  $row = 1;
	  foreach($content as $line){

	    $data_row = str_getcsv($line);
	    if($row ==1){
	    	if(trim(strtolower($data_row[0])) != 'product code' || trim(strtolower($data_row[1])) != 'transferred count' || trim(strtolower($data_row[2])) != 'received count'){
	    	$invalid = true;
	    	$invalid_message = "Invalid content header";
	      	break;
	    	}
	    }else{
	      if(count($data_row) > 3){
	      	$invalid = true;
	      	$invalid_message = "Invalid content column count";
	      	break;
	      }
	      elseif(!is_numeric(trim($data_row[1]))){
	      	if($invalid == false){
		      	$invalid = true;
		      	$invalid_message = "Invalid trasferred count on line ".$row;
		    }else{
			    $invalid_message .=", ".$row;
			}
	      }
	      elseif(!is_numeric(trim($data_row[2]))){
	      	if($invalid == false){
		      	$invalid = true;
		      	$invalid_message = "Invalid received count on line ".$row;
		    }else{
			    $invalid_message .=", ".$row;
			}
	      }
	      if(!array_filter($data_row)) break;
	       	$arrData ['product_code'] = trim($data_row[0]);
        	$arrData ['transferred_count'] = trim($data_row[1]);
        	$arrData ['received_count'] = trim($data_row[2]);
        	$data[] = $arrData;
	    }
	    $row++;
	  }
	}
	$json = [];
	$json['invalid_message'] = ($invalid) ? $invalid_message."." : false;
	$json['data'] = $data;
	echo json_encode($json);
?>