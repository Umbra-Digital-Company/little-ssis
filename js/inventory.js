$(document).ready(function() {

    if ($('.select2').length) {

        $('.select2').select2({
            width: '100%'
        });

        if ($('.select2').hasClass('no-search')) {
            $('.select2.no-search').select2({
                minimumResultsForSearch: -1
            });
        } else {

        }

    }

    ////////////////////////// CALCULATION OF TOTAL RECEIVED COUNT

    function calculateTotal(input, elem, change=false) {

        if (change) {
            input.on('blur', function() {
                var sum = 0;
                input.each(function() {
                    sum += Number($(this).val());
                });

                elem.text(sum);
            })
        } else {
            var sum = 0;
            input.each(function () {
                sum += Number($(this).val());
            });

            elem.text(sum);
        }
    }

    if ($('#total-receive-calc').length) {

        calculateTotal( $('input[name="received_count[]"]'), $('#total-receive-calc'), true );

    }

    ////////////////////////// CALCULATION OF TOTAL TRANSFER COUNT

    if ($('#total-transfer-calc').length) {

        calculateTotal( $('input[name="frame_count[]"]'), $('#total-transfer-calc'), true );

    }

    ///////////////////////// ADD MULTIPLE FRAMES

    if ($('#add-new-row-item').length) {

        let newContent = `
			<tr>
				<th style="width: 360px">
					<select name="frame_code[]" class="select2 filled frame_code">
						${$('.frame_code.select2').html()}
					</select>
				</th>
				<td style="min-width:100px;max-width:100px;">
					<input min="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#">
				</td>
				<td style="min-width:300px;">
					<input type="text" name="item_remark[]" class="form-control filled" placeholder="Remarks for this item">
				</td>
				<td style="vertical-align:middle!important;cursor:pointer;width:50px;"><img src="/assets/images/icons/icon-close-danger.png" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>
			</tr>
		`;

        let pulloutContent = `
			<tr>
				<th style="width: 360px">
					<select name="frame_code[]" class="select2 filled frame_code">
						${$('.frame_code.select2').html()}
					</select>
				</th>
				<td style="min-width:100px;max-width:100px;">
					<input min="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#">
				</td>
				<td style="min-width:300px;">
					<input type="text" name="item_remark[]" class="form-control filled" placeholder="Remarks for this item">
				</td>
				<td style="vertical-align:middle!important;cursor:pointer;width:50px;"><img src="/assets/images/icons/icon-close-danger.png" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>
			</tr>
		`;

        let forManufacturer = `
			<tr>
				<td style="width: 360px">
					<select name="frame_code[]" class="select2 filled frame_code">
						${$('.frame_code.select2').html()}
					</select>
				</td>
				<td style="min-width:100px;max-width:100px;">
					<input mind="0" type="number" name="frame_count[]" class="form-control filled" placeholder="#">
				</td>
				<td style="min-width:100px;max-width:100px;">
					<input type="text" name="received_count[]" class="form-control filled" placeholder="#">
				</td>
				<td style="vertical-align:middle!important;cursor:pointer;width:50px;"><img src="/assets/images/icons/icon-close-danger.png" alt="Close" class="img-fluid remove--item" style="width: 25px!important;max-width:25px!important;"></td>
			</tr>
		`;

        var maxAppend = 0;
        $(document).on('click', '#add-new-row-item', function() {
            if ($(this).hasClass('for-pullout')) {
                for (var i = 0; i < 5; i++) {
                    $('#multiple-item-row tbody').append(pulloutContent);
                }
            } else if ($(this).hasClass('for-manufacturer')) {
                for (var i = 0; i < 5; i++) {
                    $('#multiple-item-row tbody').append(forManufacturer);
                }
            } else {
                for (var i = 0; i < 5; i++) {
                    $('#multiple-item-row tbody').append(newContent);
                }
            }

             calculateTotal( $('input[name="frame_count[]"]'),  $('#total-transfer-calc') );

            $('.select2').select2({
                width: '100%'
            });

            $('.frame_code').on('change', function() {
                if ($(this).val() != "") {
                    $(this).parent().next('td').find('input').prop('required', true);
                }
            })
        })

        $('.frame_code').on('change', function() {
            if ($(this).val() != "") {
                $(this).parent().next('td').find('input').prop('required', true);
            }
        })

        $(document).on('click', '.remove--item', function() {
            $(this).closest('tr').remove();
            calculateTotal( $('input[name="frame_count[]"]'), $('#total-transfer-calc') );
            calculateTotal( $('input[name="received_count[]"]'), $('#total-receive-calc') );
        })

    }



    ///////////////////////// SIGNATURE

    if ($('#thecanvas').length) {

        var reader = new FileReader();

        reader = function() {

            $(".link").attr("href", reader.result);
            $(".link").text(reader.result);

        }

        var canvas = document.getElementById('thecanvas');
        var signaturePad = new SignaturePad(canvas);

        drawSignatureLine();

        var file = signaturePad.toDataURL("image/png");

        $('.save').click(function(e) {
            e.preventDefault();
            $(".signature64").val(signaturePad.toDataURL("image/png"));
            if ($('.signature64').val() != '') {
                $('#confirmForm').prop('disabled', false);
            }
        });

        $('a.clear').click(function(e) {
            e.preventDefault();
            $(".signature64").val("");
            signaturePad.clear();
            drawSignatureLine();
            $('#confirmForm').prop('disabled', true);
        });

        $('.close-signature').click(function(e) {

            // e.preventDefault();

            // var id = $(this).attr("id").replace("close-signature_","");

            // $('#overlay_'+ id).fadeOut();

        });

        function drawSignatureLine() {

            var context = canvas.getContext('2d');
            context.lineWidth = .5;
            context.strokeStyle = '#333';
            context.beginPath();
            context.moveTo(0, 150);
            context.lineTo(500, 150);
            context.stroke();

        }

        function encodeImagetoBase64(element) {

            var file = element.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {

                $(".link").attr("href", reader.result);
                $(".link").text(reader.result);

            }

            reader.readAsDataURL(file);

        }

    }

    if ($('#thecanvasNew').length) {

        var reader = new FileReader();

        reader = function() {

            $(".link").attr("href", reader.result);
            $(".link").text(reader.result);

        }

        var canvas = document.getElementById('thecanvasNew');
        var signaturePad = new SignaturePad(canvas);

        drawSignatureLine();

        var file = signaturePad.toDataURL("image/png");

        $('.save').click(function(e) {
            e.preventDefault();
            $(".signature64").val(signaturePad.toDataURL("image/png"));
            if ($('.signature64').val() != '') {
                $('#confirmFormNew').show();
            }
        });

        $('a.clear').click(function(e) {
            e.preventDefault();
            $(".signature64").val("");
            signaturePad.clear();
            drawSignatureLine();
            $('#confirmFormNew').hide();
        });

        $('.close-signature').click(function(e) {

            // e.preventDefault();

            // var id = $(this).attr("id").replace("close-signature_","");

            // $('#overlay_'+ id).fadeOut();

        });

        function drawSignatureLine() {

            var context = canvas.getContext('2d');
            context.lineWidth = .5;
            context.strokeStyle = '#333';
            context.beginPath();
            context.moveTo(0, 150);
            context.lineTo(500, 150);
            context.stroke();

        }

        function encodeImagetoBase64(element) {

            var file = element.files[0];
            var reader = new FileReader();

            reader.onloadend = function() {

                $(".link").attr("href", reader.result);
                $(".link").text(reader.result);

            }

            reader.readAsDataURL(file);

        }

    }

})