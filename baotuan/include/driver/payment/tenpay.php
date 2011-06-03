<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename tenpay.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class tenpayPaymentDriver extends PaymentDriver
{
    
    private $Gateway_com = 'https://www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi';
    
    private $is_notify = null;
    
    public function CreateLink($payment, $parameter)
    {
        
        $post = array(
            'bargainor_id' => $payment['config']['bargainor'],
            'date' => date('Ymd'),
            'sp_billno' => $parameter['sign'],
            'transaction_id' => $payment['config']['bargainor'].str_pad($parameter['sign'], 18, 0, STR_PAD_LEFT),
            'desc' => $parameter['name'],
            'total_fee' => $parameter['price'] * 100,
            'return_url' => $parameter['notify_url']
        );
        return $this->__BuildForm($payment, $post);
    }
    
    public function CreateConfirmLink($payment, $order)
    {
        return '?mod=buy&code=tradeconfirm&id='.$order['orderid'];
    }
    
    public function CallbackVerify($payment)
    {
        $trade_status = $this->__Verify($payment);
        return $trade_status;
    }
    
    public function GetTradeData()
    {
        $src = 'GET';
        $trade = array();
        $trade['sign'] = logic('safe')->Vars($src, 'sp_billno', 'number');
        $trade['trade_no'] = logic('safe')->Vars($src, 'transaction_id', 'number');
        $trade['price'] = logic('safe')->Vars($src, 'total_fee', 'float') / 100;
        $trade['money'] = $trade['price'];
        $TMP_status = logic('safe')->Vars($src, 'pay_result', 'int');
        $trade['status'] = $TMP_status ? 'VERIFY_FAILED' : 'TRADE_FINISHED';
        return $trade;
    }
    
    public function StatusProcesser($status)
    {
        if (!$this->__Is_Nofity())
        {
            return false;
        }
        if ($status != 'VERIFY_FAILED')
        {
            $url = ini('settings.site_url').'/?mod=me&code=order';
            $this->__showURL($url);
        }
        else
        {
            echo 'failed';
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
        return;
    }
	
    private function __Is_Nofity()
    {
        if (is_null($this->is_notify))
        {
            if (count($_COOKIE) == 0)
            {
                $this->is_notify = true;
            }
            else
            {
                $this->is_notify = false;
            }
        }
        return $this->is_notify;
    }
    
    private function __BuildForm($payment, $parameter)
    {
        $this->__INIT_Post($parameter);
        $parameter['sign'] = $this->__CreateSign($payment, $parameter);
        $url = $this->Gateway_com;
	    $sHtml = '<form id="tenpaysubmit" name="tenpaysubmit" action="'.$url.'" method="get" target="_blank">';
        foreach ($parameter as $key => $val)
        {
            $sHtml.= '<input type="hidden" name="'.$key.'" value="'.$val.'"/>';
        }
        $sHtml .= '<input type="submit" value="跳到财付通去付款" onclick="javascript:$.hook.call(\'pay.button.click\');" >';
        $sHtml .= '</form>';
        return $sHtml;
    }
    
    private function __Verify($payment)
    {
		$sign_generate = $this->__CreateSign($payment, $_GET);
        $sign_response = get('sign', 'txt');
        if ($sign_response != $sign_generate)
        {
            return 'VERIFY_FAILED';
        }
        $TMP_status = logic('safe')->Vars('GET', 'pay_result', 'int');
        return $TMP_status ? 'VERIFY_FAILED' : 'TRADE_FINISHED';
    }
    private function __INIT_Post(&$post)
    {
        $post += array(
            'cmdno'             => 1,
            'bank_type'         => 0,
            'purchaser_id'      => '',
            'fee_type'          => 1,
            'attach'            => '',
			'spbill_create_ip'  => $_SERVER['REMOTE_ADDR'],
        	'sys_id'            => '542554970',
            'sp_suggestuser'    => '1202822001',
            'cs'                => ini('settings.charset')
        );
    }
    
    private function __CreateSign($payment, $parameter)
    {
        $string =
        	"cmdno=" . $parameter['cmdno'] .
            (
        	    ($parameter['pay_result'] != '')
        	        ? "&pay_result=".$parameter['pay_result']
        	        : ""
            ) .
        	"&date=" . $parameter['date'] .
            (
        	    ($parameter['pay_info'] == '')
        	        ? "&bargainor_id=".$parameter['bargainor_id']
        	        : ""
            ) .
        	"&transaction_id=" . $parameter['transaction_id'] .
        	"&sp_billno=" . $parameter['sp_billno'] .
        	"&total_fee=" . $parameter['total_fee'] . 
        	"&fee_type=" . $parameter['fee_type'] . 
        	(
        	    ($parameter['return_url'] != '')
        	        ? "&return_url=".$parameter['return_url']
        	        : ""
            ) .
        	"&attach=" . $parameter['attach'] .
        	(
        	    ($parameter['spbill_create_ip'] != '')
        	        ? "&spbill_create_ip=".$parameter['spbill_create_ip']
        	        : ""
            ) .
        	"&key=" . $payment['config']['key'];
        $sign = strtoupper(md5($string));
        return $sign;
    }
    private function __showURL($url)
    {
		$sHtml = "<html><head>\r\n" .
			"<meta name=\"TENCENT_ONLINE_PAYMENT\" content=\"China TENCENT\">" .
			"<script language=\"javascript\">\r\n" .
				"window.location.href='" . $url . "';\r\n" .
			"</script>\r\n" .
			"</head><body></body></html>";
		echo $sHtml;
	}
}

?>