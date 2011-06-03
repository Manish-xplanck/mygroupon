<?php
/**
 * Config File of [service]
 *
 * @time 2011-05-19 10:42:18
 */
$config["service"] =  array (
  'mail' => 
  array (
    'balance' => true,
  ),
  'sms' => 
  array (
    'driver' => 
    array (
      'ls' => 
      array (
        'name' => '电信通道',
        'intro' => '075开头，网关短信直发，价格便宜<br/><a href="http://cenwor.com/shop/brand.php?id=7" target="_blank"><font color="red">1000条短信仅需80元，点此在线购买</font></a>',
      ),
    ),
    'autoERSend' => true,
  ),
);
?>