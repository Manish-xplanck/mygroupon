<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename load.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 

class Load
{
	function functions($name)
	{
		return include_once(FUNCTION_PATH.$name.'.func.php');
	}
	function logic($name)
	{
		return include_once(LOGIC_PATH.$name.'.logic.php');
	}
	function lib($name)
	{
		return include_once(LIB_PATH.$name.'.han.php');
	}
    function driver($name)
	{
	    return include_once(DRIVER_PATH.$name.'.drv.php');
	}
	function moduleCode($class)
	{
	    $code = $class->Code;
	    $extend = $class->OPC;
	    $runs = 'main';
	    if (preg_match('/[a-z0-9_]/i', $code))
        {
            if ($extend != '' && preg_match('/[a-z0-9_]/i', $extend))
            {
                $code .= '_'.$extend;
            }
            $runs = $code;
            
            if (DEBUG && !method_exists($class, $runs))
            {
                $runs = 'main';
            }
        }
                logic('rbac')->Access($class->FILE, $class->Module, $runs);
        return $runs;
	}
}
?>