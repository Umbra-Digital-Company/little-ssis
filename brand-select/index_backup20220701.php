<?php

session_save_path($_SERVER["DOCUMENT_ROOT"]."/cgi-bin/tmp");
session_start();

////////////////////////////////////////////////
// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
// exit;
$page = 'admin';
$sDocRoot = $_SERVER["DOCUMENT_ROOT"];
include $sDocRoot.'/includes/connect.php';
include 'get_default_page.php';

if(count($_SESSION['access_brands']) == 1 || $_SESSION['user_login']['position'] == 'lab' || $_SESSION['user_login']['position'] == 'store'){
    $arrDefaultPage = grabDefaultPages($_SESSION['user_login']['username']);

    // echo '<pre>';
    // print_r($arrDefaultPage);
    // echo '</pre>';
    // exit;
    if(count($_SESSION['access_brands']) == 1){
        if($_SESSION['access_brands'][0] == 'specs'){
            $_SESSION['selected-brand'] = 'specs';
            
            echo '<script> window.location = "'.$arrDefaultPage['specs_link'].'";</script>';

        }elseif($_SESSION['access_brands'][0] == 'studios') {
            $_SESSION['selected-brand'] = 'studios';
            echo '<script> window.location = "'.$arrDefaultPage['studios_link'].'";</script>';
        }elseif($_SESSION['access_brands'][0] == 'face') {
            $_SESSION['selected-brand'] = 'face';
            echo '<script> window.location = "'.$arrDefaultPage['face_link'].'";</script>';
        }elseif($_SESSION['access_brands'][0] == 'cup_point') {
            $_SESSION['selected-brand'] = 'cup_point';
            echo '<script> window.location = "'.$arrDefaultPage['cup_point_link'].'";</script>';
        }
    }
    else{
        //user type it means studios access
        if($_SESSION['user_type'] == 1){
            $_SESSION['selected-brand'] = 'studios';
            echo '<script> window.location = "'.$arrDefaultPage['studios_link'].'";</script>';
        }else{
             $_SESSION['selected-brand'] = 'specs';
            echo '<script> window.location = "'.$arrDefaultPage['specs_link'].'";</script>';
        }
    }
}elseif(count($_SESSION['access_brands']) == 0){
    alert('You dont have any access on this website');
}


////////////////////////////////////////////////

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];	

// 2.0
require $sDocRoot."/functions.php";
require $sDocRoot."/header.php";
require $sDocRoot."/sidebar.php";
require $sDocRoot."/top-bar.php";
require $sDocRoot."/footer.php";

// echo '<pre>';
// print_r($_SESSION);
// echo '</pre>';
// exit;

// if($_SESSION['user_login']['position']){

// }
?>
<style>

    .studios:hover, .specs:hover{
        transform: scale(1.2);
        cursor: pointer;
    }
    p {
        text-align: center; 
        font-size: 100px; 
        font-weight: bold; 
    }

    .specs{
        height: 150px;
        transition: transform .2s;
        background: #1a262b;
        border-radius: 5px;
        padding: 50px 30px 0px 30px;
    }
    .studios{
        height: 150px;
        transition: transform .2s;
        background: white;
        border-radius: 5px;
        padding: 60px 30px 0px 30px;
    }
    img{
        max-width: 100%;
    }

    
</style>


<?= get_header($page, 'login') ?>

<div class="container">	
	
	<div class="admin-form" id="admin">
		<form method="post" id="select-brand">

            <div class="modal fade" id="brand_select" role="dialog" data-keyboard="false" data-backdrop="static" style="background-color: #e6ddce;">
                <div class="modal-dialog  modal-dialog-centered">
                
                <!-- Modal content-->
                <div class="modal-content" style="background-color: transparent; border: none;">
                    
                    <div class="modal-body">
                        <div class="row">
                            <div class="form-group col-lg-6 ">
                                <div class="specs" id="specs" brand="specs">
                                    <p class="mt-1"style="color: white; font-weight: bold; font-size: 20px;">OPTICAL</p>                  
                                </div>
                            </div>

                            <div class="form-group col-lg-6 ">
                                <div class="studios" id="studios" brand="studios">
                                    <p class="mt-1"style="color: #000; font-weight: bold; font-size: 20px;">SUN</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                   
                </div>
                
                </div>
            </div>
			

		</form>
	</div>

</div>

<script type="text/javascript">
	$(document).ready(function(){

        $('#brand_select').modal('show');

        $('#studios').click(function(){
            $.post("brand_selected.php",{brand: $(this).attr('brand')},function(d){
				
               window.location = d;
    
            });
        });

        $('#specs').click(function(){
            $.post("brand_selected.php",{brand: $(this).attr('brand')},function(d){
				
                window.location = d;
		
			});
        });

	});
</script>

<?= get_footer() ?>