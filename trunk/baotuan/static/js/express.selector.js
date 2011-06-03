var __express_for_address_id = '';
var __express_Sel = {};

//$(document).ready(function(){
	price_type_reg('express', '快递费用');
	$.hook.add('address_change', function(aid){express_display(aid)});
	$.hook.add('address_rewrite', function(){express_display_none()});
	$.hook.add('checkout_submit', function(){
		var exp = $('input[name=express_id]:checked').val();
		if (exp == 0 || exp == undefined)
		{
			$('input[name=express_id]:first').tipTip({
				content:"请选择一个有效的快递方式！",
				keepAlive:true,
				activation:"focus",
				defaultPosition:"top",
				edgeOffset:8,
				maxWidth:"300px"
			});
			$('input[name=express_id]:first').focus();
			_allow_to_submit = false;
		}
		else
		{
			checkout_field_append('express_id', exp);
			_allow_to_submit = true;
		}
	});
	$.hook.add('buys_num_change', function(num){
		express_price_calc(num);
	});
//});

function express_price_change(fu, fp, cu, cp)
{
	__express_Sel.fu = fu;
	__express_Sel.fp = fp;
	__express_Sel.cu = cu;
	__express_Sel.cp = cp;
	express_price_calc(parseInt($('#num_buys').val()));
}

function express_price_calc(num)
{
	var AW = num * weight;
	var price = __express_Sel.fp;
	if (AW > __express_Sel.fu)
	{
		var LW = AW - __express_Sel.fu;
		if (__express_Sel.cu <=0)
		{
			__express_Sel.cu = 1;
		}
		price += Math.ceil(LW / __express_Sel.cu) * __express_Sel.cp;
	}
	price_change('express', price);
}

function express_display(aid)
{
	if (aid == __express_for_address_id) return false;
	if ($.cache.check(aid))
	{
		$('#express_list').html($.cache.get(aid)).show();
		$('#address_first').css('display', 'none');
		__express_for_address_id = aid;
	}
	else
	{
		$('#address_first').text('正在根据您的地址加载配送方式...').css('display', 'block');
		$.get('?mod=misc&code=express&op=list&aid='+aid, function(data){
			eval('var data='+data);
			if (data.status != 'ok' || data.html.length == 0)
			{
				$('#address_first').text('无法加载配送列表！');
			}
			else
			{
				var html = '<ul>';
				$.each(data.html, function(i, exp){
					html += '\
					<li>\
						<input type="radio" name="express_id" value="'+exp.id+'" onclick="express_price_change('+exp.firstunit+', '+exp.firstprice+','+exp.continueunit+','+exp.continueprice+');">\
						'+exp.name+'\
					</li>';
				});
				html += '</ul>';
				$.cache.set(aid, html);
				express_display(aid);
			}
		});
	}
	return true;
}

function express_display_none()
{
	$('#express_list').hide();
	$('#address_first').text('请先选择收货地址！').css('display', 'block');
	__express_for_address_id = 0;
}
