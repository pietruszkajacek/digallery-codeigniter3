DIGALLERY = $.extend(true, (typeof DIGALLERY === 'undefined') ? {} : DIGALLERY, {
	
	gallery: {
		
		view: function () {
			DIGALLERY.common.init_ajax_signin_form_submit();
			DIGALLERY.comments.comments_init( DIGALLERY.comments.add_gallery_comment );
			
			$('#delete-image-confirm-modal').on('show', DIGALLERY.common.modal_center);
			$('#stop18-confirm-modal').on('show', DIGALLERY.common.modal_center);
			$('#stop18-confirm-btn').click(DIGALLERY.common.set_maturity_user);
			
			var number_li = $('.jcarousel ul.thumbnails li').length;

			if (number_li < 9)
			{
				ul_width = number_li * 63 - 8;

				$('.jcarousel')
						.css('width', ul_width + 'px');
			}
			
			$('.jcarousel').jcarousel({
				start: parseInt($('.jcarousel').attr('data-jcar-start')) - 4,
				scroll: 3,
				buttonNextHTML: null,
				buttonPrevHTML: null,
				initCallback: DIGALLERY.gallery._init_carousel,
				buttonPrevCallback: function (carousel, button, enabled) {
					enabled ? $("div.jcarousel-prev-horizontal").removeClass("disabled") : $("div.jcarousel-prev-horizontal").addClass("disabled");
				},
				buttonNextCallback: function (carousel, button, enabled) {
					enabled ? $("div.jcarousel-next-horizontal").removeClass("disabled") : $("div.jcarousel-next-horizontal").addClass("disabled");
				}

			});

			$('#delete-image-btn').click(function (event) {
				var gallery_id = ((new String(document.location)).split('/'))[5];

				$.get("/gallery/soft_delete_gallery/" + gallery_id, function (data) {
					if (data.status == 1)
					{
						$('#delete-image-confirm-modal').one('hidden', function () {
							window.location = "/";
						});

						$('#delete-image-confirm-modal').modal('hide');
					}
					else
					{
						alert('Wystąpił jakiś błąd. Nie można usunąć galerii.')
					}
				}, "json");

				event.preventDefault();
			});
		},
		
		_init_carousel: function ( carousel ) {
			$('#jcarousel-next').bind('click', function () {
				carousel.next();
				return false;
			});

			$('#jcarousel-prev').bind('click', function () {
				carousel.prev();
				return false;
			});
		}
	}
});