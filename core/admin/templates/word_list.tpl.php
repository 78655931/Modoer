<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>
        1. �����������ʱ�����滻Ϊ������д��<span class="font_1">{BLOCK}</span>����Ϊ��ֹ�ύ����д��<span class="font_1">{CHECK}</span>����Ϊ�ֹ���ˣ�����Ϊ�滻ֵ��<br />
        2. ʹ�ù���Ĵ�����������Ӱ�������Ч�ʡ�
    </td></tr>
    </table>
</div>
<form method="post" name="myform" action="<?=cpurl($module,$act)?>&">
    <div class="space">
        <div class="subtitle">�������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="30">&nbsp;<a href="javascript:checkbox_checked('ids[]');">ѡ</a></td>
                <td width="250">��������</td>
                <td width="250">�滻Ϊ</td>
                <td width="*">������</td>
            </tr>
            <?php if($total > 0) {?>
            <?php while($val = $list->fetch_array()) {?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?=$val['id']?>" /></td>
                <td><input type="text" name="words[<?=$val['id']?>][keyword]" class="txtbox3 width" value="<?=$val['keyword']?>" /></td>
                <td><input type="text" name="words[<?=$val['id']?>][expression]" class="txtbox3 width" value="<?=$val['expression']?>" /></td>
                <td><?=$val['admin']?></td>
            </tr>
            <? } $list->free_result(); ?>
            <? } else {?>
            <tr>
                <td colspan="4">������Ϣ��</td>
            </tr>
            <?}?>
            <tr class="altbg1">
                <td>����:</td>
                <td><input type="text" name="newword[keyword]" class="txtbox3 width" /></td>
                <td><input type="text" name="newword[expression]" class="txtbox3 width" /></td>
                <td>&nbsp;</td>
            </tr>
        </table>
        <?=$multipage?>
        <center>
            <input type="hidden" name="op" value="update" />
            <input type="hidden" name="dosubmit" value="yes" />
            <? if($total) : ?>
            <input type="button" name="dosubmit" value="ɾ����ѡ" class="btn" onclick="easy_submit('myform', 'delete', 'ids[]');" />
            <? endif; ?>
            <input type="button" name="dosubmit" value="�ύ����" class="btn" onclick="easy_submit('myform', 'update', null);" />
        </center>
    </div>
</form>
</div>