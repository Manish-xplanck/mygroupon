<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename account.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 



class ModuleObject extends MasterObject
{
    public $Username = '';
    public $Password = '';
    public $Secques = '';
    public $IsAdmin = false;

    function ModuleObject( $config )
    {
        $this->MasterObject($config);
                $this->Username = isset($this->Post['username']) ? trim($this->Post['username']) : "";
        $this->Password = isset($this->Post['password']) ? trim($this->Post['password']) : "";
        $this->Secques = quescrypt($this->Post['question'], $this->Post['answer']);
        if ( MEMBER_ID > 0 )
        {
            $this->IsAdmin = $this->MemberHandler->HasPermission('member', 'admin');
        }
                $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    
    public function Main()
    {
        header('Location: '.rewrite('?mod=me'));
    }
    
    public function Exists()
    {
        $field = get('field', 'txt');
        $value = get('value');
        $allows = array(
            'email', 'name'
        );
        if (false !== array_search($field, $allows))
        {
            $ops = array(
                'status' => 'ok',
                'result' => logic('account')->Exists($field, $value)
            );
        }
        else
        {
            $ops = array('status'=>'failed');
        }
        exit(jsonEncode($ops));
    }

    
    function Login()
    {
        if ( MEMBER_ID != 0 and false == $this->IsAdmin )
        {
            $this->Messager("您已经使用用户名 " . MEMBER_NAME . " 登录系统，无需再次登录！", null);
        }
        $loginperm = $this->_logincheck();
        if ( ! $loginperm )
        {
            $this->Messager("累计 5 次错误尝试，15 分钟内您将不能登录。", null);
        }
        $this->Title = "用户登录";
        
        $action = "?mod=account&code=login&op=done";
        $question_select = FormHandler::Select("question", ConfigHandler::get("member", "question_list"), 0);
        $role_type_select = FormHandler::Radio("role_type", ConfigHandler::get("member", "role_type_list"), "normal");
        account()->loginReferer($_SERVER['HTTP_REFERER']);
        include ($this->TemplateHandler->Template("account_login"));
    }

    
    function Login_done()
    {
        if ( $this->Username == "" || $this->Password == "" )
        {
            $this->Messager("无法登录,用户名或密码不能为空", '?mod=account&code=login');
        }
        $loginperm = $this->_logincheck();
        if ( ! $loginperm )
        {
            $this->Messager("累计 5 次错误尝试，15 分钟内您将不能登录。", null);
        }
        $sql = "select `uid`,`username`,`checked`,`role_id` from `" . TABLE_PREFIX . "system_members` where `username`='{$this->Username}'";
        $query = $this->DatabaseHandler->Query($sql);
        $row = $query->GetRow();
        if ( ! $row )
        {
            $sql = "select `uid`,`username`,`checked`,`role_id` from `" . TABLE_PREFIX . "system_members` where `email`='{$this->Username}'";
            $query = $this->DatabaseHandler->Query($sql);
            $row = $query->GetRow();
            if ( ! $row )
            {
                            }
        }
        if ( $row )
        {
            $this->Username = $row['username'];
        }
        else
        {
                    }
                $config = ConfigHandler::get('product');
        if ( $row && $row['role_id'] != 2 && $row['checked'] == 0 && $config['default_emailcheck'] )
        {
            $this->Messager("您还没有通过邮箱验证呢！<a href='?mod=account&code=sendcheckmail&uname=" . urlencode($this->Username) . "'>点这里重新发送认证邮件  </a>", 'null');
        }
        $keepLogin = ($_POST['keeplogin'] == 'on');
        $lresult = account()->Login($this->Username, $this->Password, $keepLogin);
        if ($lresult['error'])
        {
            $this->_loginfailed($loginperm);
            $this->Messager($lresult['result'], -1);
        }
        $ref = account()->loginReferer();
        $ref || $ref = '?mod=me';
        $this->Messager(__('登录成功！').$lresult['result'], $ref);
    }
    
    function Login_union()
    {
        $flag = get('flag', 'txt');
        if (!$flag || !ini('alipay.account.login.source.'.$flag)) exit('ERROR: no Union Login Request');
        $html = account('ulogin')->linker($flag);
        include handler('template')->file('@account/login/redirect');
    }
    function Login_callback()
    {
        $from = get('from', 'txt');
        $uuid = account('ulogin')->verify($from);
        if ($uuid !== false)
        {
            if (meta($uuid))
            {
                                $result = account('ulogin')->login($uuid);
                $ref = account()->loginReferer();
                $ref || $ref = '?mod=me';
                $this->Messager(__('登录成功！').$result, $ref);
            }
            else
            {
                                $data = account('ulogin')->ddata($from);
                                include handler('template')->file('account_active');
            }
        }
        else
        {
            $this->Messager(__('快捷登录验证出错！'));
        }
    }
    function Login_active()
    {
        $uuid = post('uuid', 'txt');
        $username = post('username');
        $password = post('password');
        $mail = post('mail', 'txt');
        if (!$mail || !check_email($mail))
        {
            $this->Messager(__('请输入正确的Email地址！'), -1);
        }
        $subs = post('subs');
                $result = account('ulogin')->active($uuid, $username, $password, $mail);
        if (!$result)
        {
            $this->Messager(__('帐号激活失败！'));
        }
                if ($subs)
        {
            logic('subscribe')->Add(logic('misc')->City('id'), 'mail', $mail, 'true');
        }
                $phone = post('phone', 'number');
        if ($phone && strlen($phone) == 11)
        {
            user($result)->set('phone', $phone);
            if ($subs)
            {
                logic('subscribe')->Add(logic('misc')->City('id'), 'sms', $phone, 'true');
            }
        }
                $result = account('ulogin')->login($uuid);
        $ref = account()->loginReferer();
        $ref || $ref = '?mod=me';
        $this->Messager(__('登录成功！').$result, $ref);
    }

    
    function _updateLoginFields( $uid )
    {
        $timestamp = time();
        $last_ip = getenv('REMOTE_ADDR');
        $sql = "
		UPDATE
			" . TABLE_PREFIX . 'system_members' . "
		SET
			`login_count`='login_count'+1,
			`lastvisit`='{$timestamp}',
			`lastactivity`='{$timestamp}',
			`lastip`='{$last_ip}'
		WHERE 
			uid={$uid}";
        $query = $this->DatabaseHandler->Query($sql);
        Return $query;
    }

    
    function Logout()
    {
        handler('cookie')->ClearAll();
        handler('member')->SessionExists = false;
        handler('member')->MemberFields = array();
        if ( true === UCENTER )
        {
            include_once (UC_CLIENT_ROOT . './client.php');
            $msg = uc_user_synlogout();
        }
        $this->Messager($msg . '退出成功', '?');
    }

    function _logincheck()
    {
        $onlineip = $_SERVER['REMOTE_ADDR'];
        $timestamp = time();
        $query = $this->DatabaseHandler->Query("SELECT count, lastupdate FROM " . TABLE_PREFIX . 'system_failedlogins' . " WHERE ip='$onlineip'");
        if ( $login = $query->GetRow() )
        {
            if ( $timestamp - $login['lastupdate'] > 900 )
            {
                return 3;
            }
            elseif ( $login['count'] < 5 )
            {
                return 2;
            }
            else
            {
                return 0;
            }
        }
        else
        {
            return 1;
        }
    }

    function _loginfailed( $permission )
    {
        $onlineip = $_SERVER['REMOTE_ADDR'];
        $timestamp = time();
        switch ( $permission )
        {
            case 1 :
                $this->DatabaseHandler->Query("REPLACE INTO " . TABLE_PREFIX . 'system_failedlogins' . " (ip, count, lastupdate) VALUES ('$onlineip', '1', '$timestamp')");
                break;
            case 2 :
                $this->DatabaseHandler->Query("UPDATE " . TABLE_PREFIX . 'system_failedlogins' . " SET count=count+1, lastupdate='$timestamp' WHERE ip='$onlineip'");
                break;
            case 3 :
                $this->DatabaseHandler->Query("UPDATE " . TABLE_PREFIX . 'system_failedlogins' . " SET count='1', lastupdate='$timestamp' WHERE ip='$onlineip'");
                $this->DatabaseHandler->Query("DELETE FROM " . TABLE_PREFIX . 'system_failedlogins' . " WHERE lastupdate<$timestamp-901", 'UNBUFFERED');
                break;
        }
    }

    function Register()
    {
        $this->Title = __('注册');
                $city = logic('misc')->CityList();
        $action = '?mod=account&code=register&op=done';
        include ($this->TemplateHandler->Template("account_register"));
    }

    function Register_done()
    {
        extract($this->Post);
        if ( $email == '' || $truename == '' || $pwd == '' )
        {
            $this->Messager('请将资料填写完整', -1);
        }
        ;
                if ( ! check_email($email) )
        {
            $this->Messager("邮箱地址有误！", -1);
        }
        ;
        $sql = 'select uid from ' . TABLE_PREFIX . 'system_members where email = \'' . $email . '\'';
        $query = $this->DatabaseHandler->Query($sql);
        $user = $query->GetRow();
        if ( $user != '' )
        {
            $this->Messager("邮箱已经被注册。请重新输入！", -1);
        }
        ;
        if ( preg_match('~[\~\`\!\@\#\$\%\^\&\*\(\)\=\+\[\{\]\}\;\:\'\"\,\<\.\>\/\?]~', $truename) )
        {
            $this->Messager("用户名不能包含特殊字符", - 1);
        }
        ;
                if ( $pwd != $ckpwd )
        {
            $this->Messager("两次密码输入不一致！", -1);
        }
        ;
        $sql = 'select uid from ' . TABLE_PREFIX . 'system_members where truename = \'' . $truename . '\'';
        $query = $this->DatabaseHandler->Query($sql);
        $user = $query->GetRow();
        if ( $user != '' ) $this->Messager("用户名已存在。请重新输入！", -1);
        $showemail = $showemail == false ? 0 : 1;
        $rresult = account()->Register($truename, $pwd, $email, $phone);
        if ($rresult['error'])
        {
            $this->Messager($rresult['result'], -1);
        }
        $keepLogin = true;
        $lresult = account()->Login($truename, $pwd, $keepLogin);
        if ($lresult['error'])
        {
                                }
        $ref = account()->loginReferer();
        $ref || $ref = '?mod=me';
        $ucsynlogin = $lresult['result'];
        
                if ( $showemail == 1 )
        {
                        logic('subscribe')->Validate(logic('subscribe')->Add($city, 'mail', $email));
            if (preg_match('/[0-9]{8,12}/', $phone))
            {
                logic('subscribe')->Validate(logic('subscribe')->Add($city, 'sms', $phone));
            }
        }
        if ( ! $this->config['default_emailcheck'] )
        {
            $this->Messager("注册成功{$ucsynlogin}", $ref);
        }
                $this->registmail($truename, $email);
        $this->Messager("感谢您的注册，我们已经给您的邮箱发送了一封邮件请您登录邮箱激活账号！{$ucsynlogin}");
    }

        function registmail( $truename, $email )
    {
        $key = authcode($truename, 'ENCODE', $this->Config['auth_key']);
        $mail['title'] = $this->Config['site_name'] . '欢迎您！';
        $mail['content'] = $this->Config['site_name'] . '欢迎您的注册 ，<a href="' . $this->Config['site_url'] . '/?mod=index&code=confirm&pwd=' . urlencode($key) . '">请点击这里激活账号</a>，或者复制 <br/>' . $this->Config['site_url'] . '/?mod=index&code=confirm&pwd=' . urlencode($key) . '到浏览器中';
        logic('service')->mail()->Send($email, $mail['title'], $mail['content']);
    }

    function Sendcheckmail()
    {
        extract($this->Get);
        $uname = urldecode($uname);
                $sql = 'select * from ' . TABLE_PREFIX . 'system_members where username=\'' . $uname . '\' and checked = 0';
        $query = $this->DatabaseHandler->Query($sql);
        $user = $query->GetRow();
        if ( $user != '' )
        {
            $this->registmail($uname, $user['email']);
            $this->Messager("已经发送一封确认信件到您的邮箱去了，请注意查收！", '?');
        }
        $this->Messager("错误，该用户以确认信箱或不存在！", '?');
    }
}
?>