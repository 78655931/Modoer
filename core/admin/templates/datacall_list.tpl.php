<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <div class="space">
        <div class="subtitle">调用筛选</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="100">调用模块：</td>
                <td width="100"><select name="flag">
                    <option value="">全部模块</option>
                    <?=form_module($_GET['flag'])?>
                </select></td>
                <td class="altbg1" width="100">调用ID：</td>
                <td width="100"><input type="text" name="callid" value="<?=$_GET['callid']?>" class="txtbox5" /></td>
                <td class="altbg1" width="100">调用标题：</td>
                <td width="*"><input type="text" name="name" value="<?=$_GET['name']?>" class="txtbox3" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="100">结果排序</td>
                <td colspan="5">
                    <select name="orderby">
                        <option value="callid"<?=$_GET['orderby']=='callid'?' selected="selected"':''?>>默认排序</option>
                        <option value="calltype"<?=$_GET['orderby']=='calltype'?' selected="selected"':''?>>调用类型</option>
                    </select>&nbsp;
                    <select name="ordersc">
                        <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>递减</option>
                        <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>递增</option>
                    </select>&nbsp;
                    <select name="offset">
                        <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>每页显示20个</option>
                        <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>每页显示50个</option>
                        <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>每页显示100个</option>
                    </select>&nbsp;
                    <button type="submit" name="dosubmit" value="yes" class="btn2">筛选</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">调用管理</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">&nbsp;选</td>
                <td width="40">ID</td>
                <td width="*">名称</td>
                <td width="100">所属模块</td>
                <td width="100">调用函数</td>
                <td width="160">调用模板</td>
                <td width="80">缓存(秒)</td>
                <td width="80">代码</td>
                <td width="60">操作</td>
            </tr>
            <?if($total > 0){?>
            <?while($val=$list->fetch_array()) { ?>
            <tr>
                <? $exp = unserialize($row['expression']); ?>
                <td><input type="checkbox" name="callids[]" value="<?=$val['callid']?>" /></td>
                <td><?=$val['callid']?></td>
                <td><?=$val['name']?></td>
                <td><?=$_G['modules'][$val['module']]['name']?></td>
                <td><?=$val['fun']?></td>
                <td><?=$val['tplname']?></td>
                <td><?=$val['cachetime']?></td>
                <td><a href="<?=cpurl($module,$act,'code',array('callid'=>$val['callid']))?>">查看代码</a>
                <td><a href="<?=cpurl($module,$act,($val['calltype']=='fun'?'edit':'editsql'),array('callid'=>$val['callid']))?>">编辑</a>&nbsp;<a href="<?=cpurl($module,$act,($val['calltype']=='fun'?'add':'addsql'),array('cy_callid'=>$val['callid']))?>">复制</a></td>
            </tr>
            <?}?>
            <tr class="altbg1">
                <td colspan="9">
                    <button type="button" onclick="checkbox_checked('callids[]');" class="btn2" />全选</button>&nbsp;
                    <button type="button" onclick="location.href='<?=cpurl($module,$act,'add')?>'" class="btn2">新增函数调用</button>&nbsp;
                    <button type="button" onclick="location.href='<?=cpurl($module,$act,'addsql')?>'" class="btn2">新增SQL调用</button>
                </td>
            </tr>
            <?} else {?>
                <td colspan="9">暂无信息。</td>
            <?}?>
        </table>
        <?if($total > 0){?>
        <div><?=$multipage?></div>
        <center>
            <input type="hidden" name="op" value="<?=$op?>" />
            <input type="hidden" name="datacallsubmit" value="yes" />
            <input type="button" value="更新全部数据缓存" class="btn" onclick="easy_submit('myform', 'refresh', null);" />
            <input type="button" value="更新所选数据缓存" class="btn" onclick="easy_submit('myform', 'refresh', 'callids[]');" />
            <input type="button" value="删除所选" class="btn" onclick="easy_submit('myform', 'delete', 'callids[]');" />
        </center>
        <?}?>
    </div>
</form>
</div>