<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
    <form method="post" name="myform" action="<?=cpurl($module,$act,'import')?>" enctype="multipart/form-data" >
    <input type="hidden" name="op" value="<?=$op?>" />
    <div class="space">
        <div class="subtitle">导入调用数据库</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="30%"><strong>导入文件:</strong>导入的数据调用文件，此文件由导出功能导出。</td>
                <td width="*"><input type="file" name="importfile" /><br />
                <span class="font_1">注意：进行导入操作，将会清空本地的调用数据。</span>
                </td>
                <td width="20%" class="altbg1" style="text-align:center;"><button type="button" class="btn" onclick="window.open('<?=cpurl($module,$act,'export')?>');" /><b>导出调用数据</b></button></td>
            </tr>
        </table>
        <center>
            <button type="submit" name="dosubmit" value="yes" class="btn" />导入</button>&nbsp;
        </center>
    </div>
    </form>
</div>