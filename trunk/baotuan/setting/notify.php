<?php
/**
 * Config File of [notify]
 *
 * @time 2011-05-20 10:56:04
 */
$config["notify"] =  array (
  'api' => 
  array (
    'sms' => 
    array (
      'name' => '����֪ͨ',
      'enabled' => true,
    ),
    'mail' => 
    array (
      'name' => '�ʼ�֪ͨ',
      'enabled' => true,
    ),
    'qqrobot' => 
    array (
      'name' => 'QQ������',
      'enabled' => false,
      'server' => '127.0.0.1',
      'port' => '9103',
      'seckey' => 'QQRobot.Moyo.(C).im.uuland.org',
    ),
  ),
  'listener' => true,
  'event' => 
  array (
    'admin_mod_notify_Event_test' => 
    array (
      'struct' => '*',
      'name' => '��̨֪ͨ����',
      'msg' => 
      array (
        'sms' => '����֪ͨ����',
        'mail' => '�ʼ�֪ͨ����',
        'qqrobot' => '������֪ͨ����',
      ),
      'cfg' => 
      array (
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'mail' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'qqrobot' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
      'hook' => 
      array (
        'qqrobot' => 
        array (
          'enabled' => true,
        ),
        'sms' => 
        array (
          'enabled' => true,
        ),
        'mail' => 
        array (
          'enabled' => true,
        ),
      ),
      'intro' => '��̨֪ͨ���ԣ����������Ƿ��������֪ͨ',
    ),
    'logic_coupon_Create' => 
    array (
      'struct' => 'uid,productid,orderid,number,password,mutis,status,',
      'name' => '�Ź�ȯ����',
      'msg' => 
      array (
        'mail' => '��л���Ĺ���
�����ţ�{orderid}
�Ź�ȯ��ţ�{number}
���룺{password}',
        'qqrobot' => '��л���Ĺ���
�����ţ�{orderid}
�Ź�ȯ��ţ�{number}
���룺{password}',
        'sms' => '��л���Ĺ��������Ź�ȯ��ţ�{number}�����룺{password}���뾡��ʹ�ã�������ڣ�',
      ),
      'cfg' => 
      array (
        'mail' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'qqrobot' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
      'hook' => 
      array (
        'mail' => 
        array (
          'enabled' => true,
        ),
        'qqrobot' => 
        array (
          'enabled' => true,
        ),
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
      'intro' => '��һ���Ź�ȯ��������ʱ��ᴥ����֪ͨ',
    ),
    'logic_order_MakeSuccessed' => 
    array (
      'struct' => 'orderid,productflag,productnum,productprice,buytime,paymoney,paytime,expressprice,extmsg,',
      'name' => '�������',
      'intro' => '���û���һ�ʶ�������֧�����ʱ�ᴥ����֪ͨ',
      'hook' => 
      array (
        'mail' => 
        array (
          'enabled' => true,
        ),
      ),
      'msg' => 
      array (
        'mail' => '������ġ�{productflag}���Ķ����Ѿ���ɣ�����ţ�{orderid}������л���Ĺ���',
      ),
      'cfg' => 
      array (
        'mail' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
    ),
    'logic_pay_SendGoods' => 
    array (
      'struct' => 'trade_no,name,invoice,',
      'name' => '�̼ҷ���֪ͨ',
      'intro' => '���̼һ��߹���Ա�ϴ��˶���ʱ��ᴥ����֪ͨ',
      'hook' => 
      array (
        'sms' => 
        array (
          'enabled' => true,
        ),
        'mail' => 
        array (
          'enabled' => true,
        ),
      ),
      'msg' => 
      array (
        'sms' => '���ã����������Ʒ�Ѿ��������ͻ���ʽ��{name}���˵��ţ�{invoice}',
        'mail' => '���ã����������Ʒ�Ѿ��������ͻ���ʽ��{name}���˵��ţ�{invoice}',
      ),
      'cfg' => 
      array (
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'mail' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
    ),
    'admin_mod_order_AfService' => 
    array (
      'struct' => 'orderid,remark,',
      'name' => '���������ۺ����',
      'hook' => 
      array (
        'qqrobot' => 
        array (
          'enabled' => false,
        ),
      ),
      'msg' => 
      array (
        'qqrobot' => '',
      ),
      'cfg' => 
      array (
        'qqrobot' => 
        array (
          'cc2admin' => false,
          'al2user' => false,
        ),
      ),
      'intro' => '��̨�Զ��������ۺ����ʱ����',
    ),
    'logic_order_Confirm' => 
    array (
      'struct' => 'orderid,',
      'name' => '��������ȷ�϶���',
      'hook' => 
      array (
        'qqrobot' => 
        array (
          'enabled' => false,
        ),
      ),
      'msg' => 
      array (
        'qqrobot' => '',
      ),
      'cfg' => 
      array (
        'qqrobot' => 
        array (
          'cc2admin' => false,
          'al2user' => false,
        ),
      ),
      'intro' => '��̨�Զ�������ȷ�ϲ���ʱ����',
    ),
    'logic_order_Refund' => 
    array (
      'struct' => 'orderid,productflag,refundmoney,',
      'name' => '���������˿�',
      'intro' => '��̨�Զ��������˿����ʱ����',
    ),
    'logic_order_Cancel' => 
    array (
      'struct' => 'orderid,',
      'name' => '��������ȡ������',
      'intro' => '��̨�Զ�������ȡ������ʱ����',
    ),
    'admin_mod_coupon_Alert' => 
    array (
      'struct' => 'ticketid,uid,productid,orderid,number,password,usetime,mutis,status,name,flag,intro,perioddate,',
      'name' => '�Ź�ȯ��������',
      'hook' => 
      array (
        'qqrobot' => 
        array (
          'enabled' => true,
        ),
        'mail' => 
        array (
          'enabled' => true,
        ),
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
      'msg' => 
      array (
        'qqrobot' => '��ã�����Ź�ȯ�������ڣ�������Ʒ��{flag}',
        'mail' => '���ã������Ź�ȯ�������ڣ��뾡��ʹ�ã�',
        'sms' => '���ã������Ź�ȯ��{number}���������ڣ��뾡��ʹ�ã�',
      ),
      'cfg' => 
      array (
        'qqrobot' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'mail' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
      'intro' => '��̨�Ź�ȯ����ҳ����Ź�ȯ������������ʱ������֪ͨ',
    ),
    'admin_mod_login_done' => 
    array (
      'struct' => 'uid,username,password,secques,gender,adminid,regip,regdate,lastip,lastvisit,lastactivity,lastpost,oltime,pageviews,credits,extcredits1,extcredits2,email,bday,sigstatus,tpp,ppp,styleid,dateformat,timeformat,pmsound,showemail,newsletter,invisible,timeoffset,newpm,accessmasks,face,tag_count,role_id,role_type,new_msg_count,tag,own_tags,login_count,truename,phone,last_year_rank,last_month_rank,last_week_rank,this_year_rank,this_month_rank,this_week_rank,last_year_credit,last_month_credit,last_week_credit,this_year_credit,this_month_credit,this_week_credit,view_times,use_tag_count,create_tag_count,image_count,noticenum,ucuid,invite_count,invitecode,province,city,topic_count,at_count,follow_count,fans_count,email2,qq,msn,aboutme,at_new,comment_new,fans_new,topic_favorite_count,tag_favorite_count,disallow_beiguanzhu,validate,favoritemy_new,money,checked,finder,findtime,totalpay,',
      'hook' => 
      array (
        'qqrobot' => 
        array (
          'enabled' => false,
        ),
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
      'name' => '��̨�����¼',
      'intro' => '����̨�����ʺŵ�¼ʱ�ᴥ����֪ͨ��ǿ�ҽ������������¼��Ķ���֪ͨ�����ַǷ���¼ʱ���㼰ʱ�޸ĺ�̨���룬������վ��Ӫ��ȫ',
      'msg' => 
      array (
        'sms' => '�ʺţ�{username} �Ѿ���¼��̨����ȷ�ϣ�',
      ),
      'cfg' => 
      array (
        'sms' => 
        array (
          'cc2admin' => true,
          'al2user' => false,
        ),
      ),
    ),
    'logic_account_register_done' => 
    array (
      'struct' => 'username,truename,password,phone,email,showemail,role_id,checked,finder,findtime,ucuid,regip,regdate,',
      'name' => '�û�ע�����',
      'intro' => '���û�ע��ʱ�ᴥ����֪ͨ',
      'hook' => 
      array (
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
      'msg' => 
      array (
        'sms' => '���ã���л����ע�ᣬ�����Ź���ӭ���ĵ�����',
      ),
      'cfg' => 
      array (
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
    ),
    'logic_coupon_Used' => 
    array (
      'struct' => 'productflag,number,time,',
      'name' => '����ȯ��ʹ��',
      'intro' => '���û�������ȯ���̼ұ��Ϊ����ʹ�á�ʱ�ᴥ����֪ͨ',
      'msg' => 
      array (
        'sms' => '���ã������Ź�ȯ{number}�Ѿ���ʹ�ã�{time}',
      ),
      'cfg' => 
      array (
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
      'hook' => 
      array (
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
    ),
    'user_pay_confirm' => 
    array (
      'struct' => 'sign,trade_no,price,money,status,',
      'name' => '�ȴ��û�ȷ�ϸ���',
      'intro' => '���������˵������׽ӿڣ�������ƷΪ�Ź�ȯ��ʱ��Żᴥ����֪ͨ',
      'msg' => 
      array (
        'sms' => '���ã���վ�ѿ����������׽ӿڣ�Ϊ�˱��Ͻ��׵İ�ȫ�������ȵ�֧����ȷ�ϸ��������ǻ��Զ�Ϊ�������Ź�ȯ���ǳ���Ǹ����л����֧�֣�',
      ),
      'cfg' => 
      array (
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
      'hook' => 
      array (
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
    ),
    'list_ask_new' => 
    array (
      'struct' => 'userid,username,content,time,',
      'name' => '�ʴ�ģ����������',
      'intro' => '�û����ʴ�ģ����������ʱ��ᴥ��������',
      'msg' => 
      array (
        'qqrobot' => '���µ����⣺
{content}',
        'sms' => '��ã���������������⣺{content}',
      ),
      'cfg' => 
      array (
        'qqrobot' => 
        array (
          'cc2admin' => true,
          'al2user' => false,
        ),
        'sms' => 
        array (
          'cc2admin' => true,
          'al2user' => false,
        ),
      ),
      'hook' => 
      array (
        'qqrobot' => 
        array (
          'enabled' => false,
        ),
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
    ),
    'list_ask_reply' => 
    array (
      'struct' => 'id,userid,username,content,reply,time,',
      'name' => '�ظ��û�����',
      'intro' => '����Ա�ں�̨�ظ��û�����ʱ������֪ͨ',
      'hook' => 
      array (
        'qqrobot' => 
        array (
          'enabled' => false,
        ),
        'sms' => 
        array (
          'enabled' => true,
        ),
      ),
      'msg' => 
      array (
        'qqrobot' => '���ã�����Ա�ش����������⣡

{reply}',
        'sms' => '���ã�����Ա�ظ��������ʣ�{reply}',
      ),
      'cfg' => 
      array (
        'qqrobot' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
        'sms' => 
        array (
          'cc2admin' => false,
          'al2user' => true,
        ),
      ),
    ),
  ),
  'adminid' => 1,
);
?>