<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename task.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


class ModuleObject extends MasterObject
{
	var $Config = array();
	function ModuleObject(& $config)
	{
		$this->MasterObject($config);
		Load::moduleCode($this);$this->Execute();
	}
	function Execute()
	{
		if ('run' == $this->Code)
		{
			$this->Run();
		}
	}
	function Run()
	{
		require_once(LOGIC_PATH.'task.logic.php');
		$TaskLogic=new TaskLogic();
		$TaskLogic->run();
		echo 'ok';
		exit;
	}
}

?>