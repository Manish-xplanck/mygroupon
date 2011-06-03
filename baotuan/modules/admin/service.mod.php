<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename service.mod.php $
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
        $list = array(
            'mail' => '�ʼ�������',
            'sms' => '���ŷ�����'
        );
        include handler('template')->file('@admin/service');
    }
    
    function Mail()
    {
        $list = logic('service')->mail()->GetList();
        $balance = ini('service.mail.balance');
        include handler('template')->file('@admin/service_mail_list');
    }
    function Mail_balance()
    {
        $power = get('power', 'txt');
        $tf = ($power == 'on') ? true : false;
        ini('service.mail.balance', $tf);
        $this->Messager('������ɣ�');
    }
    function Mail_add()
    {
        $actionName = '���';
        include handler('template')->file('@admin/service_mail_mgr');
    }
    function Mail_edit()
    {
        $actionName = '�༭';
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        $c = logic('service')->mail()->GetOne($id);
        include handler('template')->file('@admin/service_mail_mgr');
    }
    function Mail_save()
    {
        $id = post('id', 'int');
        $c = array();
        $c['flag'] = post('flag', 'txt');
        $c['name'] = post('name', 'txt');
        $c['weight'] = post('weight', 'int');
        $c['enabled'] = post('enabled', 'txt');
        $c['config'] = serialize(post('cfg'));
        logic('service')->mail()->Update($id, $c);
        $this->Messager('���³ɹ���', '?mod=service&code=mail');
    }
    function Mail_switch()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        $power = get('power', 'txt');
        $data['enabled'] = ($power == 'on') ? 'true' : 'false';
        logic('service')->mail()->Update($id, $data);
        $this->Messager('���³ɹ���');
    }
    function Mail_del()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        logic('service')->mail()->Del($id);
        $this->Messager('ɾ���ɹ���');
    }
    function Mail_test()
    {
        $mail = get('mail', 'txt');
        $info = logic('service')->mail()->Test($mail);
        include handler('template')->file('@admin/service_mail_test');
    }
    
    function SMS()
    {
        $list = logic('service')->sms()->GetList();
        $drivers = logic('service')->sms()->DriverList();
        include handler('template')->file('@admin/service_sms_list');
    }
    function SMS_add()
    {
        $actionName = '���';
        $drivers = logic('service')->sms()->DriverList();
        include handler('template')->file('@admin/service_sms_mgr');
    }
    function SMS_edit()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        $c = logic('service')->sms()->GetOne($id);
        $actionName = '�༭';
        $drivers = logic('service')->sms()->DriverList();
        include handler('template')->file('@admin/service_sms_mgr');
    }
    function SMS_save()
    {
        $id = post('id', 'int');
        $c = array();
                                $c['enabled'] = post('enabled', 'txt');
        $c['config'] = serialize(post('cfg'));
        logic('service')->sms()->Update($id, $c);
        $this->Messager('���³ɹ���', '?mod=service&code=sms');
    }
    function SMS_switch()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        $power = get('power', 'txt');
        $data['enabled'] = ($power == 'on') ? 'true' : 'false';
        logic('service')->sms()->Update($id, $data);
        $this->Messager('���³ɹ���');
    }
    function SMS_del()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        logic('service')->sms()->Del($id);
        $this->Messager('ɾ���ɹ���');
    }
    function SMS_test()
    {
        $phone = get('phone', 'txt');
        $content = get('content');
        $info = logic('service')->sms()->Test($phone, $content);
        include handler('template')->file('@admin/service_sms_test');
    }
    function SMS_status()
    {
        $id = get('id', 'int');
        echo logic('service')->sms()->Status($id);
        exit;
    }
}

?>