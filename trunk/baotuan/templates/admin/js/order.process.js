var IMG_LOADING = 'templates/admin/images/btn_loading.gif';

$(document).ready(function(data){
	 tb_init('a.thickbox');
	 $('#remark').bind('blur', OrderRemark);
	 $('.service').bind('click', function(){
	 	if (!confirm('ȷ���ύ��'))
		{
			return;
		}
		doService(this);
	 });
});

function OrderRemark()
{
	var old = $('#remark').attr('title');
	var mark = $('#remark').val();
	if (mark == old)
	{
		return;
	}
	var TMP_id = 'img_loading_remark';
	$('#remark').after('<img id="'+TMP_id+'" style="margin-left:10px;" src="'+IMG_LOADING+'" />');
	$.get('?mod=order&code=remark&oid='+__Global_OID+'&text='+encodeURIComponent(mark), function(data){
		if (data == 'ok')
		{
			$('#remark').attr('title', mark);
		}
		else
		{
			$.notify.alert('����ʧ�ܣ�');
		}
		$('#'+TMP_id).remove();
	});
}

function doService(obj)
{
	var mark = $('#opmark').val();
	$('#service_result').text('�����ύ�����Ժ�...');
	var lnk = $(obj).attr('title');
	$.get(lnk+'&oid='+__Global_OID+'&mark='+encodeURIComponent(mark), function(data){
		if (data == 'ok')
		{
			window.location = window.location;
		}
		else
		{
			$('#service_result').html(data);
		}
	});
}
