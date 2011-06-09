<?php

require_once 'include/init.php';

define('DEBUG',false);


$init = new initialize();
$init->envInit();
$init->allowMod('index,cache,coupon,db,dbf,delivery,export,express,ini,link,login,master,member,notify,order,payment,product,push,report,rewrite,robot,role,role_action,role_module,service,sessions,setting,show,subscribe,task,tttuangou,ucenter,upgrade,voa,widget,ui');
$init->load('admin');
unset($init);

?>