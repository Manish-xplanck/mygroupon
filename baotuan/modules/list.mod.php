<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename list.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/



class ModuleObject extends MasterObject
{
    var $city;
    var $cityname;
    var $ProductLogic;
    var $PayLogic;
    var $MeLogic;
    var $OrderLogic;
    function ModuleObject( $config )
    {
        $this->MasterObject($config);         Load::logic('product');
        $this->ProductLogic = new ProductLogic();
        Load::logic('pay');
        $this->PayLogic = new PayLogic();
        Load::logic('me');
        $this->MeLogic = new MeLogic();
        Load::logic('order');
        $this->OrderLogic = new OrderLogic();
        $this->ID = ( int )($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);
        $this->CacheConfig = ConfigHandler::get('cache');         $this->ShowConfig = ConfigHandler::get('show');         $runCode = Load::moduleCode($this, $this->Code);
        $this->$runCode();
    }
    function Main()
    {
        $this->Faq();
    }
    function Faq()
    {
        $this->Title = __('��������');
        include ($this->TemplateHandler->Template("faq"));
    }
    function Ask()
    {
        $this->Title = __('�����ʴ�');
        $action = '?mod=list&code=doquestion';
        include ($this->TemplateHandler->Template("ask"));
    }
    function Doquestion()
    {
        extract($this->Post);
        if ( MEMBER_ID < 1 ) $this->Messager(__('�������ȵ�¼���ܷ����������ʣ�'));
        if ( $question == '' ) $this->Messager(__('���ⲻ����Ϊ��Ŷ��'));
        if ( $a = filter($question) ) $this->Messager($a);
        $ary = array(
            userid => MEMBER_ID, username => MEMBER_NAME, content => $question, time => time()
        );
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_question');
        $result = $this->DatabaseHandler->Insert($ary);
        $ary['time'] = date('Y-m-d H:i:s', $ary['time']);
        notify(MEMBER_ID, 'list.ask.new', $ary);
        $this->Messager(__("���ʳɹ�����ȴ�����Ա�Ļظ���"), "?mod=list&code=ask");
        exit();
    }
    function Help()
    {
        $this->Title = __('�Ź�ָ��');
        include ($this->TemplateHandler->Template("help"));
    }
    // add by caojianlong 2011-06-08
    function Link() {
		$this->Title = __('��������');
		include ($this->TemplateHandler->Template("link"));
    }
	function Guide() {
        $this->Title = __('��ת���챧����');
        include ($this->TemplateHandler->Template("guide"));
    }
	//end add by caojianlong

    function About()
    {
        $this->Title = __('�������챧��');
        include ($this->TemplateHandler->Template('about'));
    }
    function Privacy()
    {
        $this->Title = __('��˽����');
        include ($this->TemplateHandler->Template('privacy'));
    }
    function Contact()
    {
        $this->Title = __('��ϵ����');
        include ($this->TemplateHandler->Template('contact'));
    }
    function Join()
    {
        $this->Title = __('�������챧��');
        include ($this->TemplateHandler->Template('join'));
    }
    function Terms()
    {
        $this->Title = __('�û�Э��');
        include ($this->TemplateHandler->Template('terms'));
    }
    function Business()
    {         $this->Title = __('�������');
        $action = '?mod=index&code=doteamwork';
        include ($this->TemplateHandler->Template('business'));
    }
    function Doteamwork()
    {
        if ( $this->Post['name'] == '' || $this->Post['phone'] == '' || $this->Post['content'] == '' ) $this->Messager("ȱ�ٱ�Ҫ����������ȷ��д��");
        if ( $a = filter($this->Post['content']) ) $this->Messager($a);
        $ary = array(
            'name' => $this->Post['name'], 'phone' => $this->Post['phone'], 'elsecontat' => $this->Post['elsecontat'], 'content' => $this->Post['content'], 'time' => time(), 'type' => 2, 'readed' => 0
        );
        $this->MeLogic->UserMsg($ary);
        $this->Messager(__("�����Ѿ���¼�����ĺ�����Ϣ�����ǽ���������ظ���"), "?mod=list&code=business");
    }
    function Feedback()
    {         $this->Title = __('�������');
        $action = '?mod=index&code=dofeedback';
        include ($this->TemplateHandler->Template('feedback'));
    }
    function Dofeedback()
    {
        if ( $this->Post['name'] == '' || $this->Post['phone'] == '' || $this->Post['content'] == '' ) $this->Messager("ȱ�ٱ�Ҫ����������ȷ��д��");
        if ( false != $a = filter($this->Post['content']) ) $this->Messager($a);
        $ary = array(
            'name' => $this->Post['name'], 'phone' => $this->Post['phone'], 'elsecontat' => $this->Post['elsecontat'], 'content' => $this->Post['content'], 'time' => time(), 'type' => 1, 'readed' => 0
        );
        $this->MeLogic->UserMsg($ary);
        $this->Messager(__("�����Ѿ���¼�����ķ�����Ϣ����л���Ա�վ��֧�֣�"), "?mod=list&code=feedback");
    }
    function Deals()
    {
        $this->Title = __('��ʷ�Ź�');
        $product = logic('product')->GetList(logic('misc')->City('id'), PRO_ACV_No);
        include ($this->TemplateHandler->Template('deals'));
    }
    function History()
    {
        extract($this->Get);
        $p2 = 'class="current"';
        $nowdate = date('Y-m-d', time());
        $product = $this->ProductLogic->productGet(intval($id), $this->city);
        $imgs = explode('|', $product['img']);
        $IMG_BASE = IMAGE_PATH . 'product/';
        foreach ( $imgs as $i => $img )
        {
            if ( $img != '' )
            {
                $imgsx[$i] = $IMG_BASE . $img;
            }
        }
        $product['imgs'] = $imgsx;
        if ( ! $product ) $this->Messager(__("��Ʒ�����ڣ�"));
        $sellermap = explode(',', $product['sellermap']);
        $this->Title = $product['name'];
        $question = $this->OrderLogic->questionlist();
        include ($this->TemplateHandler->Template("tttuangou_history"));
    }
    function Sendemail()
    {
        extract($this->Post);
        if ( ! check_email($email) ) $this->Messager(__("�����ַ����"));
        if ( isset($del) )
        {
            $this->MeLogic->mail($email, $city, 0);
        }
        else
        {
            $this->MeLogic->mail($email, $city, 1);
        }
        $this->Messager(__("�����ɹ���"), "?");
    }
    function Invite()
    {
        $this->Title = __('�����н�');
        if ( MEMBER_ID < 1 )
        {
            $this->Messager(__("������ע����¼��"), '?mod=account&code=login');
        }
        $finder = $this->MeLogic->finderList(user()->get('id'));
        include ($this->TemplateHandler->Template("invite"));
    }

    function Ckticket()
    {
        $this->Title = __('���Ѿ��ѯ');
        $action = '?mod=list&code=dockticket';
        include ($this->TemplateHandler->Template("tttuangou_ckticket"));
    }
    function Dockticket()
    {
        extract($this->Get);
        if ( $number == '' ) exit('<font color="red">��Ų���Ϊ�գ�</font>');
        $sql = 'select * from ' . TABLE_PREFIX . 'tttuangou_ticket where number = \'' . $number . '\'';
        $query = $this->DatabaseHandler->Query($sql);
        $ticket = $query->GetRow();
        if ( $ticket['status'] === TICK_STA_Unused )
        {
                        $this->MeLogic->ticketCheck($ticket);
        }
        if ( $do == 'check' )
        {
            if ( empty($ticket) )
            {
                exit('<font color="red">������ȯ�����ڣ�</font>');
            }
            else
            {
                if ( $ticket['status'] == TICK_STA_Unused )
                {
                    $msg = '<font color="green">������ȯ����ʹ��</font>';
                }
                elseif ( $ticket['status'] == TICK_STA_Used )
                {
                    $msg = '<font color="blue">������ȯ�Ѿ���ʹ�ã�����ʱ�䣺' . $ticket['usetime'] . '</font>';
                }
                else
                {
                    $msg = '<font color="red">������ȯ�ѹ��ڣ�</font>';
                }
                exit($msg);
            }
            ;
        }
        elseif ( $do == 'getname' )
        {
            if ( empty($ticket) )
            {
                exit('<font color="red">��Ч������ȯ��</font>');
            }
            $sql = 'select s.userid,p.name from ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id  where p.id = ' . $ticket['productid'];
            $query = $this->DatabaseHandler->Query($sql);
            if ( $query )
            {
                $result = $query->GetRow();
                exit($result['name'].'<br/> X <font color="red"><b>'.$ticket['mutis'].'</b></font> ��');
            }
            else
            {
                exit('<font color="red">û���ҵ��ò�Ʒ��</font>');
            }
        }
        else
        {
            if ( empty($ticket) )
            {
                exit('<font color="red">������ȯ�����ڣ�</font>');
            }
            elseif ( $ticket['status'] == TICK_STA_Used )
            {
                exit('<font color="blue">������ȯ�Ѿ���ʹ�ã�����ʱ�䣺' . $ticket['usetime'] . '</font>');
            }
            elseif ( $ticket['status'] != TICK_STA_Unused )
            {
                exit('<font color="red">������ȯ�ѹ��ڣ�</font>');
            }
            ;
            $sql = 'select s.userid from ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id  where p.id = ' . $ticket['productid'];
            $query = $this->DatabaseHandler->Query($sql);
            $product = $query->GetRow();
            if ( $product['userid'] != MEMBER_ID ) exit('<font color="red">������ȯ���������Ĳ�Ʒ��</font>');
            if ( $password == $ticket['password'] )
            {
                $ary = array(
                    'status' => TICK_STA_Used, 'usetime' => date('Y-m-d H:i:s', time())
                );
                $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_ticket');
                $result = $this->DatabaseHandler->Update($ary, 'ticketid=' . $ticket['ticketid']);
                                logic('notify')->Call($ticket['uid'], 'logic.coupon.Used', array(
                    'productflag' => $product['flag'],
                    'number' => $ticket['number'],
                    'time' => $ary['usetime']
                ));
                exit('<font color="green">����ȯ��ȷ���Ѿ��ɹ�ʹ�ã�</font>');
            }
            ;
            exit('<font color="red">����ȯ�������</font>');
        }
    }
}
?>