function init_carousel(carousel)
{
	$('#jcarousel-next').bind('click', function() {
        carousel.next();
        return false;
    });

    $('#jcarousel-prev').bind('click', function() {
        carousel.prev();
        return false;
    });
}

$(document).ready(function () {

	comments_init(add_gallery_comment);
	init_ajax_signin_form_submit();
	
	$('#delete-image-confirm-modal').on('show', modal_center);
	$('#stop18-confirm-modal').on('show', modal_center);
	$('#stop18-confirm-btn').click(set_maturity_user);
	
	number_li = $('.jcarousel ul.thumbnails li').length;
	
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
		initCallback: init_carousel,
		buttonPrevCallback: function (carousel, button, enabled) {
			enabled ? $("div.jcarousel-prev-horizontal").removeClass("disabled") : $("div.jcarousel-prev-horizontal").addClass("disabled");
		},
		buttonNextCallback: function (carousel, button, enabled) {
			enabled ? $("div.jcarousel-next-horizontal").removeClass("disabled") : $("div.jcarousel-next-horizontal").addClass("disabled");
		}
			
    });

/*
    //jcarousel 0.3.0 - błedy podczas scrollowania...
	
    $('.jcarousel').jcarousel({
        // Configuration goes here
		'list': '.thumbnails'
		//'item': '.thumbnails > li',
    });

    $('#jcarousel-prev')
        .bind('active.jcarouselcontrol', function() {
            $("div.jcarousel-prev-horizontal").removeClass("disabled"); 
        })
        .bind('inactive.jcarouselcontrol', function() {
            $("div.jcarousel-prev-horizontal").addClass("disabled");
        })
        .jcarouselControl({target: '-=3'});

	$('#jcarousel-next')
        .bind('active.jcarouselcontrol', function() {
			$("div.jcarousel-next-horizontal").removeClass("disabled"); 
        })
        .bind('inactive.jcarouselcontrol', function() {
            $("div.jcarousel-next-horizontal").addClass("disabled"); 
        })
        .jcarouselControl({target: '+=3'});
*/

	$('#delete-image-btn').click(function (event) {
		var gallery_id = ((new String(document.location)).split('/'))[5];

		$.get("/gallery/soft_delete_gallery/" + gallery_id, function(data) {
			if (data.status == 1)
			{
				$('#delete-image-confirm-modal').one('hidden', function() {
					window.location = "/";
				});

				$('#delete-image-confirm-modal').modal('hide');
			}
		}, "json");

		event.preventDefault();
	});
});