
$(document).ready(function () {
	
	var container_width = 937,
		$img = $( "#img-full-view" ),
		$img_clip = $( "#img-clip" ),
		img_natural_width = $img.attr( 'width' );
		
	init_ajax_signin_form_submit();

	if ( $img.length && img_natural_width > container_width )
	{
		$img.addClass( 'container-view' );
		$img.click( function () {
		
			if ( $img_clip.width() > container_width )
			{	
				$img_clip.width( container_width );
				$img.removeClass( 'full-view' )
					.addClass( 'container-view' );
			}
			else
			{
				$img_clip.width( img_natural_width );
				$img.removeClass( 'container-view' )
					.addClass( 'full-view' );
			}
		});
	}
	else
	{
		$('#stop18-confirm-modal').on('show', modal_center);
		$('#stop18-confirm-btn').click(set_maturity_user);
	}
});