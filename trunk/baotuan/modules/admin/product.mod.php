<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename product.mod.php $
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
        header('Location: ?mod=product&code=vlist');
    }
    function vList()
    {
        $list = logic('product')->GetList();
        logic('product')->AVParser($list);
        include handler('template')->file('@admin/product_list');
    }
    function Add()
    {
        $p = array();
        $p['successnum'] = ini('product.default_successnum');
        $p['virtualnum'] = ini('product.default_virtualnum');
        $p['oncemax'] = ini('product.default_oncemax');
        $p['oncemin'] = ini('product.default_oncemin');
        include handler('template')->file('@admin/product_mgr');
    }
    function Add_image()
    {
        $pid = get('pid', 'int');
        $id = get('id', 'int');
        $p = logic('product')->SrcOne($pid);
        $imgs = explode(',', $p['img']);
        foreach ($imgs as $i => $iid)
        {
            if ($iid == '' || $iid == 0)
            {
                unset($imgs[$i]);
            }
        }
        $imgs[] = $id;
        $new = implode(',', $imgs);
        logic('product')->Update($pid, array('img'=>$new));
        exit('ok');
    }
    function Edit()
    {
        $id = get('id', 'int');
        $p = logic('product')->GetOne($id);
        include handler('template')->file('@admin/product_mgr');
    }
    function Save()
    {
        $data = array();
        $data['name'] = post('name', 'txt');
        $data['flag'] = post('flag', 'txt');
        $data['city'] = post('city', 'int');
        $data['display'] = post('display', 'int');
        $data['sellerid'] = post('sellerid', 'int');
        $data['intro'] = post('intro');
        $data['order'] = post('order', 'int');
        $data['content'] = post('content');
        $data['cue'] = post('cue');
        $data['theysay'] = post('theysay');
        $data['wesay'] = post('wesay');
        $data['price'] = post('price', 'float');
        $data['nowprice'] = post('nowprice', 'float');
        $data['maxnum'] = post('maxnum', 'int');
        $data['begintime'] = strtotime(post('begintime'));
        $data['overtime'] = strtotime(post('overtime'));
        $data['type'] = post('type', 'txt');
        $data['perioddate'] = strtotime(post('perioddate'));
        $data['allinone'] = post('allinone', 'txt');
        $data['weight'] = post('weight', 'int');
        $data['weight'] *= (post('weightunit', 'txt') == 'g') ? 1 : 1000;
        $data['successnum'] = post('successnum', 'int');
        $data['virtualnum'] = post('virtualnum', 'int');
        $data['oncemax'] = post('oncemax', 'int');
        $data['oncemin'] = post('oncemin', 'int');
        $data['multibuy'] = post('multibuy', 'txt');
        if (post('imgs') != '')
        {
            $data['img'] = substr(post('imgs', 'txt'), 0, -1);
        }
        $id = post('id', 'int');
        if ($id == 0)
        {
            $data['addtime'] = time();
            $data['status'] = PRO_STA_Normal;
            $id = logic('product')->Publish($data);
        }
        else
        {
            logic('product')->Update($id, $data);
        }
                $hideSeller = post('hideseller', 'txt');
        if ($hideSeller == 'true')
        {
            meta('p_hs_'.$id, 'yes');
        }
        else
        {
            meta('p_hs_'.$id, null);
        }
        logic('product')->Maintain($id);
        $this->Messager('更新完成！', '?mod=product');
    }
    function Save_intro()
    {
        $id = get('id', 'int');
        $intro = get('intro', 'txt');
        logic('upload')->Field($id, 'intro', $intro);
        exit('ok');
    }
    function Del()
    {
        $id = get('id', 'int');
        logic('product')->Delete($id);
        $this->Messager('删除完成！');
    }
    function Del_image()
    {
        $pid = get('pid', 'int');
        $id = get('id', 'int');
        $p = logic('product')->SrcOne($pid);
        if ($p['img'] == '')
        {
                        logic('upload')->Delete($id);
        }
        else
        {
            $imgs = explode(',', $p['img']);
            foreach ($imgs as $i => $iid)
            {
                if ($iid == $id)
                {
                    logic('upload')->Delete($id);
                    unset($imgs[$i]);
                }
            }
            $new = implode(',', $imgs);
            logic('product')->Update($pid, array('img'=>$new));
        }
        exit('ok');
    }
}

?>