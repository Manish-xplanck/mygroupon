<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename init.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




function microtime_float()
{
    list ($usec, $sec) = explode(" ", microtime());
    return (( float )$usec + ( float )$sec);
}

function gzip_ops( &$buffer, $mode = 5 )
{
    if ( GZIP === true && function_exists('ob_gzhandler') && substr_count($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip') )
    {
        $buffer = ob_gzhandler($buffer, $mode);
    }
    return $buffer;
}

class initialize
{
    var $config = array();
    var $allowModules = array();
    function initialize()
    {
        require_once 'setting/settings.php';
        $this->config = $config['settings'];
    }
    function envInit()
    {
        if ( DEBUG )
        {
            error_reporting(E_ALL ^ E_NOTICE);
        }
        else
        {
            error_reporting(0);
        }
        @set_time_limit(30);
        @ini_set("arg_seperator.output", "&amp;");
        @ini_set("magic_quotes_runtime", 0);
        header('Content-Type: text/html; charset=' . $this->config['charset']);
        if ( version_compare(phpversion(), '5.1.0', '>=') )
        {
            date_default_timezone_set('PRC');
        }
        else
        {
            putenv("PRC");
        }
                define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());         define('ROOT_PATH', './');                 define('UPLOAD_PATH', ROOT_PATH.'uploads/');         define('CACHE_PATH', ROOT_PATH . 'cache/');         define('DATA_PATH', ROOT_PATH . 'data/');         define('INCLUDE_PATH', ROOT_PATH . "include/");         define('DB_DRIVER_PATH', INCLUDE_PATH . "db/");         define('LIB_PATH', INCLUDE_PATH . "lib/");         define('FUNCTION_PATH', INCLUDE_PATH . "function/");         define('TASK_PATH', INCLUDE_PATH . "task/");         define('LOGIC_PATH', INCLUDE_PATH . "logic/");         define('DRIVER_PATH', INCLUDE_PATH . 'driver/');         define('UI_POWER_PATH', INCLUDE_PATH . 'ui/');         define('CONFIG_PATH', ROOT_PATH . "setting/");         

                if ( !is_file(DATA_PATH.'install.lock') && 'inizd.php' != strstr($_SERVER['PHP_SELF'], 'inizd.php') )
        {
            header('Location: inizd.php?mod=install');
            exit;
        }
                if ( file_exists('./cache/site_enable.php') && 'account' != $_GET['mod'] && !stristr($_SERVER['PHP_SELF'], 'admin.php'))
        {
            die(file_get_contents('./cache/site_enable.php'));
        }
                if ( is_file('./cache/upgrade.lock') && filemtime('./cache/upgrade.lock') + 600 > time() )
        {
            die("now is Updating...");
        }
    }
    function allowMod( $modules )
    {
        $allowModules = array();
        $mSplits = explode(',', $modules);
        foreach ( $mSplits as $i => $module )
        {
            $allowModules[$module] = true;
        }
        $this->allowModules = $allowModules;
    }
    function load( $module )
    {
        if ( $module == 'index' )
        {
            $module = '';
        }
        define('MOD_PATH', ROOT_PATH . 'modules/' . $module . '/');
                switch ( $module )
        {
            case 'ajax':
                header("Cache-Control: no-cache, must-revalidate");
		        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		    break;
        }
        $time_start = microtime_float();
                ob_start("gzip_ops");
        $this->run($module);
        ob_end_flush();
                $time_finish = microtime_float();
        $time_use = (string)($time_finish - $time_start).' second(s)';
        if (DEBUG && $_GET['mod'] != 'callback')
        {
            $mod = $_GET['mod'];
            if ($mod != 'misc' && $_SERVER['REQUEST_METHOD'] != 'POST' && DEBUG)
            {
                                            }
        }
    }
    function run($module)
    {
        $config = $this->config;
                if ($module != 'admin')
        {
            global $rewriteHandler;
            include_once './include/rewrite.php';
        }
                require_once DRIVER_PATH . 'i18n.php';
        i18n_init($config['language']);
                require_once LIB_PATH . 'config.han.php';
                include_once CONFIG_PATH . 'robot.php';
                require_once FUNCTION_PATH . 'common.func.php';
                define('MY_QUERY_ERROR', 10);
                require_once CONFIG_PATH . 'constants.php';         require_once CONFIG_PATH . 'credits.php';                 require_once FUNCTION_PATH . 'cache.func.php';
                require_once FUNCTION_PATH . 'global.func.php';
                require_once INCLUDE_PATH . 'load.php';
                require_once LIB_PATH . 'http.han.php';
                require_once LIB_PATH . 'template.han.php';
                require_once LIB_PATH . 'form.han.php';
                require_once DB_DRIVER_PATH . 'database.db.php';
		require_once DB_DRIVER_PATH . "mysql.db.php";
				require_once INCLUDE_PATH . 'constant.php';
				require_once INCLUDE_PATH . 'engine.php';
				require_once INCLUDE_PATH . 'extend.php';
                require_once MOD_PATH . 'master.mod.php';
                require_once MOD_PATH . $this->accessMod($config['default_module']) . '.mod.php';
                $_GET = HttpHandler::checkVars($_GET);
        $_POST = HttpHandler::checkVars($_POST);
        $moduleobject = new ModuleObject($config);
        $module != 'inizd' && handler('member')->SaveActionToLog($moduleobject->Title);
        unset($moduleobject);
    }
    function accessMod( $default = 'index' )
    {
        $modss = $this->allowModules;
        $mod = (isset($_POST['mod']) ? $_POST['mod'] : $_GET['mod']);
        if ( !$mod ) $mod = $default;
        if (DEBUG && $modss['*'])
        {
                    }
        else
        {
            if ( !isset($modss[$mod]) || !$modss[$mod])
            {
                include (INCLUDE_PATH . 'error_404.php');
                exit();
            }
        }
        $_POST['mod'] = $_GET['mod'] = $mod;
        return $mod;
    }
}
?>