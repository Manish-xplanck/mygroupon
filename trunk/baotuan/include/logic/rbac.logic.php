<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename rbac.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class RBACLogic
{
    
    public function Access($file, $module, $action)
    {
        $idx = 'rbac.list.'.$file.'.'.$module.'.'.$action;
                $action = ini($idx);
        if (!$action)
        {
            ini($idx, array(
                'name' => '',
                'enabled' => false,
            ));
            return;
        }
        if (!$action['enabled'])
        {
            return;
        }
                if (user()->get('id') != 1)
        {
			$text = '��Ǹ����ʾ�ʺŲ����Է��ʴ˹��ܣ�';
			include handler('template')->file('@inizd/alert');
            exit();
        }
    }
}

?>