<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename index.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 





class ModuleObject extends MasterObject
{
    public function __construct($config)
    {
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    public function Main()
    {
        include handler('template')->file('@inizd/index');
    }
}

?>