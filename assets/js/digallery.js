//			var output = '';
//			for ( var property in this ) {
//				output += property + ': ' + this[property] + '; ';
//			}
//			alert( output );

DIGALLERY = $.extend( true, (typeof DIGALLERY === 'undefined') ? { } : DIGALLERY, {
	
	common: {
	
		init: function () {
			//alert( 'OK!' );
		},
		
		modal_center: function ( event ) {
			var modal = $( this );

			modal.css( 'margin-top', - (modal.outerHeight() / 2) )
					.css( 'margin-left', - (modal.outerWidth() / 2) );

			return this;
		},
		
		set_maturity_user: function ( event ) {
			event.preventDefault();

			$.ajax( {
				cache: false,
				url: '/image/stop18_confirm/',
				type: 'POST',
				success: function ( data ) {
					if ( data.status == 1 )
					{
						$( '#stop18-confirm-modal' ).one( 'hidden', function ( evt ) {
							window.location = window.location;
						} );

						$( '#stop18-confirm-modal' ).modal( 'hide' );
					}
					else
					{
						alert( 'Niestety nie udało się potwierdzić...' );
						$( '#stop18-confirm-modal' ).modal( 'hide' );
					}
				},
				error: function ( data ) {
					alert( 'Niestety nie udało się potwierdzić...' );
					$( '#stop18-confirm-modal' ).modal( 'hide' );
				}
			} );
		},

		init_ajax_signin_form_submit: function () {
			$( "#signin-form" ).submit( function ( event ) {

				/* stop form from submitting normally */
				event.preventDefault();

				var $form = $( this ),
						url = $form.attr( 'action' );

				$.post( url, $form.serialize(), function ( data ) {
					if ( data.status == 1 )
					{
						window.location = window.location; //document.location;
					}
					else if ( data.status == 0 )
					{
						$email_input = $( '#email', $( '#signin-dropdown' ) );
						$email_input.popover( 'destroy' );
						$email_input.popover( {
							trigger: 'manual',
							placement: 'left',
							content: data.msg,
							html: true,
							title: data.error_type === 'login' ? 'Błąd logowania.' : 'Błąd walidacji danych.',
							delay: { show: 500, hide: 100 }
						} );

						$email_input.popover( 'show' );
					}
				}, "json" );
			} );
		}
	}
} );
		
UTIL = {
	exec: function ( controller, action ) {
		var ns = DIGALLERY,
				action = (action === undefined) ? "init" : action;

		if ( controller !== "" && ns[controller] && typeof ns[controller][action] == "function" ) {
			ns[controller][action]();
		}
	},
	init: function () {
		var body = document.body,
				controller = body.getAttribute( "data-controller" ),
				action = body.getAttribute( "data-action" );

		UTIL.exec( "common" );
		UTIL.exec( controller );
		UTIL.exec( controller, action );
	}
};

$( document ).ready( UTIL.init );