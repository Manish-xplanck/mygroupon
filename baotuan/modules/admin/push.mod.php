<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename push.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    public function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    public function Main()
    {
        $this->Queue();
    }
    public function Queue()
    {
        $rund = get('rund', 'txt');
        if (!$rund) $rund = 'false';
        $list = logic('push')->ListQueue($rund);
        include handler('template')->file('@admin/push_queue');
    }
    public function Queue_clear()
    {
        $rund = get('rund', 'txt');
        $sql_limit_time = '`update` '.$this->__sql_clear_time();
        $sql = 'DELETE FROM '.table('push_queue').' WHERE '.$sql_limit_time.' AND rund="'.$rund.'"';
        dbc()->Query($sql);
        $this->Messager('操作完成！', '?mod=push&code=queue&rund='.$rund);
    }
    public function Log()
    {
        $list = logic('push')->ListLog();
        include handler('template')->file('@admin/push_log');
    }
    public function Log_clear()
    {
        $sql_limit_time = '`update` '.$this->__sql_clear_time();
        $sql = 'DELETE FROM '.table('push_log').' WHERE '.$sql_limit_time;
        dbc()->Query($sql);
        $this->Messager('操作完成！', '?mod=push&code=log');
    }
    private function __sql_clear_time()
    {
        $time = post('clear_time', 'int');
        $unit = post('clear_unit', 'txt');
        $type = post('clear_type', 'txt');
        $time_unit = array(
            's' => 1,
            'm' => 60,
            'h' => 3600,
            'd' => 86400
        );
        $now = time();
        $pox = $now - $time * $time_unit[$unit];
        if ($type == 'in')
        {
            return '>= '.$pox;
        }
        else
        {
            return '<= '.$pox;
        }
    }
    
}

?>