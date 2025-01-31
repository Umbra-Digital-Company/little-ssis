<?php 

if(!isset($_SESSION['customer_id'])) {
?>
<div class="wrapper">
    <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
    <div class="text-center mt-4">
        <a href="/v2.0/sis/face/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
    </div>    
</div>
<?php
}else{
   include "./modules/includes/products/grab_select_readers.php";
?>

<div class="packages-list hiding">
    <?php if(isset($_GET['product-detail']) && trim($_GET['product-detail']) != "") { ?>
        
    <?php }else{ ?>

        <section class="product-panel" id="product-panel">
            
                <div class="frame-list">    

                    <div class="row align-items-start align-items-stretch" style="margin: 0 -10px;">     

                        <?php for($i = 0; $i < sizeOf($arrProductsSorted); $i++) { ?>                       

                            <div class="frame-style col-6 mb-3" data-style="<?= $arrProductsSorted[$i]['item_description'] ?>">
                                <div class="frame-style__slider">

                                <?php                                

                                    // Set current colors array
                                    $curColors = $arrProductsSorted[$i]["colors"];

                                    for($a = 0; $a < sizeOf($curColors); $a++) { 

                                ?> 

                                    <div class="product-option" prod-item-link="readers" data-color-name="<?= $curColors[$a]['color'] ?>" data-color-code="<?= $curColors[$a]['product_code'] ?>" product-code="<?= $curColors[$a]['product_code'] ?>">
                                        <input type="radio" name="frame_style" class="sr-only" >
                                        <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center" style="background-color: #e8e8e4;">

                                        <?php

                                            $curImageURL = $curColors[$a]["image"];
                                            // $curStyle        = $arrProductsSorted[$i]['item_description'];
                                            // $curColor        = str_replace("-g", "-gradient", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($curColors[$a]['color'])))));

                                            // $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';

                                        ?>

                                            <div class="image-wrapper" style="width: 100%; padding-bottom: 75%; background-image: url(<?= $curImageURL ?>); background-repeat: no-repeat; background-size: 80%; background-position: center;"></div>

                                            <p style="font-size: 12px; position: absolute; top: 10px; right: 10px;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?><?= $curColors[$a]['price'] ?></p>

                                        </label>
                                    </div>
                                                
                                <?php } ?>

                                </div>
                                <div style="background: #e8e8e4; padding: 15px; border-radius: 0 0 10px 10px;">
                                    <section class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                                        <h4><?= $arrProductsSorted[$i]['item_description'] ?>&nbsp;<span class="blk"><?= trimColor($curColors[0]['color']); ?></span></h4>
                                    </section>
                                    <ul class="row switch-color p-0" style="margin: 0 -3px;">

                                        <?php for($a = 0; $a< sizeOf($curColors); $a++) { ?>
                                    
                                            <li class="<?= $a === 0 ? 'active' : '' ?>" data-index="<?= $a ?>" data-style-name="<?= trim($arrProductsSorted[$i]['item_description']) ?>" data-color-name="<?= trimColor($curColors[$a]['color']) ?>" data-color-code="<?= trim($curColors[$a]['product_code']) ?>" style="<?=($curColors[$a]['color_swatch'] != '') ? 'background-color: '.$curColors[$a]['color_swatch'].';' : 'background-color: #000;' ?>"></li>
                                            
                                        <?php } ?>

                                    </ul>    
                                    <div class="row d-flex justify-content-center mt-3">
                                        <form class="col-12 form-quick-add-to-bag" item="antirad" id="form-quick-add-to-bag<?= $i ?>" method="POST">
                                            <input type="hidden" name="studios_product_code" id="input-sku-<?= trim($arrProductsSorted[$i]['item_description']) ?>"  value="<?= trim($curColors[0]['product_code']) ?>">
                                            <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
                                            <button type="submit" class="btn btn-primary">add to bag</button>
                                        </form>
                                    </div>                                
                                </div>
                            </div>

                        <?php } ?>

                    </div>
                </div>
           
        </section>
    <?php } ?>
</div>
<?php } ?>