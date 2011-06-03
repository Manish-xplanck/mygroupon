var $_G_regiond_id = '';

$(document).ready(function(){
	$('input[name=regiond]').bind('click', function(){
		regiond_area_displayer(this);
	});
	$('#region_selector select').bind('change', function(){
		region_loads($(this))
	});
	region_loads(null);
	check_default_set();
});
/**
 * ָ�����͵�ַ�ͷ���
 * @param {Object} tar
 */
function regiond_area_displayer(tar)
{
	var regiond = $(tar).val();
	if (regiond == 0)
	{
		$('.regiond_no_area').fadeOut();
	}
	else
	{
		$('.regiond_no_area').fadeIn();
	}
}
/**
 * ���Ĭ��ѡ��״̬
 */
function check_default_set()
{
	regiond_area_displayer($('input[name=regiond]:checked'));
}
/**
 * ������͵���
 */
function ex_regions_add()
{
	var sid = __rand_key();
	var tpl = $('#ex_region_tpl').html();
	tpl = tpl.replace(/\[ID\]/g, sid);
	$('#ex_region_tpl').before('<li id="ex_region_id_'+sid+'">'+tpl+'</li>');
}
/**
 * ɾ�����͵���
 * @param {Object} id
 */
function ex_regions_del(div, id)
{
	if (!confirm('��ȷ��Ҫɾ����'))
	{
		return;
	}
	if (id == 0)
	{
		$('#ex_region_id_'+div).remove();
		return;
	}
	$.get('?mod=express&code=del&op=regions&id='+id, function(data){
		if (data == 'ok')
		{
			$('#ex_region_id_'+div).remove();
		}
	});
}
function regionCommiter()
{
	var id = $_G_regiond_id;
	if (id == 0) return;
	var province = $('#addr_province').val();
	var provinceName = $('#addr_province option:selected').text();
	var city = $('#addr_city').val();
	var cityName = $('#addr_city option:selected').text();
	var country = $('#addr_country').val();
	var countryName = $('#addr_country option:selected').text();
	if (province == 0)
	{
		alert('ʡ�ݲ�����Ϊ���⣡');
		return;
	}
	var loc = regionSelAdd($('#erl_for_'+id), province, city, country);
	regionSelAddName(id, loc, provinceName, cityName, countryName);
	regionSelector(0);
}
function regionSelAdd(obj, province, city, country)
{
	var loc = province;
	if (city > 0)
	{
		loc += ','+city;
	}
	if (country > 0)
	{
		loc += ','+country;
	}
	loc = '[,'+loc+',]';
	var old = obj.val();
	obj.val(old+loc);
	return loc;
}
function regionSelAddName(id, loc, province, city, country)
{
	var obj = $('#erl_font_'+id);
	var name = province;
	if (city != '����')
	{
		name += ' - '+city;
	}
	if (country != '����')
	{
		name += ' - '+country;
	}
	var pid = 'erl_font_of_'+__rand_key();
	var html = '<font id="'+pid+'">'+name+' | <a href="#void" onclick="javascript:regionSelDel(\''+id+'\', \''+pid+'\', \''+loc+'\');return false;">�Ƴ�</a><br/></font>';
	obj.before(html);
}
function regionSelDel(id, pid, loc)
{
	var obj = $('#erl_for_'+id);
	var list = obj.val();
	list = list.replace(loc, '');
	obj.val(list);
	$('#'+pid).remove();
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
/**
 * ����ѡ����
 */
function regionSelector(id)
{
	$_G_regiond_id = id;
	var obj = $('#region_selector');
	var dsp = obj.css('display');
	if (dsp == 'none')
	{
		obj.slideDown();
	}
	else
	{
		obj.slideUp();
	}
}
/**
 * ������Ϣ����
 * @param {Object} obj
 */
function region_loads(obj)
{
	var tpl_select = '<option value="0">����</option>';
	var tpl_loader = '<option value="">������</option>';
	if (obj == null)
	{
		$('#addr_province').html(tpl_loader);
		$('#addr_city').html(tpl_select);
		$('#addr_country').html(tpl_select);
		$.get('index.php?mod=misc&code=region&parent=0', function(data){
			$('#addr_province').html(tpl_select+data);
		});
		return;
	}
	var id = obj.attr('id');
	if (id == 'addr_country') return;
	var parent = obj.val();
	if (parent == 0) return;
	if (id == 'addr_province')
	{
		$('#addr_city').html(tpl_loader);
		$('#addr_country').html(tpl_select);
		$.get('index.php?mod=misc&code=region&parent='+parent, function(data){
			$('#addr_city').html(tpl_select+data);
		});
	}
	else if (id == 'addr_city')
	{
		$('#addr_country').html(tpl_loader);
		$.get('index.php?mod=misc&code=region&parent='+parent, function(data){
			$('#addr_country').html(tpl_select+data);
		});
	}
}