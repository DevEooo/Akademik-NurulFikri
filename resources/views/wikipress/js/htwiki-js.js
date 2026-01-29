//
// Main JS File
//

// Start Wrapper
jQuery(document).ready(function ($) {
	//$(window).on('load', function() {
	console.log('doing nav js');

	// Header User Info Menu
	$('#wkp-userinfo').click(function () {
		var wkp_userinfo_state = $(this).attr('data-nav-state');

		if (wkp_userinfo_state == 'inactive') {
			$(this).attr('data-nav-state', 'active');
		} else {
			$(this).attr('data-nav-state', 'inactive');
		}
	});

	// Header Navigation Menu
	$('#wkp-mainnav__mobile').click(function () {
		var wkp_mainnav_state = $(this).parent().attr('data-nav-state');

		if (wkp_mainnav_state == 'inactive') {
			$(this).parent().attr('data-nav-state', 'active');
		} else {
			$(this).parent().attr('data-nav-state', 'inactive');
		}
	});

	// Meny Category Hide/Show
	//$('#wkp-mainnav ul li span').click(function () {
	//
	//	var wkp_mainnav_li_state = $(this).parent().attr('data-nav-state');
	//	
	//	if (wkp_mainnav_li_state == 'inactive') {
	//		$(this).parent().attr('data-nav-state', 'active');
	//	} else {
	//		$(this).parent().attr('data-nav-state', 'inactive');
	//	}
	//});


	// AJAX Login Form
	$('form#wkp-login').on('submit', function(e){
		$('form#wkp-login .wkp-formstatus').removeClass( 'wkp-formstatus--failed wkp-formstatus--success' ).show().text(ajax_login_object.loadingmessage);
		$('form#wkp-login .wkp-formsubmit').addClass('wkp-formsubmit--loading').prop('disabled', true);
		$.ajax({
			type: 'POST',
			dataType: 'json',
			url: ajax_login_object.ajaxurl,
			data: { 
				'action': 'ajaxlogin', //calls wp_ajax_nopriv_ajaxlogin
				'email': $('form#wkp-login [name=email]').val(), 
				'password': $('form#wkp-login [name=password]').val(), 
				'security': $('form#wkp-login [name=security]').val() },
				success: function(data){
					$('form#wkp-login .wkp-formstatus').addClass( 'wkp-formstatus--failed').text(data.message);
					$('form#wkp-login .wkp-formsubmit').removeClass('wkp-formsubmit--loading').prop('disabled', false);
					if (data.loggedin === true){
						$('form#wkp-login .wkp-formstatus').addClass( 'wkp-form__status--success');
						var redirect = $("form#wkp-login [name=redirect_to]").val();
						if(redirect){
							document.location.href = redirect;
						} else {
							document.location.href = ajax_login_object.redirecturl;	 
						}
					}
				}
		  	});
			e.preventDefault();
		});
	//});

});

// Inital load
jQuery( window ).load( function () {
	WKP_setup_gutenbergheight();
  WKP_dropdown_setup();
} ); 


// Window resize
jQuery( window ).resize( function() {
	WKP_setup_gutenbergheight();
  WKP_dropdown_setup();
} );

function WKP_setup_gutenbergheight() {

	//default
	var wkp_article_height = 500;

	// Get element heights
	if (jQuery(window).width() < 700) {
  	  wkp_article_height = 500;
	}
	else {
	  wkp_article_height = jQuery('.wkp-main').outerHeight(true);
	}
	
	// Set positioning for mainnav dropdown
	jQuery('.gutenberg' ).attr( 'style', 'height: ' + wkp_article_height + 'px' );

}

function WKP_dropdown_setup() {

	// Get element heights
	var wkp_header_height = jQuery('.wkp-header').outerHeight(true);

	// Set positioning for mainnav dropdown
	jQuery('#wkp-mainnav > ul' ).attr( 'style', 'top: ' + wkp_header_height + 'px' );

}

//
// Form Validation
//
//Get all the inputs...
const inputs2 = document.querySelectorAll('input, select, textarea');

// Loop through them...
for(let input2 of inputs2) {
	/* jshint ignore:start */
	// Just before submit, the invalid event will fire, let's apply our class there.
	input2.addEventListener('invalid', function(e) {
		input2.classList.add('error');    
	}, false);

	// Optional: Check validity onblur
	input2.addEventListener('blur', function(e) {
		input2.checkValidity();
	});
	/* jshint ignore:end */

}

const validationErrorClass = 'validation-error';
const parentErrorClass = 'has-validation-error';
const inputs = document.querySelectorAll('input, select, textarea');
inputs.forEach(function (input) {
	function checkValidity (options) {
		const insertError = options.insertError;
		const parent = input.parentNode;
		const error = parent.querySelector(`.${validationErrorClass}`) || document.createElement('div');

		if (!input.validity.valid && input.validationMessage) {
			error.className = validationErrorClass;
			error.textContent = input.validationMessage;

			if (insertError) {
				parent.insertBefore(error, input);
				parent.classList.add(parentErrorClass);
			}
		} else {
			parent.classList.remove(parentErrorClass);
			error.remove();
		}
	}
	input.addEventListener('input', function () {
		// We can only update the error or hide it on input.
		// Otherwise it will show when typing.
		checkValidity({insertError: false});
	});
	input.addEventListener('invalid', function (e) {
		// prevent showing the default display
		e.preventDefault();
		// We can also create the error in invalid.
		checkValidity({insertError: true});
	});
});