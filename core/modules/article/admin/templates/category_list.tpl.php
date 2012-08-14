<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">���·������<?if($cate):?>&nbsp;[����: <?=$cate['name']?>]<?endif;?></div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">�����б�</a></li>
            <li id="btn_config2"><a href="#" onclick="tabSelect(2,'config');" onfocus="this.blur()">��ӷ���</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1" trmouse="Y">
			<tr class="altbg1">
				<td width="25">ѡ</td>
                <td width="50">ID</td>
                <td width="130">����</td>
                <td width="*">����</td>
                <?if($pid):?><td width="100">����</td><?endif;?>
                <td width="120">����</td>
			</tr>
            <?if($list):?>
            <?while($val = $list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="catids[]" value="<?=$val['catid']?>" /></td>
                <td><?=$val['catid']?></td>
                <td><input type="text" name="category[<?=$val['catid']?>][listorder]" value="<?=$val['listorder']?>" class="txtbox4" /></td>
                <td><input type="text" name="category[<?=$val['catid']?>][name]" value="<?=$val['name']?>" class="txtbox2" /></td>
                <?if($pid):?><td><?=$val['total']?></td><?endif;?>
                <td>
                    <?if(!$val['pid']):?><a href="<?=cpurl($module,$act,'list',array('pid'=>$val['catid']))?>">�鿴�¼�����</a>&nbsp;<?endif;?>
                    <a href="<?=cpurl($module,$act,'delete',array('catids'=>$val['catid']))?>" onclick="return confirm('��ȷ��Ҫɾ�������Լ�����������');">ɾ��</a>
                </td>
            </tr>
            <?endwhile;?>
            <?else:?>
            <td colspan="10">������Ϣ��</td>
            <?endif;?>
            <?if($pid):?>
            <tr class="altbg1">
                <td colspan="6">ת����ѡ���ൽ��<select name="move_pid">
                    <option value="">==ѡ�����==</option>
                    <?=form_article_category(0,'',array($pid))?>
                    </select>&nbsp;
                    <button type="button" class="btn2" onclick="easy_submit('myform','move','catids[]')">ת�Ʒ���</button>
                </td>
            </tr>
            <?endif;?>
        </table>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config2" style="display:none;">
            <?if($pid):?>
            <tr>
                <td class="altbg1">����:</td>
                <td><?=$cate['name']?><input type="hidden" name="newcategory[pid]" value="<?=$pid?>" /></td>
            </tr>
            <?endif;?>
            <tr>
                <td class="altbg1" width="120">����:</td>
                <td width="*"><input type="text" name="newcategory[name]" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1">����:</td><td>
                <input type="text" name="newcategory[listorder]" class="txtbox2" value="0" /></td>
            </tr>
        </table>
    </div>
	<center>
        <?if($total):?>
		<input type="hidden" name="dosubmit" value="yes" />
		<input type="hidden" name="op" value="update" />
        <?endif;?>
        <button type="button" class="btn" onclick="easy_submit('myform','update',null)">����/���²���</button>&nbsp;
        <button type="button" class="btn" onclick="easy_submit('myform','delete','catids[]')">ɾ����ѡ</button>&nbsp;
        <?if($pid):?>
        <button type="button" class="btn" onclick="easy_submit('myform','rebuild','catids[]')">�ؽ�����</button>&nbsp;
        <button type="button" class="btn" onclick="document.location='<?=cpurl($module,$act,'list')?>';">������һ��</button>
        <?endif;?>
	</center>
</form>
</div>