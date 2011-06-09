<?php
require_once(dirname(__FILE__) . '/app.php');

if(!$INI['db']['host']) redirect( WEB_ROOT . '/install.php' );
if($city&&option_yes('rewritecity')){
	redirect(WEB_ROOT."/{$city['ename']}");
}

$request_uri = 'index';
$team = $teams = index_get_team($city['id']);

if ($team && $team['id']) {
	$_GET['id'] = abs(intval($team['id']));
	die(require_once( dirname(__FILE__) . '/team.php'));
}
elseif ($teams) {
	$disable_multi = true;
	die(require_once( dirname(__FILE__) . '/multi.php'));
}

include template('subscribe');

