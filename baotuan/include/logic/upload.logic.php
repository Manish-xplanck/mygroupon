<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename upload.logic.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class UploadLogic
{
    
    public function html($class = 'image')
    {
        $exts = explode(',', ini('upload.exts'));
        $allowExts = '';
        foreach ($exts as $i => $ext)
        {
            $allowExts .= '*.'.$ext.';';
        }
        list($unit, $val) = explode(':', ini('upload.size'));
        $allowSize = ($unit == 'mb') ? $val*1024 : $val;
        include handler('template')->file('@html/uploader/'.$class);
    }
    
    public function GetOne($id)
    {
        $id = (int)$id;
        if ($id == 0)
        {
            return '';
        }
        $ckey = 'upload.getone.'.$id;
        $list = cached($ckey);
        if ($list) return $list;
        
        $sql = 'SELECT * FROM '.table('uploads').' WHERE id='.$id;
        $list = dbc()->Query($sql)->GetRow();
        
        return cached($ckey, $list);
    }
    
    public function Field($id, $key, $val = null)
    {
        if (!is_null($val))
        {
            $data = array(
                $key => $val
            );
            dbc()->SetTable(table('uploads'));
            dbc()->Update($data, 'id='.$id);
            return;
        }
        $file = $this->GetOne($id);
        return $file ? $file[$key] : '';
    }
    
    public function Update($id, $data)
    {
        dbc()->SetTable(table('uploads'));
        return dbc()->Update($data, 'id='.$id);
    }
    
    public function Delete($id)
    {
        $file = $this->GetOne($id);
        if (is_file($file['path']))
        {
            unlink($file['path']);
        }
        dbc()->SetTable(table('uploads'));
        dbc()->Delete('', 'id='.$id);
    }
    
    public function Save($field = 'Filedata')
    {
        $upr = handler('upload')->Newz();
        $upr->AllowExts(ini('upload.exts'));
        $upr->AllowSize(ini('upload.size'));
        $upr->SavePath(UPLOAD_PATH.'{$Y}-{$M}-{$D}/{$HASH}.{$EXT}');
        $files = $upr->Process($field);
        $result = array();
        if (isset($files[0]['name']))
        {
                        foreach ($files as $i => $file)
            {
                $result[] = $this->Log($file);
            }
        }
        else
        {
                        $result = $this->Log($files);
        }
        return $result;
    }
    
    private function Log($file)
    {
        if (is_string($file))
        {
            return array(
                'error' => true,
                'msg' => $file
            );
        }
        $data = $file;
        $data['intro'] = '';
        $data['url'] = ini('settings.site_url').str_replace('./', '/', $data['path']);
        $data['extra'] = '';
        $data['uid'] = user()->get('id');
        $data['ip'] = ip2long(client_ip());
        $data['update'] = time();
        dbc()->SetTable(table('uploads'));
        $data['id'] = dbc()->Insert($data);
        return $data;
    }
}

?>