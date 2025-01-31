$(document).ready(function() {

	// ============================================== ACTIONS / CONFIRMATION
	
	$("#btnsubmit").click(function(){

		$('#action_type_lab').val( $(this).data('value') );
		$('.remarks-overlay').fadeIn();

		$.post("../process/confirmation.php",$("form#lab_form").serialize(),function(d){

			$('.form-remarks').html(d);

			$('.close-confirmation').click(function() {
				// window.location.reload(true);
				$('.form-remarks').html('');
				$('.remarks-overlay').fadeOut();
				$('#action_type_lab').val('');
			});

		});
	});


	$("#btnsubmit2").click(function(){

		$('#action_type_lab').val( $(this).data('value') );
		$('.remarks-overlay').fadeIn();
		
		$.post("../process/confirmation.php",$("form#lab_form").serialize(),function(d){

			$('.form-remarks').html(d);

			$('.close-confirmation').click(function() {
				// window.location.reload(true);
				$('.form-remarks').html('');
				$('.remarks-overlay').fadeOut();
				$('#action_type_lab').val('');
			});

		});
	});

	// ===================================== SEARCH

	$("#search2").keyup(function(e) {
		setTimeout(function() {
			if ( e.keyCode  ) {
				s = $("#search2").val().replace(/\s/g, "+");
				$(".details").load("../process/search.php?s=" + s, function() {
					activateSignature();
				});
				( s == '' ) ? $('#clear_search').addClass('d-none') : $('#clear_search').removeClass('d-none');
				$('.non-search').hide();
			}
		},1000);
	});

});