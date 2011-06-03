<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename loader.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class LoaderUI
{
	
	private $__loaded_list = array();
    function js($name, $once = false)
    {
        list($name, $loc) = $this->__GetLoc($name, 'js', 'static/js');
        $file = ROOT_PATH.$loc.$name.'.js';
        $url = ini('settings.site_url').$loc.$name.'.js';
        if (!is_file($file)) return '';
        if ($this->loaded('js_'.$name)) return '';
        if ($once)
        {
        	$this->loaded('js_'.$name, true);
        }
        return '<script type="text/javascript" src="'.$url.'"></script>';
    }
    function css($name, $once = false)
    {
        list($name, $loc) = $this->__GetLoc($name, 'styles', 'static/css');
        $file = ROOT_PATH.$loc.$name.'.css';
        $url = ini('settings.site_url').$loc.$name.'.css';
        if (!is_file($file)) return '';
        if ($this->loaded('css_'.$name)) return '';
        if ($once)
        {
        	$this->loaded('css_'.$name, true);
        }
        return '<link href="'.$url.'" rel="stylesheet" type="text/css" />';
    }
    function addon($flag)
    {
        $map = array(
            'picker.date' => '@DatePicker/WdatePicker',
            'editor.kind' => '@KindEditor/kindeditor',
            'uploader.swf' => '@SwfUploader/swfupload',
        );
        $name = $map[$flag];
        list($name, $loc) = $this->__GetLoc($name, 'js', 'static/addon');
        $file = ROOT_PATH.$loc.$name.'.js';
        $url = ini('settings.site_url').$loc.$name.'.js';
        if (!is_file($file)) return '';
        return '<script type="text/javascript" src="'.$url.'"></script>';
    }
    private function __GetLoc($name, $dirCom, $dirSAt)
    {
        $template_path = ini('settings.template_root_path').ini('settings.template_path').'/'.$dirCom.'/';
        if (substr($name, 0, 1)=='@')
        {
            $name = substr($name, 1);
            $template_path = './'.$dirSAt.'/';
        }
        elseif (substr($name, 0, 1)=='#')
        {
            $name = substr($name, 1);
            $template_path = ini('settings.template_root_path');
        }
        if (substr($template_path, 0, 1)=='.')
        {
            $template_path = substr($template_path, 1);
        }
        return array($name, $template_path);
    }
    private function loaded($name, $flag = false)
    {
    	if (!$flag)
    	{
    		return isset($this->__loaded_list[$name]) ? true : false;
    	}
    	return $this->__loaded_list[$name] = true;
    }
}

?>