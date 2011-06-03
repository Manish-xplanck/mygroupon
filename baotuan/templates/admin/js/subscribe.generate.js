$(document).ready(function(){
	if (templateID == '')
	{
		template_generate();
	}
	else
	{
		template_preview(parseInt(templateID));
	}
});

function pushdPreview(obj)
{
	var target = $('#target').val();
	if (target == '')
	{
		$.notify.alert('�����뷢��Ŀ����룡');
		return;
	}
	$(obj).val('���ڷ���');
	var tplid = templateID;
	$.get('?mod=subscribe&code=push&op=preview&class='+__flag+'&tid='+tplid+'&target='+target, function(data){
		if (data == 'ok')
		{
			$(obj).val('���ͳɹ�');
		}
		else
		{
			$(obj).val('����ʧ��');
		}
	});
}

function pushdRequest(flag, obj)
{
	var tplid = templateID;
	var cityid = $('#citySel_of_'+flag).val();
	$(obj).val('��������');
	$.get('?mod=subscribe&code=push&class='+flag+'&tid='+tplid+'&city='+cityid, function(data){
		if (data == 'ok')
		{
			$(obj).val('���ͳɹ�');
			$(obj).attr('disabled', 'disabled');
		}
		else
		{
			$(obj).val('����ʧ��');
		}
	});
}

function template_edit()
{
	parent.window.location = 'admin.php?mod=subscribe&code=broadcast&op=edit&id='+templateID;
}

function template_generate()
{
	var obj = $('#templateButton');
	$(obj).val('���ڼ���');
	$.get('?mod=subscribe&code=generate&op=template&flag='+__flag+'&from='+__from+'&idx='+__idx, function(data){
		template_preview(parseInt(data));
		$(obj).val('�༭ģ��');
	});
}

function template_preview(tplid)
{
	$('#piframe').attr('src', '?mod=subscribe&code=template&op=preview&id='+tplid);
	templateID = tplid;
}
