<?php 
/*********************************************
 *[tttuangou] (C)2005 - 2010 Cenwor Inc.
 *
 * 快钱支付接口文件
 *
 * @author 狐狸<foxis@qq.com>
 * @package www.tttuangou.net
 **********************************************/

function payTo($payment,$returnurl,$pay){
	$payment=unserialize($pay['pay_config']);
	$merchant_acctid    = trim($payment['kuaiqian_account']);                 	$key                = trim($payment['kuaiqian_key']);
	$input_charset      = 1;                                                	$page_url           = $returnurl;
	$bg_url             = '';
	$version            = 'v2.0';
	$language           = 1;
	$sign_type          = 1;                                                	$payer_name         = '';
	$payer_contact_type = '';
	$payer_contact      = '';
	$order_id           = $pay['orderid'];                                    	$order_amount       = floatval($pay['price']) * 100;                        	$order_time         = date('YmdHis');            	$product_name       = '';
	$product_num        = '';
	$product_id         = '';
	$product_desc       = '';
	$ext1               = '';
	$ext2               = '';
	$pay_type           = '00';                                                	$bank_id            = '';
	$redo_flag          = '0';
	$pid                = '';
	
	
	$signmsgval = '';
	$signmsgval = append_param($signmsgval, "inputCharset", $input_charset);
	$signmsgval = append_param($signmsgval, "pageUrl", $page_url);
	$signmsgval = append_param($signmsgval, "bgUrl", $bg_url);
	$signmsgval = append_param($signmsgval, "version", $version);
	$signmsgval = append_param($signmsgval, "language", $language);
	$signmsgval = append_param($signmsgval, "signType", $sign_type);
	$signmsgval = append_param($signmsgval, "merchantAcctId", $merchant_acctid);
	$signmsgval = append_param($signmsgval, "payerName", $payer_name);
	$signmsgval = append_param($signmsgval, "payerContactType", $payer_contact_type);
	$signmsgval = append_param($signmsgval, "payerContact", $payer_contact);
	$signmsgval = append_param($signmsgval, "orderId", $order_id);
	$signmsgval = append_param($signmsgval, "orderAmount", $order_amount);
	$signmsgval = append_param($signmsgval, "orderTime", $order_time);
	$signmsgval = append_param($signmsgval, "productName", $product_name);
	$signmsgval = append_param($signmsgval, "productNum", $product_num);
	$signmsgval = append_param($signmsgval, "productId", $product_id);
	$signmsgval = append_param($signmsgval, "productDesc", $product_desc);
	$signmsgval = append_param($signmsgval, "ext1", $ext1);
	$signmsgval = append_param($signmsgval, "ext2", $ext2);
	$signmsgval = append_param($signmsgval, "payType", $pay_type);
	$signmsgval = append_param($signmsgval, "bankId", $bank_id);
	$signmsgval = append_param($signmsgval, "redoFlag", $redo_flag);
	$signmsgval = append_param($signmsgval, "pid", $pid);
	$signmsgval = append_param($signmsgval, "key", $key);
	$signmsg    = strtoupper(md5($signmsgval));    	
	
	$def_url  = '<div style="text-align:center"><form name="kqPay" style="text-align:center;" method="post" action="https:/'.'/www.99bill.com/gateway/recvMerchantInfoAction.htm" target="_blank">';
	$def_url .= "<input type='hidden' name='inputCharset' value='" . $input_charset . "' />";
	$def_url .= "<input type='hidden' name='bgUrl' value='" . $bg_url . "' />";
	$def_url .= "<input type='hidden' name='pageUrl' value='" . $page_url . "' />";
	$def_url .= "<input type='hidden' name='version' value='" . $version . "' />";
	$def_url .= "<input type='hidden' name='language' value='" . $language . "' />";
	$def_url .= "<input type='hidden' name='signType' value='" . $sign_type . "' />";
	$def_url .= "<input type='hidden' name='signMsg' value='" . $signmsg . "' />";
	$def_url .= "<input type='hidden' name='merchantAcctId' value='" . $merchant_acctid . "' />";
	$def_url .= "<input type='hidden' name='payerName' value='" . $payer_name . "' />";
	$def_url .= "<input type='hidden' name='payerContactType' value='" . $payer_contact_type . "' />";
	$def_url .= "<input type='hidden' name='payerContact' value='" . $payer_contact . "' />";
	$def_url .= "<input type='hidden' name='orderId' value='" . $order_id . "' />";
	$def_url .= "<input type='hidden' name='orderAmount' value='" . $order_amount . "' />";
	$def_url .= "<input type='hidden' name='orderTime' value='" . $order_time . "' />";
	$def_url .= "<input type='hidden' name='productName' value='" . $product_name . "' />";
	$def_url .= "<input type='hidden' name='payType' value='" . $pay_type . "' />";
	$def_url .= "<input type='hidden' name='productNum' value='" . $product_num . "' />";
	$def_url .= "<input type='hidden' name='productId' value='" . $product_id . "' />";
	$def_url .= "<input type='hidden' name='productDesc' value='" . $product_desc . "' />";
	$def_url .= "<input type='hidden' name='ext1' value='" . $ext1 . "' />";
	$def_url .= "<input type='hidden' name='ext2' value='" . $ext2 . "' />";
	$def_url .= "<input type='hidden' name='bankId' value='" . $bank_id . "' />";
	$def_url .= "<input type='hidden' name='redoFlag' value='" . $redo_flag ."' />";
	$def_url .= "<input type='hidden' name='pid' value='" . $pid . "' />";
	$def_url .= "<input type='submit' name='submit' value='立即支付' />";
	$def_url .= "</form></div></br>";
	
	return $def_url;
}

function payRe($userID){

	$get_merchant_acctid = trim($_REQUEST['merchantAcctId']);
	$pay_result          = trim($_REQUEST['payResult']);
	$version             = trim($_REQUEST['version']);
	$language            = trim($_REQUEST['language']);
	$sign_type           = trim($_REQUEST['signType']);
	$pay_type            = trim($_REQUEST['payType']);
	$bank_id             = trim($_REQUEST['bankId']);
	$order_id            = trim($_REQUEST['orderId']);
	$order_time          = trim($_REQUEST['orderTime']);
	$order_amount        = trim($_REQUEST['orderAmount']);
	$deal_id             = trim($_REQUEST['dealId']);
	$bank_deal_id        = trim($_REQUEST['bankDealId']);
	$deal_time           = trim($_REQUEST['dealTime']);
	$pay_amount          = trim($_REQUEST['payAmount']);
	$fee                 = trim($_REQUEST['fee']);
	$ext1                = trim($_REQUEST['ext1']);
	$ext2                = trim($_REQUEST['ext2']);
	$err_code            = trim($_REQUEST['errCode']);
	$sign_msg            = trim($_REQUEST['signMsg']);
	
	if ($pay_result!=10 && $pay_result!=00) 
	{
		return __('支付失败');
	}
	
	$error='';
    $DatabaseHandler = Obj::registry('DatabaseHandler');
    	$sql='select * from '.TABLE_PREFIX.'tttuangou_order where orderid  = '.addslashes($order_id).' and userid ='.$userID;
	$query = $DatabaseHandler->Query($sql);
	$order=$query->GetRow();
	if($order=='')return __("无法继续付款，无法找到订单！");
	if($order['pay']==1) return __("请勿重复使用已支付的订单！");
	
		$sql='select * from '.TABLE_PREFIX.'tttuangou_payment where pay_id   = '.$order['paytype'];
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	$payment=unserialize($pay['pay_config']);
	
    $merchant_acctid     = $payment['kuaiqian_account'];                     $key                 = $payment['kuaiqian_key'];		

		$merchant_signmsgval = '';
	$merchant_signmsgval = append_param($merchant_signmsgval,"merchantAcctId",$merchant_acctid);
	$merchant_signmsgval = append_param($merchant_signmsgval,"version",$version);
	$merchant_signmsgval = append_param($merchant_signmsgval,"language",$language);
	$merchant_signmsgval = append_param($merchant_signmsgval,"signType",$sign_type);
	$merchant_signmsgval = append_param($merchant_signmsgval,"payType",$pay_type);
	$merchant_signmsgval = append_param($merchant_signmsgval,"bankId",$bank_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"orderId",$order_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"orderTime",$order_time);
	$merchant_signmsgval = append_param($merchant_signmsgval,"orderAmount",$order_amount);
	$merchant_signmsgval = append_param($merchant_signmsgval,"dealId",$deal_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"bankDealId",$bank_deal_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"dealTime",$deal_time);
	$merchant_signmsgval = append_param($merchant_signmsgval,"payAmount",$pay_amount);
	$merchant_signmsgval = append_param($merchant_signmsgval,"fee",$fee);
	$merchant_signmsgval = append_param($merchant_signmsgval,"ext1",$ext1);
	$merchant_signmsgval = append_param($merchant_signmsgval,"ext2",$ext2);
	$merchant_signmsgval = append_param($merchant_signmsgval,"payResult",$pay_result);
	$merchant_signmsgval = append_param($merchant_signmsgval,"errCode",$err_code);
	$merchant_signmsgval = append_param($merchant_signmsgval,"key",$key);
	$merchant_signmsg    = md5($merchant_signmsgval);
	
		if ($get_merchant_acctid != $merchant_acctid)
	{
	    	    return __("商户号错误");
	}
	
	if (strtoupper($sign_msg) == strtoupper($merchant_signmsg))
	{
	    return ($ary=array('price'=> $order_amount/100 ,'orderid'=>$order_id));		
	}
	else
	{
	    	    return __('密钥校对错误');
	}
}

function addmymoney($userID){
	$error='';
	 
	$get_merchant_acctid = trim($_REQUEST['merchantAcctId']);
	$pay_result          = trim($_REQUEST['payResult']);
	$version             = trim($_REQUEST['version']);
	$language            = trim($_REQUEST['language']);
	$sign_type           = trim($_REQUEST['signType']);
	$pay_type            = trim($_REQUEST['payType']);
	$bank_id             = trim($_REQUEST['bankId']);
	$order_id            = trim($_REQUEST['orderId']);
	$order_time          = trim($_REQUEST['orderTime']);
	$order_amount        = trim($_REQUEST['orderAmount']);
	$deal_id             = trim($_REQUEST['dealId']);
	$bank_deal_id        = trim($_REQUEST['bankDealId']);
	$deal_time           = trim($_REQUEST['dealTime']);
	$pay_amount          = trim($_REQUEST['payAmount']);
	$fee                 = trim($_REQUEST['fee']);
	$ext1                = trim($_REQUEST['ext1']);
	$ext2                = trim($_REQUEST['ext2']);
	$err_code            = trim($_REQUEST['errCode']);
	$sign_msg            = trim($_REQUEST['signMsg']);
	
	if ($pay_result!=10 && $pay_result!=00) 
	{
		return __('支付失败');
	}
		
		$DatabaseHandler = Obj::registry('DatabaseHandler');
	$sql='select count(*) as `count` from '.TABLE_PREFIX.'tttuangou_addmoney where id=\''.addslashes($order_id).'\'';
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	if($pay['count']!=0)return __('您不能重复充值同一订单，充值失败！');
	$sql='select * from '.TABLE_PREFIX.'tttuangou_payment where pay_code   =\''.addslashes($_GET['pay']).'\'';
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	$payment=unserialize($pay['pay_config']);
	
    $merchant_acctid     = $payment['kuaiqian_account'];                     $key                 = $payment['kuaiqian_key'];		

		$merchant_signmsgval = '';
	$merchant_signmsgval = append_param($merchant_signmsgval,"merchantAcctId",$merchant_acctid);
	$merchant_signmsgval = append_param($merchant_signmsgval,"version",$version);
	$merchant_signmsgval = append_param($merchant_signmsgval,"language",$language);
	$merchant_signmsgval = append_param($merchant_signmsgval,"signType",$sign_type);
	$merchant_signmsgval = append_param($merchant_signmsgval,"payType",$pay_type);
	$merchant_signmsgval = append_param($merchant_signmsgval,"bankId",$bank_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"orderId",$order_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"orderTime",$order_time);
	$merchant_signmsgval = append_param($merchant_signmsgval,"orderAmount",$order_amount);
	$merchant_signmsgval = append_param($merchant_signmsgval,"dealId",$deal_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"bankDealId",$bank_deal_id);
	$merchant_signmsgval = append_param($merchant_signmsgval,"dealTime",$deal_time);
	$merchant_signmsgval = append_param($merchant_signmsgval,"payAmount",$pay_amount);
	$merchant_signmsgval = append_param($merchant_signmsgval,"fee",$fee);
	$merchant_signmsgval = append_param($merchant_signmsgval,"ext1",$ext1);
	$merchant_signmsgval = append_param($merchant_signmsgval,"ext2",$ext2);
	$merchant_signmsgval = append_param($merchant_signmsgval,"payResult",$pay_result);
	$merchant_signmsgval = append_param($merchant_signmsgval,"errCode",$err_code);
	$merchant_signmsgval = append_param($merchant_signmsgval,"key",$key);
	$merchant_signmsg    = md5($merchant_signmsgval);
	
		if ($get_merchant_acctid != $merchant_acctid)
	{
	    	    return __("商户号错误");
	}
	
	if (strtoupper($sign_msg) == strtoupper($merchant_signmsg))
	{
	    return ($ary=array('price'=> $order_amount/100 ,'orderid'=>$order_id));		
	}
	else
	{
	    	    return __('密钥校对错误');
	}
}



function append_param($strs,$key,$val)
{
    if($strs != "")
    {
        if($key != '' && $val != '')
        {
            $strs .= '&' . $key . '=' . $val;
        }
    }
    else
    {
        if($val != '')
        {
            $strs = $key . '=' . $val;
        }
    }
    return $strs;
}
?>