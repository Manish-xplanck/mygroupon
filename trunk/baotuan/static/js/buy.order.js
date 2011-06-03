var _allow_to_submit = true;

$(document).ready(function(){
	
});

function fizinit()
{
	$('#order_submit').bind('submit', function(){return order_submit()});
}

function order_field_append(name, value)
{
	$('#order_id').after('<input type="hidden" name="'+name+'" value="'+value+'" />');
}

function order_submit()
{
	$.hook.call('order_submit');
	if (_allow_to_submit)
	{
		$('#order_submit').ajaxSubmit({
			beforeSubmit: function()
			{
				$('#order_submit_button').attr('disabled', true);
				$('#submit_status').text('����Ϊ������֧����ʽ�����Ժ�...');
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
					$('#submit_status').text('�Ѿ�������ã�������ת��֧��ҳ��...');
					order_finish();
				}
				$('#order_submit_button').attr('disabled', false);
			}
		});
	}
	return false;
}
