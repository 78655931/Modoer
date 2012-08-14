<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,$op)?>">
    <input type="hidden" name="dobackup" value="yes" />
    <div class="space">
        <div class="subtitle">网站数据备份</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%">建表语句格式:</td>
                <td width="*"><select name="sqlcompat">
                    <option value="" selected="selected">默认</option>
                    <option value="MYSQL40">MySQL 3.23/4.0.x</option>
                    <option value="MYSQL41">MySQL 4.1.x/5.x</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">使用扩展插入(Extended Insert)方式:</td>
                <td><select name="extendins">
                    <option value="1">是</option>
                    <option value="0" selected="selected">否</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">添加字符集限定(SET NAMES):</td>
                <td><select name="addsetnames">
                    <option value="" selected="selected">默认</option>
                    <option value="1">是</option>
                    <option value="0">否</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">备份范围:</td>
                <td><select name="backuplimit">
                    <option value="modoer" selected="selected">Modoer数据表</option>
                    <option value="all">全部数据表</option>
                </select></td>
            </tr>
            <tr>
                <td class="altbg1">分卷备份 - 每个文件长度限制为:</td>
                <td><input type="text" name="sizelimit" value="2048" class="txtbox4" /> KB</td>
            </tr>
            <tr>
                <td class="altbg1">备份文件名:</td>
                <td><input type="text" name="filename" value="<?=$backfilename?>" class="txtbox2" /> .sql</td>
            </tr>
        </table>
    </div>
    <center><input type="submit" name="dosubmit" value=" 提交 " class="btn" /></center>
</form>
</div>