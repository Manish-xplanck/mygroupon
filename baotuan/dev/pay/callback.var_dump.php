<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename callback.var_dump.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


$output = '<p><h1>'.date('Y-m-d H:i:s').'</h1></p>';

$server = var_export($_SERVER, true);

$get = var_export($_GET, true);

$post = var_export($_POST, true);

$cookie = var_export($_COOKIE, true);

$session = var_export($_SESSION, true);

$output .= '<br/><b>$_SERVER</b><br/><pre>'.$server.'</pre><br/>';
$output .= '<br/><b>$_GET</b><br/><pre>'.$get.'</pre><br/>';
$output .= '<br/><b>$_POST</b><br/><pre>'.$post.'</pre><br/>';
$output .= '<br/><b>$_COOKIE</b><br/><pre>'.$cookie.'</pre><br/>';
$output .= '<br/><b>$_SESSION</b><br/><pre>'.$session.'</pre><br/>';

$trade_status = isset($_GET['trade_status']) ? $_GET['trade_status'] : $_POST['trade_status'];
if ($trade_status == '')
{
	$trade_status = isset($_GET['pay_result']) ? $_GET['pay_result'] : '';
	if ($trade_status != '')
	{
		$trade_status = (int)$trade_status ? 'VERIFY_FAILED' : 'TRADE_FINISHED';
	}
}
if ($trade_status != '')
{
	$msgType = 'rediret';
	if (isset($_POST['trade_status']) || count($_COOKIE) == 0)
	{
		$msgType = 'notify';
	}
	$oid = isset($_GET['out_trade_no']) ? $_GET['out_trade_no'] : $_POST['out_trade_no'];
	if ($oid == '')
	{
		$oid = $_GET['sp_billno'];
	}
	file_put_contents(DEV_PATH.'pay/notify/'.$oid.'.'.$msgType.'.'.$trade_status.'.html', $output);
}

?>