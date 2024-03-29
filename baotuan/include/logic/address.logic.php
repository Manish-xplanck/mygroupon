<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename address.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class AddressLogic
{

    
    function html( $data )
    {
        switch (mocod())
        {
            case 'buy.checkout':
                if ( $data['type'] != 'stuff' ) return;
                handler('template')->load('@html/address_selector');
                break;
            case 'buy.order':
                if ( $data['product']['type'] != 'stuff' ) return;
                $addressID = $data['addressid'];
                include handler('template')->file('@html/address_displayer');
                break;
        }
    }
    
    function GetOne( $id, $uid = 0 )
    {
    	$sql_limit_user = '1';
    	if ($uid > 0)
    	{
    		$sql_limit_user = 'owner = '.$uid;
    	}
        $sql = '
        SELECT
        	*
        FROM
        	' . table('address') . '
        WHERE
        	id=' . $id .'
        AND
        	'.$sql_limit_user;
        $result = dbc()->Query($sql)->GetRow();
        return $this->__parse_region($result);
    }
    
    function GetList( $uid = 0 )
    {
        $sql_limit_user = '1';
        if ( $uid > 0 )
        {
            $sql_limit_user = 'owner = ' . $uid;
        }
        $sql = '
        SELECT
        	*
    	FROM
    		' . table('address') . '
		WHERE
			' . $sql_limit_user . '
		ORDER BY
			lastuse
		DESC';
        $result = dbc()->Query($sql)->GetAll();
        return $this->__parse_region($result);
    }
    
    private function __parse_region($data)
    {
        if ( ! $data ) return false;
        if ( is_array($data[0]) )
        {
            $return = array();
            foreach ( $data as $i => $one )
            {
                $return[] = $this->__parse_region($one);
            }
            return $return;
        }
        $regions = substr(substr($data['region'], 1), 0, -1);
        $regions || $regions = 1;
        $sql = '
        SELECT
        	name
        FROM
        	'.table('regions').'
        WHERE
        	id IN('.$regions.')
        ';
        $result = dbc()->Query($sql)->GetAll();
        $name = '';
        foreach ($result as $i => $one)
        {
            $name .= $one['name'].' ';
        }
        $region_loc = $data['region'];
        $data['region'] = $name;
        $data['region_loc'] = $region_loc;
        return $data;
    }
    
    function Used( $id )
    {
        $ary = array( 
            'lastuse' => time() 
        );
        dbc()->SetTable(table('address'));
        dbc()->Update($ary, 'id=' . $id);
        return true;
    }
    
    function Add( $uid = 0, $array )
    {
        if ($uid == 0)
        {
            $uid = user()->get('id');
        }
        $rid = $this->__Add_check_repeat($uid, $array);
        if ($rid)
        {
        	return $rid;
        }
        $array['owner'] = $uid;
        $array['lastuse'] = time();
        dbc()->SetTable(table('address'));
        return dbc()->Insert($array);
    }
    
    private function __Add_check_repeat($uid, $array)
    {
    	$old = dbc(DBCMax)->select('address')->where(array(
    		'owner' => $uid,
    		'name' => $array['name'],
    		'region' => $array['region'],
    		'address' => $array['address'],
    		'zip' => $array['zip'],
    		'phone' => $array['phone']
    	))->limit(1)->done();
    	if ($old)
    	{
    		return $old['id'];
    	}
    	else
    	{
    		return false;
    	}
    }
    
    function Update($id, $array, $uid = 0)
    {
    	$sql_limit_user = '1';
    	if ($uid > 0)
    	{
    		$sql_limit_user = 'owner = '.$uid;
    	}
    	dbc()->SetTable(table('address'));
    	dbc()->Update($array, 'id='.$id.' AND '.$sql_limit_user);
    	return dbc()->AffectedRows();
    }
    
    function Remove( $id, $uid = 0 )
    {
    	$sql_limit_user = '1';
    	if ($uid > 0)
    	{
    		$sql_limit_user = 'owner = '.$uid;
    	}
        $sql = '
        DELETE
        FROM
        	' . table('address') . '
    	WHERE
    		id=' . $id .'
    	AND
    		'.$sql_limit_user;
        dbc()->Query($sql);
        return dbc()->AffectedRows();
    }
    
    function Accessed($class, &$data)
    {
        if ($class == 'order.save')
        {
            $aid = post('address_id', 'int');
            if ($aid)
            {
                $data['addressid'] = $aid;
            }
        }
    }
    
    function Regions($search = 1, $where = 1, $limit = 1)
    {
    	$sql = '
        SELECT
        	*
        FROM
        	'.table('regions').'
        WHERE
        	'.$search.'
        AND
        	'.$where.'
        ';
    	$query = dbc()->Query($sql);
    	if ($limit > 1)
    	{
    		return $query->GetAll();
    	}
    	else
    	{
    		return $query->GetRow();
    	}
    }
	
    public function import()
    {
        return loadInstance('logic.address.import', 'AddressLogic_Import');
    }
}


class AddressLogic_Import
{
	
	public function referer($ref = null)
	{
        if (is_null($ref))
        {
                        $ref = handler('cookie')->GetVar('addriref');
            if (!$ref || $ref == '')
            {
                return false;
            }
            else
            {
                handler('cookie')->SetVar('addriref', '', -1);
                return $ref;
            }
        }
        else
        {
                        handler('cookie')->SetVar('addriref', $ref);
        }
	}
	
    public function wlist()
    {
        if (ini('alipay.address.import.enabled'))
        {
            $list = ini('alipay.address.import.source');
            include handler('template')->file('@address/import/list');
        }
    }
    
    public function linker($flag)
    {
        return driver('iaddress')->api($flag)->linker();
    }
	
    public function verify($flag)
    {
        $data = driver('iaddress')->api($flag)->verify();
        return $data;
    }
    
    public function insert($data)
    {
    	$idata = array();
    	    	    	$prov = $data['prov']."\n";
    	$prov = str_replace('ʡ'."\n", '', $prov);
    	$rps = logic('address')->Regions('name LIKE "%'.$prov.'%"');
    	$province = $rps ? $rps['id'] : 0;
    	    	$cty = $data['city'];
    	$rps = logic('address')->Regions('name LIKE "%'.$cty.'%"', 'parent='.$province);
    	$city = $rps ? $rps['id'] : 0;
    	    	$area = $data['area'];
    	$rps = logic('address')->Regions('name LIKE "%'.$area.'%"', 'parent='.$city);
    	$country = $rps ? $rps['id'] : 0;
    	    	$idata['region'] = ',' . $province . ',' . $city . ',' . $country . ',';
    	    	$idata['address'] = $data['address'];
    	    	$idata['zip'] = $data['post'];
    	    	$idata['name'] = $data['fullname'];
    	    	$idata['phone'] = $data['mobile_phone'];
    	    	$id = logic('address')->Add(user()->get('id'), $idata);
    	return $id;
    }
}

?>