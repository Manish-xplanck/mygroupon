<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename misc.mod.php $
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
        header('Location: .');
    }

    
    function Region()
    {
        $parent = $this->Get['parent'];
        $parent = (is_numeric($parent)) ? ( int )$parent : 0;
        $list = logic('misc')->RegionList($parent);
        $ops = '';
        foreach ( $list as $i => $one )
        {
            $ops .= '<option value="' . $one['id'] . '">' . $one['name'] . '</option>';
        }
        echo $ops;
    }
    
    
    function Address()
    {
        $ops = array( 
            'status' => 'failed', 'msg' => __('请求无效！') 
        );
        echo jsonEncode($ops);
    }
    
    
    function Address_get()
    {
    	$id = post('id', 'int');
    	$address = logic('address')->GetOne($id, user()->get('id'));
    	if (!$address)
    	{
    		$ops = array( 
            	'status' => 'failed', 'msg' => __('找不到相关地址信息！') 
        	);
    	}
    	else
    	{
    		$loc = $address['region_loc'];
    		list(, $province, $city, $country, ,) = explode(',', $loc);
    		$array = array(
    			'id' => $address['id'],
    			'province' => $province,
    			'city' => $city,
    			'country' => $country,
    			'address' => $address['address'],
    			'zip' => $address['zip'],
    			'name' => $address['name'],
    			'phone' => $address['phone']
    		);
    		$ops = array(
    			'status' => 'ok',
    			'addr' => $array
    		);
    	}
    	echo jsonEncode($ops);
    }
    
    
    function Address_save()
    {
        $province = $this->Post('province', 'int');
        $city = $this->Post('city', 'int');
        $country = $this->Post('country', 'int');
        $post['region'] = ',' . $province . ',' . $city . ',' . $country . ',';
        $post['address'] = $this->Post('address');
        $post['zip'] = $this->Post('zip', 'number');
        $post['name'] = $this->Post('name');
        $post['phone'] = $this->Post('phone');
        $id = post('id', 'int');
        if (!$id)
        {
        	$new_id = logic('address')->Add(user()->get('id'), $post);
        }
        else
        {
        	$new_id = logic('address')->Update($id, $post, user()->get('id'));
        }
        if ( $new_id > 0 )
        {
            $ops = array( 
                'status' => 'ok', 'id' => $new_id 
            );
        }
        else
        {
            $ops = array( 
                'status' => 'failed', 'msg' => __('保存失败！')
            );
        }
        echo jsonEncode($ops);
    }
    
    
	function Address_del()
	{
		$id = post('id', 'int');
		$result = logic('address')->Remove($id, user()->get('id'));
		if ($result > 0)
		{
			$ops = array(
				'status' => 'ok'
			);
		}
		else
		{
			$ops = array(
				'status' => 'failed',
				'msg' => __('删除失败！')
			);
		}
		echo jsonEncode($ops);
	}

    
    function Express()
    {
        $ops = array( 
            'status' => 'failed', 'msg' => __('请求无效！') 
        );
        echo jsonEncode($ops);
    }

    
    function Express_list()
    {
        $aid = $this->Get('aid');
        $list = logic('express')->GetList($aid);
        $ops = array( 
            'status' => 'ok', 'html' => $list 
        );
        echo jsonEncode($ops);
    }
}
?>