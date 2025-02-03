<?php include "packaging_list.php"; ?>
<div class="product-section d-flex align-items-center" style="gap: 16px;">

    <div class="d-flex justify-content-between flex-grow-1">
        <div class="form-group w-100">
            <select class="form-control add_paper " id="add_paper">
                <option selected value="" disabled>Additional paper bag</option>
                <?php
                $arrPaperBagSelected = [];
                $arrPaperBag = paperBagList();

                for ($i = 0; $i < count($arrPaperBag); $i++) {
                ?>
                    <option value="<?= $arrPaperBag[$i]["product_code"] ?>"><?= $arrPaperBag[$i]["item_name"] ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="d-flex align-items-center justify-content-center count_item">
        <div class="button-container">
            <!-- <input type="button" class="minus_count_decrement_sac custom-button" group-orders-specs-id="" value="-"> -->
            <button type="button" class="minus_count_decrement_pbag custom-button" group-orders-specs-id=""
                style="height: 40px; width: 48px; background: #fff;">
                <img src="/v2.0/sis/studios/assets/images/icons/icon-decrement.png" alt="minus"
                    style="height: 24px; width: 24px;  z-index: 99;">
            </button>
        </div>
        
        <input type="text" style="font-size: 16px;" class="form-control count_num_pbag bg-transparent" group-orders-specs-id="" value="0" readonly>


        <div class="button-container">
            <!-- <input type="button" class="add_count_increment_sac custom-button" group-orders-specs-id="" value="+"> -->
            <button type="button" class="add_count_increment_pbag custom-button" group-orders-specs-id=""
                style="height: 40px; width: 48px; background: #fff;">
                <img src="/v2.0/sis/studios/assets/images/icons/icon-increment.png" alt="add"
                    style="height: 24px; width: 24px; z-index: 99;">
            </button>
        </div>
    </div>


</div>