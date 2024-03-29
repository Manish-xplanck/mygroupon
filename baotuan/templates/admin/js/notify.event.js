var IMG_ENABLE = 'templates/admin/images/btn_enable.png';
var IMG_DISABLE = 'templates/admin/images/btn_disable.gif';
var IMG_EDITOR = 'templates/admin/images/btn_editor.gif';
var IMG_DELETE = 'templates/admin/images/btn_delete.png';
var IMG_LOADING = 'templates/admin/images/btn_loading.gif';

$(document).ready(function(){
	$('.enable,.ol_enable').attr('src', IMG_ENABLE);
	$('.disable,.ol_disable').attr('src', IMG_DISABLE);
	$('.editor,.ol_editor').attr('src', IMG_EDITOR);
	$('.delete,.ol_delete').attr('src', IMG_DELETE);
	$.each($('img.enable,img.disable'), function(i, one){
		var tar = $(one);
		var SWC_TO = 'enable';
		if (tar.attr('class') == SWC_TO)
		{
			SWC_TO = 'disable';
		}
		var link = '?mod=notify&code=event&op=switch&hook='+tar.attr('title')+'&power='+SWC_TO;
		var id = 'idx_'+__rand_key();
		tar.wrap('<a id="'+id+'" href="#void" onclick="javascript:pow_switch(\''+id+'\', \''+link+'\');return false;"></a>');
	});
	$.each($('.editor'), function(i, one){
		var TMP_id = 'editor_lnk_'+__rand_key();
		$(one).wrap('<a id="'+TMP_id+'" style="margin-left:10px;" class="thickbox" href="#void" onclick="javascript:msg_editor(\''+TMP_id+'\', \''+$(one).attr('title')+'\');return false;"></a>');
	});
	$.each($('.delete'), function(i, one){
		var TMP_id = 'delete_lnk_'+__rand_key();
		$(one).wrap('<a id="'+TMP_id+'" href="#void" onclick="javascript:event_delete(\''+TMP_id+'\', \''+$(one).attr('title')+'\');return false;"></a>');
	});
	$('.cname').css('border', '1px solid #fff').bind('mouseover', function(){
		$(this).css('border', '1px solid #6699CC');
	}).bind('mouseout', function(){
		$(this).css('border', '1px solid #fff');
	}).bind('focus', function(){
		$(this).attr('tmp', $(this).val());
	}).bind('blur', function(){
		if ($(this).val() != $(this).attr('tmp'))
		{
			var TMP_id = 'img_loading_'+__rand_key();
			$(this).after('<img id="'+TMP_id+'" src="'+IMG_LOADING+'" />');
			$.get('?mod=notify&code=event&op=rename&hook='+$(this).attr('title')+'&name='+encodeURIComponent($(this).val()), function(data){
				if (data != 'ok')
				{
					alert('保存失败！');
				}
				$('#'+TMP_id).remove();
			});
		}
	});
	$('#msgSubmit').bind('click', function(){
		editorSubmit();
	});
	$('#tagsClear').bind('click', function(){
		if (!confirm('清理标记后，您必须开启事件监听并重新触发该事件时系统才能获取到数据结构，确认清理吗？\n（普通用户建议不要清理）')) return;
		$.get('?mod=notify&code=tag&op=clear&hook='+$(this).attr('title'), function(data){
			if (data != 'ok')
			{
				$.notify.failed(data);
				return;
			}
			$.notify.show('清理完成！请重新打开编辑器。');
			tb_remove();
		});
	});
});

function event_delete(lnk, event)
{
	if (!confirm('确定要删除吗？')) return;
	var lnkIMG = $('#'+lnk+' img');
	lnkIMG.attr('src', IMG_LOADING);
	$.get('?mod=notify&code=event&op=delete&hook='+event, function(data){
		if (data == 'ok')
		{
			// remove event
			$('#tr_of_'+event).slideDown('slow', function(){
				$('#tr_of_'+event).remove();
			});
		}
		else
		{
			lnkIMG.attr('src', IMG_DELETE);
			$.notify.failed(data);
		}
	});
}

function msg_editor(tar, hook)
{
	var TMP_id = 'img_loading_'+__rand_key();
	$('#'+tar).after('<img id="'+TMP_id+'" style="margin-left:10px;" src="'+IMG_LOADING+'" />');
	$.get('?mod=notify&code=event&op=msg&hook='+hook, function(data){
		eval('var data='+data);
		if (data.status == 'ok')
		{
			$('#msgContent').val(data.msg);
			$('#al2user').attr('checked', data.al2user ? 'checked' : '');
			$('#cc2admin').attr('checked', data.cc2admin ? 'checked' : '');
			var html = '<ul>';
			$.each(data.tags, function(i, n){
				if (n != '')
				{
					html += '<li onclick="javascript:msg_flag_insert(this);">{'+n+'}</li>';
				}
			});
			html += '</ul>';
			$('#msgTags').html(html);
			$('#tagsClear').attr('title', hook);
			$('#msgSubmit').attr('title', hook);
			tb_show(data.name, '#TB_inline?height=200&width=500&inlineId=msgEditor', false);
		}
		else
		{
			alert('加载失败！');
		}
		$('#'+TMP_id).remove();
	});
}

function msg_flag_insert(obj)
{
	$('#msgContent').val($('#msgContent').val() + $(obj).text());
}

function editorSubmit()
{
	var hook = $('#msgSubmit').attr('title');
	var TMP_id = 'img_loading_'+__rand_key();
	$('#msgSubmit').after('<img id="'+TMP_id+'" style="margin-left:10px;" src="'+IMG_LOADING+'" />');
	$.post('?mod=notify&code=event&op=save&hook='+hook, {
		msg: $('#msgContent').val(),
		al2user: $('#al2user').attr('checked') ? 'true' : 'false',
		cc2admin: $('#cc2admin').attr('checked') ? 'true' : 'false',
		FORMHASH: $('#formHash').val()
	}, function(data){
		if (data != 'ok')
		{
			alert('保存失败！');
		}
		else
		{
			tb_remove();
		}
		$('#'+TMP_id).remove();
	});
}

function pow_switch(id, href)
{
	var lnk = $('#'+id);
	var img = lnk.children('img');
	$(img).attr('src', IMG_LOADING);
	lnk.attr('href', '#void');
	lnk.bind('click', function(){return false;});
	$.get(href, function (data){
		var SWC_LNK = '';
		if (data == 'enable')
		{
			$(img).attr('src', IMG_ENABLE);
			SWC_LNK = href.replace('power=enable', 'power=disable');
		}
		else
		{
			$(img).attr('src', IMG_DISABLE);
			SWC_LNK = href.replace('power=disable', 'power=enable');
		}
		lnk.attr('href', '#void');
		lnk.bind('click', function(){pow_switch(id, SWC_LNK);});
	});
}

function event_test()
{
	var TMP_id = 'img_loading_'+__rand_key();
	$('#event_test_lnk').after('<img id="'+TMP_id+'" style="margin-left:10px;" src="'+IMG_LOADING+'" />');
	$.get('?mod=notify&code=event&op=test', function (data){
		if (data != 'ok')
		{
			alert('测试失败！');
		}
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