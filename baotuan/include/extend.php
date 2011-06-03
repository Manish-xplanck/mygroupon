<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename extend.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 






function table($name)
{
    $forSystem = array(
        'failedlogins',
        'log',
        'memberfields',
        'members',
        'onlinetime',
        'report',
        'robot',
        'robot_ip',
        'robot_log',
        'role',
        'role_action',
        'role_module',
        'sessions'
    );
    if (array_search($name, $forSystem))
    {
        return TABLE_PREFIX.'system_'.$name;
    }
    $forBlank = array(
        'task',
        'task_log'
    );
    if (array_search($name, $forBlank))
    {
        return TABLE_PREFIX.$name;
    }
    return TABLE_PREFIX.'tttuangou_'.$name;
}


function get($key, $limit = '')
{
    return logic('safe')->Vars('GET', $key, $limit);
}

function post($key, $limit = '')
{
    return logic('safe')->Vars('POST', $key, $limit);
}

$__T_Page_Moyo_HTML = '';

function page_moyo($sql = '')
{
    global $__T_Page_Moyo_HTML;
    if ($sql == '')
    {
        $css = ui('loader')->css('@moyo.pager');
        return $css.$__T_Page_Moyo_HTML;
    }
    if (isset($_GET[EXPORT_GENEALL_FLAG]) && $_GET[EXPORT_GENEALL_FLAG] == EXPORT_GENEALL_VALUE)
    {
        return $sql;
    }
        $max = page_moyo_max_selector();
    $flag = 'page';
        $sql_count = preg_replace('/select.*?from/is', 'SELECT count(*) AS MCNT FROM', $sql);
        $query = dbc()->Query($sql_count);
    if ($query)
    {
        $result = $query->GetRow();
    }
    else
    {
        $result = array('MCNT' => 0);
    }
    $total = $result['MCNT'];
        if ($total <= $max)
    {
        return $sql;
    }
        $pn = isset($_GET[$flag]) ? (int)$_GET[$flag] : 1;
    if ($pn <= 0) $pn = 1;
        $sql = $sql . ' LIMIT '.($pn-1)*$max.','.$max;
        $url = $_SERVER['REQUEST_URI'];
        $url = page_moyo_rewrite('page.unset', $flag, $url);
    $pa = ceil($total/$max);
    $pre = '';
    if ($pn > 1)
    {
        $pre = '<a href="'.$url.'&'.$flag.'='.($pn-1).'"><font class="page_up">上一页</font></a>';
    }
    $nxt = '';
    if ($pn < $pa)
    {
        $nxt = '<a href="'.$url.'&'.$flag.'='.($pn+1).'"><font class="page_down">下一页</font></a>';
    }
    $pfirst = '<a href="'.$url.'"><font class="page_first">首页</font></a>';
    $plast = '<a href="'.$url.'&'.$flag.'='.$pa.'"><font class="page_last">尾页</font></a>';
        $plist = '';
    $al = 10;
    if ($pn - $al/2 <= 0) $pfrom = 1;
    else $pfrom = $pn - $al/2 + 1;
    $pend = $pfrom + $al - 1;
    for ($pi = $pfrom; $pi < $pend; $pi++)
    {
        if ($pi > $pa) break;
        if ($pi != $pn)
        $plist .= '<a href="'.$url.'&'.$flag.'='.$pi.'"><font class="page_number">['.$pi.']</font></a>';
        else $plist .= '<font class="page_current">'.$pi.'</font> ';
    }
    $html = $pfirst.''.$pre.''.$plist.''.$nxt.''.$plast;
    $html .= '<font class="page_count">共 '.$total.' 条记录，分为 '.$pa.' 页，每页 '.page_moyo_max_selector($max).' 条</font>';
        $__T_Page_Moyo_HTML = page_moyo_rewrite('page.rewrite', $flag, $html);
    return $sql;
}

function page_moyo_rewrite($action, $flag, $content)
{
    global $rewriteHandler;
    if ($action == 'page.unset')
    {
        $pn_find = '/'.$flag.'=\d+/i';
        $pn_replace = '/[&]?'.$flag.'=\d+/';
        if ($rewriteHandler)
        {
            $pn_find = '/\/'.$flag.'-\d+/i';
            $pn_replace = '/[\/]?'.$flag.'-\d+/';
        }
        if (preg_match($pn_find, $content))
        {
            $url = preg_replace($pn_replace, '', $content);
        }
        else
        {
            $url = $content;
        }
        return $url;
    }
    elseif ($action == 'page.rewrite')
    {
        if (!$rewriteHandler)
        {
            return $content;
        }
        return preg_replace('/&'.$flag.'=(\d+)/', '/'.$flag.'-$1', $content);
    }
}

function page_moyo_max_selector($max = null)
{
    if (is_null($max))
    {
                $int = handler('cookie')->GetVar('moyo_pm_int');
        $max = $int ? (int)$int : 12;
        $max = $max ? $max : 12;
        return $max;
    }
        return $max;
        
    $html = '';
    $html = '<select onchange="alert(this.value)">';
    $pfrom = $max/2 + 2;
    $pend = $max + $max/2 - 1;
    for ($pi = $pfrom; $pi < $pend; $pi++)
    {
        if ($pi <= 0) break;
        if ($pi != $max)
            $html .= '<option value="'.$pi.'">'.$pi.'</option>';
        else
            $html .= '<option value="'.$pi.'" selected="selected">'.$pi.'</option>';
    }
    $html .= '</select>';
    return $html;
}

$__T_Cache_Storage = array();


function cached($key, $val = '')
{
    global $__T_Cache_Storage;
    $cd = &$__T_Cache_Storage;
    if ($val == '')
    {
        return isset($cd[$key]) ? $cd[$key] : false;
    }
    $cd[$key] = $val;
    return $val;
}


function fcache($key, $mixed)
{
    if (is_numeric($mixed))
    {
        return driver('cache')->read($key, $mixed);
    }
    else
    {
        return driver('cache')->write($key, $mixed);
    }
}


$__S_lock_driver = null;

function locked($name, $lock = null)
{
    
    global  $__S_lock_driver;
    $lck = &$__S_lock_driver;
    if (is_null($lck))
    {
        $lck = driver('lock');
    }
    if ($lock === null)
    {
        return $lck->islocked($name);
    }
    return $lck->locks($name, $lock);
}


function moSpace( $SID, &$Storage = null )
{
    global $__engine_global_objs;
    $obj = &$__engine_global_objs;
    if ( ! is_null($Storage) )
    {
        $obj[$SID] = $Storage;
        return $Storage;
    }
    if ( ! isset($obj[$SID]) )
    {
        return false;
    }
    return $obj[$SID];
}


function loadInstance($SID, $className)
{
    $obj = moSpace($SID);
    if ( ! $obj )
    {
        $obj = moSpace($SID, (new $className()));
    }
    return $obj;
}


function mocod()
{
    $mod = isset($_GET['mod']) ? $_GET['mod'] : $_POST['mod'];
    if ($mod == '') $mod = 'index';
    $code = isset($_GET['code']) ? $_GET['code'] : $_POST['code'];
    if ($code == '') $code = 'main';
    return $mod.'.'.$code;
}


function imager($id, $size = IMG_Original, $height = 0)
{
    if ($size > 0)
    {
        $width = $size;
    }
    elseif ($size == IMG_Original)
    {
        $width = 0;
        $height = 0;
    }
    elseif ($size == IMG_Tiny)
    {
        $width = 80;
        $height = 60;
    }
    elseif ($size == IMG_Small)
    {
        $width = 200;
        $height = 121;
    }
    elseif ($size == IMG_Normal)
    {
        $width = 450;
        $height = 268;
    }
    $file = logic('upload')->GetOne($id);
    if ($width == 0 && $height == 0)
    {
        return $file['url'];
    }
    if ($file['extra'] == '')
    {
        $extra = handler('image')->Info($file['path']);
        $data['extra'] = serialize(array(
            'width' => $extra['width'],
            'height' => $extra['height']
        ));
        logic('upload')->Update($id, $data);
    }
    else
    {
        $extra = unserialize($file['extra']);
    }
    $srcWidth = $extra['width'];
    $srcHeight = $extra['height'];
    if ($srcWidth < $width && $srcHeight < $height)
    {
        return $file['url'];
    }
    $upd = UPLOAD_PATH;
    $upt = UPLOAD_PATH.'thumb/'.$width.'x'.$height.'/';
    $thumb = str_replace($upd, $upt, $file['path']);
    if (is_file($thumb))
    {
        $file = $thumb;
    }
	else
	{
		$file = handler('image')->Thumb($file['path'], $thumb, $file['type'], $width, $height);
	}
    $file = ini('settings.site_url').str_replace('./', '/', $file);
    return $file;
}


function timebefore($time, $nosign = false)
{
    if ($time <= 0)
    {
        return '时间错误！';
    }
    $now = time();
    if ($time > $now)
    {
        return '还未开始！';
    }
    return __timeUnit($now - $time).($nosign ? '' : ' 前');
}


function timeless($time, $sign = null)
{
    if ($time <= 0)
    {
        return '时间错误！';
    }
    $now = time();
    if ($time < $now)
    {
        return '已经结束！';
    }
    return '剩余 '.__timeUnit($now - $time);
}


function __timeUnit($ss, $uc = 1)
{
    $timeCalc = array(
        '天' => 86400,
        '小时' => 3600,
        '分' => 60,
        '秒' => 1
    );
    $return = '';
    foreach ($timeCalc as $name => $secs)
    {
        if ($ss >= $secs)
        {
            $sc = floor($ss / $secs);
            $return .= $sc.' '.$name;
            $ss -= $sc * $secs;
            $uc --;
        }
        if ($uc == 0) break;
    }
    return $return;
}


define('ENC_IS_GBK', (strtolower(ini('settings.charset')) == 'gbk'));

define('X_IS_AJAX', (strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'));


function jsonEncode($value)
{
    if (ENC_IS_GBK)
    {
        if (is_array($value))
        {
            array_walk_recursive($value, '__enc_for_jsonEncode');
        }
        elseif (is_string($value))
        {
            __enc_g2u($value);
        }
    }
    return json_encode($value);
}
function __enc_for_jsonEncode(&$value, &$key)
{
    if (is_string($key))
    {
        $key = ENC_G2U($key);
    }
    if (is_string($value))
    {
        $value = ENC_G2U($value);
    }
}


function ENC_G2U($value)
{
    return __enc_g2u($value);
}
function __enc_g2u(&$value)
{
	$backup = $value;
    $value = iconv('GBK', 'UTF-8/'.'/IGNORE', $value);
	if (empty($value))
    {
    	$value = $backup;
    }
    return $value;
}


function ENC_U2G($value)
{
    return __enc_u2g($value);
}
function __enc_u2g(&$value)
{
	$backup = $value;
    $value = iconv('UTF-8', 'GBK/'.'/IGNORE', $value);
    if (empty($value))
    {
    	$value = $backup;
    }
    return $value;
}

?>