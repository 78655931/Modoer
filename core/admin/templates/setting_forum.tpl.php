<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">������ʾ</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    	<tr><td>�뵽Modoer�ٷ���վ������̳���[Modoer���Ͽ���̨]�����ϴ�����̳FTP������̳��̨��װ���ٵ�Modoer��̨�������á�</td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">��̳����</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
			<?if(ini_get("allow_url_fopen")):?>
            <tr>
                <td width="45%" class="altbg1"><strong>������̳��������:</strong>����Modoer������̳�����������ϡ�</td>
                <td width="*"><?=form_bool('setting[forum]',$config['forum'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��̳��ַ:</strong>��д��̳���ڵ�ַ������üӡ�/�������磺http://bbs.modoer.com</td>
                <td>
                    <?=form_input('setting[forum_url]',$config['forum_url'],'txtbox2')?>&nbsp;
					<?if($config['forum_url'] && $config['forum_type'] && $config['forum_key']):?>
					<input type="button" value="ͨ�Ų���" onclick="forum_test('input_setting_forum_url')" class="btn2" />
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ͨ����Կ:</strong>����̳��̨Modoer�������һ��ͨ����Կ�������临�Ƶ����ȷ�����ߵ���Կһ�¡�</td>
                <td>
                    <?=form_input('setting[forum_key]',$config['forum_key'],'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>��̳����:</strong>ѡ����Ҫ���ϵ���̳�����͡�</td>
                <td width="*">
                    <?=form_select('setting[forum_type]',
					array('dz'=>'Discuz!','dzx'=>'Discuz!X'),
					$config['forum_type'])?>
                </td>
            </tr>
			<?else:?>
            <tr>
                <td>���ķ�������֧��PHP��Զ������(allow_url_fopen)���޷����ú�ʹ����̳�������Ϲ��ܣ�������PHP.INI�п�����</td>
            </tr>
			<?endif;?>
		</table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>
<script type="text/javascript">
function forum_test(id) {
	var input = $('#'+id);
	if(input.val()=='') {
		alert('δ��д��̳��ַ��');
		return;
	}
	$.post("<?=cpurl($module,$act,$op)?>", {do:"forum_test",url:input.val(),in_ajax:1}, function(result) {
		if (result.match(/\{\s+caption:".*",message:".*".*\s*\}/)) {
            myAlert(result);
        } else {
			alert(result);
		}
	});
}
</script>