<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename me.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    var $city;
    var $config;
    function ModuleObject( $config )
    {
        $this->MasterObject($config);         if (MEMBER_ID < 1)
        {
            $this->Messager(__('���ȵ�¼��'), '?mod=account&code=login');
        }
        Load::logic('product');
        $this->ProductLogic = new ProductLogic();
        Load::logic('pay');
        $this->PayLogic = new PayLogic();
        Load::logic('me');
        $this->MeLogic = new MeLogic();
        Load::logic('order');
        $this->OrderLogic = new OrderLogic();
        $this->config = $config;
        $this->ID = ( int )($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);
        $this->CacheConfig = ConfigHandler::get('cache');         $this->ShowConfig = ConfigHandler::get('show');         $runCode = Load::moduleCode($this, $this->Code);
        $this->$runCode();
    }
    function Main()
    {
        $_GET['code'] = 'coupon';
        $this->Coupon();
    }
    function Coupon()
    {
        $this->Title = __('�ҵ��Ź�ȯ');
        $status = $this->Get['status'];
        if ($status == '')
        {
            $status = -1;
        }
        else
        {
            $status = (int)$status;
        }
        $ticket_all = logic('coupon')->GetList(user()->get('id'), ORD_ID_ANY, $status);
        
                $_s1=$_s2=$_s3= $_s4=3;
        if($status==-1) $_s1=2;
        if($status== 0) $_s2=2;
        if($status== 1) $_s3=2;
        if($status== 2) $_s4=2;
        
        include handler('template')->file('my_coupon');
    }
    
    function Order()
    {
        $this->Title = __('�ҵĶ���');
        $pay = $this->Get['pay'];
        if ($pay == '')
        {
            $pay = -1;
        }
        else
        {
            $pay = (int)$pay;
        }
        $order_all = logic('order')->GetList(user()->get('id'), -1, $pay);
        
                $_s1= $_s2 = $_s3=3;
        if($pay==-1) $_s1=2;
        if($pay==1 ) $_s2=2;
        if($pay==0 ) $_s3=2;
        
        include handler('template')->file('my_order');
    }
    
    function Bill()
    {
        $this->Title = __('�����굥');
        $usermoney = logic('me')->money()->log();
        include handler('template')->file('my_bill');
    }
    
    function Setting()
    {
        $this->Title = __('�˻�����');
        $user = user()->get();
        include handler('template')->file('my_setting');
    }
    
    function Address()
    {
        $this->Title = __('���͵�ַ');
        $addressList = logic('address')->GetList(user()->get('id'));
        include handler('template')->file('my_address');
    }
    
    
    function Cancel()
    {
                extract($this->Get);
        $this->OrderLogic->orderType($orderid, '0');
        $this->Messager("���Ѿ��ɹ�ȡ���ö�����", "?mod=me&code=order");
    }
    function Doinfo()
    {
        extract($this->Post);
        $ary = array();
        if ( $newpwd == $confirmpwd && $newpwd != '' )
        {
            $ary['password'] = md5($newpwd);
                        if ( true === UCENTER )
            {
                include_once (UC_CLIENT_ROOT . './client.php');
                $result = uc_user_edit(MEMBER_NAME, '', $newpwd, '', 1);
                if ( $result != 1 )
                {
                    $this->Messager('֪ͨUC�޸�����ʧ�ܣ��������UC���ã�');
                }
            }
        };
        if ( $phone != '' )
        {
            $ary['phone'] = $phone;
        };
        $sql = 'select `email` from ' . TABLE_PREFIX . 'system_members where uid = ' . MEMBER_ID;
        $query = $this->DatabaseHandler->Query($sql);
        $user = $query->GetRow();
                        $ary['qq'] = $qq;
        if ( $user['email'] != $email )
        {
            $ary['email'] = $email;
            if ( $this->config['default_emailcheck'] )
            {
                $ary['checked'] = 0;
            }
        }
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'system_members');
        $result = $this->DatabaseHandler->Update($ary, 'uid = ' . MEMBER_ID);
        $this->Messager("�����޸ĳɹ���", "?mod=me&code=setting");
    }
    function Printticket()
    {
        extract($this->Get);
        $order = $this->OrderLogic->GetTicket($id);
        $pwd = $order['password'];
        if ( $order == '' || $pwd == '' ) $this->Messager("��ȡ�Ź�ȯ���ִ���", "?mod=me");
        include ($this->TemplateHandler->Template("tttuangou_printticket"));
    }
    
    function Addmoney()
    {
        $money = $this->MeLogic->moneyMe();
        $pay = $this->PayLogic->payType(intval($id), $this->city);
        $action = '?mod=me&code=doaddmoney';
        include ($this->TemplateHandler->Template("tttuangou_addmoney"));
    }
    function Topay( $mod, $returnurl, $pay )
    {
        $payment = unserialize($pay['pay_config']);
        $returnurl .= '&pay=' . $mod;
        include_once ('./modules/' . $mod . '.pay.php');
        $bottom = payTo($payment, $returnurl, $pay);
        return $bottom;
    }
    function Doaddmoney()
    {
        $this->Post['money'] = round($this->Post['money'], 2);
        if ( $this->Post['paytype'] == '' ) $this->Messager("��û��ѡ��֧����ʽ����û���ʺϵ�֧���ӿڣ�");
        if ( ! is_numeric($this->Post['money']) || $this->Post['money'] <= 0 ) $this->Messager("��ֵ���������0��");
        $pay = $this->PayLogic->payChoose(intval($this->Post['paytype']));
        $pay['orderid'] = time() . rand(10000, 99999);
        $pay['price'] = $this->Post['money'];
        $pay['name'] = '�˻���ֵ';
        $pay['show_url'] = $this->Config['site_url'] . '/?mod=me&code=money';
        $returnurl = $this->Config['site_url'] . '/index.php?mod=me&code=readdmoney';
        $bottom = $this->Topay($pay['pay_code'], $returnurl, $pay);
        include ($this->TemplateHandler->Template('tttuangou_doaddmoney'));
    }
    function Readdmoney()
    {
        $pay_code = (isset($_POST['pay']) ? $_POST['pay'] : $_GET['pay']);
        if ( $pay_code == '' )
        {
            $this->Messager('�������ݴ���');
        }
        if ( isset($_GET['pay']) )
        {
            $is_notify = false;
            $userID = MEMBER_ID;
        }
        elseif ( isset($_POST['pay']) )
        {
            $is_notify = true;
            $userID = $_POST['uid'];
        }
        include_once ('./modules/' . $pay_code . '.pay.php');
        $msg = addmymoney($userID);
        $oid = isset($_GET['out_trade_no']) ? $_GET['out_trade_no'] : $_POST['out_trade_no'];
        $trade_status = isset($_GET['trade_status']) ? $_GET['trade_status'] : $_POST['trade_status'];
        $pay = $this->PayLogic->payChoose($pay_code);
        $pay = unserialize($pay['pay_config']);
        $ifTrust = true;
        if ( $pay['alipay_iftype'] == 'distrust' )
        {
            $ifTrust = false;
        }
        if ( is_array($msg) )
        {
            if ( $is_notify )
            {
                $trade_no = $_POST['trade_no'];
                if ( $ifTrust || $trade_status == 'TRADE_FINISHED' )
                {
                                        $this->Dopay($msg['price'], $msg['orderid'], $userID, $trade_no);
                }
                if ( ! $ifTrust && $trade_status == 'WAIT_SELLER_SEND_GOODS' )
                {
                                        $url = sendGoods($trade_no, $pay);
                    $doc = new DOMDocument();
                    $doc->load($url);
                                     }
                exit('success');
            }
            if ( $pay_code != 'alipay' )
            {
                if ( $pay_code == 'tenpay' )
                {
                    $trade_no = $_REQUEST['transaction_id'];
                }
                elseif ( $pay_code == 'kuaiqian' )
                {
                    $trade_no = $_REQUEST['dealId'];
                }
                $result = $this->Dopay($msg['price'], $msg['orderid'], $userID, $trade_no);
                $this->Messager($result, '?mod=me&code=money');
            }
            else
            {
                if ( ! $ifTrust && $trade_status != 'TRADE_FINISHED' )
                {
                                        $this->Messager('֧����û����ɣ�������ȷ���ջ���֮��ϵͳ����Զ���ɱ��ν��ף�', 'http:/' . '/lab.alipay.com/consume/record/buyerConfirmTrade.htm?tradeNo=' . $_GET['trade_no'], 5);
                }
            }
            $this->Messager('��ֵ�ɹ���', '?mod=me&code=money');
        }
        else
        {
            if ( $is_notify )
            {
                exit('success');
            }
                        if ( $pay_code == 'alipay' && $msg == '�������ظ���ֵͬһ��������ֵʧ�ܣ�' )
            {
                $this->Messager('��ֵ�ɹ���', '?mod=me&code=money');
            }
            $this->Messager($msg, '?mod=me&code=money');
        }
        ;
    }
    function Dopay( $price, $orderid, $userID, $trade_no )
    {
        if ( $price == '' || $orderid == '' )
        {
            return "֧��ʧ��!";
        }
        ;
                if ( $price > 0 )
        {
            $pay = $this->MeLogic->moneyAdd($price, $userID);
            $ary = array( 
                'userid' => $userID, 'type' => 1, 'name' => 'ֱ�ӳ�ֵ', 'intro' => '��Ϊ�˻���ֵ' . $price . 'Ԫ', 'money' => $price, 'time' => time(), 'trade_no' => $trade_no 
            );
            $this->MeLogic->moneyLog($ary);
        }
        ;
                $ary = array( 
            'id' => $orderid, 'money' => $price, 'userid' => $userID, 'paytime' => date('Y-m-d H:i:s') 
        );
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_addmoney');
        $result = $this->DatabaseHandler->Insert($ary);
        return '��ֵ�ɹ���';
    }
    
    function Face()
    {
        $sql = 'select ucuid from ' . TABLE_PREFIX . 'system_members where uid = ' . MEMBER_ID;
        $query = $this->DatabaseHandler->Query($sql);
        $usr = $query->GetRow();
        if ( UCENTER )
        {
            include_once (UC_CLIENT_ROOT . './client.php');
            $face = uc_avatar($usr['ucuid']);
        }
        else
        {
            ;
        }
        include ($this->TemplateHandler->Template("tttuangou_face"));
    }
    
        
    function __AddressCheckOns( $id )
    {
        return false;
        $sql = 'SELECT COUNT(orderid) AS CNT FROM ' . TABLE_PREFIX . 'tttuangou_order WHERE addressid=' . $id . ' AND status IN(1,4)';
        $query = $this->DatabaseHandler->Query($sql)->GetRow();
        if ( $query['CNT'] > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }
}
?>