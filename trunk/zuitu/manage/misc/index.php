<?php
require_once(dirname(dirname(dirname(__FILE__))) . '/app.php');

need_manager();
$daytime = strtotime(date('Y-m-d'));

$team_count = Table::Count('team');
$order_count = Table::Count('order');
$user_count = Table::Count('user');
$subscribe_count = Table::Count('subscribe');

$order_today_count = Table::Count('order', array(
	"create_time > {$daytime}",
));
$order_today_pay_count = Table::Count('order', array(
	"create_time > {$daytime}",
	'state' => 'pay',
));
$order_today_unpay_count = $order_today_count - $order_today_pay_count;

$user_today_count = Table::Count('user', array(
	"create_time > {$daytime}",
));

$income_pay = Table::Count('order', array(
	"create_time > {$daytime}",
	'state' => 'pay',
), 'money');

$income_charge = Table::Count('flow', array(
	"create_time > {$daytime}",
	'action' => 'charge',
), 'money');

$income_store= Table::Count('flow', array(
	"create_time > {$daytime}",
	'action' => 'store',
), 'money');

$income_withdraw = Table::Count('flow', array(
	"create_time > {$daytime}",
	'action' => 'withdraw',
), 'money');

/* 一周数据统计 */
$time = time() - 3600*24*7;
$week['order_count'] = Table::Count('order', array(
	"create_time > {$time}",
));

$week['order_pay'] = Table::Count('order', array(
	"create_time > {$time}",
	'state' => 'pay',
));

$week['order_unpay'] = $week['order_count'] - $week['order_pay'];

$week['user_count'] = Table::Count('user', array(
	"create_time > {$time}",
));

$week['income_pay'] = Table::Count('order', array(
	"create_time > {$time}",
	'state' => 'pay',
), 'money');

$week['income_charge'] = Table::Count('flow', array(
	"create_time > {$time}",
	'action' => 'charge',
), 'money');

$week['income_store'] = Table::Count('flow', array(
	"create_time > {$time}",
	'action' => 'store',
), 'money');

$week['income_withdraw'] = Table::Count('flow', array(
	"create_time > {$time}",
	'action' => 'withdraw',
), 'money');

/* version */
$version = strval(SYS_VERSION);
$subversion = strval(SYS_SUBVERSION);
$action = strval($_GET['action']);

$version_meta = zuitu_version($version);
$newversion = $version_meta['version'];
$newsubversion = $version_meta['subversion'];
$software = $version_meta['software'];
$isnew = ( $newversion==$version && $subversion == $newsubversion ) ;

if ( 'db' == $action ) {
	$r = zuitu_upgrade($action, $version);
	Session::Set('notice', '数据库结构升级成功，数据库已经是最新版本');
	redirect( WEB_ROOT . '/manage/misc/index.php' );
}
else if ( 'opt' == $action ) {
	$tables = DB::GetQueryResult("SHOW TABLE STATUS", false);
	foreach($tables AS $one) {
		DB::Query("OPTIMIZE TABLE {$one['name']}");
	}
	Session::Set('notice', '数据库结构优化完成');
	redirect( WEB_ROOT . '/manage/misc/index.php' );
}

include template('manage_misc_index');
