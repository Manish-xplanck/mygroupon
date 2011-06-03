<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename payment.mod.php $
 *
 * @Author http://www.tttuangou.net $
 *
 * @Date 2011-05-30 10:08:26 $
 *******************************************************************/ 
 




class ModuleObject extends MasterObject
{
    function ModuleObject( $config )
    {
        $this->MasterObject($config);
        $runCode = Load::moduleCode($this);
        $this->$runCode();
    }
    function Main()
    {
        header('Location: ?mod=payment&code=vlist');
    }
    public function vList()
    {
        $list = logic('pay')->SrcList();
        include handler('template')->file('@admin/payment_list');
    }
    function Config()
    {
        $flag = get('flag', 'txt');
        $file = DRIVER_PATH.'payment/'.$flag.'.config.php';
        if (!is_file($file))
        {
            $this->Messager('此支付方式没有配置项！');
        }
        else
        {
            include handler('template')->absfile($file);
        }
    }
    function Save()
    {
        $data = array(
            'config' => serialize(post('cfg'))
        );
        logic('pay')->Update($data, 'id='.post('id', 'number'));
        $this->Messager('修改完成！', '?mod=payment');
    }
}

?>