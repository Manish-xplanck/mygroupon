<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename cod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class codPaymentDriver extends PaymentDriver
{
	public function CreateLink($payment, $parameter)
	{
		$html =  '<form action="?mod=callback&pid='.$payment['id'].'" method="post">';
		$html .= '<input type="hidden" name="sign" value="'.$parameter['sign'].'" />';
		$html .= '<p>请确认以上订单信息</p>';
		$html .= '<input type="submit" value=" 下 单 " />';
		$html .= '</form>';
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
		return 'WAIT_SELLER_SEND_GOODS';
	}
	public function GetTradeData()
	{
		$sign = post('sign', 'number');
		$order = logic('order')->SrcOne($sign);
		$trade = array();
        $trade['sign'] = $sign;
        $trade['trade_no'] = time();
        $trade['price'] = $order['paymoney'];
        $trade['money'] = 0;
        $trade['status'] = 'TRADE_FINISHED';
        $trade['__order__'] = $order;
        return $trade;
	}
	public function StatusProcesser($status)
	{
		return false;
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