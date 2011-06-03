<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename upload.han.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class UploadHandler
{
    
    public function Newz()
    {
        return new self();
    }
    private $exts = array();
    private $maxSize = 0;
    private $savePath = '';
    
    public function AllowExts($exts)
    {
        $exts = explode(',', $exts);
        foreach ($exts as $i => $ext)
        {
            $this->exts[strtolower($ext)] = true;
        }
    }
    
    public function AllowSize($size)
    {
        list($unit, $val) = explode(':', $size);
        $unitCalc = array(
            'b' => 1,
            'kb' => 1024,
            'mb' => 1024*1024
        );
        $unitVal = $unitCalc[$unit];
        $unitVal = ($unitVal > 0) ? $unitVal : 1;
        $maxSize = $unitVal * (int)$val;
        $this->maxSize = $maxSize;
    }
    
    public function SavePath($path)
    {
        $this->savePath = $path;
    }
    
    public function Process($field)
    {
        $files = $_FILES[$field];
        $result = array();
        if (isset($files[0]['name']))
        {
                        foreach ($files as $i => $file)
            {
                $result[] = $this->Upload($file);
            }
        }
        else
        {
                        $result = $this->Upload($files);
        }
        return $result;
    }
    
    private function Upload($file)
    {
        if ($file['error'] != UPLOAD_ERR_OK)
        {
            return $this->GetError($file['error']);
        }
        $ext = $this->CheckExt($file);
        if (!$ext)
        {
            return $this->GetError(501);
        }
        $size = $this->CheckSize($file);
        if (!$size)
        {
            return $this->GetError(502);
        }
        $path = $this->MakePath($file);
        if (!$path)
        {
            return $this->GetError(503);
        }
        if (false == move_uploaded_file($file['tmp_name'], $path))
        {
            if (false == copy($file['tmp_name'], $path))
            {
                return $this->GetError(504);
            }
        }
        $result = array(
            'name' => $file['name'],
            'type' => $ext,
            'mime' => $file['type'],
            'size' => $size,
            'path' => $path
        );
        return $result;
    }
    
    private function CheckExt($file)
    {
        $name = $file['name'];
        $ext = strtolower(end(explode('.', $name)));
        if (isset($this->exts[$ext]) && true === $this->exts[$ext])
        {
            return $ext;
        }
        return false;
    }
    
    private function CheckSize($file)
    {
        $size = $file['size'];
        if ($size < $this->maxSize)
        {
            return $size;
        }
        return false;
    }
    
    private function MakePath($file)
    {
        $name = $file['name'];
        $ext = strtolower(end(explode('.', $name)));
        list($s, $ms) = explode(' ', microtime());
        $hash = md5($name.$s.$ms);
        $path = $this->savePath;
        $flag = array(
            '{$Y}', '{$M}', '{$D}', '{$EXT}', '{$HASH}'
        );
        $flag_val = array(
            date('Y'), date('m'), date('d'), $ext, $hash
        );
        $path = str_ireplace($flag, $flag_val, $path);
        $this->InitPath($path);
        if ($this->Writeable($path))
        {
            return $path;
        }
        return false;
    }
    
    private function Writeable($file)
    {
        $fp = fopen($file, 'w');
        $fp && fclose($fp) && unlink($file);
        return $fp ? true : false;
    }
    
    private function InitPath($path)
    {
        $all = explode('/', $path);
        $path = implode('/', array_slice($all, 0, count($all)-1));
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
    }
    
    private function GetError($code)
    {
        $errMsg = array(
            UPLOAD_ERR_INI_SIZE => __('�ϴ��ļ�̫��[INI]'),
            UPLOAD_ERR_FORM_SIZE => __('�ϴ��ļ�̫��[FORM]'),
            UPLOAD_ERR_PARTIAL => __('�ļ�û���ϴ�������'),
            UPLOAD_ERR_NO_FILE => __('û��ѡ���ϴ��ļ���'),
            501 => __('��������ϴ��ļ����ͣ�'),
            502 => __('�ϴ��ļ�̫��[MAX]'),
            503 => __('�ϴ�Ŀ¼�޷�д�룡'),
            504 => __('������ʱ�ļ�ʱ����')
        );
        return $errMsg[$code];
    }
}

?>