<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename safe.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class SafeLogic
{
    private $__vf_driver = null;
    
    function Vars($method, $key, $limit)
    {
        switch ($method)
        {
            case 'POST':
                $var = &$_POST;
                break;
            default:
                $var = &$_GET;
        }
        if ($key == '')
        {
            return $var;
        }
        if ($limit == '')
        {
            $igc = isset($var[$key]) ? $var[$key] : false;
        }
        else
        {
            $igc = $var[$key];
            $loops = explode(';', $limit);
            foreach ($loops as $i => $rule)
            { 
                $igc = ($igc !== false) ? $this->__vars_filter($rule, $igc) : false;
            }
        }
        if (ENC_IS_GBK)
        {
            $IS_GET = ($_SERVER['REQUEST_METHOD'] == 'GET');
            if (is_string($igc) && $IS_GET)
            {
                $igc = iconv('UTF-8', 'GBK/'.'/IGNORE', $igc);
            }
            elseif (is_string($igc) && X_IS_AJAX)
			{
				$igc = iconv('UTF-8', 'GBK/'.'/IGNORE', $igc);
			}
        }
        return $igc;
    }
    private function __vars_filter($rule, $val)
    {
        if ($this->__vf_driver == null)
        {
            $this->__vf_driver = new SafeLogicVarsFilter();
        }
        if (method_exists($this->__vf_driver, $rule))
        {
            return $this->__vf_driver->$rule($val);
        }
        return $val;    
    }
}


class SafeLogicVarsFilter
{
    function int($val)
    {
        return (int)$val;
    }
    function number($val)
    {
        return is_numeric($val) ? $val : false;
    }
    function txt($val)
    {
        return htmlspecialchars($val);
    }
    function float($val)
    {
        return (float)$val;
    }
}
?>