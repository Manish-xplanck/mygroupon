<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename account.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class AccountLogic
{
    
    public function Exists($field, $value)
    {
        $result = $this->Search($field, $value);
        return $result ? true : false;
    }
    
    public function Search()
    {
        $argc = func_num_args();
                $field = $sfield = func_get_arg(0);
        $map = array(
            'id' => 'uid',
            'name' => 'username'
        );
        if (array_key_exists($sfield, $map))
        {
            $field = $map[$sfield];
        }
        if ($argc > 1)
        {
            $value = func_get_arg(1);
        }
        $sql_sel = '*';
        foreach ($map as $flag => $src)
        {
            $sql_sel .= ',`'.$src.'` AS `'.$flag.'`';
        }
        $sql = 'SELECT '.$sql_sel.' FROM '.table('members');
        if (isset($value))
        {
            $sql .= ' WHERE `'.$field.'`='.(is_string($value) ? '"'.$value.'"' : $value);
        }
        $limit = 0;
        if ($argc > 2)
        {
            $limit = func_get_arg(2);
            $sql .= ' LIMIT '.$limit;
        }
        $query = dbc()->Query($sql);
        $result = ($limit == 1) ? $query->GetRow() : $query->GetAll();
        return $result;
    }
    
    public function Login($username, $password, $keepLogin = true)
    {
        $extend = '';
                if ( true === UCENTER )
        {
            include_once (UC_CLIENT_ROOT . './client.php');
            list ($uc_uid, $uc_username, $uc_password, $uc_email, $uc_same_username) = uc_user_login($username, $password);             $query = dbc()->Query("select * from " . TABLE_PREFIX . "system_members where username='{$username}'");
            $check = 0;
            $member_info = $query->GetRow();
            if ( $member_info )
            {
                if ( $member_info['password'] == md5($password) )
                {
                    $check = 1;
                }
                else
                {
                    $check = - 1;
                }
                if ( $member_info['ucuid'] != $uc_uid )
                {
                    dbc()->Query("update " . TABLE_PREFIX . "system_members set `ucuid`='$uc_uid' where `uid`='{$member_info['uid']}'");
                }
            }             if ( $uc_uid < 0 && $check < 1 )             {
            	$debug = '';
            	if (DEBUG) $debug = '[L].['.$check.','.$uc_uid.']';
                return $this->ErrorInf(__('登录失败！').$debug);
            }
            else
            {
                if ( $uc_uid > 0 && $check < 1 )                 {
                    if ( $check == 0 )                     {
                        dbc()->Query("insert into " . TABLE_PREFIX . "system_members set `username`='{$uc_username}',`truename`='{$uc_username}',`password`='" . (md5($password)) . "',`email`='{$uc_email}',`role_id`='".ini('settings.normal_default_role_id')."',`ucuid`='{$uc_uid}'");
                        $newuid = dbc()->Insert_ID();
                        dbc()->Query("replace into " . TABLE_PREFIX . "system_memberfields(`uid`) values('{$newuid}')");
                    }
                    else                     {
                        if ( $member_info['uid'] > 0 )                         {
                            dbc()->Query("update " . TABLE_PREFIX . "system_members set `password`='" . (md5($password)) . "' where `uid`='{$member_info['uid']}'");
                        }
                        else                         {
                                                        $debug = '';
                            if (DEBUG) $debug = '[R]';
                            return $this->ErrorInf(__('登录失败！').$debug);
                        }
                    }
                }
                elseif ( $uc_uid < 0 && $check == 1 )                 {
                    if ( $uc_uid == - 1 )                     {
                        $uc_uid = uc_user_register($username, $password, $member_info['email']);                         if ( $uc_uid > 0 )
                        {
                            dbc()->Query("update " . TABLE_PREFIX . "system_memebrs set ucuid='{$uc_uid}' where `uid`='{$member_info['uid']}'");
                        }
                    }
                    else
                    {
                                                $uc_uid = $member_info['ucuid'];
                    }
                }
                if ( $uc_uid > 0 )
                {
                    $extend = uc_user_synlogin($uc_uid);                 }
            }
        }
        $check = handler('member')->CheckMember($username, $password);
        if ($check == -1)
        {
            return $this->ErrorInf(__('无法登录，用户密码错误，您可以有至多 5 次尝试。'));
        }
        elseif ($check == 0)
        {
            return $this->ErrorInf(__('无法登录，用户不存在，您可以有至多 5 次尝试。'));
        }
        $UserFields = handler('member')->GetMemberFields();
                handler('cookie')->SetVar('sid', '', - 365 * 86400 * 50);
        handler('member')->SessionExists = false;
        handler('member')->MemberFields['uid'] = $UserFields['uid'];
        handler('member')->session['uid'] = $UserFields['uid'];
        handler('member')->session['username'] = $UserFields['username'];
        $authcode = authcode("{$UserFields['password']}\t{$UserFields['secques']}\t{$UserFields['uid']}", 'ENCODE');
        if ( $keepLogin )
        {
            $expires = (int)ini('settings.cookie_expire') * 86400;
        }
        else
        {
            $expires = false;
        }
        handler('cookie')->SetVar('auth', $authcode, $expires);
        handler('cookie')->SetVar('cookietime', '2592000', $expires);
                return $this->SuccInf($extend);
    }
    public function Register($username, $password, $mail = '', $phone = '', $qq = '')
    {
        $UCuid = 0;
                if ( true === UCENTER )
        {
            include (UC_CLIENT_ROOT . './client.php');
                        $uc_result = uc_user_register($username, $password, $mail);
            if ( $uc_result < 0 )
            {
                if ( $uc_result > - 4 )
                {
                    return $this->ErrorInf(__('您输入的用户名无效或已被他人使用！'));
                }
                if ( $uc_result > - 7 )
                {
                    return $this->ErrorInf(__('您输入的Email地址无效或已被他人使用！'));
                }
                return $this->ErrorInf(__('注册失败！'));
            }
            else
            {
                $UCuid = $uc_result;
            }
        }
                $data = array(
            'username' => $username,
            'truename' => $username,
            'password' => md5($password),
            'phone' => (is_numeric($phone) ? $phone : ''),
            'email' => $mail,
            'role_id' => ini('settings.normal_default_role_id'),
            'checked' => ((ini('settings.default_emailcheck') == 1) ? 0 : 1),
            'finder' => handler('cookie')->GetVar('finderid'),
            'findtime' => handler('cookie')->GetVar('findtime'),
            'ucuid' => $UCuid,
            'regip' => client_ip(),
            'lastip' => client_ip(),
            'regdate' => time()
        );
        $iid = dbc(DBCMax)->insert('members')->data($data)->done();
                logic('notify')->Call($iid, 'logic.account.register.done', $data);
                return $this->SuccInf($iid);
    }
    
    public function loginReferer($ref = null)
    {
        if (is_null($ref))
        {
                        $ref = handler('cookie')->GetVar('loginref');
            if (!$ref || $ref == '')
            {
                return false;
            }
            else
            {
                handler('cookie')->SetVar('loginref', '', -1);
                return $ref;
            }
        }
        else
        {
                        handler('cookie')->SetVar('loginref', $ref);
        }
    }
    private function SuccInf($text)
    {
        return array(
            'error' => false,
            'result' => $text
        );
    }
    private function ErrorInf($text)
    {
        return array(
            'error' => true,
            'result' => $text
        );
    }
    
    public function GetFreeName($format = '{$UNAME$}')
    {
                $mkey = 'logic.account.freename.mc';
        $mf = fcache($mkey, 86400);
        if (!$mf)
        {
            $mf = dbc(DBCMax)->select('members')->in('COUNT(uid) AS MC')->limit(1)->done();
            fcache($mkey, $mf);
        }
        $mc = (int)$mf['MC'];
        if ($mc < 300)
        {
            $length = 2;
        }
        elseif ($mc < 10000)
        {
            $length = 3;
        }
        else
        {
                        $length = 4;
        }
        $rand = random($length);
        $username = str_replace('{$UNAME$}', $rand, $format);
        if ($this->Exists('name', $username))
        {
            return $this->GetFreeName($format);
        }
        return $username;
    }
    
    public function ulogin()
    {
        return loadInstance('logic.account.ulogin', 'AccountLogic_uLogin');
    }
}


class AccountLogic_uLogin
{
    
    public function wlist()
    {
        if (ini('alipay.account.login.enabled'))
        {
            $list = ini('alipay.account.login.source');
            include handler('template')->file('@account/login/union_list');
        }
    }
    
    public function linker($flag)
    {
        return driver('ulogin')->api($flag)->linker();
    }
    
    public function verify($flag)
    {
        $uid = driver('ulogin')->api($flag)->verify();
        return $uid ? 'ul.'.$flag.'.'.$uid : false;
    }
	
    public function ddata($flag)
    {
        $data = driver('ulogin')->api($flag)->transdata();
        if ($data['username'] == '' || account()->Exists('name', $data['username']))
        {
            $data['username'] = account()->GetFreename('ul.{$UNAME$}');
        }
        $data['password'] = random(18);
        
        return $data;
    }
    
    public function login($uuid)
    {
        $acf = meta($uuid);
        list($username, $password) = explode("\n", $acf);
                $lresult = account()->Login($username, $password, false);
                $this->mksource($uuid);
        return $lresult['result'];
    }
    
    public function active($uuid, $username, $password, $mail)
    {
        if (account()->Exists('name', $username))
        {
                        $username = account()->GetFreename('ul.{$UNAME$}');
        }
                $rresult = account()->Register($username, $password, $mail);
        if ($rresult['error'])
        {
            return false;
        }
                $mf = account()->Search('id', $rresult['result'], 1);
        $username = $mf['name'];
                list($action, $source, $luid) = explode('.', $uuid);
        meta('luid_'.$rresult['result'], $luid);
                $acf = $username."\n".$password."\n".$rresult['result'];
        meta($uuid, $acf);
                return $rresult['result'];
    }
    
    public function token($luid = null, $token = null)
    {
    	if (is_null($token))
    	{
    		if (is_null($luid))
    		{
    			    			$uid = user()->get('id');
    			$luid = meta('luid_'.$uid);
    		}
    		return meta('token_'.$luid);
    	}
    	    	meta('token_'.$luid, $token);
    }
    
    private function mksource($uuid)
    {
    	list($action, $source, $luid) = explode('.', $uuid);
    	handler('cookie')->SetVar('loginSource', $source);
    }
}

?>