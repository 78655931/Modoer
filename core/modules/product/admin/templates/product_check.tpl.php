<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act))?>
    <div class="space">
        <div class="subtitle">��Ʒ���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" trmouse="Y">
            <tr class="altbg1">
                <td width="25">ѡ</td>
                <td width="200">����</td>
                <td width="200">��������</td>
                <td width="*">���</td>
                <td width="110">�ύʱ��</td>
                <td width="60">����</td>
            </tr>
            <?if($total):?>
            <?while($val=$list->fetch_array()):?>
            <tr>
                <td><input type="checkbox" name="pids[]" value="<?=$val['pid']?>" /></td>
                <td><?=$val['subject']?></td>
                <td><a href="<?=url('item/detail/id/'.$val['sid'])?>" target="_blank"><?=$val['name'].$val['subname']?></a></td>
                <td src="<?=$val['thumb']?>" onmouseover="tip_start(this);"><?=$val['description']?></td>
                <td><?=date('Y-m-d H:i',$val['dateline'])?></td>
                <td><a href="<?=cpurl($module,$act,'edit',array('pid'=>$val['pid']))?>">�༭</a></td>
            </tr>
            <?endwhile;?>
            <tr class="altbg1">
                <td colspan="3">
                    <button type="button" onclick="checkbox_checked('pids[]');" class="btn2">ȫѡ</button>
                </td>
                <td colspan="3" style="text-align:right;"><?=$multipage?></td>
            </tr>
            <?else:?>
            <tr><td colspan="8">��ʱû����Ϣ</td></tr>
            <?endif?>
        </table>
    </div>
    <?if($total):?>
    <div class="multipage"><?=$multipage?></div>
    <center>
        <input type="hidden" name="dosubmit" value="yes" />
        <input type="hidden" name="op" value="delete" />
        <button type="button" class="btn" onclick="easy_submit('myform','checkup','pids[]')">���ͨ��</button>
        <button type="button" class="btn" onclick="easy_submit('myform','delete','pids[]')">ɾ����ѡ</button>
    </center>
    <?endif;?>
<?=form_end()?>
</div>