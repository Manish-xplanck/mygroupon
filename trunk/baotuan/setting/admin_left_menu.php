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
    'title' => '���ò���',
    'link' => '',
    'sub_menu_list' => 
    array (
    ),
  ),
  2 => 
  array (
    'title' => 'ȫ������',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=setting&code=modify_normal',
        'shortcut' => false,
      ),
      2 => 
      array (
        'title' => 'α��̬',
        'link' => 'admin.php?mod=setting&code=modify_rewrite',
        'shortcut' => false,
      ),
      3 => 
      array (
        'title' => '���ݹ���',
        'link' => 'admin.php?mod=setting&code=modify_filter',
        'shortcut' => false,
      ),
      4 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=link',
        'shortcut' => false,
      ),
      5 => 
      array (
        'title' => 'IP���ʿ���',
        'link' => 'admin.php?mod=setting&code=modify_access',
        'shortcut' => false,
      ),
      6 => 
      array (
        'title' => '��ҳ��������',
        'link' => 'admin.php?mod=tttuangou&code=indexnav',
        'shortcut' => false,
      ),
	  7 => 
      array (
        'title' => '�ͷ���Ϣ����',
        'link' => 'admin.php?mod=widget&code=block&op=config&flag=cservice',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '�Ź�����',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=tttuangou&code=varshow',
        'shortcut' => true,
      ),
	  12 => 
      array (
        'title' => '���������',
        'link' => 'admin.php?mod=widget',
        'shortcut' => false,
      ),
      1002 => 
      array (
        'title' => '�������',
        'link' => 'hr',
        'shortcut' => false,
      ),
      21 => 
      array (
        'title' => 'Ĭ��ģ��',
        'link' => 'admin.php?mod=tttuangou&code=defaultstyle',
        'shortcut' => true,
      ),
      22 => 
      array (
        'title' => 'վ��Logo',
        'link' => 'admin.php?mod=tttuangou&code=sitelogo',
        'shortcut' => false,
      ),
      23 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=tttuangou&code=shareconfig',
        'shortcut' => false,
      ),
	  24 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=ui&code=igos&op=config',
        'shortcut' => false,
      ),
    ),
  ),
  3 => 
  array (
    'title' => '�Ź�����',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '��Ʒ����',
        'link' => 'admin.php?mod=product',
        'shortcut' => true,
      ),
      2 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=order',
        'shortcut' => true,
      ),
      3 => 
      array (
        'title' => '�Ź�ȯ����',
        'link' => 'admin.php?mod=coupon',
        'shortcut' => false,
      ),
      4 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=delivery',
        'shortcut' => true,
      ),
      5 => 
      array (
        'title' => '��������',
        'link' => 'admin.php?mod=tttuangou&code=mainfinder',
        'shortcut' => false,
      ),
      6 => 
      array (
        'title' => '���й���',
        'link' => 'admin.php?mod=tttuangou&code=city',
        'shortcut' => false,
      ),
      7 => 
      array (
        'title' => '�̼ҹ���',
        'link' => 'admin.php?mod=tttuangou&code=mainseller',
        'shortcut' => true,
      ),
      8 => 
      array (
        'title' => '���͹���',
        'link' => 'admin.php?mod=express',
        'shortcut' => false,
      ),
	 9 => 
      array (
        'title' => '֧������',
        'link' => 'admin.php?mod=payment',
        'shortcut' => true,
      ),
      10 => 
      array (
        'title' => '���Ӷ���',
        'link' => 'admin.php?mod=voa',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '��������',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '���ݳ�ʼ��',
        'link' => 'admin.php?mod=tttuangou&code=clear',
        'shortcut' => false,
      ),
    ),
  ),
  4 => 
  array (
    'title' => '����Ӫ��',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '����ƽ̨����',
        'link' => 'admin.php?mod=service&code=sms',
        'shortcut' => true,
      ),
      2 => 
      array (
        'title' => 'Ⱥ���������',
        'link' => 'admin.php?mod=service',
        'shortcut' => false,
      ),
      3 => 
      array (
        'title' => '���Ĺ���',
        'link' => 'admin.php?mod=subscribe',
        'shortcut' => false,
      ),
	  4 => 
      array (
        'title' => '����Ⱥ��',
        'link' => 'admin.php?mod=subscribe&code=broadcast&class=mail',
        'shortcut' => false,
      ),
      5 => 
      array (
        'title' => '֪ͨ��ʽ',
        'link' => 'admin.php?mod=notify',
        'shortcut' => false,
      ),
      6 =>
      array (
        'title' => '֪ͨ�¼�����',
        'link' => 'admin.php?mod=notify&code=event',
        'shortcut' => false,
      ),
      7 =>
      array (
        'title' => '�ʴ����',
        'link' => 'admin.php?mod=tttuangou&code=mainquestion',
        'shortcut' => false,
      ),
      8 =>
      array (
        'title' => '������Ϣ',
        'link' => 'admin.php?mod=tttuangou&code=usermsg',
        'shortcut' => false,
      ),
	  1001 => 
      array (
        'title' => '���͹���',
        'link' => 'hr',
        'shortcut' => false,
      ),
	  11 => 
      array (
        'title' => '���Ͷ���',
        'link' => 'admin.php?mod=push&code=queue',
        'shortcut' => false,
      ),
	  2001 => 
      array (
        'title' => '���ݵ���',
        'link' => 'hr',
        'shortcut' => false,
      ),
	  21 => 
      array (
        'title' => '�ⲿ����',
        'link' => 'admin.php?mod=tttuangou&code=dataapi',
        'shortcut' => false,
      ),
    ),
  ),
  5 => 
  array (
    'title' => 'ϵͳ����',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '���»���',
        'link' => 'admin.php?mod=cache',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '���ݿ�',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '���ݱ���',
        'link' => 'admin.php?mod=db&code=export',
        'shortcut' => false,
      ),
      12 => 
      array (
        'title' => '���ݻָ�',
        'link' => 'admin.php?mod=db&code=import',
        'shortcut' => false,
      ),
      13 => 
      array (
        'title' => '���ݱ��Ż�',
        'link' => 'admin.php?mod=db&code=optimize',
        'shortcut' => false,
      ),
      1002 => 
      array (
        'title' => 'վ����Ϣ',
        'link' => 'hr',
        'shortcut' => false,
      ),
      21 => 
      array (
        'title' => '֩������ͳ��',
        'link' => 'admin.php?mod=robot',
        'shortcut' => false,
      ),
      22 => 
      array (
        'title' => '�ؼ�������',
        'link' => 'http://keyword.biniu.com',
        'shortcut' => false,
      ),
      23 => 
      array (
        'title' => 'alexa����',
        'link' => 'http://alexa.biniu.com',
        'shortcut' => true,
      ),
      24 => 
      array (
        'title' => '�������Ӽ��',
        'link' => 'http://checklink.biniu.com',
        'shortcut' => true,
      ),
      25 => 
      array (
        'title' => '��¼��ѯ',
        'link' => 'http://shoulu.biniu.com',
        'shortcut' => true,
      ),
      26 => 
      array (
        'title' => 'ͬIP��վ',
        'link' => 'http://sameip.biniu.com',
        'shortcut' => false,
      ),
      27 => 
      array (
        'title' => '�������ӷ���',
        'link' => 'http://backlink.biniu.com',
        'shortcut' => true,
      ),
    ),
  ),
  6 => 
  array (
    'title' => '�û�����',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => 'Ucenter����',
        'link' => 'admin.php?mod=ucenter',
        'shortcut' => false,
      ),
      2 => 
      array (
        'title' => '+������û�',
        'link' => 'admin.php?mod=member&code=add',
        'shortcut' => false,
      ),
      3 => 
      array (
        'title' => '�༭�û�',
        'link' => 'admin.php?mod=member&code=search',
        'shortcut' => false,
      ),
      4 => 
      array (
        'title' => '��ǰ�����û�',
        'link' => 'admin.php?mod=sessions',
        'shortcut' => false,
      ),
      1001 => 
      array (
        'title' => '��ɫ����',
        'link' => 'hr',
        'shortcut' => false,
      ),
      11 => 
      array (
        'title' => '����Ա��ɫ',
        'link' => 'admin.php?mod=role&code=list&type=admin',
        'shortcut' => false,
      ),
      12 => 
      array (
        'title' => '��ͨ�û���ɫ',
        'link' => 'admin.php?mod=role&code=list&type=normal',
        'shortcut' => false,
      ),
      13 => 
      array (
        'title' => '+����û���ɫ',
        'link' => 'admin.php?mod=role&code=add',
        'shortcut' => false,
      ),
    ),
  ),
  7 => 
  array (
    'title' => 'ʹ�ð���',
    'link' => '',
    'sub_menu_list' => 
    array (
      1 => 
      array (
        'title' => '�����ֲ�',
        'link' => 'http://cenwor.com/thread-3524-1-1.html',
        'shortcut' => false,
      ),
	  2 => 
      array (
        'title' => '���Ź���',
        'link' => 'http://cenwor.com/shop/brand.php?id=7',
        'shortcut' => false,
      ),
	  3 => 
      array (
        'title' => '֧��ƽ̨',
        'link' => 'http://cenwor.com/thread-3955-1-1.html',
        'shortcut' => false,
      ),
	  1001 => 
      array (
        'title' => '����֧��',
        'link' => 'hr',
        'shortcut' => false,
      ),
	  11 => 
      array (
        'title' => '֧����̳',
        'link' => 'http://cenwor.com/',
        'shortcut' => false,
      ),
    ),
  ),
); ?>