<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename alipay.pay.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 
 


function payTo($payment,$returnurl,$pay){
		$charset = 'utf-8';
        $service = 'create_direct_pay_by_user';
        $agent = 'C4335319945672464113';

        $parameter = array(
            'agent'             => $agent,
            'service'           => $service,
            'partner'           => $payment['alipay_partner'],
            '_input_charset'    => $charset,            'notify_url'        => "$returnurl"."&uid=".MEMBER_ID,             'return_url'        => "$returnurl",             
            'subject'           => $pay['name'],            'out_trade_no'      => $pay['orderid'],            'price'             => $pay['price'],			'body'				=> $pay['name'],
            'quantity'          => 1,
            'payment_type'      => 1,
            
            'logistics_type'    => 'EXPRESS',
            'logistics_fee'     => 0,
            'logistics_payment' => 'SELLER_PAY',
            
            'seller_email'      => $payment['alipay_account']
        );
        if ($payment['alipay_iftype'] == 'distrust')
        {
        	        	$parameter['service'] = 'create_partner_trade_by_buyer';
	        $parameter["receive_name"] = '天天团购系统';
	        $parameter["receive_address"] = '请先确认收货，之后系统会自动为您开通订单！';
	        $parameter["receive_zip"] = '310000';
	        $parameter["receive_phone"] = '0571-6666666';
	        $parameter["receive_mobile"] = '18666666666';
	        $parameter["show_url"] = $pay['show_url'];
        }
        ksort($parameter);
        reset($parameter);
        $param = '';
        $sign  = '';
        foreach ($parameter AS $key => $val){
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        };
        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment['alipay_key'];
        $button = ' <input type="submit" class="formbutton" onclick="window.open(\'https:/'.'/www.alipay.com/cooperate/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5\')" value="立即支付">';
		return $button;
}

function sendGoods($trade_no, $payment)
{
		$charset = 'utf-8';
        $service = 'send_goods_confirm_by_platform';

        $parameter = array(
            'service'           => $service,
            'partner'           => $payment['alipay_partner'],
            '_input_charset'    => $charset,            
        	'trade_no'			=> $trade_no,
            'transport_type'    => 'EXPRESS',
            'logistics_name'    => '虚拟发货',
            'invoice_no'		=> '00000000000000000',
        );
        ksort($parameter);
        reset($parameter);
        $param = '';
        $sign  = '';
        foreach ($parameter AS $key => $val){
            $param .= "$key=" .urlencode($val). "&";
            $sign  .= "$key=$val&";
        };
        $param = substr($param, 0, -1);
        $sign  = substr($sign, 0, -1). $payment['alipay_key'];
        $url = 'https:/'.'/www.alipay.com/cooperate/gateway.do?'.$param. '&sign='.md5($sign).'&sign_type=MD5';
		return $url;
}

function payRe($userID){
	$error='';
	if (!empty($_POST)){
		   foreach($_POST as $key => $data){
		   $_GET[$key] = $data;
		   };
		};
	$DatabaseHandler = Obj::registry('DatabaseHandler');
		$sql='select * from '.TABLE_PREFIX.'tttuangou_order where orderid  = '.addslashes($_GET['out_trade_no']).' and userid ='.addslashes($userID);
	$query = $DatabaseHandler->Query($sql);
	$order=$query->GetRow();
	if($order=='') return "无法继续付款，无法找到订单!";
	if($order['pay']==1) return "请勿重复使用已支付的订单!";
		$sql='select * from '.TABLE_PREFIX.'tttuangou_payment where pay_code   =\''.addslashes($_GET['pay']).'\'';
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	$payment=unserialize($pay['pay_config']);
	
	if ($_GET['uid'] == '')
	{
				$vfpass = ali_verify_return($payment['alipay_key']);
	}
	else
	{
				$vfpass = ali_verify_notify($payment['alipay_key'], $payment['alipay_partner']);
	}
	if (!$vfpass)
	{
		return "支付失败!<br/>";
	}
		return $ary=array('price'=> $_GET['total_fee'] ,'orderid'=>$_GET['out_trade_no']);
}

function addmymoney($userID){
	if (!empty($_POST)){
		foreach($_POST as $key => $data){
		   $_GET[$key] = $data;
		};
	};
		$DatabaseHandler = Obj::registry('DatabaseHandler');
	$sql='select * from '.TABLE_PREFIX.'tttuangou_addmoney where id ='.addslashes($_GET['out_trade_no']);
	$query = $DatabaseHandler->Query($sql);
	$search = $query->GetRow();
	if($search) return '您不能重复充值同一订单，充值失败！';
	$sql='select * from '.TABLE_PREFIX.'tttuangou_payment where pay_code   =\''.addslashes($_GET['pay']).'\'';
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	$payment=unserialize($pay['pay_config']);
	
	if ($_GET['uid'] == '')
	{
				$vfpass = ali_verify_return($payment['alipay_key']);
	}
	else
	{
				$vfpass = ali_verify_notify($payment['alipay_key'], $payment['alipay_partner']);
	}
	if (!$vfpass){
	     return "支付失败！<br/>";
	}
	return $ary=array('price'=> $_GET['total_fee'] ,'orderid'=>$_GET['out_trade_no']);
}

function ali_verify_return($sCode)
{
	$sort_get= ali_arg_sort($_GET);
	while (list ($key, $val) = each ($sort_get)) {
		if($key != "sign" && $key != "sign_type" && $key != 'code' && $key != 'mod' && $key != 'pay')
		$arg.=$key."=".$val."&";
	}
	$prestr = substr($arg,0,count($arg)-2); 	$mysign = ali_sign($prestr.$sCode);
	if ($mysign == $_GET["sign"]) return true;
	else return false;
}

function ali_verify_notify($sCode, $pid)
{
	$transport = 'http';
	if($transport == "https")
	{
		$gateway = "https:/"."/www.alipay.com/cooperate/gateway.do?";
		$veryfy_url = $gateway. "service=notify_verify"."&partner=".$pid."&notify_id=".$_POST["notify_id"];
	} else {
		$gateway = "http:/"."/notify.alipay.com/trade/notify_query.do?";
		$veryfy_url = $gateway. "partner=".$pid."&notify_id=".$_POST["notify_id"];
	}
	$veryfy_result  = ali_get_verify($veryfy_url);
	$post           = ali_para_filter($_POST);
	$sort_post      = ali_arg_sort($post);
	while (list ($key, $val) = each ($sort_post)) {
		$arg.=$key."=".$val."&";
	}
	$prestr = substr($arg,0,count($arg)-2);  	$mysign = ali_sign($prestr.$sCode);
	if (eregi("true$",$veryfy_result) && $mysign == $_POST["sign"])
	{
		return true;
	} else return false;
}

function ali_arg_sort($array) {
	ksort($array);
	reset($array);
	return $array;

}

function ali_sign($prestr)
{
	$sign='';
	$sign = md5($prestr);
	return $sign;
}

function ali_get_verify($url,$time_out = "60")
{
	$urlarr     = parse_url($url);
	$errno      = "";
	$errstr     = "";
	$transports = "";
	if($urlarr["scheme"] == "https") {
		$transports = "ssl:/"."/";
		$urlarr["port"] = "443";
	} else {
		$transports = "tcp:/"."/";
		$urlarr["port"] = "80";
	}
	$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
	if(!$fp) {
		die("ERROR: $errno - $errstr<br />\n");
	} else {
		fputs($fp, "POST ".$urlarr["path"]." HTTP/1.1\r\n");
		fputs($fp, "Host: ".$urlarr["host"]."\r\n");
		fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
		fputs($fp, "Content-length: ".strlen($urlarr["query"])."\r\n");
		fputs($fp, "Connection: close\r\n\r\n");
		fputs($fp, $urlarr["query"] . "\r\n\r\n");
		while(!feof($fp)) {
			$info[]=@fgets($fp, 1024);
		}
		fclose($fp);
		$info = implode(",",$info);
		while (list ($key, $val) = each ($_POST)) {
			$arg.=$key."=".$val."&";
		}
		return $info;
	}
}

function ali_para_filter($parameter) {
	$para = array();
	while (list ($key, $val) = each ($parameter)) {
		if($key == "sign" || $key == "sign_type" || $key == 'code' || $key == 'mod' || $key == 'pay' || $key == 'uid' || $val == "")
		{
			continue;
		}
		else
		{
			$para[$key] = $parameter[$key];
		}
	}
	return $para;
}
?>