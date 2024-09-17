<?php

if (!isset($_SESSION['customer_id'])) {
    ?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="/sis/studios/v1.0/?page=store-home"><button class="btn btn-primary">go to step 1</button></a>
        </div>
    </div>
    <?php
} else {
    include "./modules/includes/products/grab_select_free_item.php";
    ?>

    <div class="packages-list hiding">
        <?php if (isset($_GET['product-detail']) && trim($_GET['product-detail']) != "") { ?>

        <?php } else { ?>

            <section class="product-panel" id="product-panel">

                <div class="frame-list">

                    <div class="row align-items-start align-items-stretch" style="margin: 0 -10px;">

                        <?php for ($i = 0; $i < sizeOf($arrProductsSorted); $i++) { ?>

                            <div class="frame-style col-6 mb-3" data-style="<?= $arrProductsSorted[$i]['item_description'] ?>">
                                <div class="frame-style__slider">

                                    <?php

                                    // Set current colors array
                                    $curColors = $arrProductsSorted[$i]["colors"];

                                    for ($a = 0; $a < sizeOf($curColors); $a++) {

                                        ?>

                                        <div class="product-option" prod-item-link="free-item"
                                            data-color-name="<?= $curColors[$a]['color'] ?>"
                                            data-color-code="<?= $curColors[$a]['product_code'] ?>"
                                            product-code="<?= $curColors[$a]['product_code'] ?>">
                                            <input type="radio" name="frame_style" class="sr-only">
                                            <label
                                                class="list-item frame-grid d-flex flex-column align-items-center justify-content-center"
                                                style="background-color: #fff;">

                                                <?php

                                                $curImageURL = $curColors[$a]["image"];
                                                // $curStyle        = $arrProductsSorted[$i]['item_description'];
                                                // $curColor        = str_replace("-g", "-gradient", str_replace("-m", "-mirror", str_replace("-f", "-full", str_replace(" ", "-", trim($curColors[$a]['color'])))));
                                
                                                // $curImageURL = 'https://sunniesstudioseyewear.s3-ap-northeast-1.amazonaws.com/products/'.$curStyle.'/'.$curColor.'/front.png';
                                
                                                ?>

                                                <div class="image-wrapper"
                                                    style="width: 100%; padding-bottom: 75%; border-radius: 8px; background-color: #f1f1f1; background-image: url('<?= $curImageURL ?>'); background-repeat: no-repeat; background-size: 100%; background-position: center  ;">
                                                </div>
                                                <!-- <p style="font-size: 12px; position: absolute; top: 10px; right: 10px;">
                                                    <?= (isset($_SESSION['store_type']) && trim($_SESSION['store_type']) == 'vs') ? 'VND ' : '₱' ?>                <?= $curColors[$a]['price'] ?>
                                                </p> -->

                                            </label>
                                        </div>

                                    <?php } ?>

                                </div>
                                <div
                                    style="border-radius: 0 0 16px 16px !important; background-color: #fff; padding: 0 15px 15px 15px;">
                                    <div class="d-flex justify-content-between">
                                        <section
                                            class="product-details row flex-nowrap no-gutters align-items-start justify-content-between">
                                            <h4><?= $arrProductsSorted[$i]['item_description'] ?>
                                            </h4>
                                            <h4><span class="blk"><?= trimColor($curColors[0]['color']); ?></span></h4>
                                        </section>

                                        <section
                                            class="product-details flex-nowrap no-gutters align-items-start justify-content-between">
                                            <h5>
                                                <span class="item-price">P<?= trim($curColors[0]['price']); ?></span>
                                            </h5>
                                        </section>
                                    </div>

                                    <ul class="row switch-color">
                                        <?php
                                        $totalColors = sizeof($curColors);
                                        $maxVisibleColors = 4; // Limit the number of visible colors to 4
                            
                                        for ($a = 0; $a < $totalColors; $a++) {
                                            if ($a < $maxVisibleColors) {
                                                // Display the first 4 items
                                                ?>
                                                <li class="visible" data-index="<?= $a ?>"
                                                    data-style-name="<?= trim($arrProductsSorted[$i]['item_description']) ?>"
                                                    data-color-name="<?= trimColor($curColors[$a]['color']) ?>"
                                                    data-color-code="<?= trim($curColors[$a]['product_code']) ?>"
                                                    data-color-price="P<?= $curColors[$a]['price'] ?>"
                                                    style="<?= ($curColors[$a]['color_swatch'] != '') ? 'background-color: ' . $curColors[$a]['color_swatch'] . ';' : 'background-color: #000;' ?>">
                                                </li>
                                                <?php
                                            }
                                        }

                                        // If there are more than 4 colors, add a "+n" button
                                        if ($totalColors > $maxVisibleColors) {
                                            $remainingColors = $totalColors - $maxVisibleColors;
                                            ?>
                                            <li class="more-item">+<?= $remainingColors ?></li>
                                        <?php } ?>
                                    </ul>
                                    <!-- <div class="row d-flex justify-content-center mt-3">
                                        <form class="col-12 form-quick-add-to-bag" item="merch" id="form-quick-add-to-bag<?= $i ?>" method="POST">
                                            <input type="hidden" name="studios_product_code" id="input-sku-<?= trim($arrProductsSorted[$i]['item_description']) ?>"  value="<?= trim($curColors[0]['product_code']) ?>">
                                            <input type="hidden" class="form-control count_num" name="count_num_value" value="1" readonly>
                                            <button type="submit" class="btn btn-primary">add to bag</button>
                                        </form>
                                    </div>                                 -->
                                </div>
                            </div>

                        <?php } ?>

                    </div>
                </div>

            </section>
        <?php } ?>
    </div>
<?php } ?>