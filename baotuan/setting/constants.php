<?php

/*
+------------------------------------------------------------------------------
+ 软件的信息
+------------------------------------------------------------------------------
*/
define('SYS_NAME',			$config['site_name']);		//system name
define('SYS_VERSION',		'2.0 beta');
define('SYS_BUILD',			'build 20110530');
define('SYS_PATH',			'./');

//输出控制
define('GZIP',				(boolean) $config['gzip']);


//安全模式
//define('SYSTEM_SAFE_MODE',	(boolean) $config['safe_mode']);

/*
+------------------------------------------------------------------------------
+ 图片相关的信息
+------------------------------------------------------------------------------
*/

define('SMALL_PIC_PREFIX',	's-');
define('WATERMARK_PIC_PREFIX',	'w-');
define('SMALL_PIC_WIDTH',	89);
define('SMALL_PIC_HEIGHT',	89);

define('TABLE_PREFIX',				$config['db_table_prefix']);


//Ucenter 设置
@include_once(ROOT_PATH . './setting/ucenter.php');

define('UCENTER' , 			(boolean) $config['ucenter']['enable']);//标识Ucenter是否已经开启
	
define('UC_CLIENT_ROOT', 	ROOT_PATH . './uc_client/');

if (true === UCENTER) {

define('UC_CONNECT', 		$config['ucenter']['uc_connect']);	// 连接 UCenter 的方式: mysql/NULL, 默认为空时为 fscoketopen()

//数据库相关 (mysql 连接时, 并且没有设置 UC_DBLINK 时, 需要配置以下变量)
define('UC_DBHOST',		 	$config['ucenter']['uc_db_host']);			// UCenter 数据库主机
define('UC_DBUSER', 		$config['ucenter']['uc_db_user']);				// UCenter 数据库用户名
define('UC_DBPW', 			$config['ucenter']['uc_db_password']);					// UCenter 数据库密码
define('UC_DBNAME', 		$config['ucenter']['uc_db_name']);				// UCenter 数据库名称
define('UC_DBCHARSET',		$config['ucenter']['uc_db_charset'] ? $config['ucenter']['uc_db_charset'] : 'gbk');				// UCenter 数据库字符集
define('UC_DBTABLEPRE', 	$config['ucenter']['uc_db_table_prefix']);			// UCenter 数据库表前缀

//通信相关
define('UC_KEY', 			$config['ucenter']['uc_key']);				// 与 UCenter 的通信密钥, 要与 UCenter 保持一致
define('UC_API', 			$config['ucenter']['uc_api']);	// UCenter 的 URL 地址, 在调用头像时依赖此常量
define('UC_CHARSET', 		$config['ucenter']['uc_charset'] ? $config['ucenter']['uc_charset'] : 'gbk');				// UCenter 的字符集
define('UC_IP', 			$config['ucenter']['uc_ip']);					// UCenter 的 IP, 当 UC_CONNECT 为非 mysql 方式时, 并且当前应用服务器解析域名有问题时, 请设置此值
define('UC_APPID', 			$config['ucenter']['uc_app_id']);					// 当前应用的 ID
}

?>