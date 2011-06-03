<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename admin.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




require_once 'include/init.php';

define('DEBUG',false);


$init = new initialize();
$init->envInit();
$init->allowMod('index,cache,coupon,db,dbf,delivery,export,express,ini,link,login,master,member,notify,order,payment,product,push,report,rewrite,robot,role,role_action,role_module,service,sessions,setting,show,subscribe,task,tttuangou,ucenter,upgrade,voa,widget,ui');
$init->load('admin');
unset($init);

?>