function couponAlert(id)
{
	couponOping(id);
	$.get('?mod=coupon&code=alert&id='+id, function(data){
		if (data != 'ok')
		{
			$.notify.alert('����ʧ�ܣ�');
		}
		couponOping(id, 'end');
		$.notify.success('�Ѿ��ɹ����ѣ�');
	});
}
function couponReissue(id)
{
	couponOping(id);
	$.get('?mod=coupon&code=reissue&id='+id, function(data){
		if (data != 'ok')
		{
			$.notify.alert('֪ͨʧ�ܣ�');
		}
		couponOping(id, 'end');
		$.notify.success('�Ѿ��ɹ�֪ͨ��');
	});
}
function couponDelete(id)
{
	if (!confirm('ȷ��ɾ����')) return;
	couponOping(id);
	$.get('?mod=coupon&code=delete&id='+id, function(data){
		if (data == 'ok')
		{
			couponOping(id, 'close');
		}
		else
		{
			$.notify.alert('ɾ��ʧ�ܣ�');
			couponOping(id, 'end');
		}
	});
}
function couponOping(id, op)
{
	if (op == undefined)
	{
		$('#cp_on_'+id).removeClass().addClass('oping');
		return;
	}
	if (op == 'end')
	{
		$('#cp_on_'+id).removeClass();
		return;
	}
	if (op == 'close')
	{
		$('#cp_on_'+id).fadeOut();
		return;
	}
}