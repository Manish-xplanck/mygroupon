<?php

require_once 'include/init.php';

define('DEBUG',false);

$init = new initialize();
$init->envInit();
$init->allowMod('index,install');
$init->load('inizd');
unset($init);

?>