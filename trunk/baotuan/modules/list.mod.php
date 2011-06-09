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
        $this->Title = __('常见问题');
        include ($this->TemplateHandler->Template("faq"));
    }
    function Ask()
    {
        $this->Title = __('天天问答');
        $action = '?mod=list&code=doquestion';
        include ($this->TemplateHandler->Template("ask"));
    }
    function Doquestion()
    {
        extract($this->Post);
        if ( MEMBER_ID < 1 ) $this->Messager(__('您必须先登录才能发表您的提问！'));
        if ( $question == '' ) $this->Messager(__('问题不可以为空哦！'));
        if ( $a = filter($question) ) $this->Messager($a);
        $ary = array(
            userid => MEMBER_ID, username => MEMBER_NAME, content => $question, time => time()
        );
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_question');
        $result = $this->DatabaseHandler->Insert($ary);
        $ary['time'] = date('Y-m-d H:i:s', $ary['time']);
        notify(MEMBER_ID, 'list.ask.new', $ary);
        $this->Messager(__("提问成功，请等待管理员的回复！"), "?mod=list&code=ask");
        exit();
    }
    function Help()
    {
        $this->Title = __('团购指南');
        include ($this->TemplateHandler->Template("help"));
    }
    // add by caojianlong 2011-06-08
    function Link() {
		$this->Title = __('友情链接');
		include ($this->TemplateHandler->Template("link"));
    }
	function Guide() {
        $this->Title = __('玩转天天抱团网');
        include ($this->TemplateHandler->Template("guide"));
    }
	//end add by caojianlong

    function About()
    {
        $this->Title = __('关于天天抱团');
        include ($this->TemplateHandler->Template('about'));
    }
    function Privacy()
    {
        $this->Title = __('隐私保护');
        include ($this->TemplateHandler->Template('privacy'));
    }
    function Contact()
    {
        $this->Title = __('联系我们');
        include ($this->TemplateHandler->Template('contact'));
    }
    function Join()
    {
        $this->Title = __('加入天天抱团');
        include ($this->TemplateHandler->Template('join'));
    }
    function Terms()
    {
        $this->Title = __('用户协议');
        include ($this->TemplateHandler->Template('terms'));
    }
    function Business()
    {         $this->Title = __('商务合作');
        $action = '?mod=index&code=doteamwork';
        include ($this->TemplateHandler->Template('business'));
    }
    function Doteamwork()
    {
        if ( $this->Post['name'] == '' || $this->Post['phone'] == '' || $this->Post['content'] == '' ) $this->Messager("缺少必要参数，请正确填写！");
        if ( $a = filter($this->Post['content']) ) $this->Messager($a);
        $ary = array(
            'name' => $this->Post['name'], 'phone' => $this->Post['phone'], 'elsecontat' => $this->Post['elsecontat'], 'content' => $this->Post['content'], 'time' => time(), 'type' => 2, 'readed' => 0
        );
        $this->MeLogic->UserMsg($ary);
        $this->Messager(__("我们已经记录下您的合作信息，我们将尽快给您回复！"), "?mod=list&code=business");
    }
    function Feedback()
    {         $this->Title = __('意见反馈');
        $action = '?mod=index&code=dofeedback';
        include ($this->TemplateHandler->Template('feedback'));
    }
    function Dofeedback()
    {
        if ( $this->Post['name'] == '' || $this->Post['phone'] == '' || $this->Post['content'] == '' ) $this->Messager("缺少必要参数，请正确填写！");
        if ( false != $a = filter($this->Post['content']) ) $this->Messager($a);
        $ary = array(
            'name' => $this->Post['name'], 'phone' => $this->Post['phone'], 'elsecontat' => $this->Post['elsecontat'], 'content' => $this->Post['content'], 'time' => time(), 'type' => 1, 'readed' => 0
        );
        $this->MeLogic->UserMsg($ary);
        $this->Messager(__("我们已经记录下您的反馈信息，感谢您对本站的支持！"), "?mod=list&code=feedback");
    }
    function Deals()
    {
        $this->Title = __('历史团购');
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
        if ( ! $product ) $this->Messager(__("商品不存在！"));
        $sellermap = explode(',', $product['sellermap']);
        $this->Title = $product['name'];
        $question = $this->OrderLogic->questionlist();
        include ($this->TemplateHandler->Template("tttuangou_history"));
    }
    function Sendemail()
    {
        extract($this->Post);
        if ( ! check_email($email) ) $this->Messager(__("邮箱地址有误！"));
        if ( isset($del) )
        {
            $this->MeLogic->mail($email, $city, 0);
        }
        else
        {
            $this->MeLogic->mail($email, $city, 1);
        }
        $this->Messager(__("操作成功！"), "?");
    }
    function Invite()
    {
        $this->Title = __('邀请有奖');
        if ( MEMBER_ID < 1 )
        {
            $this->Messager(__("请您先注册或登录！"), '?mod=account&code=login');
        }
        $finder = $this->MeLogic->finderList(user()->get('id'));
        include ($this->TemplateHandler->Template("invite"));
    }

    function Ckticket()
    {
        $this->Title = __('消费卷查询');
        $action = '?mod=list&code=dockticket';
        include ($this->TemplateHandler->Template("tttuangou_ckticket"));
    }
    function Dockticket()
    {
        extract($this->Get);
        if ( $number == '' ) exit('<font color="red">编号不能为空！</font>');
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
                exit('<font color="red">该消费券不存在！</font>');
            }
            else
            {
                if ( $ticket['status'] == TICK_STA_Unused )
                {
                    $msg = '<font color="green">该消费券可以使用</font>';
                }
                elseif ( $ticket['status'] == TICK_STA_Used )
                {
                    $msg = '<font color="blue">该消费券已经被使用，消费时间：' . $ticket['usetime'] . '</font>';
                }
                else
                {
                    $msg = '<font color="red">该消费券已过期！</font>';
                }
                exit($msg);
            }
            ;
        }
        elseif ( $do == 'getname' )
        {
            if ( empty($ticket) )
            {
                exit('<font color="red">无效的消费券！</font>');
            }
            $sql = 'select s.userid,p.name from ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id  where p.id = ' . $ticket['productid'];
            $query = $this->DatabaseHandler->Query($sql);
            if ( $query )
            {
                $result = $query->GetRow();
                exit($result['name'].'<br/> X <font color="red"><b>'.$ticket['mutis'].'</b></font> 份');
            }
            else
            {
                exit('<font color="red">没有找到该产品！</font>');
            }
        }
        else
        {
            if ( empty($ticket) )
            {
                exit('<font color="red">该消费券不存在！</font>');
            }
            elseif ( $ticket['status'] == TICK_STA_Used )
            {
                exit('<font color="blue">该消费券已经被使用，消费时间：' . $ticket['usetime'] . '</font>');
            }
            elseif ( $ticket['status'] != TICK_STA_Unused )
            {
                exit('<font color="red">该消费券已过期！</font>');
            }
            ;
            $sql = 'select s.userid from ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id  where p.id = ' . $ticket['productid'];
            $query = $this->DatabaseHandler->Query($sql);
            $product = $query->GetRow();
            if ( $product['userid'] != MEMBER_ID ) exit('<font color="red">此消费券不属于您的产品！</font>');
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
                exit('<font color="green">消费券正确，已经成功使用！</font>');
            }
            ;
            exit('<font color="red">消费券密码错误！</font>');
        }
    }
}
?>