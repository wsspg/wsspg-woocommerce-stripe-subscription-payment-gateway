(function( $ ) {
	'use strict';

	/**
	 * Wsspg Checkout JS
	 *
	 * Handles the frontend checkout: validates input, tokenizes the
	 * customer's payment details on the client side, submits status
	 * codes, errors, and/or tokens to the server.
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
		data: $( '#wsspg-data' ),

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
				.on( 'submit checkout_place_order_stripe' )
				.append( "<input type='hidden' name='wsspg-data' value='' />" );

		},

		/**
		 * On Click
		 *
		 * If Wsspg is the selected payment method, check whether a saved
		 * method is selected, otherwise open the Stripe Checkout modal.
		 *
		 * Update the form with our data and submit to the server.
		 *
		 * @since  1.0.0
		 */
		on_click: function( e ) {

			//	get the payment method.
			//
			var payment_method = $('input[name=payment_method]:checked').val();

			//	if Wsspg is not the selected method, return true and
			//	bypass this method.
			//
			if( payment_method !== 'wsspg' ) {

				return true;

			} else {

				//	if any required fields are empty, return true and checkout will
				//	let the user know about them.
				//
				var $required = $( '.validate-required' );
				var $valid = true;
				$required.each( function() {
					if( $( this ).parent().css("display") != 'none' ) {
						if( $( this ).find( 'input.input-text, select' ).val() === '' ) $valid = false;
					}
				});
				if( ! $valid ) return true;


				//	check for saved payment methods.
				//
				var method = $('input[name="wc-wsspg-payment-token"]:checked').val();

				//	if the method is not new, submit the method ID.
				//
				if( method !== 'new' ) {

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

					// return false;

				} else {

					//	the default action of the event will not be triggered.
					//
					e.preventDefault();

					var handler = StripeCheckout.configure({

						key:                 wsspg.data.data('key'),
						name:                wsspg.data.data('name'),
						email:               $( '#billing_email' ).val(),
						amount:              wsspg.data.data('amount'),
						currency:            wsspg.data.data('currency'),
						panelLabel:          wsspg.data.data('label'),
						image:               wsspg.data.data('image'),
						locale:              wsspg.data.data('locale'),
						bitcoin:             wsspg.data.data('bitcoin'),
						allowRememberMe:     wsspg.data.data('remember-me'),
						alipay:              wsspg.data.data('alipay'),

						//	this callback is invoked when the Checkout process is complete.
						//
						token: function( token, args ) {

							var data = {
								method:  "checkout",
								token:   token,
								save:    $( '#wc-wsspg-new-payment-method' ).is(":checked"),
								error:   null
							};

							//	stringify the JSON object and store it in an input field for submission.
							//
							$( 'input[name=wsspg-data]' ).val( JSON.stringify( data, null ) );

							console.log( data ) ;

							//	submit the form.
							//
							wsspg.form.submit();

							// return false;

						}

					});

					handler.open();

					return false;

				}

			}

		},

	};

	wsspg.init();

})( jQuery );
