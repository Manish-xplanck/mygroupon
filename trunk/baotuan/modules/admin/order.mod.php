<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename order.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    function Main()
    {
        header('Location: ?mod=order&code=vlist');
    }
    function vList()
    {
        $list = logic('order')->GetList();
        include handler('template')->file('@admin/order_list');
    }
    function Process()
    {
        $referrer = get('referrer', 'txt');
        $id = get('id', 'number');
        $order = logic('order')->GetOne($id);
        if (!$order)
        {
            $this->Messager(__('找不到相关订单！'), '?mod=order');
        }
        $user = user($order['userid'])->get();
        $payment = logic('pay')->SrcOne($order['paytype']);
        $paylog = logic('pay')->GetLog($order['orderid'], $order['userid']);
        $coupons = logic('coupon')->SrcList($order['userid'], $order['orderid']);
        $express = logic('express')->SrcOne($order['expresstype']);
        $address = logic('address')->GetOne($order['addressid']);
        $clog = logic('order')->clog($order['orderid'])->vlist();
        include handler('template')->file('@admin/order_process');
    }
    function Remark()
    {
        $id = get('oid', 'number');
        $text = get('text', 'txt');
        logic('order')->Update($id, array('remark'=>$text));
        exit('ok');
    }
    function Refund()
    {
        $id = get('oid', 'number');
        $remark = '[退款] '.get('mark', 'txt');
        logic('order')->clog($id)->add('refund', $remark);
        logic('order')->Refund($id);
        exit('ok');
    }
    function Confirm()
    {
        $id = get('oid', 'number');
        $remark = '[确认订单] '.get('mark', 'txt');
        logic('order')->clog($id)->add('confirm', $remark);
        logic('order')->Confirm($id);
        exit('ok');
    }
    function Cancel()
    {
        $id = get('oid', 'number');
        $remark = '[取消订单] '.get('mark', 'txt');
        logic('order')->clog($id)->add('cancel', $remark);
        logic('order')->Cancel($id);
        exit('ok');
    }
    function AfService()
    {
        $id = get('oid', 'number');
        $mark = get('mark', 'txt');
        $remark = '[售后] '.$mark;
        logic('order')->clog($id)->add('afservice', $remark);
        $order = logic('order')->SrcOne($id);
        logic('notify')->Call($order['userid'], 'admin.mod.order.AfService', array('orderid'=>$id,'remark'=>$mark));
        exit('ok');
    }
    function Ends()
    {
        $id = get('oid', 'number');
        $mark = get('mark', 'txt');
        $remark = '[结单] '.$mark;
        logic('order')->clog($id)->add('ends', $remark);
        logic('order')->Update($id, array('process'=>'TRADE_FINISHED'));
        exit('ok');
    }
    function Delete()
    {
        $id = get('oid', 'number');
        logic('order')->Delete($id);
        exit('ok');
    }
}


?>