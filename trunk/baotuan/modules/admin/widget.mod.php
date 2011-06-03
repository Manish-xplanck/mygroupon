<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename widget.mod.php $
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
        $list = ini('widget');
        unset($list['~@blocks']);
        include handler('template')->file('@admin/widget_area_list');
    }
    function Config()
    {
        $area_name = get('flag', 'txt');
        $list = ini('widget.'.$area_name.'.blocks');
        $blocks = ini('widget.~@blocks');
        include handler('template')->file('@admin/widget_area_blocks');
    }
    function Config_block_add()
    {
        $add_area = get('area', 'txt');
        $add_block = get('block', 'txt');
        $list = ini('widget.'.$add_area.'.blocks');
        if (isset($list[$add_block]))
        {
            exit(__('已经加载了此模块！'));
        }
        $blocks = ini('widget.~@blocks');
        if (!isset($blocks[$add_block]))
        {
            exit(__('不存在此模块，无法添加！'));
        }
        ini('widget.'.$add_area.'.blocks.'.$add_block, array('enabled'=>true));
        exit('ok');
    }
    function Config_block_remove()
    {
        $rm_area = get('area', 'txt');
        $rm_block = get('block', 'txt');
        $list = ini('widget.'.$rm_area.'.blocks');
        if (!isset($list[$rm_block]))
        {
            exit(__('不存在此模块，无法删除！'));
        }
        ini('widget.'.$rm_area.'.blocks.'.$rm_block, INI_DELETE);
        exit('ok');
    }
    function Block()
    {
        $list = ini('widget.~@blocks');
        include handler('template')->file('@admin/widget_block_list');
    }
    function Block_add()
    {
        $class = get('class', 'txt');
        include handler('template')->file('@admin/widget_add_'.$class);
    }
    function Block_add_save_diy()
    {
        $flag = post('flag', 'txt');
        $name = post('name', 'txt');
        $title = post('title');
        $content = post('content');
                $content = stripcslashes($content);
        $dir = ROOT_PATH.'templates/widget/';
        $tpl = file_get_contents($dir.'!diy.template.html');
        $write = str_replace(
        	array('{$title}', '{$content}'),
        	array($title, $content),
    	$tpl);
    	ini('widget.~@blocks.'.$flag, array('name' => $name));
    	file_put_contents($dir.$flag.'.html', $write);
    	$this->Messager('模块创建成功！', '?mod=widget&code=block');
    }
    function Block_config()
    {
        $flag = get('flag', 'txt');
        $file = ROOT_PATH.'templates/widget/'.$flag.'.config.html';
        if (!is_file($file))
        {
            $this->Messager('此模块不需要配置！');
        }
        include handler('template')->file('@widget/'.$flag.'.config');
    }
    function Block_config_save()
    {
        $flag = post('flag', 'txt');
        $data = post('data');
        ini('data.'.$flag, $data);
        $this->Messager('配置已经更新！', '?mod=widget&code=block');
    }
    function Block_editor()
    {
        $flag = get('flag', 'txt');
        $file = ROOT_PATH.'templates/widget/'.$flag.'.html';
        $content = file_get_contents($file);
        include handler('template')->file('@admin/widget_editor');
    }
    function Block_editor_save()
    {
        $flag = post('flag', 'txt');
        $file = ROOT_PATH.'templates/widget/'.$flag.'.html';
        $content = post('content');
                $content = stripcslashes($content);
        file_put_contents($file, $content);
                $cfile = handler('template')->file('@widget/'.$flag);
        is_file($cfile) && unlink($cfile);
                $this->Messager('文件更新完成！', '?mod=widget&code=block');
    }
    function Block_delete()
    {
        $flag = get('flag', 'txt');
                if (false !== ini('widget.~@blocks.'.$flag))
        {
            ini('widget.~@blocks.'.$flag, INI_DELETE);
        }
        $dir = ROOT_PATH.'templates/widget/';
                $file = $dir.$flag.'.config.html';
        if (is_file($file))
        {
            unlink($file);
        }
                $file = $dir.$flag.'.html';
        if (is_file($file))
        {
            unlink($file);
        }
        $this->Messager('模块已经删除！', '?mod=widget&code=block');
    }
}


?>