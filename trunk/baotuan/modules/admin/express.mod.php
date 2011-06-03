<?php
/*******************************************************************
 *[TTTuangou] (C)2005 - 2011 Cenwor Inc.
 *
 * This is NOT a freeware, use is subject to license terms
 *
 * @Filename express.mod.php $
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
        $list = logic('express')->SrcList();
        include handler('template')->file('@admin/express_list');
    }
    function Add()
    {
        $actionName = '添加';
        $corpList = logic('express')->CorpList();
        include handler('template')->file('@admin/express_manager');
    }
    function Edit()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('非法配送方式编号！');
        }
        $actionName = '编辑';
        $corpList = logic('express')->CorpList();
        $c = logic('express')->AdmOne($id);
        include handler('template')->file('@admin/express_manager');
    }
    function Save()
    {
        $id = post('id', 'int');
        $c = array();
        $c['name'] = post('name', 'txt');
        $c['express'] = post('express', 'int');
        $c['firstunit'] = post('firstunit', 'float');
        $c['continueunit'] = post('continueunit', 'float');
        $fuu = post('fuunit', 'txt');
        $cuu = post('cuunit', 'txt');
        $c['firstunit'] *= ($fuu == 'g') ? 1 : 1000;
        $c['continueunit'] *= ($cuu == 'g') ? 1 : 1000;
        $c['firstprice'] = post('firstprice', 'float');
        $c['continueprice'] = post('continueprice', 'float');
        $c['regiond'] = post('regiond', 'int');
        $dpenable = post('dpenable', 'txt');
        if ($dpenable)
        {
            $c['dpenable'] = 'true';
        }
        else
        {
            $c['dpenable'] = 'false';
        }
        $c['detail'] = post('detail');
        $c['order'] = post('order', 'int');
        $c['enabled'] = post('enabled', 'txt');
        dbc()->SetTable(table('express'));
        if ($id == 0)
        {
            $id = dbc()->Insert($c);
        }
        else
        {
            dbc()->Update($c, 'id='.$id);
        }
        if ($c['regiond'] == 1)
        {
            $eids = post('ex_region_id');
            $efp = post('ex_firstprice');
            $ecp = post('ex_continueprice');
            $eregions = post('ex_regions');
            foreach ($eids as $i => $eid)
            {
                $e = array();
                $e['parent'] = $id;
                $e['firstprice'] = $efp[$i];
                $e['continueprice'] = $ecp[$i];
                $e['region'] = $eregions[$i];
                if ($e['firstprice']=='' || $e['continueprice']=='' || $e['region']=='')
                {
                    continue;
                }
                dbc()->SetTable(table('express_area'));
                if ($eid == 0)
                {
                    dbc()->Insert($e);
                }
                else
                {
                    dbc()->Update($e, 'id='.$eid);
                }
            }
        }
        $this->Messager('更新成功！', '?mod=express');
    }
    function Del()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            $this->Messager('非法配送方式编号！');
        }
        logic('express')->Del($id);
        $this->Messager('删除成功！');
    }
    function Del_regions()
    {
        $id = get('id', 'int');
        if (!$id)
        {
            exit;
        }
        logic('express')->AreaDel($id);
        echo 'ok';
        exit;
    }
}

?>