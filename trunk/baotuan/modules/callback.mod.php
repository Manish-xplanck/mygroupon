<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename callback.mod.php $
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
        $payid = get('pid', 'int');
        if (!$payid) $payid = post('pid', 'int');
        if (!$payid)
        {
            exit('.0 0.');
        }
        
        $payment = logic('pay')->GetOne($payid);
        $status = logic('pay')->Verify($payment);
        if (preg_match('/[a-z_]/i', $status))
        {
            $code = 'Parse_'.$status;
            if (method_exists($this, $code))
            {
                $this->$code($payment);
            }
            else
            {
            	exit('.0 0.');
            }
        }
    }
    
    function Parse_VERIFY_FAILED($payment = false)
    {
        if (!$payment) return false;
                        if (logic('pay')->Process($payment, 'VERIFY_FAILED')) return;
        $this->Messager(__('支付验证失败！'), '?mod=me&code=order');
    }
    
    function Parse_WAIT_BUYER_PAY($payment = false)
    {
        if (!$payment) return false;
                        if(logic('pay')->Process($payment, 'WAIT_BUYER_PAY')) return;
        $this->Messager(__('订单已经生成，请尽快付款！'), '?mod=me&code=order');
    }
    
    function Parse_WAIT_SELLER_SEND_GOODS($payment = false)
    {
        if (!$payment) return false;
                $trade = logic('pay')->TradeData($payment);
        $order = logic('order')->_TMP_Payed($trade);
        if ($order['product']['type'] == 'ticket')
        {
                        logic('pay')->SendGoods($order);
            if (true == ini('payment.trade.sendgoodsfirst'))
            {
                                logic('order')->MakeSuccessed($trade['sign']);
                logic('order')->Processed($trade['sign'], 'WAIT_BUYER_CONFIRM_GOODS');
            }
            else
            {
                                notify(logic('pay')->TD2UID($payment), 'user.pay.confirm', $trade);
            }
        }
                if(logic('pay')->Process($payment, 'WAIT_SELLER_SEND_GOODS')) return;
        $this->Messager(__('我们已经收到付款，会尽快为您准备发货！'), '?mod=me&code=order');
    }
    
    function Parse_WAIT_BUYER_CONFIRM_GOODS($payment = false)
    {
        if (!$payment) return false;
                $trade = logic('pay')->TradeData($payment);
        logic('order')->Processed($trade['sign'], 'WAIT_BUYER_CONFIRM_GOODS');
                if(logic('pay')->Process($payment, 'WAIT_BUYER_CONFIRM_GOODS')) return;
        $this->Messager(__('我们已经发货完毕，请收到货物后尽快确认收货！'), '?mod=me&code=order');
    }
    
    function Parse_TRADE_FINISHED($payment = false)
    {
        if (!$payment) return false;
                $trade = logic('pay')->TradeData($payment);
        logic('order')->_TMP_Payed($trade);
        logic('order')->MakeSuccessed($trade['sign']);
                if(logic('pay')->Process($payment, 'TRADE_FINISHED')) return;
        $this->Messager(__('本次交易已经完成！'), '?mod=me&code=order');
    }
}

?>