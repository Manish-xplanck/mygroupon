<?php

require_once 'include/init.php';

define('DEBUG',false);


$init = new initialize();
$init->envInit();
$init->allowMod('check,getseller,member');
$init->load('ajax');
unset($init);

?>