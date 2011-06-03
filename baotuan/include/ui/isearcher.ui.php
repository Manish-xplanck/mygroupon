<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename isearcher.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class iSearcherUI
{
    
    public function load($idx)
    {
        $map = ini('isearcher.map');
        $fidString = ini('isearcher.idx.'.$idx);
        $fids = explode(',', $fidString);
        include handler('template')->file('@html/isearcher/index');
    }
}

?>