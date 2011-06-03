<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename index.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


class ModuleObject extends MasterObject
{
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    function Main()
    {
        $clientUser = get('u', 'int');
        if ( $clientUser != '' )
        {
            handler('cookie')->setVar('finderid', $clientUser);
            handler('cookie')->setVar('findtime', time());
        }
        $productID = get('view', 'int');
        if ( false == $productID )
        {
            $mutiView = true;
                        $_GET[EXPORT_GENEALL_FLAG] = EXPORT_GENEALL_VALUE;
            $product = logic('product')->GetList(logic('misc')->City('id'), PRO_ACV_Yes);
        }
        else
        {
            $mutiView = false;
            $product = logic('product')->GetOne($productID);
        }
        if ( false == $product )
        {
                        header('Location: '.rewrite('?mod=subscribe&code=mail'));
        }
        if ( $mutiView && count($product) == 1 )
        {
            $mutiView = false;
            $product = $product[0];
                        $_GET['view'] = $product['id'];
        }
        $this->Title = $mutiView ? '' : $product['name'];
                $this->MetaKeywords = ini('settings.index_meta_keywords');
        $this->MetaDescription = ini('settings.index_meta_description');
                $file = $mutiView ? 'home' : 'detail';
                include handler('template')->file($file);
    }
    function Confirm()
    {
        $pwd = get('pwd');
        if ( $pwd == '' ) $this->Messager(__("错误！"));
        $pwd = authcode(urldecode($pwd), 'DECODE', $this->Config['auth_key']);
        $sql = 'select * from ' . TABLE_PREFIX . 'system_members where truename = \'' . $pwd . '\'';
        $query = $this->DatabaseHandler->Query($sql);
        $user = $query->GetRow();
        if ( $user == '' || $user['checked'] == 1 ) $this->Messager(__("用户不存在或已经通过验证！"));
        $ary = array( 
            'checked' => 1 
        );
        $this->DatabaseHandler->SetTable(TABLE_PREFIX . 'system_members');
        $result = $this->DatabaseHandler->Update($ary, 'truename = \'' . $pwd . '\'');
        $this->Messager(__("邮箱认证成功！"), "?");
    }
    function ExpressConfirm()
    {
        $oid = $this->Get['id'];
        $result = $this->OrderLogic->orderExpressConfirm($oid);
        if ( $result )
        {
            $this->Messager(__('已经确认收货，本次交易完成！'), '?mod=me&code=order');
        }
        else
        {
            $this->Messager(__('无效的订单号！'), '?mod=me&code=order');
        }
    }
}
?>