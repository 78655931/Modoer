<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); ?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'post')?>">
    <input type="hidden" name="uid" value="<?=$_GET['uid']?>" />
    <div class="space">
        <div class="subtitle">�û������޸�</div>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr class="altbg2"><td colspan="2"><strong>������Ϣ</strong></td></tr>
            <tr>
                <td class="altbg1" width="150">�û���:</td>
                <td width="*"><?=$detail['username']?></td>
            </tr>
            <tr>
                <td class="altbg1">E-mail:</td>
                <td><input type="text" name="member[email]" value="<?=$detail['email']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1">�ֻ�����:</td>
                <td><input type="text" name="member[mobile]" value="<?=$detail['mobile']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1">֧������</td>
                <td><input type="text" name="profile[alipay]" value="<?=$detail['alipay']?>" class="txtbox2" /></td>
            </tr>
            <tr>
                <td class="altbg1">�û���:</td>
                <?php $gid = $UP->point_by_usergroup($detail['point']);?>
                <td><select name="member[groupid]">
                    <?=form_member_usergroup($detail['groupid'],array('system','special'))?>
                    <option value="<?=$gid?>"<?if($gid==$detail['groupid'])echo' selected="selected"';?>><?=template_print('member','group',array('groupid'=>$gid))?></option>
                </select>&nbsp;<span class="font_2">��ͨ�û�������ŵȼ������Զ��仯�������ϵͳ�û��鲻�ᡣ</span></td>
            </tr>
            <tr>
                <td class="altbg1">��Ա�鵽��ʱ��:</td>
                <td><input type="text" name="member[nexttime]" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})" class="txtbox2" value="<?=$detail['nexttime']>0?date('Y-m-d H:i',$detail['nexttime']):''?>" />&nbsp;��ʽ:YYYY-MM-DD<span class="font_2">�������û�����Ч</span></td>
            </tr>
            <tr>
                <td class="altbg1">���ں�ת����:</td>
                <td><select name="member[nextgroupid]">
                    <option value="10">��ͨ�û�</option>
                    <?=form_member_usergroup($detail['nextgroupid'],array('system','special'))?>
                </select></td>
            </tr>
            <?if($passport):?>
            <tr>
                <td class="altbg1">�Ѱ��˺�:</td>
                <td>
                    <?if($passport) foreach($passport as $key=>$val):?>
                        <input type="hidden" name="passport[<?=$key?>][psname]" value="<?=$key?>">
                        <input type="checkbox" value="1" id="<?=$key?>" name="passport[<?=$key?>][enable]" checked="checked"><label for="<?=$key?>"><?=$MOD['passport_'.$key.'_title']?></label>
                    <?endforeach;?>
                    &nbsp;<span class="font_2">��ȡ����ѡ������󶨣��ύ�󲻿ɻָ�
                </td>
            </tr> 
            <?endif;?>   
            <tr>
                <td class="altbg1">��ʵ������</td>
                <td><input type="text" name="profile[realname]" value="<?=$detail[realname]?>" class="txtbox2"></td>
            </tr>
            <tr>
                <td class="altbg1">�Ա�</td>
                <td>
                    <select name="profile[gender]">
                        <option value="0">����</option>
                        <option value="1"<?if($detail[gender]==='1')echo'selected="selected"'?>>����</option>
                        <option value="2"<?if($detail[gender]==='2')echo'selected="selected"'?>>Ů��</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td class="altbg1">���գ�</td>
                <td><input type="text" name="profile[birthday]" value="<?=$detail[birthday]?>" class="txtbox2" onfocus="WdatePicker({dateFmt:'yyyy-MM-dd'})"></td>
            </tr>
            <tr>
                <td class="altbg1">QQ��</td>
                <td><input type="text" name="profile[qq]" value="<?=$detail[qq]?>" class="txtbox2"></td>
            </tr>
            </tr>
            <tr>
                <td class="altbg1">MSN��</td>
                <td><input type="text" name="profile[msn]" value="<?=$detail[msn]?>" class="txtbox2"></td>
            </tr>
            </tr>
            <tr>
                <td class="altbg1">�ʼĵ�ַ��</td>
                <td><input type="text" name="profile[address]" value="<?=$detail[address]?>" class="txtbox2"></td>
            </tr>
            <tr>
                <td class="altbg1">�ʱࣺ</td>
                <td><input type="text" name="profile[zipcode]" value="<?=$detail[zipcode]?>" class="txtbox2"></td>
            </tr>
            <tr class="altbg2"><td colspan="2"><strong>�����޸�</strong></td></tr>
            <tr>
                <td class="altbg1">������:</td>
                <td><input type="password" name="member[password]" class="txtbox2" />&nbsp;&nbsp;���޸ģ�������</td>
            </tr>
            <tr>
                <td class="altbg1">�ٴ���������:</td>
                <td><input type="password" name="member[password2]" class="txtbox2" /></td>
            </tr>
        </table>
    </div>
    <center>
        <input type="hidden" name="forward" value="<?=get_forward()?>" />
        <button type="submit" name="dosubmit" value="yes" class="btn">�ύ</button>&nbsp;
        <button type="button" onclick="history.go(-1);" class="btn">����</button>
    </center>
</form>
</div>