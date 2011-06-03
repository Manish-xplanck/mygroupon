var IMG_LOADING = 'templates/admin/images/btn_loading.gif';

var __img_last_id = '';
var __img_control_d = false;

$(document).ready(function(){
	$.each($('.editor'), function(i, n){
		var iid = 'editor_'+__rand_key();
		$(this).attr('id', iid);
		KE.show({id:iid});
	});
	var cfg = {};
		cfg.trigger = 'click';
	if (!$.browser.msie)
	{
		cfg.action = 'roll';
		cfg.direct = 'left';
	}
	$('#divFormer').KandyTabs(cfg);
	// thickbox
	tb_init('a.thickbox');
	// hook for Swfupload
	$.hook.add('swfuploaded', function(file){InsertImage(file)});
});

function introFocus(obj)
{
	$(obj).attr('last', $(obj).val());
}

function introChange(id, obj)
{
	if ($(obj).attr('last') == $(obj).val())
	{
		return;
	}
	$(obj).attr('last', $(obj).val());
	var TMP_id = 'img_loading_'+__rand_key();
	$(obj).after('<img id="'+TMP_id+'" src="'+IMG_LOADING+'" />');
	$.get('?mod=product&code=save&op=intro&id='+id+'&intro='+encodeURIComponent($(obj).val()), function(data){
		if (data != 'ok')
		{
			alert('±£´æÊ§°Ü£¡');
		}
		$('#'+TMP_id).remove();
	});
}

function InsertImage(file)
{
	if (__Global_PID == '')
	{
		$('#imgs').val($('#imgs').val()+file.id+',');
		ShowUploadImage(file);
		return;
	}
	$.get('?mod=product&code=add&op=image&pid='+__Global_PID+'&id='+file.id, function(data){
		if (data == 'ok')
		{
			ShowUploadImage(file);
		}
	});
}

function ShowUploadImage(file)
{
	var tpl = $('#img_li_TPL').html();
	tpl = tpl.replace(/http\:\/\/\[ID\]/g, file.id);
	tpl = tpl.replace(/http\:\/\/\[URL\]/g, file.url);
	$('#img_li_TPL').before('<li id="img_li_for_'+file.id+'">'+tpl+'</li>');
}

function DeleteImage(id)
{
	if (!confirm('È·ÈÏÉ¾³ý£¿')) return;
	$.get('?mod=product&code=del&op=image&pid='+__Global_PID+'&id='+id, function(data){
		if (data == 'ok')
		{
			if (__Global_PID == '')
			{
				$('#imgs').val($('#imgs').val().replace(id+',', ''));
			}
			$('#img_li_for_'+id).slideUp();
		}
	});
}

/**
 * Ëæ»ú×Ö·û
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