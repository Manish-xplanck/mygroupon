<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename notify.mod.php $
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
    function Main()
    {
        $list = ini('notify.api');
        include handler('template')->file('@admin/notify_list');
    }
    function Config()
    {
        $flag = get('flag', 'txt');
        if (in_array($flag, array('mail', 'sms')))
        {
            header('Location: ?mod=service&code='.$flag);
        }
        $file = DRIVER_PATH.'notify/'.$flag.'.config.php';
        if (!is_file($file))
        {
            $this->Messager('此通知方式没有配置项！');
        }
        else
        {
            include handler('template')->absfile($file);
        }
    }
    function Online()
    {
        $flag = get('flag', 'txt');
        $power = get('power', 'txt');
        $tf = ($power == 'on') ? true : false;
        ini('notify.api.'.$flag.'.enabled', $tf);
        $this->Messager('更新完成！');
    }
    function Save()
    {
        $flag = post('flag', 'txt');
        $config = post('cfg');
        $config['enabled'] = ($config['enabled'] == 'on') ? true : false;
        ini('notify.api.'.$flag, $config);
        $this->Messager('修改完成！');
    }
    function Event()
    {
        $api = ini('notify.api');
        $apiTitles = '';
        foreach ($api as $i => $one)
        {
            if (!$one['enabled'])
            {
                unset($api[$i]);
            }
            else
            {
                $apiTitles .= '<td width="10%">'.$one['name'].'</td>';
            }
        }
        $colspan = count($api)+4;
        $list = ini('notify.event');
        foreach ($list as $name => $cfg)
        {
            $string = '';
            foreach ($api as $flag => $one)
            {
                $status = 'enable';
                if (!$cfg['hook'][$flag]['enabled'])
                {
                    $status = 'disable';
                }
                $string .= '<td><img class="'.$status.'" title=".event.'.$name.'.hook.'.$flag.'.enabled" /><img class="editor" title="'.$name.'.msg.'.$flag.'" /></td>';
            }
            $list[$name]['_apis'] = $string;
        }
        $listener = ini('notify.listener') ? 'enable' : 'disable';
        $extend = '';
        
        include handler('template')->file('@admin/notify_events'.$extend);
    }
    function Event_switch()
    {
        $hook = get('hook', 'txt');
        $power = get('power', 'txt');
        $tf = ($power == 'enable') ? true : false;
        ini('notify'.$hook, $tf);
        exit($power);
    }
    function Event_rename()
    {
        $hook = get('hook', 'txt');
        $name = get('name', 'txt');
        ini('notify.event.'.$hook.'.name', $name);
        exit('ok');
    }
    function Event_msg()
    {
        $hook = get('hook', 'txt');
        list($event, $flag) = explode('.msg.', $hook);
        $struct = ini('notify.event.'.$event.'.struct');
        $msg = ini('notify.event.'.$event.'.msg.'.$flag);
        $ops = array(
            'status' => 'ok',
            'name' => ini('notify.event.'.$event.'.name'),
            'msg' => $msg,
        	'al2user' => ini('notify.event.'.$event.'.cfg.'.$flag.'.al2user') ? true : false,
            'cc2admin' => ini('notify.event.'.$event.'.cfg.'.$flag.'.cc2admin') ? true : false,
            'tags' => explode(',', $struct)
        );
        echo jsonEncode($ops);
        exit;
    }
    function Event_save()
    {
        $hook = get('hook', 'txt');
        $msg = post('msg', 'txt');
        $al2user = post('al2user', 'txt');
        $cc2admin = post('cc2admin', 'txt');
        ini('notify.event.'.$hook, $msg);
                $hookc = str_replace('.msg.', '.cfg.', $hook);
        $tf = ($cc2admin == 'true') ? true : false;
        ini('notify.event.'.$hookc.'.cc2admin', $tf);
        $tf = ($al2user == 'true') ? true : false;
        ini('notify.event.'.$hookc.'.al2user', $tf);
                logic('notify')->Clears();
        exit('ok');
    }
    function Event_test()
    {
        logic('notify')->Call(user()->get('id'), 'admin.mod.notify.Event.test', date('Y-m-d H:i:s'));
        exit('ok');
    }
    function Event_delete()
    {
        $event = get('hook', 'txt');
        ini('notify.event.'.$event, INI_DELETE);
        exit('ok');
    }
    function Tag_clear()
    {
        $hook = get('hook', 'txt');
        list($event) = explode('.', $hook);
        ini('notify.event.'.$event.'.struct', '');
        exit('ok');
    }
    function AdminID_save()
    {
        $adminid = post('adminid', 'int');
        if (!$adminid)
        {
            $this->Messager('请输入有效的管理员ID！');
        }
        else
        {
            ini('notify.adminid', $adminid);
            $this->Messager('保存完成！');
        }
    }
}

?>