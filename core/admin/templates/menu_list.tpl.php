<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">�˵�����<?if($goto_info){?>[<?=$goto_info['title']?>]<?}?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">ɾ?</td>
                <td width="40">�˵�ID</td>
                <td width="55">����</td>
                <td width="110">����</td>
                <td width="*">��ַ</td>
                <td width="105">ͼ��</td>
                <td width="25">ͣ��</td>
                <td width="50">����</td>
                <td width="120">����</td>
            </tr>
            <?if($list) {?>
            <?foreach($list as $val) {?>
            <tr>
                <input type="hidden" name="menus[<?=$val['menuid']?>][isfolder]" value="<?=$val['isfolder']?>" />
                <td><input type="checkbox" name="menuids[]" value="<?=$val['menuid']?>" style="width:100%;" /></td>
                <td><?=$val['menuid']?></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][listorder]" class="txtbox5" value="<?=$val['listorder']?>" style="width:100%;" /></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][title]" class="txtbox4" value="<?=$val['title']?>" style="width:100%;" /></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][url]" class="txtbox3" value="<?if($val['isfolder']){?>N/A<?}else{?><?=$val['url']?><?}?>" <?if($val['isfolder']){?> disabled<?}?> style="width:100%;" /></td>
                <td><input type="text" name="menus[<?=$val['menuid']?>][icon]" class="txtbox4" value="<?=$val['icon']?>" style="width:100%;" /></td>
                <td><input type="checkbox" name="menus[<?=$val['menuid']?>][isclosed]" value="1"<?if($val['isclosed'])echo' checked="checked"';?> /></td>
                <td><?=$val['isfolder']?'�˵���':'�˵�'?></td>
                <td>
                    <?if($val['isfolder']) {?>
                        <a href="<?=cpurl($module,$act,'edit',array('menuid'=>$val['menuid']))?>">�༭</a>
                        <a href="<?=cpurl($module,$act,'list',array('parentid'=>$val['menuid']))?>">�¼�</a>
                        <a href="<?=cpurl($module,$act,'add',array('parentid'=>$val['menuid']))?>">����¼�</a>
                        <!--
                    <select id="select" name="select" onChange="selectOperation(this);">
                        <option value="">==����==</option>
                        <option value="<?=cpurl($module,$act,'edit',array('menuid'=>$val['menuid']))?>">�༭�˵�</option>
                        <option value="<?=cpurl($module,$act,'list',array('parentid'=>$val['menuid']))?>">�鿴�Ӳ˵�</option>
                        <option value="<?=cpurl($module,$act,'add',array('parentid'=>$val['menuid']))?>">����Ӳ˵�</option>
                    </select>
                    -->
                    <? } else {?>
                        <a href="<?=cpurl($module,$act,'edit',array('menuid'=>$val['menuid']))?>">�༭</a>
                    <? } ?>
                </td>
            </tr>
            <? } ?>
            <tr class="altbg1">
                <td colspan="2"><input type="button" value="ȫѡ" onclick="checkbox_checked('menuids[]');" class="btn2" /></td>
                <td colspan="8" style="text-align:right;"><?=$multipage?></td>
            </tr>
            <?} else {?>
                <td colspan="8">������Ϣ��</td>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="op" value="<?=$op?>" />
            <input type="hidden" name="parentid" value="<?=$parentid?>" />
            <input type="hidden" name="dosubmit" value="yes" />
            <input type="button" value="���²˵�" class="btn" onclick="easy_submit('myform', 'list', null);" />
            <input type="button" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform', 'delete', 'menuids[]');" />
            <input type="button" value="���Ӳ˵�" class="btn" onclick="location.href='<?=cpurl($module, $act, 'add', array('parentid'=>$parentid))?>'" />
            <?if($parentid){?>
            <input type="button" value="������һ��" class="btn" onclick="location.href='<?=cpurl($module, $act, 'list', array('parentid'=>$goto_parentid))?>'" />
            <?}?>
        </center>
    </div>
</form>
</div>