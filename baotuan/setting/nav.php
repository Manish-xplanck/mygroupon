<?php
 /*********************************************
 *[tttuangou] (C)2005 - 2010 Cenwor Inc.
 *
 * tttuangou nav配置
 *
 * @author www.tttuangou.net
 *
 * @time 2011-04-22 17:47
 *********************************************/


$config['nav']=array (
  0 =>
  array (
    'order' => '1',
    'name' => '本期团购',
    'url' => '',
    'title' => '查看本期团购',
    'target' => '_self',
  ),
  1 =>
  array (
    'order' => '2',
    'name' => '往期团购',
    'url' => '?mod=list&code=deals',
    'title' => '查看往期团购',
    'target' => '_self',
  ),
  2 =>
  array (
    'order' => '3',
    'name' => '团购指南',
    'url' => '?mod=list&code=help',
    'title' => '不知道如何团购？来看看吧',
    'target' => '_self',
  ),
  3 =>
  array (
    'order' => '4',
    'name' => '常见问题',
    'url' => '?mod=list&code=faq',
    'title' => '有问题了，先来这里看看吧',
    'target' => '_self',
  ),
//  4 =>
//  array (
//    'order' => '5',
//    'name' => '天天问答',
//    'url' => '?mod=list&code=ask',
//    'title' => '您可以在这里提出您的疑问',
//    'target' => '_self',
//  ),
//  5 =>
//  array (
//   'order' => '6',
//    'name' => '邀请有奖',
//    'url' => '?mod=list&code=invite',
//    'title' => '邀请好友参加团购有返利的哦',
//    'target' => '_self',
//  ),
);
?>