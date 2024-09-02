$(document).ready(function() {
    
   fProv = $('#province').val();
   fCity = $('#city').val();
   fBara = $('#barangay').val();

   $('#country').change(function() {
        if ( $(this).val() == 'philippines' ) {
            $('#province, #city, #barangay').removeAttr('disabled').parent().parent().show();
            $('#country').parent().parent().attr('class', 'col-6');
        } else {
            $('#province, #city, #barangay').attr('disabled', 'disabled').parent().parent().hide();
            $('#country').parent().parent().attr('class', 'col-12');
        }
    });

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
        if(user_type != 3){
            loadCities(cProv, add, selCity, selBarangay);
        }
    };

    $('#province').change(function() {

        var add = '';
        var thisVal = $('#' + add + 'province').val();

        loadLocations('');
        loadCities(thisVal, add); 

    });

});

