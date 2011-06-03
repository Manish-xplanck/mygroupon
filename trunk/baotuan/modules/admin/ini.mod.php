<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename ini.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    public function Main()
    {
            }
    public function Get()
    {
        $path = get('path', 'txt');
        exit(jsonEncode(ini($path)));
    }
    public function Set()
    {
        $path = get('path', 'txt');
        $data = get('data');
                $data = (strlen($data) <= 5) ? (($data == 'true') ? true : (($data == 'false') ? false : $data)) : $data;
                exit(jsonEncode(ini($path, $data)));
    }
}

?>