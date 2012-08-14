<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<div class="space">
    <div class="subtitle">操作提示</div>
    <table class="maintable" border="0" cellspacing="0" cellpadding="0">
    	<tr><td>Modoer 本身没有附带 UCenter 客户端包，请到 www.comsenz.com 下载客户端包，请将客户端包以 "uc_client" 为名称存放在 Modoer 系统的根目录，如果您的论坛为 Discuz 6.1.0 则直接将 Discuz 下的 "uc_client" 文件夹复制到 Modoer 根目录下即可。填写下列配置信息，使 UCenter 的通信密匙与 UCenter Server 端设置一致，服务器端安装请查阅 UCenter 安装手册或到<a href="http://www.modoer.com/bbs" target="_blank">论坛</a>求助。</td></tr>
    </table>
</div>
<?if(!is__writable(MUDDER_ROOT . 'uc_client' . DS . 'data' . DS . 'cache')):?>
    <div class="space">
        <div class="subtitle"><?=lang('admincp_cphome_system_msg_title')?></div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td>
                    <span class="font_4">文件夹 uc_client/data/cache 没有写入权限，将影响整合系统登录同步，请设置该文件夹和文件保持可读写状态。本提示同样适用于其他系统下的uc_client</span>
                </td>
            </tr>
        </table>
    </div>
<?endif;?>
<form method="post" action="<?=cpurl($module,$act)?>">
    <div class="space">
        <div class="subtitle">UCenter</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="45%" class="altbg1"><strong>开启Ucenter整合:</strong>启用前，请确保本客户端与服务器端都安装可使用。</td>
                <td width="55%"><?=form_bool('modcfg[uc_enable]', $modcfg['uc_enable']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用积分兑换功能:</strong>启用前，请确保UCenter正常运作，积分兑换不能同步，请在UCenter服务器端积分兑换处多次点击同步设置。</td>
                <td><?=form_bool('modcfg[uc_exange]', $modcfg['uc_exange']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用事件Feed推送到UC:</strong>开启本功能，可将会员在Modoer上的操作事件显示到 UCHome/Discuz!X 中。</td>
                <td><?=form_bool('modcfg[uc_feed]', $modcfg['uc_feed']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>启用个人主页跳转UCHome:</strong>启用后，点击Modoer的会员空间将直接跳转至 UCHome/Discuz!X 的个人主页。</td>
                <td><?=form_bool('modcfg[uc_uch]', $modcfg['uc_uch']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCHome的网址:</strong>填写UChome的URL地址，最后请不要添加"/"。本设置在启用跳转UChmoe功能后有效。</td>
                <td><input type="text" class="txtbox2" name="modcfg[uc_uch_url]" value="<?=$modcfg['uc_uch_url']?>" /></td>
            </tr>
        </table>
        <div class="subtitle">链接配置</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <?if($disabled) {?>
            <tr><td class="altbg2" colspan="2"><span class="font_1">配置文件不可写，无法编辑配置信息。</span></td></tr>
            <? } ?>
            <tr>
                <td width="45%" class="altbg1"><strong>UCenter 应用 ID:</strong>该值为当前系统在 UCenter 的应用 ID</td>
                <td width="55%"><input type="text" class="txtbox2" name="uc[appid]" value="<?=UC_APPID?>" <?=$disabled?> /></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 通信密钥:</strong>通信密钥用于在 UCenter 和 Modoer 之间传输信息的加密，可包含任何字母及数字，请在 UCenter 与 Modoer 设置完全相同的通讯密钥，以确保两套系统能够正常通信</td>
                <td><input type="text" class="txtbox2" name="uc[key]" value="<?=UC_KEY?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 服务器端访问地址:</strong>在您 UCenter 地址或者目录改变的情况下，修改此项<br />例如: http://www.sitename.com/uc_server (最后不要加'/')；discuz!X 默认是在http://www.discuzxsite.com/uc_server</td>
                <td><input type="text" class="txtbox2" name="uc[api]" value="<?=UC_API?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter IP地址:</strong>如果您的服务器无法通过域名访问 UCenter，可以输入 UCenter 服务器的 IP 地址</td>
                <td><input type="text" class="txtbox2" name="uc[ip]" value="<?=UC_IP?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 连接方式:</strong>请根据您的服务器网络环境选择适当的连接方式</td>
                <td><input type="radio" name="uc[connect]" value="mysql" <?if(UC_CONNECT=='mysql') echo 'checked="checked"' ?><?=$disabled?>/> 数据库方式&nbsp;&nbsp;<input type="radio" name="uc[connect]" value="" <?if(UC_CONNECT==NULL) echo 'checked="checked"' ?> <?=$disabled?>/> 接口方式</td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 数据库服务器:</strong>可以是本地也可以是远程数据库服务器，如果 MySQL 端口不是默认的 3306，请填写如下形式：127.0.0.1:6033</td>
                <td><input type="text" class="txtbox2" name="uc[dbhost]" value="<?=UC_DBHOST?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 数据库用户名:</strong>UCenter 连接方式为数据库方式时有效</td>
                <td><input type="text" class="txtbox2" name="uc[dbuser]" value="<?=UC_DBUSER?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 数据库密码:</strong>UCenter 连接方式为数据库方式时有效</td>
                <td><input type="text" class="txtbox2" name="uc[dbpw]" value="<?=$ucdbpw?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 数据库名:</strong>UCenter的数据库名称，填写错误，将无法注册和登录，出现SQL错误</td>
                <td><input type="text" class="txtbox2" name="uc[dbname]" value="<?=UC_DBNAME?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 数据库字符集:</strong>UCenter 连接方式为数据库方式时有效</td>
                <td><input type="text" class="txtbox2" name="uc[dbcharset]" value="<?=UC_DBCHARSET?>" <?=$disabled?>/></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>UCenter 表前缀:</strong>请仔细确认UCenter表前缀，Discuz!X的UCenter默认表前缀为 pre_ucenter_，如果是discuz一体包默认是 dbc_uc_，其他默认为 uc_</td>
                <td><input type="text" class="txtbox2" name="uc[dbtablepre]" value="<?=$uctablepre?>" <?=$disabled?> /></td>
            </tr>
        </table>
        <center><input type="submit" name="dosubmit" value=" 提交 " class="btn" /></center>
    </div>
</form>
</div>