<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename delivery.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


class DeliveryLogic
{
    
    public function GetList($alsend = DELIV_SEND_No)
    {
        $sql_limit_status = '1';
        if ($alsend == DELIV_SEND_Yes)
        {
            $sql_limit_status = 'o.process IN("WAIT_BUYER_CONFIRM_GOODS","TRADE_FINISH")';
        }
        elseif ($alsend == DELIV_SEND_No)
        {
            $sql_limit_status = 'o.process="WAIT_SELLER_SEND_GOODS"';
        }
        $sql = '
        SELECT
        	o.*, p.flag
    	FROM
    		'.table('order').' o
    	LEFT JOIN
    		'.table('product').' p
    	ON
    		o.productid = p.id
    	WHERE
    		p.type="stuff"
    	AND
    		'.$sql_limit_status.'
    	ORDER BY
    		o.paytime
    	DESC';
        logic('isearcher')->Linker($sql);
        $sql = page_moyo($sql);
        $result = dbc()->Query($sql)->GetAll();
        foreach ($result as $i => $order)
        {
            $product = array('flag'=>$result[$i]['flag']);
            unset($result[$i]['flag']);
            $result[$i]['product'] = $product;
            $result[$i]['express'] = logic('express')->SrcOne($order['expresstype']);
            $result[$i]['address'] = logic('address')->GetOne($order['addressid']);
        }
        return $result;
    }
	
    public function Send($oid, $invoice)
    {
        logic('order')->Update($oid, array(
            'invoice' => $invoice,
            'expresstime' => time()
        ));
        $order = logic('order')->SrcOne($oid);
                logic('pay')->SendGoods($order, true);
        return true;
    }
}
?>