<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename iaddress.drv.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ImportAddressDriver
{
    
    public final function api($name)
    {
	    $SID = 'driver.iaddress.api.'.$name;
        $obj = moSpace($SID);
        if ( ! $obj )
        {
            include_once dirname(__FILE__).'/iaddress/'.$name.'.php';
            $className = $name.'ImportAddressDriver';
            $obj = moSpace($SID, (new $className()));
        }
        return $obj;
    }
}

?>