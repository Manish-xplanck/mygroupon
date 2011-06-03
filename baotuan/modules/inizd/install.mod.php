<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename install.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    public function __construct($config)
    {
        if (is_file(DATA_PATH.'install.lock'))
        {
            return $this->Alert('���Ѱ�װ���������°�װ����ɾ�� '.DATA_PATH.' Ŀ¼�µ�install.lock�ļ���');
        }
        if (ini('settings.site_domain') == 'localx.uuland.org')
        {
                        ini('settings.site_domain', $_SERVER['HTTP_HOST']);
            ini('settings.site_url', rtrim(htmlspecialchars('http:/'.'/'.$_SERVER['HTTP_HOST'].preg_replace("/\/+/",'/',str_replace("\\",'/',dirname($_SERVER['PHP_SELF']))."/")),'/'));
        }
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    public function Main()
    {
        include handler('template')->file('@inizd/install/welcome');
    }
    private function Alert($text)
    {
        include handler('template')->file('@inizd/alert');
    }
    public function Env()
    {
                $env = array();
        $env['os'] = array('val' => PHP_OS, 'sp' => true);
        $env['phpv'] = array('val' => PHP_VERSION, 'sp' => (PHP_VERSION > '5'));
        $_up_allow = intval(@ini_get('file_uploads'));
        $_up_max_size = @ini_get('upload_max_filesize');
        $env['upload'] = array('val' => ($_up_allow ? '����/���'.$_up_max_size : '������'), 'sp' => $_up_allow);
        if (function_exists('gd_info'))
        {
            $gdfunction = 'gd_info';
            $gd = $gdfunction();
            $gdv = $gd['GD Version'];
        }
        else
        {
            $gdv = 'δ֪�汾';
        }
        $env['gd'] = array('val' => $gdv, 'sp' => true);
        $_free_space = intval(diskfreespace('.') / (1024 * 1024));
        if ($_free_space > 0)
        {
        	$env['space'] = array('val' => $_free_space.'MB', 'sp' => ($_free_space > 10));
        }
        else
        {
        	$env['space'] = array('val' => 'δ֪�ռ��С', 'sp' => true);
        }
        $rwList = array(
            'setting/',
            'cache/',
            'errorlog/',
            'data/',
            'uploads/',
            'templates/widget/'
        );
        $fcList = array(
            'mysql_connect',
            'fsockopen',
            'file_get_contents',
            'file_put_contents'
        );
        $dir = $this->DirPermission($rwList);
        $function = $this->FunctionTest($fcList);
        include handler('template')->file('@inizd/install/env');
    }
    public function DBS()
    {
        include handler('template')->file('@inizd/install/dbs');
    }
    public function DBS_save()
    {
        if ($_SERVER['REQUEST_METHOD'] != 'POST')
        {
            return $this->Alert('����ʽ��Ч��');
        }
        $db = post('db');
        $handler = @mysql_connect($db['host'], $db['username'], $db['password']);
        if (!$handler)
        {
            return $this->Alert('�޷��������ݿ��������');
        }
        if (!@mysql_select_db($db['name']))
        {
            $sql = 'CREATE DATABASE IF NOT EXISTS `'.$db['name'].'` DEFAULT CHARACTER SET '.str_replace('-', '', ini('settings.charset'));
            @mysql_query($sql);
            if (!@mysql_select_db($db['name']))
            {
                return $this->Alert('û���ҵ����ݿ⣬���������ʺ�û��Ȩ�޴����µ����ݿ⣡');
            }
        }
                if (strstr($db['host'], ':'))
        {
            list($host, $port) = explode(':', $db['host']);
        }
        else
        {
            $host = $db['host'];
            $port = '3306';
        }
        ini('settings.db_host', $host);
        ini('settings.db_port', $port);
        ini('settings.db_user', $db['username']);
        ini('settings.db_pass', $db['password']);
        ini('settings.db_name', $db['name']);
        ini('settings.db_table_prefix', ($db['prefix'] != '') ? $db['prefix'] : 'tttuangou_'.$this->RandString(6).'_');
                header('Location: ?mod=install&code=config');
    }
    public function Config()
    {
        include handler('template')->file('@inizd/install/config');
    }
    public function Config_save()
    {
        $c = post('c');
        if (trim($c['username']) == '')
        {
            return $this->Alert('�û�������Ϊ�գ�');
        }
        if ($c['password'] != $c['repassword'])
        {
            return $this->Alert('�������벻һ�£�');
        }
        if ($c['password'] == '')
        {
            return $this->Alert('���벻��Ϊ�գ�');
        }
        if ($c['email'] == '')
        {
            return $this->Alert('�����ַ����Ϊ�գ�');
        }
                ini('__install_config_temp', $c);
        header('Location: ?mod=install&code=install');
    }
    public function Install()
    {
        $test = ini('__install_config_temp.test');
        include handler('template')->file('@inizd/install/install');
    }
    public function Process_struct()
    {
        $this->RunSQL('struct');
        $this->RunSQL('data');
        $this->RunSQL('regions');
    }
    public function Process_admin()
    {
        $c = ini('__install_config_temp');
        $sql = file_get_contents(DATA_PATH.'install/admin.sql');
        $sql = preg_replace('/\{\$username\}/', $c['username'], $sql);
        $sql = str_replace('{$password}', md5($c['password']), $sql);
        $sql = str_replace('{$email}', $c['email'], $sql);
        $this->RunSQL($sql);
    }
    public function Process_setting()
    {
        $c = ini('__install_config_temp');
        ini('settings.site_name', $c['sitename']);
        ini('settings.site_admin_email', $c['email']);
        ini('settings.auth_key', $this->RandString(16));
        ini('settings.cookie_prefix', 'TTtuangou_'.$this->RandString(6).'_');
    }
    public function Process_test()
    {
        $this->RunSQL('test');
    }
    public function Process_clean()
    {
        @unlink(CONFIG_PATH.'__install_config_temp.php');
    }
    public function Process_ends()
    {
        file_put_contents(DATA_PATH.'install.lock', date('Y-m-d H:i:s', time()));
    }
    public function Process_lives()
    {
		$this->iLinks();
        $this->iLives();
    }
    private function DirPermission($list)
    {
        $return = array();
        foreach ($list as $i => $dir)
        {
            $result = array();
            $result['dir'] = $dir;
            $path = ROOT_PATH.$dir;
            if (is_dir($path))
            {
                $file = $path.'.tttg.dir.permission.test';
                if (!@file_put_contents($file, 'moyo'))
                {
                    $result['rw'] = false;
                }
                else
                {
                    if (@file_get_contents($file) != 'moyo')
                    {
                        $result['rw'] = false;
                    }
                    else
                    {
                        $result['rw'] = true;
                        @unlink($file);
                    }
                }
            }
            $return[] = $result;
        }
        return $return;
    }
    private function FunctionTest($list)
    {
        $return = array();
        foreach ($list as $i => $func)
        {
            $return[] = array(
                'name' => $func,
                'sp' => function_exists($func)
            );
        }
        return $return;
    }
    private function RandString($length)
    {
    	$hash = '';
    	$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
    	$max = strlen($chars) - 1;
    	PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
    	for($i = 0; $i < $length; $i++) {
    		$hash .= $chars[mt_rand(0, $max)];
    	}
    	return $hash;
    }
    private function RunSQL($file)
    {
        if (strlen($file) < 12)
        {
            $sql = @file_get_contents(DATA_PATH.'install/'.$file.'.sql');
        }
        else
        {
            $sql = $file;
        }
        if ($sql == '') return;
    	$sql = str_replace("\r", "\n", str_replace('`{prefix}', "`" . ini('settings.db_table_prefix'), $sql));
    	$sql = preg_replace('/\/\*.*?\*\/[;]?/s', '', $sql);
    	$ret = array();
    	$num = 0;
    	foreach(explode(";\n", trim($sql)) as $query) {
    		$queries = explode("\n", trim($query));
    		foreach($queries as $query) {
    			$ret[$num] .= $query[0] == '#' || $query[0].$query[1] == '--' ? '' : $query;
    		}
    		$num++;
    	}
    	unset($sql);

    	$dbcharset = str_replace('-', '', ini('settings.charset'));
    	
    	foreach($ret as $query) {
    		$query = trim($query);
    		if($query) {
    			if(substr($query, 0, 13) == 'CREATE TABLE ') {
    				$name = preg_replace("/CREATE TABLE .*?([a-z0-9_]+)`? .*/is", "\\1", $query);
    				$_sql = $this->Createtable($query, $dbcharset);
    				dbc()->Query($_sql);
    			} else {
    				dbc()->Query($query);
    			}
    		}
    	}
    }
    private function Createtable($sql, $dbcharset)
    {
	    $type = strtoupper(preg_replace("/^\s*CREATE TABLE\s+.+\s+\(.+?\).*(ENGINE|TYPE)\s*=\s*([a-z]+?).*$/isU", "\\2", $sql));
	    $type = in_array($type, array('MYISAM', 'HEAP')) ? $type : 'MYISAM';
	    return preg_replace("/^\s*(CREATE TABLE\s+.+\s+\(.+?\)).*$/isU", "\\1", $sql).
		(mysql_get_server_info() > '4.1' ? " ENGINE=$type DEFAULT CHARSET=$dbcharset" : " TYPE=$type");
    }
    private function iLinks()
    {
        include_once MOD_PATH.'install.live.php';
        install_links();
    }
    private function iLives()
    {
        include_once MOD_PATH.'install.live.php';
        install_request(array(),$install_request_error);
    }
}

?>