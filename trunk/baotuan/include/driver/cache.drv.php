<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename cache.drv.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class CacheDriver
{
    public static $root = 'fcache/';
	public function read($key, $live)
	{
	    $path = CACHE_PATH.self::$root;
		$file = $path.$key.'.cache.php';
		if ( !is_file($file) )
		{
			return false;
		}
		else
		{
			if ($live >= 0)
			{
    			if ( time() - filemtime($file) > $live)
    			{
    				$live = 0;
    			}
			    if ($live == 0)
    			{
    			    unlink($file);
    			    return false;
    			}
			}
			$cache = array();
			include $file;
			return $cache;
		}
	}
	public function write($key, $value)
	{
	    $path = CACHE_PATH.self::$root;
	    if ( !is_dir($path) )
	    {
	        $list = explode('/', $path);
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
		$file = $path.$key.'.cache.php';
        file_put_contents($file, 
        '<?php'."\n".
        ''."\n".
        '$cache =  ' . var_export($value, true) . ';'."\n".
        '?>');
        return true;
	}
}

?>