$(document).ready(function(){
	$('#export_submit').bind('click', doExport);
});

function doExport()
{
	var action = $('#export_action').val();
	var filter = $('#export_filter').val();
	var geneall = $('input[name="export_all"]:checked').val();
	var format = $('#export_format').val();
	$('#export_result').text('���������У����Ժ�...');
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
				$('#export_result').html('�������أ�<a href="'+file.url+'">'+file.name+'</a>');
			}
			catch(e)
			{
				$.notify.alert('����ʧ�ܣ�');
				$('#export_result').text('����ʧ�ܣ������ԣ�');
			}
		}
	);
}
