DIGALLERY = $.extend(true, (typeof DIGALLERY === 'undefined') ? {} : DIGALLERY, {
	
	profile: {
		start_drag: false,
		sortable_receive:  false,
		disable_thumb_allowed: false,
		remove_thumb_allowed: false,
		
		index: function () {
			DIGALLERY.common.init_ajax_signin_form_submit();
			DIGALLERY.comments.comments_init( DIGALLERY.comments.add_profile_comment );
		},
		
		submit: function () {
			// Wyśrodkowanie okna dialogowego
			$('#select-categories-modal').on('show', DIGALLERY.common.modal_center);
			
			// Pobranie kategorii
			$.post("/profile/get_categories_ajax/", function (data) {
				var $input_cat_id = $('input[name="category_id"]'),
						cat_id = $input_cat_id.val();

				$("#wrapper-categories").select_category({
					categories: data,
					category_id: (cat_id !== '' ? cat_id : null),
					change: function (e, ui) {
						if (ui.leaf)
							$("#select-btn").removeClass("disabled");
						else
							$("#select-btn").addClass("disabled");
					}
				});

				$('#select-btn').click(function (e) {
					if ($("#select-btn").hasClass('disabled'))
						e.preventDefault();
					else {
						$input_cat_id.attr("value", $("#wrapper-categories").select_category("option", "category_id"));

						$("#select-categories-button").text($("#wrapper-categories").select_category("path_levels_str", ">"));

						$("#select-categories-modal").modal('hide');
						e.preventDefault();
					}
				});

				//$('#select-categories-modal').modal({show: false});

				$('#select-categories-button')
						.removeClass('disabled')
						.click(function (e) {
							$('#select-categories-modal').modal("show");
							e.preventDefault();
						});
			});

			// Walidacja formularza wgrywania pracy
			$("#profile-upload-image").submit(function () {
				if ($('input[name="title"]').val() != "" && $('input[name="file"]').val() != "" && $('input[name="statement"]:checkbox').is(':checked')
						&& $('input[name="category_id"]').val() != "")
				{
					return true;
				}

				var msg_error = '';
				// title
				if ($('input[name="title"]').val() == "")
				{
					$("#ctrl-gr-title").attr("class", "control-group error");
					msg_error = msg_error + 'Musisz poda\u0107 tytuł pracy.' + '<br />';
				}
				else
				{
					$("#ctrl-gr-title").attr("class", "control-group");
				}
				// file
				if ($('input[name="file"]').val() == "")
				{
					$("#ctrl-gr-file").attr("class", "control-group error");
					msg_error = msg_error + 'Nie wybra\u0142eś pliku.' + '<br />';
				}
				else
				{
					$("#ctrl-gr-file").attr("class", "control-group");
				}
				// category
				if ($('input[name="category_id"]').val() == "")
				{
					$("#ctrl-gr-category").attr("class", "control-group error");
					msg_error = msg_error + 'Nie okre\u015bliłeś kategorii.' + '<br />';
				}
				else
				{
					$("#ctrl-gr-category").attr("class", "control-group");
				}
				// statement
				if (!$('input[name="statement"]:checkbox').is(':checked'))
				{
					$("#ctrl-gr-statement").attr("class", "control-group error");
					msg_error = msg_error + 'Brak o\u015bwiadczenia.' + '<br />';
				}
				else
				{
					$("#ctrl-gr-statement").attr("class", "control-group");
				}

				DIGALLERY.common.clean_alerts(); // usuń stare komunikaty

				// wygeneruj komunikat o błędzie...
				DIGALLERY.common.alert("#content-submit", 'Błąd!', msg_error, 'error');
				
				return false;
			});

			// Walidacja formularza edycji pracy
			$("#profile-edit-image").submit(function () {
				if ($('input[name="title"]').val() != "" && $('input[name="statement"]:checkbox').is(':checked')
						&& $('input[name="category_id"]').val() != "")
				{
					return true;
				}

				var msg_error = '';
				// title
				if ($('input[name="title"]').val() == "")
				{
					$("#ctrl-gr-title").attr("class", "control-group error");
					msg_error = msg_error + 'Musisz poda\u0107 tytuł pracy.' + '<br />';
				}
				else
				{
					$("#ctrl-gr-title").attr("class", "control-group");
				}
				// category
				if ($('input[name="category_id"]').val() == "")
				{
					$("#ctrl-gr-category").attr("class", "control-group error");
					msg_error = msg_error + 'Nie określiłeś kategorii.' + '<br />';
				}
				else
				{
					$("#ctrl-gr-category").attr("class", "control-group");
				}
				// statement
				if (!$('input[name="statement"]:checkbox').is(':checked'))
				{
					$("#ctrl-gr-statement").attr("class", "control-group error");
					msg_error = msg_error + 'Brak oświadczenia.' + '<br />';
				}
				else
				{
					$("#ctrl-gr-statement").attr("class", "control-group");
				}

				DIGALLERY.common.clean_alerts(); // usuń stare komunikaty

				// wygeneruj komunikat o błędzie...
				DIGALLERY.common.alert("#content-submit", 'Błąd!', msg_error, 'error');
				return false;
			});
		},

		add_edit_gallery: function () {
			// pobierz prace - pierwszą stronę
			DIGALLERY.profile._load_thumbs(1);

			// obsługa kliknięcia w przyciski paginacji
			$(document).on("click", "#thumbs-images-pagination ul li", function (event) {
				var href_target = $(event.target).attr('href');
				var page = 1;

				if (href_target !== '#')
				{
					page = href_target[2];
				}

				// pobiera prace z danej parametrem page strony
				DIGALLERY.profile._load_thumbs(page);

				return false;
			});
			
			// init widget'a sortable - nasza tworzona galeria
			$("#gallery ul.thumbnails").sortable({
				receive: function (event, ui)
				{
					//console.log('receive');
					if (DIGALLERY.profile.start_drag)
					{
						DIGALLERY.profile.sortable_receive = true;
					}
				},
				beforeStop: function (event, ui)
				{
					//console.log('beforestop');
					if (DIGALLERY.profile.remove_thumb_allowed)
					{
						DIGALLERY.profile.remove_thumb_allowed = false;
						$("#gallery ul.thumbnails").sortable("enable");
						$('li[data-image-id=' + $(ui.item).attr('data-image-id') + ']', $('#thumbs-images')).draggable('enable');
					}
				},
				out: function (event, ui)
				{
					//console.log('out');
					if (DIGALLERY.profile.start_drag)
					{
						if (DIGALLERY.profile.sortable_receive)
						{
							DIGALLERY.profile.disable_thumb_allowed = true;
							DIGALLERY.profile.sortable_receive = false;
						}
						else
						{
							DIGALLERY.profile.disable_thumb_allowed = false;
						}
					}
				},
				over: function (event, ui)
				{
//					//console.log('over');
//					if (DIGALLERY.profile.start_drag)
//					{
//						DIGALLERY.profile.disable_allowed = true;
//					}
				},
				sort: function (event, ui)
				{
//					console.log('sort');
//					if (DIGALLERY.profile.start_drag)
//					{
//						DIGALLERY.profile.disable_allowed = true;
//					}
				}
			});

			// przycisk wyślij tworzona galeria wysyłana jest na serwer
			$('#profile-add-edit-gallery').submit(DIGALLERY.profile._submit_gallery);
		},
		
		// wysyłanie galerii na serwer
		_submit_gallery: function () {
			var number_images_in_gallery = $("#gallery li").length;

			if ($('input[name="gallery_name"]').val() != "" && number_images_in_gallery > 0)
			{
				var $hidden = $('input:hidden[name="gallery_images_in_gallery"]');

				$hidden.val('');

				// przypisz id prac w tworzonej galerii do ukrytego pola gallery_images_in_gallery
				$("#gallery li").each(function (i, val) {
					var $item = $(this);
					var value_hidden = $hidden.val();

					// przypisuje wszystkie id w ciagu do pola input gallery_images_in_gallery rozdzielone
					// spacją (1 2 3 itd). Po ostatnim elemencie rozdzielająca spacja nie jest dodawana.
					$hidden.val(value_hidden + $item.attr('data-image-id') + (i !== (number_images_in_gallery - 1) ? " " : ""));
				});

				return true;
			}

			// walidacja
			var msg_error = '';

			if ($('input[name="gallery_name"]').val() == "")
			{
				$("#ctrl-gr-gallery-name").attr("class", "control-group error");
				msg_error = msg_error + 'Nie określiłeś nazwy galerii.' + '<br />';
			}
			else
			{
				$("#ctrl-gr-gallery-name").attr("class", "control-group");
			}

			if (!(number_images_in_gallery > 0))
			{
				msg_error = msg_error + 'Brak prac w galerii.' + '<br />';
			}

			DIGALLERY.common.clean_alerts(); // usuń stare komunikaty

			// wygeneruj komunikat o błędzie...
			DIGALLERY.common.alert("#content-add-edit-gallery", "Błąd!", msg_error, "error");

			return false;
		},
		
		_load_thumbs: function ( page ) {
			$.ajax({
				cache: false,
				url: '/profile/get_thumbs_images/' + page,
				type: 'POST',
				success: function (data) {

					$('#thumbs-images').html(data.images); // prace
					$('#thumbs-images-pagination').html(data.pagination); //paginacja
					
					// inicjalizacja przeciągania prac (drag & drop)
					$("#thumbs-images ul.thumbnails > li.dragdrop").draggable({
						connectToSortable: "#gallery ul.thumbnails", // powiązanie z tworzoną galerią (element sortable)
						helper: "clone",
						//revert: "invalid",
						start: function (event, ui)
						{
							//console.log('start_drag');
							DIGALLERY.profile.start_drag = true;
						},
						stop: function (event, ui)
						{
							//console.log('stop_drag');
							DIGALLERY.profile.start_drag = false;

							if (DIGALLERY.profile.disable_thumb_allowed)
							{
								$(this).draggable("disable");
								DIGALLERY.profile.disable_thumb_allowed = false;
							}
						}
					});

					// "wyłącz" przeciąganie prac, które zostały już przeniesione do nowo
					// tworzonej galerii
					$('#thumbs-images li.dragdrop').each(function () {
						$item = $(this);

						if ($('li[data-image-id=' + $item.attr('data-image-id') + ']', $('#gallery')).length === 1)
						{
							$item.draggable('disable');
						}
					});
					
					//
					$("#thumbs-images").droppable({
						accept: "#gallery li",
						drop: function (event, ui) {
							//console.log('drop');
							$("#gallery ul.thumbnails").sortable("disable");
							(ui.helper).remove(); 
							DIGALLERY.profile.remove_thumb_allowed = true;
						}
					});

					// wyłącz możliwość wyboru dla list i ich elementów
					$("ul, li").disableSelection();
				}
			});
		}
	}
});
