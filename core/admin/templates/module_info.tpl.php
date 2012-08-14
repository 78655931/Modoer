<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">Ä£¿éÐÅÏ¢</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?foreach($moduleinfo as $key => $val) {?>
            <tr>
                <td width="20%" class="altbg1"><strong><?=$key?></strong></td>
                <td width="*" style="padding:5px;">
                <?if($key=='name'){?>
                    <input type="text" name="moduleinfo[name]" value="<?=$val?>" class="txtbox2" />
                <?}else{?>
                    <?=$val?>
                <?}?>
                </td>
            </tr>
            <?}?>
        </table>
        <center>
            <input type="hidden" name="moduleinfo[flag]" value="<?=$moduleinfo['flag']?>" />
            <input type="hidden" name="moduleinfo[moduleid]" value="<?=$moduleinfo['moduleid']?>" />
            <button type="submit" name="dosubmit" class="btn" value="yes"><?=lang('global_submit')?></button>&nbsp;
            <button type="button" class="btn" onclick="history.go(-1);"><?=lang('global_return')?></button>
        </center>
    </div>
</form>
</div>