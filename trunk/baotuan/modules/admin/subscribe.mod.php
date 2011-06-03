<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename subscribe.mod.php $
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
        $class = get('class', 'txt');
        $class = $class ? $class : 'mail';
        $typeDfs = logic('subscribe')->TypeList();
        $type = $typeDfs[$class];
        $list = logic('subscribe')->GetList($class);
        foreach ($list as $i => $one)
        {
            $city = logic('misc')->CityList($one['city']);
            $list[$i]['cityName'] = $city[0]['cityname'];
        }
        include handler('template')->file('@admin/subscribe');
    }
    function Del()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        logic('subscribe')->Del($id);
        $this->Messager('ɾ���ɹ���');
    }
    function Broadcast()
    {
        $class = get('class', 'txt');
        $class = $class ? $class : 'mail';
        $typeDfs = logic('subscribe')->TypeList();
        $type = $typeDfs[$class];
        $list = logic('push')->template()->GetList($class);
        include handler('template')->file('@admin/subscribe_broadcast');
    }
    function Broadcast_add()
    {
        $class = get('class', 'txt');
        $actionName = '�½�';
        $typeDfs = logic('subscribe')->TypeList();
        include handler('template')->file('@admin/push_template_mgr');
    }
    function Broadcast_edit()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        $actionName = '�༭';
        $typeDfs = logic('subscribe')->TypeList();
        $tpl = logic('push')->template()->GetOne($id);
        $class = $tpl['type'];
        include handler('template')->file('@admin/push_template_mgr');
    }
    function Broadcast_save()
    {
        $id = post('id', 'int');
        $data = array();
        $data['type'] = post('type', 'txt');
        $data['name'] = post('name', 'txt');
        $data['intro'] = post('intro', 'txt');
        $data['title'] = post('title', 'txt');
        $data['content'] = post('content');
        logic('push')->template()->Update($id, $data);
        $this->Messager('���³ɹ���', '?mod=subscribe&code=broadcast&class='.$data['type']);
    }
    function Broadcast_del()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('�Ƿ���ţ�');
        }
        logic('push')->template()->Del($id);
        $this->Messager('ɾ���ɹ���');
    }
    function Push()
    {
        $class = get('class', 'txt');
        $tid = get('tid', 'int');
        $tpl = logic('push')->template()->GetOne($tid);
        $city = get('city', 'int');
        logic('subscribe')->Push($class, $city, $tpl);
        exit('ok');
    }
    function Push_preview()
    {
        $class = get('class', 'txt');
        $tid = get('tid', 'int');
        $tpl = logic('push')->template()->GetOne($tid);
        $target = get('target', 'txt');
        logic('push')->addi($class, $target, array('subject'=>$tpl['title'],'content'=>$tpl['content']));
        exit('ok');
    }
    public function Generate()
    {
        $from = get('from', 'txt');
        $idx = get('idx', 'int');
        if ($from == 'product')
        {
            $product = logic('product')->GetOne($idx);
            $source = $product['flag'];
            $cityID = ($product['display'] == PRO_DSP_Global) ? 0 : $product['city'];
        }
        $flag = get('type', 'txt');
        $template = logic('push')->template()->Search('name', $flag.':'.substr(md5($source), 12, 6));
        include handler('template')->file('@admin/subscribe_generate');
    }
    public function Generate_template()
    {
        $flag = get('flag', 'txt');
        $from = get('from', 'txt');
        $idx = get('idx', 'int');
        if ($from == 'product')
        {
            $data = logic('product')->GetOne($idx);
            $source = $data['flag'];
        }
        $content = handler('template')->content('@html/push/'.$flag.'/default', $data);
        $data = array();
        $data['type'] = $flag;
        $data['name'] = $flag.':'.substr(md5($source), 12, 6);
        $data['intro'] = '[ '.date('Y-m-d').' ] '.$source;
        $data['title'] = ini('settings.site_name').'��'.$source;
        $data['content'] = $content;
        $id = logic('push')->template()->Update(0, $data);
        exit((string)$id);
    }
    public function Template_preview()
    {
        $id = get('id', 'int');
        $template = logic('push')->template()->GetOne($id);
        exit($template['content']);
    }
    public function Config()
    {
        $typeDfs = logic('subscribe')->TypeList();
        $type = 'config';
        include handler('template')->file('@admin/subscribe_config');
    }
}

?>