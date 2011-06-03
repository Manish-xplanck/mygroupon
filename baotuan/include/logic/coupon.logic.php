<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename coupon.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 



class CouponLogic
{
    
    public function GetOne($id)
    {
        $sql = '
        SELECT
        	t.*, p.name, p.flag, p.intro, p.perioddate
        FROM
        	' . table('ticket').' t
        LEFT JOIN
        	' . table('product').' p
        ON
        	(t.productid = p.id)
        WHERE
        	t.ticketid='.$id;
        return dbc()->Query($sql)->GetRow();
    }
    
    public function GetList( $uid = 0, $oid = 0, $status = 0 )
    {
        $sql_limit_user = '1';
        if ($uid > 0)
        {
            $sql_limit_user = 't.uid = '.$uid;
        }
        $sql_limit_order = '1';
        if ($oid > 0)
        {
            $sql_limit_order = 't.orderid = '.$oid;
        }
        $sql_limit_status = 't.status = '.$status;
        if ($status < 0)
        {
            $sql_limit_status = '1';
        }
        $sql = '
        SELECT
        	t.*, p.name, p.flag, p.intro, p.perioddate
        FROM
        	' . table('ticket').' t
        LEFT JOIN
        	' . table('product').' p
        ON
        	(t.productid = p.id)
        WHERE
        	'.$sql_limit_user.'
        AND
        	'.$sql_limit_order.'
        AND
        	' . $sql_limit_status . '
        ORDER BY
        	t.ticketid
        DESC';
        logic('isearcher')->Linker($sql);
        $sql = page_moyo($sql);
        return dbc()->Query($sql)->GetAll();
    }
    public function SrcOne($id)
    {
        $result = dbc(DBCMax)->select('ticket')->where('ticketid='.$id)->done();
        return $result[0];
    }
    public function SrcList($uid = 0, $oid = 0, $status = 0)
    {
        $sql_limit_user = '1';
        if ($uid > 0)
        {
            $sql_limit_user = 'uid = '.$uid;
        }
        $sql_limit_order = '1';
        if ($oid > 0)
        {
            $sql_limit_order = 'orderid = '.$oid;
        }
        $sql_limit_status = 'status = '.$status;
        if ($status < 0)
        {
            $sql_limit_status = '1';
        }
        $sql = '
        SELECT
        	*
        FROM
        	' . table('ticket').'
        WHERE
        	'.$sql_limit_user.'
        AND
        	'.$sql_limit_order.'
        AND
        	' . $sql_limit_status . '
        ORDER BY
        	ticketid
        DESC';
        return dbc()->Query($sql)->GetAll();
    }
    
    public function STA_Name($STA_Code)
    {
        $STA_NAME_MAP = array(
            TICK_STA_Unused => '还未使用',
            TICK_STA_Used => '已经使用',
            TICK_STA_Overdue => '已经过期',
            TICK_STA_Invalid => '号码无效'
        );
        return $STA_NAME_MAP[$STA_Code];
    }
    
    public function Create($pid, $oid, $uid, $mutis = 1, $number = false, $password = false)
    {
        $number = $number ? $number : $this->__random_num(12);
        $password = $password ? $password : $this->__random_num(6);
        $data = array
        (
            'uid' => $uid,
            'productid' => $pid,
        	'orderid' => $oid,
    		'number' => $number,
    		'password' => $password,
            'mutis' => $mutis,
			'status' => 0
        );
        dbc()->SetTable(table('ticket'));
        dbc()->Insert($data);
        logic('coupon')->Create_OK($uid, $data);
    }
    
    public function Create_OK($uid, $data)
    {
        logic('notify')->Call($uid, 'logic.coupon.Create', $data);
    }
    
    public function Delete($id)
    {
        return dbc(DBCMax)->delete('ticket')->where('ticketid='.$id)->done();
    }
    
    public function __random_num($length = 12)
    {
        $length = (int)$length;
        $loops = ceil($length / 3);
        $string = '';
        for ( $i=0; $i<$loops; $i++ )
        {
            $string .= (string)mt_rand(100, 999);
        }
        $string = substr($string, 0, $length);
        return $string;
    }
}
?>