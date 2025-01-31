<?php if(!isset($_SESSION['customer_id'])) { ?>

    <div class="wrapper">
        <p class="text-center font-bold h2 pt-3 pb-3">Complete step 1 to proceed</p>
        <div class="text-center mt-4">
            <a href="./?page=health-declaration-form"><button class="btn btn-primary">go to step 1</button></a>
        </div>    
    </div>
<?php }else{ ?>	
    <div class="packages-list">
        <div class="form-row mt-3">
            <div class="col-lg-6 col-xs-12 mt-4">
                <div  class="card"  style="padding: 14px; text-align: center; border-radius: 20px">
                    <a href="./?page=select-store-studios" id="studios" >
                        <img src="/sis/studios/assets/images/icons/icon-frame-lens.png" class="img-responsive" width="100" height="30">
                    </a>
                    <p style="font-size: 20px; font-weight: bold;" class="mt-2">Sunnies Studios</p>
                </div>
            </div>
            <div class="col-lg-6 col-xs-12 mt-4">
                <div  class="card"  style="padding: 30px; text-align: center; border-radius: 20px">
                    <a href="./?page=select-merch" id="studios" >
                        <p style="font-size: 25px; font-weight: bold;">Merch</p>
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php } ?>	