<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename dbf.mod.php $
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
                list($search, $field) = explode('/', $path);
        list($sWhere, $sTable) = explode('@', $search);
        list($sField, $sValue) = explode(':', $sWhere);
        $sql = 'SELECT '.$field.' FROM '.table($sTable).' WHERE '.$sField.'='.(is_numeric($sValue) ? $sValue : '"'.$sValue.'"');
        $result = dbc()->Query($sql)->GetRow();
        exit(jsonEncode($result[$field]));
    }
    public function Set()
    {
        $path = get('path', 'txt');
        $data = get('data');
                list($search, $field) = explode('/', $path);
        list($sWhere, $sTable) = explode('@', $search);
        list($sField, $sValue) = explode(':', $sWhere);
        $sql = 'UPDATE `'.table($sTable).'` SET `'.$field.'`='.(is_numeric($data) ? $data : '"'.$data.'"').' WHERE `'.$sField.'`='.(is_numeric($sValue) ? $sValue : '"'.$sValue.'"');
        dbc()->Query($sql);
        exit('end');
    }
}

?>