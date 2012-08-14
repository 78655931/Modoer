<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'post');?>">
    <input type="hidden" name="adminid" value="<?=$_GET['adminid']?>" />
    <div class="space">
        <div class="subtitle">增加/编辑后台用户</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1" width="45%">用户名：</td>
                <td width="*">
                    <?if($admin->is_founder):?>
                    <input type="input" name="admin[adminname]" class="txtbox" value="<?=$detail['adminname']?>" />
                    <?else:?>
                    <?=$detail['adminname']?>
                    <?endif;?>
                </td>
            </tr>
            <tr>
                <td class="altbg1">E-mail：</td>
                <td><input type="text" name="admin[email]" value="<?=$detail['email']?>" class="txtbox"/></td>
            </tr>
            <tr><td colspan="2" class="altbg2"><strong>修改密码</strong></td></tr>
            <tr>
                <td class="altbg1">新密码：</td>
                <td><input type="password" name="admin[password]" class="txtbox" /><?if($op=='edit') {?>&nbsp;不修改则留空<? } ?></td>
            </tr>
            <tr>
                <td class="altbg1">确认密码：</td>
                <td><input type="password" name="password2" class="txtbox" /></td>
            </tr>
            <?if($admin->is_founder && $detail['is_founder']!='Y'):?>
            <tr><td colspan="2" class="altbg2"><strong>权限设置 </strong></td></tr>
            <tr>
                <td class="altbg1"><strong>禁止登录：</strong>禁止本帐号在后台登陆权限</td>
                <td><?=form_bool('admin[closed]', $detail['closed']);?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许操作的分站城市：</strong>设置后台帐号的分站管理权限。</td>
                <td>
                    <?$citys=$_G['loader']->variable('area');?>
                    <select id="mycitys" name="admin[mycitys][]" multiple="true">
                        <?foreach($citys as $k => $v):?>
                        <option value="<?=$v['aid']?>"<?=$mycitys&&in_array($v['aid'],$mycitys)?' selected="selected"':''?>><?=$v['name']?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#mycitys').mchecklist();
                    </script>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>允许操作的模块：</strong>设置后台帐号的管理权限。</td>
                <td>
                    <select id="mymodules" name="admin[mymodules][]" multiple="true">
                        <option value="modoer"<?=$mymodules&&in_array('modoer',$mymodules)?' selected="selected"':''?>>框架设置</option>
                        <?foreach($_G['modules'] as $k => $v):?>
                        <option value="<?=$v['flag']?>" <?=$mymodules&&in_array($v['flag'],$mymodules)?' selected="selected"':''?>><?=$v['name']?></option>
                        <?endforeach;?>
                    </select>
                    <script type="text/javascript">
                    $('#mymodules').mchecklist();
                    </script>
                </td>
            </tr>
            <?elseif(!$admin->is_founder):?>
            <tr>
                <td class="altbg1"><strong>允许操作的模块：</strong>设置后台帐号的管理权限。</td>
                <td>
                    <?php foreach($mymodules as $flag) :
                    echo $split . ($flag=='modoer'?'框架设置':$_G['modules'][$flag]['name']);
                    $split='，';
                    endforeach;?>
                </td>
            </tr>
            <?endif;?>
        </table>
        <center>
            <input type="hidden" name="do" value="<?=$op?>" />
            <input type="hidden" name="forward" value="<?=get_forward()?>" />
            <input type="submit" name="dosubmit" value=" 提交 " class="btn" />&nbsp;
            <input type="button" value=" 返回 " class="btn" onclick="history.go(-1);" />
        </center>
    </div>
</form>
</div>