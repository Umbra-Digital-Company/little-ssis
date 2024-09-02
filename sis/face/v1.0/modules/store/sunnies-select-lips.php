
<div class="packages-list hiding">

        <section class="product-panel" id="product-panel">
                <div class="frame-list">
                  
                    <div class="row align-items-start align-items-stretch" style="margin: 0 -10px;">     

                        <?php
                            $isMob = is_numeric(strpos(strtolower($_SERVER["HTTP_USER_AGENT"]), "mobile"));
                        ?>

                        <?php for($i = 0; $i < count($arrProductsSorted); $i++) { ?>                       

                            

                                <?php                                

                                    // Set current colors array
                                    $curColors = $arrProductsSorted[$i]["colors"];

                                    for($a = 0; $a < sizeOf($curColors); $a++) { 

                                        $categoryItem = '';
                                        $subProduct = '';
                                        $prod = $arrProductsSorted[$i]['item_description'].' '.trim($curColors[$a]['color']);

                                        switch($prodCategory){
                                            case 'lips':
                                                if(strstr($prod, 'fluffbalm')){
                                                    $categoryItem = 'fluffbalm';
                                                }elseif(strstr($prod, 'fluffmatte')){
                                                    $categoryItem = 'fluffmatte';
                                                }elseif(strstr($prod, 'dip')){
                                                    $categoryItem = 'lip dip';
                                                }elseif(strstr($prod, 'treat')){
                                                    $categoryItem = 'lip treat';
                                                }
                                                $subProduct = $categoryItem;
                                            break;
                                            case 'face':
                                                if(strstr($prod, 'multistick')){
                                                    $categoryItem = 'the multistick';
                                                    $subProduct = 'multistick';
                                                }elseif(strstr($prod, 'powder')){
                                                    $categoryItem = 'the powder';
                                                    $subProduct = 'powder';
                                                }elseif(strstr($prod, '+')){
                                                    $categoryItem = 'the perfector';
                                                    $subProduct = '+';
                                                }
                                            break;
                                            case 'brows':
                                                if(strstr($prod, 'gel')){
                                                    $categoryItem = 'lifebrow grooming gel';
                                                    $subProduct = 'gel';
                                                }elseif(strstr($prod, 'micro marker')){
                                                    $categoryItem = 'lifebrow micromarker';
                                                    $subProduct = 'micro marker';
                                                }elseif(strstr($prod, 'pencil')){
                                                    $categoryItem = 'lifebrow skinny pencil';
                                                    $subProduct = 'pencil';
                                                }elseif(strstr($prod, 'bundle')){
                                                    $categoryItem = 'lifebrow bundle';
                                                    $subProduct = 'bundle';
                                                }
                                            break;
                                            case 'eyes':
                                                if(strstr($prod, 'eye crayon')){
                                                    $categoryItem = 'eyecrayon';
                                                    $subProduct = 'eye crayon';
                                                }elseif(strstr($prod, 'lashlift')){
                                                    $categoryItem = 'lashlift';
                                                    $subProduct = $categoryItem;
                                                }
                                            break;
                                            case 'skin':
                                                if(strstr($prod, 'dream cream')){
                                                    $categoryItem = 'dream cream';
                                                }elseif(strstr($prod, 'sunsafe')){
                                                    $categoryItem = 'sunsafe';
                                                }elseif(strstr($prod, 'face erase')){
                                                    $categoryItem = 'face erase';
                                                }
                                                 $subProduct = $categoryItem;
                                            break;

                                            case 'cheeks':
                                                if(strstr($prod, 'airblush')){
                                                    $categoryItem = 'airblush';
                                                }elseif(strstr($prod, 'face glass')){
                                                    $categoryItem = 'face glass';
                                                }elseif(strstr($prod, 'glowbeam')){
                                                    $categoryItem = 'glowbeam';
                                                }
                                                 $subProduct = $categoryItem;
                                            break;

                                             case 'nails':
                                                if(strstr($prod, 'play')){
                                                    $categoryItem = '';
                                                }
                                                 $subProduct = $categoryItem;
                                            break;
                                             case 'sets':
                                                $categoryItem = '';
                                                 $subProduct = $categoryItem;
                                            break;
                                        }

                                        // if($categoryItem == '') continue;

                                ?> 
                                <div class="frame-style-2 col-6 mb-3" data-style="<?= $arrProductsSorted[$i]['item_description'] ?>">
                                    <div class="frame-style__slider-2">
                                        <a href="?page=select-store-<?=$prodCategory?>&filter=true&sub-product=<?= $subProduct ?>&data_cgc=<?= $curColors[$a]['data_cgc'] ?>&sub_category=<?= $curColors[$a]['sub_category'] ?>&category=<?= $curColors[$a]['category'] ?>">
                                            <div  prod-item-link="<?=$prodCategory?>" data-color-name="<?= $curColors[$a]['color'] ?>" data-color-code="<?= $curColors[$a]['product_code'] ?>" product-code="<?= $curColors[$a]['product_code'] ?>">
                                                <input type="radio" name="frame_style" class="sr-only" >
                                                <label class="list-item frame-grid d-flex flex-column align-items-center justify-content-center" style="background-color: #f2efea;">

                                                <?php

                                                    $curImageURL = $curColors[$a]["image"];
                                                    // $curStyle        = $arrProductsSorted[$i]['item_description'];
                                                    // $curColor        = str_replace("-g", "-gradient", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($curColors[$a]['color'])))));

                                                    // $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';

                                                    // $color = explode(' ', trim($curColors[$a]['color']));
                                                    // $color = $color[0];




                                                ?>

                                                    <div class="image-wrapper" style="width: 100%; padding-bottom: 75%; background-image: url('<?= $curImageURL ?>'); background-repeat: no-repeat; background-size: 80%; background-position: center;"></div>

                                                    <p style="font-size: 12px; position: absolute; top: 10px; right: 10px;"><?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱'?><?= $curColors[$a]['price'] ?></p>

                                                </label>
                                            </div>
                                        </a>
                                     </div>
                                     <div style="background: #f2efea; padding: 15px; border-radius: 0 0 10px 10px;">
                                        <section class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                                            <h4><?= (trim($categoryItem) != '') ? $categoryItem : trimColor($curColors[$a]['color']) ?><?= (isset($_GET['filter']) && isset($_GET['search']) && trim($_GET['search']) != '') ? '&nbsp;<span class="blk">'.trimColor($curColors[$a]['color']).'</span>' : ''; ?></h4>
                                        </section>
                                        <?php if(isset($_GET['filter']) && isset($_GET['search']) && trim($_GET['search']) != ''){ ?>

                                            <div class="row d-flex justify-content-center mt-3">
                                                <form class="col-12 form-quick-add-to-bag" item="face" id="form-quick-add-to-bag<?= $i ?>" method="POST">
                                                    <input type="hidden" name="studios_product_code" id="input-sku-<?= trim($arrProductsSorted[$i]['item_description']) ?>"  value="<?= trim($curColors[$a]['product_code']) ?>">
                                                    <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
                                                    <button type="submit" class="btn btn-primary">add to bag</button>
                                                </form>
                                            </div>
                                        <?php }else{ ?>

                                            <div class="row d-flex justify-content-center mt-3 pl-3 pr-3">
                                                <input type="hidden" name="studios_product_code" id="input-sku-<?= trim($arrProductsSorted[$i]['item_description']) ?>" value="<?= trim($curColors[$a]['product_code']) ?>">
                                                <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
                                                <a href="?page=select-store-<?=$prodCategory?>&filter=true&sub-product=<?= $subProduct ?>&data_cgc=<?= $curColors[$a]['data_cgc'] ?>&sub_category=<?= $curColors[$a]['sub_category'] ?>&category=<?= $curColors[$a]['category'] ?>" class="btn btn-primary" id="btn-add-<?= strtolower($arrProductsSorted[$i]['item_description']) ?>">Shop All</a> 
                                            </div>
                                        <?php } ?>                             
                                    </div>
                                </div>
                                                
                                <?php } ?>

                               
                        <?php } ?>
                    </div>
                                
                </div>
           
        </section>
</div>