<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename mysql_max.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 



class mysql_maxDatabaseDriver
{
        private $_config_default = array( 
        'debug' => false, 'host' => 'localhost:3306', 'username' => 'root', 'password' => '', 'database' => 'mysql', 'prefix' => '', 'charset' => 'utf-8', 'cached' => 'file://{root}/query_cache/' 
    );
    public $CACHE_HASH_SALT = 'sql.cache.uuland.org';
    public $CLIENT_MULTI_RESULTS = 131072;
        private $_config = array();
    private $_debug = false;
    private $_host = '';
    private $_username = '';
    private $_password = '';
    private $_database = '';
    private $_prefix = '';
    private $_charset = '';
    private $_cached = '';
    private $_fc_path = '';
    private $_mc_server = '';
        private $_dbc_handle = null;
    private $_query_handle = null;
    public $sql = '';
    private $_cache_key = '';
    private $_result = array();
    private $_need_cache = false;
        private $_operate = '';
    private $_column = '';
    private $_field = '';
    private $_where = array();
    private $_order = array();
    private $_limit = '';
    private $_data = array();
    private $_cache = '';
        private $_trace = array();

    public function __construct()
    {
    }

        public function __destruct()
    {
                $this->free();
                $this->close();
    }

        public function config( $config )
    {
        $this->trace('public::config::load');
        $this->_config = $config;
                $this->init();
    }

        private function init()
    {
        $this->trace('private::config::init_default');
                foreach ( $this->_config as $key => $val )
        {
            $mkey = '_' . $key;
            $this->$mkey = isset($this->_config[$key]) ? $this->_config[$key] : $this->_config_default[$key];
        }
                if ($this->_charset)
        {
            $this->_charset = str_replace('-', '', $this->_charset);
        }
                if ( ! $this->_debug ) unset($this->_trace);
                $this->trace('private::config::init_cache');
        $cache_conf = explode('://', $this->_cached);
        $this->_cached = $cache_conf[0];
        if ( $this->_cached == 'file' )
        {
            $this->_fc_path = str_replace('{current}', dirname(__FILE__), str_replace('{root}', $_SERVER['DOCUMENT_ROOT'], $cache_conf[1]));
                        if ( ! is_dir($this->_fc_path) )
            {
    	        $list = explode('/', $this->_fc_path);
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
        }
        elseif ( $this->_cached == 'memcache' )
        {
            $this->_mc_server = $cache_conf[1];
        }
        unset($this->_config);
    }

        private function connect()
    {
        $this->trace('public::server::connect');
                $this->_dbc_handle = mysql_connect($this->_host, $this->_username, $this->_password, true, $this->CLIENT_MULTI_RESULTS);
        if ( ! $this->_dbc_handle )
        {
            $this->alert('Can\'t connect to Server [ ' . $this->_username . '@' . $this->_host . ' ]');
            return false;
        }
                if ( ! mysql_select_db($this->_database, $this->_dbc_handle) )
        {
            $this->alert('Can\'t select database [' . $this->_database . ']');
            return false;
        }
        $version = mysql_get_server_info($this->_dbc_handle);
                if ( $version >= '4.1' )
        {
                        mysql_query('SET NAMES "' . $this->_charset . '"', $this->_dbc_handle);
        }
                if ( $version > '5.0.1' )
        {
            mysql_query('SET SQL_Mode=""', $this->_dbc_handle);
        }
        return true;
    }

        private function free()
    {
        $this->trace('public::query::free');
        if ( $this->_query_handle && $this->_operate == 'SELECT' )
        {
            mysql_free_result($this->_query_handle);
        }
        unset($this->_query_handle);
        unset($this->_operate);
        unset($this->_column);
        unset($this->_field);
        unset($this->_where);
        unset($this->_order);
        unset($this->_limit);
        unset($this->_data);
        unset($this->_cache);
        unset($this->_result);
        return true;
    }

        private function close()
    {
        if ( $this->_dbc_handle )
        {
            $this->trace('public::server::close');
            mysql_close($this->_dbc_handle);
            unset($this->_dbc_handle);
        }
    }

            public function select( $column )
    {
        $this->_operate = 'SELECT';
        $this->_column = $column;
        return $this;
    }

    public function update( $column )
    {
        $this->_operate = 'UPDATE';
        $this->_column = $column;
        return $this;
    }

    public function insert( $column )
    {
        $this->_operate = 'INSERT';
        $this->_column = $column;
        return $this;
    }

    public function delete( $column )
    {
        $this->_operate = 'DELETE';
        $this->_column = $column;
        return $this;
    }

        public function in( $field )
    {
        $this->_field = $field;
        return $this;
    }

        public function where( $where )
    {
        $this->_where[] = $where;
        return $this;
    }

        public function order( $order )
    {
        $this->_order[] = $order;
        return $this;
    }

        public function limit( $limit )
    {
        $this->_limit = $limit;
        return $this;
    }

        public function data( $data )
    {
        $this->_data[] = $data;
        return $this;
    }

        public function cache( $cache )
    {
        $this->_cache = $cache;
        return $this;
    }

        public function done()
    {
        $this->trace('public::query::init');
                                $column = table($this->_column);
                switch ( $this->_operate )
        {
            case 'SELECT' :
                                if ( $this->_field )
                {
                    $field = $this->_field;
                }
                else
                {
                    $field = '*';
                }
                $sql = 'SELECT ' . $field . ' FROM `' . $column . '`' . $this->pack_where() . $this->pack_order() . $this->pack_limit();
                break;
            case 'UPDATE' :
                $sql = 'UPDATE `' . $column . '`' . $this->pack_data() . $this->pack_where();
                break;
            case 'INSERT' :
                $sql = 'INSERT INTO `' . $column . '`' . $this->pack_data();
                break;
            case 'DELETE' :
                $sql = 'DELETE FROM `' . $column . '`' . $this->pack_where();
                break;
            default :
                break;
        }
        $this->sql = $sql;
                if ( $this->_operate == 'SELECT' && $this->cache_check() )
        {
            $return = $this->_result;
                        if ( $this->free() ) return $return;
        }
                if ( ! $this->_dbc_handle ) $this->connect();
                $this->trace('public::query::begin[' . $this->_operate . ']');
        $this->_query_handle = mysql_query($sql, $this->_dbc_handle);
        if ( ! $this->_query_handle )
        {
            $this->alert('SQL run error.');
        }
        $this->trace('public::query::finish[' . $this->_operate . ']');
        if ( $this->_operate == 'SELECT' )
        {
            if ( mysql_num_rows($this->_query_handle) > 0 )
            {
                while ( false !== $one_row = mysql_fetch_assoc($this->_query_handle) )
                {
                    $this->_result[] = $one_row;
                }
                mysql_data_seek($this->_query_handle, 0);
            }
            else
            {
                $this->_result = null;
            }
                        if ( $this->_need_cache ) $this->cache_write();
            $result = $this->_result;
            $return = ($this->_limit == 1) ? $result[0] : $result;
                        if ( $this->free() ) return $return;
        }
        elseif ( $this->_operate == 'INSERT' )
        {
            $return = mysql_insert_id($this->_dbc_handle);
                        if ( $this->free() ) return $return;
        }
        else
        {
            $return = mysql_affected_rows($this->_dbc_handle);
                        if ( $this->free() ) return $return;
        }
    }

        private function pack_limit()
    {
        if ( $this->_limit == '' ) return '';
        if ( is_numeric($this->_limit) )
        {
            return ' LIMIT 0,' . $this->_limit;
        }
        elseif ( is_string($this->_limit) )
        {
            return ' LIMIT ' . $this->_limit;
        }
    }

        private function pack_where()
    {
        if ( ! $this->_where ) return '';
        $sql_where = ' WHERE ';
        foreach ( $this->_where as $where )
        {
            if ( is_array($where) )
            {
                foreach ( $where as $key => $val )
                {
                    if ( is_numeric($val) )
                    {
                        $sql_where .= '`'.$key.'`' . '=' . $val;
                    }
                    elseif ( is_string($val) )
                    {
                        $sql_where .= '`'.$key.'`' . '="' . $val . '"';
                    }
                    elseif ( is_null($val) )
                    {
                        $sql_where .= '`'.$key.'`' . '=' . $val;
                    }
                    $sql_where .= ' and ';
                }
            }
            elseif ( is_string($where) )
            {
                $conds = explode(',', $where);
                foreach ( $conds as $one_cond )
                {
                    $sql_where .= $one_cond . ' and ';
                }
            }
        }
        return substr($sql_where, 0, - 5);
    }

        private function pack_order()
    {
        if ( ! $this->_order ) return '';
        $sql_order = ' ORDER BY ';
        foreach ( $this->_order as $order )
        {
            if ( is_array($order) )
            {
                foreach ( $order as $key => $type )
                {
                    $sql_order .= '`'.$key.'`' . ' ' . $type . ', ';
                }
            }
            elseif ( is_string($order) )
            {
                $ords = explode(',', $order);
                foreach ( $ords as $one_ord )
                {
                    $sql_order .= str_replace('.', ' ', $one_ord) . ', ';
                }
            }
        }
        return substr($sql_order, 0, - 2);
    }

        private function pack_data()
    {
        if ( ! $this->_data ) return '';
        $sql_data = ' SET ';
        foreach ( $this->_data as $data )
        {
            if ( is_array($data) )
            {
                foreach ( $data as $key => $val )
                {
                    $noData = false;
                    if ( is_numeric($val) )
                    {
                        $sql_data .= '`'.$key.'`' . '=' . $val;
                    }
                    elseif ( is_string($val) )
                    {
                        $sql_data .= '`'.$key.'`' . '="' . $val . '"';
                    }
                    elseif ( is_null($val) )
                    {
                        $sql_data .= '`'.$key.'`' . '=' . $val;
                    }
                    else
                    {
                        $noData = true;
                    }
                    $noData || $sql_data .= ', ';
                }
            }
            elseif ( is_string($data) )
            {
                $datas = explode(',', $data);
                foreach ( $datas as $one_data )
                {
                    $sql_data .= $one_data . ', ';
                }
            }
        }
        return substr($sql_data, 0, - 2);
    }

            private function cache_check()
    {
        $this->trace('private::cache::check');
        if ( $this->_cache == '' ) return false;
        $this->_cache_key = md5($this->sql . '@' . $this->CACHE_HASH_SALT);
        $time_calc = array( 
            's' => 1, 'm' => 60, 'h' => 3600, 'd' => 86400 
        );
        $c_rule = explode(':', $this->_cache);
        $c_time = $c_rule[0];
        $c_long = ( int )$c_rule[1];
        if ( time() - $this->cache_time() > $time_calc[$c_time] * $c_long )
        {
            $this->_need_cache = true;
            return false;
        }
        $this->_result = $this->cache_read();
        return true;
    }

        private function cache_time()
    {
        $handle = 'cache_handle_' . $this->_cached . '_time';
        return $this->$handle($this->_cache_key);
    }

        private function cache_read()
    {
        $this->trace('private::cache::read');
        $handle = 'cache_handle_' . $this->_cached . '_value';
        return $this->$handle($this->_cache_key);
    }

        private function cache_write()
    {
        $this->trace('private::cache::write');
        $handle = 'cache_handle_' . $this->_cached . '_write';
        $this->$handle($this->_cache_key, $this->_result);
        $this->_need_cache = false;
    }

            private function cache_handle_file_time( $key )
    {
        if ( is_file($this->_fc_path . $key . '.sql') )
        {
            return filemtime($this->_fc_path . $key . '.sql');
        }
        else
        {
            return 0;
        }
    }

    private function cache_handle_file_value( $key )
    {
        if ( is_file($this->_fc_path . $key . '.sql') )
        {
            return unserialize(file_get_contents($this->_fc_path . $key . '.sql'));
        }
        else
        {
            return false;
        }
    }

    private function cache_handle_file_write( $key, $val )
    {
        file_put_contents($this->_fc_path . $key . '.sql', serialize($val));
        return true;
    }

        private function cache_handle_memcache_time( $key )
    {
        $mec = new Memcache();
        $mec->connect($this->_mc_server);
        $val = $mec->get($this->_cache_key . '_time');
        $mec->close();
        if ( $val == '' )
        {
            return 0;
        }
        else
        {
            return $val;
        }
    }

    private function cache_handle_memcache_value( $key )
    {
        $mec = new Memcache();
        $mec->connect($this->_mc_server);
        $val = $mec->get($this->_cache_key . '_value');
        $mec->close();
        if ( $val == '' )
        {
            return false;
        }
        else
        {
            return $val['value'];
        }
    }

    private function cache_handle_memcache_write( $key, $val )
    {
        $mec = new Memcache();
        $mec->connect($this->_mc_server);
        $mec->set($this->_cache_key . '_time', time());
        $mec->set($this->_cache_key . '_value', array( 
            'cached' => true, 'value' => $val 
        ));
        $mec->close();
        return true;
    }

                        private function alert( $message )
    {
        if ( ! $this->_debug ) return;
        echo '<div style="border:2px solid #000;margin:10px;padding:10px;">';
        echo $message;
        echo '<hr/>';
        echo mysql_error();
        if ($this->_debug)
        {
            echo '<hr/>';
            echo $this->sql;
        }
        echo '</div>';
        exit();
    }

        private function trace( $message )
    {
        if ( ! $this->_debug ) return;
        $this->_trace[] = array( 
            'timer' => microtime(), 'mmusage' => memory_get_usage(), 'message' => $message 
        );
    }

        public function trace_output()
    {
        if ( ! $this->_debug ) return;
        echo '<div style="border:2px solid #000;margin:10px;padding:10px;">';
        echo '<ul>';
        $last_timer = 0;
        $last_mmusage = 0;
        foreach ( $this->_trace as $i => $trace )
        {
            $timer_e = explode(' ', $trace['timer']);
            $timer = ( float )$timer_e[0];
            $mmusage = $trace['mmusage'];
            echo '<li>Time: ' . $timer . ' <font color="#0FC69D">+' . ($timer - $last_timer) . '</font> Memory: ' . $trace['mmusage'] . ' <font color="#E56298">+' . ($mmusage - $last_mmusage) . '</font> Call: ' . $trace['message'] . '</li>';
            $last_timer = $timer;
            $last_mmusage = $mmusage;
        }
        echo '</ul>';
        echo '</div>';
    }
}
?>