<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,'save'))?>
    <input type="hidden" name="cid" value="<?=$detail['cid']?>" />
    <div class="space">
        <div class="subtitle">�༭����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="120">����:</td>
                <td width="*"><input type="text" name="title" class="txtbox" value="<?=$detail['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td><?=form_radio('grade',array('0��','1��','2��','3��','4��','5��'),$detail['grade'])?></td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td><textarea name="content" style="width:400px;height:100px;"><?=$detail['content']?></textarea></td>
            </tr>
            <tr>
                <td class="altbg1">״̬</td>
                <td><?=form_radio('status',array('δ���','ͨ�����'),$detail['status'])?></td>
            </tr>
        </table>
    </div>
    <input type="hidden" name="forward" value="<?=get_forward()?>" />
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
    <?=form_button_return(lang('admincp_return'),'btn')?></center>
<?=form_end()?>
</div>