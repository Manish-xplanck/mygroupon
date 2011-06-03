var FMT_Success = -10001;
var FMT_Failed = -10002;

$(document).ready(function(){
	$.each($('.wchecker'), function(i, one){
		var TMP_id = 'check_status_'+__rand_key();
		$(one).after('<font id="'+TMP_id+'" style="margin-left:10px;"></font>');
		$(one).bind('blur', function(){
			wExistChecks(one, TMP_id);
		});
	});
	KE.show({id:'content'});
});

function wExistChecks(box, sid)
{
	var val = $(box).val();
	if (val == '')
	{
		Status(sid, '���������ݣ�', FMT_Failed);
		return;
	}
	var regx = /^[a-z0-9_]*$/i;
	if (!regx.test(val))
	{
		Status(sid, '���ֻ��Ϊ���ַ����ַ�a��z������0-9���»���_��', FMT_Failed);
		return;
	}
	Status(sid, '���ڼ��...');
	var path = $(box).attr('title')+val;
	$.get('?mod=ini&code=get&path='+path, function(data){
		if (data == 'false')
		{
			Status(sid, '�˱�ǿ���ʹ�ã�', FMT_Success);
		}
		else
		{
			Status(sid, '�˱���Ѿ���ʹ�ã��뻻һ����', FMT_Failed);
		}
	});
}

function Status(sid, text, format)
{
	if (format == FMT_Success)
	{
		$('#'+sid).html('<font color="green">'+text+'</font>');
	}
	else if(format == FMT_Failed)
	{
		$('#'+sid).html('<font color="red">'+text+'</font>');
	}
	else
	{
		$('#'+sid).text(text);
	}
}

/**
 * ����ַ�
 */
function __rand_key()
{
	var salt = '0123456789qwertyuioplkjhgfdsazxcvbnm';
	var str = '';
	for(var i=0; i<6; i++)
	{
		str += salt.charAt(Math.ceil(Math.random()*100000000)%salt.length);
	}
	return str;
}