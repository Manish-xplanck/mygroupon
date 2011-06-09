<?php

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
        $this->Mail();
    }
    function Mail()
    {
        $target = get('target', 'txt');
        $this->Title = __('�ʼ�����');
        include handler('template')->file('subscribe_mail');
    }
    function SMS()
    {
        $target = get('target', 'txt');
        $this->Title = __('���Ŷ���');
        include handler('template')->file('subscribe_sms');
    }
    //add by caojianlong 2011-06-08
	function Vote() {
		$target = get('target', 'txt');
        $this->Title = __('�û�����');
        include handler('template')->file('subscribe_vote');
    }
    //end add by caojianlong 2011-06-08

    function Save()
    {
        $type = post('type', 'txt');
        $target = post('target', 'txt');
        if ($type == 'mail')
        {
            if (!preg_match('/[a-z0-9\._]+@[a-z0-9\.-]+/', $target))
            {
                $this->Messager(__('��Ч��Email��ַ��'));
            }
        }
        elseif ($type == 'sms')
        {
            if (!preg_match('/[0-9]{11}/', $target))
            {
                $this->Messager(__('��Ч���ֻ����룡'));
            }
        }
        $city = post('city', 'int');
        $result = logic('subscribe')->Search('target', $target);
        if ($result)
        {
            if ($result['validated'] == 'true')
            {
                $this->Messager(__('���Ѿ����Ĺ��ˣ��벻Ҫ�ظ��ύŶ��'));
            }
            $sid = $result['id'];
        }
        else
        {
            $sid = logic('subscribe')->Add($city, $type, $target);
        }
        if (ini('subscribe.validate.do.'.$type))
        {
            $this->Validate_resend($sid);
            header('Location: '.rewrite('?mod=subscribe&code=validate&sid='.$sid));
        }
        else
        {
            $this->Validate_verify('do', $sid);
        }
    }
    public function Undo()
    {
        $target = get('target', 'txt');
        $this->Title = __('ȡ������');
        include handler('template')->file('subscribe_undo');
    }
    public function Undo_confirm()
    {
        $target = post('target', 'txt');
        $sid = logic('subscribe')->Subsd($target);
        if (!$sid)
        {
            $this->Messager(__('����û�н��ж��ģ��޷�ȡ����'));
        }
                $sub = logic('subscribe')->GetOne($sid);
        $type = $sub['type'];
        if (ini('subscribe.validate.undo.'.$type))
        {
            $this->Validate_resend($sid, 'undo');
            header('Location: '.rewrite('?mod=subscribe&code=validate&sid='.$sid.'&action=undo'));
        }
        else
        {
            $this->Validate_verify('undo', $sid);
        }
    }
    public function Validate()
    {
        $sid = get('sid', 'int');
        $sub = logic('subscribe')->GetOne($sid);
        $this->Title = __('������֤');
        $action = get('action', 'txt');
        if ($action && $action == 'undo')
        {
            $this->Title = __('ȡ������');
        }
        else
        {
            $action = 'dosub';
        }
        include handler('template')->file('subscribe_'.$sub['type'].'_validate');
    }
    public function Validate_resend($csid = null, $action = 'dosub')
    {
        $sid = $csid ? $csid : get('sid', 'int');
        if (get('action')) $action = get('action', 'txt');
                $lastSend = meta('sub_last_send_of_'.$sid);
        if ($lastSend)
        {
            if (time() - $lastSend < 120)
            {
                if (is_null($csid))
                {
                    $this->Messager(__('ϵͳ�Ѿ����͹���֤��Ϣ���������·��ͣ��������������ԣ�'));
                }
                return;
            }
        }
        $sub = logic('subscribe')->GetOne($sid);
        $vcode = $this->__vcode_generate();
        $send = handler('template')->content('@html/subscribe/'.$action.'.validate.'.$sub['type'], array('vcode'=>$vcode));
        if ($sub['type'] == 'sms')
        {
            logic('push')->addi('sms', $sub['target'], array('content'=>$send));
        }
        else
        {
            $subject = __('�������붩�ģ�����֤��');
            if ($action == 'undo')
            {
                $subject = __('��������ȡ�����ģ���ȷ�ϣ�');
            }
            logic('push')->add($sub['type'], $sub['target'], array('subject'=>$subject,'content'=>$send));
        }
        meta('sub_vcode_'.$vcode, $sid, 'd:1');
        meta('sub_last_send_of_'.$sid, time(), 'm:2');
        if (is_null($csid))
        {
            $this->Messager(__('���ͳɹ���'), '?mod=subscribe&code=validate&sid='.$sid.'&action='.$action);
        }
        return;
    }
    public function Validate_verify($action = null, $sid = null)
    {
        if (is_null($action) && is_null($sid))
        {
            $vcode = get('vcode', 'txt');
            $vcode = $vcode ? $vcode : post('vcode', 'txt');
            $vcode = trim($vcode);
            $action = get('action', 'txt');
            $action = $action ? $action : post('action', 'txt');
            $sid = meta('sub_vcode_'.$vcode);
            if (!$sid)
            {
                $this->Messager(__('��Ч����֤�룡'));
            }
            meta('sub_vcode_'.$vcode, null);

                    }
                if ($action == 'undo')
        {
            logic('subscribe')->Validate($sid, 'false');
            $this->Messager(__('�Ѿ�ȡ�����ģ�'), '?mod=me&code=setting');
        }
        else
        {
            logic('subscribe')->Validate($sid);
            $this->Messager(__('�Ѿ��ɹ����ģ�'), '?mod=me&code=setting');
        }
    }
    private function __vcode_generate()
    {
        $string = md5(time());
        return substr($string, 12, 6);
    }
}

?>