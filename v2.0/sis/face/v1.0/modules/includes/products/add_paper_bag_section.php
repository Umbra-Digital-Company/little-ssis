<?php include 'packaging_list.php' ?>
    <div class="product-section d-flex justify-content-between">
        <div class="d-flex justify-content-between" style="text-align:left">
            <div style="text-align:left;" class="form-group col-12">
                <select class="form-control add_paper_bag" id="add_paper_bag">
                    <option value="">-</option>
                    <?php
                            $arrPaperBagSelected = [];
                            $arrPaperBag = paperBagList();
                            
                        for($i = 0; $i< count($arrPaperBag); $i++) {
                    ?>
                        <option value="<?= $arrPaperBag[$i]["product_code"] ?>"><?= $arrPaperBag[$i]["item_name"] ?></option>
                    <?php } ?>
                </select>
                <label class="placeholder" for="add_paper_bag" style="background-color: #e8e8e4;">Paper Bag</label>
            </div>
        </div>
        
        <div style="text-align: right;">
            <div class="d-flex justify-content-start mt-2 count_item">
                <span><input type="button" class="form-control minus_count_decrement_pbag" group-orders-specs-id="" value="-"></span>
                <input type="text" class="form-control count_num_pbag" value="0" readonly>
                <span><input type="button" class="form-control add_count_increment_pbag" group-orders-specs-id="" value="+"></span>
            </div>
            
        </div>
    </div>