<?php
(!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); 
$_G['loader']->variable('templates');
?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">界面设置</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>网站默认模板:</strong>选择一个网站的默认风格。</td>
                <td width="*"><select name="setting[templateid]">
                    <?=form_template('main',$config['templateid'])?>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>首页默认页面:</strong>可以设置不同的模块为首页。</td>
                <td width="*" id="td_setting_index_module">
    				<select id="setting_index_module" name="setting[index_module]">
    					<option value="index">默认首页</option>
    					<?=form_module_index($config['index_module'])?>
    				</select>

				</td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>网站头部菜单组:</strong>用于显示网站中头部的菜单信息，<span class="font_2">不支持多层分类</span>。</td>
                <td width="*">
				<select name="setting[main_menuid]">
					<option value="">==选择菜单组==</option>
					<?=form_menu_main($config['main_menuid'])?>
				</select>
				</td>
            </tr>
            <tr>
                <td class="altbg1" width="45%"><strong>网站底部菜单组:</strong>用于显示网站中底部的菜单信息，<span class="font_2">不支持多层分类</span>。</td>
                <td width="*">
				<select name="setting[foot_menuid]">
					<option value="">==选择菜单组==</option>
					<?=form_menu_main($config['foot_menuid'])?>
				</select>
				</td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong><?=lang('admincp_setting_basic_buildinfo')?></strong><?=lang('admincp_setting_basic_buildinfo_des')?>
                </td>
                <td><?=form_bool('setting[buildinfo]', $config['buildinfo'])?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1">
                    <strong><?=lang('admincp_setting_basic_copyright')?></strong><?=lang('admincp_setting_basic_copyright_des')?>
                </td>
                <td><?=form_textarea('setting[copyright]', _T($config['copyright']),5,50,'txtarea2')?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1">
                    <strong><?=lang('admincp_setting_basic_statement')?></strong><?=lang('admincp_setting_basic_statement_des')?>
                </td>
                <td><?=form_textarea('setting[statement]', _T($config['statement']),5,50,'txtarea2')?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1">
                    <strong><?=lang('admincp_setting_basic_statistics')?></strong><?=lang('admincp_setting_basic_statistics_des')?>
                </td>
                <td><?=form_textarea('setting[statistics]', _T($config['statistics']), 5, 50, 'txtarea2')?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1">
                    <strong><?=lang('admincp_setting_basic_sharecode')?></strong><?=lang('admincp_setting_basic_sharecode_des')?>
                </td>
                <td><?=form_textarea('setting[sharecode]', _T($config['sharecode']), 5, 50, 'txtarea2')?></td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>
<script type="text/javascript">
function ajax_index_module_page() {
    var module = $('#setting_index_module').val();
    $.post("<?=cpurl($module,$act,$op)?>", {do:"index_module_page",module_flag:module,in_ajax:1}, function(result) {
        if (is_message(result)) {
            myAlert(result);
        } else {
            alert(result);
        }
    });
}
</script>