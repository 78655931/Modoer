<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">操作提示</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    	<tr><td>请到Modoer官方网站下载论坛插件[Modoer整合控制台]，并上传到论坛FTP，在论坛后台安装，再到Modoer后台进行设置。</td></tr>
    </table>
</div>
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">论坛设置</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
			<?if(ini_get("allow_url_fopen")):?>
            <tr>
                <td width="45%" class="altbg1"><strong>开启论坛数据整合:</strong>允许Modoer链接论坛进行数据整合。</td>
                <td width="*"><?=form_bool('setting[forum]',$config['forum'])?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>论坛地址:</strong>填写论坛所在地址，最后不用加“/”，例如：http://bbs.modoer.com</td>
                <td>
                    <?=form_input('setting[forum_url]',$config['forum_url'],'txtbox2')?>&nbsp;
					<?if($config['forum_url'] && $config['forum_type'] && $config['forum_key']):?>
					<input type="button" value="通信测试" onclick="forum_test('input_setting_forum_url')" class="btn2" />
					<?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>通信密钥:</strong>在论坛后台Modoer插件设置一个通信密钥，并将其复制到这里，确保两边的密钥一致。</td>
                <td>
                    <?=form_input('setting[forum_key]',$config['forum_key'],'txtbox2')?>
                </td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>论坛类型:</strong>选择需要整合的论坛的类型。</td>
                <td width="*">
                    <?=form_select('setting[forum_type]',
					array('dz'=>'Discuz!','dzx'=>'Discuz!X'),
					$config['forum_type'])?>
                </td>
            </tr>
			<?else:?>
            <tr>
                <td>您的服务器不支持PHP打开远程连接(allow_url_fopen)，无法设置和使用论坛数据整合功能，请先在PHP.INI中开启。</td>
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
		alert('未填写论坛地址。');
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