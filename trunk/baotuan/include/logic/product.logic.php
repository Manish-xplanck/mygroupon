<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename product.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


class ProductLogic
{
    
    function GetOne( $id )
    {
        $ckey = 'product.getone.'.$id;
        $list = cached($ckey);
        if ($list) return $list;
        $pid = ( int )$id;
        $sql = '
        SELECT
        	p.*,s.sellername,s.sellerphone,s.selleraddress,s.sellerurl,s.sellermap
  		FROM
  			' . table('product') . ' p
  		LEFT JOIN ' . table('seller') . ' s
  		ON
  			(p.sellerid = s.id)
		WHERE
			p.id = ' . $pid;
        $result = dbc()->Query($sql)->GetRow();
        $list = $this->__parse_result($result);
        return cached($ckey, $list);
    }
    
    public function GetFirst()
    {
        $list = $this->GetList(logic('misc')->City('id'), PRO_ACV_Yes);
        return $list[0];
    }
    
    function GetList( $cid = -1, $actived = null, $extend = '1' )
    {
        $sql_limit_city = '1';
        if ( $cid > 0 )
        {
            $sql_limit_city = '(p.display = '.PRO_DSP_Global.' OR (p.display = '.PRO_DSP_City.' AND p.city = ' . $cid . ') )';
        }
        $sql_limit_actived = '1';
        $now = time();
        if ( !is_null($actived) )
        {
            if ($actived === PRO_ACV_Yes)
            {
                $sql_limit_actived = 'p.begintime < ' . $now . ' AND p.overtime > ' . $now;
            }
            else
            {
                $sql_limit_actived = 'p.overtime < ' . $now;
            }
        }
        $sql = '
        SELECT
        	p.*,s.sellername,s.sellerphone,s.selleraddress,s.sellerurl,s.sellermap,p.totalnum
        FROM
  			' . table('product') . ' p
  		LEFT JOIN ' . table('seller') . ' s
        ON
        	(p.sellerid=s.id)
        WHERE
        	' . $sql_limit_actived . '
        AND
        	' . $sql_limit_city . '
        AND
    		' . $extend . '
        ORDER BY
        	p.order DESC, p.id
        DESC';
        logic('isearcher')->Linker($sql);
        $sql = page_moyo($sql);
        $result = dbc()->Query($sql)->GetAll();
        return $this->__parse_result($result);
    }
    
    public function SrcOne($id)
    {
        return dbc(DBCMax)->select('product')->where('id='.$id)->limit(1)->done();
    }
    
    public function Where($sql_limit)
    {
        $sql = '
        SELECT
            *
        FROM
            '.table('product').'
        WHERE
            '.$sql_limit.'
        ';
        return dbc()->Query($sql)->GetAll();
    }
    
    public function Update($id, $array)
    {
        dbc()->SetTable(table('product'));
        dbc()->Update($array, 'id = '.$id);
    }
    
    public function Delete($id)
    {
        $p = $this->SrcOne($id);
        $imgs = explode(',', $p['img']);
        foreach ($imgs as $i => $iid)
        {
            logic('upload')->Delete($iid);
        }
        dbc(DBCMax)->delete('product')->where('id='.$id)->done();
                $sqls = array(
                        'DELETE FROM '.table('finder').' WHERE productid='.$id,
                        'DELETE FROM '.table('ticket').' WHERE productid='.$id,
        );
                $orderList = logic('order')->Where('productid='.$id);
        foreach ($orderList as $i => $order)
        {
            $oid = $order['orderid'];
                        $sqls[] = 'DELETE FROM '.table('order').' WHERE orderid='.$oid;
                        $sqls[] = 'DELETE FROM '.table('order_clog').' WHERE sign='.$oid;
                        $sqls[] = 'DELETE FROM '.table('paylog').' WHERE sign='.$oid;
        }
        foreach ($sqls as $i => $sql)
        {
            dbc()->Query($sql);
        }
        logic('seller')->product_del($p['sellerid']);
        return true;
    }
    
    public function Publish($data)
    {
        logic('seller')->product_add($data['sellerid']);
        dbc()->SetTable(table('product'));
        return dbc()->Insert($data);
    }
    
    function MoneySaves()
    {
        $now = time();
        $sql = '
        SELECT
        	SUM((price-nowprice)*(virtualnum+totalnum)) AS saves
        FROM
        	' . table('product') . '
    	WHERE
			overtime < ' . $now . '
		AND
			status = 2';
        $result = dbc()->Query($sql)->GetRow();
        return $result['saves'];
    }
    
    function SellsCount( $id )
    {
        $sql = '
        SELECT
        	SUM(productnum) AS sums
        FROM
        	' . table('order') . '
        WHERE
        	productid=' . $id . '
        AND
        	pay=1';
        $result = dbc()->Query($sql)->GetRow();
        return (int)$result['sums'];
    }
    
    function BuyersCount( $id )
    {
        $sql = '
        SELECT
        	COUNT(*)
        FROM
        	' . table('order') . '
    	WHERE
    		productid = ' . intval($id) . '
    	AND
    		pay = 1';
        $query = dbc()->Query($sql);
        $result = $query->GetRow();
        return $result['count(*)'];
    }
    
    function Surplus( $maxnum, $id )
    {
        $sql = 'select sum(productnum) from ' . TABLE_PREFIX . 'tttuangou_order where productid = ' . intval($id) . ' and pay = 1';
        $query = dbc()->Query($sql);
        $result = $query->GetRow();
        $num = $result['sum(productnum)'];
        $surplusnum = $maxnum - $num;
        return $surplusnum;
    }
    
    function BuysCheck( $id )
    {
        if (!$id)
        {
            return array(
                'false' => __('请选择你要购买的产品！')
            );
        }
        $sql = '
        SELECT
        	*
        FROM
        	' . table('product') . '
        WHERE
        	id = ' . $id;
        $query = dbc()->Query($sql);
        $error = '';
        if ( ! $query ) $error = __('没有找到相应的产品！');
        $product = $query->GetRow();
        $now = time();
        if ( $product['begintime'] > $now ) $error = __('团购还没有开始哦！');
        if ( $product['overtime'] < $now ) $error = __('团购已经结束了哦！');
        if ( $product['maxnum'] > 0 )
        {
        	if ( $this->SellsCount($id) >= $product['maxnum'] ) $error = __('该产品已经卖完了！下次请赶早');
        }
        if ( $product['multibuy'] == 'false' )
        {
            if ( $this->AlreadyBuyed($id, user()->get('id')) ) $error = __('您已经购买过此产品了哦！');
        }
        if ( $error != '' )
        {
            return array( 
                'false' => $error 
            );
        }
        return $this->__parse_result($product);
    }
    
    function AlreadyBuyed( $id, $uid )
    {
        $sql = '
        SELECT
        	orderid
        FROM
        	' . table('order') . '
        WHERE
        	productid = ' . $id . '
    	AND
    		userid= ' . $uid . '
		AND
			pay=1';
        $result = dbc()->Query($sql)->GetRow();
        return $result ? true : false;
    }
    
    function Maintain($pid)
    {
        $now = time();
        $sqls = array(
                        'UPDATE '.table('product').' SET status='.PRO_STA_Failed.' WHERE successnum>(virtualnum+totalnum) AND overtime<'.$now.' AND begintime<'.$now,
                        'UPDATE '.table('product').' SET status='.PRO_STA_Finish.' WHERE successnum<=(virtualnum+totalnum) AND overtime<'.$now.' AND begintime<'.$now,
                        'UPDATE '.table('product').' SET status='.PRO_STA_Normal.' WHERE successnum>(virtualnum+totalnum) AND overtime>'.$now.' AND begintime<'.$now,
                        'UPDATE '.table('product').' SET status='.PRO_STA_Success.' WHERE successnum<=(virtualnum+totalnum) AND overtime>'.$now.' AND begintime<'.$now,
        );
        foreach ($sqls as $i => $sql)
        {
            dbc()->Query($sql);
        }
        $product = logic('product')->GetOne($pid);
        if ($product['succ_remain'] <= 0)
        {
                        logic('order')->findSuccess($pid);
        }
    }
    
    private function __parse_result( $product )
    {
        if ( ! $product ) return false;
        if ( is_array($product[0]) )
        {
            $returns = array();
            foreach ( $product as $i => $one )
            {
                $returns[] = $this->__parse_result($one);
            }
            return $returns;
        }
                $product['price'] *= 1;
        $product['nowprice'] *= 1;
        if ( $product['nowprice'] > 0 )
        {
            $product['discount'] = round(10 / ($product['price'] / $product['nowprice']), 1);
        }
        else
        {
            $product['discount'] = 0;
        }
        if ( $product['discount'] <= 0 ) $product['discount'] = 0;
        $product['time_remain'] = $product['overtime'] - time();
        $product['succ_total'] = $product['successnum'];
        $product['succ_real'] = $product['totalnum'];
        $product['succ_buyers'] = $product['totalnum'] + $product['virtualnum'];
        $product['succ_remain'] = $product['successnum'] - $product['succ_buyers'];
                $product['sells_real'] = $this->SellsCount($product['id']);
        $product['sells_count'] = $product['virtualnum'] + $product['sells_real'];
            	if ($product['oncemin'] <= 0)
        {
            $product['oncemin'] = 1;
        }
                if ( $product['maxnum'] > 0 )
        {
            $product['surplus'] = $this->Surplus($product['maxnum'], $product['id']);
        }
        else
        {
        	            $product['surplus'] = 9999;
        }
        
                $product['imgs'] = ($product['img'] != '') ? explode(',', $product['img']) : null;
		$product['img'] = $product['imgs'][0];
                $product['sellermap'] = explode(',', $product['sellermap']);
                if ($product['type'] == 'stuff')
        {
            $product['weightunit'] = ($product['weight'] >= 1000) ? 'kg' : 'g';
            $product['weight'] *= ($product['weightunit'] == 'kg') ? 0.001 : 1;
        }
        return $product;
    }
    
    public function AVParser(&$product)
    {
        if ( ! $product ) return false;
        if ( is_array($product[0]) )
        {
            $returns = array();
            foreach ( $product as $i => &$one )
            {
                $this->AVParser($one);
            }
            return;
        }
        $base = 'productid='.$product['id'];
        $STA_Normal = 'status='.ORD_STA_Normal;
        $product['mny_all'] = (float)logic('order')->Summary($base);
        $product['mny_paid'] = (float)logic('order')->Summary($base.' AND pay='.ORD_PAID_Yes.' AND '.$STA_Normal);
        $product['mny_waited'] = (float)logic('order')->Summary($base.' AND pay='.ORD_PAID_No.' AND '.$STA_Normal);
        $product['mny_refund'] = (int)logic('order')->Summary($base.' AND status='.ORD_STA_Refund);
    }
    
    public function STA_Name($STA_Code)
    {
        $STA_NAME_MAP = array(
            PRO_STA_Failed => '已经结束，团购失败',
            PRO_STA_Normal => '正在进行中，未成团',
            PRO_STA_Success => '正在进行中，已成团',
            PRO_STA_Finish => '已经结束，团购成功',
            PRO_STA_Refund => '已经结束，已经返款'
        );
        return $STA_NAME_MAP[$STA_Code];
    }

    
	function productCheck($id,$city=''){		$id = (is_numeric($id) ? $id : 0);
		$now = time();
		if($city!=''){
			$sql='select * from '.TABLE_PREFIX.'tttuangou_product where begintime <= '.$now.' and overtime > '.$now.' and id = '.$id.' and (city = '.floatval($city).' or display = 2)';
		}else{
			$sql='select * from '.TABLE_PREFIX.'tttuangou_product where begintime <= '.$now.' and overtime > '.$now.' and id = '.$id;
		}
		$query = dbc()->Query($sql);
		if (!$query)
		{
			return false;
		}
		$product=$query->GetRow();
				$product['price'] *= 1;
		$product['nowprice'] *= 1;
		return $product;
	}
    function AddSellerProNum($sellerid){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set productnum = productnum + 1 where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
		return true;
	}
	function DelSellerProNum($sellerid){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set productnum = productnum - 1 where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
		return true;
	}
	function AddSellerSucNum($sellerid){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set successnum = successnum + 1 where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
		return true;
	}
	function AddSellerTotMoney($sellerid,$money){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set money = money + '.$money.' where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
	}
function delSellerTotMoney($sellerid,$money){
		$sql='update '.TABLE_PREFIX.'tttuangou_seller set money = money - '.$money.' where id = '.floatval($sellerid);
		$query = dbc()->Query($sql); 
	}
	
}
?>