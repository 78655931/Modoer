<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'post')?>">
    <input type="hidden" name="uid" value="<?=$_GET['uid']?>" />
    <div class="space">
        <div class="subtitle">用户资料修改</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg2"><td colspan="2"><strong>基本信息</strong></td></tr>
            <tr>
                <td class="altbg1" width="150">用户名:</td>
                <td width="*"><?=$detail['username']?></td>
            </tr>
            <tr>
                <td class="altbg1">E-mail:</td>
                <td><input type="text" name="member[email]" value="<?=$detail['email']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1">手机号码:</td>
                <td><input type="text" name="member[mobile]" value="<?=$detail['mobile']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1">支付宝：</td>
                <td><input type="text" name="profile[alipay]" value="<?=$detail['alipay']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1">用户组:</td>
                <?php $gid = $UP->point_by_usergroup($detail['point']);?>
                <td><select name="member[groupid]">
                    <?=form_member_usergroup($detail['groupid'],array('system','special'))?>
                    <option value="<?=$gid?>"<?if($gid==$detail['groupid'])echo' selected="selected"';?>><?=template_print('member','group',array('groupid'=>$gid))?></option>
                </select>&nbsp;<span class="font_2">普通用户组会随着等级积分自动变化，特殊和系统用户组不会。</span></td>
            </tr>
            <tr>
                <td class="altbg1">会员组到期时间:</td>
                <td><input type="text" name="member[nexttime]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="txtbox2" value="<?=$detail['nexttime']>0?date('Y-m-d H:i',$detail['nexttime']):''?>" />&nbsp;格式:YYYY-MM-DD<span class="font_2">仅特殊用户组有效</span></td>
            </tr>
            <tr>
                <td class="altbg1">到期后转入组:</td>
                <td><select name="member[nextgroupid]">
                    <option value="10">普通用户</option>
                    <?=form_member_usergroup($detail['nextgroupid'],array('system','special'))?>
                </select></td>
            </tr>
            <?if($passport):?>
            <tr>
                <td class="altbg1">已绑定账号:</td>
                <td>
                    <?if($passport) foreach($passport as $key=>$val):?>
                        <input type="hidden" name="passport[<?=$key?>][psname]" value="<?=$key?>">
                        <input type="checkbox" value="1" id="<?=$key?>" name="passport[<?=$key?>][enable]" checked="checked"><label for="<?=$key?>"><?=$MOD['passport_'.$key.'_title']?></label>
                    <?endforeach;?>
                    &nbsp;<span class="font_2">仅取消勾选即解除绑定，提交后不可恢复
                </td>
            </tr> 
            <?endif;?>   
            <tr>
                <td class="altbg1">真实姓名：</td>
                <td><input type="text" name="profile[realname]" value="<?=$detail[realname]?>" class="txtbox2"></td>
            </tr>
            <tr>
                <td class="altbg1">性别：</td>
                <td>
                    <select name="profile[gender]">
                        <option value="0">保密</option>
                        <option value="1"<?if($detail[gender]==='1')echo'selected="selected"'?>>男性</option>
                        <option value="2"<?if($detail[gender]==='2')echo'selected="selected"'?>>女性</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">生日：</td>
                <td><input type="text" name="profile[birthday]" value="<?=$detail[birthday]?>" class="txtbox2" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"></td>
            </tr>
            <tr>
                <td class="altbg1">QQ：</td>
                <td><input type="text" name="profile[qq]" value="<?=$detail[qq]?>" class="txtbox2"></td>
            </tr>
            </tr>
            <tr>
                <td class="altbg1">MSN：</td>
                <td><input type="text" name="profile[msn]" value="<?=$detail[msn]?>" class="txtbox2"></td>
            </tr>
            </tr>
            <tr>
                <td class="altbg1">邮寄地址：</td>
                <td><input type="text" name="profile[address]" value="<?=$detail[address]?>" class="txtbox2"></td>
            </tr>
            <tr>
                <td class="altbg1">邮编：</td>
                <td><input type="text" name="profile[zipcode]" value="<?=$detail[zipcode]?>" class="txtbox2"></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>密码修改</strong></td></tr>
            <tr>
                <td class="altbg1">新密码:</td>
                <td><input type="password" name="member[password]" class="txtbox2" />&nbsp;&nbsp;不修改，请留空</td>
            </tr>
            <tr>
                <td class="altbg1">再次输入密码:</td>
                <td><input type="password" name="member[password2]" class="txtbox2" /></td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <button type="submit" name="dosubmit" value="yes" class="btn">提交</button>&nbsp;
        <button type="button" onclick="history.go(-1);" class="btn">返回</button>
    </center>
</form>
</div>