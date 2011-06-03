<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename soso.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 

$si = array
(
	'site_name' => $this->Config['site_name'],
	'site_title' => $this->Config['site_name'],
	'site_url' => $this->Config['site_url']
);
if (!ENC_IS_GBK) $si = array_iconv('GBK', 'UTF-8', $si);
if (!ENC_IS_GBK) $team = array_iconv('GBK', 'UTF-8', $productList[0]);
$team = $productList[0];
if (!ENC_IS_GBK) $city = iconv('GBK', 'UTF-8/'.'/IGNORE', $cityList[$team['city']]);
$group = $city;
$oa = array();
$oa['provider'] = $si['site_name'];
$oa['version'] = '1.0';
$oa['dataServiceId'] = '1_1';

$item = array();
$item['keyword'] = "{$si['site_name']} {$team['name']}";
$item['url'] = "{$si['site_url']}/?view={$team['id']}";
$item['creator'] = $_SERVER['HTTP_HOST'];
$item['title'] = "{$si['site_name']} {$team['name']}";
$item['publishdate'] = date('Y-m-d', $team['begintime']);
$item['imageaddress1'] = imager($team['img']);
$item['imagealt1'] = $team['name'];
$item['imagelink1'] = "{$si['site_url']}/?view={$team['id']}";
$item['content1'] = $team['name'];
$item['linktext1'] = $team['name'];
$item['linktarget1'] = "{$si['site_url']}/?view={$team['id']}";
$item['content2'] = "{$team['price']}ิช";
$item['content3'] = "{$team['nowprice']}".__('ิช');
$item['content4'] = $team['discount'].__("ีÛ");
$item['content5'] = $group;
$item['content6'] = $city;
$item['content7'] = $team['num'];
$item['linktext2'] = $si['site_name'];
$item['linktarget2'] = $si['site_url'];
$item['content8'] = date('Y-m-d H:i:s', $team['begintime']);
$item['content9'] = date('Y-m-d H:i:s', $team['overtime']);
$item['valid'] = '1';

$oa['datalist']['item'] = $item;

header('Content-Type: application/xml; charset=GBK');
Output::XmlCustom($oa, 'sdd', 'GBK');
?>