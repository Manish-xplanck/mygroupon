var $__G_ISC_fid = '';
var $__G_ISC_IS_Timer = null;
var $__G_ISC_Last_Search = '';

$(document).ready(function(){
	$('#iscp_fids').bind('change', iscp_fidChange);
	$('#iscp_input').bind('keyup', function(event){iscp_iSearch(event);});
	$('#iscp_input').bind('focus', iscp_IS_ready);
	$('#iscp_input').bind('blur', iscp_IS_stop);
	$('#iscp_button').bind('click', iscp_doSearch);
	iscp_fillSearcher();
	iscp_fidChange();
});

function iscp_fillSearcher()
{
	var loc = window.location;
		loc = loc.toString();
	var args = loc.split('&');
	$.each(args, function(i, one){
		var arg = one.split('=');
		if (arg[0] == 'ssrc')
		{
			$('#iscp_fids').val(arg[1]);
		}
		else if (arg[0] == 'search')
		{
			var swd = arg[1];
			if (swd.substr(0, 3) == 'wd:')
			{
				$('#iscp_input').val(decodeURIComponent(swd.substr(3)));
			}
			else
			{
				$('#iscp_search').val(swd);
			}
		}
		else if (arg[0] == 'sstr')
		{
			$('#iscp_input').val(decodeURIComponent(arg[1]));
		}
	});
}

function iscp_fidChange()
{
	$__G_ISC_fid = $('#iscp_fids option:selected').val();
}

function iscp_IS_ready()
{
	var swd = $('#iscp_input').val();
	if (swd != '')
	{
		iscp_reallySearch();
	}
}

function iscp_IS_stop()
{
	var div = $('#iscp_iresult');
	div.slideUp('fast');
}

function iscp_doSearch()
{
	var swd = $('#iscp_input').val();
	if (swd == '')
	{
		$.notify.alert('ÇëÊäÈëËÑË÷Ìõ¼þ£¡');
		return;
	}
	swd = encodeURIComponent(swd);
	var sc = $('#iscp_search').val();
	var where = '';
	if (sc != '')
	{
		where = sc;
	}
	else
	{
		where = 'wd:'+swd;
	}
	var source = '&ssrc='+$__G_ISC_fid;
	var string = '&sstr='+swd;
	var rxSearch = /search=.*?$/ig;
	var loc = window.location;
	if (rxSearch.test(loc))
	{
		window.location = loc.toString().replace(rxSearch, 'search='+where+source+string);
	}
	else
	{
		window.location = loc+'&search='+where+source+string;
	}
}

function iscp_iSearch(event)
{
	clearTimeout($__G_ISC_IS_Timer);
	var swd = $('#iscp_input').val();
	if (swd == '')
	{
		iscp_IS_stop();
		return;
	}
	if (event.keyCode == 13)
	{
		iscp_doSearch();
		return;
	}
	$__G_ISC_IS_Timer = setTimeout(iscp_reallySearch, 500);
}

function iscp_reallySearch()
{
	$('#iscp_search').val('');
	iscp_iresult_fixPos();
	var swd = $('#iscp_input').val();
	if (swd == $__G_ISC_Last_Search)
	{
		return;
	}
	$__G_ISC_Last_Search = swd;
	iscp_iresult_fillData('searching for ['+swd+']...');
	$.get('index.php?mod=search&code=ajax&fid='+$__G_ISC_fid+'&wd='+encodeURIComponent(swd)+__url_stamp(), function(data){
		if (data.substr(0, 1) != '{')
		{
			// fails
			return;
		}
		eval('var data='+data);
		if (data.resultCount == 0)
		{
			iscp_iresult_fillData(data.msg);
			return;
		}
		iscp_iresult_fillData(data.results);
	});
}

function iscp_iresult_set(key, val, obj)
{
	$('#iscp_search').val(key+':'+val);
	$('#iscp_input').val($(obj).text());
	iscp_doSearch();
}

function iscp_iresult_fixPos()
{
	var box = $('#iscp_input');
	var div = $('#iscp_iresult');
	div.css('top', box.offset().top+box.height()+8);
	div.css('left', box.offset().left);
	div.slideDown('fast');
}

function iscp_iresult_fillData(data)
{
	if (typeof(data) == 'string')
	{
		$('#iscp_iresult_list').html('<li>'+data+'</li>');
		return;
	}
	var html = '';
	$.each(data, function(i, one){
		html += '<li onclick="iscp_iresult_set(\''+one.key+'\', \''+one.val+'\', this)">'+one.title+'</li>';
	});
	$('#iscp_iresult_list').html(html);
}

function __url_stamp()
{
	return '&timestamp='+(new Date()).getTime();
}