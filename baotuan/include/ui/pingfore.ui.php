<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename pingfore.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class PingforeUI
{
    public function html()
    {
        if (locked('logic.push.running'))
        {
            $isCheck = rand(1, 13);
            if ($isCheck == 13)
            {
                                $this->doCheckLockFile('logic.push.running');
            }
            return '';
        }
        return ui('loader')->js('@pingfore');
    }
    private function doCheckLockFile($name)
    {
        $file = driver('lock')->file($name);
        if (!is_file($file)) return;
        $mtime = filemtime($file);
        if (time() - $mtime > 60)
        {
                        unlink($file);
        }
    }
}

?>