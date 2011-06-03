<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename apiz.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    function ModuleObject( & $config )
    {
        $this->MasterObject($config);
        Load::moduleCode($this);$this->Execute();
    }
    function Execute()
    {
        if ( 'js' == $this->Code )
        {
            $this->JsResponse();
        }
    }
    function JsResponse()
    {
        $product = logic('product')->GetFirst();
        include ($this->TemplateHandler->Template('tttuangou_data_show_js'));
    }
}
?>