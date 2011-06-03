$(document).ready(function(){
	$('#btn_random').bind('click', GenerateNAP);
	$('#btn_create').bind('click', GenerateCoupon);
});

function GenerateNAP()
{
	$('#number').val(__rand_key(12));
	$('#password').val(__rand_key(6));
}

function GenerateCoupon()
{
	if (!confirm('ȷ�����ɣ�')) return;
	var uid = $('#uid').val();
	var pid = $('#pid').val();
	var oid = $('#oid').val();
	if (uid == '' || pid == '' || oid == '')
	{
		$.notify.alert('UID��PID��OID����Ϊ�գ�');
		return;
	}
	$('#generate_result').text('��������...');
	var number = $('#number').val();
	var password = $('#password').val();
	var mutis = $('#mutis').val();
	$.get('?mod=coupon&code=add&op=save&uid='+uid+'&pid='+pid+'&oid='+oid+'&number='+number+'&password'+password+'&mutis='+mutis, function(data){
		if (data == 'ok')
		{
			$('#generate_result').html('<font color="green"><b>�Ѿ����ɣ�</b></font>');
			setTimeout(function(){$('#generate_result').text('�ȴ�����');}, 2000);
		}
		else
		{
			$('#generate_result').html(data);
		}
	});
}

/**
 * ����ַ�
 */
function __rand_key(length)
{
	var salt = '0123456789';
	var str = '';
	for(var i=0; i<length; i++)
	{
		str += salt.charAt(Math.ceil(Math.random()*100000000)%salt.length);
	}
	return str;
}