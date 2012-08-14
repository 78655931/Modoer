<?php (!defined('IN_ADMIN') || !defined('IN_MUDDER')) && exit('Access Denied'); 
$_G['loader']->helper('form', 'member');
?>
<script type="text/javascript" src="./static/javascript/My97DatePicker/WdatePicker.js"></script>
<script type="text/javascript">
loadscript('mdialog');
function access_groups() {
    var value=0;
    $("[name=access]").each(function(i){ 
        if($(this).attr('checked')) {
            value = $(this).val();
        }
    }); 
    if(value=='2') {
        $('#access_groups').show();
    } else {
        $('#access_groups').hide();
    }
}
function task_save_succeed (data) {
    document.location = "<?=cpurl($module,$act)?>";
}
function load_typecfg(obj) {
    var flag = $(obj).val();
    if(!flag) return;
    document.location="<?=cpurl($module,$act,'add')?>&flag="+flag;
}
</script>
<div id="body">
<form method="post" action="<?=cpurl($module,$act,'save',array('in_ajax'=>1))?>&" name="postform" id="postform">
    <input type="hidden" name="do" value="<?=$op?>" />
    <?if($op=='edit'):?><input type="hidden" name="taskid" value="<?=$detail['taskid']?>" /><?endif;?>
    <div class="space">
        <div class="subtitle">��������</div>
        <ul class="cptab">
            <li><a href="<?=cpurl($module,$act)?>" onfocus="this.blur()">�����б�</a></li>
            <li><a href="<?=cpurl($module,$act,'type')?>" onfocus="this.blur()">��������</a></li>
            <li  class="selected"><a href="<?=cpurl($module,$act,'add')?>" onfocus="this.blur()">��������</a></li>
        </ul>
        <table class="maintable" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td class="altbg1"><strong>��������:</strong>ѡ��һ����վ�������ͣ�ÿ�����Ͷ��в�ͬ����ɷ�ʽ��������</td>
                <td><select name="taskflag" id="taskflag" style="width:200px;"<?if($op=='edit')echo'disabled'?> onchange="load_typecfg(this);">
                    <?=form_member_tasktype($taskflag)?>
                    </select>
                </td>
            </tr>
            <tr>
                <td width="45%" class="altbg1"><strong>��������:</strong>��ʾ��ǰ̨�����б��е����ơ�</td>
                <td width="*"><input type="text" name="title" value="<?=$detail['title']?>" class="txtbox2"></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>��������:</strong><span class="font_1">���ı���֧��HTML������ʾ</span><br />��д�������ϸ�������Ա��û�����ϸ���������������������̣�</td>
                <td><?=form_textarea('des',_T($detail['des']))?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���񿪷�ʱ��:</strong><span class="font_1">���ձ�ʾ������Ч�����Ͽ�ʼ</span><br />������״ο�ʼʱ�䣬�û�ֻ���ڵ�ǰ���õ�����ʱ���������뱾����</td>
                <td><?=form_datetime('starttime',$detail['starttime'],'Y-m-d H:i:s','txtbox3','onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm\'})"')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�������ʱ��:</strong><span class="font_1">���ձ�ʾ��ǰ����������Ч</span><br />����Ľ���ʱ�䣬�����û��ڵ�ǰ�������ʱ�����δ��ɵģ����Զ��ر�����</td>
                <td><?=form_datetime('endtime',$detail['endtime'],'Y-m-d H:i:s','txtbox3','onfocus="WdatePicker({dateFmt:\'yyyy-MM-dd HH:mm\'})"')?></td>
            </tr>
            <tr>
                <td class="altbg1"><strong>���������ں����ڵ�λ:</strong>�������ձ�ʾһ�������񣬷���Ϊ����������<br />Сʱ����ʾָ��Сʱ����ٴ����룬���������д�����Сʱ��<br />�죺��ʾָ�������ٴ����룬���������д���������<br />�ܣ���ʾ��ÿ��ָ��ʱ��(��һ������)��ſ��ٴ����룬���������д����1(��һ)��7(����)<br />�£���ʾ��ÿ��ָ�����ں�ſ��ٴ����룬���������д����1��29��30��31</td>
                <td><input type="text" name="period" value="<?=$detail['period']?$detail['period']:''?>" class="txtbox4">
                    <?=form_select('period_unit', array('Сʱ','��','��','��'), $detail['period_unit'])?>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>�������������ͺ�����:</strong>��Ա�������󣬽�������Ա�Ļ������͡�</td>
                <td><input type="text" name="point" value="<?=$detail['point']?>" class="txtbox4">
                    <select name="pointtype">
                    <?=form_member_pointgroup($detail['pointtype'])?>
                    </select>
                </td>
            </tr>
            <tr><td colspan="2" class="altbg2"><center><strong>������������</strong></center></td></tr>
            <tr>
                <td class="altbg1"><strong>�û�������:</strong>���ƻ�Ա������������Ȩ�ޣ�<br />ע���Ա����ʾȫ���Ѿ�ע��ɷ��ʵ��û�����ͨ��Ա����ȥ�����û���������û��飻ָ���û��飺ָ������ļ����û���</td>
                <td><?=form_radio('access', array('ע���û�','��ͨ�û�(������ϵͳ�û���)','ָ���û���'),$detail['access'],'onclick="access_groups(this.value);"')?></td>
            </tr>
            <tr id="access_groups">
                <td class="altbg1"><strong>ѡ��ָ����Ա��:</strong>�ɶ�ѡ����Ctrl+������������ѡ��</td>
                <td>
                    <?if($detail['access_groupids']) $detail['access_groupids']=explode(',',$detail['access_groupids']);?>
                    <select name="access_groupids[]" size="6" style="width:200px;" multiple>
                        <?=form_member_usergroup($detail['access_groupids'],null,true)?>
                    </select>
                    <script type="text/javascript">access_groups();</script>
                </td>
            </tr>
            <tr>
                <td class="altbg1"><strong>ע����Զ���������:</strong>�������ο�ע���Ϊ��Աʱ���Զ����뱾����<br />ע������û�����������ʱ�������ܽ�ʧЧ��</td>
                <td><?=form_bool('reg_automatic', (bool)$detail['reg_automatic'])?></td>
            </tr>
            <?if($form_condition):?>
            <tr id="condition"><td colspan="2" class="altbg2"><center><strong>�����������</strong></center></td></tr>
            <?=$form_condition?>
            <?endif;?>
        </table>
    </div>
    <center>
        <button type="button" class="btn" onclick="ajaxPost('postform', '', 'task_save_succeed');">�ύ</button>
        <button type="button" class="btn" onclick="document.location=document.referrer;">����</button>
    </center>
</form>
</div>