var $__G_Time = {};
var $__ms_Count = {};

$(document).ready(function(){
	for (id in $__G_Time)
	{
		// first time minus 1 secs
		showtime(id, $__G_Time[id]-1);
	}
});

function addTimeLesser(id, time)
{
	$__G_Time['remainTime_'+id] = time;
}

function showtime(id, time, msid)
{
	var msC = $__ms_Count[id];
	if (msC == undefined) msC = 0;
	if (msC > 0 && msid != '')
	{
		$('#'+msid).text('.'+msC);
		msC --;
		$__ms_Count[id] = msC;
		setTimeout(function(){showtime(id, time, msid)}, 100);
		return;
	}
	$__ms_Count[id] = 9;
	if (time <= 0)
	{
		$('#' + id).html('<span>团购已经结束</span>');
		return;
	}
	var timeUnits = {
		'day': { 'name': '天', 'count': 86400 },
		'hour': { 'name': '小时', 'count': 3600 },
		'minute': { 'name': '分', 'count': 60 },
		'second': { 'name': '秒', 'count': 1 }
	};
	var string = '';
	var iLess = time;
	for (ix in timeUnits)
	{
		var unit = timeUnits[ix];
		if (iLess >= unit.count || iLess == 0)
		{
			var cc = Math.floor(iLess / unit.count);
			var ccString = cc < 10 ? '0'+cc.toString() : cc.toString();
			string += '<span style="font-size:20px;">' + ccString + '</span>' + unit.name;
			iLess -= cc * unit.count;
		}
	}
	var msid = 'msid_'+__rand_key();
	$('#' + id).html(string+'<font id="'+msid+'">.0</font>');
	setTimeout(function(){showtime(id, time - 1, msid)}, 100);
}

function __rand_key()
{
	var salt = '0123456789qwertyuioplkjhgfdsazxcvbnm';
	var str = 'id_';
	for(var i=0; i<6; i++)
	{
		str += salt.charAt(Math.ceil(Math.random()*100000000)%salt.length);
	}
	return str;
}