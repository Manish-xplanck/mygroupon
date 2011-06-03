<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename magseller.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 
 


 class ModuleObject extends MasterObject{
	var $city;
	var $config;

	function ModuleObject($config){
		$this->MasterObject($config);		Load::logic('product');
		$this->ProductLogic = new ProductLogic();
		Load::logic('pay');
		$this->PayLogic = new PayLogic();
		Load::logic('me');
		$this->MeLogic = new MeLogic();
		Load::logic('order');
		$this->OrderLogic = new OrderLogic();
		$this -> config =$config;
		$this->MasterObject($config);		$this->ID = (int) ($this->Post['id'] ? $this->Post['id'] : $this->Get['id']);
		$this->CacheConfig = ConfigHandler::get('cache');			$this->ShowConfig = ConfigHandler::get('show');    		Load::moduleCode($this);$this->Execute();
	}
	
function Execute(){	include_once ROOT_PATH . './setting/constants.php';
	$this -> Title ='�̼��Ź�����';
	if(MEMBER_ID < 1)$this->Messager("��������ע����¼!");
	$this -> config=ConfigHandler::get('product');
	list($this->cityary,$this->city,$this->cityname)=logic('misc')->City();
	ob_start();
	switch($this->Code){
		case 'ticket':
			$this->Ticket();
			break;
		case 'sendmail':
			$this->Sendmail();
			break;
		case 'express':
			$this->Express();
			break;
		default:
			$this->Main();
			break;
	};
	$body = ob_get_clean();
	$this->ShowBody($body);
}

function Main(){
	$sql='select p.* from '.TABLE_PREFIX.'tttuangou_product p LEFT JOIN '.TABLE_PREFIX.'tttuangou_seller s on p.sellerid=s.id where s.userid = '.MEMBER_ID;
	$query = $this->DatabaseHandler->Query($sql); 
	$product=$query->GetAll();
	include($this->TemplateHandler->Template("seller_manage"));
	}
	
function Ticket(){
	extract($this->Get);
	extract($this->Post);
	$productid=intval($id);
	$p1=$p2=$p3=2;
	$p1=1;
		$sql='select p.flag,p.perioddate,t.* from '.TABLE_PREFIX.'tttuangou_ticket t left join '.TABLE_PREFIX.'tttuangou_product p on t.productid=p.id where t.productid='.$productid;
	if($use==1){
		$p3=1;
		$p1=2;
		$sql.=' and t.status =0';
	}elseif($use=='0'){
		$p2=1;
		$p1=2;
		$sql.=' and t.status =1';
	}
		$query = $this->DatabaseHandler->Query($sql); 
		$ticket=$query->GetAll();
		include($this->TemplateHandler->Template("tttuangou_ticketlist"));
	}
function Sendmail(){
	$id = get('id', 'int');
    $c = logic('coupon')->GetOne($id);
    logic('notify')->Call($c['uid'], 'admin.mod.coupon.Alert', $c);
	$this->Messager("���Ѿ��ɹ������˸��û���");
}
}
?>