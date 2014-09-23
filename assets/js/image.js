DIGALLERY = $.extend(true, (typeof DIGALLERY === 'undefined') ? {} : DIGALLERY, {
	image: {
		init: function () {
			DIGALLERY.common.init_ajax_signin_form_submit();
			$('#stop18-confirm-modal').on('show', DIGALLERY.common.modal_center);
			$('#stop18-confirm-btn').click(DIGALLERY.common.set_maturity_user);
		},
		preview: function () {
			$.ajaxSetup({cache: false});

			DIGALLERY.comments.comments_init(DIGALLERY.comments.add_image_comment);

			$('#delete-image-confirm-modal').on('show', DIGALLERY.common.modal_center);
			$('#who-favorites-modal').on('show', DIGALLERY.common.modal_center);

			$(document).on("click", "#who-added-to-favorites", DIGALLERY.image.show_favorites_modal);

			$('#favs-btn').click(function (event) {
				var image_id = ((new String(document.location)).split('/'))[5];

				$.get("/image/add_remove_favorite/" + image_id, function (data) {
					if (data.status == 1)
					{
						var icon,
								title;

						if (data.added == 1)
						{
							icon = 'icon-star-empty';
							title = '...usuń z ulubionych.';
						}
						else if (data.added == 0)
						{
							icon = 'icon-star';
							title = '...dodaj do ulubionych.';
						}

						$("#icon-favs-btn").attr('class', icon);
						$('#favs-btn').attr('title', title);
						$('#number-favs').empty().append(data.number_favs > 0 ? data.number_favs + ' [ ' + '<a id="who-added-to-favorites" href="#">kto?</a>' + ' ]' : '0');
					}
					else
					{
						$("#favs-btn").addClass("disabled");
					}
				}, "json");

				event.preventDefault();
			});
			
			// Usuwanie pracy...
			$('#delete-image-btn').click(function (event) {
				var image_id = ((new String(document.location)).split('/'))[5];

				$.get("/image/soft_delete_image/" + image_id, function (data) {
					if (data.status == 1)
					{
						$('#delete-image-confirm-modal').one('hidden', function () {
							window.location = "/";
						});

						$('#delete-image-confirm-modal').modal('hide');
					}
					else
					{
						//DIGALLERY.common.clean_alerts();
						//DIGALLERY.common.alert(".span11", 'Błąd!', 'Niestety nie można usunąć pracy.', 'error');
						$('#delete-image-confirm-modal').modal('hide');
					}
				}, "json");

				event.preventDefault();
			});
		},
		zoom: function () {
			var container_width = 937,
					$img = $("#img-full-view"),
					$img_clip = $("#img-clip"),
					img_natural_width = $img.attr('width');

			if ($img.length && img_natural_width > container_width)
			{
				$img.addClass('container-view');
				$img.click( function () {

					if ($img_clip.width() > container_width)
					{
						$img_clip.width(container_width);
						$img.removeClass('full-view')
								.addClass('container-view');
					}
					else
					{
						$img_clip.width(img_natural_width);
						$img.removeClass('container-view')
								.addClass('full-view');
					}
				});
			}
			
		},
		show_favorites_modal: function (event) {
			$.get('/image/who_add_favorites/' + (new String(document.location)).split('/')[5], function (data) {
				$('.modal-body', $('#who-favorites-modal')).html(data);
				$('#who-favorites-modal').modal('show');
			});
			event.preventDefault();
		}
	}
});