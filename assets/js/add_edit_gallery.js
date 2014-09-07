var dis = false,
	s_drag = false,
	rec = false,
	rm = false;	

$(document).ready(function () {

	load_thumbs(1);
	
	$(document).on("click", "#thumbs-images-pagination ul li", function(event){ 
		var href_target = $(event.target).attr('href');
		var page = 1;
		
		if (href_target !== '#')
		{
			page = href_target[2];
		} 
		
		load_thumbs(page);
		
		return false;
    });

	$("#gallery ul.thumbnails").sortable({
		receive: function(event, ui)
		{
			if (s_drag)
			{
				rec = true;
			}
		},
		beforeStop: function(event, ui)
		{
			if (rm)
			{
				rm = false;
				$("#gallery ul.thumbnails").sortable("enable");
				
				$('li[data-image-id=' + $(ui.item).attr('data-image-id') + ']', $('#thumbs-images')).draggable('enable');
			}
		},
		out: function(event, ui)
		{
			if (s_drag) 
			{
				if (rec)
				{
					dis = true;
					rec = false;
				}
				else
				{
					dis = false;
				}
			}
		},
		over: function(event, ui)
		{
			if (s_drag) 
			{
				dis = true;
			}
		},
		sort: function(event, ui)
		{
			if (s_drag) 
			{
				dis = true;
			}
		}
	});
	
	$('#profile-add-edit-gallery').submit(submit_gallery);
});

function submit_gallery()
{
	var number_images_in_gallery = $("#gallery li").length;
	
	if (number_images_in_gallery > 0)
	{
		var $hidden = $('input:hidden[name="gallery_images_in_gallery"]');
		
		$hidden.val('');
		
		$("#gallery li").each(function(i, val) {
			var $item = $(this);
			var value_hidden = $hidden.val();

			$hidden.val(value_hidden + $item.attr('data-image-id') + (i !== (number_images_in_gallery - 1) ? " " : ""));
		});
	}

	if ($('input[name="gallery_name"]').val() != "" && number_images_in_gallery > 0)
	{
		return true;
	}	

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

	$("#validation-alert").remove(); // usuń stare komunikaty

	// wygeneruj komunikat o błędzie...
	$("#content-add-edit-gallery").prepend('<div class="alert alert-error" id="validation-alert"></div>');
	$("#validation-alert").append('<a class="close" data-dismiss="alert" href="#">×</a>')
			.append('<h4 class="alert-heading">Błąd!</h4>')
			.append(msg_error);
	
	return false;
}

function load_thumbs(page)
{
    $.ajax({
        cache: false,
        url: '/profile/get_thumbs_images/' + page,
        type: 'POST',
        success: function(data) {

			$('#thumbs-images').html(data.images);
			$('#thumbs-images-pagination').html(data.pagination);
			
			$( "#thumbs-images ul.thumbnails > li.test" ).draggable({
				connectToSortable: "#gallery ul.thumbnails",
				helper: "clone",
				//revert: "invalid",
				start: function( event, ui ) 
				{
					s_drag = true;
				},
				stop: function( event, ui ) 
				{
					s_drag = false;
					
					if (dis)
					{
						$( this ).draggable( "disable" );
						dis = false;
					}
				}
			});
		
			$('#thumbs-images li.test').each(function() {
				$item = $(this);
				
				if ($('li[data-image-id=' + $item.attr('data-image-id') + ']', $('#gallery')).length === 1)
				{
					$item.draggable('disable');
				}
			});
			
			$("#thumbs-images").droppable({
				accept: "#gallery li",
				drop: function( event, ui ) {
					$("#gallery ul.thumbnails").sortable("disable");
					(ui.helper).remove();
					rm = true;
				}
			}); 			
			
			$( "ul, li" ).disableSelection();
		}
     });
}
