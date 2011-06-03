<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename igos.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class iGOSUI
{
    
    public function load($product)
    {
        $style = ini('ui.igos.style');
        $style || $style = 'default';
        include handler('template')->file('@html/igos/'.$style.'/index');
    }
}

?>