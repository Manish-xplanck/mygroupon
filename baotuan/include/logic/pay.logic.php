<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename pay.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


 

class PayLogic
{
    
    public function html( $data )
    {
        switch (mocod())
        {
            case 'buy.order':
                $pay_money = $data['price_of_total'];
                $product_type = $data['product']['type'];
                include handler('template')->file('@html/pay_selector');
                break;
        }
    }
    
    public function GetOne($choose)
    {
        $sql_where = '0';
        if ( is_numeric($choose) )
        {
            $sql_where = 'id = '.$choose;
        }
        else
        {
            $sql_where = 'code = "'.$choose.'"';
        }
        $sql = '
        SELECT
            *
        FROM
            ' . table('payment') .'
        WHERE
            ' . $sql_where;
        return $this->__parse_payment(dbc()->Query($sql)->GetRow());
    }
    
    public function GetList()
    {
    	$sql_filter = '1';
    	    	$lsrc = handler('cookie')->GetVar('loginSource');
    	if ($lsrc && $lsrc == 'alipay')
    	{
    		$sql_filter = 'code="alipay"';
    	}
    	        $sql = '
        SELECT
            *
        FROM
            ' . table('payment') . '
        WHERE
            enabled = "true"
        AND
        	' . $sql_filter . '
        ORDER BY
            `order`
        ASC';
        return $this->__parse_payment(dbc()->Query($sql)->GetAll());
    }
    
    private function __parse_payment($data)
    {
        if ( ! $data ) return false;
        if ( is_array($data[0]) )
        {
            $return = array();
            foreach ( $data as $i => $one )
            {
                $result = $this->__parse_payment($one);
                if ($result)
                {
                    $return[] = $result;
                }
            }
            return $return;
        }
        $data['config'] = ($data['config'] == '') ? array() : unserialize($data['config']);
        return $data;
    }
    
    public function SrcOne($choose)
    {
        $sql_where = '0';
        if ( is_numeric($choose) )
        {
            $sql_where = 'id = '.$choose;
        }
        else
        {
            $sql_where = 'code = "'.$choose.'"';
        }
        $sql = '
        SELECT
            *
        FROM
            ' . table('payment') .'
        WHERE
            ' . $sql_where;
        return dbc()->Query($sql)->GetRow();
    }
    
    public function SrcList()
    {
        $sql = '
        SELECT
            *
        FROM
            ' . table('payment') . '
        ORDER BY
            `order`
        ASC';
        return dbc()->Query($sql)->GetAll();
    }
    
    public function Update($data, $where)
    {
        dbc()->SetTable(table('payment'));
        dbc()->Update($data, $where);
    }
    
    private function __GetAPI($code)
    {
        $SID = 'payment.driver.api.'.$code;
        $api = moSpace($SID);
        if (!$api)
        {
            $api = moSpace($SID, driver('payment')->load($code));
        }
        return $api;
    }
    
    public function Linker($payment, $parameter)
    {
        $api = $this->__GetAPI($payment['code']);
        $linker = $api->CreateLink($payment, $parameter);
        $log_data = array(
            'type' => $payment['id'],
            'sign' => $parameter['sign'],
            'money' => $parameter['price']
        );
        $this->__LogCreate($log_data);
        logic('order')->Processed($parameter['sign'], 'WAIT_BUYER_PAY');
        return $linker;
    }
    
    public function ConfirmLinker($order)
    {
        $payment = $this->GetOne($order['paytype']);
        $api = $this->__GetAPI($payment['code']);
        return $api->CreateConfirmLink($payment, $order);
    }
    
    public function Verify($payment)
    {
        $api = $this->__GetAPI($payment['code']);
        
        $status = $api->CallbackVerify($payment);
        $trade = $this->TradeData($payment);
        $this->__LogUpdate($trade['sign'], $trade['trade_no'], $status);
        return $status;
    }
    
    public function TradeData($payment)
    {
        $api = $this->__GetAPI($payment['code']);
        return $api->GetTradeData();
    }
    
    public function Process($payment, $status)
    {
        $api = $this->__GetAPI($payment['code']);
        return $api->StatusProcesser($status);
    }
    
    public function SendGoods($order, $ignore_trade_no = false)
    {
        $payment = $this->GetOne($order['paytype']);
        $api = $this->__GetAPI($payment['code']);
        $paylog = $this->GetLog($order['orderid'], 0, '1', true);
        $trade_no = $paylog['trade_no'];
        if (!$ignore_trade_no && !is_numeric($trade_no))
        {
                        return;
        }
        if ($order['product']['type'] == 'ticket')
        {
            $name = __('虚拟团购');
            $invoice = sprintf(__('订单号：%s'), $order['orderid']);
        }
        else
        {
            $expressChoose = logic('express')->SrcOne($order['expresstype']);
            $name = $expressChoose['name'];
            $invoice = $order['invoice'];
        }
        $express = array(
            'trade_no' => $trade_no,
            'name' => $name,
            'invoice' => $invoice
        );
        $api->GoodSender($payment, $express, $order['orderid'], $order['product']['type']);
        logic('notify')->Call($order['userid'], 'logic.pay.SendGoods', $express);
    }
    
    public function TD2UID($payment)
    {
        $trade = logic('pay')->TradeData($payment);
        $order = logic('order')->SrcOne($trade['sign']);
        $uid = $order['userid'];
        return $uid;
    }
    
    public function GetLog($sign, $uid = 0, $where = '1', $getOne = false)
    {
        $sql_limit_user = '1';
        if ($uid > 0)
        {
            $sql_limit_user = 'uid = '.$uid;
        }
        $sql = '
        SELECT
        	*
        FROM
        	'.table('paylog').'
        WHERE
        	sign = "'.$sign.'"
        AND
        	'.$sql_limit_user.'
        AND
        	'.$where.'
        ORDER BY
        	id
        DESC';
        if ($getOne)
        {
            return dbc()->Query($sql)->GetRow();
        }
        else
        {
            return dbc()->Query($sql)->GetAll();
        }
    }
    
    private function __LogCreate($data)
    {
        $data['uid'] = user()->get('id');
        $log = $this->GetLog($data['sign'], $data['uid']);
        if (!$log)
        {
            $data['time'] = time();
            $data['trade_no'] = '__NULL__';
            $data['status'] = '__CREATE__';
            dbc()->SetTable(table('paylog'));
            dbc()->Insert($data);
        }
    }
    
    private function __LogUpdate($sign, $trade_no, $status)
    {
        if (trim($sign) == '' || trim($status) == '') return;
        $uid = user()->get('id');
        $log = $this->GetLog($sign, $uid, 'status="'.$status.'"');
        if ($log) return;
        $log = $this->GetLog($sign, $uid, '1', true);
        unset($log['id']);
        $log['time'] = time();
        $log['trade_no'] = $trade_no;
        $log['status'] = $status;
        dbc()->SetTable(table('paylog'));
        dbc()->Insert($log);
    }
    
    public function misc()
    {
        $SID = 'logic.pay.misc';
        $obj = moSpace($SID);
        if ( ! $obj )
        {
            $obj = moSpace($SID, (new PayLogic_Misc()));
        }
        return $obj;
    }
}


class PayLogic_Misc
{
    public function ID2Name($flag)
    {
        $payment = logic('pay')->SrcOne($flag);
        if ($payment)
        {
            return $payment['name'];
        }
        else
        {
            return __('未识别');
        }
    }
    public function TradeNO($oid)
    {
        $log = logic('pay')->GetLog($oid, 0, '1', true);
        if ($log['trade_no'] == '__NULL__')
        {
            return __('还未支付');
        }
        else
        {
            return $log['trade_no'];
        }
    }
}
?>