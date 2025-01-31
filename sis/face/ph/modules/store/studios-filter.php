<?php

if ( !isset($_SESSION) ) { 
	session_start();
}

include("../../modules/functions.php");

?>
<style>
    .panel-group .panel {
        
        border-top: 1px solid #000000;
        border-radius: 0;
        margin-top: 20px;
    }
    .panel-last{
        border-bottom: 1px solid #000000;
    }
    
    .panel .pull-right {
        float: right !important;
    }
   
    @media (max-width: 768px){
    .panel .panel-body .col-xs-6 {
        -ms-flex: 0 0 50%;
        flex: 0 0 50%;
        max-width: 50%;
        }
    }
</style>
<div class="overlay-title">
	<div class="d-flex align-items-center">
		<span class="close-overlay" data-reload="no"><img src="<?= get_url('images/icons') ?>/icon-left-arrow.png" alt="close" class="img-fluid"></span>
		<p class="h2">Filter</p>
	</div>
</div>

<section class="col-12 hidden-xs sect-left-panel" id="sect-left-panel">
    <div class="row mt-3">
        <div class="col-md-12 sect-top-right">
            <select id="select-sort" class="hidden-xs select-sort" style="height: 40px; width: 227px; border: 1px solid #000000; font-size: 16px; line-height: 40px;">
                <option value="none" selected="selected" disabled="">Sort</option>
                <option value="no-sort">No Sort</option>
                <option value="sort-new">Sort New Arrivals</option>
                <option value="sort-popular">Sort Most Popular</option>
                <option value="price-up">price - low to high</option>
                <option value="price-down">price - high to low</option>
            </select>
        </div>
        <div class="col-md-12 panel-group" id="accordion" role="tablist" aria-multiselectable="true" style="position: inherit; top: 80px; bottom: inherit;height:85vh; overflow: auto;">
            <div class="panel">
                <div class="panel-heading" role="tab" id="hFFrameColor">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#fFrameColor" aria-expanded="true" aria-controls="hFFrameColor" class="">
                    <p>frame color<span class="pull-right">+</span></p>
                    </a>
                </h4>
                </div>
                <div id="fFrameColor" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="hFFrameColor" aria-expanded="true" style="">
                <div class="panel-body">
                    <form id="filter-color" class="form-filter filter-color text-left">
                    <div class="row">
                    <div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="brown1a" value="brown" name="checkbox[]"><label for="brown1a">Brown</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="tort2a" value="tort" name="checkbox[]"><label for="tort2a">Tort</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="white3a" value="white" name="checkbox[]"><label for="white3a">White</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="pink4a" value="pink" name="checkbox[]"><label for="pink4a">Pink</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="blue5a" value="blue" name="checkbox[]"><label for="blue5a">Blue</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="green6a" value="green" name="checkbox[]"><label for="green6a">Green</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="yellow7a" value="yellow" name="checkbox[]"><label for="yellow7a">Yellow</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="gold8a" value="gold" name="checkbox[]"><label for="gold8a">Gold</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="silver9a" value="silver" name="checkbox[]"><label for="silver9a">Silver</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="clear10a" value="clear" name="checkbox[]"><label for="clear10a">Clear</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="red11a" value="red" name="checkbox[]"><label for="red11a">Red</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="black0a" value="black" name="checkbox[]"><label for="black0a">Black</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="grey12a" value="grey" name="checkbox[]"><label for="grey12a">Grey</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="rose_gold13a" value="rose_gold" name="checkbox[]"><label for="rose_gold13a">Rose Gold</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="purple14a" value="purple" name="checkbox[]"><label for="purple14a">Purple</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="nude15a" value="nude" name="checkbox[]"><label for="nude15a">Nude</label></p></div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-heading" role="tab" id="hFLensColor">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#fLensColor" aria-expanded="false" aria-controls="hFLensColor" class="collapsed">
                    <p>lens color<span class="pull-right">+</span></p>
                    </a>
                </h4>
                </div>
                <div id="fLensColor" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hFLensColor" aria-expanded="false" style="height: 0px;">
                <div class="panel-body">
                    <form id="filter-color-lens" class="form-filter filter-color-lens text-left">
                    <div class="row">
                        <div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="black0b" value="black" name="checkbox[]"><label for="black0b">Black</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="brown1b" value="brown" name="checkbox[]"><label for="brown1b">Brown</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="tort2b" value="tort" name="checkbox[]"><label for="tort2b">Tort</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="white3b" value="white" name="checkbox[]"><label for="white3b">White</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="pink4b" value="pink" name="checkbox[]"><label for="pink4b">Pink</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="blue5b" value="blue" name="checkbox[]"><label for="blue5b">Blue</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="green6b" value="green" name="checkbox[]"><label for="green6b">Green</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="yellow7b" value="yellow" name="checkbox[]"><label for="yellow7b">Yellow</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="gold8b" value="gold" name="checkbox[]"><label for="gold8b">Gold</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="silver9b" value="silver" name="checkbox[]"><label for="silver9b">Silver</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="red10b" value="red" name="checkbox[]"><label for="red10b">Red</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="grey11b" value="grey" name="checkbox[]"><label for="grey11b">Grey</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="blush12b" value="blush" name="checkbox[]"><label for="blush12b">Blush</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="purple13b" value="purple" name="checkbox[]"><label for="purple13b">Purple</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="bronze14b" value="bronze" name="checkbox[]"><label for="bronze14b">Bronze</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="tropical15b" value="tropical" name="checkbox[]"><label for="tropical15b">Tropical</label></p></div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-heading" role="tab" id="hFStyle">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#fStyle" aria-expanded="false" aria-controls="hFStyle" class="collapsed">
                    <p>style<span class="pull-right">+</span></p>
                    </a>
                </h4>
                </div>
                <div id="fStyle" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hFStyle" aria-expanded="false" style="height: 0px;">
                <div class="panel-body">
                    <form id="filter-style" class="form-filter filter-style text-left">
                    <div class="row">
                    <div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="pilot0c" value="pilot" name="checkbox[]"><label for="pilot0c">Pilot</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="cateye1c" value="cateye" name="checkbox[]"><label for="cateye1c">Cateye</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="butterfly2c" value="butterfly" name="checkbox[]"><label for="butterfly2c">Butterfly</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="browline3c" value="browline" name="checkbox[]"><label for="browline3c">Browline</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="square4c" value="square" name="checkbox[]"><label for="square4c">Square</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="oversized5c" value="oversized" name="checkbox[]"><label for="oversized5c">Oversized</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="round6c" value="round" name="checkbox[]"><label for="round6c">Round</label></p></div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-heading" role="tab" id="hFFinish">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#fFinish" aria-expanded="false" aria-controls="hFFinish" class="collapsed">
                    <p>finish<span class="pull-right">+</span></p>
                    </a>
                </h4>
                </div>
                <div id="fFinish" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hFFinish" aria-expanded="false">
                <div class="panel-body">
                    <form id="filter-finish" class="form-filter filter-finish text-left">
                    <div class="row">
                        <div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="shiny0d" value="shiny" name="checkbox[]"><label for="shiny0d">Shiny</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="matte1d" value="matte" name="checkbox[]"><label for="matte1d">Matte</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="clear2d" value="clear" name="checkbox[]"><label for="clear2d">Clear</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="metal3d" value="metal" name="checkbox[]"><label for="metal3d">Metal</label></p></div>
                        </div>
                    </form>
                </div>
                </div>
            </div>
            <div class="panel panel-last">
                <div class="panel-heading" role="tab" id="hFFaceShape">
                <h4 class="panel-title">
                    <a role="button" data-toggle="collapse" data-parent="#accordion" href="#fFaceShape" aria-expanded="false" aria-controls="hFFaceShape" class="collapsed">
                    <p>face shape<span class="pull-right">+</span></p>
                    </a>
                </h4>
                </div>
                <div id="fFaceShape" class="panel-collapse collapse" role="tabpanel" aria-labelledby="hFFaceShape" aria-expanded="false">
                <div class="panel-body">
                    <form id="filter-face" class="form-filter filter-face text-left">
                    <div class="row">
                    <div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="oval0e" value="oval" name="checkbox[]"><label for="oval0e">Oval</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="round1e" value="round" name="checkbox[]"><label for="round1e">Round</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="square2e" value="square" name="checkbox[]"><label for="square2e">Square</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="heart3e" value="heart" name="checkbox[]"><label for="heart3e">Heart</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="diamond4e" value="diamond" name="checkbox[]"><label for="diamond4e">Diamond</label></p></div><div class="checkbox col-md-6 col-xs-6" style="margin-top: 0;"><p><input type="checkbox" id="long5e" value="long" name="checkbox[]"><label for="long5e">Long</label></p></div>
                    </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>