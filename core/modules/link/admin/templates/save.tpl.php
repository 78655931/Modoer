<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,'save'))?>
    <div class="space">
        <div class="subtitle">���/�༭����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" width="120">����:</td>
                <td width="*"><input type="text" name="title" class="txtbox" value="<?=$detail['title']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td><input type="text" name="displayorder" class="txtbox4" value="<?=$detail['displayorder']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">����:</td>
                <td><input type="text" name="link" class="txtbox" value="<?=$detail['link']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">Logo:</td>
                <td><input type="text" name="logo" class="txtbox" value="<?=$detail['logo']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">˵��:</td>
                <td><input type="text" name="des" class="txtbox" value="<?=$detail['des']?>" /></td>
            </tr>
            <tr>
                <td class="altbg1">״̬:</td>
                <td><?=form_radio('ischeck',array('δ���','ͨ�����'),$detail['ischeck']?$detail['ischeck']:1)?></td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="do" value="<?=$op?>" />
        <?if($op=='edit'):?>
        <input type="hidden" name="linkid" value="<?=$detail['linkid']?>" />
        <?endif;?>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>&nbsp;
        <?=form_button_return(lang('admincp_return'),'btn')?>
    </center>
<?=form_end()?>
</div>