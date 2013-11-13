

$(document).ready(function () {
	
	number_li = $('.jcarousel ul.thumbnails li').length;
	
	if (number_li < 5)
	{
		ul_width = number_li * 63 - 8;
		
		$('.jcarousel')
				.css('width', ul_width + 'px');
	}


	$('.jcarousel').jcarousel({
		//center: true
    });

	$('#btn-test').click(test);


	
	

});

function test()
{
	//$('.jcarousel').jcarousel('scroll', '+=1');
	
	$('.jcarousel ul.thumbnails').css('width', '315px');
}