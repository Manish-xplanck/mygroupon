var __use_surplus = false;
//$(document).ready(function(){
	$.hook.add('order_submit', function(){
		var payd = $('#paytype_list input[name=payment_id]:checked').val();
		if (payd == 0 || payd == undefined)
		{
			$('#paytype_list input[name=payment_id]:first').tipTip({
				content:"请选择一个有效的支付方式",
				keepAlive:true,
				activation:"focus",
				defaultPosition:"top",
				edgeOffset:8,
				maxWidth:"300px"
			});
			$('#paytype_list input[name=payment_id]:first').focus();
			_allow_to_submit = false;
		}
		else
		{
			order_field_append('payment_id', payd);
			if (__use_surplus)
			{
				order_field_append('payment_use_surplus', 'true');
			}
			_allow_to_submit = true;
		}
	});
	$('#payment_use_surplus').bind('change', function(){
		var left = $('#payment_clear').offset().left;
		var showAni = {
			left: left+'px'
		};
		var hideAni = {
			left: '-1000px'
		};
		if ($(this).attr('checked'))
		{
			$('#payment_remain_money').animate(showAni);
			$('#payment_total_money').animate(hideAni);
			__use_surplus = true;
		}
		else
		{
			$('#payment_total_money').animate(showAni);
			$('#payment_remain_money').animate(hideAni);
			__use_surplus = false;
		}
	});
//});