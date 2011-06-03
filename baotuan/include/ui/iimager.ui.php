<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename iimager.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class iImagerUI
{
    public $style = 'default';
    
    public function single($pid, $iid)
    {
        include handler('template')->file('@html/iimager/'.$this->style.'/single');
    }
    
    public function multis($pid, $iids)
    {
        include handler('template')->file('@html/iimager/'.$this->style.'/multis');
    }
}

?>