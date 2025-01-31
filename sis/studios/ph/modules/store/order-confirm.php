<?php 
   include "./modules/includes/grab_product_studios.php";
?>
<?php 
if(!isset($_SESSION['customer_id'])) {?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=health-declaration-form"><button class="btn btn-primary">go to step 1</button></a>
        </div>    
    </div>
<?php }elseif(count($arrCart) == 0) { ?>
    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 2 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=select-store-studios"><button class="btn btn-primary">go to step 2</button></a>
        </div>    
    </div>
<?php }else{ ?>
<style>

    .panel-group .panel {
        
        border-top: 1px solid #000000;
        border-radius: 0;
        margin-top: 20px;
    }
    .panel-last{
        border-bottom: 1px solid #000000;
    }
    .panel p {
        display: block;
        margin-block-start: 1em;
        margin-block-end: 1em;
        margin-inline-start: 0px;
        margin-inline-end: 0px;
    }
    .panel .pull-right {
        float: right !important;
    }
    [type="checkbox"]:not(:checked) + label, [type="checkbox"]:checked + label {
        position: relative;
        padding-left: 1em;
        cursor: pointer;
    }
    .radio-active label, .radio .checked, .checkbox .checked, .checkbox-active label {
        font-weight: 600;
    }
    .radio label, .checkbox label {
        min-height: 20px;
        padding-left: 20px;
        margin-bottom: 0;
        font-weight: normal;
        cursor: pointer;
    }
    label {
        display: inline-block;
        max-width: 100%;
        margin-bottom: 5px;
        font-weight: bold;
    }
    #product-list .card{
        border-radius: 10px;
        cursor: pointer;
    }
    #cart{
        padding: 5px 5px 0px 5px;
        vertical-align: center;
        border-radius: 30px;
        cursor: pointer;
    }
    .count{
        background-color: #FFD5C6; padding: 0px 7px; border-radius: 15px;
    }

    .card{
        border-radius: 15px;
    }
  
}
  
</style>
<div class="packages-list ">
  
    <section class="col-lg-12 col-md-12 col-xs-12 hidden-xs product-panel" id="product-panel">
        
           <form action="#" method="post">
                <?php for($i = 0; $i< count($arrCart); $i++) { ?>
                    <div class="card mt-2">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div class="d-flex justify-content-between" style="text-align:left">
                                    <div style="text-align:left;">
                                        <img src="././images/studios/front.png" class="img-responsive" width="160" height="50" >
                                    </div>
                                    <div style="padding-left: 20px;">
                                        <input type="hidden" name="orders_specs_id[]"  value="<?= $arrCart[$i]['orders_specs_id'] ?>">
                                        <p style="font-size: 12px;" class="mt-2"><?= $arrCart[$i]['item_description'] ?></p>
                                    </div>
                                </div>
                            
                                <div>
                                    <p style="font-size: 18px;"><strong>₱</strong><?= $arrCart[$i]['price'] ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            <div class=" d-flex justify-content-center mt-4" style="text-align: center;">'
                    <div class="col-6">
                    <a href="?page=select-merch"><input type="button" class="btn btn-primary" value="Add Merch"></a>
                        <a href="?page=send-cashier"><input type="submit" class="btn btn-primary" value="Send To Cashier"></a>
                    </div>
                </div>
            </form>
    </section>
</div>
 <?php } ?>