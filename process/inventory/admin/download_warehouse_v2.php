<?php
    $sDocRoot = $_SERVER["DOCUMENT_ROOT"];
    if(isset($_POST['filename'])){
        unlink($sDocRoot.'/downloads/'.$_POST['filename']);
        exit;
    }
// Required includes
require $sDocRoot."/includes/connect.php";
    // Set stores array
       $store = str_replace(" ","_" ,ucwords($_POST["store"]));

    $filename = $store."_".date('mdYhis').".csv";
    $f = fopen($sDocRoot.'/downloads/'.$filename,"w");
    $header = json_decode($_POST['header'],true);
    $data = json_decode($_POST['data'],true);
    $arrHeader = [];
    if($_POST['content'] == 'running'){
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['sku_desc']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_product_code']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_running_inventory']));
    }else{
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['sku_desc']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_product_code']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_beginning_inventory']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_daily_sales']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_stock_transfer_plus']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_stock_transfer_minus']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_inter_branch_plus']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_inter_branch_minus']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_pullout']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_damage_in']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_damage']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_in_transit_plus']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_in_transit_minus']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_running_inventory']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_physical_count']));
        $arrHeader[] = strtoupper(str_replace("<br>", " ", $header[0]['header_variance']));
    }
    fputcsv($f, $arrHeader, ",");

    if($_POST['content'] == 'running'){
        for($i = 0; $i < count($data); $i++){
            $arrData = [];
            $arrData[] = $data[$i]['sku_desc'];
            $arrData[] = $data[$i]['sku_code'];
            $arrData[] = $data[$i]['get_running_inventory'];

            fputcsv($f, $arrData, ",");
        }
    }else{
        for($i = 0; $i < count($data); $i++){
            $arrData = [];
            $arrData[] = $data[$i]['sku_desc'];
            $arrData[] = $data[$i]['sku_code'];
            $arrData[] = $data[$i]['get_beginning_inventory'];
            $arrData[] = $data[$i]['get_daily_sales'];
            $arrData[] = $data[$i]['get_stock_transfer_plus'];
            $arrData[] = $data[$i]['get_stock_transfer_minus'];
            $arrData[] = $data[$i]['get_inter_branch_plus'];
            $arrData[] = $data[$i]['get_inter_branch_minus'];
            $arrData[] = $data[$i]['get_pullout'];
            $arrData[] = $data[$i]['get_damage_in'];
            $arrData[] = $data[$i]['get_damage'];
            $arrData[] = $data[$i]['get_in_transit_plus'];
            $arrData[] = $data[$i]['get_in_transit_minus'];
            $arrData[] = $data[$i]['get_running_inventory'];
            $arrData[] = $data[$i]['get_physical_count'];
            $arrData[] = $data[$i]['get_variance'];

            fputcsv($f, $arrData, ",");
        }
    }
    fwrite($f);
    fclose($f);
    $json['filename'] = $filename;
    echo json_encode($json);
    
?>