<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    <tr><td>1. ��������ݱ������� Modoer ���ݵ��ļ���<br />2. ����������ļ����ݱ���ȫ���ǵ�ǰ Modoer ��ʹ�õ����ݱ�����ļ��ڵı�ǰ׺�͵�ǰϵͳ��ͬ�����������룻<br />3. ֻ����Ӿ��1���ļ���ʼ�ָ����ݡ�</td></tr>
    </table>
</div>
<form method="post" action="<?=cpurl($module,$act,'reset')?>">
    <div class="space">
        <div class="subtitle">���ݻָ�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="20">[<a href="javascript:;" onclick="allchecked();">ѡ</a>]</td>
                <td width="*">�ļ���</td>
                <td width="120">����ʱ��</td>
                <td width="120">�޸�ʱ��</td>
                <td width="100">�汾</td>
                <td width="50">���</td>
                <td width="100">�ļ���С</td>
                <td width="50">����</td>
            </tr>
            <? if($list) { foreach($list as $dbfile) { ?>
            <tr>
                <td><input type="checkbox" name="backfiles[]" value="<?=$dbfile['filename']?>" /></td>
                <td><a href="<?='./data/backupdata/'.$dbfile['filename']?>" target="_blank" title="�����Ҽ���Ŀ�����Ϊ�������ص�����"><?=$dbfile['filename']?></a></td>
                <td><?=$dbfile['bktime']?></td>
                <td><?=$dbfile['mtime']?></td>
                <td><?=$dbfile['version']?></td>
                <td><?=$dbfile['volume']?></td>
                <td><?=$dbfile['filesize']?></td>
                <td><? if($dbfile['volume'] == 1) { ?><a href="<?=cpurl($module,$act,'reset',array('filename'=>$dbfile['filename'],'doreset'=>'confirm'))?>" onclick="return confirm('��ȷ��Ҫ���뱸��������')">����</a><? } else { ?>���ɵ���<? } ?></td>
            </tr>
            <?}}?>
            <? if(!$list) {?><tr><td colspan="8">û�б����ļ���</td></tr><? } ?>
        </table>
        <center><input type="submit" name="dosubmit" value=" ɾ����ѡ " class="btn" /></center>
    </div>
</form>
</div>