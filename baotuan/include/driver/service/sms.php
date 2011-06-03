<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename sms.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class smsServiceDriver extends ServiceDriver
{
    public $Debug = false;
    public $Errored = false;
    public $errorMsg = '';
    private $__debugs = array();
    private $__apis = array();

    public final function api($driver)
    {
        $api = &$this->__apis[$driver];
        if (!is_null($api))
        {
            return $api;
        }
        $file = dirname(__FILE__).'/sms.ls.php';
	    include_once $file;
	    $className = $driver.'_smsServiceDriver';
	    $api = new $className();
	    $api->config($this->conf);
	    return $api;
    }
    public final function Send($phone, $content)
    {
        return $this->api($this->conf['driver'])->IMSend($phone, $content);
    }
    public final function Status()
    {
        return $this->api($this->conf['driver'])->IMStatus();
    }
    public final function Get($url)
    {
        return dfopen($url, 10485760, '', '', true);
    }
    public final function Debug($msg = null)
    {
        if (!$this->Debug) return;
        if (is_null($msg)) return $this->__debugs;
        list($ms, $s) = explode(' ', microtime());
        $this->__debugs[] = array(
            'timer' => (float)($s+$ms),
            'msg' => $msg."\r\n"
        );
    }
    public final function Error($msg)
    {
        $this->Errored = true;
        $this->errorMsg = $msg;
    }
}

?>