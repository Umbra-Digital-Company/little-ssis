<?php

	session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
	session_start();

	$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
	set_time_limit(0);
	ini_set('memory_limit', '3G');
	// Required includes
	require $sDocRoot."/includes/connect.php";
	require $sDocRoot."/inventory/includes/s_admin_function.php";
	//print_r($_GET); exit;
	$arrData = [];
	if(isset($_GET['filterStores'])){

	$product_code=str_replace("%20"," ",$_GET['product_code']);	

		 $storelength=strlen($_GET['filterStores']);
		if($_GET['filterStores']=='warehouse'){
			
			$branch ='warehouse';
			$arrData[] = WarehouseChecker_smr($product_code,$_GET['dateStart'],$_GET['dateEnd']);
			
		}elseif($storelength=='3' || $storelength=='7'){
			$branch ='store';
			$arrData[] = storeChecker_smr($product_code,$_GET['filterStores'],$_GET['dateStart'],$_GET['dateEnd']);
		}
		elseif($_GET['filterStores']=='1000'){
			$branch ='store';
			$arrData[] = storeChecker_smr($product_code,$_GET['filterStores'],$_GET['dateStart'],$_GET['dateEnd']);
		}
		else{
			
			$branch ='warehouse';
			$arrData[] = WarehouseChecker_smr($product_code,$_GET['dateStart'],$_GET['dateEnd']);
		}
	}else{
		
		$branch ='warehouse';
		$arrData[] = WarehouseChecker_smr($product_code,$_GET['dateStart'],$_GET['dateEnd']);
	}


	//print_r($arrData);
	$arrColumnData = [];
	if(empty($arrData)){

		$arrColumnData['beg_inventory'] ='0';
		$arrColumnData['pullout'] = '0';
		$arrColumnData['damage'] = '0';
		$arrColumnData['stock_transfer_out'] = '0';
		$arrColumnData['stock_transfer_in_c'] = '0';
		$arrColumnData['stock_transfer_out_c'] = '0';
		$arrColumnData['interbranch_out_c'] = '0';
		$arrColumnData['interbranch_in_c'] = '0';
		$arrColumnData['pullout_c'] = '0';
		$arrColumnData['damage_c'] = '0';
		$arrColumnData['damage_i'] = '0';
		$arrColumnData['sales'] = '0';
		$arrColumnData['number'] = '0';
		$arrColumnData['transit_out'] = '0';
		$arrColumnData['requested'] = '0';
		$arrColumnData['transit_in'] = '0';
		$arrColumnData['transit_out_c'] = '0';
		$arrColumnData['past_variance']  = '0';
		$arrColumnData["sales_past"] = '0';

		$arrColumnData['past_variance_2']  = '0';
		$arrColumnData['audit_date']  = '0';
		$arrColumnData['Interbranch_status_date']  = '0';
		$arrColumnData['stock_transfer_status_date']  = '0';
		$arrColumnData['interbranch_in_past']  = '0';
		$arrColumnData['stock_transfer_in_past']  = '0';
		$arrColumnData['sales_deduct_physical']  = '0';
		$arrColumnData['damage_past_date']  = '0';
		$arrColumnData['pullout_past_date']  = '0';
		$arrColumnData['stock_transfer_minus_date']  = '0';
		$arrColumnData['stock_transfer_minus']  = '0';
		$arrColumnData['interbranch_out_past']  = '0';
		$arrColumnData['interbranch_out_past_date']  = '0';
		$arrColumnData['interbranch_in_past_date']  = '0';

		$arrColumnData['beg_inventory'] = '0';
		$arrColumnData['running_total'] = '0';
		$arrColumnData['physical_count'] = '0';	
		$arrColumnData['variance'] = '0';

	}else{
        $arrColumnData['store_name'] = $arrData[0][0]['store_name'];
        $arrColumnData['item_name'] = $arrData[0][0]['item_name'];
        $arrColumnData['product_code'] = $arrData[0][0]['product_code'];
		$arrColumnData['beg_inventory'] = $arrData[0][0]['beg_inventory'];
		$arrColumnData['pullout'] = $arrData[0][0]['pullout'];
		$arrColumnData['damage'] = $arrData[0][0]['damage'];
		$arrColumnData['stock_transfer_out'] = $arrData[0][0]['stock_transfer_out'];
		$arrColumnData['stock_transfer_in_c'] = $arrData[0][0]['stock_transfer_in_c'];
		$arrColumnData['stock_transfer_out_c'] = $arrData[0][0]['stock_transfer_out_c'];
		$arrColumnData['interbranch_out_c'] = $arrData[0][0]['interbranch_out_c'];
		$arrColumnData['interbranch_in_c'] = $arrData[0][0]['interbranch_in_c'];
		$arrColumnData['pullout_c'] = $arrData[0][0]['pullout_c'];
		$arrColumnData['damage_c'] = $arrData[0][0]['damage_c'];
		$arrColumnData['damage_i'] = $arrData[0][0]['damage_i'];
		$arrColumnData['sales'] = $arrData[0][0]['sales'];
		$arrColumnData['number'] = $arrData[0][0]['number'];
		$arrColumnData['transit_out'] = $arrData[0][0]['transit_out'];
		$arrColumnData['requested'] = $arrData[0][0]['requested'];
		$arrColumnData['transit_in'] = $arrData[0][0]['transit_in'];
		$arrColumnData['transit_out_c'] = $arrData[0][0]['transit_out_c'];
		$arrColumnData['past_variance']  = $arrData[0][0]['past_variance'];
		$arrColumnData["sales_past"] =  $arrData[0][0]['sales_past'];

		$arrColumnData['past_variance_2']  = $arrData[0][0]['past_variance_2'];
		$arrColumnData['audit_date']  = $arrData[0][0]['audit_date'];
		$arrColumnData['Interbranch_status_date']  = $arrData[0][0]['Interbranch_status_date'];
		$arrColumnData['stock_transfer_status_date']  = $arrData[0][0]['stock_transfer_status_date'];
		$arrColumnData['interbranch_in_past']  = $arrData[0][0]['interbranch_in_past'];
		$arrColumnData['stock_transfer_in_past']  = $arrData[0][0]['stock_transfer_in_past'];
		$arrColumnData['sales_deduct_physical']  = $arrData[0][0]['sales_deduct_physical'];
		$arrColumnData['damage_past_date']  = $arrData[0][0]['damage_past_date'];
		$arrColumnData['pullout_past_date']  = $arrData[0][0]['pullout_past_date'];
		$arrColumnData['stock_transfer_minus_date']  = $arrData[0][0]['stock_transfer_minus_date'];
		$arrColumnData['stock_transfer_minus']  = $arrData[0][0]['stock_transfer_minus'];
		$arrColumnData['interbranch_out_past']  = $arrData[0][0]['interbranch_out_past'];
		$arrColumnData['interbranch_out_past_date']  = $arrData[0][0]['interbranch_out_past_date'];
		$arrColumnData['interbranch_in_past_date']  = $arrData[0][0]['interbranch_in_past_date'];

	
											//
											
		if(($arrColumnData['beg_inventory'] =='0'  || $arrColumnData['beg_inventory'] ==''  ) &&
		$arrColumnData['pullout'] =='0' &&
		$arrColumnData['damage'] =='0' &&
		$arrColumnData['stock_transfer_out'] =='0' &&
		$arrColumnData['stock_transfer_in_c'] =='0' &&
		$arrColumnData['stock_transfer_out_c'] =='0' &&
		$arrColumnData['interbranch_out_c'] =='0' &&
		$arrColumnData['interbranch_in_c'] =='0' &&
		$arrColumnData['pullout_c'] =='0' &&
		$arrColumnData['damage_c'] =='0' &&
		$arrColumnData['damage_i'] =='0' &&
		$arrColumnData['sales'] =='0' &&
		$arrColumnData['number'] =='0' &&
		$arrColumnData['transit_out'] =='0' &&
		$arrColumnData['requested'] =='0' &&
		$arrColumnData['transit_in'] =='0' &&
		$arrColumnData['transit_out_c'] =='0' &&
		$arrColumnData['sales_deduct_physical'] =='0'
	
		 ){
			$beg_inventoryfloat = 0;
			$beg_inventoryfloat += $arrColumnData["past_variance_2"];
		 	$beg_inventory= $beg_inventoryfloat;

		}
		else{
	
		
		$beg_inventoryx =$arrColumnData["beg_inventory"]
		-$arrColumnData["pullout"]
		-$arrColumnData["damage"]
		-$arrColumnData["stock_transfer_out"]
		-$arrColumnData["sales_past"];

		
		if($branch=='warehouse'){

		
				if(strpos($arrColumnData["past_variance"],"-")){
					$beg_inventory=$beg_inventoryx-$arrColumnData["past_variance"];

				}else{
						$beg_inventory=$beg_inventoryx+$arrColumnData["past_variance"];
				}
			}else{

		if(strpos($arrColumnData["past_variance"],"-")){
		
					$beg_inventory=$beg_inventoryx-$arrColumnData["past_variance"];
				
				}else{
					

					if(  ( $arrColumnData["audit_date"] >=$arrColumnData["stock_transfer_status_date"] 
							 &&  !empty($arrColumnData["stock_transfer_status_date"] )  && empty($arrColumnData["interbranch_out_past_date"]  )  )
						
					){
						
				
                        $beg_inventory= $arrColumnData["past_variance_2"] - $arrColumnData["sales_deduct_physical"] + $arrColumnData["stock_transfer_in_past"] + $arrColumnData["interbranch_in_past"];
                        

					}
					elseif($arrColumnData["audit_date"] >=$arrColumnData["interbranch_out_past_date"] 
					&&  !empty($arrColumnData["interbranch_out_past_date"]) && empty($arrColumnData["stock_transfer_status_date"] ) ){


						$beg_inventory= $arrColumnData["past_variance_2"] - $arrColumnData["sales_deduct_physical"] + $arrColumnData["stock_transfer_in_past"] + $arrColumnData["interbranch_in_past"];
					}
					
					elseif( ( $arrColumnData["audit_date"] <=$arrColumnData["stock_transfer_status_date"]) 
                              || ( $arrColumnData["audit_date"] <=$arrColumnData["damage_past_date"]) 
							|| ($arrColumnData["audit_date"]!=''  &&  empty($arrColumnData["stock_transfer_status_date"]))
							|| ( $arrColumnData["audit_date"] <= $arrColumnData["stock_transfer_minus_date"]) 
							|| ( $arrColumnData["audit_date"] <= $arrColumnData["interbranch_out_past_date"])
							||  ( $arrColumnData["audit_date"] <= $arrColumnData["interbranch_in_past_date"])
							){
							
                                if( $arrColumnData["audit_date"] <=$arrColumnData["stock_transfer_status_date"] || 
                                ( $arrColumnData["audit_date"]!=''  &&  empty($arrColumnData["stock_transfer_status_date"])) ) {
									// echo "stfd<br>";
									
									 $stok_transfer_beg=$arrColumnData["stock_transfer_in_past"];
									//  echo "<br>";
                                }else{
									// echo "xxstfdxx<br>";
                                    $stok_transfer_beg="0";

								}
								


								if( $arrColumnData["audit_date"] <=$arrColumnData["interbranch_in_past_date"] || 
                                ( $arrColumnData["audit_date"]!=''  &&  empty($arrColumnData["interbranch_in_past_date"])) ) {
									// echo "interbranch<br>";
									
									 $interbranch_in_past=$arrColumnData["interbranch_in_past"];
									//  echo "<br>";
                                }else{
									// echo "XXXinterbranchXXX<br>";
                                    $interbranch_in_past="0";

								}
								


                                if( $arrColumnData["audit_date"] < $arrColumnData["damage_past_date"]) {
									// echo "DMG<br>";

										$damage_beg =$arrColumnData["damage"];
										// echo "<br>";
                                }else{
									// echo "xxxxDMGxxxxxxx<br>";
                                    $damage_beg ="0";
								}

								if( $arrColumnData["audit_date"] <= $arrColumnData["interbranch_out_past_date"]) {
									// echo "interbranch_out_past_date";

								$past_interbranch =$arrColumnData["interbranch_out_past"];

									// echo "<br>";
							}else{
								// echo "xxxxxxxxx";
								$past_interbranch ="0";
							}
							
								
								if(( $arrColumnData["audit_date"] <=$arrColumnData["stock_transfer_minus_date"]) ){
									// echo "minustf<br>";
									$stock_transfer_beg_minus =$arrColumnData["stock_transfer_minus"];

								}else{
									// echo "xxxxxxxminustfxxxxxx<br>";
									$stock_transfer_beg_minus = "0";
								}

                             


                                 
						// echo "bbb";
						$beg_inventory=  $arrColumnData["past_variance_2"]+ $stok_transfer_beg
						+$interbranch_in_past
                        - $arrColumnData["sales_deduct_physical"]
						- $damage_beg
						-$stock_transfer_beg_minus
						-$past_interbranch;
                    
                    }else{
					
							$beg_inventory=$beg_inventoryx;
						}
				}
				// }	
			}		
		}
	
		$arrColumnData['beg_inventory'] = $beg_inventory;

		//sales

		if($_GET["product_code"]=='M100'){
			$arrColumnData['sales'] = 0;
		}
		//running
											
	   	$runningtotal =  $beg_inventory +$arrColumnData["stock_transfer_in_c"]
								+$arrColumnData["interbranch_in_c"]- $arrColumnData["stock_transfer_out_c"]-
								$arrColumnData["interbranch_out_c"]-$arrColumnData["damage_c"]-$arrColumnData["pullout_c"]-$arrColumnData['sales']; 

		
		$arrColumnData['running_total'] = $runningtotal;

		$arrPhysicalCount= array();
		
		$grabParams= array(
									'count',
									'actual_count_id',
									'date_count',
									'date_start',
									'date_end', 
									'store_audited',
									'auditor',
									'product_code',
									'input_count'
		);
		 $query="SELECT `count`,
									`actual_count_id`,
									`date_count`,
									`date_start`,
									`date_end`, 
									`store_audited`,
									`auditor`,
									`product_code`,
									`input_count` 
									FROM `inventory_actual_count_studios`
									 WHERE store_audited='".$_GET['filterStores']."'
									 AND product_code ='".$_GET['product_code']."'
									 AND  date_end ='".$_GET['dateEnd']."'
									";
		$stmt = mysqli_stmt_init($conn);
		if (mysqli_stmt_prepare($stmt, $query)) {

			mysqli_stmt_execute($stmt);
			mysqli_stmt_bind_result($stmt, $result1, $result2, $result3, $result4, $result5, $result6, $result7, $result8, $result9);

			while (mysqli_stmt_fetch($stmt)) {

				$tempArray = array();

				for ($i=0; $i < sizeOf($grabParamsACtual); $i++) { 

					$tempArray[$grabParams[$i]] = ${'result' . ($i+1)};

				};

				$arrPhysicalCount[] = $tempArray;

			};

			mysqli_stmt_close($stmt);    
									
		}
		else {

			echo mysqli_error($conn);

		};

			$arrActual=array();
			$arrActualX=array();
			for ($i=0;$i<sizeof($arrPhysicalCount);$i++) {
				$arrActualX[$i]=$arrPhysicalCount[$i]["product_code"];
				$arrActual[$arrPhysicalCount[$i]["product_code"]][$arrPhysicalCount[$i]["date_end"]]["product_code"]=$arrPhysicalCount[$i]["product_code"];
				$arrActual[$arrPhysicalCount[$i]["product_code"]][$arrPhysicalCount[$i]["date_end"]]["count"]=$arrPhysicalCount[$i]["count"];
				$arrActual[$arrPhysicalCount[$i]["product_code"]][$arrPhysicalCount[$i]["date_end"]]["date_end"]=$arrPhysicalCount[$i]["date_end"];
				$arrActual[$arrPhysicalCount[$i]["product_code"]][$arrPhysicalCount[$i]["date_end"]]["input_count"]=$arrPhysicalCount[$i]["input_count"];

			}
		//physical
			if(in_array($_GET['product_code'],$arrActualX )){
			$dateEndpdh = $_GET['dateEndpdh'];
									
				if($arrActual[$_GET['product_code']][$dateEndpdh]["date_end"]==$dateEndpdh 
				&& $_GET['product_code']==$arrActual[$_GET['product_code']][$dateEndpdh]["product_code"]){
						$compute ="y";
							$actual=$arrActual[$_GET['product_code']][$dateEndpdh]["input_count"];
				}else{
					$compute ="n";
						$actual="0";
				}
			}else{
				$compute ="n";
				$actual="0";
			}
			$arrColumnData['physical_count'] = $actual;			

		//variance
		if($compute=='y'){
			$variance =$actual-$runningtotal;
		}else{
			$variance ="0";
		} 
		$arrColumnData['variance'] =  $variance;
	}

	echo json_encode($arrColumnData);
?>