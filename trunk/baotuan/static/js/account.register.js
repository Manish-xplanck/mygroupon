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
		reg_alert('email', '请输入Email地址！');
		return;
	}
	var emailRegExp = new RegExp("[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?");
	if (!emailRegExp.test(email)||email.indexOf('.')==-1)
	{
		reg_alert('email', '请输入正确的Email地址！');
		return;
	}
	else
	{
		$.get('?mod=account&code=exists&field=email&value='+email, function(data)
		{
			eval('var data=' + data);
			if (data.status == 'failed')
			{
				reg_alert('email', '验证失败！');
				return;
			}
			if (data.result)
			{
				reg_alert('email', '此Email地址已经被注册！');
				return;
			}
			reg_success('email', '可以使用！');
		});
	}
}

function CheckPhone()
{
	var phone = $('#phone').val();
	if (phone == '') return;
	if (phone.length != 11){
		reg_alert('phone', '请输入正确的手机号码！');
		return;
	}
	reg_success('phone', '输入正确！');
}

function CheckUsername()
{
	var name = $('#username').val();
	if(name.replace(/[^\x00-\xff]/g,"**").length<4 || name.length>16){
		reg_alert('username', '用户名必须在4位到16位！');
		return;
	}
	$.get('?mod=account&code=exists&field=name&value='+name, function(data)
	{
		eval('var data=' + data);
		if (data.status == 'failed')
		{
			reg_alert('username', '验证失败！');
			return;
		}
		if (data.result)
		{
			reg_alert('username', '此用户名已经被注册！');
			return;
		}
		reg_success('username', '可以使用！');
	});
}

function CheckPassword()
{
	var pwd = $('#password').val();
	if(pwd.length < 4){
		reg_alert('password', '密码最短4位数！');
		return;
	}
	reg_success('password', '可以使用！');
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
		reg_alert('repassword', '两次密码不一致！');
		return;
	}
	reg_success('repassword', '密码输入正确！');
}

function reg_alert(field, text)
{
	$('#'+field+'_result').html(text).removeClass().addClass('alert');
}
function reg_success(field, text)
{
	$('#'+field+'_result').html(text).removeClass().addClass('success');
}
