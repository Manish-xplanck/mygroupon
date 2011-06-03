<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename notify.drv.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class NotifyDriver
{
    private $name = '';
    
    public function load($name, $conf)
    {
        $this->name = $name;
        $file = dirname(__FILE__).'/notify/'.$name.'.php';
	    include_once $file;
	    $className = $name.'NotifyDriver';
	    return new $className($conf);
    }
    
    public function FlagParser($flag, $data, &$msg)
    {
        if (!is_array($data))
        {
                        $msg = preg_replace('/{\*}/i', $data, $msg);
            return;
        }
                $flags = $this->__GetMsgFlags($flag, $msg, $data);
        foreach ($flags as $name)
        {
            $msg = preg_replace('/{'.$name.'}/i', $data[$name], $msg);
        }
    }
    
    private function __GetMsgFlags($flag, $msg, $data)
    {
        $fid = 'notify.msg.'.$flag;
        $flags = fcache($fid, 3600);
        if ($flags)
        {
            return $flags;
        }
        preg_match_all('/{([a-z0-9\._]+)}/i', $msg, $matchs);
        $flags = array();
        foreach ($matchs[1] as $key)
        {
            $flags[] = $key;
        }
        fcache($fid, $flags);
        return $flags;
    }
}

?>