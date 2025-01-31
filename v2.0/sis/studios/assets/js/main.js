$(document).ready(function () {

	/*
	* GLOBAL FUNCTION FUNCTION
	* Scripts for all pages start here
	*
	* 1: Create a function | 2: Check if element exist |  3: Use the function
	*
	*/

	const autoReload = ( seconds ) => {
		setTimeout(function() {
			window.location.reload(true);
		}, seconds);
	}


	
	const ajaxSubmit = ( file, form) => { // submit form via ajax
		$.post( file, $("form#"+form).serialize(), function(d) {
			$('#msg').html("");
			( d.substring(0, 7) == 'success' ) ? window.location = "./?page=home" : $("#msg").html(d);
		});
	}

	const commaSeparateNumber = val => {
		while (/(\d+)(\d{3})/.test(val.toString())) {
			val = val.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
		}
		return val;
	}

	const overlayContent = body => {
		$('.ssis-overlay').fadeIn(200).addClass('show').html(body);
		$('.close-overlay').click(function () {
			if ($(this).data('reload') == 'yes') {
				window.location.reload(true);
			} else {
				$('.ssis-overlay').removeClass('show').fadeOut().html("");
			}

			if ( $(this).data('sidebar') == 'yes' ) {
				toggleSidebar('show');
			}
		});
	}

	const togglePassword = () => { // show or hide password
		var x = $('.toggle-password');
		(x.prop('type') === "password") ? x.attr('type', 'text') : x.attr('type', 'password');
	}

	const formatNumber = elm => {
		var number = $(elm).val().replace(/-/g, "");

		if (number.length > 3) {
			if (number.length < 6) {
				$(elm).val(number.substr(0, 3) + '-' + number.substr(3, 3));
			}
			else if (number.length > 6) {
				if (number.length == 7) {
					$(elm).val(number.substr(0, 3) + '-' + number.substr(3, 4));
				}
				else if (number.length < 11) {
					$(elm).val(number.substr(0, 3) + '-' + number.substr(3, 3) + '-' + number.substr(6, 4));
				}
				else {
					$(elm).val(number.substr(0, 3) + '-' + number.substr(3, 3) + '-' + number.substr(6, 4) + '-' + number.substr(10, 4));
				}
			}
		}
	}

	const validateEmail = checkoutEmail => {
		var filter = /^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/i;
		if (filter.test(checkoutEmail)) {
			return true;
		} else {
			return false;
		}
	}

	const toggleSidebar = toggle => {
		if ( toggle == 'show' ) {
			$('#ssis_sidebar').fadeIn(200).addClass('show');
			// $('.ssis-backdrop').fadeIn(100);
		} else if ( toggle == 'hide' ) {
			$('#ssis_sidebar').fadeOut(200).removeClass('show');
			// $('.ssis-backdrop').fadeOut(100);
		}
	}

	const mobileFormat = (action, $this) => {
		if ( action == 'show' ) {
			$this.addClass('active').siblings('.mobile-format').show();
		} else if ( action == 'hide' ) {
			$this.removeClass('active').siblings('.mobile-format').hide();
		}
	}

	// =================== FORMAT MOBILE NUMBER

	$('.mobile-number').on('focus', function() {
		mobileFormat('show', $(this));
	}).on('blur', function() {
		if ( $(this).val() == '' ) {
			mobileFormat('hide', $(this));
		}
		if (/^[0-9]/.test(this.value)) {
			this.value = this.value.replace(/^0/, "");
			formatNumber(this);
		}
	});

	// =================== FORMAT PRICE

	$('.format-price').each(function () {
		$(this).text(commaSeparateNumber('P' + $(this).text()));
	});

	// =================== SHOW OR HIDE PASSWORD VALUE

	$('#toggle_password').on('click', togglePassword);

	// ================== OPEN AND CLOSE SIDEBAR

	$('.account').on('click', function() {
		toggleSidebar('show');

		$('#hide_sidebar').on('click', function() {
			toggleSidebar('hide');
		});
	});

	// ================== ALERT

	if ( $('.alert').length ) {
		$('.alert').delay(5000).fadeOut(500);
	}

	// ================= DOCTOR PROGRESS

	$('.prevent').on('click', function(e) {
		e.preventDefault();
	});

	
	// ================= ASSISTANT CONFIRMATION

	$('#exit_customer_page').on('click', function() {

		// Check the get parameter "page=?"" value
		var page = window.location.search;
		var pageValue = page.split('=')[1];
		if (pageValue != 'select-store') {
			// Back in the previous page
			window.history.back();
			return;
		}
		

		$('.ssis-overlay').load("/v2.0/sis/studios/func/store/cancel-confirmation.php", function (d) {
			overlayContent(d);

			let confirmAssistant = password => {
				if ( $('#password_confirmation').val() != password ) {
					$('.wrong-password').fadeIn();
				} else {
					$.post('/v2.0/sis/studios/func/process/unset_login.php');
					window.location.href="./?page=store-home";
				}
			}

			const toggleButtonState = () => {
				const password = $("#password_confirmation").val();
				$("#yes_button").prop("disabled", password.trim() === "");
			  };


			  // Initial call to set the correct state
			toggleButtonState();
	
			// Listen for changes in the password field
			$("#password_confirmation").on("keyup", function (e) {
			  if (e.keyCode == 13) {
				confirmAssistant("SSIS");
			  }
			  toggleButtonState(); // Update button state on input change
			});
	
			// Listen for "Yes" button click
			$("#yes_button").on("click", function () {
			  confirmAssistant("SSIS");
			});
	
			$("#no_button").on("click", function () {
			  $(".ssis-overlay").hide();
			  $(".modal-backdrop").remove(); // Ensure the backdrop is removed
			  $(".home.doctor-home").show(); // Ensure the exit button is shown
			});

			$(".form-control").on('keyup', function (e) {
				if (e.keyCode == 13) {
					confirmAssistant('SSIS');
				};
			});

		});
	});





	/*
	 * ADMIN LOGIN FUNCTION
	 * Scripts for login functionality start here
	 * 
	 * 1: Create a function | 2: Check if element exist |  3: Use the function
	 * 
	 */

	if ( $('#login_form').length ) {

		$(".form-control").on('keyup', function(e) {
			if ( e.keyCode == 13 ) ajaxSubmit('/v2.0/sis/studios/func/loginConfirm.php', 'login_form');
		});

		$("#btnsubmit").on('click', function() {
			ajaxSubmit('/v2.0/sis/studios/func/loginConfirm.php', 'login_form');
		});

	}
	
	// ================= SWITCH ACCOUNT

	const ajaxLogin2 = () => { // submit form via ajax
		$.post("", $("form#add_account_login").serialize(), function (d) {
			$('#msg').html("");
			(d.substring(0, 7) == 'success') ? window.location.reload() : $("#msg").html(d);
		});
	}

	$('#switch_account').on('click', function (e) {
		e.preventDefault();
		$('.ssis-overlay').load("/v2.0/sis/studios/func/accounts.php", function (d) {
			overlayContent(d);

			// ================ ADD ACCOUNT 

			$('#add_account').click(function () {
				$('.acct-new').fadeIn();
			});
			$('#cancelSign').click(function () {
				$('.acct-new').fadeOut();
			});

			// submit login
			$("#btnsubmit").on('click', function () {
				ajaxSubmit('/v2.0/sis/studios/func/process/add_user2.php', 'add_account_login');
			});

			$(".form-control").on('keyup', function (e) {
				if (e.keyCode == 13) ajaxSubmit('/v2.0/sis/studios/func/process/add_user2.php', 'add_account_login');;
			});

			// toggle password visiblity
			$('#toggle_password').on('click', togglePassword);

			// ================ REMOVE ACCOUNT CONFIRMATION

			$('.btn_remove_account').on('click', function () {
				var id = $(this).attr('href');

				$(id).fadeIn();

				$('.cancel_remove').on('click', function() {
					$(this).parents('.remove_account_confirmation').hide();
				});
			});
		});
	});

	$('#add_employee').on('click', function() {
		$('.ssis-overlay').load("/v2.0/sis/studios/func/account/create-account.php", function (d) {
			overlayContent(d);
		});
	});



	/*
	 * ASSISTANT FUNCTION
	 * Scripts for assistant functionality start here
	 * 
	 * 1: Create a function | 2: Check if element exist |  3: Use the function
	 * 
	 */

	const verifyCheckbox = () => {
		$('.checkbox').on('click', () => {
			var isChecked = $('input[type="checkbox"]:checked').length;

			if (isChecked > 0) {
				$('#btnsubmit2').addClass('btn-primary').removeClass('btn-secondary');
			} else {
				$('#btnsubmit2').addClass('btn-secondary').removeClass('btn-primary');
			};
		});
	}

	const loadOverviewConfirmation = form => {
		var isChecked = $('input[type="checkbox"]:checked').length;

		if (isChecked > 0) {
			$.post("/v2.0/sis/studios/func/process/overview_confirmation.php", $(form).serialize(), function (d) {
				overlayContent(d);
			});
		} else {
			$('.alert').fadeIn().find('#error-message').text('Please check order to synch');
		}
	}

	// const loadOverviewSearchResult = () => {
	// 	s = $("#input_search").val().replace(/\s/g, "+");
	// 	t = $('#theme_layout').val();

	// 	$('.ssis-searching').fadeIn();
	// 	verifyCheckbox();

	// 	$(".search-result").load("/ssis/modules/dispatch/overview_search.php?s=" + s + "&t=" + t, function () {
	// 		verifyCheckbox();
	// 		$("#btnsubmit2").click(function () {
	// 			loadOverviewConfirmation('form#lab_form2');
	// 		});
	// 		$('.ssis-searching').fadeOut();
	// 	});

	// 	if ($('#input_search').val() != '') {
	// 		$('.non-search').hide();
	// 		$('#btnsubmit2').addClass('btn-secondary').removeClass('btn-primary');
	// 	}
	// }

	const resetValue = val => {
		$('#edit-personal-content .form-group input').each(function () {
			$(this).val($(this).data(val));
		});
	}

	const toggleProfileContent = target => {
		$('.details-content').each(function () {
			if ($(this).attr('id') == target) {
				$(this).fadeIn().addClass('active');
			} else {
				$(this).hide().removeClass('active');
			}
		});
	}

	// ========================== STORE HOME

	if ( $('.store-statistics').length ) {

		$('.count').each(function () {
			var $this = $(this);

			$({ Counter: 0 }).animate({ Counter: $this.text() }, {
				duration: 1000,
				easing: 'swing',
				step: function () {
					$this.text(Math.ceil(this.Counter));
				}
			});
		});

	}

	// ========================== ORDER MANAGEMENT
	if ( $('.overview-content').length ) {

		var typingTimer;                
		var doneTypingInterval = 2000;  

		verifyCheckbox();

		$('#overview_search_form').on('submit', function(e) {
			if ( $('#input_search').val() == '' ) {
				e.preventDefault();
			}
		});

		$("#btnsubmit2").on('click', () => {
			loadOverviewConfirmation('form#lab_form');
		});

	}

	// ========================== ORDER MANAGEMENT -> CUSTOMER PROFILE

	if ( $('.overview-details').length ) {

		$('.details-navigation a').on('click', function (e) {
			resetValue('old');
			if (!$(this).hasClass('logout')) {
				e.preventDefault();
				var target = $(this).attr('href').replace('#', '') + '-content';

				$(this).siblings().find('canvas').removeClass('active');
				$(this).find('canvas').addClass('active');
				toggleProfileContent(target);
			}
		});

		$('#edit-personal').on('click', function () {
			var target = 'edit-personal-content';

			$('.details-content').each(function () {
				toggleProfileContent(target);
			});

			resetValue('old');

			$('#cancel-edit').on('click', function (e) {
				e.preventDefault();
				resetValue('old');
				$('#edit-personal-content').hide();
				$('#personal-content').fadeIn();
			});
		});

		$('#re_sendPOS').on('click', function () {
			var body = $('#resendPos').html();
			overlayContent(body);
		});

		$('.re_order').on('click', function () {
			var body = $('#orderEdit').html();
			overlayContent(body);
		});

	}

	/*
	 * CUSTOMER FUNCTION
	 * Scripts for customer page functionality start here
	 * 
	 * 1: Create a function | 2: Check if element exist |  3: Use the function
	 * 
	 */

	const useAccountLogin = () => {
		$.post("/v2.0/sis/studios/func/store/store-sign-master.php", $("form#use_account").serialize(), function (d) {
			$('#msg').html("");

			if (d.substring(0, 7) != 'success') {
				$("#msg").html(d);
			}
		});
	}

	const validateInput = $input => ($input.val() != '') ? $input.css('border-color', '#a19f9d') : $input.css('border-color', '#e54242');
	const validateSelect = $select => ($select.val() != null && $select.val() != 'n') ? $select.css('border-color', '#a19f9d') : $select.css('border-color', '#e54242');

		// =============================== STEP 1 - Use or Create Account

	if ( $('.account-navigation').length ) {

		$('.account-option').on('click', function (e) {
			var target 		= $(this).attr('href');
			e.preventDefault();

			if ( target == '#use-content' ) {
				$('.switch-animation').removeClass('slide');
				
				
			} else {
				$('.switch-animation').addClass('slide');
			
			}
			
			$(this).addClass('active').siblings().removeClass('active');
			$(target).fadeIn().addClass('active').siblings('.account-content').hide().removeClass('active');
		});

		// =============================== USE ACCOUNT

		$('#username').on('keydown', function () {
			if ($.isNumeric($(this).val())) {
				$(this).next('label').text('Mobile Number');
			} else {
				$(this).next('label').text('Email Address');
			}
		}).on('blur', function () {
			if ($(this).val() == '') {
				$(this).next('label').text('Email or mobile number')
			} else {
				if (/^[0-9, +]/.test(this.value)) {
					//this.value = this.value.replace(/^0/, "").replace(/^63/,"").replace("+63","");
					this.value = this.value.replace(/^0/, "").replace("+","");
					formatNumber(this);
				}
			}
		});

		$("#use_account .form-control").on('focus', function() {
			$(this).on('keypress', function (e) {
				if (e.keyCode == 13) {
					e.preventDefault();
					useAccountLogin();
				}
			});
		});

		$("#btnsubmit").on('click', useAccountLogin);

		// =============================== CREATE ACCOUNT

		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth() + 1;
		var yyyy = today.getFullYear();

		if (dd < 10) {
			dd = '0' + dd;
		}

		if (mm < 10) {
			mm = '0' + mm;
		}

		today = yyyy + '-' + mm + '-' + dd;
		document.getElementById("bdate2").setAttribute("max", '2019-12-31');

		let getAge = (value) => {
			var today = new Date().getTime(),
				dob = new Date(value).getTime(),
				age = today - dob;
			yoa = Math.floor(age / 1000 / 60 / 60 / 24 / 365.25);
			$('#age').val(yoa);
		}

		$('#bdate2').on('change', function () {
			var bday = $(this).val();
			$('#bdate').val(bday);
			getAge(bday);
		});

		$('#mnum').on('blur', function() {
			if (/^[0-9]/.test(this.value)) {
				this.value = this.value.replace(/^0/, "");
				formatNumber(this);
			}
		});

		$('form#create_account').submit(function (e) {
			var v_lname = false,
				v_fname = false,
				v_mname = false,
				v_address = false,
				v_country = false,
				v_province = false,
				v_city = false,
				v_brgy = false,
				v_bdate = false,
				v_age = false,
				v_gender = false,
				v_email = false,
				v_mobile = false;

			var r_lname = $('#lname'),
				r_fname = $('#fname'),
				r_mname = $('#mname'),
				r_address = $('#home_address'),
				r_country = $('#country'),
				r_province = $('#province'),
				r_city = $('#city'),
				r_brgy = $('#barangay'),
				r_bdate = $('#bdate'),
				r_age = $('#age'),
				r_email = $('#s_email'),
				r_mobile = $('#mnum'),
				r_gender = {
					'male': $('#gender-male'),
					'female': $('#gender-female')
				};

			$('input[required]').each(function () { validateInput($(this)); });

			(r_lname.val() != '') ? v_lname = true : v_lname = false;
			(r_fname.val() != '') ? v_fname = true : v_fname = false;
			(r_mname.val() != '') ? v_mname = true : v_mname = false;
			(r_address.val() != '') ? v_address = true : v_address = false;
			(r_country.val() != null) ? v_country = true : v_country = false;
			(r_bdate.val() != '') ? v_bdate = true : v_bdate = false;
			(r_age.val() != '') ? v_age = true : v_age = false;
			if (r_country.val() == 'philippines') {
				$('form#create_account select').each(function () { validateSelect($(this)); });
				(r_province.val() != null) ? v_province = true : v_province = false;
				(r_city.val() != null) ? v_city = true : v_city = false;
				(r_brgy.val() != 'n') ? v_brgy = true : v_brgy = false;
			} else {
				$('form#create_account select').each(function () { $(this).css('border-color', '#a19f9d') });
				v_province = true;
				v_city = true;
				v_brgy = true;
			}

			// validate email
			if (validateEmail(r_email.val()) || r_email.val() != '') {
				r_email.css('border-color', '#a19f9d');
				v_email = true;
			} else {
				r_email.css('border-color', '#e54242');
				v_email = false;
			}

			// validate gender
			if (r_gender['male'].prop('checked') == true || r_gender['female'].prop('checked') == true) {
				r_gender['male'].next('label').css('border-color', '#a19f9d');
				r_gender['female'].next('label').css('border-color', '#a19f9d');
				v_gender = true;
			} else {
				r_gender['male'].next('label').css('border-color', '#e54242');
				r_gender['female'].next('label').css('border-color', '#e54242');
				v_gender = false;
			}

			// validate mobile number
			if (r_mobile.val() != '' && r_mobile.val().length == 12) {
				r_mobile.css('border-color', '#a19f9d');
				v_mobile = true;
			} else {
				r_mobile.css('border-color', '#e54242');
				v_mobile = false;
				if (/^[0-9]/.test(r_mobile.val())) {
					r_mobile.val() = r_mobile.val().replace(/^0/, "");
					formatNumber(r_mobile);
				}
			}

			// condition if country is not in the philippines
			if (r_country.val() != 'philippines') {
				if (v_lname == true && v_fname == true && v_mname == true && v_address == true && v_country == true && v_email == true && v_mobile == true && v_bdate == true && v_age == true && v_gender == true) {

				} else {
					e.preventDefault();
					$('.required-warning').fadeIn();
				}
			} else {
				if (v_lname == true && v_fname == true && v_mname == true && v_address == true && v_country == true && v_province == true && v_city == true && v_brgy == true && v_email == true && v_mobile == true && v_bdate == true && v_gender == true) {

				} else {
					e.preventDefault();
					$('.required-warning').fadeIn();
				}
			}
		});

		$('.btn-terms').on('click', function() {
			var body = $('#termsAndCondition').html();
			overlayContent(body);
		})

	}

	// =================================== STEP 2 - Add Packages or Services

	const showAvailableFrame = () => {
		if ( $('#search_frame').val() != '' ) {
			var s = $("#search_frame").val().toLowerCase();
			$('.ssis-searching').fadeIn();

			$('.frame-style').each(function () {
				if ( $(this).data('style').match(s.toLowerCase()) ) {
					$(this).fadeIn();
				} else {
					$(this).fadeOut();
				}
				$('.ssis-searching').fadeOut();
			});
		} else {
			$('.frame-style').fadeIn();
		}
	}

	if ( $('.order-list').length || $('#change_frame_post').length ) {

		// adding packages
		$('.frame-option').on('click', function() {
			if ( $(this).prop('checked') ) {
				$('.frame-action').slideDown();

				// select lens option
				$('.frame-action select').on('change', function() {
					if ($(this).attr('id')=='item_option_placeholder') {
						$('#item_option').val($(this).val())
					} else {
						$('#reason').val($(this).val())
					}
				}).change();
			}
		});

		// select lens reason
		$('#lens_reason_placeholder').on('change', function() {
			if ($(this).val()!='other') {
				$('#lens_reason').val($(this).val());
				$('#specific-reason').fadeOut();
			} else {
				$('#lens_reason').val("");
				$('#specific-reason').fadeIn();
			}
		}).change();
		
		// $('.packages-list .list-item').on('click', function () {
		// 	if ($(this).attr('id') == 'add-frame') { // open modal > select frame > select lens option

		// 		if ( $('.order-count .list-item').length ) {
		// 			$('.order-count').hide();
		// 		}

		// 	} else if ($(this).attr('id') == 'add-frame-only') { // open modal > select frame > input reason

		// 		if ( $('.order-count .list-item').length ) {
		// 			$('.order-count').hide();
		// 		}
		// 		$('#item_option').val('without prescription');
		// 		$('.option-list').hide();
		// 		$('#select_frame').fadeIn();

		// 		// select frame
		// 		$('.frame-option').on('click', function() {
		// 			if ( $(this).prop('checked') ) {
		// 				$('.frame-action').slideDown();
		// 				$('#item_option_placeholder').hide();
		// 				$('#reason_placeholder').show();
		
		// 				// select lens option
		// 				$('#reason_placeholder').on('change', function() {
		// 					$('#reason').val($(this).val())
		// 				}).change();
		// 			}
		// 		});

		// 	} else if ( $(this).attr('id') == 'add-lens' ) { // input reason > add to bag

		// 		$('#add_lens').val('lens only');
		// 		$('#item_option').val('with prescription');
		// 		$('#packages_form').submit();

		// 	}
		// });

		// adding services

		$('.services-list .list-item').on('click', function() {
			var dataVal = $(this).attr('id');
			
			$('#add_services').val(dataVal);
			$('#item_option').val('service');
			$('#packages_form').submit();
		});

		$('input[name="frame_layout"]').on('change', function() {
			var main_style 	= $('.frame-style > p'),
				col 		= $('.frame-style .form-group'),
				list_item   = $('.frame-style .list-item'),
				style_sect  = $('.frame-style .list-item section'),
				frame_style = $('.frame-style .list-item section .style'),
				frame_color = $('.frame-style .list-item section .color');

			if ( $(this).val() == 'list' ) {
				main_style.hide();
				col.removeClass('col-4').addClass('col-12');
				list_item.removeClass('flex-column justify-content-center frame-grid').addClass('flex-row justify-content-start frame-list-view');
				style_sect.removeClass('text-center mt-2').addClass('ml-3');
				frame_style.show();
				frame_color.removeClass('text-center').addClass('text-secondary');
			} else {
				main_style.show();
				col.addClass('col-4').removeClass('col-12');
				list_item.addClass('flex-column justify-content-center frame-grid').removeClass('flex-row justify-content-start frame-list-view');
				style_sect.addClass('text-center mt-2').removeClass('ml-3');
				frame_style.hide();
				frame_color.addClass('text-center').removeClass('text-secondary');
			}
		});

		var typingTimer;                
		var doneTypingInterval = 500;

		$('#search_frame').on('keyup', function () {
			clearTimeout(typingTimer);
			typingTimer = setTimeout(showAvailableFrame, doneTypingInterval);
		});

		$('#search_frame').on('keydown', function () {
			clearTimeout(typingTimer);
		});

		$('#add_more_item').on('click', function() {
			$('.option-list').fadeIn();
			$('.order-count').hide();
			$('#customer-occupation').hide();

			$('#exit_add_more').on('click', function () {
				$('.option-list').hide();
				$('.order-count').fadeIn();
				$('#customer-occupation').fadeIn();
			});
		});

		var orderid = $('.vip_profile').attr('data-order-id');
		var sendtype = $('.btn-submit').attr('data-type');
		var vip = $('.vip_profile').val();

		$(".checkbox").on("change", function () {
			var values = [];
			var result = 0;

			$('.checkbox:checked').each(function () {
				result = $(this).val();
			});

			values.push(result);
			if (sendtype == 'doctor') {
				$(".viplink").attr('href', './?page=doctorque&priority=' + values.join("&") + '&profile_id=' + vip + '&orderNo=' + orderid + '&sendtype=' + sendtype);
			} else {
				$(".viplink").attr('href', './?page=success&priority=' + values.join("&") + '&profile_id=' + vip + '&orderNo=' + orderid + '&sendtype=' + sendtype);
			}
		});

		$('.remove-frame, .remove-frame-count').click(function () {
			$('.ssis-loading').fadeIn();
			id = $(this).parent().data('id');
			$(window).load("/ssis/modules/process/remove_frame.php?order_specs_id=" + id, function () {
				window.location.reload(true);
			});
		});

		// change frame
		$('.change-frame').on('click', function() {
			var order_specs_id = $(this).data('id'),
				lens_option = $(this).data('lens-opt'),
				itempackage = $(this).data('package'),
				reason = $(this).data('reason');

			if ( reason != '' ) {
				window.location.href="./?page=change-current-frame&order_specs_id="+order_specs_id+"&lens_option="+lens_option+"&opt="+reason+"&itempackage="+itempackage;
			} else {
				window.location.href="./?page=change-current-frame&order_specs_id="+order_specs_id+"&lens_option="+lens_option+"&itempackage="+itempackage;
			}
			
		});

		// change frame
		$('.change-frame-doctor').on('click', function() {
			var orderNo = $(this).data('order-no'),
				profileID = $(this).data('profile-id'),
				lens_option = $(this).data('lens-opt'),
				orderSpecsUnique= $(this).data('order-specs-id'),
				reason = $(this).data('reason');

			if ( reason != '' ) {
				window.location.href="./?page=change-current-frame&orderspecsUnique="+orderSpecsUnique+"&orderNo="+orderNo+"&profile_id="+profileID+"&lens_option="+lens_option+"&comp=exam&opt="+reason;
			} else {
				window.location.href="./?page=change-current-frame&orderspecsUnique="+orderSpecsUnique+"&orderNo="+orderNo+"&profile_id="+profileID+"&lens_option="+lens_option+"&comp=exam";
			}
		});

		$('#item_option_placeholder').on('change', function() {
			$('#item_option').val($(this).val())
		}).change();

		$('#reason_placeholder').on('change', function() {
			$('#reason').val($(this).val())
		}).change();

	}



	if ( $('.customer-layout.order-confirmation').length ) {
		
		$('#add-discount').on('submit', function(e) {

			var item = $('#discounted_item').val();
			var price = $('#discounted_price').val();
			
			if ( $('#discounted_price').val() == '' ) {
				$('#discounted_price').addClass('border-danger');
			} else {
				$('#discounted_price').removeClass('border-danger');
				$('#discounted_price').val(price.replace(/,/g,''));
			}

			var selected_item = item;
			var discounted_price = price.replace(/,/g,'');
	
			if ( price == '' ) { // invalid
				console.log('add price');
				e.preventDefault();
			} else {
				console.log(selected_item + ' is discounted to ' + discounted_price);
				// query
			}

		});

	}



	/*
	 * DOCTOR FUNCTION
	 * Scripts for assistant functionality start here
	 * 
	 * 1: Create a function | 2: Check if element exist |  3: Use the function
	 * 
	 */

	const setPrescription = ($method, $profile_id, $order_no, $pres_id, $cart_id, $framePrice) => {
		$('#final_rx_od').on('click', function () {
			if ($(this).prop('checked')) {
				$('.final_rx_od').prop('disabled', true).removeClass('border-danger').val("");
				$(this).addClass('disabled');

				if ($('#final_rx_os').prop('checked')) {
					$('#final_rx_os').prop('checked', false);
					$('.final_rx_os').prop('disabled', false);
					$('#final_rx_os').removeClass('disabled');
				}
			} else {
				$('.final_rx_od').prop('disabled', false);
				$(this).removeClass('disabled');
			}
		});

		$('#final_rx_os').on('click', function () {
			if ($(this).prop('checked')) {
				$('.final_rx_os').prop('disabled', true).removeClass('border-danger').val("");
				$(this).addClass('disabled');

				if ($('#final_rx_od').prop('checked')) {
					$('#final_rx_od').prop('checked', false);
					$('.final_rx_od').prop('disabled', false);
					$('#final_rx_od').removeClass('disabled');
				}
			} else {
				$('.final_rx_os').prop('disabled', false);
				$(this).removeClass('disabled');
			}
		});

		// SUBMIT CREATE PRESCRIPTION

		$('select').on('change', function () {
			if ($(this).val() != '') {
				$(this).removeClass('border-danger');
			}
		});

		$('#create_prescription').click(function (e) {

			// check if OD is disabled
			if ($('#final_rx_od').prop('checked')) {
				// check if OS have valid value
				$('.final_rx_os').each(function () {
					if ($(this).val() == '' || $(this).val() == null) {
						$(this).addClass('border-danger');
					} else {
						$(this).removeClass('border-danger');
					}
				});
			} else if ($('#final_rx_os').prop('checked')) {
				// check if OD have valid value
				$('.final_rx_od').each(function () {
					if ($(this).val() == '' || $(this).val() == null) {
						$(this).addClass('border-danger');
					} else {
						$(this).removeClass('border-danger');
					}
				});
			} else {
				$('.final-rx select').each(function () {
					if ($(this).val() == '' || $(this).val() == null) {
						$(this).addClass('border-danger');
					} else {
						$(this).removeClass('border-danger');
					}
				});
			}

			if ($('select').hasClass('border-danger')) {
				console.log('complete the prescription form');
			} else {

				// validate sleep_time and contact_lens 
				if ( $('#contact_lens').val() == "" || $('#sleep_time').val() == "" ) {
					$('.create-prescription-error').fadeIn();
				} else {
					$('.create-prescription-error').fadeOut();

					var arrPrescription = [], arrPrescriptionFull = [], arrPrescriptionOld = [], arrPrescriptionType = [], arrPurpose = [], arrOD = [], arrOS = [], arrfOD = [], arrfOS = [], arroOD = [], arroOS = [], arrPresType = [], arrPurposeType = [], arrRemarks = [],arrPresRemarks = [], arrDocRemarks = [], arrPresDocRemarks = [], arrSleepTime = [], arrContactLens = [], arrOrdersSleepTime = [], arrOrdersContactLens = [];

					var arrLabRemarks = [];
					$('input[name=lab_remarks]').each(function() {

						if ($(this).prop('checked')) {
							remarks_value = $(this).val();
							arrLabRemarks.push(remarks_value);
						}
					});

					//Remarks
					arrDocRemarks.push($('#doctors_note').val());
					// Prescription name
					prescriptionName = $('#prescription_name').val();
					//purpose
					arrPurpose.push($('.ssis-overlay input[name=prescription_purpose]:checked').val());
					// SPH
					arrOD.push($('#final_rx_sph_od').val());
					arrOS.push($('#final_rx_sph_os').val());
					// CYL
					arrOD.push($('#final_rx_cyl_od').val());
					arrOS.push($('#final_rx_cyl_os').val());
					// AXIS
					arrOD.push($('#final_rx_axis_od').val());
					arrOS.push($('#final_rx_axis_os').val());
					// ADD
					arrOD.push($('#final_rx_add_od').val());
					arrOS.push($('#final_rx_add_os').val());
					// IPD
					arrOD.push($('#final_rx_ipd_od').val());
					arrOS.push($('#final_rx_ipd_os').val());
					// PH
					arrOD.push($('#final_rx_ph_od').val());
					arrOS.push($('#final_rx_ph_os').val());
					// VA
					arrOD.push($('#final_rx_va_od').val());
					arrOS.push($('#final_rx_va_os').val());

					////fulRX
					// SPH
					arrfOD.push($('#full_rx_sph_od').val());
					arrfOS.push($('#full_rx_sph_os').val());
					// CYL
					arrfOD.push($('#full_rx_cyl_od').val());
					arrfOS.push($('#full_rx_cyl_os').val());
					// AXIS
					arrfOD.push($('#full_rx_axis_od').val());
					arrfOS.push($('#full_rx_axis_os').val());
					// ADD
					arrfOD.push($('#full_rx_add_od').val());
					arrfOS.push($('#full_rx_add_os').val());

					//old prescription
					// SPH
					arroOD.push($('#old_rx_sph_od').val());
					arroOS.push($('#old_rx_sph_os').val());
					// CYL
					arroOD.push($('#old_rx_cyl_od').val());
					arroOS.push($('#old_rx_cyl_os').val());
					// AXIS
					arroOD.push($('#old_rx_axis_od').val());
					arroOS.push($('#old_rx_axis_os').val());
					// ADD
					arroOD.push($('#old_rx_add_od').val());
					arroOS.push($('#old_rx_add_os').val());

					arrPresType.push($('.ssis-overlay input[name=prescription_option]:checked').val());

					// arrSleepTime.push($('.ssis-overlay input[name=sleep_time]:checked').val());

					// arrContactLens.push($('.ssis-overlay input[name=contact_lens]:checked').val());

					// Push both arrays to main array
					arrPrescription.push(arrOD);
					arrPrescription.push(arrOS);

					arrPrescriptionFull.push(arrfOD);
					arrPrescriptionFull.push(arrfOS);

					arrPrescriptionOld.push(arroOD);
					arrPrescriptionOld.push(arroOS);

					arrPrescriptionType.push(arrPresType);
					arrPurposeType.push(arrPurpose);
					arrLabRemarks.push($('#prescript_remarks').val());
					arrPresDocRemarks.push(arrDocRemarks);

					// arrOrdersSleepTime.push(arrSleepTime);
					// arrOrdersContactLens.push(arrContactLens);

					// prevent user to click multiple times
					$('#create_prescription').text('Saving prescription...').prop('disabled', true);
					
					if ( $method == 'save' ) {
						$.ajax({
							url: './modules/process/prescription/prescription-add-to-db.php',
							method: 'POST',
							data: {
								profile_id: $profile_id,
								order_no: $order_no,
								prescription_name: prescriptionName,
								data_array: arrPrescription,
								data_array2: arrPrescriptionFull,
								data_array3: arrPrescriptionOld,
								data_array4: arrPrescriptionType,
								data_array5: arrPurposeType,
								remarks: arrLabRemarks,
								docremarks: arrPresDocRemarks,
								sleep_time: $('#sleep_time').val(),
								contact_lens: $('#contact_lens').val()
							},
							success: function () {
								window.location = `./?page=prescription-list&profile_id=${$profile_id}&orderNo=${$order_no}&comp=exam&pres_id=${$pres_id}&cartID=${$cart_id}&frame_price=${$framePrice}`;
							}
						});

					} else if ( $method == 'update' ) {
						$.ajax({
							url: './modules/process/prescription/prescription-add-edit-to-db',
							method: 'POST',
							data: {
								profile_id: $profile_id,
								order_no: $order_no,
								prescription_name: prescriptionName,
								prescription_id: $pres_id,
								data_array: arrPrescription,
								data_array2: arrPrescriptionFull,
								data_array3: arrPrescriptionOld,
								data_array4: arrPrescriptionType,
								data_array5: arrPurposeType,
								remarks: arrLabRemarks,
								docremarks: arrPresDocRemarks,
								sleep_time: $('#sleep_time').val(),
								contact_lens: $('#contact_lens').val()
							},
							success: function () {
								window.location = `./?page=prescription-list&profile_id=${$profile_id}&orderNo=${$order_no}&comp=exam&pres_id=${$pres_id}&cartID=${$cart_id}&frame_price=${$framePrice}`;
							}
						});
					}
				}
			}

		});
	}

	// ============================= DOCTORQUE - PENDING AND COMPLETED CUSTOMER

	if ( $('.doctor-home').length ) {
		$('.cancel-order').on('click', function (e) {
			e.preventDefault();

			var x = $(this).attr('data-orderno');
			var y = $(this).attr('data-profileid');
			var z = $(this).attr('data-name');

			$('#orderproid').val(x);
			$('#profile_id2').val(y);
			$('.data-name').text(z);

			var body = $('#confirm_cancel_order').html();
			overlayContent(body);
		});
	}

	// ============================ CUSTOMER EXAMINE

	if ( $('.customer-profile-examine').length ) {

		// toggle profile details
		$('#show-more').click(function (e) {
			e.preventDefault();
			$('.personal-details').toggleClass('d-none');
		});

		// toggle delete frame
		$('#edit_frame').on('click', function() {
			if ($(this).prop('checked')) {
				$(this).next('label').text('Cancel Edit');
				$('.list-item.frame .frame-price').hide();
				$('.list-item.frame .remove-frame-count').fadeIn();
				$('.list-item.frame .change-frame-doctor').fadeIn();
			} else {
				$(this).next('label').text('Edit Order');
				$('.list-item.frame .frame-price').fadeIn();
				$('.list-item.frame .remove-frame-count').hide();
				$('.list-item.frame .change-frame-doctor').hide();
			}
		});

		// send to cashier overview
		$('#send_to_cashier_overview').on('click', function() {
			var body = $('#orderReview').html();
			overlayContent(body);

			// find the duplicate canvas and add
			$('.ssis-overlay .thecanvas').attr('id', 'thecanvas');
			$('.ssis-overlay .insurance').attr('id', 'insurance');
			$('.ssis-overlay .insurance_placeholder_yes').attr('id', 'insurance_placeholder_yes');
			$('.ssis-overlay .insurance_placeholder_no').attr('id', 'insurance_placeholder_no');

			var reader = new FileReader();

			reader = function() {
				$(".link").attr("href",reader.result);
				$(".link").text(reader.result);
			}

			var canvas = document.getElementById('thecanvas');
			var signaturePad = new SignaturePad(canvas);   

			drawSignatureLine();

			var current_file = signaturePad.toDataURL("image/png");

			$('button.clear').click(function(){
				$(".signature64").val("");
				signaturePad.clear();        
				drawSignatureLine();
				$('.send_to_cashier').prop('disabled', true);
			});

			var orderNO = "";
			var profileID = "";
			var signatu = "";
			var insurance = "no";

			$('input[name=insurance_placeholder]').on('click', function() {
				if ($(this).prop('checked')) {
					$('#insurance').val($(this).val());
				}

				if ( $('.signature64').val() != "" ) {
					$('.send_to_cashier').prop('disabled', false);
				}
			});

			$('button.save').click(function(){
				$(".signature64").val(signaturePad.toDataURL("image/png"));
				if ( $('.signature64').val() == current_file ) {
					$('.error-signature').removeClass('d-none').html('<p class="text-danger">Make sure to sign first</p>');
					$('.send_to_cashier').prop('disabled', true);
				} else {
					$('.error-signature').addClass('d-none');
					if ( $('#insurance').val() != '' ) {
						$('.send_to_cashier').prop('disabled', false);
					}
					orderNO = $('.send_to_cashier').data('orderno');
					profileID = $('.send_to_cashier').data('profileid');
					signatu=signaturePad.toDataURL("image/png");
				}
			});

			function drawSignatureLine(){  
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
					$(".link").attr("href",reader.result);
					$(".link").text(reader.result);
				}
				
				reader.readAsDataURL(file);
			}

			var sendToCashier = $('.send_to_cashier');

			sendToCashier.on('click', function() {
				var dataToSend = {
					orderno: orderNO,
					profileid: profileID,
					signature: signatu,
					insurance: insurance
				}

				console.log(dataToSend);
				$('.ssis-loading').fadeIn();
				$.ajax({
					url: '/v2.0/sis/studios/func/process/send_cashier.php',
					method: 'POST',
					data: {orderNo:orderNO,profile_id:profileID,signature:signatu,insurance:insurance,comp:'exam',sendtype:'cashier'},
					success: function() {
						window.location = '../ssis/?page=doctor'
					}
				});
			});
		});

		// check required prescription

		let send_to_cashier = false;
		if ($('.frame-order .list-item.frame').data('required') == 'yes' ) {
			send_to_cashier = false;
		} else {
			send_to_cashier = true;
		}

	}

	// ============================ EXAMINE PROGRESS

	if ($('.examine-progress').length) {
		
		// create prescription
		$('#create_new_prescription').on('click', function() {
			var profile_id = $(this).data('profile-id');
			var order_no = $(this).data('order-no');
			var pres_id = $(this).data('pres-id');
			var cart_id = $(this).data('cart-id');
			var frame_price = $(this).data('frame-price');
			
			$('.ssis-overlay').load("/ssis/modules/doctors/customer-prescription.php?profile_id="+profile_id+"&orderNo="+order_no+"&pres_id="+pres_id+"&cartID="+cart_id+"&frame_price="+frame_price, function (d) {
				overlayContent(d);
				setPrescription('save', profile_id, order_no, pres_id, cart_id, frame_price);

				$('input[name=sleep_time_placeholder]').on('click', function() {
					if ($(this).prop('checked')) {
						$('#sleep_time').val($(this).val());
					}	
				});

				$('input[name=contact_lens_placeholder]').on('click', function() {
					if ($(this).prop('checked')) {
						$('#contact_lens').val($(this).val());
					}
				});

				$('.ssis-overlay .lab_remarks_1').attr('id', 'remarks_1');
				$('.ssis-overlay .lab_remarks_2').attr('id', 'remarks_2');
				$('.ssis-overlay .lab_remarks_3').attr('id', 'remarks_3');
				$('.ssis-overlay .lab_remarks_4').attr('id', 'remarks_4');

				$('.ssis-overlay .sleep_time').attr('id', 'sleep_time');
				$('.ssis-overlay .sleep_time_yes').attr('id', 'sleep_time_yes');
				$('.ssis-overlay .sleep_time_no').attr('id', 'sleep_time_no');
				$('.ssis-overlay .contact_lens').attr('id', 'contact_lens');
				$('.ssis-overlay .contact_lens_yes').attr('id', 'contact_lens_yes');
				$('.ssis-overlay .contact_lens_no').attr('id', 'contact_lens_no');
				
			});
		});
		
		// edit prescription
		$('.edit_prescription').on('click', function(e) {
			e.stopPropagation();

			var profile_id = $(this).data('profile-id');
			var order_no = $(this).data('order-no');
			var pres_id = $(this).data('pres-id');
			var cart_id = $(this).data('cart-id');
			var frame_price = $(this).data('frame-price');
			
			$('.ssis-overlay').load("/ssis/modules/doctors/edit-customer-prescription.php?profile_id=" + profile_id + "&orderNo=" + order_no + "&pres_id=" + pres_id + "&cartID=" + cart_id, function (d) {
				overlayContent(d);
				setPrescription('update', profile_id, order_no, pres_id, cart_id, frame_price);

				$('input[name=sleep_time_placeholder]').on('click', function() {
					if ($(this).prop('checked')) {
						$('#sleep_time').val($(this).val());
					}	
				});

				$('input[name=contact_lens_placeholder]').on('click', function() {
					if ($(this).prop('checked')) {
						$('#contact_lens').val($(this).val());
					}
				});

				$('.ssis-overlay .lab_remarks_1').attr('id', 'remarks_1');
				$('.ssis-overlay .lab_remarks_2').attr('id', 'remarks_2');
				$('.ssis-overlay .lab_remarks_3').attr('id', 'remarks_3');
				$('.ssis-overlay .lab_remarks_4').attr('id', 'remarks_4');

				$('.ssis-overlay .sleep_time').attr('id', 'sleep_time');
				$('.ssis-overlay .sleep_time_yes').attr('id', 'sleep_time_yes');
				$('.ssis-overlay .sleep_time_no').attr('id', 'sleep_time_no');
				$('.ssis-overlay .contact_lens').attr('id', 'contact_lens');
				$('.ssis-overlay .contact_lens_yes').attr('id', 'contact_lens_yes');
				$('.ssis-overlay .contact_lens_no').attr('id', 'contact_lens_no');
			});
		});
		
		// search prescription
		$('#search_old_prescription').on('click', function() {
			$('#searchPrescriptionContent').fadeIn();
			$('.examine-progress').hide();

			$('#cancel_search').on('click', function() {
				$('#searchPrescriptionContent').hide();
				$('.examine-progress').fadeIn();
			});

			$('#search_birth2').on('change', function () {
				var bday = $(this).val();
				$('#search_birth').val(bday);
			});

			$('#validate_search').on('click', function () {
				var sFName = $('#search_f_name').val().replace(/\s/g, '%20'),
					sMName = $('#search_m_name').val().replace(/\s/g, '%20'),
					sLName = $('#search_l_name').val().replace(/\s/g, '%20'),
					sEmail = $('#search_email').val().replace(/\s/g, '%20'),
					sMobile = $('#search_mobile').val().replace(/\s/g, '%20'),
					sBirth = $('#search_birth').val().replace(/-/g, '').replace(/\s/g, '%20'),
					p = $("#searchprofileid").val(),
					o = $("#searchorderid").val(),
					i = $("#searchpresid").val();
				
				let count = 0;
				$('.check-value').each(function() {
					if ( $(this).val() != '' ) {
						count += 1;
					}
				});

				if ( count >= 3 ) {
					$('.ssis-searching').fadeIn();
					$('.search-warning').fadeOut();
					$(".search-prescription-list").load("./modules/process/prescription_search.php?search_f_name=" + sFName + "&search_m_name=" + sMName + "&search_l_name=" + sLName + "&search_email=" + sEmail + "&search_mobile=" + sMobile + "&search_birth=" + sBirth + "&search_limit=&profile_id=" + p + "&orderNo=" + o + "&pres_id=" + i + "&searchadd=", function () {

						$('.ssis-searching').fadeOut();

						$('.view-old-pres').on('click', function () {
							$(this).toggleClass('active').siblings().removeClass('active');
						});
						$('.save_old_prescription').on('click', function (e) {
							e.stopPropagation();
							$(this).parent().siblings().find('form').submit();
						});
						
					});
				} else {
					$('.search-warning').fadeIn();
				}
			});
		});
		
		$('.list-item.prescription').on('click', function() {
			$(this).toggleClass('active').siblings().removeClass('active');
		});
		$('.attach_prescription').on('click', function(e) {
			e.stopPropagation();

			var profile_id = $(this).data('profile-id');
			var pres_id = $(this).data('pres-id');
			var vision = $(this).data('vision');
		});

		// attach eye exam prescription
		if ( $('#confirm_eye_exam_prescription').length ) {
			$('#confirm_eye_exam_prescription').on('click', function() {
				$('.ssis-loading').fadeIn();
				let data = $(this),
					data_profile_id = data.data('profile-id'),
					data_order_id = data.data('order-id'),
					data_specs_id = data.data('order-specs-id'),
					data_frame_price = data.data('price-frame'),
					data_vision = data.data('vision'),
					data_pres_id = data.data('pres-id'),
					data_lens_price = data.data('price-lens'),
					data_lens_code = data.data('lens-code'),
					data_upgrade = data.data('upgrade'),
					data_upgrade_price = data.data('upgrade-price'),
					data_total = data.data('total-price');

				$.ajax({
					url: './modules/process/order/update_order.php',
					method: 'POST',
					data: { 
						profile_id		: data_profile_id,
						order_id		: data_order_id,
						frame_price		: data_frame_price,
						lens_price		: data_lens_price,
						upgrade_price	: data_upgrade_price,
						lens_code		: data_lens_code,
						upgrade			: data_upgrade,
						vision			: data_vision,
						prescription_id	: data_pres_id,
						os_id			: data_specs_id,
						totalPrice		: data_total
					},
					success: function () {
						window.location = './?page=customer-examine&orderNo=' + data_order_id + '&profile_id=' + data_profile_id + '&comp=exam';
					}
				});
			})
		}

	}

	if ( $('.select-lenses-examine').length ) {

		$('#special_order').on('click', function(e) {
			e.preventDefault();
			$('#create_special_order').slideToggle(300);
			$('.toggle_special_order').toggle();
			
		});

		$('#create_special_order').on('click', function(e) {
			e.stopPropagation();
		});

		$('#save_special_order').on('click', function(e) {
			var lens = $('#SR_lens').val();
			var price = $('#SR_price').val();

			$('#create_special_order input').each(function() {
				if ( $(this).val() == '' ) {
					$(this).addClass('border-danger');
				} else {
					$(this).removeClass('border-danger');
					$('#SR_price').val(price.replace(/,/g,''));
				}
			});

			if ( lens == '' || price == '' ) {
				e.preventDefault();
			}
		});

	}

	if ( $('.select-upgrades-examine').length ) {

		let data = $('.confirm_and_save_upgrade'),
			data_profile_id = data.data('profile-id'),
			data_order_id = data.data('order-id'),
			data_specs_id = data.data('order-specs-id'),
			data_frame_price = data.data('price-frame'),
			data_vision = data.data('vision'),
			data_pres_id = data.data('pres-id'),
			data_lens_price = data.data('price-lens'),
			data_lens_code = data.data('lens-code'),
			data_upgrade = data.data('upgrade'),
			data_upgrade_price = data.data('upgrade-price'),
			data_total = data.data('total-price');

		$('.upgrade-details').on('click', function(e) {
			e.stopPropagation();
		});

		$('.lens-upgrade').on('click', function() {
			$(this).toggleClass('active').siblings().removeClass('active');

			if ( $(this).hasClass('tints') ) {
				$('.tint-div').on('click', function() {
					$(this).find('.tint-color').toggleClass('active');
					$(this).siblings().find('.tint-color').removeClass('active');

					var tint_upgrade = $(this).find('.tint-color').data('type');
					var tint_lens_code = $(this).find('.tint-color').data('lens-code');
					var tint_price = $(this).parents('.lens-upgrade').data('price');

					data.attr('data-upgrade', tint_upgrade);
					data.attr('data-lens-code', tint_lens_code);
					data.attr('data-upgrade-price', tint_price);
					data.attr('data-total-price', tint_price+data_frame_price+data_lens_price);

					if ( $(this).find('.tint-color').hasClass('active') ) {
						$('#upgrade_name').text(tint_upgrade.replace('tint-', ' '));
						$('#upgrade_option').text('Tints');
						$('#upgrade_price').text('P' + commaSeparateNumber(tint_price));
						$('#subtotal_price').text('P' + commaSeparateNumber(tint_price + data_lens_price + data_frame_price));
						$('.calc-discount').attr('data-upgrade-price', tint_price);
					} else {
						$('#upgrade_name').text('No upgrade');
						$('#upgrade_option').text('-');
						$('#upgrade_price').text('P' + 0);
						$('#subtotal_price').text('P' + commaSeparateNumber(data_lens_price + data_frame_price));
						$('.calc-discount').attr('data-upgrade-price', 0);
					}
				});
			} else {
				var upgrade = $(this).data('upgrade');
				var upgrade_lens_code = $(this).data('lens-code');
				var upgrade_price = $(this).data('price');

				data.attr('data-upgrade', upgrade);
				data.attr('data-lens-code', upgrade_lens_code);
				data.attr('data-upgrade-price', upgrade_price);
				data.attr('data-total-price', upgrade_price + data_frame_price + data_lens_price);

				if ( $(this).hasClass('active') ) {
					$('#upgrade_name').text(upgrade.replace('_',' '));
					$('#upgrade_option').text('Upgrade');
					$('#upgrade_price').text('P'+commaSeparateNumber(upgrade_price));
					$('#subtotal_price').text('P'+commaSeparateNumber(upgrade_price+data_lens_price+data_frame_price));
					$('.calc-discount').attr('data-upgrade-price', upgrade_price);
				} else {
					$('#upgrade_name').text('No upgrade');
					$('#upgrade_option').text('-');
					$('#upgrade_price').text('P' + 0);
					$('#subtotal_price').text('P' + commaSeparateNumber(data_lens_price + data_frame_price));
					$('.calc-discount').attr('data-upgrade-price', 0);
				}
			}
		});

		$('#upgrade_overview').on('click', function() {
			var body = $('#upgradeConfirmation').html();
			overlayContent(body);

			$('.calc-discount').on('change', function() {
				var selected = $(this).find('option:selected');
				var x = selected.data('discount-x');
				var y = selected.data('discount-y');
				var disc_frame = $(this).data('frame-price');
				var disc_lens = $(this).data('lens-price');
				var disc_upgrade = $(this).data('upgrade-price');
				
				if ( $(this).val() != 0 ) {
					let totalDiscount = disc_frame + ((disc_lens + disc_upgrade) / x * y );
					$('.subtotal_price').text('P' + commaSeparateNumber(totalDiscount.toFixed(2)));
				} else {
					let originalPrice = disc_frame + disc_lens + disc_upgrade;
					$('.subtotal_price').text('P' + commaSeparateNumber(originalPrice));
				}
			});

			$('.confirm_and_save_upgrade').on('click', function() {
				$('.ssis-loading').fadeIn();
				let data = $(this),
					data_profile_id = data.data('profile-id'),
					data_order_id = data.data('order-id'),
					data_specs_id = data.data('order-specs-id'),
					data_frame_price = data.data('price-frame'),
					data_vision = data.data('vision'),
					data_pres_id = data.data('pres-id'),
					data_lens_price = data.data('price-lens'),
					data_lens_code = data.data('lens-code'),
					data_upgrade = data.data('upgrade'),
					data_upgrade_price = data.data('upgrade-price'),
					data_total = data.data('total-price');

				$.ajax({
					url: './modules/process/order/update_order.php',
					method: 'POST',
					data: { 
						profile_id		: data_profile_id,
						order_id		: data_order_id,
						frame_price		: data_frame_price,
						lens_price		: data_lens_price,
						upgrade_price	: data_upgrade_price,
						lens_code		: data_lens_code,
						upgrade			: data_upgrade,
						vision			: data_vision,
						prescription_id	: data_pres_id,
						os_id			: data_specs_id,
						totalPrice		: data_total
					},
					success: function () {
						window.location = './?page=customer-examine&orderNo=' + data_order_id + '&profile_id=' + data_profile_id + '&comp=exam';
					}
				});
			});
		});

	}
	$('.language').on('click', function() {
		$(this).find('.lang-opt').toggle('fade');
	});


	// ========================== GUEST Login
	document.getElementById('guestForm').addEventListener('input', function() {
        var isFormValid = true;
        
        
        var lastname = document.getElementById('lastname').value.trim();
        var firstname = document.getElementById('firstname').value.trim();
        var gender = document.getElementById('gender').value;
        var ageRange = document.getElementById('age_range').value;

        if (!lastname || !firstname || !gender || !ageRange) {
            isFormValid = false;
        }

        
        document.getElementById('guest-submit').disabled = !isFormValid;
    });


	document.getElementById('guestForm').addEventListener('input', function() {
        var isFormValid = true;
        
        
        var lastname = document.getElementById('lastname').value.trim();
        var firstname = document.getElementById('firstname').value.trim();
        var gender = document.getElementById('gender').value;
        var ageRange = document.getElementById('age_range').value;

        if (!lastname || !firstname || !gender || !ageRange) {
            isFormValid = false;
        }

        
        document.getElementById('guest-submit').disabled = !isFormValid;
    });


	

	// ============================= Filters

	$(document).ready(function () {
		const filterActive = " <?= get_url('images/icons') ?>/icon-filter-active.png";
		const filterClose = " <?= get_url('images/icons') ?>/icon-close-white.png";
		const filterButton = document.getElementById('btn-filter')
		filterButton.innerHTML = `<img id="filter-active" src="${filterActive}" alt="Filter-active"
																																																								style="margin-left: 3px; margin-right: 6px; height: 24px; width: 24px;">Filter 
																																																								<a id="close-icon" href="/v2.0/sis/studios/v1.0/?page=<?= $_GET['page'] ?>"><img id="btn-icon-close" src="${filterClose}" alt="close"
																																																								style="margin-left: 8px; margin-right: 3px; height: 24px; width: 24px;"> </a>`
		filterButton.classList.add('filter-active')
	})
	const closeIcon = document.getElementById('close-icon');
	closeIcon.addEventListener('click', function (event) {
		filterButton.removeAttribute('id');
		event.preventDefault(); // Prevent the default link behavior
		// Your custom close action, e.g., remove the 'filter-active' class
		filterButton.classList.remove('filter-active');

		filterButton.innerHTML = 'Filter'; // Reset the button content or perform other actions
	});

});