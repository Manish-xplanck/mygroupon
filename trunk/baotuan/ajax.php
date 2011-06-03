<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename ajax.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




require_once 'include/init.php';

define('DEBUG',false);


$init = new initialize();
$init->envInit();
$init->allowMod('check,getseller,member');
$init->load('ajax');
unset($init);

?>