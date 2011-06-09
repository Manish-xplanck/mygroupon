<?php
/**
 * �ļ�����link.mod.php
 * �汾�ţ�1.0
 * ����޸�ʱ�䣺Tue Oct 30 13:16:22 CST 2007
 * ���ߣ�����<foxis@qq.com>
 * ������������������ģ�����
 */

class ModuleObject extends MasterObject
{

	var $Code = array();
	
	var $ID = 0;
	var $_config=array();
	var $configPath="";

	
	function ModuleObject($config)
	{
		$this->MasterObject($config);
		$this->ID = $this->Get['id']?(int)$this->Get['id']:(int)$this->Post['id'];
		$this->configPath=CONFIG_PATH;
		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{
		switch($this->Code)
		{
			case 'modify':
				$this->Main();
				break;
			case 'domodify':
				$this->DoModify();
				break;
			default:
				$this->Main();
				break;
		}
	}
	
	
	function Main()
	{
		$this->Modify();
	}
	
	function Modify()
	{
		$link_list=array();
		$current_domain=preg_replace("~^www\.~i","",$_SERVER['HTTP_HOST']);
		if(@include($this->configPath.'link.php'))
		{
			$link_list=$config['link'];
		}
		include(handler('template')->file('@admin/link'));
	}
	
	function DoModify()
	{
		$link=$this->Post['link'];
		if($link['new']['name']!="" && $link['new']['url']!="" )
		{
			$new_link=$link['new'];
			
			$link[]=$new_link;
		}
		unset($link['new']);
		if($this->Post['delete'])
		{
			foreach ($this->Post['delete'] as $link_id)
			{
				unset($link[$link_id]);
			}
		}
		
				$n=100;
		$i=0;
		$link_list=array();
		foreach (@$link as $l)
		{			
			if(!empty($l['logo']))
			{
				$key = $i++;
			}
			else 
			{
				$key = $n++;
			}
			
			$l['order'] = (int) ($l['order'] ? $l['order'] : $key);
			
			$link_list[$key]=$l;
		}
				if($link_list) {
			foreach ($link_list as $k=>$n)
			{
				$order[$k]=$n['order'];
			}
			@array_multisort($order,SORT_ASC,$link_list);
		}
		
		
		$this->_saveConfig($link_list);
		$this->Messager("�޸ĳɹ�");
	}
	
	function _saveConfig($link)
	{
		$fp=fopen($this->configPath.'link.php',"wb");
		if(!$fp)
		$this->Messager("�����ļ���{$this->configPath}link.php���޷�д�룬�����ļ��Ƿ��п�дȨ��");
		$link=HttpHandler::CheckVars($link,false);
		fwrite($fp,"<?php\r\n\$config['link']=".var_export($link,true).";\r\n?>");
		fclose($fp);
		return true;
	}
}
?>