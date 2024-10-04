<?php include 'packaging_list.php' ?>
<div class="product-section d-flex align-items-center justify-content-between gap-3" style="gap: 16px;">
    <div class="d-flex justify-content-between">
        <div class="form-group">
            <select class="form-control add_paper " id="add_paper">
                <option selected value="" disabled>Sac</option>
                <?php
                $arrPaperBagSelected = [];
                $arrPaperBag = sacList();

                for ($i = 0; $i < count($arrPaperBag); $i++) {
                ?>
                    <option value="<?= $arrPaperBag[$i]["product_code"] ?>"><?= $arrPaperBag[$i]["item_name"] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>


    <div class="d-flex align-items-center justify-content-center count_item">
        <div class="button-container">
            <input type="button" class="minus_count_decrement_sac custom-button" group-orders-specs-id="" value="-">
        </div>

        <input type="text" style="font-size: 16px;" class="form-control count_num_sac bg-transparent" group-orders-specs-id="" value="0" readonly>


        <div class="button-container">
            <input type="button" class="add_count_increment_sac custom-button" group-orders-specs-id="" value="+">
        </div>
    </div>


</div>