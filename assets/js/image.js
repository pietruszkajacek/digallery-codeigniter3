DIGALLERY = $.extend( true, (typeof DIGALLERY === 'undefined') ? { } : DIGALLERY, {
	image: {
		preview: function () {
			
			$.ajaxSetup( {cache: false} );

			comments_init( DIGALLERY.comments.add_image_comment );
			init_ajax_signin_form_submit();

			$( '#delete-image-confirm-modal' ).on( 'show', modal_center );
			$( '#who-favorites-modal' ).on( 'show', modal_center );
			$( '#stop18-confirm-modal' ).on( 'show', modal_center );

			$( '#who-added-to-favorites' ).click( show_favorites_modal );

			$( '#favs-btn' ).click( function ( event ) {
				var image_id = ((new String( document.location )).split( '/' ))[5];

				$.get( "/image/add_remove_favorite/" + image_id, function ( data ) {
					if ( data.status == 1 )
					{
						var icon,
								title;

						if ( data.added == 1 )
						{
							icon = 'icon-star-empty';
							title = '...usuń z ulubionych.';
						}
						else if ( data.added == 0 )
						{
							icon = 'icon-star';
							title = '...dodaj do ulubionych.';
						}

						$( "#icon-favs-btn" ).attr( 'class', icon );
						$( '#favs-btn' ).attr( 'title', title );
						$( '#number-favs' ).empty().append( data.number_favs > 0 ? data.number_favs + ' [ ' + '<a id="who-added-to-favorites" href="#">kto?</a>' + ' ]' : '0' );
						$( '#who-added-to-favorites' ).click( show_favorites_modal );
					}
					else
					{
						$( "#favs-btn" ).addClass( "disabled" );
					}
				}, "json" );

				event.preventDefault();
			} );

			$( '#delete-image-btn' ).click( function ( event ) {
				var image_id = ((new String( document.location )).split( '/' ))[5];

				$.get( "/image/soft_delete_image/" + image_id, function ( data ) {
					if ( data.status == 1 )
					{
						$( '#delete-image-confirm-modal' ).one( 'hidden', function () {
							window.location = "/";
						} );

						$( '#delete-image-confirm-modal' ).modal( 'hide' );
					}
				}, "json" );

				event.preventDefault();
			} );

			$( '#stop18-confirm-btn' ).click( set_maturity_user );
		},
		show_favorites_modal: function ( event ) {
			
			alert(( new String( document.location ) ).split( '/' )[5]);
			
			$.get( '/image/who_add_favorites/' + ( new String( document.location ) ).split( '/' )[5], function ( data ) {
				$( '.modal-body', $( '#who-favorites-modal' ) ).html( data );
				$( '#who-favorites-modal' ).modal( 'show' );
			} );
			event.preventDefault();
		}

	}
} );