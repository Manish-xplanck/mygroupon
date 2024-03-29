<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename streams.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 



if ( !class_exists( 'POMO_Reader' ) ):
class POMO_Reader {

	var $endian = 'little';
	var $_post = '';

	function POMO_Reader() {
		$this->is_overloaded = ((ini_get("mbstring.func_overload") & 2) != 0) && function_exists('mb_substr');
		$this->_pos = 0;
	}

	
	function setEndian($endian) {
		$this->endian = $endian;
	}

	
	function readint32() {
		$bytes = $this->read(4);
		if (4 != $this->strlen($bytes))
			return false;
		$endian_letter = ('big' == $this->endian)? 'N' : 'V';
		$int = unpack($endian_letter, $bytes);
		return array_shift($int);
	}

	
	function readint32array($count) {
		$bytes = $this->read(4 * $count);
		if (4*$count != $this->strlen($bytes))
			return false;
		$endian_letter = ('big' == $this->endian)? 'N' : 'V';
		return unpack($endian_letter.$count, $bytes);
	}


	function substr($string, $start, $length) {
		if ($this->is_overloaded) {
			return mb_substr($string, $start, $length, 'ascii');
		} else {
			return substr($string, $start, $length);
		}
	}

	function strlen($string) {
		if ($this->is_overloaded) {
			return mb_strlen($string, 'ascii');
		} else {
			return strlen($string);
		}
	}

	function str_split($string, $chunk_size) {
		if (!function_exists('str_split')) {
			$length = $this->strlen($string);
			$out = array();
			for ($i = 0; $i < $length; $i += $chunk_size)
				$out[] = $this->substr($string, $i, $chunk_size);
			return $out;
		} else {
			return str_split( $string, $chunk_size );
		}
	}


	function pos() {
		return $this->_pos;
	}

	function is_resource() {
		return true;
	}

	function close() {
		return true;
	}
}
endif;

if ( !class_exists( 'POMO_FileReader' ) ):
class POMO_FileReader extends POMO_Reader {
	function POMO_FileReader($filename) {
		parent::POMO_Reader();
		$this->_f = fopen($filename, 'r');
	}

	function read($bytes) {
		return fread($this->_f, $bytes);
	}

	function seekto($pos) {
		if ( -1 == fseek($this->_f, $pos, SEEK_SET)) {
			return false;
		}
		$this->_pos = $pos;
		return true;
	}

	function is_resource() {
		return is_resource($this->_f);
	}

	function feof() {
		return feof($this->_f);
	}

	function close() {
		return fclose($this->_f);
	}

	function read_all() {
		$all = '';
		while ( !$this->feof() )
			$all .= $this->read(4096);
		return $all;
	}
}
endif;

if ( !class_exists( 'POMO_StringReader' ) ):

class POMO_StringReader extends POMO_Reader {

	var $_str = '';

	function POMO_StringReader($str = '') {
		parent::POMO_Reader();
		$this->_str = $str;
		$this->_pos = 0;
	}


	function read($bytes) {
		$data = $this->substr($this->_str, $this->_pos, $bytes);
		$this->_pos += $bytes;
		if ($this->strlen($this->_str) < $this->_pos) $this->_pos = $this->strlen($this->_str);
		return $data;
	}

	function seekto($pos) {
		$this->_pos = $pos;
		if ($this->strlen($this->_str) < $this->_pos) $this->_pos = $this->strlen($this->_str);
		return $this->_pos;
	}

	function length() {
		return $this->strlen($this->_str);
	}

	function read_all() {
		return $this->substr($this->_str, $this->_pos, $this->strlen($this->_str));
	}

}
endif;

if ( !class_exists( 'POMO_CachedFileReader' ) ):

class POMO_CachedFileReader extends POMO_StringReader {
	function POMO_CachedFileReader($filename) {
		parent::POMO_StringReader();
		$this->_str = file_get_contents($filename);
		if (false === $this->_str)
			return false;
		$this->_pos = 0;
	}
}
endif;

if ( !class_exists( 'POMO_CachedIntFileReader' ) ):

class POMO_CachedIntFileReader extends POMO_CachedFileReader {
	function POMO_CachedIntFileReader($filename) {
		parent::POMO_CachedFileReader($filename);
	}
}
endif;