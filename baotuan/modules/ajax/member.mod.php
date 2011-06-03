<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename member.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


class ModuleObject extends MasterObject
{
	
	var $Config = array(); 	
	
	var $ID;
	

	function ModuleObject(& $config)
	{
		$this->MasterObject($config);
		$this->ID=$this->Post['id']?(int)$this->Post['id']:(int)$this->Get['id'];

		Load::moduleCode($this);$this->Execute();
	}

	
	function Execute()
	{
		switch ($this->Code)
		{
			
			case 'check_username':
				$this->CheckUsername();
				break;
			case 'check_email':
				$this->CheckEmail();
				break;
									
			default:
				$this->Main();
				break;
		}

	}
	
	
	function Main()
	{
		response_text(__("���ڽ����С���"));
	}

	
	
	function CheckUsername()
	{
		$username=trim($this->Post['username'] ? $this->Post['username'] : $this->Post['check_value']);
		
		if (strlen($username) < 3 || strlen($username) > 15) {
			response_text(__("�û��������������3~15"));
		}
		if (($filter_msg = filter($username))) {
			response_text("�û��� ".$filter_msg);
		}
		if (preg_match('~[\~\`\!\@\#\$\%\^\&\*\(\)\=\+\[\{\]\}\;\:\'\"\,\<\.\>\/\?]~',$username)) {
			response_text(__("�û������ܰ��������ַ�"));
		}
		$censoruser = ConfigHandler::get('user','forbid');
		$censoruser .= "topic
login
member
profile
tag
get_password
report
weather
master
url";

		$censorexp = '/^('.str_replace(array('\\*', "\r\n", ' '), array('.*', '|', ''), preg_quote(($censoruser = trim($censoruser)), '/')).')$/i';
		if($censoruser && @preg_match($censorexp, $username)) {
			response_text(sprintf(__("�û���<b>%s</b>����������ֹע��"), $username));
		}
		
		$response= "�Բ�����������û��� <B>{$username}</B> ����ע����Ѿ�������ʹ�ã���ѡ���������ֺ����ԡ�";
		
		$this->DatabaseHandler->SetTable(TABLE_PREFIX. 'system_members');
		$is_exists=$this->DatabaseHandler->Select('',"username='{$username}'");
		
		if($is_exists) {
			response_text($response);
		}
		
		if(true === UCENTER)
		{
			include_once(UC_CLIENT_ROOT . './client.php');
			$uc_result = uc_user_checkname($username);
			
			if($uc_result < 0) {
				response_text($response);
			}
		}
		exit ;
	}
	
	function CheckEmail()
	{
		$email=trim($this->Post['email'] ? $this->Post['email'] : $this->Post['check_value']);
		if (strlen($email) < 5) {
			response_text(__("��������ȷ��Email��ַ"));
		}
		
		$host=strstr($email,'@');
		if (stristr($this->Config["reg_email_forbid"],$host)!==false)
		{
			$response= __("�������ʼ������ṩ�̻���˱�վ�����͵���Ч�ʼ�����������дһ��EMAIL��ַ��");
			response_text($response);
		}
		include(LIB_PATH."validate.han.php");
		$this->ValidateHandler=new ValidateHandler();
		if ($this->ValidateHandler->IsValid($email,"email")==false)
		{
			$response= __("�������EMAIL��ַ��ʽ��Ч") ;
			response_text($response);
		}

		$response = sprintf(__("�Բ����������EMAIL��ַ <b>%s</b> ����ע����Ѿ�������ʹ��"), $email) ;			
		if($this->Config["reg_email_doublee"]!= "1")
		{
		
			$this->DatabaseHandler->SetTable(TABLE_PREFIX. 'system_members');
			$is_exists=$this->DatabaseHandler->Select('',"email='{$email}'");
			if($is_exists!==false) response_text($response);
		}
		
		if(true === UCENTER)
		{
			include_once(UC_CLIENT_ROOT . './client.php');
			
			$uc_result = uc_user_checkemail($email);
			
			if($uc_result < 0) {
				response_text($response);
			}
		}
		exit ;
	}
	
	
}


?>