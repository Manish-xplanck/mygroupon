<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename push.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class PushLogic
{
    
    public function add($type, $target, $data, $pr = 1)
    {
        $queue = array(
            'type' => $type,
            'target' => $target,
            'data' => serialize($data),
            'update' => time(),
            'pr' => $pr
        );
        dbc()->SetTable(table('push_queue'));
        dbc()->Insert($queue);
    }
    
    public function addi($type, $target, $data)
    {
        $runCode = 'run_'.$type;
        $this->$runCode($target, $data);
    }
    public function run($max)
    {
        $lck = 'logic.push.running';
        if (locked($lck))
        {
            return;
        }
        locked($lck, true);
        ignore_user_abort(true);
        $sql = 'SELECT * FROM '.table('push_queue').' WHERE rund="false" ORDER BY pr DESC LIMIT '.$max;
        $queues = dbc()->Query($sql)->GetAll();
        if (is_array($queues) && count($queues) > 0)
        {
            foreach ($queues as $i => $queue)
            {
                $runCode = 'run_'.$queue['type'];
                $result = $this->$runCode($queue['target'], unserialize($queue['data']));
                $this->__update($queue['id'], $result);
            }
            $this->__clear();
        }
        locked($lck, false);
    }
    
    public function log($type, $driver, $target, $data, $result)
    {
        $data = array(
            'type' => $type,
            'driver' => $driver,
            'target' => $target,
            'data' => serialize($data),
            'result' => $result,
            'update' => time()
        );
        dbc()->SetTable(table('push_log'));
        dbc()->Insert($data);
    }
    
    private function __update($id, $result)
    {
        $data = array(
            'rund' => 'true',
            'result' => $result,
            'update' => time()
        );
        dbc()->SetTable(table('push_queue'));
        dbc()->Update($data, 'id='.$id);
    }
    
    private function __clear()
    {
        $flagKey = 'push.logic.clear.flag';
        $timeInv = 3600;
        $flag = fcache($flagKey, $timeInv);
        if (!$flag)
        {
            $timeBefore = time() - $timeInv;
            dbc()->SetTable(table('push_queue'));
            dbc()->Delete('', 'rund="true" AND update<'.$timeBefore);
            fcache($flagKey, 'mark');
        }
    }
    
    private function run_mail($target, $data)
    {
        $result = logic('service')->mail()->Send($target, $data['subject'], $data['content']);
        if ($result == 1)
        {
            $result = __('邮件发送成功！');
        }
        return $result;
    }
    
    private function run_sms($target, $data)
    {
        return logic('service')->sms()->Send($target, $data['content']);
    }
    
    public function template()
    {
        $SID = 'logic.push.template';
        $obj = moSpace($SID);
        if ( ! $obj )
        {
            $obj = moSpace($SID, (new PushLogic_Template()));
        }
        return $obj;
    }
    
    public function ListQueue($rund = 'false', $type = null)
    {
        $sql_limit_type = '1';
        if ($type !== null)
        {
            $sql_limit_type = 'type="'.$type.'"';
        }
        $sql = 'SELECT * FROM '.table('push_queue').' WHERE '.$sql_limit_type.' AND rund="'.$rund.'" ORDER BY id DESC';
        $sql = page_moyo($sql);
        return dbc()->Query($sql)->GetAll();
    }
    
    public function ListLog($type = null)
    {
        $sql_limit_type = '1';
        if ($type !== null)
        {
            $sql_limit_type = 'type="'.$type.'"';
        }
        $sql = 'SELECT * FROM '.table('push_log').' WHERE '.$sql_limit_type.' ORDER BY id DESC';
        $sql = page_moyo($sql);
        return dbc()->Query($sql)->GetAll();
    }
}


class PushLogic_Template
{
    public function GetList($type = null)
    {
        $sql_limit_type = '1';
        if (!is_null($type))
        {
            $sql_limit_type = 'type="'.$type.'"';
        }
        $sql = 'SELECT * FROM '.table('push_template').' WHERE '.$sql_limit_type;
        $sql = page_moyo($sql);
        return dbc()->Query($sql)->GetAll();
    }
    public function GetOne($id)
    {
        $sql = 'SELECT * FROM '.table('push_template').' WHERE id='.$id;
        return dbc()->Query($sql)->GetRow();
    }
	
    public function Search($field, $value, $getOne = true)
    {
        $dbc = dbc(DBCMax)->select('push_template')->where(array($field=>$value));
        if ($getOne)
        {
            $dbc->limit(1);
        }
        return $dbc->done();
    }
    public function Update($id, $data)
    {
        dbc()->SetTable(table('push_template'));
        $data['update'] = time();
        if ($id == 0)
        {
            $result = dbc()->Insert($data);
        }
        else
        {
            $result = dbc()->Update($data, 'id='.$id);
        }
        return $result;
    }
    public function Del($id)
    {
        dbc()->SetTable(table('push_template'));
        dbc()->Delete('', 'id='.$id);
    }
}

?>