<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename express.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ExpressLogic
{
    
    function html( $data )
    {
        switch (mocod())
        {
            case 'buy.checkout':
                if ($data['type'] != 'stuff') return;
                handler('template')->load('@html/express_selector');
                break;
            case 'buy.order':
                if ($data['product']['type'] != 'stuff') return;
                $EID = $data['expresstype'];
                include handler('template')->file('@html/express_displayer');
                break;
        }
    }
    
    function GetOne( $id, $aid )
    {
        $sql = '
        SELECT
            *
        FROM
            ' . table('express') .'
        WHERE
            id = '.$id.'
        AND
        	regiond = 0
        ';
        $rs = dbc()->Query($sql)->GetRow();
        if (empty($rs))
        {
            $address = logic('address')->GetOne($aid, user()->get('id'));
            list( , $province, $city, $country, ) = explode(',', $address['region_loc']);
            $likes = 'LIKE "%[,'.$province.',]%" OR "%[,'.$province.','.$city.',]%" OR "%[,'.$province.','.$city.','.$country.',]%"';
            $sql = '
            SELECT
            	e.*, ea.*, e.firstprice AS fp, e.continueprice AS cp
            FROM
            	'.table('express_area').' ea
            RIGHT JOIN
            	'.table('express').' e
            ON
    			e.id = ea.parent
    		WHERE
    			e.id = '.$id.'
    		AND (
    			dpenable="true"
    		OR
    			ea.region '.$likes.'
    		)';
            $rs = dbc()->Query($sql)->GetRow();
        }
        return $rs;
    }
    
    function GetList($aid = 0)
    {
        $result = array();
                $sql = 'SELECT * FROM '.table('express').' WHERE enabled="true" AND regiond=0 ORDER BY `order` ASC';
        $rs = dbc()->Query($sql)->GetAll();
        $noIDs = '0,';
        foreach ($rs as $i => $one)
        {
            $noIDs .= $one['id'].',';
        }
        $noIDs = '('.substr($noIDs, 0, -1).')';
        $result = array_merge($result, $rs);
                $address = logic('address')->GetOne($aid, user()->get('id'));
        list( , $province, $city, $country, ) = explode(',', $address['region_loc']);
        $allLikes = array(
            '[,'.$province.',]',
            '[,'.$province.','.$city.',]',
            '[,'.$province.','.$city.','.$country.',]'
        );
        $likes = '';
        foreach ($allLikes as $i => $eaLike)
        {
            $likes .= 'ea.region LIKE "%'.$eaLike.'%" OR ';
        }
        $likes = substr($likes, 0, -4);
        $sql = '
        SELECT
        	e.*, ea.*, e.firstprice AS fp, e.continueprice AS cp
        FROM
        	'.table('express_area').' ea
        RIGHT JOIN
        	'.table('express').' e
        ON
			e.id = ea.parent
		WHERE
		(
			dpenable="true"
		OR
			'.$likes.'
		)
		AND
			ea.parent NOT IN '.$noIDs.'
		';
        $rs = dbc()->Query($sql)->GetAll();
        $filter = array();
        foreach ($rs as $i => $one)
        {
        	if (isset($filter[$one['name']]))
        	{
        		$f = $filter[$one['name']];
        		if ($f['fp'] == $one['fp'] && $f['cp'] == $one['cp'])
        		{
        			        			unset($rs[$i]);
        			continue;
        		}
        	}
        	else
        	{
        		$filter[$one['name']] = $one;
        	}
            $region = '<'.$one['region'].'>';
            if (
                !strpos($region, '[,'.$province.',]') &&
                !strpos($region, '[,'.$province.','.$city.',]') &&
                !strpos($region, '[,'.$province.','.$city.','.$country.',]')
            )
            {
                $rs[$i]['firstprice'] = $one['fp'];
                $rs[$i]['continueprice'] = $one['cp'];
            }
        }
        $result = array_merge($result, $rs);
                $return = array();
        foreach ($result as $i => $one)
        {
            $return[] = array(
                'id' => $one['id'],
                'name' => $one['name'],
                'firstunit' => $one['firstunit'],
                'firstprice' => $one['firstprice'],
                'continueunit' => $one['continueunit'],
                'continueprice' => $one['continueprice'],
                'detail' => $one['detail']
            );
        }
        return $return;
    }
    
    function AdmOne($id)
    {
        $sql = 'SELECT * FROM '.table('express').' WHERE id='.$id;
        $c = dbc()->Query($sql)->GetRow();
                $c['fuu'] = 'g';
        if ($c['firstunit'] >= 1000)
        {
            $c['firstunit'] *= 0.001;
            $c['fuu'] = 'kg';
        }
        $c['cuu'] = 'g';
        if ($c['continueunit'] >= 1000)
        {
            $c['continueunit'] *= 0.001;
            $c['cuu'] = 'kg';
        }
        $c['firstprice'] *= 1;
        $c['continueprice'] *= 1;
        
        $sql = 'SELECT * FROM '.table('express_area').' WHERE parent='.$c['id'];
        $regions = dbc()->Query($sql)->GetAll();
        foreach ($regions as $i => $one)
        {
            $regions[$i]['firstprice'] *= 1;
            $regions[$i]['continueprice'] *= 1;
            $alist = explode('][', $one['region']);
            $regionsName = array();
            foreach ($alist as $ix => $area)
            {
                $area = trim(preg_replace('/[\[\]]/', '', $area));
                if ($area == '') continue;
                $A = $this->AreaGet($area);
                $regionsName[$ix] = array(
                    'name' => $A['name'],
                    'loc' => $area
                );
            }
            $regions[$i]['regionName'] = $regionsName;
        }
        $c['regions'] = $regions;
        return $c;
    }
    
    function SrcOne($id)
    {
        $sql = 'SELECT * FROM '.table('express').' WHERE id='.$id;
        return dbc()->Query($sql)->GetRow();
    }
    
    function SrcList()
    {
        $sql = 'SELECT * FROM '.table('express');
        return dbc()->Query($sql)->GetAll();
    }
    
    function CorpList($enabled = 'true')
    {
        $sql_limit_enabled = 'enabled="'.$enabled.'"';
        if ($enabled == 'all')
        {
            $sql_limit_enabled = '1';
        }
        $sql = 'SELECT * FROM '.table('express_corp').' WHERE '.$sql_limit_enabled;
        return dbc()->Query($sql)->GetAll();
    }
    
    function Del($id)
    {
        dbc()->SetTable(table('express'));
        dbc()->Delete('', 'id='.$id);
        dbc()->SetTable(table('express_area'));
        dbc()->Delete('', 'parent='.$id);
    }
    
    function AreaGet($path)
    {
        $sql = 'SELECT * FROM '.table('regions').' WHERE path = "'.$path.'"';
        return dbc()->Query($sql)->GetRow();
    }
    
    function AreaDel($id)
    {
        dbc()->SetTable(table('express_area'));
        dbc()->Delete('', 'id='.$id);
    }
    
    
        function orderExpressUpdate($orderid, $invoice)
	{
		$ary = array(
			'invoice'=>$invoice,
			'expresstime'=>time(),
			'status'=>4 		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_order');
		$this->DatabaseHandler->Update($ary, 'orderid='.$orderid);
		return true;
	}

	function orderWaitExpressCount($productid=0, $sql_search='')
	{
		if ($productid>0)
		{
			$limit = 'productid='.$productid;
		}
		else
		{
			$limit = 'status=1';
		}
		$sql='select count(orderid) AS count from '.TABLE_PREFIX.'tttuangou_order where pay = 1 and addressid <> 0 and '.$limit.' '.$sql_search;
		$query = $this->DatabaseHandler->Query($sql);
		$orderCount=$query->GetRow();
		return $orderCount['count'];
	}
	
	function orderSentExpressCount($productid=0, $sql_search='')
	{
		if ($productid>0)
		{
			$limit = 'productid='.$productid;
		}
		else
		{
			$limit = 'status IN(4,9)';
		}
		$sql='select count(orderid) AS count from '.TABLE_PREFIX.'tttuangou_order where pay = 1 and addressid <> 0 and '.$limit.' '.$sql_search;
		$query = $this->DatabaseHandler->Query($sql);
		$orderCount=$query->GetRow();
		return $orderCount['count'];
	}

	function orderWaitExpressList($productid=0, $page=0, $epage=20, $sql_search='')
	{
		$condition = '1';
		if ($productid > 0)
		{
			$condition = 'o.productid='.$productid;
		}
		$limit = '';
		if ($page > 0)
		{
			$limit = ' LIMIT '.((int)$page-1)*$epage.','.$epage;
		}
		$sql='SELECT p.name,p.successnum,o.orderid,o.addressid,m.username,o.paytime FROM '.TABLE_PREFIX.'tttuangou_order o LEFT JOIN '.TABLE_PREFIX.'system_members m ON m.uid=o.userid LEFT JOIN '.TABLE_PREFIX.'tttuangou_product p ON p.id=o.productid WHERE p.type = "stuff" AND o.pay = 1 AND p.status IN(0,1,2) AND o.status = 1 AND '.$condition.' '.$sql_search.' ORDER BY o.paytime ASC'.$limit;
		return $this->DatabaseHandler->Query($sql)->GetAll();
	}

	function orderSentExpressList($productid=0, $page=0, $epage=20, $sql_search='')
	{
		$condition = '1';
		if ($productid > 0)
		{
			$condition = 'o.productid='.$productid;
		}
		$limit = '';
		if ($page > 0)
		{
			$limit = ' LIMIT '.((int)$page-1)*$epage.','.$epage;
		}
		$sql='SELECT p.name,p.successnum,o.orderid,o.addressid,m.username,o.expresstime FROM '.TABLE_PREFIX.'tttuangou_order o LEFT JOIN '.TABLE_PREFIX.'system_members m ON m.uid=o.userid LEFT JOIN '.TABLE_PREFIX.'tttuangou_product p ON p.id=o.productid WHERE p.type = "stuff" AND o.pay = 1 AND p.status IN(0,1,2) AND o.status IN(4,9) AND '.$condition.' '.$sql_search.' ORDER BY o.expresstime DESC'.$limit;
		return $this->DatabaseHandler->Query($sql)->GetAll();
	}

	function orderExpressConfirm($oid)
	{
		$ary = array(
			'status'=>9
		);
		$this->DatabaseHandler->SetTable(TABLE_PREFIX.'tttuangou_order');
		return $this->DatabaseHandler->Update($ary, 'orderid='.$oid.' AND userid='.MEMBER_ID);
	}
    
    function Accessed($class, &$data)
    {
        if ($class == 'order.save')
        {
            $id = post('express_id', 'int');
            if (!$id || $data['addressid'] == 0)
            {
                                                $data['expressprice'] = 9999;
                return;
            }
            $data['expresstype'] = $id;
            $express = $this->GetOne($id, $data['addressid']);
            $product = logic('product')->BuysCheck($data['productid']);
            $allWeight = $data['productnum'] * $product['weight'];
            $price = $express['firstprice'];
            if ($allWeight > $express['firstunit'])
            {
                $lessWeight = $allWeight - $express['firstunit'];
                if ($express['continueunit'] <= 0)
                {
                    $express['continueunit'] = 1;
                }
                $price += ceil($lessWeight / $express['continueunit']) * $express['continueprice'];
            }
            $data['expressprice'] = $price;
        }
        elseif ($class == 'order.show')
        {
            if ($data['product']['type'] == 'ticket') return;
            $data['price_of_total'] += $data['expressprice'];
        }
    }
}
?>