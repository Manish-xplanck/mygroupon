<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename misc.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class MiscLogic
{
    
    public function City($field = '', $cid = 0)
    {
                $cityAry = $this->CityList($cid);
                if ( $_GET['city'] != '' )
        {                
            foreach ( $cityAry as $value )
            {
                if ( $value['shorthand'] == $_GET['city'] )
                {
                    handler('cookie')->setVar('mycity', $value['cityid']);
                    $cid = $value['cityid'];
                    break;
                }
            }
        }
                if ( $cid == '' )
        {
            if ( handler('cookie')->getVar('mycity') != '' )
            {
                $cid = handler('cookie')->getVar('mycity');
            }
            else
            {
                $cid = ini('product.default_city');
            }
        }
        foreach ($cityAry as $i => $city)
        {
            if ($city['cityid'] == $cid)
            {
                break;
            }
        }
        $map = array(
            'id' => 'cityid',
            'name' => 'cityname',
            'flag' => 'shorthand'
        );
        if ($field == '')
        {
            return $city;
        }
        return $city[$map[$field]];
    }
    
    public function CityList($cid = 0)
    {
        $ckey = 'misc.citylist.'.$cid;
        $list = cached($ckey);
        if ($list) return $list;
        $sql_limit_city = '1';
        if ($cid > 0)
        {
            $sql_limit_city = 'cityid = '.$cid;
        }
        $sql = '
        SELECT
        	*
		FROM
			'.table('city').'
		WHERE
			display >= 1
		AND
			'.$sql_limit_city;
        $list = dbc()->Query($sql)->GetAll();
        return cached($ckey, $list);
    }
    
    public function AskList()
    {
        $ckey = 'misc.asklist';
        $list = cached($ckey);
        if ($list) return $list;
        $sql = '
        SELECT
        	*
        FROM
        	'.table('question').'
        WHERE
        	reply <> ""
        ORDER
        	BY time
        DESC
        ';
        $sql = page_moyo($sql);
        $list = dbc()->Query($sql)->GetAll();
        return $list;
    }
    
    public function RegionList($parent = 0)
    {
        $sql_limit = '1';
        if ($parent == 0)
        {
            $sql_limit = 'grade = 1';
        }
        else
        {
            $sql_limit = 'parent = '.$parent;
        }
        $sql = '
        SELECT
        	*
        FROM
        	'.table('regions').'
        WHERE
        	'.$sql_limit.'
        ';
        return dbc()->Query($sql)->GetAll();
    }
    
    public function ShareList()
    {
        $shares = ini('share');
                foreach ( $shares as $flag => $share )
        {
            if ( $share['display'] == 'no' )
            {
                unset($shares[$flag]);
            }
        }
        return $shares;
    }
}
?>