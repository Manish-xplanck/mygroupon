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
        'name' => '��Ʒ����',
        'table' => 'product',
        'src' => 'flag',
        'key' => 'pid',
        'idx' => 'id'
    ),
	'seller_name' => array(
        'name' => '�̼�����',
        'table' => 'seller',
        'src' => 'sellername',
        'key' => 'sid',
        'idx' => 'id'
    ),
	'city_name' => array(
        'name' => '��������',
        'table' => 'city',
        'src' => 'cityname',
        'key' => 'cid',
        'idx' => 'cityid'
    ),
	'order_id' => array(
		'name' => '�������',
		'table' => 'order',
		'src' => 'orderid',
		'key' => 'oid',
		'idx' => 'orderid'
	),
	'user_name' => array(
		'name' => '�û���',
		'table' => 'members',
		'src' => 'username',
		'key' => 'uid',
		'idx' => 'uid'
	),
	'coupon_no' => array(
		'name' => '�Ź�ȯ����',
		'table' => 'ticket',
		'src' => 'number',
		'key' => 'coid',
		'idx' => 'ticketid'
 	),
)
);

?>