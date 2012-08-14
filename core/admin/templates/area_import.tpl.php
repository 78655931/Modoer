<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied');?>
<div id="body">
    <form method="post" action="<?=cpurl($module, $act, $op)?>" enctype="multipart/form-data">
        <div class="space">
            <div class="subtitle">导入地区数据</div>
            <table class="maintable" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td width="45%" class="altbg1"><strong>地区XML文件：</strong>选择从其他Modoer系统站导出的网站地区数据文件</td>
                    <td width="*"><input type="file" name="area_import_file"></td>
                </tr>
                <tr>
                    <td class="altbg1"><strong>已存在城市处理：</strong>在导入地区数据时，如果当前系统已存在这个城市，则进行选择性操作</td>
                    <td><?=form_radio('city_exists',array('0'=>'全部添加，忽略判断',1=>'跳过已存在的城市'),0)?></td>
                </tr>
            </table>
        </div>
        <center>
            <input type="submit" name="dosubmit" value=" 开始导入 " class="btn" />&nbsp;
            <input type="button" value=" 返回 " onclick="javascript:history.go(-1);" class="btn" />
        </center>
    </form>
</div>