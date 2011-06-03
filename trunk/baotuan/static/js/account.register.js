$(document).ready(function(){
	$('#email').bind('blur', CheckEmail);
	$('#phone').bind('blur', CheckPhone);
	$('#username').bind('blur', CheckUsername);
	$('#password').bind('blur', CheckPassword);
	$('#repassword').bind('blur', CheckRepassword);
});

function CheckEmail()
{
	var email = $('#email').val();
	if (email == '')
	{
		reg_alert('email', '������Email��ַ��');
		return;
	}
	var emailRegExp = new RegExp("[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?");
	if (!emailRegExp.test(email)||email.indexOf('.')==-1)
	{
		reg_alert('email', '��������ȷ��Email��ַ��');
		return;
	}
	else
	{
		$.get('?mod=account&code=exists&field=email&value='+email, function(data)
		{
			eval('var data=' + data);
			if (data.status == 'failed')
			{
				reg_alert('email', '��֤ʧ�ܣ�');
				return;
			}
			if (data.result)
			{
				reg_alert('email', '��Email��ַ�Ѿ���ע�ᣡ');
				return;
			}
			reg_success('email', '����ʹ�ã�');
		});
	}
}

function CheckPhone()
{
	var phone = $('#phone').val();
	if (phone == '') return;
	if (phone.length != 11){
		reg_alert('phone', '��������ȷ���ֻ����룡');
		return;
	}
	reg_success('phone', '������ȷ��');
}

function CheckUsername()
{
	var name = $('#username').val();
	if(name.replace(/[^\x00-\xff]/g,"**").length<4 || name.length>16){
		reg_alert('username', '�û���������4λ��16λ��');
		return;
	}
	$.get('?mod=account&code=exists&field=name&value='+name, function(data)
	{
		eval('var data=' + data);
		if (data.status == 'failed')
		{
			reg_alert('username', '��֤ʧ�ܣ�');
			return;
		}
		if (data.result)
		{
			reg_alert('username', '���û����Ѿ���ע�ᣡ');
			return;
		}
		reg_success('username', '����ʹ�ã�');
	});
}

function CheckPassword()
{
	var pwd = $('#password').val();
	if(pwd.length < 4){
		reg_alert('password', '�������4λ����');
		return;
	}
	reg_success('password', '����ʹ�ã�');
}

function CheckRepassword()
{
	var pwd = $('#password').val();
	if (pwd == '')
	{
		return;
	}
	var repwd = $('#repassword').val();
	if (pwd != repwd)
	{
		reg_alert('repassword', '�������벻һ�£�');
		return;
	}
	reg_success('repassword', '����������ȷ��');
}

function reg_alert(field, text)
{
	$('#'+field+'_result').html(text).removeClass().addClass('alert');
}
function reg_success(field, text)
{
	$('#'+field+'_result').html(text).removeClass().addClass('success');
}
