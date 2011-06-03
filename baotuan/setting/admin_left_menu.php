<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename admin_left_menu.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 
 $menu_list = array (
  1 => 
  array (
    'title' => '常用操作',
    'link' => '',
    'sub_menu_list' => 
    array (
    ),
  ),
  2 => 
  array (
    'title' => '全局设置',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '核心设置',
        'link' => 'admin.php?mod=setting&code=modify_normal',
        'shortcut' => false,
      ),
      2 => 
      array (
        'title' => '伪静态',
        'link' => 'admin.php?mod=setting&code=modify_rewrite',
        'shortcut' => false,
      ),
      3 => 
      array (
        'title' => '内容过滤',
        'link' => 'admin.php?mod=setting&code=modify_filter',
        'shortcut' => false,
      ),
      4 => 
      array (
        'title' => '友情链接',
        'link' => 'admin.php?mod=link',
        'shortcut' => false,
      ),
      5 => 
      array (
        'title' => 'IP访问控制',
        'link' => 'admin.php?mod=setting&code=modify_access',
        'shortcut' => false,
      ),
      6 => 
      array (
        'title' => '首页导航设置',
        'link' => 'admin.php?mod=tttuangou&code=indexnav',
        'shortcut' => false,
      ),
	  7 => 
      array (
        'title' => '客服信息设置',
        'link' => 'admin.php?mod=widget&code=block&op=config&flag=cservice',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '团购设置',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '常用设置',
        'link' => 'admin.php?mod=tttuangou&code=varshow',
        'shortcut' => true,
      ),
	  12 => 
      array (
        'title' => '侧边栏管理',
        'link' => 'admin.php?mod=widget',
        'shortcut' => false,
      ),
      1002 => 
      array (
        'title' => '风格设置',
        'link' => 'hr',
        'shortcut' => false,
      ),
      21 => 
      array (
        'title' => '默认模板',
        'link' => 'admin.php?mod=tttuangou&code=defaultstyle',
        'shortcut' => true,
      ),
      22 => 
      array (
        'title' => '站点Logo',
        'link' => 'admin.php?mod=tttuangou&code=sitelogo',
        'shortcut' => false,
      ),
      23 => 
      array (
        'title' => '分享设置',
        'link' => 'admin.php?mod=tttuangou&code=shareconfig',
        'shortcut' => false,
      ),
	  24 => 
      array (
        'title' => '多团设置',
        'link' => 'admin.php?mod=ui&code=igos&op=config',
        'shortcut' => false,
      ),
    ),
  ),
  3 => 
  array (
    'title' => '团购管理',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '产品管理',
        'link' => 'admin.php?mod=product',
        'shortcut' => true,
      ),
      2 => 
      array (
        'title' => '订单管理',
        'link' => 'admin.php?mod=order',
        'shortcut' => true,
      ),
      3 => 
      array (
        'title' => '团购券管理',
        'link' => 'admin.php?mod=coupon',
        'shortcut' => false,
      ),
      4 => 
      array (
        'title' => '发货管理',
        'link' => 'admin.php?mod=delivery',
        'shortcut' => true,
      ),
      5 => 
      array (
        'title' => '返利管理',
        'link' => 'admin.php?mod=tttuangou&code=mainfinder',
        'shortcut' => false,
      ),
      6 => 
      array (
        'title' => '城市管理',
        'link' => 'admin.php?mod=tttuangou&code=city',
        'shortcut' => false,
      ),
      7 => 
      array (
        'title' => '商家管理',
        'link' => 'admin.php?mod=tttuangou&code=mainseller',
        'shortcut' => true,
      ),
      8 => 
      array (
        'title' => '配送管理',
        'link' => 'admin.php?mod=express',
        'shortcut' => false,
      ),
	 9 => 
      array (
        'title' => '支付设置',
        'link' => 'admin.php?mod=payment',
        'shortcut' => true,
      ),
      10 => 
      array (
        'title' => '电子对账',
        'link' => 'admin.php?mod=voa',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '数据清理',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '数据初始化',
        'link' => 'admin.php?mod=tttuangou&code=clear',
        'shortcut' => false,
      ),
    ),
  ),
  4 => 
  array (
    'title' => '互动营销',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '短信平台设置',
        'link' => 'admin.php?mod=service&code=sms',
        'shortcut' => true,
      ),
      2 => 
      array (
        'title' => '群发服务管理',
        'link' => 'admin.php?mod=service',
        'shortcut' => false,
      ),
      3 => 
      array (
        'title' => '订阅管理',
        'link' => 'admin.php?mod=subscribe',
        'shortcut' => false,
      ),
	  4 => 
      array (
        'title' => '订阅群发',
        'link' => 'admin.php?mod=subscribe&code=broadcast&class=mail',
        'shortcut' => false,
      ),
      5 => 
      array (
        'title' => '通知方式',
        'link' => 'admin.php?mod=notify',
        'shortcut' => false,
      ),
      6 =>
      array (
        'title' => '通知事件管理',
        'link' => 'admin.php?mod=notify&code=event',
        'shortcut' => false,
      ),
      7 =>
      array (
        'title' => '问答管理',
        'link' => 'admin.php?mod=tttuangou&code=mainquestion',
        'shortcut' => false,
      ),
      8 =>
      array (
        'title' => '反馈信息',
        'link' => 'admin.php?mod=tttuangou&code=usermsg',
        'shortcut' => false,
      ),
	  1001 => 
      array (
        'title' => '推送管理',
        'link' => 'hr',
        'shortcut' => false,
      ),
	  11 => 
      array (
        'title' => '推送队列',
        'link' => 'admin.php?mod=push&code=queue',
        'shortcut' => false,
      ),
	  2001 => 
      array (
        'title' => '数据调用',
        'link' => 'hr',
        'shortcut' => false,
      ),
	  21 => 
      array (
        'title' => '外部调用',
        'link' => 'admin.php?mod=tttuangou&code=dataapi',
        'shortcut' => false,
      ),
    ),
  ),
  5 => 
  array (
    'title' => '系统工具',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '更新缓存',
        'link' => 'admin.php?mod=cache',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '数据库',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '数据备份',
        'link' => 'admin.php?mod=db&code=export',
        'shortcut' => false,
      ),
      12 => 
      array (
        'title' => '数据恢复',
        'link' => 'admin.php?mod=db&code=import',
        'shortcut' => false,
      ),
      13 => 
      array (
        'title' => '数据表优化',
        'link' => 'admin.php?mod=db&code=optimize',
        'shortcut' => false,
      ),
      1002 => 
      array (
        'title' => '站点信息',
        'link' => 'hr',
        'shortcut' => false,
      ),
      21 => 
      array (
        'title' => '蜘蛛爬行统计',
        'link' => 'admin.php?mod=robot',
        'shortcut' => false,
      ),
      22 => 
      array (
        'title' => '关键词排名',
        'link' => 'http://keyword.biniu.com',
        'shortcut' => false,
      ),
      23 => 
      array (
        'title' => 'alexa排名',
        'link' => 'http://alexa.biniu.com',
        'shortcut' => true,
      ),
      24 => 
      array (
        'title' => '友情链接检测',
        'link' => 'http://checklink.biniu.com',
        'shortcut' => true,
      ),
      25 => 
      array (
        'title' => '收录查询',
        'link' => 'http://shoulu.biniu.com',
        'shortcut' => true,
      ),
      26 => 
      array (
        'title' => '同IP网站',
        'link' => 'http://sameip.biniu.com',
        'shortcut' => false,
      ),
      27 => 
      array (
        'title' => '反向链接分析',
        'link' => 'http://backlink.biniu.com',
        'shortcut' => true,
      ),
    ),
  ),
  6 => 
  array (
    'title' => '用户管理',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => 'Ucenter整合',
        'link' => 'admin.php?mod=ucenter',
        'shortcut' => false,
      ),
      2 => 
      array (
        'title' => '+添加新用户',
        'link' => 'admin.php?mod=member&code=add',
        'shortcut' => false,
      ),
      3 => 
      array (
        'title' => '编辑用户',
        'link' => 'admin.php?mod=member&code=search',
        'shortcut' => false,
      ),
      4 => 
      array (
        'title' => '当前在线用户',
        'link' => 'admin.php?mod=sessions',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '角色管理',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '管理员角色',
        'link' => 'admin.php?mod=role&code=list&type=admin',
        'shortcut' => false,
      ),
      12 => 
      array (
        'title' => '普通用户角色',
        'link' => 'admin.php?mod=role&code=list&type=normal',
        'shortcut' => false,
      ),
      13 => 
      array (
        'title' => '+添加用户角色',
        'link' => 'admin.php?mod=role&code=add',
        'shortcut' => false,
      ),
    ),
  ),
  7 => 
  array (
    'title' => '使用帮助',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '帮助手册',
        'link' => 'http://cenwor.com/thread-3524-1-1.html',
        'shortcut' => false,
      ),
	  2 => 
      array (
        'title' => '短信购买',
        'link' => 'http://cenwor.com/shop/brand.php?id=7',
        'shortcut' => false,
      ),
	  3 => 
      array (
        'title' => '支付平台',
        'link' => 'http://cenwor.com/thread-3955-1-1.html',
        'shortcut' => false,
      ),
	  1001 => 
      array (
        'title' => '技术支持',
        'link' => 'hr',
        'shortcut' => false,
      ),
	  11 => 
      array (
        'title' => '支持论坛',
        'link' => 'http://cenwor.com/',
        'shortcut' => false,
      ),
    ),
  ),
); ?>