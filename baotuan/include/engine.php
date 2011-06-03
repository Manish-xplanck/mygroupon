<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename engine.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 





$__engine_global_objs = array();


function logic( $name )
{
        $map = array( 
        'product' => 'ProductLogic',
        'order' => 'OrderLogic',
        'misc' => 'MiscLogic',
        'coupon' => 'CouponLogic',
        'me' => 'MeLogic',
        'notify' => 'NotifyLogic',
        'express' => 'ExpressLogic',
        'address' => 'AddressLogic',
        'safe' => 'SafeLogic',
        'pay' => 'PayLogic',
        'service' => 'ServiceLogic',
        'push' => 'PushLogic',
        'subscribe' => 'SubscribeLogic',
        'upload' => 'UploadLogic',
        'isearcher' => 'iSearcherLogic',
        'account' => 'AccountLogic',
        'seller' => 'SellerLogic',
        'delivery' => 'DeliveryLogic',
        'rbac' => 'RBACLogic',
    );
    return __object_auto_load('logic', $name, $map, LOGIC_PATH, 'logic');
}


function handler( $name )
{
        $map = array( 
        'config' => 'ConfigHandler',
        'cookie' => 'CookieHandler',
        'template' => 'TemplateHandler',
        'member' => 'MemberHandler',
        'upload' => 'UploadHandler',
        'image' => 'ImageHandler',
        'verify' => 'VerifyHandler'
    );
    return __object_auto_load('handler', $name, $map, LIB_PATH, 'han');
}


function driver( $name )
{
    $map = array( 
        'config' => 'ConfigDriver',
        'database' => 'DatabaseDriver',
        'payment' => 'PaymentDriver',
        'notify' => 'NotifyDriver',
        'cache' => 'CacheDriver',
        'service' => 'ServiceDriver',
        'lock' => 'LockDriver',
        'ulogin' => 'UnionLoginDriver',
    	'iaddress' => 'ImportAddressDriver'
    );
    return __object_auto_load('driver', $name, $map, DRIVER_PATH, 'drv');
}


function ui( $name )
{
    $map = array( 
        'widget' => 'WidgetUI',
        'loader' => 'LoaderUI',
        'iimager' => 'iImagerUI',
        'pingfore' => 'PingforeUI',
        'isearcher' => 'iSearcherUI',
        'igos' => 'iGOSUI'
    );
    return __object_auto_load('ui', $name, $map, UI_POWER_PATH, 'ui');
}


function __object_auto_load( $channel, $name, $map, $dir, $midfix )
{
    global $__engine_global_objs;
    $engos = &$__engine_global_objs;
    if ( ! isset($engos['_' . $channel . '_']) )
    {
        $engos['_' . $channel . '_'] = array();
    }
    $ego = &$engos['_' . $channel . '_'];
    if ( isset($ego[$name]) )
    {
        return $ego[$name];
    }
    if ( ! isset($map[$name]) )
    {
        return null;
    }
    require_once $dir . $name . '.' . $midfix . '.php';
    $ego[$name] = new $map[$name]();
    return $ego[$name];
}


function ini()
{
    $argc = func_num_args();
        $key = func_get_arg(0);
    if ($argc == 1)
    {
        return driver('config')->read($key);
    }
    if ($argc == 2)
    {
        $write = func_get_arg(1);
    }
    if ( $write === INI_DELETE )
    {
        driver('config')->delete($key);
    }
    else
    {
        driver('config')->write($key, $write);
    }
    driver('config')->close();
    return true;
}

$__engine_global_db_linker = null;


function dbc($imax = false)
{
    global $__engine_global_db_linker;
    $lnks = &$__engine_global_db_linker;
        $driver = $imax ? 'max' : 'old';
    if (isset($lnks[$driver]))
    {
        $lnk = $lnks[$driver];
    }
    else
    {
                $lnks[$driver] = null;
        $lnk = &$lnks[$driver];
    }
    if ( ! is_null($lnk) )
    {
        return $lnk;
    }
    if (false == $imax)
    {
                $lnk = driver('database')->load('mysql');
        $lnk->ServerHost = ini('settings.db_host');
        $lnk->ServerPort = ini('settings.db_port');
        $lnk->Charset(ini('settings.charset'));
        $lnk->DoConnect(ini('settings.db_user'), ini('settings.db_pass'), ini('settings.db_name'), ini('settings.db_persist'));
    }
    else
    {
                $lnk = driver('database')->load('mysql_max');
        $lnk->config(array(
    		'debug' => DEBUG,
    		'host' => ini('settings.db_host').':'.ini('settings.db_port'),
    		'username' => ini('settings.db_user'),
    		'password' => ini('settings.db_pass'),
    		'database' => ini('settings.db_name'),
                		'prefix' => '',
    		'charset' => ini('settings.charset'),
    		'cached' => 'file://'.CACHE_PATH.'query/'
    	));
    }
    return $lnk;
}


function user($uid = null)
{
    return logic('me')->user($uid);
}


function meta($key, $val = false, $life = 0)
{
    return user(0)->field($key, $val, $life);
}


function rewrite($string)
{
    global $rewriteHandler;
    if (!$rewriteHandler)
    {
        return $string;
    }
    $string = $rewriteHandler->formatURL($string);
    return $string;
}


function account($method = null)
{
    $logicAcc = logic('account');
    if (is_null($method))
    {
        return $logicAcc;
    }
    if (method_exists($logicAcc, $method))
    {
        return $logicAcc->$method();
    }
    else
    {
        exit('[ERROR] # account.('.$method.').404');
    }
}


function notify($uid, $class, $data = array())
{
    return logic('notify')->Call($uid, $class, $data);
}

?>