// ====================================== COUNTDOWN TIMER



// ====================================== PREVIEW CHAT

const previewChat = ( message ) => {
	$('.inner-chat').append(
		'<div class="store-messenger remarks-messenger"><div class="row no-gutters flex-column align-items-end justify-content-end"><div class="col-10 text-white ssis-bg-primary"><p>' + message + '</p></div><p class="font-weight-bold text-right small" style="margin-top: 5px; opacity: .65;">Just now</p></div></div>'
	);
	scrollChat( 300 );
}

const scrollChat = ( time ) => {
	setTimeout(function() {
		$('#informationRemarks .modal-body').animate({
			scrollTop : $('.inner-chat').height()
		});
	}, time);
}

// ====================================== FORMAT NUMBER

// const commaSeparateNumber = (val) => {
//    	while (/(\d+)(\d{3})/.test(val.toString())){
//      	val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
//    	}
//    	return val;
// }