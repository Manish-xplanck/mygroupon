<?php 
require_once("alipay.config.php");
require_once("lib/alipay_notify.class.php");
$alipayNotify = new AlipayNotify($aliapy_config);
$verify_result = $alipayNotify->verifyReturn();
if($verify_result) {
    //print_r($_REQUEST);exit;
	$ali_user = ZUser::Check_alifast($_GET['user_id'],$_GET['real_name']);
	//print_r($ali_user);exit;
	if($ali_user){
	   Session::Set('user_id', $ali_user['id']);
	   Session::Set('ali_token', $_GET['token']);
	   ZCredit::Login($ali_user['id']);
	   redirect(get_loginpage(WEB_ROOT . '/index.php'));
	}else{ 
       Session::Set('error', '验证失败');
	   redirect(WEB_ROOT . '/index.php');
	}

	//etao专用
    if($_GET['target_url'] != "") {
           exit('暂不支持');
    }
	
     
}
else {
    echo "验证失败";
}
?>