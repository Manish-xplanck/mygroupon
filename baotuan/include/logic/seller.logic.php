<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename seller.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class SellerLogic
{
    public function money_add($sid, $money)
    {
        dbc(DBCMax)
            ->update('seller')
            ->data('money = money + '.$money)
            ->where('id='.$sid)
        ->done();
    }
    public function money_less($sid, $money)
    {
        dbc(DBCMax)
            ->update('seller')
            ->data('money = money - '.$money)
            ->where('id='.$sid)
        ->done();
    }
    public function order_success($sid)
    {
        dbc(DBCMax)
            ->update('seller')
            ->data('successnum = successnum + 1')
            ->where('id='.$sid)
        ->done();
    }
    public function order_failed($sid)
    {
        dbc(DBCMax)
            ->update('seller')
            ->data('successnum = successnum - 1')
            ->where('id='.$sid)
        ->done();
    }
    public function product_add($sid)
    {
        dbc(DBCMax)
            ->update('seller')
            ->data('productnum = productnum - 1')
            ->where('id='.$sid)
        ->done();
    }
    public function product_del($sid)
    {
        dbc(DBCMax)
            ->update('seller')
            ->data('productnum = productnum - 1')
            ->where('id='.$sid)
        ->done();
    }
}
?>