<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename upload.mod.php $
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
                $rid = user()->get('role_id');
        if ($rid != 2)
        {
            $msg = 'Access Deined';
			if ($this->Code == 'image')
			{
				$ops = array(
					'status' => 'fails',
					'msg' => $msg
				);
			}
			elseif ($this->Code == 'editor')
			{
				$ops = array(
					'error' => 1,
					'message' => $msg
				);
			}
			else
			{
				exit($msg);
			}
			exit(jsonEncode($ops));
        }
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    function Main()
    {
        exit('IO.Uploads.Index');
    }
    function Image()
    {
                $result = logic('upload')->Save('Filedata');
        if (isset($result['error']) && $result['error'])
        {
            $ops = array(
                'status' => 'fails',
                'msg' => $result['msg']
            );
        }
        else
        {
            $ops = array(
                'status' => 'ok',
                'file' => $result
            );
        }
        exit(jsonEncode($ops));
    }
    function Editor()
    {
        $field = get('field', 'txt');
        $result = logic('upload')->Save($field);
        if (isset($result['error']) && $result['error'])
        {
            $ops = array(
                'error' => 1,
                'message' => $result['msg']
            );
        }
        else
        {
            $ops = array(
                'error' => 0,
                'url' => $result['url']
            );
        }
        exit(jsonEncode($ops));
    }
}

?>