DIGALLERY = $.extend( true, (typeof DIGALLERY === 'undefined') ? {} : DIGALLERY, {
	
	comments: {
		
		add_gallery_comment: function ( event ) {
			if ( $( '#send-comment-textarea' ).val() != '' )
			{
				$.ajax( {
					cache: false,
					url: '/comments/add_gallery_comment/' + ((new String( document.location )).split( '/' ))[5],
					type: 'POST',
					data: {
						comment: $( '#send-comment-textarea' ).val(),
						rate: $( '#object-rate' ).length !== 0 ? $( "select option:selected" ).val() : 0
					},
					success: function ( data ) {
						if ( data.status == 1 )
						{
							split_url = (new String( document.location )).split( '/' );
							window.location = "/gallery/view/" + split_url[5] + (split_url[6] != undefined ? '/' + split_url[6] : '');
						}
						else if ( data.status == 0 )
						{
							alert( 'Nie udało się dodać komentarza...' )
						}
					},
					error: function ( data ) {

					}
				} );
			}
			else
			{
				alert( 'Komentarz musi zawierać treść!' );
			}
			event.preventDefault();
		},
		
		add_profile_comment: function ( event ) {
			if ( $( '#send-comment-textarea' ).val() != '' )
			{
				$.ajax( {
					cache: false,
					url: '/comments/add_profile_comment/' + ((new String( document.location )).split( '/' ))[4],
					type: 'POST',
					data: {
						comment: $( '#send-comment-textarea' ).val()
					},
					success: function ( data ) {
						if ( data.status == 1 )
						{
							window.location = '/profile/' + ((new String( document.location )).split( '/' ))[4];
						}
						else if ( data.status == 0 )
						{
							alert( 'Nie udało się dodać komentarza...' )
						}
					},
					error: function ( data ) {

					}
				} );
			}
			else
			{
				alert( 'Komentarz musi zawierać treść!' );
			}
			event.preventDefault();
		},
		
		add_image_comment: function ( event ) {
			if ( $( '#send-comment-textarea' ).val() != '' )
			{
				$.ajax( {
					cache: false,
					url: '/comments/add_image_comment/' + ((new String( document.location )).split( '/' ))[5],
					type: 'POST',
					data: {
						comment: $( '#send-comment-textarea' ).val(),
						rate: $( '#object-rate' ).length !== 0 ? $( "select option:selected" ).val() : 0
					},
					success: function ( data ) {
						if ( data.status === 1 )
						{
							window.location = '/image/preview/' + ((new String( document.location )).split( '/' ))[5];
						}
						else if ( data.status === 0 )
						{
							alert( 'Nie udało się dodać komentarza...' );
						}
					},
					error: function ( data ) {

					}
				} );

			}
			else
			{
				alert( 'Komentarz musi zawierać treść!' );
			}
			event.preventDefault();
		},
		
		delete_comment: function ( event ) {
			var comment_id = $( this ).parents( 'div.comment' ).attr( 'id' );

			$( '#delete-comment-btn' ).off().on( 'click', function ( event ) {
				$.ajax( {
					cache: false,
					url: '/comments/delete_comment/' + comment_id,
					type: 'POST',
					success: function ( data ) {
						if ( data.status == 1 )
						{
							$( '#delete-comment-confirm-modal' ).one( 'hidden', function ( evt ) {
								window.location = window.location;
							} );

							$( '#delete-comment-confirm-modal' ).modal( 'hide' );
						}
						else if ( data.status == 0 )
						{
							alert( 'Niestety nie udało się usunąć komentarza...' );
						}
					},
					error: function ( data ) {

					}
				} );
				event.preventDefault();
			} );

			$( '#delete-comment-confirm-modal' ).modal( 'show' );

			event.preventDefault();
		},
		
		edit_comment: function ( event ) {
			var $comment_div = $( this ).parents( 'div.comment' );
			var comment_id = $comment_div.attr( 'id' );
			var $a_comment = $( '#comment_' + comment_id, $comment_div );

			$.ajax({
				cache: false,
				url: '/comments/get_comment/' + comment_id,
				success: function (data)
				{
					$('textarea', '#edit-comment-modal').val(data.comment);

					$('#save-comment-btn').off().on('click', function (event) 
					{
						if ($('textarea', '#edit-comment-modal').val() != '')
						{
							if ($('textarea', '#edit-comment-modal').val() === data.comment)
							{
								$('#edit-comment-modal').modal('hide');
							}
							else
							{
								$.ajax({
									cache: false,
									url: '/comments/edit_comment/' + comment_id,
									data: {comment: $('textarea', '#edit-comment-modal').val()},
									type: 'POST',
									success: function (data)
									{
										if (data.status == 1)
										{
											$('#edit-comment-modal').one('hidden', function (evt)
											{
												$a_comment.html(data.comment);
												$('em', $comment_div).html('Ostatnia zmiana: ' + data.last_edit);
											});

											$('#edit-comment-modal').modal('hide');
										}
										else if (data.status == 0)
										{
											alert('Nie udało się zaktualizować komentarza...');
										}
									},
									error: function (data)
									{
										alert('Błąd...')
									}
								});
							}
						}
						else
						{
							alert('Komentarz musi zawierać treść!');
						}

						event.preventDefault();
					});

					$('#edit-comment-modal').modal('show');
				},
				error: function ( data )
				{
					alert( 'Błąd...' )
				}
			} );

			event.preventDefault();
		},
		
		open_signin_dropdown: function ( event ) {
			$( '#login-drop-menu' ).addClass( "open" );
			$( '#email' ).focus();

			event.stopPropagation();
			event.preventDefault();
		},
		
		comments_init: function ( commenting_function_name ) {
			$( '#delete-comment-confirm-modal' ).on( 'show', DIGALLERY.common.modal_center );
			$( '#edit-comment-modal' ).on( 'show', DIGALLERY.common.modal_center );

			$( '#send-comment-btn' ).click( commenting_function_name );
			$( '.btn-comment-trash' ).click( DIGALLERY.comments.delete_comment );
			$( '.btn-comment-edit' ).click( DIGALLERY.comments.edit_comment );

			$( '#login-comment-btn' ).click( DIGALLERY.comments.open_signin_dropdown );
		}
	}
} );