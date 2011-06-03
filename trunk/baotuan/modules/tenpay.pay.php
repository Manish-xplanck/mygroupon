<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename tenpay.pay.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 
 


function payTo($payment,$returnurl,$pay){
		$payment=unserialize($pay['pay_config']);
		$cmd_no = '1';
        
        $sp_billno = $pay['orderid'];
        
        $today = date('Ymd');
        
        $bill_no = str_pad($pay['orderid'], 18, 0, STR_PAD_LEFT);
        $transaction_id = $payment['tenpay_account'].$bill_no;
        
        $bank_type = '0';
        
        $desc = $pay['name'];
        $attach = $pay['name'];
        
        $return_url = $returnurl;
        
        $total_fee = floatval($pay['price'])*100;
        
        $fee_type = '1';
        
        $attach = '';
		
        $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
        
        $sign_text = "cmdno=" . $cmd_no . "&date=" . $today . "&bargainor_id=" . $payment['tenpay_account'] .
          "&transaction_id=" . $transaction_id . "&sp_billno=" . $sp_billno .
          "&total_fee=" . $total_fee . "&fee_type=" . $fee_type . "&return_url=" . $return_url .
          "&attach=" . $attach . "&spbill_create_ip=" . $spbill_create_ip . "&key=" . $payment['tenpay_key'];
        $sign = strtoupper(md5($sign_text));
        $parameter = array(
            'cmdno'             => $cmd_no,                                 'date'              => $today,                                  'bank_type'         => $bank_type,                              'desc'              => $desc,                                   'purchaser_id'      => '',                                      'bargainor_id'      => $payment['tenpay_account'],              'transaction_id'    => $transaction_id,                         'sp_billno'         => $sp_billno,                              'total_fee'         => $total_fee,                              'fee_type'          => $fee_type,                               'return_url'        => $return_url,                             'attach'            => $attach,                                 'sign'              => $sign,                       			'spbill_create_ip'  => $spbill_create_ip,                   	'sys_id'            => '542554970',                 
            'sp_suggestuser'    => '1202822001'                         );
        $button  = '<form action="https:/'.'/www.tenpay.com/cgi-bin/v1.0/pay_gate.cgi" target="_blank" style="clear:right;" >';
        foreach ($parameter AS $key=>$val){
            	$button  .= "<input type='hidden' name='$key' value='$val' />";
        }
        $button  .= '<input type="submit" class="formbutton"  value="立即支付" /></form>';
		return $button;
}

function payRe($userID){
	 $cmd_no         = $_GET['cmdno'];
     $pay_result     = $_GET['pay_result'];
     $pay_info       = $_GET['pay_info'];
     $bill_date      = $_GET['date'];
     $bargainor_id   = $_GET['bargainor_id'];
     $transaction_id = $_GET['transaction_id'];
     $sp_billno      = $_GET['sp_billno'];     $total_fee      = $_GET['total_fee'];     $fee_type       = $_GET['fee_type'];
     $attach         = $_GET['attach'];
     $sign           = $_GET['sign'];
     
     if ($pay_result > 0){
       	return "支付失败!";
    };
    $error='';
    $DatabaseHandler = Obj::registry('DatabaseHandler');
    	$sql='select * from '.TABLE_PREFIX.'tttuangou_order where orderid = '.addslashes($_GET['sp_billno']).' and userid ='.$userID;
	$query = $DatabaseHandler->Query($sql);
	$order=$query->GetRow();
	if($order=='')return "无法继续付款，无法找到订单!";
	if($order['pay']==1) return "请勿重复使用已支付的订单!";
		$sql='select * from '.TABLE_PREFIX.'tttuangou_payment where pay_id = '.$order['paytype'];
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	$payment=unserialize($pay['pay_config']);
    
    $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result .
     	"&date=" . $bill_date . "&transaction_id=" . $transaction_id .
        "&sp_billno=" . $sp_billno . "&total_fee=" . $total_fee .
        "&fee_type=" . $fee_type . "&attach=" . $attach .
        "&key=" . $payment['tenpay_key'];
    $sign_md5 = strtoupper(md5($sign_text));
    if ($sign_md5 != $sign){
       return "支付失败!";
    }else{
       	return $ary=array('price'=> $total_fee/100 ,'orderid'=>$_GET['sp_billno']);
    };
}

function addmymoney($userID){
	 $error='';
	 $cmd_no         = $_GET['cmdno'];
     $pay_result     = $_GET['pay_result'];
     $pay_info       = $_GET['pay_info'];
     $bill_date      = $_GET['date'];
     $bargainor_id   = $_GET['bargainor_id'];
     $transaction_id = $_GET['transaction_id'];
     $sp_billno      = $_GET['sp_billno'];     $total_fee      = $_GET['total_fee'];     $fee_type       = $_GET['fee_type'];
     $attach         = $_GET['attach'];
     $sign           = $_GET['sign'];
     
     if ($pay_result > 0){
       	return "支付失败!";
    };
		$DatabaseHandler = Obj::registry('DatabaseHandler');
	$sql='select count(*) from '.TABLE_PREFIX.'tttuangou_addmoney where id=\''.addslashes($_GET['sp_billno']).'\'';
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	if($pay['count(*)']!=0)return '您不能重复充值同一订单，充值失败！';
	$sql='select * from '.TABLE_PREFIX.'tttuangou_payment where pay_code   =\''.addslashes($_GET['pay']).'\'';
	$query = $DatabaseHandler->Query($sql);
	$pay=$query->GetRow();
	$payment=unserialize($pay['pay_config']);
	
	 $sign_text  = "cmdno=" . $cmd_no . "&pay_result=" . $pay_result .
     	"&date=" . $bill_date . "&transaction_id=" . $transaction_id .
        "&sp_billno=" . $sp_billno . "&total_fee=" . $total_fee .
        "&fee_type=" . $fee_type . "&attach=" . $attach .
        "&key=" . $payment['tenpay_key'];
	 $sign_md5 = strtoupper(md5($sign_text));
    if (trim($sign_md5) != trim($sign)){
       return "验证失败!";
    }else{
       return $ary=array('price'=>  $total_fee/100 ,'orderid'=>$_GET['sp_billno']);
    };
}

?>