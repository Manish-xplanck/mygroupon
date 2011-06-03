<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename isearcher.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


$config['isearcher'] = array(
'idx' => array(
	'admin' => array(
		'product_list' => 'product_name,seller_name,city_name',
		'order_list' => 'product_name,order_id,user_name',
		'coupon_list' => 'product_name,order_id,user_name,coupon_no',
		'delivery_list' => 'product_name,order_id,user_name',
	)
),
'map' => array(
	'product_name' => array(
        'name' => '产品名称',
        'table' => 'product',
        'src' => 'flag',
        'key' => 'pid',
        'idx' => 'id'
    ),
	'seller_name' => array(
        'name' => '商家名称',
        'table' => 'seller',
        'src' => 'sellername',
        'key' => 'sid',
        'idx' => 'id'
    ),
	'city_name' => array(
        'name' => '城市名称',
        'table' => 'city',
        'src' => 'cityname',
        'key' => 'cid',
        'idx' => 'cityid'
    ),
	'order_id' => array(
		'name' => '订单编号',
		'table' => 'order',
		'src' => 'orderid',
		'key' => 'oid',
		'idx' => 'orderid'
	),
	'user_name' => array(
		'name' => '用户名',
		'table' => 'members',
		'src' => 'username',
		'key' => 'uid',
		'idx' => 'uid'
	),
	'coupon_no' => array(
		'name' => '团购券号码',
		'table' => 'ticket',
		'src' => 'number',
		'key' => 'coid',
		'idx' => 'ticketid'
 	),
)
);

?>