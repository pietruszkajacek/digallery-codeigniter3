$(document).ready(function () {
	
	$.post( "/profile/get_categories_ajax/", function ( data ) {
		var $input_cat_id = $('input[name="category_id"]'),
			cat_id = $input_cat_id.val();
	
		$( "#wrapper-categories" ).select_category( {
			categories: data,
			category_id: (cat_id !== '' ? cat_id : null),
		
			change: function(e, ui) { 
				if ( ui.leaf ) 
					$( "#select-btn" ).removeClass( "disabled" );
				else
					$( "#select-btn" ).addClass( "disabled" );
			} 
		});
		
		$('#select-btn').click( function(e) {
			if ( $( "#select-btn" ).hasClass( 'disabled' ) )
				e.preventDefault();
			else {
				$input_cat_id.attr( "value", $( "#wrapper-categories" ).select_category( "option", "category_id" ) );
			
				$( "#select-categories-button" ).text( $( "#wrapper-categories" ).select_category( "path_levels_str", ">" ) );

				$( "#select-categories-modal" ).modal('hide');
				e.preventDefault();
			}
		});		
		
		$( '#select-categories-modal' ).modal( { show: false } );
		
		$( '#select-categories-button' )
				.removeClass( 'disabled' )
				.click( function(e) {
					$( '#select-categories-modal' ).modal( "show" );
					e.preventDefault();
				});
	});

	// Walidacja formularza wgrywania pracy
	$("#profile-upload-image").submit(function() {
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
		if ( ! $('input[name="statement"]:checkbox').is(':checked'))
		{
			$("#ctrl-gr-statement").attr("class", "control-group error");
			msg_error = msg_error + 'Brak o\u015bwiadczenia.' + '<br />';
		}
		else
		{
			$("#ctrl-gr-statement").attr("class", "control-group");
		}

		$("div.alert").remove(); // usuń stare komunikaty

		// wygeneruj komunikat o błędzie...
		$("#content-submit").prepend('<div class="alert alert-error"></div>');
		$("div.alert").append('<a class="close" data-dismiss="alert" href="#">×</a>')
					  .append('<h4 class="alert-heading">B\u0142\u0105d!</h4>')
					  .append(msg_error);
		return false;
	});
	
	// Wyśrodkowanie okna dialogowego
	$('#select-categories-modal').on('show', function(e) {
		var modal = $(this);
		
		modal.css('margin-top', (modal.outerHeight() / 2) * -1)
			 .css('margin-left', (modal.outerWidth() / 2) * -1);

		return this;
	});

	// Walidacja formularza edycji pracy
	$("#profile-edit-image").submit(function() {
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
		if ( ! $('input[name="statement"]:checkbox').is(':checked'))
		{
			$("#ctrl-gr-statement").attr("class", "control-group error");
			msg_error = msg_error + 'Brak oświadczenia.' + '<br />';
		}
		else
		{
			$("#ctrl-gr-statement").attr("class", "control-group");
		}

		$("div.alert").remove(); // usuń stare komunikaty

		// wygeneruj komunikat o błędzie...
		$("#content-submit").prepend('<div class="alert alert-error"></div>');
		$("div.alert").append('<a class="close" data-dismiss="alert" href="#">×</a>')
					  .append('<h4 class="alert-heading">Błąd!</h4>')
					  .append(msg_error);
		return false;
	});
});