<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename lock.drv.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class LockDriver
{
    
    private $dir = null;
    
    private $path = array();
    
    private $hashNum = 100;
    
    public function __construct()
    {
        $this->config(CACHE_PATH.'locks/');
    }
    
    public function config( $dir )
    {
        $this->dir = $dir;
    }
    
    public function file( $name )
    {
        return $this->pathed($name);
    }
    
    public function islocked( $name )
    {
        $file = $this->pathed($name);
        $c = (is_file($file)) ? file_get_contents($file) : '';
        return ($c == 'locked') ? true : false;
    }
    
    
    public function locks( $name, $lock )
    {
        $file = $this->pathed($name);
        if ($lock === true)
        {
            $result = file_put_contents($file, 'locked');
        }
        elseif ($lock === false)
        {
            $result = (is_file($file)) ? unlink($file) : false;
        }
        return $result;
    }

    
    private function pathed($name)
    {
        if (!isset($this->path[$name]))
        {
            if ( !is_dir($this->dir) )
    	    {
    	        $list = explode('/', $this->dir);
    	        $path = '';
                foreach ($list as $i => $dir)
                {
                    if ($dir == '') continue;
                    $path .= $dir.'/';
                    if ( !is_dir($path) )
                    {
                        mkdir($path);
                    }
                }
    	    }
            $this->path[$name] = $this->dir . ($this->crc32($name) % $this->hashNum) . '.lock';
        }
        return $this->path[$name];
    }

    
    private function crc32( $string )
    {
        $crc = abs(crc32($string));
        if ( $crc & 0x80000000 )
        {
            $crc ^= 0xffffffff;
            $crc += 1;
        }
        return $crc;
    }
}
?>