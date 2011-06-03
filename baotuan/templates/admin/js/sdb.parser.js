/*
 * SDB 服务端数据前端化处理器
 */

var IMG_ENABLE = 'templates/admin/images/btn_enable.png';
var IMG_DISABLE = 'templates/admin/images/btn_disable.gif';
var IMG_LOADING = 'templates/admin/images/btn_loading.gif';

var __SDATA = {};
var listSDBDriver = new Array('ini', 'dbf');

$(document).ready(function(){
	$.each(listSDBDriver, function(i, name){
		$.each($('.'+name), function(){
			HookSDBParser(name, this);
		});
	});
	$.each($('._flag_img'), function(){
		var title = $(this).attr('title');
		switch (title)
		{
			case 'enable':
				$(this).attr('src', IMG_ENABLE);
			break;
			case 'disable':
				$(this).attr('src', IMG_DISABLE);
			break;
			case 'loading':
				$(this).attr('src', IMG_LOADING);
			break;
		}
	});
});

function HookSDBParser(src, obj)
{
	if ($(obj).text() != '')
	{
		ExistSDBParser(src, obj);
		return;
	}
	var iid = 'sdb_'+__rand_key();
	$(obj).attr('id', iid);
	$('#'+iid).html('<img src="'+IMG_LOADING+'" />');
	var path = $(obj).attr('title');
	var rel = $(obj).attr('rel');
	if (rel == undefined)
	{
		rel = '';
	}
	$.get('?mod='+src+'&code=get&path='+path+__url_stamp(), function(data){
		// storage data
		__SDATA[iid] = {};
		__SDATA[iid].rel = rel;
		__SDATA[iid].src = src;
		__SDATA[iid].path = path;
		__SDATA[iid].data = eval(data);
		SDBParser(iid);
	});
}

function ExistSDBParser(src, obj)
{
	var iid = 'sdb_'+__rand_key();
	$(obj).attr('id', iid);
	var rel = $(obj).attr('rel');
	if (rel == undefined)
	{
		rel = '';
	}
	// storage data
	__SDATA[iid] = {};
	__SDATA[iid].rel = rel;
	__SDATA[iid].src = src;
	__SDATA[iid].path = $(obj).attr('title');
	var text = $(obj).text();
	var data;
	if (text == 'true' || text == 'false')
	{
		// boolean
		data = (text == 'true') ? true : false;
	}
	else
	{
		// super string ^ ^
		data = text;
	}
	__SDATA[iid].data = data;
	SDBParser(iid);
}

function SDBParser(iid)
{
	var subParser = '';
	var rel = __SDATA[iid].rel;
	if (rel == '')
	{
		subParser = 'Parser_'+typeof(__SDATA[iid].data);
	}
	else
	{
		subParser = 'Parser_'+rel;
	}
	eval(subParser+'(iid)');
}

// Parser.boolean
function Parser_boolean(iid)
{
	var area = $('#'+iid);
	if (__SDATA[iid].data)
	{
		__SDATA[iid].data = false;
		area.html('<a href="#void" onclick="javascript:Set_boolean(\''+iid+'\');return false;"><img src="'+IMG_ENABLE+'" /></a>');
	}
	else
	{
		__SDATA[iid].data = true;
		area.html('<a href="#void" onclick="javascript:Set_boolean(\''+iid+'\');return false;"><img src="'+IMG_DISABLE+'" /></a>');
	}
}
function Set_boolean(iid, set)
{
	$('#'+iid).html('<img src="'+IMG_LOADING+'" />');
	$.get('?mod='+__SDATA[iid].src+'&code=set&path='+__SDATA[iid].path+'&data='+encodeURIComponent(__SDATA[iid].data)+__url_stamp(), function(data){
		Parser_boolean(iid);
	});
}

// Parser.string
function Parser_string(iid)
{
	var area = $('#'+iid);
	var data = __SDATA[iid].data;
	var dataLen = data.toString().length;
	if (dataLen < 18)
	{
		area.html('<input type="text" value="'+data+'" onblur="javascript:Set_string(\''+iid+'\',this);"/>');
	}
	else
	{
		area.html('<textarea onblur="javascript:Set_string(\''+iid+'\',this);">'+data+'</textarea>');
	}
}
function Set_string(iid, area)
{
	var text = $(area).val();
	if (text == __SDATA[iid].data)
	{
		return;
	}
	// pre set
	__SDATA[iid].data = text;
	var TMP_id = 'img_loading_'+__rand_key();
	$('#'+iid).after('<img id="'+TMP_id+'" style="margin-left:10px;" src="'+IMG_LOADING+'" />');
	$.get('?mod='+__SDATA[iid].src+'&code=set&path='+__SDATA[iid].path+'&data='+encodeURIComponent(__SDATA[iid].data)+__url_stamp(), function(data){
		$('#'+TMP_id).remove();
	});
}

/**
 * 随机字符
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

function __url_stamp()
{
	return '&timestamp='+(new Date()).getTime();
}
