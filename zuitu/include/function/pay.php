<?php
/* payment: alipay */
function pay_team_alipay($total_money, $order) {
	global $INI; if($total_money<=0||!$order) return null;
	$team = Table::Fetch('team', $order['team_id']);
	$order_id = $order['id'];
	$pay_id = $order['pay_id'];
	$guarantee = strtoupper($INI['alipay']['guarantee'])=='Y';

	/* param */
	$_input_charset = 'utf-8';
	$service = $guarantee ? 'create_partner_trade_by_buyer' : 'create_direct_pay_by_user';
	$partner = $INI['alipay']['mid'];
	$security_code = $INI['alipay']['sec'];
	$seller_email = $INI['alipay']['acc'];
	$itbpay = strval($INI['alipay']['itbpay']);

	$sign_type = 'MD5';
	$out_trade_no = $pay_id;

	$return_url = $INI['system']['wwwprefix'] . '/order/alipay/return.php';
	$notify_url = $INI['system']['wwwprefix'] . '/order/alipay/notify.php';
	$show_url = $INI['system']['wwwprefix'] . "/team.php?id={$team['id']}";
	$show_url = obscure_rep($show_url);

	$subject = mb_substr(strip_tags($team['title']),0,128,'UTF-8');
	$body = $show_url;
	$quantity = $order['quantity'];

	$parameter = array(
			"service"         => $service,
			"partner"         => $partner,      
			"return_url"      => $return_url,  
			"notify_url"      => $notify_url, 
			"_input_charset"  => $_input_charset, 
			"subject"         => $subject,  	 
			"body"            => $body,     	
			"out_trade_no"    => $out_trade_no,
			"payment_type"    => "1",
			"show_url"        => $show_url,
			"seller_email"    => $seller_email,  
			);

	if ($guarantee) {
		$parameter['price'] = $total_money;
		$parameter['quantity'] = 1;
		$parameter['logistics_fee'] = '0.00';
		$parameter['logistics_type'] = 'EXPRESS';
		$parameter['logistics_payment'] = 'SELLER_PAY';
	} else {
		$parameter["total_fee"] = $total_money;
	}
        if(!empty($_SESSION['ali_token'])) $parameter['token'] = $_SESSION['ali_token'];
	if ($itbpay) $parameter['it_b_pay'] = $itbpay;
	$alipay = new AlipayService($parameter, $security_code, $sign_type);
	$sign = $alipay->Get_Sign();
	$reqUrl = $alipay->create_url();

	return render('block_pay_alipay', array(
				'order_id' => $order_id,
				'reqUrl' => $reqUrl,
				));
}

function pay_charge_alipay($total_money, $charge_id, $title) {
	global $INI; if($total_money<=0||!$title) return null;
	$order_id = 'charge';

	/* param */
	$_input_charset = 'utf-8';
	$service = 'create_direct_pay_by_user';
	$partner = $INI['alipay']['mid'];
	$security_code = $INI['alipay']['sec'];
	$seller_email = $INI['alipay']['acc'];
	$itbpay = strval($INI['alipay']['itbpay']);

	$sign_type = 'MD5';
	$out_trade_no = $charge_id;

	$return_url = $INI['system']['wwwprefix'] . '/order/alipay/return.php';
	$notify_url = $INI['system']['wwwprefix'] . '/order/alipay/notify.php';
	$show_url = $INI['system']['wwwprefix'] . "/credit/index.php";

	$subject = $title;
	$body = $show_url;
	$quantity = 1;

	$parameter = array(
			"service"         => $service,
			"partner"         => $partner,      
			"return_url"      => $return_url,  
			"notify_url"      => $notify_url, 
			"_input_charset"  => $_input_charset, 
			"subject"         => $subject,  	 
			"body"            => $body,     	
			"out_trade_no"    => $out_trade_no,
			"total_fee"       => $total_money,  
			"payment_type"    => "1",
			"show_url"        => $show_url,
			"seller_email"    => $seller_email,  
			);
        if(!empty($_SESSION['ali_token'])) $parameter['token'] = $_SESSION['ali_token'];
	if ($itbpay) $parameter['it_b_pay'] = $itbpay;
	$alipay = new AlipayService($parameter, $security_code, $sign_type);
	$sign = $alipay->Get_Sign();
	$reqUrl = $alipay->create_url();

	return render('block_pay_alipay', array(
				'order_id' => $order_id,
				'reqUrl' => $reqUrl,
				));
}

/* payment: tenpay */
function pay_team_tenpay($total_money, $order) {
	global $INI; if($total_money<=0||!$order) return null;
	$team = Table::Fetch('team', $order['team_id']);
	$order_id = $order['id'];

	$v_mid = $INI['tenpay']['mid'];
	$v_url = $INI['system']['wwwprefix']. '/order/tenpay/return.php';
	$key   = $INI['tenpay']['sec'];
	$v_oid = $order['pay_id'];
	$v_amount = strval($total_money * 100);
	$v_moneytype = $INI['system']['currencyname'];
	$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;

	/* must */
	$sp_billno = $v_oid;
	$transaction_id = $v_mid. date('Ymd'). date('His') .rand(1000,9999);
	$desc = mb_convert_encoding($team['title'], 'GBK', 'UTF-8');
	/* end */

	$reqHandler = new PayRequestHandler();
	$reqHandler->init();
	$reqHandler->setKey($key);
	$reqHandler->setParameter("bargainor_id", $v_mid);
	$reqHandler->setParameter("cs", "GBK");
	$reqHandler->setParameter("sp_billno", $sp_billno);
	$reqHandler->setParameter("transaction_id", $transaction_id);
	$reqHandler->setParameter("total_fee", $v_amount);
	$reqHandler->setParameter("return_url", $v_url);
	$reqHandler->setParameter("desc", $desc);
	$reqHandler->setParameter("spbill_create_ip", Utility::GetRemoteIp());
	$reqUrl = $reqHandler->getRequestURL();

	if(is_post()&&$_POST['paytype']!='tenpay') {
		$reqHandler->setParameter('bank_type', pay_getqqbank($_POST['paytype']));
		$reqUrl = $reqHandler->getRequestURL();
		redirect( $reqUrl );
	}

	return render('block_pay_tenpay', array(
				'order_id' => $order_id,
				'reqUrl' => $reqUrl,
				));
}

function pay_charge_tenpay($total_money, $charge_id, $title) {
	global $INI; if($total_money<=0||!$title) return null;
	$order_id = 'charge';

	$v_mid = $INI['tenpay']['mid'];
	$v_url = $INI['system']['wwwprefix']. '/order/tenpay/return.php';
	$key   = $INI['tenpay']['sec'];
	$v_oid = $charge_id;
	$v_amount = strval($total_money * 100);
	$v_moneytype = $INI['system']['currencyname'];
	$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;

	/* must */
	$sp_billno = $v_oid;
	$transaction_id = $v_mid. date('Ymd'). date('His') .rand(1000,9999);
	$desc = mb_convert_encoding($title, 'GBK', 'UTF-8');
	/* end */

	$reqHandler = new PayRequestHandler();
	$reqHandler->init();
	$reqHandler->setKey($key);
	$reqHandler->setParameter("bargainor_id", $v_mid);
	$reqHandler->setParameter("cs", "GBK");
	$reqHandler->setParameter("sp_billno", $sp_billno);
	$reqHandler->setParameter("transaction_id", $transaction_id);
	$reqHandler->setParameter("total_fee", $v_amount);
	$reqHandler->setParameter("return_url", $v_url);
	$reqHandler->setParameter("desc", $desc);
	$reqHandler->setParameter("spbill_create_ip", Utility::GetRemoteIp());
	$reqUrl = $reqHandler->getRequestURL();

	if(is_post()&&$_POST['paytype']!='tenpay') {
		$reqHandler->setParameter('bank_type', pay_getqqbank($_POST['paytype']));
		$reqUrl = $reqHandler->getRequestURL();
		redirect( $reqUrl );
	}

	return render('block_pay_tenpay', array(
				'order_id' => $order_id,
				'reqUrl' => $reqUrl,
				));
}
/* payment: sdopay */
function pay_team_sdopay($total_money, $order) {
	global $INI; if($total_money<=0||!$order) return null;
 	$team = Table::Fetch('team', $order['team_id']);
        $version = '3.0';
	$order_id = $order['id'];
	$merid = $INI['sdopay']['mid'];  
        //密钥
	$security_code = $INI['sdopay']['sec'];
        //支付渠道
        $paychannel='14';
        $sign_type = 'MD5';
        //交易号
	$orderid = $order['pay_id'];
        //echo $orderid;
        //exit;
        //返回地址
	$return_url = $INI['system']['wwwprefix'] . '/order/sdopay/return.php';
	//服务器终端发货通知地址
        $notify_url = $INI['system']['wwwprefix'] . '/order/sdopay/notify.php';
	
        $ordertime = date("YmdHis");
        $curtype="RMB";//货币类型，目前仅支持"RMB"
        $notifytype="http";//发货通知方式：http,https,tcp等等
        $signtype="2";//签名方式2  MD5。
        $prono='';
        $prodesc= '';
        $remark1='';
        $remark2='';
        $dfchannel = '';
        $producturl = '';
        //echo $_POST['paytype'];
        //exit;
        if(is_post()&&$_POST['paytype']!='sdopay') {
        $actionUrl = 'http://netpay.sdo.com/paygate/ibankpay.aspx';
        $banks = explode("-", $_POST['paytype']);
        $paychannel ="04";
        $bank = $banks[0];
        }else {
        $actionUrl = 'http://netpay.sdo.com/paygate/default.aspx';
        $bank = '';  
        }
    
        $data=$total_money.$orderid.$merid.$meruesr.$paychannel.$return_url.$notify_url.$backurl.$ordertime.$curtype.$notifytype.$signtype.$prono.$prodesc.$remark1.$remark2.$bank.$dfchannel.$producturl;

        $mac = md5($data.$security_code);
	return render('block_pay_sdopay', array(
                'actionUrl' => $actionUrl,
                'version' => $version,
                'amount' => $total_money,  
		'order_id' => $order_id,
                'orderid' => $orderid,
                'paychannel' => $paychannel,
		'return_url' => $return_url,
		'notifyurl' => $notify_url,
                'merid' => $merid,
		'ordertime' => $ordertime,
		'curtype' => $curtype,
		'notifytype' => $notifytype,
		'signtype' => $signtype,
                'prono' => $prono,
                'remark1' => $remark1,
                'remark2' => $remark2,
                'bank' => $bank,
                'mac' => $mac,
			));
}

//盛付通在线充值方式
function pay_charge_sdopay($total_money, $charge_id, $title) {
	global $INI; if($total_money<=0||!$title) return null;
	$version = '3.0';
        $order_id = 'charge';
        //$total_money=number_format($total_money,2);
        /* param */
        //商户
        $merid = $INI['sdopay']['mid'];
        //密钥
        $security_code = $INI['sdopay']['sec'];
        //支付渠道
        $paychannel='14';
        $sign_type = 'MD5';
        //交易号
        $orderid = $charge_id;
        //echo $orderid;
        //exit;
        //返回地址
        $return_url = $INI['system']['wwwprefix'] . '/order/sdopay/return.php';
        //服务器终端发货通知地址
        $notify_url = $INI['system']['wwwprefix'] . '/order/sdopay/notify.php';
        //echo $return_url;
        //exit;
        $backurl ='';
        $ordertime = date("YmdHis");
        //$prodesc = $title;
        $curtype="RMB";//货币类型，目前仅支持"RMB"
        $notifytype="http";//发货通知方式：http,https,tcp等等
        $signtype="2";//签名方式2  MD5。 
        $prono ='';
        $prodesc= '';
        $remark1 ='';
        $remark2 ='';
        $dfchannel = '';
        $producturl = '';
        if(is_post()&&$_POST['paytype']!='sdopay') {
        $actionUrl = 'http://netpay.sdo.com/paygate/ibankpay.aspx';
        $banks = explode("-", $_POST['paytype']);
        $paychannel ="04";
        $bank = $banks[0];
         }else {
        $actionUrl = 'http://netpay.sdo.com/paygate/default.aspx';
        $bank = '';  
      }
        //echo $actionUrl;
        //exit;
        $data=$total_money.$orderid.$merid.$meruesr.$paychannel.$return_url.$notify_url.$backurl.$ordertime.$curtype.$notifytype.$signtype.$prono.$prodesc.$remark1.$remark2.$bank.$dfchannel.$producturl;

        $mac = md5($data.$security_code);
	return render('block_pay_sdopay', array(
                'actionUrl' => $actionUrl,
                'version' => $version,
                'amount' => $total_money,  
		'order_id' => $order_id,
                'orderid' => $orderid,
                'paychannel' => $paychannel,
		'return_url' => $return_url,
		'notifyurl' => $notify_url,
                'merid' => $merid,
		'ordertime' => $ordertime,
		'curtype' => $curtype,
		'notifytype' => $notifytype,
		'signtype' => $signtype,
                'prono' => $prono,
                'prodesc' => $prodesc,
                'remark1' => $remark1,
                'remark2' => $remark2,
                'bank' => $bank,
                'mac' => $mac,
              		));
}
/* payment: chinabank */
function pay_team_chinabank($total_money, $order) {
	global $INI; if($total_money<=0||!$order) return null;
	$team = Table::Fetch('team', $order['team_id']);
	$order_id = $order['id'];

	$v_mid = $INI['chinabank']['mid'];
	$v_url = $INI['system']['wwwprefix']. '/order/chinabank/return.php';
	$key   = $INI['chinabank']['sec'];
	$v_oid = $order['pay_id'];
	$v_amount = $total_money;
	$v_moneytype = $INI['system']['currencyname'];
	$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;
	$v_md5info = strtoupper(md5($text));

	return render('block_pay_chinabank', array(
				'order_id' => $order_id,
				'v_mid' => $v_mid,
				'v_url' => $v_url,
				'key' => $key,
				'v_oid' => $v_oid,
				'v_moneytype' => $v_moneytype,
				'v_md5info' => $v_md5info,
				));
}

function pay_charge_chinabank($total_money, $charge_id, $title) {
	global $INI; if($total_money<=0||!$title) return null;

	$order_id = 'charge';
	$v_mid = $INI['chinabank']['mid'];
	$v_url = $INI['system']['wwwprefix']. '/order/chinabank/return.php';
	$key   = $INI['chinabank']['sec'];
	$v_oid = $charge_id;
	$v_amount = $total_money;
	$v_moneytype = $INI['system']['currencyname'];
	$text = $v_amount.$v_moneytype.$v_oid.$v_mid.$v_url.$key;
	$v_md5info = strtoupper(md5($text));

	return render('block_pay_chinabank', array(
				'order_id' => $order_id,
				'v_mid' => $v_mid,
				'v_url' => $v_url,
				'key' => $key,
				'v_oid' => $v_oid,
				'v_moneytype' => $v_moneytype,
				'v_md5info' => $v_md5info,
				));
}

/* payment: bill */
function pay_team_bill($total_money, $order) {
	global $INI, $login_user; if($total_money<=0||!$order) return null;
	$team = Table::Fetch('team', $order['team_id']);

	$order_id = $order['id'];
	$merchantAcctId = $INI['bill']['mid'];	
	$key = $INI['bill']['sec']; 
	$inputCharset = "1";
	$pageUrl = $INI['system']['wwwprefix'] . '/order/bill/return.php';
	$bgUrl = $INI['system']['wwwprefix'] . '/order/bill/return.php';
	$version = "v2.0";
	$language = "1";
	$signType = "1";	
	$payerName = $login_user['username'];
	$payerContactType = "1";	
	$payerContact = $login_user['email'];	
	$orderId = $order['pay_id'];
	$orderAmount = intval($total_money * 100);
	$orderTime = date('YmdHis');
	$productName = mb_substr(strip_tags($team['title']),0,255,'UTF-8');
	$productNum="1";
	$productId="";
	$productDesc="";
	$ext1="";
	$ext2="";
	$payType="00";
	$bankId="";
	$redoFlag="0";
	$pid=""; 

	$sv = billAppendParam($sv,"inputCharset",$inputCharset);
	$sv = billAppendParam($sv,"pageUrl",$pageUrl);
	$sv = billAppendParam($sv,"bgUrl",$bgUrl);
	$sv = billAppendParam($sv,"version",$version);
	$sv = billAppendParam($sv,"language",$language);
	$sv = billAppendParam($sv,"signType",$signType);
	$sv = billAppendParam($sv,"merchantAcctId",$merchantAcctId);
	$sv = billAppendParam($sv,"payerName",$payerName);
	$sv = billAppendParam($sv,"payerContactType",$payerContactType);
	$sv = billAppendParam($sv,"payerContact",$payerContact);
	$sv = billAppendParam($sv,"orderId",$orderId);
	$sv = billAppendParam($sv,"orderAmount",$orderAmount);
	$sv = billAppendParam($sv,"orderTime",$orderTime);
	$sv = billAppendParam($sv,"productName",$productName);
	$sv = billAppendParam($sv,"productNum",$productNum);
	$sv = billAppendParam($sv,"productId",$productId);
	$sv = billAppendParam($sv,"productDesc",$productDesc);
	$sv = billAppendParam($sv,"ext1",$ext1);
	$sv = billAppendParam($sv,"ext2",$ext2);
	$sv = billAppendParam($sv,"payType",$payType);	
	$sv = billAppendParam($sv,"bankId",$bankId);
	$sv = billAppendParam($sv,"redoFlag",$redoFlag);
	$sv = billAppendParam($sv,"pid",$pid);
	$sv = billAppendParam($sv,"key",$key);
	$signMsg= strtoupper(md5($sv));

	return render('block_pay_bill', array(
				'order_id' => $order_id,
				'merchantAcctId' => $merchantAcctId,
				'key' => $key,
				'inputCharset' => $inputCharset,
				'pageUrl' => $pageUrl,
				'bgUrl' => $bgUrl,
				'version' => $version,
				'language' => $language,
				'signType' => $signType,
				'payerName' => $payerName,
				'payerContactType' => $payerContactType,
				'payerContact' => $payerContact,
				'orderId' => $orderId,
				'orderAmount' => $orderAmount,
				'orderTime' => $orderTime,
				'productName' => $productName,
				'productNum' => $productNum,
				'productId' => $productId,
				'productDesc' => $productDesc,
				'ext1' => $ext1,
				'ext2' => $ext2,
				'payType' => $payType,
				'bankId' => $bankId,
				'redoFlag' => $redoFlag,
				'pid' => $pid,
				'signMsg' => $signMsg,
				));
}

function pay_charge_bill($total_money, $charge_id, $title) {
	global $INI, $login_user; if($total_money<=0||!$title) return null;

	$order_id = 'charge';
	$merchantAcctId = $INI['bill']['mid'];	
	$key = $INI['bill']['sec']; 
	$inputCharset = "1";
	$pageUrl = $INI['system']['wwwprefix'] . '/order/bill/return.php';
	$bgUrl = $INI['system']['wwwprefix'] . '/order/bill/return.php';
	$version = "v2.0";
	$language = "1";
	$signType = "1";	
	$payerName = $login_user['username'];
	$payerContactType = "1";	
	$payerContact = $login_user['email'];	
	$orderId = $charge_id;
	$orderAmount = intval($total_money * 100);
	$orderTime = date('YmdHis');
	$productName = mb_substr(strip_tags($title),0,255,'UTF-8');
	$productNum="1";
	$productId="";
	$productDesc="";
	$ext1="";
	$ext2="";
	$payType="00";
	$bankId="";
	$redoFlag="0";
	$pid=""; 

	$sv = billAppendParam($sv,"inputCharset",$inputCharset);
	$sv = billAppendParam($sv,"pageUrl",$pageUrl);
	$sv = billAppendParam($sv,"bgUrl",$bgUrl);
	$sv = billAppendParam($sv,"version",$version);
	$sv = billAppendParam($sv,"language",$language);
	$sv = billAppendParam($sv,"signType",$signType);
	$sv = billAppendParam($sv,"merchantAcctId",$merchantAcctId);
	$sv = billAppendParam($sv,"payerName",$payerName);
	$sv = billAppendParam($sv,"payerContactType",$payerContactType);
	$sv = billAppendParam($sv,"payerContact",$payerContact);
	$sv = billAppendParam($sv,"orderId",$orderId);
	$sv = billAppendParam($sv,"orderAmount",$orderAmount);
	$sv = billAppendParam($sv,"orderTime",$orderTime);
	$sv = billAppendParam($sv,"productName",$productName);
	$sv = billAppendParam($sv,"productNum",$productNum);
	$sv = billAppendParam($sv,"productId",$productId);
	$sv = billAppendParam($sv,"productDesc",$productDesc);
	$sv = billAppendParam($sv,"ext1",$ext1);
	$sv = billAppendParam($sv,"ext2",$ext2);
	$sv = billAppendParam($sv,"payType",$payType);	
	$sv = billAppendParam($sv,"bankId",$bankId);
	$sv = billAppendParam($sv,"redoFlag",$redoFlag);
	$sv = billAppendParam($sv,"pid",$pid);
	$sv = billAppendParam($sv,"key",$key);
	$signMsg= strtoupper(md5($sv));

	return render('block_pay_bill', array(
				'order_id' => $order_id,
				'merchantAcctId' => $merchantAcctId,
				'key' => $key,
				'inputCharset' => $inputCharset,
				'pageUrl' => $pageUrl,
				'bgUrl' => $bgUrl,
				'version' => $version,
				'language' => $language,
				'signType' => $signType,
				'payerName' => $payerName,
				'payerContactType' => $payerContactType,
				'payerContact' => $payerContact,
				'orderId' => $orderId,
				'orderAmount' => $orderAmount,
				'orderTime' => $orderTime,
				'productName' => $productName,
				'productNum' => $productNum,
				'productId' => $productId,
				'productDesc' => $productDesc,
				'ext1' => $ext1,
				'ext2' => $ext2,
				'payType' => $payType,
				'bankId' => $bankId,
				'redoFlag' => $redoFlag,
				'pid' => $pid,
				'signMsg' => $signMsg,
				));
}

/* payment: paypal */
function pay_team_paypal($total_money, $order) {
	global $INI, $login_user; if($total_money<=0||!$order) return null;
	$team = Table::Fetch('team', $order['team_id']);
	
	$order_id = $order['id'];
	$cmd = '_xclick';
	$business = $INI['paypal']['mid'];
	$location = $INI['paypal']['loc'];
	$currency_code = $INI['system']['currencyname'];

	$item_number = $order['pay_id'];
	$item_name = $team['title'];
	$amount = $total_money;
	$quantity = 1;

	$post_url = "https://www.paypal.com/row/cgi-bin/webscr";
	$return_url = $INI['system']['wwwprefix'] . '/order/index.php';
	$notify_url = $INI['system']['wwwprefix'] . '/order/paypal/ipn.php';
	$cancel_url = $INI['system']['wwwprefix'] . "/order/index.php";

	return render('block_pay_paypal', array(
				'order_id' => $order_id,
				'cmd' => $cmd,
				'business' => $business,
				'location' => $location,
				'currency_code' => $currency_code,
				'item_number' => $item_number,
				'item_name' => $item_name,
				'amount' => $amount,
				'quantity' => $quantity,
				'post_url' => $post_url,
				'return_url' => $return_url,
				'notify_url' => $notify_url,
				'cancel_url' => $cancel_url,
				'login_user' => $login_user,
				));
}

function pay_charge_paypal($total_money, $charge_id, $title) {
	global $INI, $login_user; if($total_money<=0||!$title) return null;

	$order_id = 'charge';
	$cmd = '_xclick';
	$business = $INI['paypal']['mid'];
	$location = $INI['paypal']['loc'];
	$currency_code = $INI['system']['currencyname'];

	$item_number = $charge_id;
	$item_name = $title;
	$amount = $total_money;
	$quantity = 1;

	$post_url = "https://www.paypal.com/row/cgi-bin/webscr";
	$return_url = $INI['system']['wwwprefix'] . '/order/index.php';
	$notify_url = $INI['system']['wwwprefix'] . '/order/paypal/ipn.php';
	$cancel_url = $INI['system']['wwwprefix'] . "/order/index.php";

	return render('block_pay_paypal', array(
				'order_id' => $order_id,
				'cmd' => $cmd,
				'business' => $business,
				'location' => $location,
				'currency_code' => $currency_code,
				'item_number' => $item_number,
				'item_name' => $item_name,
				'amount' => $amount,
				'quantity' => $quantity,
				'post_url' => $post_url,
				'return_url' => $return_url,
				'notify_url' => $notify_url,
				'cancel_url' => $cancel_url,
				'login_user' => $login_user,
				));
}

/* payment: yeepay */
function pay_team_yeepay($total_money, $order) {
	global $INI, $login_user; if($total_money<=0||!$order) return null;
	$team = Table::Fetch('team', $order['team_id']);
	require_once( WWW_ROOT . '/order/yeepay/yeepayCommon.php');

	$order_id = $order['id'];
	$pay_id = $order['pay_id'];
	$p0_Cmd = 'Buy';
	$p1_MerId = $INI['yeepay']['mid'];
	$p2_Order = $pay_id;
	$p3_Amt = $total_money;
	$p4_Cur = "CNY";
	$p5_Pid = "ZuituGo-{$_SERVER['HTTP_HOST']}({$team['id']})";
	$p6_Pcat = '';
	$p5_Pdesc = "ZuituGo-{$_SERVER['HTTP_HOST']}({$team['id']})";
	$p8_Url = $INI['system']['wwwprefix'] . '/order/yeepay/callback.php';
	$p9_SAF = '0';
	$pa_MP = '';
	$pd_FrpId = strval($_REQUEST['pd_FrpId']);
	$pr_NeedResponse = '1';
	$merchantKey = $INI['yeepay']['sec'];

	$hmac = getReqHmacString($p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse,$merchantKey);

	return render('block_pay_yeepay', array(
				'order_id' => $order_id,
				'p0_Cmd' => $p0_Cmd,
				'p1_MerId' => $p1_MerId,
				'p2_Order' => $p2_Order,
				'p3_Amt' => $p3_Amt,
				'p4_Cur' => $p4_Cur,
				'p5_Pid' => $p5_Pid,
				'p6_Pcat' => $p6_Pcat,
				'p7_Pdesc' => $p7_Pdesc,
				'p8_Url' => $p8_Url,
				'p9_SAF' => $p9_SAF,
				'pa_MP' => $pa_MP,
				'pd_FrpId' => $pd_FrpId,
				'pr_NeedResponse' => $pr_NeedResponse,
				'merchantKey' => $merchantKey,
				'hmac' => $hmac,
				));
}

function pay_charge_yeepay($total_money, $charge_id, $title) {
	global $INI, $login_user; if($total_money<=0||!$title) return null;
	require_once( WWW_ROOT . '/order/yeepay/yeepayCommon.php');

	$order_id = 'charge';
	$p0_Cmd = 'Buy';
	$p1_MerId = $INI['yeepay']['mid'];
	$p2_Order = $charge_id;
	$p3_Amt = $total_money;
	$p4_Cur = "CNY";
	$p5_Pid = "ZuituGo-Charge({$total_money})";
	$p6_Pcat = '';
	$p5_Pdesc = "ZuituGo-Charge({$total_money})";
	$p8_Url = $INI['system']['wwwprefix'] . '/order/yeepay/callback.php';
	$p9_SAF = '0';
	$pa_MP = '';
	$pd_FrpId = strval($_REQUEST['pd_FrpId']);
	$pr_NeedResponse = '1';
	$merchantKey = $INI['yeepay']['sec'];

	$hmac = getReqHmacString($p1_MerId,$p2_Order,$p3_Amt,$p4_Cur,$p5_Pid,$p6_Pcat,$p7_Pdesc,$p8_Url,$pa_MP,$pd_FrpId,$pr_NeedResponse,$merchantKey);

	return render('block_pay_yeepay', array(
				'order_id' => $order_id,
				'p0_Cmd' => $p0_Cmd,
				'p1_MerId' => $p1_MerId,
				'p2_Order' => $p2_Order,
				'p3_Amt' => $p3_Amt,
				'p4_Cur' => $p4_Cur,
				'p5_Pid' => $p5_Pid,
				'p6_Pcat' => $p6_Pcat,
				'p7_Pdesc' => $p7_Pdesc,
				'p8_Url' => $p8_Url,
				'p9_SAF' => $p9_SAF,
				'pa_MP' => $pa_MP,
				'pd_FrpId' => $pd_FrpId,
				'pr_NeedResponse' => $pr_NeedResponse,
				'merchantKey' => $merchantKey,
				'hmac' => $hmac,
				));
}

/****payment cmpay***/

function pay_team_cmpay($total_money, $order) {
	global $INI; if($total_money<=0||!$order) return null;

	$team = Table::Fetch('team', $order['team_id']);
        $order_id = $order['id'];
        $orderId = $order['pay_id'];
        $productDesc = $team['title'];
        $productName = $team['title'];
        $productId = date('Ymd');//产品编号
        $amount = $total_money;
        $currency = "CNY";
        $channelType = "TOKEN";
        $functiontype = "DODIRECTPAYMENT";
	return render('block_pay_cmpay', array(
                  'order_id' => $order_id,
                  'orderId' => $orderId,
                  'channelType' => $channelType,
                  'amount' => $amount,
                  'currency' => $currency,
                  'productName' => $productName,
                  'productDesc' => $productDesc,
                  'productId' => $productId,
				));
}


function pay_charge_cmpay($total_money, $charge_id, $title) {
	global $INI, $login_user; if($total_money<=0||!$title) return null;


        $order_id = 'charge';
        $orderId = $charge_id; 
        $productDesc = $title;
        $productName = $title;
        $productId = date('Ymd');//产品编号
        $amount = $total_money;
        $currency = "CNY";
        $channelType = "TOKEN";
        $functiontype = "DODIRECTPAYMENT";

        return render('block_pay_cmpay', array(
                  'order_id' => $order_id,
                  'orderId' => $orderId,
                  'channelType' => $channelType,
                  'amount' => $amount,
                  'currency' => $currency,
                  'productName' => $productName,
                  'productDesc' => $productDesc,
                  'productId' => $productId,
				));
}
/* pay util function */
function billAppendParam($s, $k, $v){
	$joinstring = $s ? '&' : null;
	return $v=='' ? $s : "{$s}{$joinstring}{$k}={$v}";
}
