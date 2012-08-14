<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="get" action="<?=SELF?>">
    <input type="hidden" name="module" value="<?=$module?>" />
    <input type="hidden" name="act" value="<?=$act?>" />
    <div class="space">
        <div class="subtitle">����ɸѡ</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="100">����ģ�飺</td>
                <td width="100"><select name="flag">
                    <option value="">ȫ��ģ��</option>
                    <?=form_module($_GET['flag'])?>
                </select></td>
                <td class="altbg1" width="100">����ID��</td>
                <td width="100"><input type="text" name="callid" value="<?=$_GET['callid']?>" class="txtbox5" /></td>
                <td class="altbg1" width="100">���ñ��⣺</td>
                <td width="*"><input type="text" name="name" value="<?=$_GET['name']?>" class="txtbox3" /></td>
            </tr>
            <tr>
                <td class="altbg1" width="100">�������</td>
                <td colspan="5">
                    <select name="orderby">
                        <option value="callid"<?=$_GET['orderby']=='callid'?' selected="selected"':''?>>Ĭ������</option>
                        <option value="calltype"<?=$_GET['orderby']=='calltype'?' selected="selected"':''?>>��������</option>
                    </select>&nbsp;
                    <select name="ordersc">
                        <option value="DESC"<?=$_GET['ordersc']=='DESC'?' selected="selected"':''?>>�ݼ�</option>
                        <option value="ASC"<?=$_GET['ordersc']=='ASC'?' selected="selected"':''?>>����</option>
                    </select>&nbsp;
                    <select name="offset">
                        <option value="20"<?=$_GET['offset']=='20'?' selected="selected"':''?>>ÿҳ��ʾ20��</option>
                        <option value="50"<?=$_GET['offset']=='50'?' selected="selected"':''?>>ÿҳ��ʾ50��</option>
                        <option value="100"<?=$_GET['offset']=='100'?' selected="selected"':''?>>ÿҳ��ʾ100��</option>
                    </select>&nbsp;
                    <button type="submit" name="dosubmit" value="yes" class="btn2">ɸѡ</button>
                </td>
            </tr>
        </table>
    </div>
</form>
<form method="post" action="<?=cpurl($module,$act)?>" name="myform">
    <div class="space">
        <div class="subtitle">���ù���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">&nbsp;ѡ</td>
                <td width="40">ID</td>
                <td width="*">����</td>
                <td width="100">����ģ��</td>
                <td width="100">���ú���</td>
                <td width="160">����ģ��</td>
                <td width="80">����(��)</td>
                <td width="80">����</td>
                <td width="60">����</td>
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
                <td><a href="<?=cpurl($module,$act,'code',array('callid'=>$val['callid']))?>">�鿴����</a>
                <td><a href="<?=cpurl($module,$act,($val['calltype']=='fun'?'edit':'editsql'),array('callid'=>$val['callid']))?>">�༭</a>&nbsp;<a href="<?=cpurl($module,$act,($val['calltype']=='fun'?'add':'addsql'),array('cy_callid'=>$val['callid']))?>">����</a></td>
            </tr>
            <?}?>
            <tr class="altbg1">
                <td colspan="9">
                    <button type="button" onclick="checkbox_checked('callids[]');" class="btn2" />ȫѡ</button>&nbsp;
                    <button type="button" onclick="location.href='<?=cpurl($module,$act,'add')?>'" class="btn2">������������</button>&nbsp;
                    <button type="button" onclick="location.href='<?=cpurl($module,$act,'addsql')?>'" class="btn2">����SQL����</button>
                </td>
            </tr>
            <?} else {?>
                <td colspan="9">������Ϣ��</td>
            <?}?>
        </table>
        <?if($total > 0){?>
        <div><?=$multipage?></div>
        <center>
            <input type="hidden" name="op" value="<?=$op?>" />
            <input type="hidden" name="datacallsubmit" value="yes" />
            <input type="button" value="����ȫ�����ݻ���" class="btn" onclick="easy_submit('myform', 'refresh', null);" />
            <input type="button" value="������ѡ���ݻ���" class="btn" onclick="easy_submit('myform', 'refresh', 'callids[]');" />
            <input type="button" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform', 'delete', 'callids[]');" />
        </center>
        <?}?>
    </div>
</form>
</div>