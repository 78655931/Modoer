<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?if($_GET['op'] == 'add' || $_GET['op'] == 'edit'):?>
<script type="text/javascript">
function select_sort() {
    var sort = $('#sort').val();
    if(sort=='1') {
        $('#tr_sort1').css('display','none');
    } else if (sort=='2') {
        $('#tr_sort1').css('display','');
    }
}

$(document).ready(function() {
   select_sort(); 
});
</script>
<form method="post" action="<?=cpurl($module, $act, 'save')?>">
    <?if($_GET['op']=='edit'):?>
    <input type="hidden" name="gid" value="<?=$gid?>" />
    <?endif;?>
    <div class="space">
        <div class="subtitle">����/��������</div>
        <?if($_GET['op']=='edit'):?>
        <ul class="cptab">
            <li class="selected"><a href="<?=cpurl($module,'opt_group','edit',array('gid'=>$gid))?>" onfocus="this.blur()">�༭������</a></li>
            <li><a href="<?=cpurl($module,'opt','',array('gid'=>$gid))?>" onfocus="this.blur()">ֵ����</a></li>
        </ul>
        <?endif;?>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>������:</strong>���õ�����������ƣ�ֻ�ں�̨��ʾ</td>
                <td width="55%"><input type="text" name="name" class="txtbox2" value="<?=$detail['name']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��˵��:</strong>��ľ���˵����ּ�ں�̨��ʾ��û������</td>
                <td><input type="text" name="des" class="txtbox" value="<?=$detail['des']?>" /></td>
            </tr>
        </table>
        <center>
            <button type="submit" class="btn" name="dosubmit" value="yes"> �ύ </button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);"> ���� </button>
        </center>
    </div>
</form>
<? else: ?>
<form method="post" action="<?=cpurl($module, $act, $_GET['op'])?>">
    <div class="space">
        <div class="subtitle">�����������</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="40">��ID</td>
                <td width="20%">������</td>
                <td width="30%">˵��</td>
                <td width="*">����</td>
            </tr>
            <? if($list): ?>
            <? while($val = $list->fetch_array()): ?>
            <tr>
                <td><?=$val['gid']?></td>    
                <td><?=$val['name']?></td>
                <td><?=$val['des']?></td>
                <td>
                    <a href="<?=cpurl($module, 'opt','',array('gid'=>$val['gid']))?>">���������</a>&nbsp;
                    <a href="<?=cpurl($module, $act,'edit',array('gid'=>$val['gid']))?>">�༭</a>&nbsp;
                    <a href="<?=cpurl($module, $act,'delete',array('gid'=>$val['gid']))?>" onclick="return confirm('ɾ����������ǰ����ȷ�ϵ�ǰ��û�б�����ȷ���ʹ�ã���ȷ��Ҫ����ɾ��������')">ɾ��</a>
                </td>
            </tr>
            <? endwhile; ?>
            <? else: ?>
            <tr><td colspan="4">������Ϣ��</td></tr>
            <? endif; ?>
        </table>
        <center>
            <button type="button" class="btn" onclick="location='<?=cpurl($module, $act,'add')?>';"> ������������ </button>
        </center>
    </div>
</form>
<? endif; ?>
</div>