<?php

$sDocRoot = $_SERVER["DOCUMENT_ROOT"];

// Required includes

include "modules/includes/location_setup.php";
//include $sDocRoot."modules/includes/countries_checkout.php";
?>
<script src="js/set_location.js"></script>
<style>
	
	.store-signup .radio {
		text-align: center;
	}

	.store-signup .radio label {
		font-size: 14px;
		line-height: 18px;
		margin-bottom: 0;
		cursor: pointer;
	}

	.store-signup .radio input:checked ~ label {
		background: #0275d8;
		color: #fff;
	}

	.store-signup .btn {
		min-width: 100px;
	}

	.store-signup .select2-container--default {
		width: 100%;
	}

	.store-signup .select2-container--default .select2-selection--single {
		border-color: #D9D9D9 !important;
		height: 40px;
	}

	.store-signup .select2-container--default .select2-selection--single .select2-selection__rendered {
		line-height: 35px;
	}

	.store-signup .select2-container--default .select2-selection--single .select2-selection__arrow {
		height: 40px;
	}

</style>
<?php $generate_pass = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$password = "";
	$password2 = "";

	for ($i=0; $i < 8; $i++) { 

		$password .= $generate_pass[rand(0, (strlen($generate_pass)-1))];
		$password2 .=$password;
	};

?>
<form class="store-signup" action="modules/process/store-signup-register.php" method="POST">

	<div class="row">
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<input type="hidden" value="<?php echo $password; ?>" name="password2">
				<input type="hidden" value="<?php echo $password; ?>" name="confirmPassword2">
				<label class="font-weight-bold" for="lname">Last Name*</label>
				<input type="text" name="lname" class="form-control" id="lname" required>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="fname">First Name*</label>
				<input type="text" name="fname" class="form-control" id="fname" required>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="mname">Middle Name*</label>
				<input type="text" name="mname" class="form-control" id="mname" required>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="province">Province*</label>
			  <select class="text-left s-a mh-40 form-control" name="province" id="province">
                                                            <?php 

                                                                if(checkDataLocation($cProvince)) {

                                                                    echo '<option disabled>Province</option>';

                                                                    for ($i=0; $i < sizeOf($arrCC); $i++) {                                                                         
                                                                        echo '<option value="'.$arrCC[$i]["province"].'" ';

                                                                        if($arrCC[$i]["province"] == $cProvince) {

                                                                            echo 'selected data-city="'.$cCity.'" data-barangay="'.$cBarangay.'"';

                                                                        };

                                                                        echo '>'.ucwords(str_replace("-", " ", $arrCC[$i]["province"])).'</option>';

                                                                    };

                                                                }
                                                                else {

                                                                    echo '<option selected disabled>Province</option>';

                                                                    for ($i=0; $i < sizeOf($arrCC); $i++) { 

                                                                        echo '<option value="'.$arrCC[$i]["province"].'">'.ucwords(str_replace("-", " ", $arrCC[$i]["province"])).'</option>';

                                                                    };

                                                                };

                                                            ?>
                                                        </select>
                                                        <p class="error error-province text-left"></p>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="city">City*</label>
				  <div class="p-c-sect">
                                                            <select class="text-left mh-40 form-control" name="city" id="city" disabled>
                                                                <option value="n" selected disabled>City</option>
                                                            </select>
                                                        </div>
                                                        <p class="error error-city text-left"></p>
			
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="barangay">Barangay*</label>
			<div class="c-b-sect">
                                                            <select class="text-left mh-40 form-control" name="barangay" id="barangay" disabled>
                                                                <option value="n" selected disabled>Barangay</option>
                                                            </select>
                                                        </div>
                                                        <p class="error error-barangay text-left"></p>
			</div>
		</div>

		<!-- right form -->
		<div class="col-12 col-sm-6">
			<div class="form-group">
				<label class="font-weight-bold" for="home_address">Home Address</label>
				<input type="text" name="home_address" class="form-control" id="home_address">
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="bdate">Birthdate (mm/dd/yyyy)*</label>
				<input type="text" name="bdate" class="form-control" id="bdate" required>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="age">Age</label>
				<input type="text" name="age" class="form-control" id="age">
			</div>
			<div class="form-group clearfix">
				<label class="font-weight-bold">Gender</label>
				<div class="radio">
					<input class="sr-only" type="radio" id="gender-male" name="gender" value="Male">
					<label class="form-control" style="float: left; border-top-right-radius: 0; border-bottom-right-radius: 0; border-right-width: 1px; width: 50%;" for="gender-male">Male</label>
				</div>
				<div class="radio">
					<input class="sr-only" type="radio" id="gender-female" name="gender" value="Female">
					<label class="form-control" style="float: left; border-top-left-radius: 0; border-bottom-left-radius: 0; border-left-width: 0; width: 50%;" for="gender-female">Female</label>
				</div>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="email">Email address*</label>
				<input type="email" name="email" class="form-control" id="email" required>
			</div>
			<div class="form-group">
				<label class="font-weight-bold" for="mnum">Mobile Number*</label>
				<input type="text" name="mnum" class="form-control" id="mnum" required>
			</div>
		</div>
	</div>


	<!-- ****************************************************************** -->
	<!-- ************** = ******************************** = ************** -->
	<!-- *** IMPORTANT ***  JOINING DATE + BRANCH APPLIED *** IMPORTANT *** -->

	<input type="hidden" name="specs_branch" value="<?= 'This Store Name' ?>">
	<input type="hidden" name="joining_date" value="<?= date('Y/m/d'); ?>">

	<!-- *** IMPORTANT ***  JOINING DATE + BRANCH APPLIED *** IMPORTANT *** -->
	<!-- ************** = ******************************** = ************** -->
	<!-- ****************************************************************** -->

	<div class="row no-gutters justify-content-center align-items-center">
		<a href="./?page=store-home" class="btn btn-danger" style="margin-top: 20px; margin-right: 15px;">Back</a>
		<button type="submit" class="btn btn-primary" style="margin-top: 20px">Sign Up</button>
	</div>

</form> 

<script>
	 fProv  = $('#province').val();

        fCity  = $('#city').val();

        fBara  = $('#barangay').val();

	$('.register-select').select2();
	$( "#bdate" ).datepicker({
        dateFormat : 'yy-mm-dd',
        changeMonth : true,
        changeYear : true,
        yearRange: '-100y:c+nn',
        maxDate: '-4y'
    });

 



   

//function loadBarangays(x, y, z) {
//
//alert('g');
//    city     = x;
//
//    add      = y;
//
//    barangay = z || "";c is curre
//
//alert('b');
//
//    $('.' + add + 'c-b-sect').load("/modules/includes/location_setup.php?grab_select=b&grab_info=" + add + "&check_city=" + city + "&selected_barangay=" + barangay, function() {
//
//
//
//        $('#' + add + 'barangay').bind('change', function() {
//
//
//
//            var thisVal = $('#' + add + 'barangay').val();
//
//            var inputActive = $('#p-o-1').attr('data-active');
//
//
//
//            if(thisVal.toLowerCase() == 'barangay-185') {
//
//
//
//                $('.r-o-cod').hide();
//
//                $('#p-o-1').prop("checked",false);
//
//                $('#p-o-2').prop("checked",true);
//
//
//
//            }
//
//            else if(inputActive == 'y') {
//
//            
//
//                $('.r-o-cod').show();
//
//                $('#p-o-1').prop("checked",true);
//
//                $('#p-o-2').prop("checked",false);
//
//
//
//            }
//
//            else if(inputActive == 'n') {
//
//
//
//                $('.r-o-cod').show();
//
//                $('#p-o-1').prop("checked",false).prop("disabled", true);
//
//                $('#p-o-2').prop("checked",true);
//
//
//
//            }
//
//            else {
//
//
//
//                $('.r-o-cod').show();
//
//                $('#p-o-1').prop("checked",false);
//
//                $('#p-o-2').prop("checked",true);      
//
//
//
//            };
//
//
//
//        });
//
//
//
//    });
//
//
//
//};



// Drop down loader - City

function loadCities(w, y, z) {



	
    var province = w || "";



    var city     = y || "";

    var barangay = z || "";

    $('.p-c-sect').load("modules/includes/location_setup.php?grab_select=s&check_province=" + province + "&selected_city=" + city, function() {





        // City Select

        $('#city').bind('change', function() {



            var thisVal = $('#city').val();


            $('.c-b-sect').load("modules/includes/location_setup.php?grab_select=b&check_province2=" + province + "&check_city=" + thisVal + "&selected_barangay=" + barangay, function() {




                $('#barangay').bind('change', function() {



                    var thisVal = $('#barangay').val();

                    var inputActive = $('#p-o-1').attr('data-active');


					if(inputActive == 'n') {



                        $('.r-o-cod').show();

                        $('#p-o-1').prop("checked",false).prop("disabled", true);

                        $('#p-o-2').prop("checked",true);



                    }

                    else {



                        $('.r-o-cod').show();

                        $('#p-o-1').prop("checked",false);

                        $('#p-o-2').prop("checked",true);      



                    };



                });



            });



        });



        if(barangay != "") {



            var city = $('#city').val();



            loadBarangays(city, barangay);



        };



    });    



};



// Set up locations

function loadLocations(x) {




    $('#barangay').prop('disabled', true).removeClass('s-a');



    $('#barangay option').each(function() {



        if($(this).val() != "n") {



            $(this).remove();



        }

        else {



            $(this).prop('selected', true);



        };



    });



};
	
	
	
	
	
	
	
	
	if($('#province').val() != null) {



        add         = '';

        loadLocations('');



        cProv       = $('#' + add + 'province').val();        

        selCity     = $('#' + add + 'province option:selected').attr('data-city');            

        selBarangay = $('#' + add + 'province option:selected').attr('data-barangay');   

        loadCities(cProv, add, selCity, selBarangay);



    };



    $('#province').change(function() {



        var add = '';

        var thisVal = $('#' + add + 'province').val();



        loadLocations('');

        loadCities(thisVal, add);



    });

	
	
	if( $('#province').val()!=null){
		
		
	}
	
//	 $('.province').change(function() {
//		
//         //  var thisPosition = $(this).attr('data-location');
//		 var proVal = $(this).val();
//		 //alert(proVal);
//           
//            if (proVal){
//				
//            $.ajax({
//                type: "GET",
//                url: "modules/includes/location_setup.php",
//                data: {proVal},
//                success: function(data) {
//                    alert(data);
//				 //alert('a');	
//					
//				
//                }
//            });
//				
//        }
//        });
</script>