var IMG_LOADING = 'templates/admin/images/btn_loading.gif';

function area_block_add(area)
{
	$('#block_add_result').html('<img src="'+IMG_LOADING+'" />');
	var block = $('#block_flag').val();
	$.get('?mod=widget&code=config&op=block_add&area='+area+'&block='+block, function(data){
		if (data != 'ok')
		{
			$('#block_add_result').html(data);
		}
		else
		{
			window.location = window.location;
		}
	});
}

function area_block_remove(area, block)
{
	if (!confirm('È·ÈÏÒªÉ¾³ýÂð£¿')) return;
	$('#block_remove_result').html('<img src="'+IMG_LOADING+'" />');
	$.get('?mod=widget&code=config&op=block_remove&area='+area+'&block='+block, function(data){
		if (data != 'ok')
		{
			$('#block_remove_result').html(data);
		}
		else
		{
			$('#tr_of_'+block).remove();
		}
	});
}
