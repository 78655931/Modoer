<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'post')?>">
    <div class="space">
        <div class="subtitle"><?=$subtitle?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($op=='add'):?>
            <tr><td>模板保存：<?=$filedir?>&nbsp;<input type="text" name="filename" value="<?=$filename?>" class="txtbox2" /></td></tr>
            <?else:?>
            <tr><td>保存文件：<?=$filename?></td></tr>
            <?endif;?>
            <tr>
                <td><textarea name="content" style="width:99%;height:400px;font-family:'Courier New';"><?=htmlspecialchars($contents)?></textarea></td>
            </tr>
            <?if($op=='edit' && basename($filename,$_G['cfg']['tplext']) == 'modoer_footer'):?>
            <tr><td style="color:red;text-align:center;">免费使用Modoer，请保留底部 Modoer 版权信息。Modoer因为有你的支持而强大！ </td></tr>
            <?endif;?>
        </table>
        <center>
            <?if($op=='add'):?>
            <input type="hidden" name="filedir" value="<?=$filedir?>" />
            <?else:?>
            <input type="hidden" name="filename" value="<?=$filename?>" />
            <?endif;?>
            <input type="hidden" name="forward" value="<?=get_forward();?>" />
            <input type="submit" name="dosubmit" value="<?=lang('global_submit')?>" class="btn" />&nbsp;
            <?=form_button_return(lang('global_return'),'btn')?>
        </center>
    </div>
</form>
</div>