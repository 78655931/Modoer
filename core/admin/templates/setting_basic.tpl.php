<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_setting_basic_caption')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1">
                    <strong><?=lang('admincp_setting_basic_sitename')?></strong><?=lang('admincp_setting_basic_sitename_des')?>
                </td>
                <td width="*">
                    <?=form_input('setting[sitename]', $config['sitename'], 'txtbox')?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong><?=lang('admincp_setting_basic_siteurl')?></strong><?=lang('admincp_setting_basic_siteurl_des')?>
                </td>
                <td><?=form_input('setting[siteurl]', $config['siteurl'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong><?=lang('admincp_setting_basic_city_ip_location')?></strong><?=lang('admincp_setting_basic_city_ip_location_des')?>
                </td>
                <td><?=form_bool('setting[city_ip_location]', $config['city_ip_location'])?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong><?=lang('admincp_setting_basic_city_default')?></strong><?=lang('admincp_setting_basic_city_default_des')?>
                </td>
                <td>
                    <select name="setting[city_id]">
						<option value="" selected="selected">==<?=lang('admincp_setting_basic_city_default_sel')?>==</option>
						<?=form_city($config['city_id'])?>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong><?=lang('admincp_setting_basic_icpno')?></strong><?=lang('admincp_setting_basic_icpno_des')?>
                </td>
                <td><?=form_input('setting[icpno]', $config['icpno'], 'txtbox')?></td>
            </tr>
            <tr>
                <td class="altbg1">
                    <strong><?=lang('admincp_setting_basic_closesite')?></strong><?=lang('admincp_setting_basic_closesite_des')?>
                </td>
                <td><?=form_bool('setting[siteclose]', $config['siteclose'])?></td>
            </tr>
            <tr>
                <td valign="top" class="altbg1">
                    <strong><?=lang('admincp_setting_basic_closenote')?></strong><?=lang('admincp_setting_basic_closenote_des')?>
                </td>
                <td><?=form_textarea('setting[closenote]',_T($config['closenote']),5,50,'txtarea')?></td>
            </tr>
        </table>
        <center>
            <?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?>
        </center>
    </div>
<?=form_end()?>
</div>