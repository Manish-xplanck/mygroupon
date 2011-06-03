<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename widget.ui.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class WidgetUI
{
    function load( $area = '' )
    {
        if ($area == '')
        {
            $area = str_replace('.', '_', mocod());
        }
        $pox = 'widget.'.$area.'.blocks';
        $list = ini($pox);
        if ( false === $list )
        {
            ini($pox, array());
            return;
        }
        foreach ( $list as $name => $one )
        {
            if (isset($one['enabled']) && $one['enabled'])
            {
                handler('template')->load('@widget/' . $name);
            }
        }
    }
}

?>