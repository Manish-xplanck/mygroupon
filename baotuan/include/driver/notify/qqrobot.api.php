<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename qqrobot.api.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class qqrobot_api_driver
{
		private $server;
	private $port;
	private $seckey;
		private $socket;
	
	private static $__has_instance = false;
		public function getInstance()
	{
		if ( self::$__has_instance )
		{
			return self;
		}
		else
		{
			self::$__has_instance = true;
			return new self();
		}
	}
	public function config($config)
	{
		$this->server = $config['server'];
		$this->port = $config['port'];
		$this->seckey = $config['seckey'];
	}
	public function hello()
	{
		if (!$this->socket) $this->open('hello');
		$api_hello  = "GET / HTTP/1.1\r\n";
		fputs($this->socket, $api_hello);
		$buffer = '';
		while (!feof($this->socket))
		{
			$buffer .= fgets($this->socket, 512);
		}
		$this->close();
		return substr(strstr($buffer, "\r\n\r\n"), 4);
	}
	public function command($api, $args=array())
	{
		if (!$this->socket) $this->open($api);
		$api_name = "API $api MOYO/1.1\r\n";
		$api_args = "<seckey>$this->seckey</seckey>\r\n";
		foreach ($args as $key => $val)
		{
			$api_args .= "<$key>$val</$key>\r\n";
		}
		fputs($this->socket, $api_name.$api_args);
		$buffer = '';
		while (!feof($this->socket))
		{
			$buffer .= fgets($this->socket, 512);
		}
		$this->close();
		return $buffer;
	}
	private function open($api)
	{
		if ($this->socket) return true;
		$socket = fsockopen($this->server, $this->port);
		if (!$socket)
		{
			return false;
		}
		$this->socket = $socket;
		return true;
	}
	private function close()
	{
		if ($this->socket)
		{
			fclose($this->socket);
			unset($this->socket);
		}
	}
}

?>