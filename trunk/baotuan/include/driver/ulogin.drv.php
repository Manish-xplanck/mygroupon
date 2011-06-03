<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename ulogin.drv.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class UnionLoginDriver
{
    
    public final function api($name)
    {
	    $SID = 'driver.ulogin.api.'.$name;
        $obj = moSpace($SID);
        if ( ! $obj )
        {
            include_once dirname(__FILE__).'/ulogin/'.$name.'.php';
            $className = $name.'UnionLoginDriver';
            $obj = moSpace($SID, (new $className()));
        }
        return $obj;
    }
}

?>