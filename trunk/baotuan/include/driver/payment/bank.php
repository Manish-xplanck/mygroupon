<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename bank.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class bankPaymentDriver extends PaymentDriver
{
	public function CreateLink($payment, $parameter)
	{
		$html =  '<p>'.$payment['config']['content'].'</p>';
		$html .= '<p><script type="text/javascript" src="'.ini('settings.site_url').'/?mod=callback&pid='.$payment['id'].'&sign='.$parameter['sign'].'"></script></p>';
		return $html;
	}
	public function CreateConfirmLink($payment, $order)
	{
		return '?mod=buy&code=tradeconfirm&id='.$order['orderid'];
	}
	public function CallbackVerify($payment)
	{
				if (user()->get('id') < 1)
		{
			return 'VERIFY_FAILED';
		}
		$trade = $this->GetTradeData();
		if ($trade['__order__']['paytype'] != $payment['id'])
		{
            return 'VERIFY_FAILED';
		}
		return 'WAIT_BUYER_PAY';
	}
	public function GetTradeData()
	{
		$sign = get('sign', 'number');
		$order = logic('order')->SrcOne($sign);
		$trade = array();
        $trade['sign'] = $sign;
        $trade['trade_no'] = time();
        $trade['price'] = $order['paymoney'];
        $trade['money'] = 0;
        $trade['status'] = 'WAIT_BUYER_PAY';
        $trade['__order__'] = $order;
        return $trade;
	}
	public function StatusProcesser($status)
	{
	    if ($status == 'VERIFY_FAILED')
	    {
	        echo 'document.write("支付验证失败！");';
	    }
	    elseif ($status == 'WAIT_BUYER_PAY')
	    {
	        echo 'document.write("订单已经提交，请尽快付款！");';
	    }
	    else
	    {
	        return false;
	    }
		return true;
	}
	public function GoodSender($payment, $express, $sign, $type)
	{
		if ($type == 'ticket')
		{
			logic('order')->Processed($sign, 'TRADE_FINISHED');
		}
		else
		{
			logic('order')->Processed($sign, 'WAIT_BUYER_CONFIRM_GOODS');
		}
	}
}

?>