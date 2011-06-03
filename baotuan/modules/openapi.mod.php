<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename openapi.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 


class ModuleObject extends MasterObject
{
	var $Config = array();
	var $ProductLogic = null;
	function ModuleObject(& $config)
	{
		$this->MasterObject($config);
		Load::logic('product');
		$this->ProductLogic = new ProductLogic();
		$this->Execute();
	}
	function Execute()
	{
		if ($this->Code == '')
		{
			$this->Code = 'main';
		}
		$this -> config=ConfigHandler::get('product');
		list($this->cityary,$this->city,$this->cityname)=logic('misc')->City();
		if ('main' == $this->Code)
		{
			ob_start();
			$this->UrlList();
			$body = ob_get_clean();
			$this->ShowBody($body);
		}
		else
		{
			$this->RssOutput();
		}
	}
	function UrlList()
	{
		$supportList = array
		(
			'index' => array(
				'title' => $this->Config['site_name'],
				'submit' => ''
			),
			'jutao' => array(
				'title' => '������',
				'submit' => 'http:/'.'/www.jutao.com/url.htm'
			),
			'2345' => array(
				'title' => '2345�Ź�����',
				'submit' => 'http:/'.'/bbs.2345.com/tgAPI/api.php'
			),
			'baidu' => array(
				'title' => '�ٶ�/hao123�Ź�����',
				'submit' => 'http:/'.'/www.hao123.com/redian/api.htm'
			),
			'ganji' => array(
				'title' => '�ϼ����Ź�����',
				'submit' => 'http:/'.'/hz.ganji.com/tuan/api.php'
			),
			'sohu' => array(
				'title' => '�Ѻ��Ź�����',
				'submit' => 'http:/'.'/t123.sohu.com/site.html'
			),
			'soso' => array(
				'title' => '�����Ź�����',
				'submit' => 'http:/'.'/tuan.soso.com/siteSubmit.html'
			),
			'tuan800' => array(
				'title' => '��800�Ź���',
				'submit' => 'http:/'.'/www.tuan800.com/open_api'
			),
			'tuanp' => array(
				'title' => '�Ź�֮��',
				'submit' => 'http:/'.'/www.tuanp.com/api.php'
			)
		);
				global $rewriteHandler;
		include_once INCLUDE_PATH.'rewrite.php';
		$url_pre = '/?mod=openapi&code=';
		if ($rewriteHandler)
		{
			$url_pre = $rewriteHandler->formatURL($url_pre);
		}
		$this->Title = '����API';
		include($this->TemplateHandler->Template('tttuangou_rss'));
	}
	function RssOutput()
	{
		$allowList = array
		(
			'index','360','2345','baidu','ganji','sohu','soso','tuan800','tuanp', 'jutao'
		);
		if (!in_array($this->Code, $allowList))
		{
			echo 'Action not allowed';
			return;
		}
				require 'rss/Output.class.php';
				$productList = logic('product')->GetList(-1, PRO_ACV_Yes);
		if (count($productList)<1)
		{
			echo 'Null of Database';
			return;
		}
		$cityList_nf = logic('misc')->CityList();
		foreach ($cityList_nf as $i => $one)
		{
			$cityList[$one['cityid']] = $one['cityname'];
		}
				include 'rss/'.$this->Code.'.php';
	}
}

?>