$(document).ready(function(){
	$('#export_submit').bind('click', doExport);
});

function doExport()
{
	var action = $('#export_action').val();
	var filter = $('#export_filter').val();
	var geneall = $('input[name="export_all"]:checked').val();
	var format = $('#export_format').val();
	$('#export_result').text('正在生成中，请稍候...');
	$.get('?mod=export',
		{
			'code': action,
			'op': 'generate',
			'geneall': geneall,
			'format': format,
			'filter': filter
		},
		function(data){
			try
			{
				eval('var file='+data);
				$('#export_result').html('请点击下载：<a href="'+file.url+'">'+file.name+'</a>');
			}
			catch(e)
			{
				$.notify.alert('生成失败！');
				$('#export_result').text('生成失败，请重试！');
			}
		}
	);
}
