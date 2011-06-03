<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename order.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class OrderLogic
{
	
    public function GetFree($uid = 0, $product_id = 0)
    {
        if ($uid == 0)
        {
            $uid = user()->get('id');
        }
        if ($product_id == 0)
        {
            return false;
        }
                $order = $this->Where('userid='.$uid.' AND status=255');
        if ($order)
        {
            $order = $order[0];
        }
        else
        {
                                    $order = $this->Where('userid='.$uid.' AND productid='.$product_id.' AND status=1 AND pay=0 AND buytime>'.(time()-600));
            if ($order)
            {
                $order = $order[0];
            }
            else
            {
                $order = $this->__CreateNew($uid, $product_id);
            }
        }
        return $order;
    }
    
    private function __CreateNew($uid, $product_id)
    {
        $array = array(
            'orderid' => $this->__GetFreeID(),
            'productid' => $product_id, 
            'userid' => $uid,
            'buytime' => time(),
            'status' => 255
        );
        dbc()->SetTable(table('order'));
        dbc()->Insert($array);
        return $array;    
    }
    
    private function __GetFreeID()
    {
        $id = date('Ymd', time()) . str_pad(rand('1', '99999'), 5, '0', STR_PAD_LEFT);
        $sql = '
        SELECT
            *
        FROM
            ' . table('order') . '
        WHERE
            orderid = ' . $id;
        $order = dbc()->Query($sql)->GetRow();
        if ( empty($order) )
        {
            return $id;
        }
        else
        {
            return $this->__GetFreeID();
        }
    }
    
    public function GetOne( $id, $uid = 0 )
    {
        $sql_limit_user = '1';
        if ($uid > 0)
        {
            $sql_limit_user = 'userid = '.$uid;
        }
        $sql = '
        SELECT
            *
        FROM
            ' . table('order') .'
        WHERE
            orderid = ' . $id . '
        AND
            ' . $sql_limit_user;
        $order = dbc()->Query($sql)->GetRow();
        return $this->__parse_result($order);
    }
    
    public function GetList( $uid = 0, $status = -1, $pay = -1, $extend = '1' )
    {
        $sql_limit_user = '1';
        if ($uid > 0)
        {
            $sql_limit_user = 'userid = '.$uid;
        }
        $sql_limit_status = '1';
        if ( $status >= 0 )
        {
            $sql_limit_status =  'status = '.$status;
        }
        $sql_limit_pay = '1';
        if ( $pay >= 0 )
        {
            $sql_limit_pay = 'pay = '.$pay;
        }
        $sql = '
        SELECT
            o.*, m.username
        FROM
            ' . table('order') .' o
        LEFT JOIN
        	' .table('members'). ' m
        ON
        	(o.userid=m.uid)
        WHERE
            '.$sql_limit_user.'
        AND
            '.$sql_limit_status.'
        AND
            '.$sql_limit_pay.'
        AND
        	'.$extend.'
        ORDER BY
            buytime
        DESC';
        logic('isearcher')->Linker($sql);
        $sql = page_moyo($sql);
        $order = dbc()->Query($sql)->GetAll();
        return $this->__parse_result($order);
    }
    
    public function SrcOne($id)
    {
        $sql = 'SELECT * FROM '.table('order').' WHERE orderid='.$id;
        return dbc()->Query($sql)->GetRow();
    }
    
    public function SrcList( $uid = USR_ANY, $status = ORD_STA_ANY, $pay = ORD_PAID_ANY, $extend = '1' )
    {
        $sql_limit_user = '1';
        if ($uid > 0)
        {
            $sql_limit_user = 'userid = '.$uid;
        }
        $sql_limit_status = '1';
        if ( $status >= 0 )
        {
            $sql_limit_status =  'status = '.$status;
        }
        $sql_limit_pay = '1';
        if ( $pay >= 0 )
        {
            $sql_limit_pay = 'pay = '.$pay;
        }
        $sql = '
        SELECT
            *
        FROM
            ' . table('order') .'
        WHERE
            '.$sql_limit_user.'
        AND
            '.$sql_limit_status.'
        AND
            '.$sql_limit_pay.'
        AND
        	'.$extend.'
        ORDER BY
            buytime
        DESC';
        $order = dbc()->Query($sql)->GetAll();
        return $order;
    }
    
    public function Where($sql_limit)
    {
        $sql = '
        SELECT
            *
        FROM
            '.table('order').'
        WHERE
            '.$sql_limit.'
        ';
        return dbc()->Query($sql)->GetAll();
    }
    
    public function Update($id, $array)
    {
        dbc()->SetTable(table('order'));
        dbc()->Update($array, 'orderid = '.$id);
    }
    
    public function Delete($id)
    {
        $sqls = array();
                $sqls[] = 'DELETE FROM '.table('order').' WHERE orderid='.$id;
                $sqls[] = 'DELETE FROM '.table('order_clog').' WHERE sign='.$id;
                $sqls[] = 'DELETE FROM '.table('paylog').' WHERE sign='.$id;
                $sqls[] = 'DELETE FROM '.table('ticket').' WHERE orderid='.$id;
        foreach ($sqls as $i => $sql)
        {
            dbc()->Query($sql);
        }
        return true;
    }
    
    public function Count($where)
    {
        $sql = 'SELECT COUNT(*) as CNT FROM '.table('order').' WHERE '.$where;
        $result = dbc()->Query($sql)->GetRow();
        return $result['CNT'];
    }
    
    public function Summary($where)
    {
        $sql = 'SELECT SUM(productprice*productnum) as TTL FROM '.table('order').' WHERE '.$where;
        $result = dbc()->Query($sql)->GetRow();
        return $result['TTL'];
    }
    
    public function _TMP_Payed($trade)
    {
        $id = $trade['sign'];
        $order = $this->GetOne($id);
        if ($order['pay'] == 0)
        {
            if ($trade['money'] > 0)
            {
                                logic('me')->money()->add($trade['price'], $order['userid'], array(
                	'name' => __('账户充值'),
                	'intro' => sprintf(__('订单号：%s<br/>交易单号：%s'), $order['orderid'], $trade['trade_no'])
                ));
            }
            else
            {
                                if (!$trade['nmadd'])
                {
                	logic('me')->money()->add($trade['price'], $order['userid']);
                }
            }
            $money = logic('me')->money()->count($order['userid']);
            $price = $order['productprice']*$order['productnum']+$order['expressprice'];
            if ($money >= $price && $order['status'] == ORD_STA_Normal)
            {
                $express = '';
                if ($order['expressprice'] > 0)
                {
                    $express = sprintf(__('运费：%.2f'), $order['expressprice']);
                }
                logic('me')->money()->pay($price, $order['userid'], array(
                    'name' => __('团购商品'),
                    'intro' => sprintf(__('商品名：%s<br/>单价：%.2f<br/>数目：%d<br/>%s'), $order['product']['flag'], $order['productprice'], $order['productnum'], $express)
                ));
                                $order_update = array(
                    'pay' => 1,
                    'paytime' => time()
                );
                dbc()->SetTable(table('order'));
                dbc()->Update($order_update, 'orderid='.$id);
                                logic('order')->doSuccess($order);
                                $this->Processed($order['orderid'], '__PAY_YET__');
            }
            else
            {
                            }
        }
        return $order;
    }
    
    public function Processed($orderid, $process)
    {
        $data = array(
            'process' => $process
        );
        dbc()->SetTable(table('order'));
        dbc()->Update($data, 'orderid='.$orderid);
    }
    
    public function MakeSuccessed($orderid, $ignore_rules = false)
    {
        $order = $this->GetOne($orderid);
        if (false == $ignore_rules)
        {
            if ($order['product']['succ_remain'] > 1)
            {
                                return $order;
            }
            else
            {
                                logic('product')->Maintain($order['productid']);
                                logic('order')->findSuccess($order['productid']);
                return $order;
            }
        }
        if ($order['product']['type'] == 'ticket')
        {
            $tickets = logic('coupon')->GetList($order['userid'], $order['orderid'], TICK_STA_ANY);
            if (count($tickets) > 0)
            {
                                            }
            else
            {
                                if ($order['product']['allinone'] == 'true')
                {
                                        logic('coupon')->Create($order['productid'], $order['orderid'], $order['userid'], $order['productnum']);
                }
                else
                {
                                        for ($i=0; $i<$order['productnum']; $i++)
                    {
                        logic('coupon')->Create($order['productid'], $order['orderid'], $order['userid']);
                    }
                }
            }
                        logic('order')->Processed($order['orderid'], 'TRADE_FINISHED');
        }
        else
        {
            if ($order['process'] != 'WAIT_SELLER_SEND_GOODS')
            {
                                logic('order')->Processed($order['orderid'], 'WAIT_SELLER_SEND_GOODS');
            }
            else
            {
                                logic('order')->Processed($order['orderid'], 'TRADE_FINISHED');
            }
        }
                $this->doRelSuccess($order);
                $MID = 'notify_YET_'.$orderid;
        if (meta($MID))
        {
                        meta($MID, null);
            return $order;
        }
        logic('notify')->Call($order['userid'], 'logic.order.MakeSuccessed', array(
            'orderid' => $order['orderid'],
            'productflag' => $order['product']['flag'],
            'productnum' => $order['productnum'],
            'productprice' => $order['productprice'],
            'buytime' => $order['buytime'],
            'paymoney' => $order['paymoney'],
            'paytime' => $order['paytime'],
            'expressprice' => $order['expressprice'],
            'extmsg' => $order['extmsg']
        ));
        meta($MID, time(), 'd:30');
        return $order;
    }
    
    public function findSuccess($pid)
    {
        $process_search = 'productid = '.$pid.' AND process IN("__PAY_YET__","WAIT_SELLER_SEND_GOODS","WAIT_BUYER_CONFIRM_GOODS")';
        $order_list = logic('order')->SrcList(USR_ANY, ORD_STA_Normal, ORD_PAID_Yes, $process_search);
        if (count($order_list) > 0)
        {
            foreach ($order_list as $i => $order_one)
            {
                logic('order')->MakeSuccessed($order_one['orderid'], true);
            }
        }
    }
	
    private function __parse_result($data)
    {
        if ( ! $data ) return false;
        if ( is_array($data[0]) )
        {
            $return = array();
            foreach ( $data as $i => $one )
            {
                $return[] = $this->__parse_result($one);
            }
            return $return;
        }
        $data['product'] = logic('product')->GetOne($data['productid']);
        return $data;
    }
	
    public function STA_Name($STA_Code)
    {
        $STA_NAME_MAP = array(
            ORD_STA_Cancel => '已经取消',
            ORD_STA_Failed => '订单失败',
            ORD_STA_Normal => '订单正常',
            ORD_STA_Overdue => '已经过期',
            ORD_STA_Refund => '已经返款'
        );
        return $STA_NAME_MAP[$STA_Code];
    }
    
    public function PROC_Name($PRO_Code)
    {
        $PROC_NAME_MAP = array(
            '__CREATE__' => '创建订单',
            '__PAY_YET__' => '已经付款',
            'WAIT_BUYER_PAY' => '等待付款',
            'WAIT_SELLER_SEND_GOODS' => '等待发货',
            'WAIT_BUYER_CONFIRM_GOODS' => '等待收货',
            'TRADE_FINISHED' => '交易完成'
        );
        return $PROC_NAME_MAP[$PRO_Code];
    }
    
    public function clog($oid)
    {
        $SID = 'logic.order.clog';
        $obj = moSpace($SID);
        if ( ! $obj )
        {
            $obj = moSpace($SID, (new OrderLogic_cLog()));
        }
        $obj->CSIGN = $oid;
        return $obj;
    }
    
    public function Refund($oid)
    {
        $order = logic('order')->GetOne($oid);
                logic('order')->Update($oid, array('status'=>ORD_STA_Refund));
                logic('order')->doFailed($order);
                logic('me')->money()->add($order['paymoney'], $order['userid'], array(
            'name' => __('订单退款'),
            'intro' => '您好，您的订单[ '.$oid.' ]已经退款！'
        ));
                logic('notify')->Call($order['userid'], 'logic.order.Refund', array(
            'orderid' => $oid,
            'productflag' => $order['product']['flag'],
            'refundmoney' => $order['productnum']*$order['productprice']
        ));
        return true;
    }
    
    public function Confirm($oid)
    {
        $order = logic('order')->SrcOne($oid);
        logic('me')->money()->add($order['totalprice'], $order['userid']);
                $trade = array(
            'sign' => $oid,
            'trade_no' => time(),
            'price' => $order['totalprice'],
            'money' => 0,
            'status' => 'TRADE_FINISHED'
        );
        logic('order')->_TMP_Payed($trade);
        logic('order')->MakeSuccessed($trade['sign']);
        logic('notify')->Call($order['userid'], 'logic.order.Confirm', array(
            'orderid' => $oid
        ));
        return true;
    }
    
    public function Cancel($oid)
    {
        $order = logic('order')->GetOne($oid);
        logic('order')->Update($oid, array('status'=>ORD_STA_Cancel));
        if ($order['pay'] == ORD_PAID_Yes)
        {
            logic('me')->money()->add($order['paymoney'], $order['userid'], array(
                'name' => __('订单取消'),
                'intro' => '您好，您的订单[ '.$oid.' ]已经被取消，余款已经充值到您的个人账户！'
            ));
            logic('order')->doFailed($order);
        }
        logic('notify')->Call($order['userid'], 'logic.order.Cancel', array(
            'orderid' => $oid
        ));
        return true;
    }
    
    
    public function doSuccess($order)
    {
                logic('seller')->money_add($order['product']['sellerid'], $order['productnum']*$order['productprice']);
        logic('seller')->order_success($order['product']['sellerid']);
                dbc(DBCMax)->update('product')->data('totalnum=totalnum+1')->where('id='.$order['productid'])->done();
    }
    
    public function doFailed($order)
    {
                $sid = $order['product']['sellerid'];
        logic('seller')->money_less($sid, $order['productnum']*$order['productprice']);
        logic('seller')->order_failed($sid);
                dbc(DBCMax)->update('product')->data('totalnum=totalnum-1')->where('id='.$order['productid'])->done();
    }
    
    public function doRelSuccess($order)
    {
        logic('me')->finder($order['userid'], $order['productid']);
    }

    
    function orderCheck( $id )
    {
        $sql = 'select * from ' . TABLE_PREFIX . 'tttuangou_order where orderid=' . $id . ' and status=1 and pay=0';
        $query = $this->DatabaseHandler->Query($sql);
        $order = $query->GetRow();
        return $order;
    }

    function orderGetByUser( $productid, $uid, $checkMuti = false )
    {
        if ( false == $checkMuti )
        {
            $sql = 'select * from ' . TABLE_PREFIX . 'tttuangou_order where productid=' . intval($productid) . ' and userid=' . intval($uid) . ' and status=1';
        }
        else
        {
            $sql = 'select o.*,p.multibuy from ' . TABLE_PREFIX . 'tttuangou_order o LEFT JOIN ' . TABLE_PREFIX . 'tttuangou_product p ON(o.productid=p.id) where o.productid=' . intval($productid) . ' and o.userid=' . intval($uid) . '';
        }
        $query = $this->DatabaseHandler->Query($sql);
        if ( ! $query ) return '';
        $order = $query->GetRow();
        if ( $order['multibuy'] != '' && $order['multibuy'] == 'true' )
        {
                        return '';
        }
        return $order;
    }

    function orderEdit( $orderid, $ary = array() )
    {
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_order');
        $result = $this->DatabaseHandler->Update($ary, 'orderid=' . $orderid);
        return $result;
    }

    function orderCreater( $ary = array() )
    {
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_order');
        $result = $this->DatabaseHandler->Insert($ary);
        return $result;
    }

    function orderType( $id, $type, $paytime = '', $pay = '' )
    {
        $ary = array( 
            'status' => $type 
        );
        if ( $paytime != '' ) $ary['paytime'] = time();
        if ( $pay != '' )
        {
            $ary['pay'] = 1;
            $sql = 'update ' . TABLE_PREFIX . 'tttuangou_product p left join ' . TABLE_PREFIX . 'tttuangou_order o on p.id=o.productid set p.totalnum = p.totalnum + 1 where o.orderid = ' . $id;
            $this->DatabaseHandler->Query($sql);
        }
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'tttuangou_order');
        $result = $this->DatabaseHandler->Update($ary, 'orderid=' . $id);
        return $result;
    }

    function orderPaylist( $productid )
    {
        $sql = 'select o.*,m.username,m.email,p.nowprice,p.successnum from ' . TABLE_PREFIX . 'tttuangou_order o left join ' . TABLE_PREFIX . 'system_members m on o.userid= m.uid left join ' . TABLE_PREFIX . 'tttuangou_product p on o.productid=p.id where o.productid = ' . intval($productid) . ' and o.pay = 1 and o.status = 1';
        $query = $this->DatabaseHandler->Query($sql);
        $orderPayed = $query->GetAll();
        return $orderPayed;
    }

        function listProductOrderPayed( $productid )
    {
        $sql = 'select o.*,m.username,m.email,p.nowprice,p.successnum from ' . TABLE_PREFIX . 'tttuangou_order o left join ' . TABLE_PREFIX . 'system_members m on o.userid= m.uid left join ' . TABLE_PREFIX . 'tttuangou_product p on o.productid=p.id where o.productid = ' . intval($productid) . ' and o.pay = 1';
        $query = $this->DatabaseHandler->Query($sql);
        $orderPayed = $query->GetAll();
        return $orderPayed;
    }

    function UpOrder()
    {
        $sql = 'update ' . TABLE_PREFIX . 'tttuangou_order o left join ' . TABLE_PREFIX . 'tttuangou_product p on o.productid = p.id set o.status=2 where ' . time() . ' > p.overtime and  o.pay = 0 and o.status = 1 and o.userid = ' . MEMBER_ID;
        $query = $this->DatabaseHandler->Query($sql);
        return true;
    }

    function GetTicket( $id, $seller = 0 )
    {
        if ( $seller == 0 )
        {
            $sql = 'select t.*,p.name,p.intro,p.nowprice,s.sellerurl,p.perioddate,s.selleraddress from ' . TABLE_PREFIX . 'tttuangou_ticket t LEFT JOIN ' . TABLE_PREFIX . 'tttuangou_product p on t.productid = p.id left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id where uid = ' . MEMBER_ID . ' and ticketid = ' . intval($id);
        }
        else
        {
            $sql = 'select t.*,p.name,p.intro,p.nowprice,s.sellerurl,p.perioddate,s.selleraddress from ' . TABLE_PREFIX . 'tttuangou_ticket t LEFT JOIN ' . TABLE_PREFIX . 'tttuangou_product p on t.productid = p.id left join ' . TABLE_PREFIX . 'tttuangou_seller s on p.sellerid=s.id where ticketid = ' . intval($id);
        }
        $query = dbc()->Query($sql);
        $ticket = $query->GetRow();
        return $ticket;
    }
}


class OrderLogic_cLog
{
    public $CSIGN = null;
    function add($action, $mark)
    {
        return dbc(DBCMax)->insert('order_clog')->data(array(
            'sign' => $this->CSIGN,
            'action' => $action,
            'uid' => user()->get('id'),
            'remark' => $mark,
            'time' => time()
        ))->done();
    }
    function del($id = null)
    {
        dbc(DBCMax)->delete('order_clog')->where('sign='.$this->CSIGN);
        if ($id !== null)
        {
            dbc(DBCMax)->where('id='.$id);
        }
        return dbc(DBCMax)->done();
    }
    function vlist($action = '1')
    {
        return
        dbc(DBCMax)
            ->select('order_clog')
            ->where('sign='.$this->CSIGN)
            ->where($action)
            ->order('id.desc')
        ->done();
    }
}
?>