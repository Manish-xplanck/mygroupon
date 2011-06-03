var _allow_to_submit = true;
var __reg_price_list = {};

$(document).ready(function(){
	
});

function fizinit()
{
	$('#num_buys').bind('blur', function(){change_of_num_buys()});
	change_of_num_buys();
	$('#checkout_submit').bind('submit', function(){return checkout_submit()});
}

function change_of_num_buys()
{
	var num = parseInt($('#num_buys').val());
	var tips = '';
	if (isNaN(num))
	{
		$('#num_buys').val(oncemin);
	}
	else if (num < oncemin)
	{
		tips = '您最少需要购买'+oncemin+'件商品才能参团！';
		$('#num_buys').val(oncemin);
	}
	else if (oncemax > 0 && num > oncemax)
	{
		tips = '您最多只能购买'+oncemax+'件商品！';
		$('#num_buys').val(oncemax);
	}
	else if (num > surplus)
	{
		tips = '本次团购只剩余'+surplus+'件商品了！';
		$('#num_buys').val(surplus);
	}
	if (tips != '')
	{
		$('#num_buys').tipTip({
			content:tips,
			keepAlive:true,
			activation:"focus",
			defaultPosition:"top",
			edgeOffset:8,
			maxWidth:"300px"
		});
		$('#num_buys').focus();
		num = parseInt($('#num_buys').val());
	}
	else
	{
		$.hook.call('buys_num_change', num);
	}
	__reg_price_list['product'] = price*num;
	price_calc();
}
/**
 * 统计价格
 */
function price_calc()
{
	var total = 0;
	$.each(__reg_price_list, function(item, price){
		if (typeof(price) == 'number')
		{
			$('#price_' + item).text(price.toFixed(2));
			total += price;
		}
	});
	$('#price_total').text(total.toFixed(2));
}
/**
 * 注册价格计费种类
 * @param string id
 * @param string name
 */
function price_type_reg(id, name)
{
	var fid = 'price_'+id;
	var price = __reg_price_list[id];
	if (price != undefined) return false;
	__reg_price_list[id] = 0;
	// 增加显示
	$('#tr_price_total').before('<tr id="tr_"'+fid+'><td class="left"><font id="'+fid+'_name">'+name+'</font></td><td class="right">&yen; <font id="'+fid+'" class="price">0</font></td></tr>');
}
/**
 * 更改计费名称
 * @param string id
 * @param string newName
 */
function price_type_change(id, newName)
{
	var fid = 'price_'+id+'_name';
	var oldName = $('#'+fid).text();
	if (oldName == undefined)
	{
		price_type_reg(id, newName);
	}
	else
	{
		$('#'+fid).text(newName);
	}
}
/**
 * 更改计费价格
 * @param string id
 * @param integer price
 */
function price_change(id, price)
{
	__reg_price_list[id] = price;
	price_calc();
}
/**
 * 添加一个表单字段
 * @param {Object} name
 * @param {Object} value
 */
function checkout_field_append(name, value)
{
	$('#product_id').after('<input type="hidden" name="'+name+'" value="'+value+'" />');
}
/**
 * 订单提交
 */
function checkout_submit()
{
	$.hook.call('checkout_submit');
	if (_allow_to_submit)
	{
		var num = parseInt($('#num_buys').val());
		checkout_field_append('num_buys', num);
		$('#checkout_submit').ajaxSubmit({
			beforeSubmit: function()
			{
				$('#checkout_submit_button').attr('disabled', true);
				$('#submit_status').text('正在为您生成订单，请稍候...');
				$('#submit_status').css('display', 'inline');
			},
			success: function(data)
			{
				eval('var data='+data);
				if (data.status != 'ok')
				{
					$('#submit_status').text(data.msg);
				}
				else
				{
					$('#submit_status').text('已经获取到订单，正在跳转...');
					order_finish(data.id);
				}
				$('#checkout_submit_button').attr('disabled', false);
			}
		});
	}
	return false;
}
