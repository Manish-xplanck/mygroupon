<?php

require_once 'include/init.php';

define('DEBUG',false);


$init = new initialize();
$init->envInit();
$init->allowMod('index,list,buy,login,get_password,magseller,account,me,openapi,misc,callback,pingfore,subscribe,upload,search,wap,apiz,address');
$init->load('index');
unset($init);

?>