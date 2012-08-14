<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<?=form_begin(cpurl($module,$act,$op))?>
    <div class="space">
        <div class="subtitle">缓存设置</div>
        <ul class="cptab">
            <li class="selected" id="btn_config1"><a href="#" onclick="tabSelect(1,'config');" onfocus="this.blur()">数据缓存</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0" id="config1">
            <tr>
                <td class="altbg1" class="altbg1" width="50%"><strong>数据调用缓存存放路径</strong>请尽量存在放./data下，最后不要加斜杠"/"或"\"，<br />默认为：./data/datacall</td>
                <td width="*"><input type="text" name="setting[datacall_dir]" value="<?=$config['datacall_dir']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1" class="altbg1"><strong>自动清理数据缓存</strong>设置一个时间间隔来删除数据调用的缓存，时间间隔不宜过短，否则会增加服务器负担，0表示不启用</td>
                <td><input type="text" name="setting[datacall_clearinterval]" value="<?=(int)$config['datacall_clearinterval']?>" class="txtbox5" />&nbsp;小时</td>
            </tr>
            <tr>
                <td class="altbg1" class="altbg1"><strong>清理数据缓存的时间范围</strong>清除缓存生成时间的范围，仅在设置了自动清理数据缓存功能后有效，0表示删除全部缓存</td>
                <td><input type="text" name="setting[datacall_cleartime]" value="<?=(int)$config['datacall_cleartime']?>" class="txtbox5" />&nbsp;小时</td>
            </tr>
            <tr>
                <td class="altbg1" class="altbg1"><strong>搜索数量缓存时间</strong>对用户搜索的结果数量进行缓存，默认为60分钟。</td>
                <td><input type="text" name="setting[search_life]" value="<?=$config['search_life']>0?$config['search_life']:60?>" class="txtbox5" />&nbsp;分钟</td>
            </tr>
        </table>
    </div>
    <center><?=form_submit('dosubmit',lang('admincp_submit'),'yes','btn')?></center>
<?=form_end()?>
</div>