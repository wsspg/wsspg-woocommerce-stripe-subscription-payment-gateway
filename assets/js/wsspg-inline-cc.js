(function( $ ) {
	'use strict';
	
	/**
	 * Wsspg Inline JS
	 * 
	 * Handles the frontend inline credit card form: tokenizes the
	 * customer's payment details on the client side.
	 *
	 * @since  1.0.0
	 */
	var wsspg = {
		
		/**
		 * Declare and define references and properties.
		 *
		 * @since  1.0.0
		 */
		form: $( "form.checkout, form#order_review, form#add_payment_method" ),
		pkey: $( '#wsspg-cc-fieldset' ).data( 'pkey' ),
		
		/**
		 * Init
		 *
		 * Bind events, append input fields.
		 *
		 * @since  1.0.0
		 */
		init: function() {
			
			$( wsspg.form )
				.on( 'click', '#place_order', this.on_click )
				.append( "<input type='hidden' name='wsspg-data' value='' />" );
				
		},
		
		/**
		 * Fires when the place order button is clicked.
		 * 
		 * Pre-empts form submission.
		 *
		 * Validates fields, checks for tokens and/or errors.
		 *
		 * Returns false to prevent submission until there is something to submit,
		 * then returns true, and the checkout form is submitted to the server.
		 *
		 * @since  1.0.0
		 * @param  event
		 */
		on_click: function( e ) {
			
			//	get the method.
			//	
			var payment_method = $('input[name=payment_method]:checked').val();
			
			//	if Wsspg is not the selected method, return true and
			//	bypass this method.
			//	
			if( payment_method !== 'wsspg' ) {
				
				return true;
				
			} else {
				
				//	check for saved payment methods.
				//	
				var method = $('input[name="wc-wsspg-payment-token"]:checked').val();
				
				//	if the saved method exists and is not new, submit the method ID.
				//	
				if( method && method !== 'new' ) {
					
					var data = {
					
						method:  "saved",
						token:   method,
						save:    null,
						error:   null
						
					};
					
					//	stringify the JSON object and store it in an input field for submission.
					//	
					$( 'input[name=wsspg-data]' ).val( JSON.stringify( data, null ) );
					
					//	submit the form.
					//	
					wsspg.form.submit();
					
				} else {
				
					//	the default action of the event will not be triggered.
					//	
					e.preventDefault();
					
					//	if Stripe.js is available.
					//	
					if( typeof Stripe === 'function' ) {
					
						Stripe.setPublishableKey( wsspg.pkey );
					
						Stripe.card.createToken({
					
							number:    $('#wsspg-cc-number').val(),
							cvc:       $('#wsspg-cc-cvc').val(),
							exp_month: $('#wsspg-cc-exp-month').val(),
							exp_year:  $('#wsspg-cc-exp-year').val(),
							name: $('#billing_first_name').val() + ' ' + 
								$('#billing_last_name').val() + ' <' + 
								$('#billing_email').val() + '>',
							address_line1:    $('#billing_address_1').val(),
							address_line2:    $('#billing_address_2').val(),
							address_city:     $('#billing_city').val(),
							address_state:    $('#billing_state').val(),
							address_zip:      $('#billing_postcode').val(),
							address_country:  $('#billing_country').val(),
					
						}, wsspg.handle_response );
					
						//	prevent form submission.
						//	
						return false;
						
					} else {
					
						var data = {
							method:  null,
							token:   null,
							save:    null,
							error:   "Error processing checkout. Please try again."
						};
						
						//	stringify the JSON object and store it in an input field for submission.
						//	
						$( 'input[name=wsspg-data]' ).val( JSON.stringify( data, null ) );
					
						//	submit the form.
						//	
						wsspg.form.submit();
					
					}
				
				}
				
			}
			
		},
		
		/**
		 * Handle the response.
		 *
		 * @since  1.0.0
		 * @param  status http status code
		 * @param  response
		 */
		handle_response: function( status, response ) {
			
			var data = {
			
				method:  "inline",
				token:   null,
				save:    $( '#wc-wsspg-new-payment-method' ).is(":checked"),
				error:   null
				
			};
			
			if( response.error ) {
				
				data.error = response.error.message;
				
			} else {
				
				data.token = response;
				
			}
			
			//	stringify the JSON object and store it in an input field for submission.
			//	
			$( 'input[name=wsspg-data]' ).val( JSON.stringify( data, null ) );
					
			//	submit the form.
			//	
			wsspg.form.submit();
			
		},
		
	};
	
	wsspg.init();
	
})( jQuery );
