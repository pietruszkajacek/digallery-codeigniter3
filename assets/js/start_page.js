
$(document).ready( function () {
	//$( 'input' ).placeholder();
	$( "#register-form" ).on( 'submit.ajax', register_ajax );
} );

function disable_register_form() {
	var
		$form = $( "#register-form" ),
		$button_submit = $( 'button', $form );
		
	$form.off( 'submit.ajax' );
	//$form.submit( function() { return false; } );
	$form.on( 'submit.ajax', function() { return false; })
	$button_submit.addClass( 'disabled' );
}

function enable_register_form() {
	var
		$form = $( "#register-form" ),
		$button_submit = $( 'button', $form );
		
	$form.off( 'submit.ajax' );
	//$form.submit( register_ajax );
	$form.on( 'submit.ajax', register_ajax );
	$button_submit.removeClass( 'disabled' );
}

function register_ajax( event ) {
	/* stop form from submitting normally */
	event.preventDefault();

	disable_register_form();

	var $form = $( this ),
			url = '/user/register_ajax/',
			$csrf_hidden = $( 'input:hidden', $form ),
			csrf_key = $csrf_hidden.attr( 'name' ),
			csrf_value = $csrf_hidden.attr('value');

	$csrf_hidden
			.attr( 'name', csrf_value )
			.attr( 'value', csrf_key );

	$.post( url, $form.serialize(), function ( data ) {
		if ( data.status == 1 )
		{
			window.location = '/browse/images/'; //window.location; //document.location;
		}
		else if ( data.status == 0 )
		{
			$button_submit = $( 'button', $form );

			//$button_submit.popover( 'destroy' );

			$button_submit.popover( {
				trigger: 'manual',
				placement: 'right',
				content: data.msg,
				html: true,
				title: data.error_type === 'register' ? 'Błąd rejestracji.' : 'Błąd walidacji danych.',
				delay: { show: 500, hide: 100 }
			} );

			$button_submit.popover( 'show' );

			$csrf_hidden
					.attr( 'name', Object.keys( data.csrf_key )[0] )
					.attr( 'value', data.csrf_key[Object.keys( data.csrf_key )[0]] );

			$( 'input', '.jumbotron' ).focus( function () {
				//$button_submit.popover( 'hide' );
				$button_submit.popover( 'destroy' );
			} );

			enable_register_form();
		}
	}, "json" )
	.fail( function () { window.location = '/user/register/'; } );
}
