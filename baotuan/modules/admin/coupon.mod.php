<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename coupon.mod.php $
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
        header('Location: ?mod=coupon&code=vlist');
    }
    function vList()
    {
        $list = logic('coupon')->GetList(USR_ANY, ORD_ID_ANY, TICK_STA_ANY);
        include handler('template')->file('@admin/coupon_list');
    }
    function Add()
    {
        $uid = get('uid', 'int');
        $pid = get('pid', 'int');
        $oid = get('oid', 'number');
        include handler('template')->file('@admin/coupon_add');
    }
    function Add_save()
    {
        $uid = get('uid', 'int');
        $pid = get('pid', 'int');
        $oid = get('oid', 'number');
        $number = get('number', 'number');
        if (!$number || strlen($number) != 12) $number = false;
        $password = get('password', 'number');
        if (!$password || strlen($password) != 6) $password = false;
        $mutis = get('mutis', 'int');
        if (!$mutis) $mutis = 1;
        logic('coupon')->Create($pid, $oid, $uid, $mutis, $number, $password);
        exit('ok');
    }
    function Alert()
    {
        $id = get('id', 'int');
        $c = logic('coupon')->GetOne($id);
        logic('notify')->Call($c['uid'], 'admin.mod.coupon.Alert', $c);
        exit('ok');
    }
    function Reissue()
    {
        $id = get('id', 'int');
        $c = logic('coupon')->SrcOne($id);
        $uid = $c['uid'];
        $data = array
        (
            'uid' => $c['uid'],
            'productid' => $c['productid'],
        	'orderid' => $c['orderid'],
    		'number' => $c['number'],
    		'password' => $c['password'],
            'mutis' => $c['mutis'],
			'status' => $c['status']
        );
        logic('coupon')->Create_OK($uid, $data);
        exit('ok');
    }
    function Delete()
    {
        $id = get('id', 'int');
        logic('coupon')->Delete($id);
        exit('ok');
    }
}

?>