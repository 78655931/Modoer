<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript">
function selectall(obj) {
    obj.focus();
    obj.select();
}
</script>
<div id="body">
    <div class="space">
        <div class="subtitle">���ô���</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" valign="top" width="25%"><strong>���ص���:</strong>���� Modoer ϵͳ�ڵ������ݣ�����ģ��ĵ��ñ�ǩʵ�����ݵ��ü���ʾ�������� Modoer ϵͳ��ʹ�á�</td>
                <td width="*">
                    <div><input type="input" class="txtbox" style="width:98%" onmouseover="selectall(this);" value="<!--{datacallname:<?=$detail['name']?>}-->" /></div>
                    ��
                    <div><input type="input" class="txtbox" style="width:98%" onmouseover="selectall(this);" value="<!--{datacall:<?=$callid?>}-->" /></div>
                </td>
            </tr>
            <tr>
                <td class="altbg1" valign="top"><strong>JS����</strong>�� Javascript ��ʽ�������ݣ�һ�����ڿ�վ������ϵͳ����ʱ��<br /><span class="font_1">ע��:js���ò��ܵ���ĳ���������ڰ�����̬��ǩ�����ݣ����������:{sid}���ද̬��ǩ, ��{dbpre}�����ǩ���⡣</span></td>
                <td>
                    <div><textarea style="width:98%;height:30px;font:12px Courier New;" onmouseover="selectall(this);"><script type="text/javascript" src="<?=$_CFG['siteurl']?>?act=js&callname=<?=rawurlencode($detail['name'])?>"></script></textarea></div>
                    ��
                    <div><textarea style="width:98%;height:30px;font:12px Courier New;" onmouseover="selectall(this);"><script type="text/javascript" src="<?=$_CFG['siteurl']?>?act=js&callid=<?=$callid?>"></script></textarea></div>
                </td>
            </tr>
        </table>
        <center><button type="button" onclick="history.go(-1);" class="btn" />������һҳ</button></center>
    </div>
</div>