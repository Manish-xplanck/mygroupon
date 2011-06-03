$(document).ready(function(){
	$('#submiter').bind('click', submitTrackingNo);
});

function submitTrackingNo()
{
	if (!confirm('确定提交吗？')) return;
	$('#submit_result').text('正在登记单号...');
	var trackingno = $('#trackingno').val();
	$.get('?mod=delivery&code=upload&op=single&oid='+__Global_OID+'&no='+trackingno, function(data){
		if (data != 'ok')
		{
			$('#submit_result').html(data);
		}
		else
		{
			$('#trackingno').remove();
			$('#submiter').remove();
			$('#submit_result').text(trackingno);
		}
	});
}
