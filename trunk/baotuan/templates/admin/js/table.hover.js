$(document).ready(function(){
	makeTableTR2Hover();
});

function makeTableTR2Hover()
{
	$.each($('tr'), function(){
		if ($(this).attr('class') == '')
		{
			$(this)
			.unbind()
			.bind('mouseover', function(){
				$(this).addClass('tr_hover');
			})
			.bind('mouseout', function(){
				$(this).removeClass();
			});
		}
	});
}
