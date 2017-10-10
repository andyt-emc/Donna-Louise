var w;

jQuery(document).ready(function() {
	w = getWindowWidth();

	/*******************************************
	 * HELPER FUNCTIONS *
	 *******************************************/

	/*
	 * Image lazy loading
	 * Further reading: https://www.appelsiini.net/projects/lazyload
	 * Example image tag:  <img class="lazy" data-original="img/example.jpg" width="640" height="480">
	 * Uncomment out the code below and in the index.php file to enable lazyloading
	 */

	// jQuery("img.lazy").lazyload({
	//     threshold : 200
	// });

	/*
	 * Check alt tags aren't empty for images
	 * For development use only
	 */

	// jQuery('img').each(function() {
	// 	console.log(jQuery(this).attr('src'), jQuery(this).attr('alt'));
	// });

	// swap inline png for SVG graphics// SVG / PNG
	if (!Modernizr.svg) {
		jQuery('img[src*="svg"]').attr('src', function () {
		return jQuery(this).attr('src').replace('.svg', '.png');
	  });
	}

	/*
	 * -----------------------------------------------------------------
	 * Make date dynamic
	 * -----------------------------------------------------------------
	 */

	 var d = new Date().getFullYear();
	 jQuery('#jDate').empty().append(d);


	/*******************************************
	 * RESPONSIVE NAV *
	 *******************************************/

	jQuery('.nav-btn').on('click', function() {
		jQuery('html').addClass('js-nav');
	});

	jQuery('.close-btn, .js-nav #innerwrap').on('click', function() {
		jQuery('html').removeClass('js-nav');
	});

	// add top menu to the main menu on phones (only with JS)
	if (jQuery('.js').length) {
		moveTopMenu();
	}

	/*******************************************
	 * Fixed header *
	 *******************************************/

	fixMenu();


	/*******************************************
	 * Tile Overlays - make them clickable *
	 *******************************************/

	 // jQuery('.tile-overlay').click(function() {
	 // 	var url = jQuery(this).find('a').attr('href');
	 // 	window.location = url;
	 // });

	/*******************************************
	 * Owl Carousel - latest news *
	 *******************************************/

	 // remove the three dots added by Joomla's truncate function
	jQuery('.latest-news .content-overlay').each(function() {
		var elem = jQuery(this);
		var newsOverlayContents = elem.html();
		 newsOverlayContents = newsOverlayContents.replace("...", "");
		 elem.empty().append(newsOverlayContents);
	})


	if (jQuery(".latest-news .latest-news-articles").length) {
		jQuery(".latest-news .latest-news-articles").owlCarousel({
			loop:false,
			margin:20,
			nav: true,
			dots: false,
			responsiveClass:true,
			responsive:{
				0:{
					items:1
				},
				520:{
					items:2
				},
				920:{
					items:3,
					margin: 28
				},
				1360:{
					margin: 35
				}
			}
		});
	}

	if (jQuery('#main .join-newsletter').length) {
		positionContentUnderNewsletterSignup();
	}

	/*******************************************
	 * Event Booking *
	 *******************************************/

	 // Remove main banner from events page
	 if (jQuery('#eb-event-page').length) {
		jQuery('#bannerwrap').hide();
	 }


	 // Fun Run 5k child check
	var totalAmount = jQuery('#total_amount').val();
	jQuery(document).on('focusout','input[name*="dob_5k"]', function() {
		// Compare race date against date of birth
		// if under 16 reduce cost by £5
		var totalAmended;
		var minimumAge 		= 17;
		var childDiscount	= 5;
		var childCount 		= 0;

		jQuery('input[name*="dob_5k"]').each(function() {
			var fieldVal 	= jQuery(this).val();
			var fieldDate 	= new Date(fieldVal);
			var raceDate	= new Date('05-01-2018'); // mm-dd-yyyy
			var age			= new Date(raceDate - fieldDate ).getFullYear() - 1970;

			if (age < minimumAge) {
				childCount++;
			}
		});

		// set new total amount
		totalAmended = totalAmount - (childDiscount * childCount);
		jQuery('#total_amount').val(totalAmended.toFixed(2));

	});

	/*******************************************
	* Donate Form update donation values *
	*******************************************/

	if (jQuery('.donate-type').length) {

		var minimumAmountGBP = 1.00;
		var oneTimeTargetURL = 'index.php/make-a-donation/donate';
		var monthlyTargetURL = 'https://secure.edirectdebit.com/The-Donna-Louise-Childrens-Hospice/donate/Desktop-Form-Page/';

		// reset the form
		// clear all fixed amount radio buttons
		jQuery('.fixed-amount input:radio').attr("checked", false);
		// clear any active fixed amount options
		jQuery('.fixed-amount').removeClass('active');
		// clear any custom amount value
		jQuery('#custom-amount').val('');

		// OK here we go...
		// watch for change in payment type
		jQuery('.donate-type').change(function(){
			var option = jQuery('.donate-type :selected').val();
			var imagePath = "/images/donate/";  
			switch(option) {
				case 'one-time':
					amount1 = 20;
					amount2 = 60;
					amount3 = 80;
					amount4 = 150;
					desc1 	= "Your gift could bring music and laughter to one of our children.";
					desc2 	= "Your gift could help our families express themselves when there are no words.";
					desc3 	= "Your donation could help parents learn how to have fun and interact with their child.";
					desc4 	= "Your donation could allow siblings to be around people who ‘get it’ at a sibling sleepover.";
					img1	= imagePath+"donate-once-"+amount1+".jpg";
					img2	= imagePath+"donate-once-"+amount2+".jpg";
					img3	= imagePath+"donate-once-"+amount3+".jpg";
					img4	= imagePath+"donate-once-"+amount4+".jpg";
					formTargetURL = oneTimeTargetURL;
					break;
				default:
					amount1 = 5;
					amount2 = 10;
					amount3 = 25;
					amount4 = 50;
					desc1 	= "Could allow a family to come together over one of our home-made cakes and a cup of tea.";
					desc2 	= "Could lighten the load for families by covering the cost of two volunteers to help around the home.";
					desc3 	= "Could help ensure that our skilled play team are always on hand to make the magic happen.";
					desc4 	= "Could comfort families during difficult times by helping provide emotional and bereavement support.";
					img1	= imagePath+"donate-monthly-"+amount1+".jpg";
					img2	= imagePath+"donate-monthly-"+amount2+".jpg";
					img3	= imagePath+"donate-monthly-"+amount3+".jpg";
					img4	= imagePath+"donate-monthly-"+amount4+".jpg";
					formTargetURL = monthlyTargetURL;
					break;
			}
			jQuery('.fixed-amount').removeClass('active');
			jQuery('.amount1').html(amount1); jQuery('#option1').val(amount1.toFixed(2));
			jQuery('.amount2').html(amount2); jQuery('#option2').val(amount2.toFixed(2));
			jQuery('.amount3').html(amount3); jQuery('#option3').val(amount3.toFixed(2));
			jQuery('.amount4').html(amount4); jQuery('#option4').val(amount4.toFixed(2));
			jQuery('#desc1').html(desc1);
			jQuery('#desc2').html(desc2);
			jQuery('#desc3').html(desc3);
			jQuery('#desc4').html(desc4);
			jQuery('#donate-1').attr('src', img1);
			jQuery('#donate-2').attr('src', img2);
			jQuery('#donate-3').attr('src', img3);
			jQuery('#donate-4').attr('src', img4);


			// update form action for payment type
			jQuery("#make-donation-form").attr("action", formTargetURL);
			// update form method?
			jQuery("#make-donation-form").attr("method", "GET");
		});
		// watch out for a click on FIXED donation amount box
		jQuery('.fixed-amount').on('click', function (evt) {
			evt.stopPropagation(); //evt.preventDefault();
			// remove active classes
			jQuery('.fixed-amount').removeClass('active');
			// add active class to clicked box
			jQuery(this).addClass('active');
			// select the hidden radio button
			jQuery(this).find('input').prop('checked', true);
			// disable the custom amount box
			jQuery("#custom-amount").prop('disabled', true);
			// submit the form
			jQuery("#make-donation-form").submit();
		});
		// watch out for a click on the CUSTOM donation amount
		jQuery('#custom-amount').focus(function () {
			// remove active classes from fixed amounts
			jQuery('.fixed-amount').removeClass('active');
			// uncheck all radio buttons
			jQuery('.fixed-amount input:radio').attr("checked", false);
		});
		// enable submit button only for CUSTOM donation over minimumAmountGBP
		jQuery('#custom-amount').keyup(function() {
			var submitButton = jQuery('.button');
			if ( jQuery(this).val() >= minimumAmountGBP ) {
				submitButton.prop('disabled', false);
			} else {
				submitButton.prop('disabled', true);
			}
		});
	}

	/***********************************************
	 * Donate Form (page 2) update donation values *
	 ***********************************************/

	if (jQuery('#os_form').length) {
		var minimumAmountGBP = 1.00;

		// watch out for a click on FIXED donation amount box
		jQuery('#amount_container label').on('click', function (evt) {
			evt.stopPropagation(); //evt.preventDefault();
			// remove active classes
			jQuery('#amount_container label').removeClass('active');
			// add active class to clicked box
			jQuery(this).addClass('active');
			// disable the custom amount box
			jQuery("input-prepend input").val('');
			// update the gift-aid amount
			donation = jQuery(this).find( "input" ).val();
			jQuery("#field_gift_aid .ga-amount").html(donation);
			jQuery("#field_gift_aid .ga-worth").html(getGiftAidTotal(donation).toFixed(2));
		});
		// watch out for a click on the CUSTOM donation amount
		jQuery('.input-prepend input').focus(function (evt) {
			// remove active classes from fixed amounts
			jQuery('#amount_container label').removeClass('active');
			// uncheck all radio buttons
			jQuery('#amount_container label input:radio').attr("checked", false);
		});
		// watch for CUSTOM amount changes
		jQuery('.input-prepend input').keyup(function() {
			donation = jQuery(this).val();
			if (donation < minimumAmountGBP) donation = minimumAmountGBP;
			jQuery("#field_gift_aid .ga-amount").html(donation);
			jQuery("#field_gift_aid .ga-worth").html(getGiftAidTotal(donation).toFixed(2));
		});
		function getGiftAidTotal(donation) {
			return donation * 1.25;
		}
		// show manual address fields button
		jQuery('.manual-address').on('click', function() {
			jQuery(this).css('display','none');
			jQuery('.find-address').hide();
			jQuery('.address-group').show();
		});

		// ie9 fix
		var browser = {
        isIe: function () {
            return navigator.appVersion.indexOf("MSIE") != -1;
        },
        navigator: navigator.appVersion,
        getVersion: function() {
            var version = 999; // we assume a sane browser
            if (navigator.appVersion.indexOf("MSIE") != -1)
                // bah, IE again, lets downgrade version number
                version = parseFloat(navigator.appVersion.split("MSIE")[1]);
            return version;
        }
    };

			if (browser.isIe() && browser.getVersion() <= 9) {
				jQuery('.find-address').hide();
				jQuery('.address-group').show();
			}

		// check for address request button click
		jQuery('.find-address').on('click', function() {
			// Get Postcode and house number from form
			console.log('Dave');
			var number   = jQuery('#house_id').val();
			var postcode = jQuery('#postcode').val().toUpperCase();
			if (number.length == 0 || postcode.length == 0) {
				jQuery('.lookup-error').show();
			} else {
				// get latitude & longitude
	  			jQuery.getJSON('http://maps.googleapis.com/maps/api/geocode/json?address=' + postcode + '&sensor=false', function(data, status) {
		    		if (status !== 'success') {
			    		jQuery('.lookup-error').show()
			    	} else {
			    		console.log(data.results[0]);
			    		var lat = data.results[0].geometry.location.lat;
			    		var lng = data.results[0].geometry.location.lng;
						jQuery.getJSON('http://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng + '&sensor=false', function(data) {
							if (data.results.length > 0) {
								address = data.results[0].address_components;
								console.log(address);
								var street, locality, town, county, country = '';
								jQuery.each(address, function( index, value ) {
									type = value.types[0];
									switch (type) {
										case 'route'                       : street   = value.long_name; break;
										case 'locality'                    : locality = value.long_name; break;
										case 'postal_town'                 : town     = value.long_name; break;
										case 'administrative_area_level_2' : county   = value.long_name; break;
										case 'country'                     : country  = value.long_name; break;
										case 'postal_code'                 : postcode = value.long_name; break;
									}
								});
								// elminate duplicate locality/town
								city = ( locality == town ? town : locality + ', ' + town);
								// address updates
								jQuery('#address').val(street);
								jQuery('#city').val(city);
								jQuery('#zip').val(postcode);
								jQuery('#county').val(county);
								jQuery('#country').val(country).change();
								// layout updates
								jQuery('.find-address').hide()
								jQuery('.manual-address').hide()
								jQuery('.lookup-error').hide()
								jQuery('.address-group').show();
							} else {
								jQuery('.lookup-error').show();
							}
						});
					}
				});
	  		}
		});
	}


});

jQuery(window).on('scroll', function() {
	fixMenu();
})

jQuery(window).resize(function() {
	w = getWindowWidth();

	// add top menu to the main menu on phones (only with JS)
	if (jQuery('.js').length) {
		moveTopMenu();
	}
	if (jQuery('#main .join-newsletter').length) {
		positionContentUnderNewsletterSignup();
	}
});

jQuery(window).load(function() {

	// CQC amends
	setTimeout(function() {
		jQuery('link[href="http://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/cleanslate.css"], link[href="http://www.cqc.org.uk/sites/all/modules/custom/cqc_widget/cqc-widget-styles.css"]').prop("disabled", true);
		jQuery('.cqc-widget-inner').html(function(index, html) {
			return html.replace('Donna Louise Trust', '<span class="cqc-org-title">Donna Louise Trust</span>');
		});

		// remove empty <p> tags
		jQuery('.cqc p').each(function() {
			if (jQuery(this).html() == "") {
				jQuery(this).remove();
			}
		});
	}, 2000);

	/*******************************************
	 * Totaliser - one in a million *
	 *******************************************/

	 if (jQuery('#totaliser-container').length) {
		var totaliser = jQuery('#totaliser-container');
		var totalRaised = totaliser.attr('data-raised');
		var scaleValue = totalRaised/1000;
		/* scale the total raised */
		totaliser.find('svg #raised-value').css('transform', 'scaleY(' + scaleValue + ')');
	 }

	 /*******************************************
	 * Count Down - one in a million *
	 *******************************************/

	 if (jQuery('.countdown-clock').length) {
		var clock;
		/* grab the current date */
		var currentDate = new Date();
		/* set the date for the campaign to finish */
		var finishDate = new Date("September 01 2017");
		/* calculate the difference in seconds */
		var diff = finishDate.getTime() / 1000 - currentDate.getTime() / 1000;
		/* initiate coutdown clock */
		clock = jQuery('.countdown-clock').FlipClock({
			clockFace: 'DailyCounter',
			autoStart: false,
			showSeconds: false
		});
		/* set time to count down to and other settings */
		clock.setTime(diff);
		clock.setCountdown(true);
		clock.start();
	 }

});

	/*******************************************
	 * Position article content below newsletter banner *
	 * Newsletter banner is positioned absolute *
	 *******************************************/

function positionContentUnderNewsletterSignup() {
	 var nbHeight = jQuery('#main .join-newsletter').outerHeight();
	 jQuery('#main .join-newsletter').next().css('margin-top', nbHeight+ (6*16));
}

function moveTopMenu() {
	if (w < 920) {
		// get <li> form top menu
		jQuery('.top-menu ul.nav').appendTo('#menu .moduletable').addClass('moved');
	} else if (w >= 920) {
		jQuery('#menu ul.moved').appendTo('.top-menu').removeClass('moved');
	}
}

function fixMenu() {
	var fixedPos = 50;
	if (jQuery(window).scrollTop() >= 50) {
		jQuery('#navwrap').addClass('fixed');
	} else {
		jQuery('#navwrap').removeClass('fixed');
	}
}

function getWindowWidth() {
	return window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
}
