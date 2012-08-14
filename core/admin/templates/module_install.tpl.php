<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">模块安装</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?foreach($newmodule as $key => $val) {?>
            <tr>
                <td width="20%" class="altbg1"><strong><?=$key?></strong></td>
                <td width="*">
                    <input type="text" name="newmodule[<?=$key?>]" value="<?=$val?>" class="txtbox" readonly />
                </td>
            </tr>
            <?}?>
        </table>
        <input type="hidden" name="step" value="2" />
        <input type="hidden" name="newmodule[directory]" value="<?=$_POST['directory']?>" />
        <center>
            <button type="button" class="btn" onclick="history.go(-1);">上一步</button>&nbsp;
            <button type="submit" class="btn" value="modulessubmit">安装模块</button>
        </center>
    </div>
</form>
</div>