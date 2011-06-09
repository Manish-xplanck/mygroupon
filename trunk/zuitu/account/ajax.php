<?php
require_once(dirname(dirname(__FILE__)) . '/app.php');

need_login();

$action = strval($_GET['action']);
$id = strval($_GET['id']);
$v = strval($_GET['v']);

if ( 'bindcancel' == $action ) {
	Table::UpdateCache('user', $login_user['id'], array(
				'mobilecode' => null,
				));
	json(array(
				array( 'data'=>'取消手机绑定成功', 'type' => 'alert'),
				array( 'data'=>null, 'type' => 'refresh'),
			  ),	'mix');
}
else if ( 'bindcode' == $action ) {
	if(Utility::IsMobile($v)) {
		$mobilecode = Utility::GenSecret(6, Utility::CHAR_NUM);
		sms_bind($v, $mobilecode);
		Table::UpdateCache('user', $login_user['id'], array(
					'mobilecode' => $mobilecode,
					));
		json('请输入你的手机接收到的绑定码', 'alert');
	}
	json('手机号码不正确', 'alert');
}
