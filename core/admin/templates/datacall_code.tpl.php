<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function selectall(obj) {
    obj.focus();
    obj.select();
}
</script>
<div id="body">
    <div class="space">
        <div class="subtitle">调用代码</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" valign="top" width="25%"><strong>本地调用:</strong>即在 Modoer 系统内调用数据，利用模板的调用标签实现数据调用及显示，仅限于 Modoer 系统内使用。</td>
                <td width="*">
                    <div><input type="input" class="txtbox" style="width:98%" onmouseover="selectall(this);" value="<!--{datacallname:<?=$detail['name']?>}-->" /></div>
                    或
                    <div><input type="input" class="txtbox" style="width:98%" onmouseover="selectall(this);" value="<!--{datacall:<?=$callid?>}-->" /></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>JS调用</strong>以 Javascript 方式调用数据，一般用于跨站或其他系统整合时。<br /><span class="font_1">注意:js调用不能调用某条调用项内包含动态标签的数据，例如包含了:{sid}这类动态标签, 但{dbpre}这个标签例外。</span></td>
                <td>
                    <div><textarea style="width:98%;height:30px;font:12px Courier New;" onmouseover="selectall(this);"><script type="text/javascript" src="<?=$_CFG['siteurl']?>?act=js&callname=<?=rawurlencode($detail['name'])?>"></script></textarea></div>
                    或
                    <div><textarea style="width:98%;height:30px;font:12px Courier New;" onmouseover="selectall(this);"><script type="text/javascript" src="<?=$_CFG['siteurl']?>?act=js&callid=<?=$callid?>"></script></textarea></div>
                </td>
            </tr>
        </table>
        <center><button type="button" onclick="history.go(-1);" class="btn" />返回上一页</button></center>
    </div>
</div>